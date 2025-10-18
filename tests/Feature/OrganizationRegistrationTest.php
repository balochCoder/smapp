<?php

declare(strict_types=1);

use App\Models\Organization;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;

it('can display the organization registration page', function () {
    $response = $this->get(route('register.create'));

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('auth/register-organization')
        ->has('subscriptionPlans', 4));
});

it('can register a new organization with trial plan', function () {
    $data = [
        'organization_name' => 'Acme Education Consultancy',
        'organization_slug' => 'acme-education',
        'organization_email' => 'contact@acme.com',
        'organization_phone' => '+1234567890',
        'admin_name' => 'John Doe',
        'admin_email' => 'admin@acme.com',
        'admin_password' => 'password123',
        'admin_password_confirmation' => 'password123',
        'subscription_plan' => 'trial',
        'terms_accepted' => true,
    ];

    $response = $this->post(route('register.store'), $data);

    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('success');

    // Verify organization was created
    assertDatabaseHas('organizations', [
        'name' => 'Acme Education Consultancy',
        'slug' => 'acme-education',
        'email' => 'contact@acme.com',
        'subscription_plan' => 'trial',
        'is_active' => true,
    ]);

    // Verify admin user was created
    assertDatabaseHas('users', [
        'name' => 'John Doe',
        'email' => 'admin@acme.com',
    ]);

    // Verify user is linked to organization
    $organization = Organization::where('slug', 'acme-education')->first();
    $user = User::where('email', 'admin@acme.com')->first();
    expect($user->organization_id)->toBe($organization->id);

    // Verify user is authenticated
    $this->assertAuthenticated();
    expect(auth()->user()->id)->toBe($user->id);
});

it('can register with different subscription plans', function ($plan) {
    $data = [
        'organization_name' => 'Test Organization',
        'organization_slug' => 'test-org-'.uniqid(),
        'organization_email' => 'test@example.com',
        'admin_name' => 'Admin User',
        'admin_email' => 'admin-'.uniqid().'@example.com',
        'admin_password' => 'password123',
        'admin_password_confirmation' => 'password123',
        'subscription_plan' => $plan,
        'terms_accepted' => true,
    ];

    $response = $this->post(route('register.store'), $data);

    $response->assertRedirect(route('dashboard'));

    assertDatabaseHas('organizations', [
        'subscription_plan' => $plan,
    ]);
})->with(['trial', 'basic', 'premium', 'enterprise']);

it('validates required organization fields', function () {
    $response = $this->post(route('register.store'), []);

    $response->assertSessionHasErrors([
        'organization_name',
        'organization_slug',
        'organization_email',
        'admin_name',
        'admin_email',
        'admin_password',
        'subscription_plan',
        'terms_accepted',
    ]);
});

it('validates organization_slug format', function () {
    $response = $this->post(route('register.store'), [
        'organization_name' => 'Test Org',
        'organization_slug' => 'Invalid Slug!', // Invalid format
        'organization_email' => 'test@example.com',
        'admin_name' => 'Admin',
        'admin_email' => 'admin@example.com',
        'admin_password' => 'password123',
        'admin_password_confirmation' => 'password123',
        'subscription_plan' => 'trial',
        'terms_accepted' => true,
    ]);

    $response->assertSessionHasErrors('organization_slug');
});

it('validates organization_slug is unique', function () {
    Organization::factory()->create(['slug' => 'existing-slug']);

    $response = $this->post(route('register.store'), [
        'organization_name' => 'Test Org',
        'organization_slug' => 'existing-slug',
        'organization_email' => 'test@example.com',
        'admin_name' => 'Admin',
        'admin_email' => 'admin@example.com',
        'admin_password' => 'password123',
        'admin_password_confirmation' => 'password123',
        'subscription_plan' => 'trial',
        'terms_accepted' => true,
    ]);

    $response->assertSessionHasErrors('organization_slug');
});

it('validates admin_email is unique', function () {
    $organization = Organization::factory()->create();
    User::factory()->for($organization)->create(['email' => 'existing@example.com']);

    $response = $this->post(route('register.store'), [
        'organization_name' => 'Test Org',
        'organization_slug' => 'test-org',
        'organization_email' => 'test@example.com',
        'admin_name' => 'Admin',
        'admin_email' => 'existing@example.com',
        'admin_password' => 'password123',
        'admin_password_confirmation' => 'password123',
        'subscription_plan' => 'trial',
        'terms_accepted' => true,
    ]);

    $response->assertSessionHasErrors('admin_email');
});

