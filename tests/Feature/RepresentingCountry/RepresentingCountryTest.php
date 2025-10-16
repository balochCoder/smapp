<?php

declare(strict_types=1);

use App\Models\ApplicationProcess;
use App\Models\Country;
use App\Models\RepCountryStatus;
use App\Models\RepresentingCountry;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('can display the representing countries index page', function () {
    $countries = RepresentingCountry::factory()
        ->count(3)
        ->create();

    $response = $this->get(route('representing-countries.index'));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('representing-countries/index')
        ->has('representingCountries.data', 3));
});

it('can display the create page', function () {
    $response = $this->get(route('representing-countries.create'));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('representing-countries/create')
        ->has('countries')
        ->has('applicationProcesses'));
});

it('can create a new representing country', function () {
    $country = Country::factory()->create();
    $processes = ApplicationProcess::factory()->count(3)->create();

    $data = [
        'country_id' => $country->id,
        'monthly_living_cost' => 1200.50,
        'visa_requirements' => 'Student visa required',
        'part_time_work_details' => 'Can work 20 hours per week',
        'country_benefits' => 'Great education system',
        'is_active' => true,
        'application_process_ids' => $processes->pluck('id')->toArray(),
    ];

    $response = $this->post(route('representing-countries.store'), $data);

    $response->assertRedirect();

    assertDatabaseHas('representing_countries', [
        'country_id' => $country->id,
        'monthly_living_cost' => 1200.50,
        'is_active' => true,
    ]);

    $repCountry = RepresentingCountry::where('country_id', $country->id)->first();
    expect($repCountry->repCountryStatuses)->toHaveCount(3);

    // Verify status records were created with correct status_name
    foreach ($processes as $process) {
        assertDatabaseHas('rep_country_status', [
            'representing_country_id' => $repCountry->id,
            'status_name' => $process->name,
            'is_active' => true,
        ]);
    }
});

it('validates required fields when creating', function () {
    $response = $this->post(route('representing-countries.store'), []);

    $response->assertSessionHasErrors(['country_id']);
});

it('prevents duplicate representing countries', function () {
    $country = Country::factory()->create();
    RepresentingCountry::factory()->create(['country_id' => $country->id]);

    $response = $this->post(route('representing-countries.store'), [
        'country_id' => $country->id,
        'monthly_living_cost' => 1000,
    ]);

    $response->assertSessionHasErrors(['country_id']);
});

it('can show a representing country', function () {
    $repCountry = RepresentingCountry::factory()->create();
    RepCountryStatus::factory()->count(2)->create([
        'representing_country_id' => $repCountry->id,
    ]);

    $response = $this->get(route('representing-countries.show', $repCountry));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('representing-countries/show')
        ->has('representingCountry'));
});

it('can display the edit page', function () {
    $repCountry = RepresentingCountry::factory()->create();

    $response = $this->get(route('representing-countries.edit', $repCountry));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('representing-countries/edit')
        ->has('representingCountry')
        ->has('applicationProcesses'));
});

it('can update a representing country', function () {
    $repCountry = RepresentingCountry::factory()->create([
        'monthly_living_cost' => 1000,
    ]);

    $newProcesses = ApplicationProcess::factory()->count(2)->create();

    $data = [
        'monthly_living_cost' => 1500.75,
        'visa_requirements' => 'Updated visa requirements',
        'is_active' => false,
        'application_process_ids' => $newProcesses->pluck('id')->toArray(),
    ];

    $response = $this->put(
        route('representing-countries.update', $repCountry),
        $data
    );

    $response->assertRedirect();

    assertDatabaseHas('representing_countries', [
        'id' => $repCountry->id,
        'monthly_living_cost' => 1500.75,
        'is_active' => false,
    ]);

    $repCountry->refresh();
    expect($repCountry->repCountryStatuses)->toHaveCount(2);
});

it('can delete a representing country', function () {
    $repCountry = RepresentingCountry::factory()->create();
    RepCountryStatus::factory()->count(2)->create([
        'representing_country_id' => $repCountry->id,
    ]);

    $response = $this->delete(
        route('representing-countries.destroy', $repCountry)
    );

    $response->assertRedirect(route('representing-countries.index'));

    expect($repCountry->fresh()->trashed())->toBeTrue();
});

it('validates monthly living cost is numeric', function () {
    $country = Country::factory()->create();

    $response = $this->post(route('representing-countries.store'), [
        'country_id' => $country->id,
        'monthly_living_cost' => 'invalid',
    ]);

    $response->assertSessionHasErrors(['monthly_living_cost']);
});

it('validates monthly living cost is not negative', function () {
    $country = Country::factory()->create();

    $response = $this->post(route('representing-countries.store'), [
        'country_id' => $country->id,
        'monthly_living_cost' => -100,
    ]);

    $response->assertSessionHasErrors(['monthly_living_cost']);
});

it('can toggle representing country active status', function () {
    $country = Country::factory()->create();
    $representingCountry = RepresentingCountry::factory()->create([
        'country_id' => $country->id,
        'is_active' => true,
    ]);

    $response = $this->post(route('representing-countries.toggle-active', $representingCountry));

    $response->assertRedirect()
        ->assertSessionHas('success', 'Representing country status updated successfully.');

    expect($representingCountry->fresh()->is_active)->toBeFalse();

    // Toggle back
    $this->post(route('representing-countries.toggle-active', $representingCountry));

    expect($representingCountry->fresh()->is_active)->toBeTrue();
});

it('requires authentication to toggle active status', function () {
    Auth::logout();

    $country = Country::factory()->create();
    $representingCountry = RepresentingCountry::factory()->create([
        'country_id' => $country->id,
    ]);

    $this->post(route('representing-countries.toggle-active', $representingCountry))
        ->assertRedirect(route('login'));
});

