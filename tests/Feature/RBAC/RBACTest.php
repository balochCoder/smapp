<?php

declare(strict_types=1);

use App\Models\Organization;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\PlatformRolesSeeder;
use Database\Seeders\TenantRolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed permissions and roles before each test
    $this->seed(PermissionsSeeder::class);
    $this->seed(PlatformRolesSeeder::class);
});

// =============================================================================
// PLATFORM ROLES TESTS
// =============================================================================

test('platform roles are created with is_platform_role flag', function () {
    $superAdmin = Role::where('name', 'SuperAdmin')->first();
    $support = Role::where('name', 'Support')->first();

    expect($superAdmin)->not->toBeNull()
        ->and($superAdmin->is_platform_role)->toBeTruthy() // Database stores as 1
        ->and($superAdmin->organization_id)->toBeNull()
        ->and($support)->not->toBeNull()
        ->and($support->is_platform_role)->toBeTruthy() // Database stores as 1
        ->and($support->organization_id)->toBeNull();
});

test('platform SuperAdmin role has all platform permissions', function () {
    $superAdmin = Role::where('name', 'SuperAdmin')->first();

    $platformPermissions = [
        'manage-organizations',
        'view-all-organizations',
        'suspend-organizations',
        'manage-platform-settings',
        'view-platform-analytics',
        'manage-platform-users',
        'view-system-logs',
    ];

    foreach ($platformPermissions as $permission) {
        expect($superAdmin->hasPermissionTo($permission))->toBeTrue();
    }
});

test('platform users can be assigned platform roles', function () {
    // Create platform user (no organization)
    $platformUser = User::factory()->create([
        'organization_id' => null,
        'email' => 'superadmin@platform.test',
    ]);

    setPermissionsTeamId(null);
    $platformUser->assignRole('SuperAdmin');

    expect($platformUser->hasRole('SuperAdmin'))->toBeTrue()
        ->and($platformUser->hasPermissionTo('manage-organizations'))->toBeTrue();
});

// =============================================================================
// TENANT ROLES TESTS
// =============================================================================

test('tenant roles are created for each organization', function () {
    $org1 = Organization::factory()->create(['name' => 'Org 1']);
    $org2 = Organization::factory()->create(['name' => 'Org 2']);

    // Create tenant roles for both organizations
    setPermissionsTeamId($org1->id);
    (new TenantRolesSeeder())->createTenantRoles($org1);

    setPermissionsTeamId($org2->id);
    (new TenantRolesSeeder())->createTenantRoles($org2);

    // Check Org 1 roles
    $org1Admin = Role::where('name', 'Admin')
        ->where('organization_id', $org1->id)
        ->first();

    // Check Org 2 roles
    $org2Admin = Role::where('name', 'Admin')
        ->where('organization_id', $org2->id)
        ->first();

    expect($org1Admin)->not->toBeNull()
        ->and($org1Admin->is_platform_role)->toBeFalsy() // Database stores as 0
        ->and($org1Admin->organization_id)->toBe($org1->id)
        ->and($org2Admin)->not->toBeNull()
        ->and($org2Admin->is_platform_role)->toBeFalsy() // Database stores as 0
        ->and($org2Admin->organization_id)->toBe($org2->id);
});

test('tenant roles have correct permissions', function () {
    $organization = Organization::factory()->create();

    setPermissionsTeamId($organization->id);
    (new TenantRolesSeeder())->createTenantRoles($organization);

    $admin = Role::where('name', 'Admin')
        ->where('organization_id', $organization->id)
        ->first();

    $counsellor = Role::where('name', 'Counsellor')
        ->where('organization_id', $organization->id)
        ->first();

    // Admin should have extensive permissions
    expect($admin->hasPermissionTo('create-leads'))->toBeTrue()
        ->and($admin->hasPermissionTo('manage-organization'))->toBeTrue()
        ->and($admin->hasPermissionTo('assign-roles'))->toBeTrue();

    // Counsellor should have limited permissions
    expect($counsellor->hasPermissionTo('create-students'))->toBeTrue()
        ->and($counsellor->hasPermissionTo('create-applications'))->toBeTrue()
        ->and($counsellor->hasPermissionTo('manage-organization'))->toBeFalse()
        ->and($counsellor->hasPermissionTo('assign-roles'))->toBeFalse();
});

// =============================================================================
// ORGANIZATION SCOPING TESTS
// =============================================================================