it('validates password confirmation matches', function () {
    $response = $this->post(route('register.store'), [
        'organization_name' => 'Test Org',
        'organization_slug' => 'test-org',
        'organization_email' => 'test@example.com',
        'admin_name' => 'Admin',
        'admin_email' => 'admin@example.com',
        'admin_password' => 'password123',
        'admin_password_confirmation' => 'different-password',
        'subscription_plan' => 'trial',
        'terms_accepted' => true,
    ]);

    $response->assertSessionHasErrors('admin_password');
});

it('validates subscription_plan is valid', function () {
    $response = $this->post(route('register.store'), [
        'organization_name' => 'Test Org',
        'organization_slug' => 'test-org',
        'organization_email' => 'test@example.com',
        'admin_name' => 'Admin',
        'admin_email' => 'admin@example.com',
        'admin_password' => 'password123',
        'admin_password_confirmation' => 'password123',
        'subscription_plan' => 'invalid-plan',
        'terms_accepted' => true,
    ]);

    $response->assertSessionHasErrors('subscription_plan');
});

it('requires terms acceptance', function () {
    $response = $this->post(route('register.store'), [
        'organization_name' => 'Test Org',
        'organization_slug' => 'test-org',
        'organization_email' => 'test@example.com',
        'admin_name' => 'Admin',
        'admin_email' => 'admin@example.com',
        'admin_password' => 'password123',
        'admin_password_confirmation' => 'password123',
        'subscription_plan' => 'trial',
        'terms_accepted' => false,
    ]);

    $response->assertSessionHasErrors('terms_accepted');
});

it('sets trial expiration date for trial plans', function () {
    $data = [
        'organization_name' => 'Trial Org',
        'organization_slug' => 'trial-org',
        'organization_email' => 'trial@example.com',
        'admin_name' => 'Trial Admin',
        'admin_email' => 'trial-admin@example.com',
        'admin_password' => 'password123',
        'admin_password_confirmation' => 'password123',
        'subscription_plan' => 'trial',
        'terms_accepted' => true,
    ];

    $this->post(route('register.store'), $data);

    $organization = Organization::where('slug', 'trial-org')->first();
    expect($organization->subscription_expires_at)->not->toBeNull();
    expect(now()->diffInDays($organization->subscription_expires_at))->toBeGreaterThanOrEqual(29);
});

it('does not set expiration for non-trial plans', function ($plan) {
    $data = [
        'organization_name' => 'Test Org',
        'organization_slug' => 'test-org-'.uniqid(),
        'organization_email' => 'test@example.com',
        'admin_name' => 'Admin',
        'admin_email' => 'admin-'.uniqid().'@example.com',
        'admin_password' => 'password123',
        'admin_password_confirmation' => 'password123',
        'subscription_plan' => $plan,
        'terms_accepted' => true,
    ];

    $this->post(route('register.store'), $data);

    $organization = Organization::latest()->first();
    expect($organization->subscription_expires_at)->toBeNull();
})->with(['basic', 'premium', 'enterprise']);

it('creates organization and user in a transaction', function () {
    $data = [
        'organization_name' => 'Transaction Test',
        'organization_slug' => 'transaction-test',
        'organization_email' => 'transaction@example.com',
        'admin_name' => 'Admin',
        'admin_email' => 'admin@transaction.com',
        'admin_password' => 'password123',
        'admin_password_confirmation' => 'password123',
        'subscription_plan' => 'trial',
        'terms_accepted' => true,
    ];

    $this->post(route('register.store'), $data);

    // Both organization and user should exist
    $organization = Organization::where('slug', 'transaction-test')->first();
    $user = User::where('email', 'admin@transaction.com')->first();

    expect($organization)->not->toBeNull();
    expect($user)->not->toBeNull();
    expect($user->organization_id)->toBe($organization->id);
});

it('auto-verifies admin user email', function () {
    $data = [
        'organization_name' => 'Verified Org',
        'organization_slug' => 'verified-org',
        'organization_email' => 'verified@example.com',
        'admin_name' => 'Admin',
        'admin_email' => 'admin@verified.com',
        'admin_password' => 'password123',
        'admin_password_confirmation' => 'password123',
        'subscription_plan' => 'trial',
        'terms_accepted' => true,
    ];

    $this->post(route('register.store'), $data);

    $user = User::where('email', 'admin@verified.com')->first();
    expect($user->email_verified_at)->not->toBeNull();
});
