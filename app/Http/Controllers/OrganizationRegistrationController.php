<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\OrganizationRegistrationRequest;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

final class OrganizationRegistrationController extends Controller
{
    /**
     * Display the organization registration form.
     */
    public function create(): Response
    {
        return Inertia::render('auth/register-organization', [
            'subscriptionPlans' => [
                [
                    'value' => 'trial',
                    'name' => 'Trial',
                    'description' => '30-day free trial with all features',
                    'price' => 'Free for 30 days',
                    'features' => [
                        'All core features',
                        'Up to 5 users',
                        'Email support',
                        '30-day trial period',
                    ],
                ],
                [
                    'value' => 'basic',
                    'name' => 'Basic',
                    'description' => 'Perfect for small agencies',
                    'price' => '$49/month',
                    'features' => [
                        'All core features',
                        'Up to 10 users',
                        'Email support',
                        '100 active students',
                    ],
                ],
                [
                    'value' => 'premium',
                    'name' => 'Premium',
                    'description' => 'For growing agencies',
                    'price' => '$99/month',
                    'features' => [
                        'All core features',
                        'Up to 25 users',
                        'Priority support',
                        'Unlimited students',
                        'Advanced analytics',
                    ],
                ],
                [
                    'value' => 'enterprise',
                    'name' => 'Enterprise',
                    'description' => 'For large organizations',
                    'price' => 'Custom pricing',
                    'features' => [
                        'All premium features',
                        'Unlimited users',
                        '24/7 phone support',
                        'Custom integrations',
                        'Dedicated account manager',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Handle the organization registration.
     */
    public function store(OrganizationRegistrationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Create organization and admin user in a transaction
        DB::transaction(function () use ($validated) {
            // Create organization
            $organization = Organization::create([
                'name' => $validated['organization_name'],
                'slug' => $validated['organization_slug'],
                'email' => $validated['organization_email'],
                'phone' => $validated['organization_phone'] ?? null,
                'subscription_plan' => $validated['subscription_plan'],
                'subscription_expires_at' => $validated['subscription_plan'] === 'trial'
                    ? now()->addDays(30)
                    : null,
                'is_active' => true,
            ]);

            // Create admin user for the organization
            $user = User::create([
                'organization_id' => $organization->id,
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['admin_password']),
                'email_verified_at' => now(), // Auto-verify for now
            ]);

            // TODO: Assign admin role to user (requires RBAC implementation)
            // $user->assignRole('Admin');

            // Log the user in
            Auth::login($user);
        });

        return redirect()
            ->route('dashboard')
            ->with('success', 'Welcome! Your organization has been successfully created.');
    }
}