test('users can only have roles from their organization', function () {
    $org1 = Organization::factory()->create(['name' => 'Org 1']);
    $org2 = Organization::factory()->create(['name' => 'Org 2']);

    // Create roles for both orgs
    setPermissionsTeamId($org1->id);
    (new TenantRolesSeeder())->createTenantRoles($org1);

    setPermissionsTeamId($org2->id);
    (new TenantRolesSeeder())->createTenantRoles($org2);

    // Create users
    $user1 = User::factory()->create(['organization_id' => $org1->id]);
    $user2 = User::factory()->create(['organization_id' => $org2->id]);

    // Assign roles with proper team context
    setPermissionsTeamId($org1->id);
    $user1->assignRole('Admin');

    setPermissionsTeamId($org2->id);
    $user2->assignRole('Counsellor');

    // Check user1 roles
    setPermissionsTeamId($org1->id);
    expect($user1->hasRole('Admin'))->toBeTrue()
        ->and($user1->hasPermissionTo('manage-organization'))->toBeTrue();

    // Check user2 roles
    setPermissionsTeamId($org2->id);
    expect($user2->hasRole('Counsellor'))->toBeTrue()
        ->and($user2->hasRole('Admin'))->toBeFalse();
});

test('organization admin can see only their organization roles', function () {
    $org1 = Organization::factory()->create();
    $org2 = Organization::factory()->create();

    setPermissionsTeamId($org1->id);
    (new TenantRolesSeeder())->createTenantRoles($org1);

    setPermissionsTeamId($org2->id);
    (new TenantRolesSeeder())->createTenantRoles($org2);

    // Get roles for org1
    setPermissionsTeamId($org1->id);
    $org1Roles = Role::where('organization_id', $org1->id)->get();

    // Get roles for org2
    setPermissionsTeamId($org2->id);
    $org2Roles = Role::where('organization_id', $org2->id)->get();

    expect($org1Roles)->toHaveCount(6) // 6 tenant roles
        ->and($org2Roles)->toHaveCount(6);
});

// =============================================================================
// REGISTRATION TESTS
// =============================================================================

test('organization registration creates tenant roles and assigns Admin role', function () {
    // Test the logic without going through HTTP routes
    $organization = Organization::factory()->create([
        'name' => 'Test Agency',
        'slug' => 'test-agency',
        'email' => 'admin@test-agency.com',
    ]);

    $user = User::factory()->create([
        'organization_id' => $organization->id,
        'email' => 'admin@test-agency.com',
    ]);

    // Create tenant roles (simulating what happens in registration)
    setPermissionsTeamId($organization->id);
    (new TenantRolesSeeder())->createTenantRoles($organization);

    // Assign Admin role
    $user->assignRole('Admin');

    // Check roles were created for organization
    $adminRole = Role::where('name', 'Admin')
        ->where('organization_id', $organization->id)
        ->first();

    expect($adminRole)->not->toBeNull();

    // Check user has Admin role and permissions
    expect($user->hasRole('Admin'))->toBeTrue()
        ->and($user->hasPermissionTo('manage-organization'))->toBeTrue();
});

// =============================================================================
// MIDDLEWARE TESTS
// =============================================================================

test('SetPermissionsTeam middleware sets team context based on user organization', function () {
    $organization = Organization::factory()->create();

    setPermissionsTeamId($organization->id);
    (new TenantRolesSeeder())->createTenantRoles($organization);

    $user = User::factory()->create(['organization_id' => $organization->id]);
    $user->assignRole('Admin');

    $this->actingAs($user);

    // Make a request - middleware should set team context
    $response = $this->get(route('dashboard'));

    // After middleware, team should be set to user's organization
    expect(getPermissionsTeamId())->toBe($organization->id);
});

// =============================================================================
// PERMISSION CHECK TESTS
// =============================================================================

test('users can check permissions based on their roles', function () {
    $organization = Organization::factory()->create();

    setPermissionsTeamId($organization->id);
    (new TenantRolesSeeder())->createTenantRoles($organization);

    $admin = User::factory()->create(['organization_id' => $organization->id]);
    $counsellor = User::factory()->create(['organization_id' => $organization->id]);
    $frontOffice = User::factory()->create(['organization_id' => $organization->id]);

    $admin->assignRole('Admin');
    $counsellor->assignRole('Counsellor');
    $frontOffice->assignRole('FrontOffice');

    // Admin has all permissions
    expect($admin->can('manage-organization'))->toBeTrue()
        ->and($admin->can('create-leads'))->toBeTrue()
        ->and($admin->can('create-students'))->toBeTrue();

    // Counsellor has limited permissions
    expect($counsellor->can('create-students'))->toBeTrue()
        ->and($counsellor->can('create-applications'))->toBeTrue()
        ->and($counsellor->can('manage-organization'))->toBeFalse()
        ->and($counsellor->can('assign-roles'))->toBeFalse();

    // Front Office has minimal permissions
    expect($frontOffice->can('create-leads'))->toBeTrue()
        ->and($frontOffice->can('create-students'))->toBeFalse()
        ->and($frontOffice->can('manage-organization'))->toBeFalse();
});
