# TASK-003: Database Schema Design

**Status:** ✅ **COMPLETED**  
**Priority:** P0 - Critical  
**Date Completed:** October 16, 2025  

---

## Checklist

- [x] Design ERD for all core entities (including country representation)
- [x] Create migration for users table (updated to use ULID)
- [x] Create migration for countries table with fields: name, flag, is_active (auto-seeded with 195+ countries)
- [x] Create migration for representing_countries table
- [x] Create migration for application_processes table
- [x] Create migration for rep_country_status table
- [x] Create migration for sub_statuses table
- [x] Add soft deletes to all tables
- [x] Create migration for branches table
- [x] Install Spatie Permission package and run migrations (roles/permissions tables) - v6.21
- [x] Create migration for students table
- [x] Create migration for leads table
- [x] Create migration for institutions table
- [x] Create migration for courses table
- [x] Create migration for applications table
- [x] Create migration for tasks table
- [x] Create migration for follow-ups table
- [x] Install Spatie Media Library and run migrations - v11.15
- [x] Install Spatie Activitylog and run migrations - v4.10
- [x] Implement all model factories with realistic test data
- [x] Implement all model seeders for development/testing

---

## Packages Installed

- spatie/laravel-permission v6.21
- spatie/laravel-medialibrary v11.15
- spatie/laravel-activitylog v4.10

---

## Key Relationships

- Country → Has One RepresentingCountry (if organization represents it)
- Country → Has Many Institutions → Has Many Courses → Has Many Applications
- RepresentingCountry → Has Many RepCountryStatuses (ordered country-specific instances)
- RepCountryStatus → Has Many SubStatuses (country-specific sub-steps)

---

## Architecture Notes

- All models use ULID (HasUlids trait) instead of auto-incrementing IDs
- All models use SoftDeletes trait for soft deletion
- Countries table contains 193 world countries auto-seeded inline during migration

---

## Models Created

Country (auto-seeded), RepresentingCountry, ApplicationProcess (global templates), RepCountryStatus (country instances), SubStatus, Branch, Lead, Student, Institution, Course, Application, Task, FollowUp (all with factories and seeders)

---

**Database Verified:** All tables, relationships, and seeders verified using Laravel Boost MCP tools

---

**End of Document**

