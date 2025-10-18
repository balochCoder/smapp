# Test User Credentials

**Generated:** October 18, 2025  
**Password for all users:** `password`

---

## Platform Users (No Organization)

These users manage the entire SaaS platform and can access all organizations.

### SuperAdmin
- **Email:** `superadmin@platform.com`
- **Password:** `password`
- **Role:** SuperAdmin
- **Access:** Full platform management
- **Dashboard:** `/platform/dashboard`

### Support Staff
- **Email:** `support@platform.com`
- **Password:** `password`
- **Role:** Support
- **Access:** View organizations and analytics
- **Dashboard:** `/platform/support-dashboard`

---

## Organization 1: Schiller PLC Education Consultancy

### Admin User
- **Email:** `admin@schiller-plc-education-consultancy.com`
- **Password:** `password`
- **Role:** Admin
- **Access:** Full CRUD access to all features
- **Dashboard:** `/admin/dashboard`

### Branch Manager
- **Email:** `branch@schiller-plc-education-consultancy.com`
- **Password:** `password`
- **Role:** BranchManager
- **Access:** View active countries (read-only)
- **Dashboard:** `/branch/dashboard`

### Counsellor
- **Email:** `counsellor@schiller-plc-education-consultancy.com`
- **Password:** `password`
- **Role:** Counsellor
- **Access:** Quick reference view (read-only)
- **Dashboard:** `/counsellor/dashboard`

### Processing Officer
- **Email:** `processing@schiller-plc-education-consultancy.com`
- **Password:** `password`
- **Role:** ProcessingOfficer
- **Access:** Process applications
- **Dashboard:** `/processing/dashboard`

### Front Office
- **Email:** `frontoffice@schiller-plc-education-consultancy.com`
- **Password:** `password`
- **Role:** FrontOffice
- **Access:** Lead capture and initial contact
- **Dashboard:** `/frontoffice/dashboard`

### Finance
- **Email:** `finance@schiller-plc-education-consultancy.com`
- **Password:** `password`
- **Role:** Finance
- **Access:** Financial tracking and invoicing
- **Dashboard:** `/finance/dashboard`

---

## Organization 2: Greenholt Inc Education Consultancy

### Admin User
- **Email:** `admin@greenholt-inc-education-consultancy.com`
- **Password:** `password`
- **Role:** Admin
- **Dashboard:** `/admin/dashboard`

### Branch Manager
- **Email:** `branch@greenholt-inc-education-consultancy.com`
- **Password:** `password`
- **Role:** BranchManager
- **Dashboard:** `/branch/dashboard`

### Counsellor
- **Email:** `counsellor@greenholt-inc-education-consultancy.com`
- **Password:** `password`
- **Role:** Counsellor
- **Dashboard:** `/counsellor/dashboard`

### Processing Officer
- **Email:** `processing@greenholt-inc-education-consultancy.com`
- **Password:** `password`
- **Role:** ProcessingOfficer
- **Dashboard:** `/processing/dashboard`

### Front Office
- **Email:** `frontoffice@greenholt-inc-education-consultancy.com`
- **Password:** `password`
- **Role:** FrontOffice
- **Dashboard:** `/frontoffice/dashboard`

### Finance
- **Email:** `finance@greenholt-inc-education-consultancy.com`
- **Password:** `password`
- **Role:** Finance
- **Dashboard:** `/finance/dashboard`

---

## Organization 3: Schuppe, Becker and Watsica Education Consultancy

### Admin User
- **Email:** `admin@schuppe-becker-and-watsica-education-consultancy.com`
- **Password:** `password`
- **Role:** Admin
- **Dashboard:** `/admin/dashboard`

### Branch Manager
- **Email:** `branch@schuppe-becker-and-watsica-education-consultancy.com`
- **Password:** `password`
- **Role:** BranchManager
- **Dashboard:** `/branch/dashboard`

### Counsellor
- **Email:** `counsellor@schuppe-becker-and-watsica-education-consultancy.com`
- **Password:** `password`
- **Role:** Counsellor
- **Dashboard:** `/counsellor/dashboard`

