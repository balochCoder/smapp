# TASK-005: Role-Based Access Control (RBAC)

**Status:** ⚠️ **IN PROGRESS** (~20% Complete)  
**Priority:** P0 - Critical  
**Package:** spatie/laravel-permission v6.21  

---

## Package Setup (Completed)

- [x] Install Spatie Laravel Permission package
- [x] Run package migrations for roles and permissions tables
- [x] Publish configuration file

---

## Pending Implementation

- [ ] Add HasRoles trait to User model
- [ ] Define predefined roles (Admin, Branch Manager, Counsellor, Processing Officer, Front Office, Finance)
- [ ] Define granular permissions (create-lead, edit-application, view-reports, etc.)
- [ ] Create RolesAndPermissionsSeeder
- [ ] Role based routes (e.g., /admin/representing-countries vs /branch/representing-countries)
- [ ] Create role assignment functionality
- [ ] Build custom role creation interface with shadcn/ui
- [ ] Implement middleware for permission checking
- [ ] Implement IP whitelisting per user/branch
- [ ] Build user management CRUD interface
- [ ] Write Pest tests for RBAC

---

## Predefined Roles

1. **Admin** - Full access to organization
2. **Branch Manager** - Manage specific branch
3. **Counsellor** - Manage students and applications
4. **Processing Officer** - Process applications
5. **Front Office** - Lead capture and initial contact
6. **Finance** - Financial tracking and invoicing

---

## Sample Permissions

- create-lead, edit-lead, delete-lead, view-lead
- create-student, edit-student, delete-student, view-student
- create-application, edit-application, delete-application, view-application
- view-reports, export-reports
- manage-users, assign-roles
- manage-institutions, manage-courses
- manage-branches (admin only)
- view-financials, manage-payments

---

## Multi-Tenancy Integration

**Important:** Roles and permissions should be **tenant-scoped**!

- Each organization has its own roles
- Permissions can be global but role assignments are per organization
- Use Spatie Permission's teams feature OR custom scoping

---

## Related User Stories

US-007, US-008, US-009, US-010, US-011, US-012

---

## Notes

- Package already installed and configured
- Ready for implementation
- Will integrate with multi-tenancy
- Critical for access control before building other features

---

**Estimated Time:** 2-3 hours

---

**End of Document**