it('can create a representing country with currency', function () {
    $country = Country::factory()->create();

    $data = [
        'country_id' => $country->id,
        'monthly_living_cost' => 1500.00,
        'currency' => 'GBP',
        'visa_requirements' => 'Tier 4 Student Visa required',
    ];

    $response = $this->post(route('representing-countries.store'), $data);

    $response->assertRedirect();

    assertDatabaseHas('representing_countries', [
        'country_id' => $country->id,
        'monthly_living_cost' => 1500.00,
        'currency' => 'GBP',
    ]);
});

it('defaults to USD when currency is not provided', function () {
    $country = Country::factory()->create();

    $data = [
        'country_id' => $country->id,
        'monthly_living_cost' => 1000.00,
    ];

    $response = $this->post(route('representing-countries.store'), $data);

    $response->assertRedirect();

    $repCountry = RepresentingCountry::where('country_id', $country->id)->first();
    expect($repCountry->currency)->toBe('USD');
});

it('can update currency for a representing country', function () {
    $repCountry = RepresentingCountry::factory()->create([
        'currency' => 'USD',
        'monthly_living_cost' => 1200.00,
    ]);

    $data = [
        'currency' => 'EUR',
        'monthly_living_cost' => 1000.00,
    ];

    $response = $this->put(
        route('representing-countries.update', $repCountry),
        $data
    );

    $response->assertRedirect();

    assertDatabaseHas('representing_countries', [
        'id' => $repCountry->id,
        'currency' => 'EUR',
        'monthly_living_cost' => 1000.00,
    ]);
});

it('validates currency is 3 characters', function () {
    $country = Country::factory()->create();

    $response = $this->post(route('representing-countries.store'), [
        'country_id' => $country->id,
        'currency' => 'INVALID',
    ]);

    $response->assertSessionHasErrors(['currency']);
});

it('validates currency is 3 characters on update', function () {
    $repCountry = RepresentingCountry::factory()->create();

    $response = $this->put(
        route('representing-countries.update', $repCountry),
        [
            'currency' => 'AB',
        ]
    );

    $response->assertSessionHasErrors(['currency']);
});

it('creates status records when application process ids are provided', function () {
    $country = Country::factory()->create();
    
    // Create processes with specific orders to ensure predictable sorting
    $process1 = ApplicationProcess::factory()->create(['name' => 'Process One', 'order' => 1]);
    $process2 = ApplicationProcess::factory()->create(['name' => 'Process Two', 'order' => 2]);
    $process3 = ApplicationProcess::factory()->create(['name' => 'Process Three', 'order' => 3]);

    $data = [
        'country_id' => $country->id,
        'monthly_living_cost' => 1200.00,
        'application_process_ids' => [$process1->id, $process2->id, $process3->id],
    ];

    $response = $this->post(route('representing-countries.store'), $data);

    $response->assertRedirect();

    $repCountry = RepresentingCountry::where('country_id', $country->id)->first();

    // Verify all status records were created
    expect($repCountry->repCountryStatuses)->toHaveCount(3);

    // Verify each status was created correctly (order is based on ApplicationProcess order field)
    assertDatabaseHas('rep_country_status', [
        'representing_country_id' => $repCountry->id,
        'status_name' => 'Process One',
        'order' => 1,
        'is_active' => true,
    ]);

    assertDatabaseHas('rep_country_status', [
        'representing_country_id' => $repCountry->id,
        'status_name' => 'Process Two',
        'order' => 2,
        'is_active' => true,
    ]);

    assertDatabaseHas('rep_country_status', [
        'representing_country_id' => $repCountry->id,
        'status_name' => 'Process Three',
        'order' => 3,
        'is_active' => true,
    ]);
});

it('can update without changing application processes', function () {
    $repCountry = RepresentingCountry::factory()->create([
        'monthly_living_cost' => 1000,
    ]);

    $data = [
        'monthly_living_cost' => 1200.00,
        'visa_requirements' => 'Updated requirements',
    ];

    $response = $this->put(
        route('representing-countries.update', $repCountry),
        $data
    );

    $response->assertRedirect();

    assertDatabaseHas('representing_countries', [
        'id' => $repCountry->id,
        'monthly_living_cost' => 1200.00,
        'visa_requirements' => 'Updated requirements',
    ]);
});

it('requires authentication to create', function () {
    Auth::logout();

    $this->post(route('representing-countries.store'), [])
        ->assertRedirect(route('login'));
});

it('requires authentication to update', function () {
    Auth::logout();

    $repCountry = RepresentingCountry::factory()->create();

    $this->put(route('representing-countries.update', $repCountry), [])
        ->assertRedirect(route('login'));
});

it('requires authentication to delete', function () {
    Auth::logout();

    $repCountry = RepresentingCountry::factory()->create();

    $this->delete(route('representing-countries.destroy', $repCountry))
        ->assertRedirect(route('login'));
});

it('requires authentication to view show page', function () {
    Auth::logout();

    $repCountry = RepresentingCountry::factory()->create();

    $this->get(route('representing-countries.show', $repCountry))
        ->assertRedirect(route('login'));
});

it('requires authentication to view edit page', function () {
    Auth::logout();

    $repCountry = RepresentingCountry::factory()->create();

    $this->get(route('representing-countries.edit', $repCountry))
        ->assertRedirect(route('login'));
});

it('requires authentication to view create page', function () {
    Auth::logout();

    $this->get(route('representing-countries.create'))
        ->assertRedirect(route('login'));
});

it('requires authentication to view index page', function () {
    Auth::logout();

    $this->get(route('representing-countries.index'))
        ->assertRedirect(route('login'));
});