### Processing Officer
- **Email:** `processing@schuppe-becker-and-watsica-education-consultancy.com`
- **Password:** `password`
- **Role:** ProcessingOfficer
- **Dashboard:** `/processing/dashboard`

### Front Office
- **Email:** `frontoffice@schuppe-becker-and-watsica-education-consultancy.com`
- **Password:** `password`
- **Role:** FrontOffice
- **Dashboard:** `/frontoffice/dashboard`

### Finance
- **Email:** `finance@schuppe-becker-and-watsica-education-consultancy.com`
- **Password:** `password`
- **Role:** Finance
- **Dashboard:** `/finance/dashboard`

---

## Organization 4: Global Education Consultancy

### Admin User
- **Email:** `admin@global-education.com`
- **Password:** `password`
- **Role:** Admin
- **Dashboard:** `/admin/dashboard`

### Branch Manager
- **Email:** `branch@global-education.com`
- **Password:** `password`
- **Role:** BranchManager
- **Dashboard:** `/branch/dashboard`

### Counsellor
- **Email:** `counsellor@global-education.com`
- **Password:** `password`
- **Role:** Counsellor
- **Dashboard:** `/counsellor/dashboard`

### Processing Officer
- **Email:** `processing@global-education.com`
- **Password:** `password`
- **Role:** ProcessingOfficer
- **Dashboard:** `/processing/dashboard`

### Front Office
- **Email:** `frontoffice@global-education.com`
- **Password:** `password`
- **Role:** FrontOffice
- **Dashboard:** `/frontoffice/dashboard`

### Finance
- **Email:** `finance@global-education.com`
- **Password:** `password`
- **Role:** Finance
- **Dashboard:** `/finance/dashboard`

---

## Summary

- **Total Users:** 26 (2 platform + 24 tenant users)
- **Total Roles:** 26 (2 platform + 24 tenant roles)
- **Organizations:** 4
- **Password:** `password` (for all users)

---

## Testing Different Access Levels

### Test Admin Access
1. Login with: `admin@global-education.com` / `password`
2. Visit `/dashboard` → Should redirect to `/admin/dashboard`
3. Navigate to Representing Countries
4. You should see:
   - ✅ Create button
   - ✅ Edit buttons
   - ✅ Delete buttons
   - ✅ Status management features
   - ✅ Reorder functionality
   - ✅ Notes management

### Test Branch Manager Access
1. Login with: `branch@global-education.com` / `password`
2. Visit `/dashboard` → Should redirect to `/branch/dashboard`
3. Navigate to Representing Countries
4. You should see:
   - ✅ View active countries (card grid)
   - ✅ View details button
   - ❌ No create button
   - ❌ No edit/delete buttons
   - ❌ No status management

### Test Counsellor Access
1. Login with: `counsellor@global-education.com` / `password`
2. Visit `/dashboard` → Should redirect to `/counsellor/dashboard`
3. Navigate to Representing Countries
4. You should see:
   - ✅ Simplified card grid
   - ✅ Quick view of countries
   - ❌ No create/edit/delete
   - ❌ Minimal details

### Test Platform User Access
1. Login with: `superadmin@platform.com` / `password`
2. Visit `/dashboard` → Should redirect to `/platform/dashboard`
3. Platform management interface
4. Can view all organizations

---

## Quick Login Commands (for testing)

```bash
# Admin User
php artisan tinker
>>> auth()->loginUsingId(3); // First admin user ID

# Branch Manager
>>> auth()->loginUsingId(4); // First branch manager ID

# Counsellor
>>> auth()->loginUsingId(5); // First counsellor ID
```

---

## Verify Role Assignments

```bash
php artisan tinker

# Check user roles
>>> $user = \App\Models\User::where('email', 'admin@global-education.com')->first();
>>> $user->getRoleNames();

# Check user permissions
>>> $user->getAllPermissions()->pluck('name');

# List all users with their roles
>>> \App\Models\User::with('roles')->get()->map(fn($u) => [$u->email, $u->roles->pluck('name')]);
```

---

**All credentials are for development/testing purposes only. Change passwords in production!**

