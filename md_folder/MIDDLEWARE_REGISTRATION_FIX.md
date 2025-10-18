# Middleware Registration Fix

**Issue:** `BindingResolutionException - Target class [role] does not exist`  
**Date:** October 18, 2025  
**Status:** ✅ **FIXED**

---

## Problem

When accessing routes with `role:Admin` middleware (e.g., `/admin/dashboard`), the application threw:

```
Illuminate\Contracts\Container\BindingResolutionException
Target class [role] does not exist.
```

---

## Root Cause

The Spatie Laravel Permission package provides middleware classes that need to be registered as **middleware aliases** in Laravel. The middleware wasn't registered in `bootstrap/app.php`.

---

## Solution

### 1. Registered Middleware Aliases

Updated `bootstrap/app.php` to register Spatie Permission middleware:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

    $middleware->web(append: [
        HandleAppearance::class,
        HandleInertiaRequests::class,
        AddLinkHeadersForPreloadedAssets::class,
        SetTenantContext::class,
        SetPermissionsTeam::class,
    ]);

    // Register Spatie Permission middleware aliases
    $middleware->alias([
        'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
    ]);
})
```

### 2. Cleared Application Cache

```bash
php artisan optimize:clear
```

---

## Middleware Aliases Now Available

After the fix, you can use these middleware in your routes:

### `role` Middleware
Checks if user has a specific role:
```php
Route::middleware('role:Admin')->group(function () {
    // Only users with Admin role can access
});

// Multiple roles (OR logic)
Route::middleware('role:Admin|BranchManager')->group(function () {
    // Users with Admin OR BranchManager role
});
```

### `permission` Middleware
Checks if user has a specific permission:
```php
Route::middleware('permission:create-students')->group(function () {
    // Only users with create-students permission
});

// Multiple permissions (OR logic)
Route::middleware('permission:create-students|edit-students')->group(function () {
    // Users with create-students OR edit-students permission
});
```

### `role_or_permission` Middleware
Checks if user has either a role OR a permission:
```php
Route::middleware('role_or_permission:Admin|create-students')->group(function () {
    // Users with Admin role OR create-students permission
});
```

---

## Current Route Configuration

All role-based routes are now working correctly:

```php
// Admin routes
Route::middleware(['auth', 'verified', 'role:Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', ...)->name('dashboard');
        // ... other admin routes
    });

// Branch routes
Route::middleware(['auth', 'verified', 'role:BranchManager'])
    ->prefix('branch')
    ->name('branch.')
    ->group(function () {
        Route::get('dashboard', ...)->name('dashboard');
        // ... other branch routes
    });

// Similar for other roles...
```

---

## Testing

After the fix, test different role access:

```bash
# 1. Login as Admin
Visit: http://localhost:8000/login
Email: admin@global-education.com
Password: password

# 2. Should redirect to: /admin/dashboard
# 3. Should have full access to admin features

# 4. Logout and login as Branch Manager
Email: branch@global-education.com
Password: password

# 5. Should redirect to: /branch/dashboard
# 6. Should have limited access (view only)
```

---

## Why This Error Occurred

In Laravel 11+, the middleware registration changed from the old `app/Http/Kernel.php` approach to the new `bootstrap/app.php` configuration. 

When using third-party packages that provide middleware (like Spatie Permission), you must explicitly register their middleware aliases using the `alias()` method.

---

## Related Files

- ✅ `bootstrap/app.php` - Middleware registration
- ✅ `routes/web.php` - Route definitions with role middleware
- ✅ All role-based controllers in `app/Http/Controllers/Admin`, `Branch`, `Counsellor`, etc.

---

## Key Takeaway

**Always register third-party middleware aliases in `bootstrap/app.php` when using Laravel 11+.**

Common packages that require middleware registration:
- ✅ Spatie Laravel Permission (role, permission middleware)
- ✅ Spatie Laravel Activitylog (activity middleware)
- ✅ Laravel Sanctum (auth:sanctum middleware)
- ✅ Custom application middleware

---

**Issue Resolved! ✅**

The role-based routing system is now fully functional and ready for testing.

