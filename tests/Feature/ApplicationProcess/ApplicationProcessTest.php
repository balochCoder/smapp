<?php

declare(strict_types=1);

use App\Models\ApplicationProcess;
use App\Models\RepresentingCountry;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('can display the application processes index page', function () {
    $processes = ApplicationProcess::factory()
        ->count(3)
        ->create(['parent_id' => null]);

    $response = $this->get(route('application-processes.index'));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('application-processes/index')
        ->has('processes', 3));
});

it('can display the create page', function () {
    $response = $this->get(route('application-processes.create'));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('application-processes/create')
        ->has('parentProcesses')
        ->has('representingCountries'));
});

it('can create a main application process', function () {
    $countries = RepresentingCountry::factory()->count(2)->create();

    $data = [
        'name' => 'New Process',
        'description' => 'Process description',
        'order' => 1,
        'is_active' => true,
        'representing_country_ids' => $countries->pluck('id')->toArray(),
    ];

    $response = $this->post(route('application-processes.store'), $data);

    $response->assertRedirect();

    assertDatabaseHas('application_processes', [
        'name' => 'New Process',
        'parent_id' => null,
        'order' => 1,
    ]);

    $process = ApplicationProcess::where('name', 'New Process')->first();
    expect($process->representingCountries)->toHaveCount(2);
});

it('can create a sub-process', function () {
    $parent = ApplicationProcess::factory()->create(['parent_id' => null]);

    $data = [
        'parent_id' => $parent->id,
        'name' => 'Sub Process',
        'description' => 'Sub process description',
        'order' => 1,
        'is_active' => true,
    ];

    $response = $this->post(route('application-processes.store'), $data);

    $response->assertRedirect();

    assertDatabaseHas('application_processes', [
        'name' => 'Sub Process',
        'parent_id' => $parent->id,
    ]);
});

it('validates required fields when creating', function () {
    $response = $this->post(route('application-processes.store'), []);

    $response->assertSessionHasErrors(['name']);
});

it('can show an application process', function () {
    $process = ApplicationProcess::factory()
        ->has(ApplicationProcess::factory()->count(2)->state(['parent_id' => null]), 'subProcesses')
        ->create(['parent_id' => null]);

    $response = $this->get(route('application-processes.show', $process));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('application-processes/show')
        ->has('process'));
});

it('can display the edit page', function () {
    $process = ApplicationProcess::factory()->create(['parent_id' => null]);

    $response = $this->get(route('application-processes.edit', $process));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('application-processes/edit')
        ->has('process')
        ->has('parentProcesses')
        ->has('representingCountries'));
});

it('can update an application process', function () {
    $process = ApplicationProcess::factory()->create([
        'name' => 'Old Name',
        'parent_id' => null,
    ]);

    $countries = RepresentingCountry::factory()->count(2)->create();

    $data = [
        'name' => 'Updated Name',
        'description' => 'Updated description',
        'order' => 5,
        'is_active' => false,
        'representing_country_ids' => $countries->pluck('id')->toArray(),
    ];

    $response = $this->put(
        route('application-processes.update', $process),
        $data
    );

    $response->assertRedirect();

    assertDatabaseHas('application_processes', [
        'id' => $process->id,
        'name' => 'Updated Name',
        'order' => 5,
        'is_active' => false,
    ]);

    $process->refresh();
    expect($process->representingCountries)->toHaveCount(2);
});

it('can delete an application process', function () {
    $process = ApplicationProcess::factory()->create(['parent_id' => null]);

    $response = $this->delete(
        route('application-processes.destroy', $process)
    );

    $response->assertRedirect(route('application-processes.index'));

    assertDatabaseMissing('application_processes', [
        'id' => $process->id,
    ]);
});

it('deletes sub-processes when parent is deleted', function () {
    $parent = ApplicationProcess::factory()->create(['parent_id' => null]);
    $subProcess = ApplicationProcess::factory()->create([
        'parent_id' => $parent->id,
    ]);

    $this->delete(route('application-processes.destroy', $parent));

    assertDatabaseMissing('application_processes', ['id' => $parent->id]);
    assertDatabaseMissing('application_processes', ['id' => $subProcess->id]);
});

it('maintains hierarchical relationship between parent and sub-processes', function () {
    $parent = ApplicationProcess::factory()->create(['parent_id' => null]);
    $subProcess = ApplicationProcess::factory()->create([
        'parent_id' => $parent->id,
    ]);

    $parent->refresh();

    expect($parent->subProcesses)->toHaveCount(1);
    expect($parent->subProcesses->first()->id)->toBe($subProcess->id);
    expect($subProcess->parent->id)->toBe($parent->id);
});

it('can attach processes to multiple representing countries', function () {
    $process = ApplicationProcess::factory()->create(['parent_id' => null]);
    $countries = RepresentingCountry::factory()->count(3)->create();

    $process->representingCountries()->sync($countries->pluck('id'));

    expect($process->representingCountries)->toHaveCount(3);
});
