# TASK-011: Lead Management System

**Status:** ⚠️ **IN PROGRESS** (~30% Complete)  
**Priority:** P0 - Critical  
**Package:** spatie/laravel-activitylog  

---

## Database (Completed)

- [x] Create Lead model with factory and seeder
- [x] Add organization_id for tenant scoping
- [x] Implement BelongsToOrganization trait

---

## Pending Implementation

- [ ] Implement Spatie Activitylog trait on Lead model
- [ ] Create lead CRUD operations (Controller, Actions, Requests)
- [ ] Implement manual lead capture form with preferred countries (representing countries)
- [ ] Build lead status workflow (New, Contacted, Qualified, Converted, Lost)
- [ ] Create lead assignment system (manual, round-robin, territory-based)
- [ ] Implement lead source tracking (UTM parameters, campaigns)
- [ ] Build bulk lead upload (CSV/Excel) with validation
- [ ] Implement duplicate lead detection (email, phone)
- [ ] Create lead merging functionality
- [ ] Build lead transfer between counsellors within branch
- [ ] Create lead listing page with filters (status, country preference, source)
- [ ] Track activity log for all lead actions
- [ ] Write Pest tests for lead management

---

## Related User Stories

US-031, US-032, US-033, US-034, US-035, US-036, US-037, US-038, US-039, US-040, US-041

---

## Lead Fields

- organization_id, branch_id, assigned_to
- first_name, last_name, email, phone
- date_of_birth, nationality
- preferred_countries (JSON)
- preferred_level, preferred_subjects (JSON)
- status (New/Contacted/Qualified/Converted/Lost)
- source, utm_parameters (JSON)
- notes, lost_reason
- last_contact_at, next_follow_up_at

---

## Features

### Lead Capture
- Manual entry form
- Bulk CSV/Excel upload (1000+ leads)
- Website form integration (future)

### Lead Assignment
- Manual assignment
- Round-robin distribution
- Territory-based assignment

### Duplicate Detection
- Check email and phone
- Alert before creating
- Merge duplicates

### Status Workflow
- New → Contacted → Qualified → Converted
- Or Lost (with reason)

---

## Notes

- Already tenant-scoped (organization_id)
- Activitylog for tracking all changes
- Critical for CRM functionality

---

**Estimated Time:** 3-4 hours

---

**End of Document**

