# TASK-009: Course Catalog Management

**Status:** ⚠️ **IN PROGRESS** (~30% Complete)  
**Priority:** P0 - Critical  

---

## Database (Completed)

- [x] Create Course model with factory and seeder
- [x] Add institution_id foreign key (belongs to institution)
- [x] Add organization_id for tenant scoping
- [x] Implement BelongsToOrganization trait

---

## Pending Implementation

- [ ] Create course CRUD operations (Controller, Actions, Requests)
- [ ] Implement course categorization (Level, Subject)
- [ ] Create intake management (Fall/Spring/Summer with dates)
- [ ] Implement tuition fees and scholarship fields
- [ ] Add entry requirements and English language criteria
- [ ] Build course duration and career outcomes fields
- [ ] Create course listing page with advanced filters (by country, institution, level, subject)
- [ ] Implement full-text search functionality
- [ ] Write Pest tests for course management

---

## Related User Stories

US-023, US-024, US-025, US-026

---

## Relationship

Institution → Has Many Courses  
**Flow:** Country → Institution → Courses

---

## Course Fields

- organization_id (tenant scoping)
- institution_id (which institution)
- name, code, description
- level (UG/PG/Diploma)
- subject_area, specialization
- duration_value, duration_unit
- tuition_fee, fee_currency, fee_period
- scholarships (JSON)
- intakes (JSON - Fall/Spring/Summer dates)
- entry_requirements, english_requirement
- other_requirements (JSON)
- mode_of_study (On-campus/Online/Hybrid)
- career_outcomes, course_structure
- is_active, is_featured

---

## Features

- Advanced filtering by country, institution, level, subject
- Full-text search
- Intake management
- Tuition fees with currency support
- Scholarship information

---

## Notes

- Already tenant-scoped (organization_id)
- Belongs to Institution
- Will have search and comparison features (TASK-010)

---

**Estimated Time:** 2-3 hours

---

**End of Document**

