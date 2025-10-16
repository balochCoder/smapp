<?php

declare(strict_types=1);

use App\Models\Country;
use App\Models\RepCountryStatus;
use App\Models\RepresentingCountry;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('displays the reorder page for a representing country', function () {
    actingAs($this->user);

    $country = Country::factory()->create();
    $representingCountry = RepresentingCountry::factory()->create([
        'country_id' => $country->id,
    ]);

    $status1 = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
        'status_name' => 'Application Submission',
        'order' => 1,
    ]);

    $status2 = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
        'status_name' => 'Offer Received',
        'order' => 2,
    ]);

    get(route('representing-countries.reorder', $representingCountry))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('representing-countries/reorder')
            ->has('representingCountry', fn ($country) => $country
                ->where('id', $representingCountry->id)
                ->has('country')
                ->has('rep_country_statuses', 2)
                ->etc()
            )
        );
});

it('can reorder statuses', function () {
    actingAs($this->user);

    $country = Country::factory()->create();
    $representingCountry = RepresentingCountry::factory()->create([
        'country_id' => $country->id,
    ]);

    $status1 = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
        'status_name' => 'Application Submission',
        'order' => 1,
    ]);

    $status2 = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
        'status_name' => 'Offer Received',
        'order' => 2,
    ]);

    $status3 = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
        'status_name' => 'Visa Application',
        'order' => 3,
    ]);

    // Reorder: status3 -> status1 -> status2
    post(route('representing-countries.update-order', $representingCountry), [
        'status_orders' => [
            ['id' => $status3->id, 'order' => 1],
            ['id' => $status1->id, 'order' => 2],
            ['id' => $status2->id, 'order' => 3],
        ],
    ])
        ->assertRedirect()
        ->assertSessionHas('success', 'Status order updated successfully.');

    assertDatabaseHas('rep_country_status', [
        'id' => $status3->id,
        'order' => 1,
    ]);

    assertDatabaseHas('rep_country_status', [
        'id' => $status1->id,
        'order' => 2,
    ]);

    assertDatabaseHas('rep_country_status', [
        'id' => $status2->id,
        'order' => 3,
    ]);
});

it('validates status_orders is required when updating order', function () {
    actingAs($this->user);

    $country = Country::factory()->create();
    $representingCountry = RepresentingCountry::factory()->create([
        'country_id' => $country->id,
    ]);

    post(route('representing-countries.update-order', $representingCountry), [])
        ->assertSessionHasErrors(['status_orders']);
});

it('validates status_orders must be an array', function () {
    actingAs($this->user);

    $country = Country::factory()->create();
    $representingCountry = RepresentingCountry::factory()->create([
        'country_id' => $country->id,
    ]);

    post(route('representing-countries.update-order', $representingCountry), [
        'status_orders' => 'not-an-array',
    ])
        ->assertSessionHasErrors(['status_orders']);
});

it('validates each status order has a valid id', function () {
    actingAs($this->user);

    $country = Country::factory()->create();
    $representingCountry = RepresentingCountry::factory()->create([
        'country_id' => $country->id,
    ]);

    post(route('representing-countries.update-order', $representingCountry), [
        'status_orders' => [
            ['id' => 99999, 'order' => 1],
        ],
    ])
        ->assertSessionHasErrors(['status_orders.0.id']);
});

it('validates each status order has a valid order number', function () {
    actingAs($this->user);

    $country = Country::factory()->create();
    $representingCountry = RepresentingCountry::factory()->create([
        'country_id' => $country->id,
    ]);

    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
        'order' => 1,
    ]);

    post(route('representing-countries.update-order', $representingCountry), [
        'status_orders' => [
            ['id' => $status->id, 'order' => 0], // Invalid: must be at least 1
        ],
    ])
        ->assertSessionHasErrors(['status_orders.0.order']);
});

it('requires authentication to access reorder page', function () {
    $country = Country::factory()->create();
    $representingCountry = RepresentingCountry::factory()->create([
        'country_id' => $country->id,
    ]);

    get(route('representing-countries.reorder', $representingCountry))
        ->assertRedirect(route('login'));
});

it('requires authentication to update order', function () {
    $country = Country::factory()->create();
    $representingCountry = RepresentingCountry::factory()->create([
        'country_id' => $country->id,
    ]);

    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
        'order' => 1,
    ]);

    post(route('representing-countries.update-order', $representingCountry), [
        'status_orders' => [
            ['id' => $status->id, 'order' => 1],
        ],
    ])
        ->assertRedirect(route('login'));
});

it('prevents reordering the New status', function () {
    actingAs($this->user);

    $country = Country::factory()->create();
    $representingCountry = RepresentingCountry::factory()->create([
        'country_id' => $country->id,
    ]);

    $newStatus = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
        'status_name' => 'New',
        'order' => 1,
    ]);

    $status2 = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
        'status_name' => 'Application Submitted',
        'order' => 2,
    ]);

    // Try to reorder: status2 first, New second (should be ignored)
    post(route('representing-countries.update-order', $representingCountry), [
        'status_orders' => [
            ['id' => $status2->id, 'order' => 1],
            ['id' => $newStatus->id, 'order' => 2],
        ],
    ])
        ->assertRedirect()
        ->assertSessionHas('success', 'Status order updated successfully.');

    // New status should keep its original order
    $newStatus->refresh();
    expect($newStatus->order)->toBe(1);

    // Other status should be updated
    $status2->refresh();
    expect($status2->order)->toBe(1);
});
