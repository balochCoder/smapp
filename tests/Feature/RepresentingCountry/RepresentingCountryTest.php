<?php

declare(strict_types=1);

use App\Models\ApplicationProcess;
use App\Models\Country;
use App\Models\RepresentingCountry;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

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
    $processes = ApplicationProcess::factory()->count(3)->create(['parent_id' => null]);

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
    expect($repCountry->applicationProcesses)->toHaveCount(3);
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
    $repCountry = RepresentingCountry::factory()
        ->has(ApplicationProcess::factory()->count(2), 'applicationProcesses')
        ->create();

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

    $newProcesses = ApplicationProcess::factory()->count(2)->create(['parent_id' => null]);

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
    expect($repCountry->applicationProcesses)->toHaveCount(2);
});

it('can delete a representing country', function () {
    $repCountry = RepresentingCountry::factory()
        ->has(ApplicationProcess::factory()->count(2), 'applicationProcesses')
        ->create();

    $response = $this->delete(
        route('representing-countries.destroy', $repCountry)
    );

    $response->assertRedirect(route('representing-countries.index'));

    assertDatabaseMissing('representing_countries', [
        'id' => $repCountry->id,
    ]);
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
