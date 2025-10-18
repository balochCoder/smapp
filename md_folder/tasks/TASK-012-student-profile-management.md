# TASK-012: Student Profile Management

**Status:** ⚠️ **IN PROGRESS** (~30% Complete)  
**Priority:** P0 - Critical  
**Package:** spatie/laravel-medialibrary  

---

## Database (Completed)

- [x] Create Student model with factory and seeder
- [x] Add organization_id for tenant scoping
- [x] Implement BelongsToOrganization trait

---

## Pending Implementation

- [ ] Implement Spatie Media Library trait on Student model
- [ ] Build student profile CRUD operations
- [ ] Implement comprehensive student information fields
- [ ] Create academic background tracking
- [ ] Build student document management using Spatie Media Library
- [ ] Configure media collections (passport, transcripts, certificates, etc.)
- [ ] Create student communication history
- [ ] Build student detail page
- [ ] Write Pest tests for student management

---

## Related User Stories

US-042, US-044, US-045

---

## Student Fields

- organization_id, lead_id, branch_id
- assigned_counsellor, student_id (unique)
- first_name, last_name, email, phone
- date_of_birth, gender, nationality
- passport_number, passport_expiry
- address, city, state, country, postal_code
- emergency_contact (name, phone, relationship)
- highest_education_level, field_of_study, gpa
- academic_history (JSON), work_experience (JSON)
- english_proficiency (JSON), other_tests (JSON)
- notes

---

## Media Collections

- passport (passport scans)
- transcripts (academic records)
- certificates (degrees, diplomas)
- test_scores (IELTS, TOEFL, etc.)
- personal_documents (CV, SOP, LOR)

---

## Features

### Profile Management
- Complete student information
- Academic history tracking
- Test scores tracking

### Document Management
- Upload multiple documents
- Categorize by type
- Version control
- Share across applications

### Communication
- Track all communications
- Notes and comments
- Activity history

---

## Notes

- Already tenant-scoped (organization_id)
- Can be converted from Lead
- Central to application management

---

**Estimated Time:** 3-4 hours

---

**End of Document**

