# Branch Manager - View-Only Configuration

**Updated:** October 18, 2025  
**Status:** ✅ **CONFIGURED**

---

## Overview

Branch Manager role has been configured as **view-only** - they can see everything but **cannot** add, edit, delete, or update anything.

---

## What Was Changed

### 1. Updated Permissions (TenantRolesSeeder)

**Before:** Branch Manager had create/edit permissions
```php
'create-leads', 'edit-leads', 'assign-leads',
'create-students', 'edit-students',
'create-applications', 'edit-applications',
// ... etc
```

**After:** Branch Manager has ONLY view permissions
```php
'view-leads',
'view-students',
'view-applications', 'track-applications',
'view-institutions', 'view-courses', 'search-courses',
'view-branches',
'view-users',
'view-reports', 'view-analytics', 'view-dashboard',
'view-representing-countries',
'view-tasks',
'view-follow-ups',
'view-settings',
'view-payments', 'view-invoices', 'view-commissions',
```

### 2. Routes Configuration

Branch Manager routes are **GET only** (no POST, PUT, PATCH, DELETE):

```php
Route::middleware(['auth', 'verified', 'role:BranchManager'])
    ->prefix('branch')
    ->name('branch.')
    ->group(function () {
        Route::get('dashboard', ...);  // View dashboard
        Route::get('representing-countries', ...);  // List countries
        Route::get('representing-countries/{id}', ...);  // View details
        // NO create, update, delete routes!
    });
```

### 3. Controller Implementation

Branch Controller only has `index()` and `show()` methods:

```php
final class RepresentingCountryController extends Controller
{
    // ✅ View list of countries (active only)
    public function index(Request $request): Response
    
    // ✅ View single country details (active only)
    public function show(RepresentingCountry $representingCountry): Response
    
    // ❌ No create() method
    // ❌ No store() method
    // ❌ No edit() method
    // ❌ No update() method
    // ❌ No destroy() method
}
```

---

## Branch Manager Capabilities

### ✅ What They CAN Do

**Viewing/Reading:**
- ✅ View dashboard
- ✅ View all leads
- ✅ View all students
- ✅ View all applications and track them
- ✅ View institutions and courses
- ✅ Search courses
- ✅ View branches
- ✅ View users
- ✅ View reports and analytics
- ✅ View representing countries (active only)
- ✅ View tasks
- ✅ View follow-ups
- ✅ View settings
- ✅ View payments, invoices, commissions

**Key Point:** Branch Manager sees **active** records only (no inactive/deleted items)

### ❌ What They CANNOT Do

**Creating:**
- ❌ Cannot create leads
- ❌ Cannot create students
- ❌ Cannot create applications
- ❌ Cannot create tasks
- ❌ Cannot create follow-ups
- ❌ Cannot create anything else

**Editing/Updating:**
- ❌ Cannot edit any records
- ❌ Cannot update any data
- ❌ Cannot assign tasks or leads
- ❌ Cannot manage users
- ❌ Cannot change settings

**Deleting:**
- ❌ Cannot delete anything

**Management:**
- ❌ Cannot manage branch users
- ❌ Cannot manage representing country statuses
- ❌ Cannot reorder items
- ❌ Cannot toggle active/inactive

---

## Role Comparison

| Feature | Admin | Branch Manager | Counsellor |
|---------|-------|----------------|------------|
| **View Data** | ✅ All | ✅ All (active only) | ✅ Limited |
| **Create** | ✅ Full | ❌ None | ✅ Limited |
| **Edit** | ✅ Full | ❌ None | ✅ Limited |
| **Delete** | ✅ Full | ❌ None | ❌ None |
| **Manage Status** | ✅ Yes | ❌ No | ❌ No |
| **Access Level** | Full Control | View Only | Work Only |

---

## Testing Branch Manager Access

### Test as Branch Manager

1. **Login:**
   - Email: `branch@global-education.com`
   - Password: `password`

2. **Should Redirect To:** `/branch/dashboard`

3. **Test View Access:**
   - ✅ Can view `/branch/representing-countries`
   - ✅ Can view details of any country
   - ✅ Can see all information (read-only)

4. **Test Create Access:**
   - ❌ No "Create" buttons visible
   - ❌ Direct access to `/branch/representing-countries/create` → 403 Forbidden

5. **Test Edit Access:**
   - ❌ No "Edit" buttons visible
   - ❌ Direct access to `/branch/representing-countries/{id}/edit` → 404 Not Found

6. **Test Delete Access:**
   - ❌ No "Delete" buttons visible
   - ❌ No ability to delete anything

---

## UI Differences by Role

### Admin Dashboard (`/admin/representing-countries`)
```
[+ Create New] [Edit] [Delete] [Manage Status] [Reorder]
Full table with all actions
```

### Branch Manager Dashboard (`/branch/representing-countries`)
```
[View Details only]
Card grid showing active countries
No create/edit/delete buttons
```

### Counsellor Dashboard (`/counsellor/representing-countries`)
```
[Quick View only]
Simple card grid for reference
Minimal information
```

---

## Permission Check Example

In controllers, authorization will automatically prevent unauthorized actions:

```php
// Branch Manager accessing Admin route
$this->authorize('create-representing-countries');
// ❌ Throws 403 Forbidden - Branch Manager doesn't have this permission

// Branch Manager accessing view route
$this->authorize('view-representing-countries');
// ✅ Passes - Branch Manager has this permission
```

---

## Frontend Permission Props

Controllers pass permission flags to frontend:

```php
return Inertia::render('branch/representing-countries/index', [
    'permissions' => [
        'canCreate' => false,  // ❌ Branch Manager cannot create
        'canEdit' => false,    // ❌ Branch Manager cannot edit
        'canDelete' => false,  // ❌ Branch Manager cannot delete
        'canManageStatus' => false,  // ❌ Branch Manager cannot manage status
    ],
]);
```

React components use these flags to hide buttons:

```tsx
{permissions.canCreate && (
    <Button>Create New</Button>  // ❌ Won't render for Branch Manager
)}
```

---

## Database State

After reseeding:
- ✅ All Branch Manager users now have updated permissions
- ✅ No create/edit/delete permissions
- ✅ Only view permissions active
- ✅ Changes applied to all 4 organizations

---

## Future Additions

When adding new features, ensure Branch Manager gets:
- ✅ `view-{feature}` permission
- ❌ NOT `create-{feature}` permission
- ❌ NOT `edit-{feature}` permission  
- ❌ NOT `delete-{feature}` permission

---

**Branch Manager is now properly configured as view-only!** 🔒👀

