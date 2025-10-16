<?php

declare(strict_types=1);

use App\Models\RepCountryStatus;
use App\Models\RepresentingCountry;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\delete;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('can toggle status active state', function () {
    $representingCountry = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
        'is_active' => true,
    ]);

    post(route('representing-countries.toggle-status-active', $representingCountry), [
        'status_id' => $status->id,
    ])
        ->assertRedirect()
        ->assertSessionHas('success', 'Status updated successfully.');

    assertDatabaseHas('rep_country_status', [
        'id' => $status->id,
        'is_active' => false,
    ]);

    // Toggle back
    post(route('representing-countries.toggle-status-active', $representingCountry), [
        'status_id' => $status->id,
    ]);

    assertDatabaseHas('rep_country_status', [
        'id' => $status->id,
        'is_active' => true,
    ]);
});

it('validates status_id when toggling status', function () {
    $representingCountry = RepresentingCountry::factory()->create();

    post(route('representing-countries.toggle-status-active', $representingCountry), [])
        ->assertSessionHasErrors(['status_id']);
});

it('can update status custom name', function () {
    $representingCountry = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
        'status_name' => 'Application Received',
        'custom_name' => null,
    ]);

    put(route('representing-countries.update-status-name', $representingCountry), [
        'status_id' => $status->id,
        'custom_name' => 'Custom Application Phase',
    ])
        ->assertRedirect()
        ->assertSessionHas('success', 'Status name updated successfully.');

    assertDatabaseHas('rep_country_status', [
        'id' => $status->id,
        'custom_name' => 'Custom Application Phase',
    ]);
});

it('validates required fields when updating status name', function () {
    $representingCountry = RepresentingCountry::factory()->create();

    put(route('representing-countries.update-status-name', $representingCountry), [])
        ->assertSessionHasErrors(['status_id', 'custom_name']);
});

it('validates custom_name is a string', function () {
    $representingCountry = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
    ]);

    put(route('representing-countries.update-status-name', $representingCountry), [
        'status_id' => $status->id,
        'custom_name' => 123, // Invalid: must be string
    ])
        ->assertSessionHasErrors(['custom_name']);
});

it('requires authentication to toggle status', function () {
    auth()->logout();

    $representingCountry = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
    ]);

    post(route('representing-countries.toggle-status-active', $representingCountry), [
        'status_id' => $status->id,
    ])
        ->assertRedirect(route('login'));
});

it('requires authentication to update status name', function () {
    auth()->logout();

    $representingCountry = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
    ]);

    put(route('representing-countries.update-status-name', $representingCountry), [
        'status_id' => $status->id,
        'custom_name' => 'New Name',
    ])
        ->assertRedirect(route('login'));
});

it('can add a new status to a representing country', function () {
    $representingCountry = RepresentingCountry::factory()->create();

    $initialCount = $representingCountry->repCountryStatuses()->count();

    post(route('representing-countries.add-status', $representingCountry), [
        'status_name' => 'Document Submission',
    ])
        ->assertRedirect()
        ->assertSessionHas('success', 'Application process step added successfully.');

    $representingCountry->refresh();
    expect($representingCountry->repCountryStatuses)->toHaveCount($initialCount + 1);

    $newStatus = $representingCountry->repCountryStatuses()->latest('id')->first();
    expect($newStatus->status_name)->toBe('Document Submission');
    expect($newStatus->is_active)->toBeTrue();
});

it('validates status_name when adding status', function () {
    $representingCountry = RepresentingCountry::factory()->create();

    post(route('representing-countries.add-status', $representingCountry), [])
        ->assertSessionHasErrors(['status_name']);
});

it('validates status_name max length when adding status', function () {
    $representingCountry = RepresentingCountry::factory()->create();

    post(route('representing-countries.add-status', $representingCountry), [
        'status_name' => str_repeat('a', 256), // 256 characters
    ])
        ->assertSessionHasErrors(['status_name']);
});

it('requires authentication to add status', function () {
    auth()->logout();

    $representingCountry = RepresentingCountry::factory()->create();

    post(route('representing-countries.add-status', $representingCountry), [
        'status_name' => 'New Step',
    ])
        ->assertRedirect(route('login'));
});

it('can add a sub-status to a status', function () {
    $representingCountry = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
    ]);

    $initialCount = $status->subStatuses()->count();

    post(route('representing-countries.add-substatus', [$representingCountry, $status]), [
        'name' => 'Document Verification',
        'description' => 'Verify all submitted documents',
    ])
        ->assertRedirect()
        ->assertSessionHas('success', 'Sub-status added successfully.');

    $status->refresh();
    expect($status->subStatuses)->toHaveCount($initialCount + 1);

    $newSubStatus = $status->subStatuses()->latest('id')->first();
    expect($newSubStatus->name)->toBe('Document Verification');
    expect($newSubStatus->description)->toBe('Verify all submitted documents');
    expect($newSubStatus->is_active)->toBeTrue();
});

it('validates name when adding sub-status', function () {
    $representingCountry = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
    ]);

    post(route('representing-countries.add-substatus', [$representingCountry, $status]), [])
        ->assertSessionHasErrors(['name']);
});

it('validates name max length when adding sub-status', function () {
    $representingCountry = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
    ]);

    post(route('representing-countries.add-substatus', [$representingCountry, $status]), [
        'name' => str_repeat('a', 256),
    ])
        ->assertSessionHasErrors(['name']);
});

it('prevents adding sub-status to wrong representing country', function () {
    $representingCountry1 = RepresentingCountry::factory()->create();
    $representingCountry2 = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry2->id,
    ]);

    post(route('representing-countries.add-substatus', [$representingCountry1, $status]), [
        'name' => 'Test',
    ])
        ->assertNotFound();
});

