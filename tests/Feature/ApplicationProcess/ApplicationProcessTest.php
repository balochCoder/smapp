<?php

declare(strict_types=1);

use App\Models\ApplicationProcess;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('can display the application processes index page', function () {
    // Count existing processes
    $existing = ApplicationProcess::count();

    ApplicationProcess::factory()->count(3)->create();

    $response = $this->get(route('application-processes.index'));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('application-processes/index')
        ->has('processes', $existing + 3));
});

it('can display the create page', function () {
    $response = $this->get(route('application-processes.create'));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('application-processes/create'));
});

it('can create an application process', function () {
    $data = [
        'name' => 'New Process',
        'color' => 'blue',
        'order' => 1,
    ];

    $response = $this->post(route('application-processes.store'), $data);

    $response->assertRedirect();

    assertDatabaseHas('application_processes', [
        'name' => 'New Process',
        'color' => 'blue',
        'order' => 1,
    ]);
});

it('validates required fields when creating', function () {
    $response = $this->post(route('application-processes.store'), []);

    $response->assertSessionHasErrors(['name']);
});

it('can show an application process', function () {
    $process = ApplicationProcess::factory()->create();

    $response = $this->get(route('application-processes.show', $process));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('application-processes/show')
        ->has('process'));
});

it('can display the edit page', function () {
    $process = ApplicationProcess::factory()->create();

    $response = $this->get(route('application-processes.edit', $process));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('application-processes/edit')
        ->has('process'));
});

it('can update an application process', function () {
    $process = ApplicationProcess::factory()->create([
        'name' => 'Old Name',
        'color' => 'blue',
    ]);

    $data = [
        'name' => 'Updated Name',
        'color' => 'red',
        'order' => 5,
    ];

    $response = $this->put(
        route('application-processes.update', $process),
        $data
    );

    $response->assertRedirect();

    assertDatabaseHas('application_processes', [
        'id' => $process->id,
        'name' => 'Updated Name',
        'color' => 'red',
        'order' => 5,
    ]);
});

it('can delete an application process', function () {
    $process = ApplicationProcess::factory()->create();

    $response = $this->delete(
        route('application-processes.destroy', $process)
    );

    $response->assertRedirect(route('application-processes.index'));

    expect($process->fresh()->trashed())->toBeTrue();
});

it('validates order is numeric', function () {
    $response = $this->post(route('application-processes.store'), [
        'name' => 'Test Process',
        'order' => 'not-a-number',
    ]);

    $response->assertSessionHasErrors(['order']);
});
