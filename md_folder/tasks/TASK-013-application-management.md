# TASK-013: Application Management System

**Status:** ⚠️ **IN PROGRESS** (~30% Complete)  
**Priority:** P0 - Critical  
**Packages:** spatie/laravel-medialibrary, spatie/laravel-activitylog  

---

## Database (Completed)

- [x] Create Application model with factory and seeder
- [x] Add organization_id for tenant scoping
- [x] Implement BelongsToOrganization trait

---

## Pending Implementation

- [ ] Implement Spatie Media Library trait on Application model
- [ ] Implement Spatie Activitylog trait on Application model
- [ ] Implement "One Student, Multiple Applications" architecture
- [ ] Create application CRUD operations
- [ ] Build cascading selection flow: Representing Country → Institution → Course → Intake
- [ ] Implement application status workflow
- [ ] Create customizable application stages per representing country
- [ ] Build document checklist management using Spatie Media Library
- [ ] Configure media collections for applications (documents, offer letters, visa docs)
- [ ] Implement shared documents across applications
- [ ] Create application timeline view using activity logs
- [ ] Build application history using Spatie Activitylog
- [ ] Implement conditional and final offer management
- [ ] Create application transfer between branches
- [ ] Build application listing and filtering
- [ ] Write Pest tests for application management

---

## Related User Stories

US-042, US-043, US-044, US-045, US-046, US-047, US-048, US-049, US-050, US-051, US-052

---

## Application Fields

- organization_id, student_id, branch_id
- country_id, institution_id, course_id
- application_number (unique), assigned_officer
- intake, intake_date
- status, current_stage
- workflow_stages (JSON - uses rep_country_status)
- application_date, decision_date, decision_notes
- document_checklist (JSON)
- conditional_offer, offer_conditions (JSON)
- offer_letter_path, offer_expiry_date
- application_fee, application_fee_paid
- tuition_deposit, tuition_deposit_paid
- visa_status, visa_application_date, visa_decision_date
- notes, internal_notes

---

## Key Features

### One Student, Multiple Applications
- Student profile created once
- Add new applications: Country → Institution → Course → Intake
- Shared documents across all applications
- Unified communication history

### Application Workflow
- Uses country-specific statuses from rep_country_status
- Customizable stages per country
- Timeline view with activity logs
- Document checklist per stage

### Media Collections
- application_documents (general docs)
- offer_letters (acceptance letters)
- visa_documents (visa application)

---

## Notes

- Already tenant-scoped (organization_id)
- Uses country-specific workflows (TASK-014 completed)
- Core to the entire system
- Most complex module

---

**Estimated Time:** 4-5 hours

---

**End of Document**