it('requires authentication to add sub-status', function () {
    auth()->logout();

    $representingCountry = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
    ]);

    post(route('representing-countries.add-substatus', [$representingCountry, $status]), [
        'name' => 'New Sub-Status',
    ])
        ->assertRedirect(route('login'));
});

it('can update a sub-status', function () {
    $representingCountry = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
    ]);
    $subStatus = App\Models\SubStatus::factory()->create([
        'rep_country_status_id' => $status->id,
        'name' => 'Old Name',
        'description' => 'Old Description',
    ]);

    put(route('representing-countries.update-substatus', [$representingCountry, $status, $subStatus]), [
        'name' => 'Updated Name',
        'description' => 'Updated Description',
    ])
        ->assertRedirect()
        ->assertSessionHas('success', 'Sub-status updated successfully.');

    $subStatus->refresh();
    expect($subStatus->name)->toBe('Updated Name');
    expect($subStatus->description)->toBe('Updated Description');
});

it('validates name when updating sub-status', function () {
    $representingCountry = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
    ]);
    $subStatus = App\Models\SubStatus::factory()->create([
        'rep_country_status_id' => $status->id,
    ]);

    put(route('representing-countries.update-substatus', [$representingCountry, $status, $subStatus]), [])
        ->assertSessionHasErrors(['name']);
});

it('prevents updating sub-status from wrong representing country', function () {
    $representingCountry1 = RepresentingCountry::factory()->create();
    $representingCountry2 = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry2->id,
    ]);
    $subStatus = App\Models\SubStatus::factory()->create([
        'rep_country_status_id' => $status->id,
    ]);

    put(route('representing-countries.update-substatus', [$representingCountry1, $status, $subStatus]), [
        'name' => 'Updated',
    ])
        ->assertNotFound();
});

it('requires authentication to update sub-status', function () {
    auth()->logout();

    $representingCountry = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
    ]);
    $subStatus = App\Models\SubStatus::factory()->create([
        'rep_country_status_id' => $status->id,
    ]);

    put(route('representing-countries.update-substatus', [$representingCountry, $status, $subStatus]), [
        'name' => 'Updated',
    ])
        ->assertRedirect(route('login'));
});

it('can soft delete a status', function () {
    $representingCountry = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
    ]);

    delete(route('representing-countries.delete-status', [$representingCountry, $status]))
        ->assertRedirect()
        ->assertSessionHas('success', 'Status deleted successfully.');

    $status->refresh();
    expect($status->trashed())->toBeTrue();
    expect(RepCountryStatus::withTrashed()->find($status->id))->not->toBeNull();
});

it('soft deletes all sub-statuses when parent status is deleted', function () {
    $representingCountry = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
    ]);
    $subStatus1 = App\Models\SubStatus::factory()->create([
        'rep_country_status_id' => $status->id,
    ]);
    $subStatus2 = App\Models\SubStatus::factory()->create([
        'rep_country_status_id' => $status->id,
    ]);

    delete(route('representing-countries.delete-status', [$representingCountry, $status]));

    $subStatus1->refresh();
    $subStatus2->refresh();
    expect($subStatus1->trashed())->toBeTrue();
    expect($subStatus2->trashed())->toBeTrue();
    expect(App\Models\SubStatus::withTrashed()->find($subStatus1->id))->not->toBeNull();
    expect(App\Models\SubStatus::withTrashed()->find($subStatus2->id))->not->toBeNull();
});

it('prevents deleting status from wrong representing country', function () {
    $representingCountry1 = RepresentingCountry::factory()->create();
    $representingCountry2 = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry2->id,
    ]);

    delete(route('representing-countries.delete-status', [$representingCountry1, $status]))
        ->assertNotFound();
});

it('requires authentication to delete status', function () {
    auth()->logout();

    $representingCountry = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
    ]);

    delete(route('representing-countries.delete-status', [$representingCountry, $status]))
        ->assertRedirect(route('login'));
});

it('can soft delete a sub-status', function () {
    $representingCountry = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
    ]);
    $subStatus = App\Models\SubStatus::factory()->create([
        'rep_country_status_id' => $status->id,
    ]);

    delete(route('representing-countries.delete-substatus', [$representingCountry, $status, $subStatus]))
        ->assertRedirect()
        ->assertSessionHas('success', 'Sub-status deleted successfully.');

    $subStatus->refresh();
    expect($subStatus->trashed())->toBeTrue();
    expect(App\Models\SubStatus::withTrashed()->find($subStatus->id))->not->toBeNull();
});

it('prevents deleting sub-status from wrong representing country', function () {
    $representingCountry1 = RepresentingCountry::factory()->create();
    $representingCountry2 = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry2->id,
    ]);
    $subStatus = App\Models\SubStatus::factory()->create([
        'rep_country_status_id' => $status->id,
    ]);

    delete(route('representing-countries.delete-substatus', [$representingCountry1, $status, $subStatus]))
        ->assertNotFound();
});

it('requires authentication to delete sub-status', function () {
    auth()->logout();

    $representingCountry = RepresentingCountry::factory()->create();
    $status = RepCountryStatus::factory()->create([
        'representing_country_id' => $representingCountry->id,
    ]);
    $subStatus = App\Models\SubStatus::factory()->create([
        'rep_country_status_id' => $status->id,
    ]);

    delete(route('representing-countries.delete-substatus', [$representingCountry, $status, $subStatus]))
        ->assertRedirect(route('login'));
});
