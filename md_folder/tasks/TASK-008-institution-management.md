# TASK-008: Institution Database Management

**Status:** ⚠️ **IN PROGRESS** (~30% Complete)  
**Priority:** P0 - Critical  
**Package:** spatie/laravel-medialibrary v11.15  

---

## Database (Completed)

- [x] Create Institution model with factory and seeder
- [x] Add country_id foreign key to institutions table (belongs to country)
- [x] Add organization_id for tenant scoping
- [x] Implement BelongsToOrganization trait

---

## Pending Implementation

- [ ] Implement Spatie Media Library trait on Institution model (for marketing materials)
- [ ] Create institution CRUD operations (Controller, Actions, Requests)
- [ ] Implement institution profile fields (rankings, location, accreditation, facilities)
- [ ] Build institution search and filtering by country
- [ ] Implement marketing materials upload using Spatie Media Library (brochures, videos, images)
- [ ] Configure media collections for institutions (brochures, campus_images, videos)
- [ ] Create institution contact management
- [ ] Build commission structure configuration
- [ ] Create institution listing page with shadcn/ui (grouped by country)
- [ ] Create institution detail page
- [ ] Write Pest tests for institution management

---

## Related User Stories

US-017, US-018, US-019, US-020, US-021, US-022

---

## Relationship

Country → Has Many Institutions (tenant-scoped)

---

## Institution Fields

- organization_id (tenant scoping)
- country_id (which country)
- name, logo, description
- institution_type (University, College, etc.)
- city, state, address, website, email, phone
- rankings (JSON)
- accreditation, facilities (JSON)
- campus_life, established_year
- commission_rate, commission_type
- contact_persons (JSON)
- is_partner, is_active

---

## Media Collections

- brochures (PDF documents)
- campus_images (photos)
- videos (promotional videos)

---

## Notes

- Already tenant-scoped (organization_id)
- Belongs to Country
- Has Many Courses
- Spatie Media Library for marketing materials

---

**Estimated Time:** 2-3 hours

---

**End of Document**

