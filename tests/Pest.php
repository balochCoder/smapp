<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * Create an organization for testing.
 */
function createOrganization(array $attributes = []): App\Models\Organization
{
    return App\Models\Organization::factory()->create($attributes);
}

/**
 * Create a user for a specific organization.
 */
function createUserForOrganization(?App\Models\Organization $organization = null, array $attributes = []): App\Models\User
{
    $organization = $organization ?? createOrganization();

    return App\Models\User::factory()
        ->for($organization)
        ->create($attributes);
}

/**
 * Create a user and act as them in tests.
 */
function actingAsUserWithOrganization(?App\Models\Organization $organization = null, array $userAttributes = []): App\Models\User
{
    $user = createUserForOrganization($organization, $userAttributes);

    test()->actingAs($user);

    return $user;
}
