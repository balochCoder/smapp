# Development Tasks - Study Abroad Education Consultancy Management Platform

**Version:** 1.0
**Date:** October 15, 2025
**Based on:** PRD v1.0 & User Stories v1.0

---

## Table of Contents
1. [Phase 1: MVP Foundation (Months 1-4)](#phase-1-mvp-foundation-months-1-4)
2. [Phase 2: Enhancement (Months 5-7)](#phase-2-enhancement-months-5-7)
3. [Phase 3: Scale (Months 8-10)](#phase-3-scale-months-8-10)
4. [Technical Infrastructure](#technical-infrastructure)
5. [Testing & Quality Assurance](#testing--quality-assurance)

---

## Phase 1: MVP Foundation (Months 1-4)

### 1.1 Project Setup & Infrastructure

**TASK-001: Initial Laravel Application Setup** ✅ **COMPLETED**
- [x] Set up Laravel 12 project structure
- [x] Configure environment files (.env)
- [x] Set up database (SQLite for dev, PostgreSQL/MySQL for prod)
- [x] Install and configure Inertia.js v2
- [x] Set up Vite for frontend bundling
- [x] Configure Tailwind CSS
- [x] Install and configure shadcn/ui components
- [x] Set up Laravel Pint for code formatting
- [x] Configure Pest v4 for testing
- **Related User Stories:** Infrastructure foundation
- **Priority:** P0 - Critical
- **Status:** ✅ Completed - October 15, 2025

**TASK-002: Authentication & Security Setup** ⚠️ **PARTIALLY COMPLETED**
- [x] Install and configure Laravel Fortify
- [x] Implement user registration
- [x] Implement user login/logout
- [x] Implement password reset functionality
- [ ] Set up Two-Factor Authentication (2FA) for admins
- [x] Configure session management
- [x] Set up CSRF protection
- [ ] Implement IP whitelisting capability
- **Related User Stories:** US-009, US-011
- **Priority:** P0 - Critical
- **Status:** ⚠️ Partially Complete - Basic authentication implemented, 2FA and IP whitelisting pending

**TASK-003: Database Schema Design** ✅ **COMPLETED**
- [x] Design ERD for all core entities (including country representation)
- [x] Create migration for users table (updated to use ULID)
- [x] Create migration for countries table with fields: name, flag, is_active (auto-seeded with 195+ countries)
- [x] Create migration for representing_countries table with fields: country_id, monthly_living_cost, visa_requirements, part_time_work_details, country_benefits, is_active
- [x] Create migration for application_processes table with global status definitions: name, color, order
- [x] Create migration for rep_country_status table for country-specific status instances
- [x] Create migration for sub_statuses table for status sub-steps
- [x] Add soft deletes to all tables (users, countries, branches, leads, students, institutions, courses, applications, tasks, follow_ups, representing_countries, application_processes, rep_country_status, sub_statuses)
- [x] Create migration for branches table
- [x] Install Spatie Permission package and run migrations (roles/permissions tables) - **v6.21 installed**
- [x] Create migration for students table
- [x] Create migration for leads table
- [x] Create migration for institutions table - **Must have country_id (belongs to country)**
- [x] Create migration for courses table - **Must have institution_id (belongs to institution)**
- [x] Create migration for applications table
- [x] Create migration for tasks table
- [x] Create migration for follow-ups table
- [x] Install Spatie Media Library and run migrations (media table) - **v11.15 installed**
- [x] Install Spatie Activitylog and run migrations (activity_log table) - **v4.10 installed**
- [x] Implement all model factories with realistic test data
- [x] Implement all model seeders for development/testing
- **Related User Stories:** All data-driven stories
- **Priority:** P0 - Critical
- **Status:** ✅ Completed and Verified - October 16, 2025
- **Database Verified:** All tables, relationships, and seeders verified using Laravel Boost MCP tools
- **Packages Installed:**
  - spatie/laravel-permission v6.21
  - spatie/laravel-medialibrary v11.15
  - spatie/laravel-activitylog v4.10
- **Key Relationships:**
  - Country → Has One RepresentingCountry (if organization represents it)
  - Country → Has Many Institutions → Has Many Courses → Has Many Applications
  - RepresentingCountry → Has Many RepCountryStatuses (ordered country-specific instances)
  - RepCountryStatus → Has Many SubStatuses (country-specific sub-steps)
- **Architecture:** All models use ULID (HasUlids trait) instead of auto-incrementing IDs. All models use SoftDeletes trait for soft deletion.
- **Models Created:** Country (auto-seeded), RepresentingCountry, ApplicationProcess (global templates), RepCountryStatus (country instances), SubStatus, Branch, Lead, Student, Institution, Course, Application, Task, FollowUp (all with factories and seeders)
- **Note:** Countries table contains 193 world countries auto-seeded inline during migration (verified via MCP). RepresentingCountries table contains only countries the organization represents with detailed study-abroad information. ApplicationProcesses are global status templates that are instantiated per country via rep_country_status table. Each country can customize status names, order, and add country-specific sub-statuses.

---

### 1.2 Organization & User Management

**TASK-003A: Country Representation Management** ✅ **COMPLETED**
- [x] Create Country model with factory (countries auto-seeded in migration with 195+ countries)
- [x] Create countries table migration (name, flag, is_active) - **Auto-seeds all world countries with flags**
- [x] Add soft deletes to countries table
- [x] Create RepresentingCountry model with factory and seeder
- [x] Create representing_countries table migration (country_id, monthly_living_cost, visa_requirements, part_time_work_details, country_benefits, is_active)
- [x] Add soft deletes to representing_countries table
- [x] Create ApplicationProcess model with factory (global status definitions)
- [x] Create application_processes table migration (name, color, order) - **Flat structure, no hierarchical parent_id**
- [x] Add soft deletes to application_processes table
- [x] Create RepCountryStatus model with factory (country-specific status instances)
- [x] Create rep_country_status table migration (representing_country_id, status_name, custom_name, notes, order, is_active)
- [x] Add soft deletes to rep_country_status table
- [x] Create SubStatus model with factory (sub-statuses for status steps)
- [x] Create sub_statuses table migration (rep_country_status_id, name, description, order, is_active)
- [x] Add soft deletes to sub_statuses table
- [x] Implement Country → RepresentingCountry hasOne relationship
- [x] Implement RepresentingCountry → Country belongsTo relationship
- [x] Implement RepresentingCountry → RepCountryStatuses hasMany relationship (ordered)
- [x] Implement RepCountryStatus → RepresentingCountry belongsTo relationship
- [x] Implement RepCountryStatus → SubStatuses hasMany relationship
- [x] Implement SubStatus → RepCountryStatus belongsTo relationship
- [x] Seed 12 default application statuses (New, Application On Hold, Pre-Application Process, etc.)
- [x] Auto-create status instances for each representing country upon creation
- [x] Create RepCountryStatusSeeder to automatically attach all admissions processes to all representing countries
- [x] Integrate RepCountryStatusSeeder into DatabaseSeeder (runs after ApplicationProcessSeeder)
- [x] Implement representing country CRUD operations (Actions, Requests, Controllers)
- [x] Build representing country management interface with shadcn/ui (Card-based grid layout with toggles, action buttons, expandable status steps)
- [x] Implement country-level active/inactive toggle
- [x] Implement status-level active/inactive toggle
- [x] Implement status custom name editing (per country)
- [x] Create "Add Step" functionality with text input dialog
- [x] Create "Edit Step" functionality with dialog
- [x] Implement "Add Sub-Status" functionality with dialog
- [x] Implement "Edit Sub-Status" functionality with dialog
- [x] Create "View Sub-Statuses" sheet with shadcn Sheet component
- [x] Implement real-time UI updates for sub-status changes
- [x] Implement delete status functionality with AlertDialog confirmation
- [x] Implement delete sub-status functionality with AlertDialog confirmation
- [x] Implement cascading soft deletes for sub-statuses
- [x] Implement drag-and-drop reorder functionality with @dnd-kit
- [x] Create dedicated reorder page with visual feedback and instructions
- [x] Implement auto-save on reorder with loading indicators
- [x] Protect "New" status from editing, deleting, reordering, toggle, and sub-status addition
- [x] Create useDialog custom hook for dialog state management
- [x] Implement smooth closing animations for sheets and dialogs
- [x] Implement Laravel Wayfinder for type-safe routes
- [x] Add soft deletes to all models (User, Student, Lead, Application, Branch, Country, Course, Institution, Task, FollowUp, RepresentingCountry, ApplicationProcess, RepCountryStatus, SubStatus)
- [x] Update all migrations to include soft deletes in original create table statements
- [x] Write comprehensive Pest tests for all CRUD operations
- [x] Write Pest tests for status management (27 tests passing)
- [x] Write Pest tests for reorder functionality (9 tests passing)
- [x] Write Pest tests for "New" status protection
- [x] Write Pest tests for soft deletes
- [x] Implement pagination for representing countries index (9 per page, 3-column grid optimized)
- [x] Add shadcn/ui Pagination component with smart ellipsis
- [x] Fix browser errors with null safety checks and optional chaining
- [x] Centralize all TypeScript interfaces to global types file (index.d.ts)
- [x] Create reusable PaginatedData<T> generic type for all paginated resources
- [x] Add defensive programming for undefined/null values across all pages
- [x] Fix MultiSelect component to preserve disabled options when clearing (October 17, 2025)
- [x] Implement automatic preservation of "New" status in MultiSelect clear functionality
- [x] Update RepresentingCountryResource to properly pass application_processes using isset() instead of property_exists()
- [x] Add resetOnDefaultValueChange={false} prop to MultiSelect in edit form to prevent unwanted resets
- [x] Fix handleClear() in MultiSelect to keep all disabled options when clearing selection
- [x] Fix clearExtraOptions() in MultiSelect to respect disabled options when limiting display count
- [x] Implement comprehensive dark mode support across all representing countries pages (October 17, 2025)
- [x] Add dark mode classes to index.tsx (text colors, background colors)
- [x] Add dark mode classes to create.tsx (icon containers, selected country card)
- [x] Add dark mode classes to edit.tsx (all section headers and icons)
- [x] Add dark mode classes to reorder.tsx (drag handles, badges, info cards)
- [x] Verify show.tsx and notes.tsx dark mode compatibility (already compatible via shadcn/ui)
- **Related User Stories:** New requirement - Country representation
- **Priority:** P0 - Critical
- **Status:** ✅ **FULLY COMPLETED** - All database, UI, CRUD operations, pagination, dark mode, and advanced features implemented
- **Latest Updates (October 17, 2025):**
  - ✅ Created RepCountryStatusSeeder to automatically attach all 12 admissions processes to all representing countries
  - ✅ Seeder uses updateOrCreate() to prevent duplicates and can be run multiple times safely
  - ✅ Database verified: 84 records created (7 representing countries × 12 application processes)
  - ✅ Fixed MultiSelect component to preserve disabled options (like "New" status) when clicking clear button
  - ✅ Fixed RepresentingCountryResource to properly include application_processes in edit form
  - ✅ Changed property_exists() to isset() for dynamically added properties in resource
  - ✅ Updated handleClear() to filter and keep all disabled options when clearing
  - ✅ Updated clearExtraOptions() to respect disabled options when limiting display count
  - ✅ Simplified handleProcessChange in both create.tsx and edit.tsx (component now handles disabled preservation automatically)
  - ✅ MultiSelect now automatically protects any disabled option across all interactions (manual deselect, clear button, clear dropdown, extra options clear)
  - ✅ Implemented comprehensive dark mode support across all 6 representing countries pages
  - ✅ Added dark: variants for all color classes (bg-*, text-*, border-*) across index, create, edit, and reorder pages
  - ✅ Color mappings: blue-100→blue-900, green-100→green-900, amber-100→amber-900, purple-100→purple-900, indigo-100→indigo-900, gray-50→gray-800, gray-200→gray-700
  - ✅ Icon colors adapted for dark mode visibility (600→400 variants)
  - ✅ Consistent dark mode theming across all section headers and cards
  - ✅ No linting errors - all changes validated and clean
- **Previous Updates (October 16, 2025):**
  - ✅ Fixed critical browser errors: Cannot read properties of undefined (reading 'slice', 'flag', 'toString')
  - ✅ Fixed Laravel MissingAttributeException in RepresentingCountryResource using property_exists()
  - ✅ Added pagination with shadcn/ui component (12 items per page, 3-column grid optimized)
  - ✅ Implemented smart pagination with ellipsis for large page counts
  - ✅ Centralized all TypeScript types to resources/js/types/index.d.ts (6 pages refactored)
  - ✅ Created generic PaginatedData<T> type with meta and links for Laravel Resource Collections
  - ✅ Added null safety checks across all 6 pages with optional chaining
  - ✅ All 67 Pest tests passing (RepCountryStatusTest: 31, RepresentingCountryTest: 27, RepresentingCountryReorderTest: 9)
- **Database Architecture (Refactored October 16, 2025):**
  - ✅ **application_processes table:** Global status definitions (12 statuses: New, Application On Hold, Pre-Application Process, Rejected by University, Application Submitted, Conditional Offer, Pending Interview, Unconditional Offer, Acceptance, Visa Processing, Enrolled, Dropped)
  - ✅ **rep_country_status table:** Country-specific status instances with custom names, notes, order, and active state
  - ✅ **sub_statuses table:** Sub-statuses for each status step (e.g., Document Collection, Interview Preparation, etc.)
  - ✅ **Soft Deletes:** All 14 models/tables now support soft deletes (deleted_at column)
- **UI Features Implemented:**
  - ✅ Compact card-based grid layout (3 cards per row) with country flags
  - ✅ Switch toggles for country-level and status-level active/inactive states
  - ✅ Numbered status badges (1, 2, 3...) with custom names display
  - ✅ Action buttons: View, Edit, Delete, Notes, Reorder, Add Step
  - ✅ Status-level actions: Edit (pencil), Add Sub-Status (plus), View Sub-Statuses (list), Delete (trash)
  - ✅ Drag-and-drop reordering with @dnd-kit library
  - ✅ Sub-status sheet with Shadcn Sheet component showing all sub-statuses
  - ✅ AlertDialog confirmations for delete operations (replacing native confirm)
  - ✅ Smooth animations and real-time UI updates
  - ✅ "System Status" badge for protected "New" status
  - ✅ Responsive design with proper spacing and gaps
  - ✅ Pagination with Previous/Next buttons and page numbers (shadcn/ui)
  - ✅ Smart ellipsis display for large page counts
  - ✅ All pages: index, create, edit, show, notes, reorder
  - ✅ **Full dark mode support across all pages** (October 17, 2025)
  - ✅ Icon container backgrounds adapt to dark mode (100→900 color variants)
  - ✅ Text colors optimized for readability in both light and dark themes
  - ✅ Consistent color palette across all section headers
  - ✅ Proper contrast ratios maintained in dark mode
- **Advanced Features:**
  - ✅ Custom dialog hook (useDialog) for state management
  - ✅ Multi-mode dialogs (Add/Edit status and sub-status)
  - ✅ Real-time sheet data updates without page reload
  - ✅ Smooth closing animations with delayed data reset
  - ✅ Auto-save on reorder with visual feedback
  - ✅ "New" status protection (cannot edit, delete, reorder, toggle, or add sub-statuses)
  - ✅ Auto-calculation of order numbers for new statuses
  - ✅ Form validation with Laravel Form Requests
  - ✅ Type-safe routes with Laravel Wayfinder
  - ✅ Comprehensive error handling with null safety
  - ✅ Generic PaginatedData<T> type for reusability
  - ✅ Centralized TypeScript interfaces in global types file
  - ✅ RepCountryStatusSeeder for automatic status assignment to all countries (October 17, 2025)
  - ✅ Enhanced MultiSelect component with intelligent disabled option preservation (October 17, 2025)
  - ✅ MultiSelect automatically protects disabled options across all user interactions
  - ✅ Comprehensive dark mode implementation with Tailwind CSS v4 dark: variants (October 17, 2025)
  - ✅ Seamless theme switching via useAppearance hook (light/dark/system)
  - ✅ All color classes properly mapped for dark mode compatibility
- **Architecture:**
  - **Global Statuses:** `application_processes` table contains template statuses shared across all countries
  - **Country Instances:** `rep_country_status` table creates customizable instances per representing country
  - **Sub-Statuses:** `sub_statuses` table allows country-specific sub-steps for each status
  - **Soft Deletes:** All deletions are soft (recoverable), with cascading soft deletes for relationships
- **TypeScript Architecture:**
  - **Global Types:** All model interfaces centralized in `resources/js/types/index.d.ts`
  - **Exported Types:** Country, SubStatus, RepCountryStatus, RepresentingCountry, ApplicationProcess, PaginatedData<T>
  - **Type Safety:** Full type safety across all 6 representing-countries pages (index, create, edit, show, notes, reorder)
  - **Reusability:** Generic `PaginatedData<T>` type can be used for any paginated resource
  - **Null Safety:** All optional fields properly typed with `?` and defensive checks in components
- **Relationships:**
  - Country → Has One RepresentingCountry (nullable)
  - Country → Has Many Institutions → Has Many Courses
  - RepresentingCountry → Belongs To Country
  - RepresentingCountry → Has Many RepCountryStatuses (ordered)
  - RepCountryStatus → Belongs To RepresentingCountry
  - RepCountryStatus → Has Many SubStatuses
  - SubStatus → Belongs To RepCountryStatus

**TASK-004: Multi-Branch Management System**
- [ ] Create Branch model with factory and seeder
- [ ] Create branch CRUD operations (Controller, Actions, Requests)
- [ ] Implement branch settings (currency, timezone, working hours)
- [ ] Assign representing countries to each branch
- [ ] Create branch territories/regions management
- [ ] Build branch listing page (Inertia + shadcn/ui)
- [ ] Build branch creation/edit form with validation
- [ ] Implement inter-branch data visibility controls
- [ ] Create branch-level dashboard with country-specific metrics
- [ ] Write Pest tests for branch management
- **Related User Stories:** US-001, US-002, US-003, US-004, US-005, US-006
- **Priority:** P0 - Critical

**TASK-005: Role-Based Access Control (RBAC)**
- [ ] Install Spatie Laravel Permission package (spatie/laravel-permission)
- [ ] Run package migrations for roles and permissions tables
- [ ] Define predefined roles (Admin, Branch Manager, Counsellor, Processing Officer, Front Office, Finance)
- [ ] Define granular permissions (create-lead, edit-application, view-reports, etc.)
- [ ] Role based routes because some routes will be the same like /admin/representing-countries and /branch/representing-countries
- [ ] Create role assignment functionality
- [ ] Build custom role creation interface with shadcn/ui
- [ ] Implement middleware for permission checking
- [ ] Implement IP whitelisting per user/branch
- [ ] Build user management CRUD interface
- [ ] Write Pest tests for RBAC
- **Related User Stories:** US-007, US-008, US-009, US-010, US-011, US-012
- **Priority:** P0 - Critical
- **Package:** spatie/laravel-permission

**TASK-006: User Activity Audit System**
- [ ] Install Spatie Laravel Activitylog package (spatie/laravel-activitylog)
- [ ] Run package migrations for activity_log table
- [ ] Configure activity logging for all models (User, Lead, Application, etc.)
- [ ] Log user actions with timestamps, IP addresses, and changed properties
- [ ] Create activity log viewer interface with shadcn/ui
- [ ] Implement filtering and search for audit logs
- [ ] Add export functionality for audit logs (CSV/Excel)
- [ ] Write Pest tests for audit logging
- **Related User Stories:** US-010
- **Priority:** P0 - Critical
- **Package:** spatie/laravel-activitylog

**TASK-007: White-Label Branding System**
- [ ] Create organization_settings table migration
- [ ] Implement logo upload functionality
- [ ] Build color scheme customization interface
- [ ] Create email template management system
- [ ] Implement custom domain configuration
- [ ] Apply branding across all pages
- [ ] Write Pest tests for branding features
- **Related User Stories:** US-013, US-014, US-015, US-016
- **Priority:** P1 - High

---

### 1.3 Institution & Course Management

**TASK-008: Institution Database Management**
- [ ] Create Institution model with factory and seeder
- [ ] Add country_id foreign key to institutions table (belongs to country)
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
- **Related User Stories:** US-017, US-018, US-019, US-020, US-021, US-022
- **Priority:** P0 - Critical
- **Package:** spatie/laravel-medialibrary
- **Relationship:** Country → Has Many Institutions

**TASK-009: Course Catalog Management**
- [ ] Create Course model with factory and seeder
- [ ] Add institution_id foreign key to courses table (belongs to institution)
- [ ] Create course CRUD operations (Controller, Actions, Requests)
- [ ] Implement course categorization (Level, Subject)
- [ ] Create intake management (Fall/Spring/Summer with dates)
- [ ] Implement tuition fees and scholarship fields
- [ ] Add entry requirements and English language criteria
- [ ] Build course duration and career outcomes fields
- [ ] Create course listing page with advanced filters (by country, institution, level, subject)
- [ ] Implement full-text search functionality
- [ ] Write Pest tests for course management
- **Related User Stories:** US-023, US-024, US-025, US-026
- **Priority:** P0 - Critical
- **Relationship:** Institution → Has Many Courses
- **Flow:** Country → Institution → Courses

**TASK-010: Course Search & Comparison Features**
- [ ] Build advanced filter UI with shadcn/ui (representing country, institution, subject, level, intake, budget)
- [ ] Implement cascading filters (Country → Institutions → Courses)
- [ ] Implement saved search filters functionality
- [ ] Create course comparison interface (up to 5 courses)
- [ ] Build side-by-side comparison view with shadcn/ui
- [ ] Show country and institution info in comparison
- [ ] Implement PDF export for course comparisons
- [ ] Add course comparison to favorites/bookmarks
- [ ] Write Pest tests for search and comparison
- **Related User Stories:** US-027, US-028, US-029
- **Priority:** P0 - Critical
- **Note:** Filters are cascading: Select Country → See Institutions → See Courses

---

### 1.4 Lead & Application Management

**TASK-011: Lead Management System**
- [ ] Create Lead model with factory and seeder
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
- **Related User Stories:** US-031, US-032, US-033, US-034, US-035, US-036, US-037, US-038, US-039, US-040, US-041
- **Priority:** P0 - Critical
- **Package:** spatie/laravel-activitylog

**TASK-012: Student Profile Management**
- [ ] Create Student model with factory and seeder
- [ ] Implement Spatie Media Library trait on Student model
- [ ] Build student profile CRUD operations
- [ ] Implement comprehensive student information fields
- [ ] Create academic background tracking
- [ ] Build student document management using Spatie Media Library
- [ ] Configure media collections (passport, transcripts, certificates, etc.)
- [ ] Create student communication history
- [ ] Build student detail page
- [ ] Write Pest tests for student management
- **Related User Stories:** US-042, US-044, US-045
- **Priority:** P0 - Critical
- **Package:** spatie/laravel-medialibrary

**TASK-013: Application Management System**
- [ ] Create Application model with factory and seeder
- [ ] Implement Spatie Media Library trait on Application model
- [ ] Implement Spatie Activitylog trait on Application model
- [ ] Implement "One Student, Multiple Applications" architecture
- [ ] Create application CRUD operations
- [ ] Build cascading selection flow: Representing Country → Institution (within country) → Course (within institution) → Intake
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
- **Related User Stories:** US-042, US-043, US-044, US-045, US-046, US-047, US-048, US-049, US-050, US-051, US-052
- **Priority:** P0 - Critical
- **Packages:** spatie/laravel-medialibrary, spatie/laravel-activitylog

**TASK-014: Country-Specific Application Workflows** ✅ **COMPLETED**
- [x] Create application_processes table with global status templates (12 statuses)
- [x] Create rep_country_status table for country-specific status instances
- [x] Create sub_statuses table for status sub-steps
- [x] Seed 12 default application statuses (New, Application On Hold, Pre-Application Process, Rejected by University, Application Submitted, Conditional Offer, Pending Interview, Unconditional Offer, Acceptance, Visa Processing, Enrolled, Dropped)
- [x] Auto-create status instances for each representing country
- [x] Add soft deletes to all status-related tables
- [x] Add order field to sequence application stages per country
- [x] Add custom_name field for country-specific status naming
- [x] Add notes field for detailed stage information
- [x] Add is_active field for status visibility control per country
- [x] Create workflow configuration interface for admins per representing country
- [x] Build UI to manage status steps (add/edit/delete/reorder stages)
- [x] Build UI to manage sub-statuses (add/edit/delete with real-time updates)
- [x] Implement drag-and-drop reorder with @dnd-kit library
- [x] Create dedicated reorder page with auto-save functionality
- [x] Implement "New" status protection (system status that cannot be modified)
- [x] Build representing country-specific status customization
- [x] Write Pest tests for status management (27 tests)
- [x] Write Pest tests for reorder functionality (9 tests)
- [x] Write Pest tests for "New" status protection
- [x] Write Pest tests for soft deletes and cascading deletes
- **Related User Stories:** US-166, US-167, US-168, US-169, US-170, US-171, US-172, US-173
- **Priority:** P0 - Critical
- **Status:** ✅ **FULLY COMPLETED** - Database architecture refactored, all UI and management interfaces implemented
- **Database Architecture (Refactored October 16, 2025):**
  - ✅ **application_processes:** 12 global status templates
  - ✅ **rep_country_status:** Country-specific status instances (customizable names, order, active state)
  - ✅ **sub_statuses:** Flexible sub-steps for each status per country
  - ✅ **Soft Deletes:** All status deletions are soft with cascading to sub-statuses
- **UI Features:**
  - ✅ Drag-and-drop reordering with visual feedback
  - ✅ Add/Edit/Delete status steps with dialogs
  - ✅ Add/Edit/Delete sub-statuses with sheet view
  - ✅ Real-time UI updates without page reload
  - ✅ "New" status protection across all operations
  - ✅ AlertDialog confirmations for deletions
  - ✅ Smooth animations for sheets and dialogs
- **Note:** The new architecture allows each representing country to have its own customized workflow while sharing common status templates. Countries can add custom statuses, reorder steps, rename statuses, and define country-specific sub-statuses (e.g., "CAS Obtained" for UK, "I-20 Received" for USA).

---

### 1.5 Follow-up & Task Management

**TASK-015: Follow-up Management System**
- [ ] Create FollowUp model with factory and seeder
- [ ] Build manual follow-up scheduling interface
- [ ] Implement follow-up date, time, and notes fields
- [ ] Create automated follow-up triggers based on status
- [ ] Build follow-up reminder notifications (email + in-app)
- [ ] Implement overdue follow-up alerts
- [ ] Create follow-up history and outcome recording
- [ ] Build follow-up calendar view
- [ ] Implement follow-up listing and filtering
- [ ] Write Pest tests for follow-up management
- **Related User Stories:** US-053, US-054, US-055, US-056, US-057, US-060, US-061
- **Priority:** P0 - Critical

**TASK-016: Task Management System**
- [ ] Create Task model with factory and seeder
- [ ] Build task CRUD operations
- [ ] Implement task categories (Documentation, Follow-up, Internal, Urgent)
- [ ] Create task priority levels (Low, Medium, High, Critical)
- [ ] Build task assignment to individuals/teams
- [ ] Implement subtask functionality
- [ ] Create task comments and updates
- [ ] Build task completion workflow
- [ ] Create "My Tasks" view
- [ ] Create "Team Tasks" view
- [ ] Implement task filtering and sorting
- [ ] Write Pest tests for task management
- **Related User Stories:** US-062, US-063, US-064, US-065, US-066, US-067, US-068, US-070, US-071, US-072
- **Priority:** P1 - High

---

### 1.6 Basic Reporting

**TASK-017: Standard Reports Foundation**
- [ ] Set up reporting infrastructure
- [ ] Create Lead Reports (source analysis, conversion funnel)
- [ ] Create Application Reports (by status, by country/institution)
- [ ] Create Branch Reports (application volume, conversion rates)
- [ ] Create Counsellor Reports (performance, applications processed)
- [ ] Implement date range filtering
- [ ] Build report export (PDF/Excel)
- [ ] Create basic dashboard with key metrics
- [ ] Write Pest tests for reporting
- **Related User Stories:** US-107, US-108, US-109, US-112, US-113, US-117, US-121, US-122, US-137, US-139, US-140
- **Priority:** P0 - Critical

---

### 1.7 Student Portal (Basic)

**TASK-018: Student Portal API & Interface**
- [ ] Create student authentication system
- [ ] Build student login page
- [ ] Create student dashboard
- [ ] Implement application status tracking view
- [ ] Build timeline view for application progress
- [ ] Create document upload interface for students
- [ ] Implement student-counsellor communication
- [ ] Create API endpoints for student portal
- [ ] Write Pest tests for student portal
- [ ] Write Pest browser tests for student experience
- **Related User Stories:** US-144, US-145, US-146, US-147, US-148, US-149
- **Priority:** P0 - Critical

---

## Phase 2: Enhancement (Months 5-7)

### 2.1 Finance Management

**TASK-019: Financial Tracking System**
- [ ] Create Payment model with factory and seeder
- [ ] Implement payment tracking (application fees, service fees, tuition deposits)
- [ ] Create commission tracking from institutions
- [ ] Build installment plan management
- [ ] Implement payment status workflow (Pending, Partial, Paid, Overdue)
- [ ] Create payment reminder automation
- [ ] Build payment reconciliation interface
- [ ] Create payment listing and filtering
- [ ] Write Pest tests for financial tracking
- **Related User Stories:** US-093, US-094, US-095, US-096, US-097, US-098
- **Priority:** P0 - Critical

**TASK-020: Invoicing System**
- [ ] Create Invoice model with factory and seeder
- [ ] Build invoice generation for students
- [ ] Build invoice generation for institutions
- [ ] Create customizable invoice templates
- [ ] Implement multiple currency support
- [ ] Add tax calculation (VAT/GST)
- [ ] Build invoice status tracking
- [ ] Implement automated payment reminder emails
- [ ] Create invoice types (student service fee, tuition deposit, commission, partner commission)
- [ ] Build invoice listing and management interface
- [ ] Add invoice PDF generation and download
- [ ] Write Pest tests for invoicing system
- **Related User Stories:** US-099, US-100, US-101, US-102, US-103, US-104, US-105, US-106
- **Priority:** P1 - High

**TASK-021: Financial Reports**
- [ ] Create revenue by branch/counsellor reports
- [ ] Build commission tracking reports
- [ ] Implement outstanding payments reports
- [ ] Create payment collection rate reports
- [ ] Add financial analytics to dashboard
- [ ] Write Pest tests for financial reports
- **Related User Stories:** US-133, US-134, US-135, US-136
- **Priority:** P1 - High

---

### 2.2 Advanced Reporting & Analytics

**TASK-022: Comprehensive Reporting Suite**
- [ ] Implement lead aging reports
- [ ] Create lost lead reasons analysis
- [ ] Build application success rate tracking
- [ ] Create processing time analysis
- [ ] Implement visa approval/rejection rate reports
- [ ] Build team productivity metrics
- [ ] Create follow-up completion rate reports
- [ ] Implement target vs achievement tracking
- [ ] Build task completion metrics
- [ ] Create country-wise success rate reports
- [ ] Implement intake-wise application reports
- [ ] Write Pest tests for all reports
- **Related User Stories:** US-110, US-111, US-114, US-115, US-116, US-120, US-123, US-124, US-125, US-126, US-127, US-128, US-129, US-130, US-131, US-132
- **Priority:** P1 - High

**TASK-023: Business Intelligence Dashboard**
- [ ] Design executive dashboard layout
- [ ] Implement visual charts (line, bar, pie, funnel) using Chart.js or similar
- [ ] Create custom report builder interface
- [ ] Implement scheduled report delivery via email
- [ ] Build dashboard customization for different roles
- [ ] Add real-time data updates
- [ ] Create dashboard widgets system
- [ ] Write Pest tests for BI dashboard
- **Related User Stories:** US-137, US-138, US-141, US-142, US-143
- **Priority:** P1 - High

---

### 2.3 Third-Party Integrations

**TASK-024: Google Calendar Integration**
- [ ] Set up Google Calendar API credentials
- [ ] Implement OAuth2 authentication for Google
- [ ] Build bidirectional sync for follow-ups
- [ ] Create calendar event creation/update/delete
- [ ] Implement sync status tracking
- [ ] Build calendar disconnect/reconnect functionality
- [ ] Write Pest tests for Google Calendar integration
- **Related User Stories:** US-058
- **Priority:** P1 - High

**TASK-025: WhatsApp Business API Integration**
- [ ] Set up WhatsApp Business API account
- [ ] Implement WhatsApp messaging functionality
- [ ] Create click-to-chat from lead/application pages
- [ ] Build message template management
- [ ] Implement message history tracking
- [ ] Write Pest tests for WhatsApp integration
- **Related User Stories:** US-059, US-089
- **Priority:** P1 - High

**TASK-026: SMS Gateway Integration**
- [ ] Set up SMS gateway (Twilio or similar)
- [ ] Implement SMS sending functionality
- [ ] Create SMS template management
- [ ] Build SMS notification triggers
- [ ] Implement SMS history tracking
- [ ] Add SMS delivery status tracking
- [ ] Write Pest tests for SMS integration
- **Related User Stories:** US-088
- **Priority:** P1 - High

**TASK-027: Email System Enhancement**
- [ ] Configure SMTP for email sending
- [ ] Create email template library
- [ ] Build email template editor with rich text
- [ ] Implement automated email reminders
- [ ] Create email communication tracking
- [ ] Build email scheduling functionality
- [ ] Write Pest tests for email system
- **Related User Stories:** US-087, US-090, US-091, US-092
- **Priority:** P1 - High

**TASK-028: Payment Gateway Integration**
- [ ] Set up Stripe integration
- [ ] Set up PayPal integration
- [ ] Set up Razorpay integration (for Indian market)
- [ ] Implement online payment processing
- [ ] Build payment confirmation and receipts
- [ ] Create refund handling
- [ ] Write Pest tests for payment gateways
- **Related User Stories:** Finance-related stories
- **Priority:** P1 - High

**TASK-029: Cloud Storage Integration**
- [ ] Set up AWS S3 or Azure Blob Storage
- [ ] Implement document upload to cloud storage
- [ ] Create secure document access/download
- [ ] Build document versioning
- [ ] Implement storage quota management
- [ ] Write Pest tests for cloud storage
- **Related User Stories:** Document management stories
- **Priority:** P1 - High

---

### 2.4 Associate & Partner Management

**TASK-030: Associate Partner Portal**
- [ ] Create Associate model with factory and seeder
- [ ] Build associate account creation
- [ ] Implement associate authentication
- [ ] Create associate-specific dashboard
- [ ] Build limited access application submission
- [ ] Implement application status viewing for associates
- [ ] Create commission tracking per application for associates
- [ ] Build document upload for associates
- [ ] Implement communication thread with main agency
- [ ] Create access restrictions (no financial reports, no other partners' data)
- [ ] Write Pest tests for associate portal
- [ ] Write Pest browser tests for associate experience
- **Related User Stories:** US-073, US-074, US-075, US-076, US-077, US-078, US-079, US-080, US-081
- **Priority:** P1 - High

---

### 2.5 Communication & Notifications

**TASK-031: Announcement Management System**
- [ ] Create Announcement model with factory and seeder
- [ ] Build announcement CRUD operations
- [ ] Implement target audience selection (organization, branches, roles)
- [ ] Create scheduled announcement publishing
- [ ] Build announcement pinning functionality
- [ ] Implement announcement expiry dates
- [ ] Create rich text editor for announcements
- [ ] Add attachment support to announcements
- [ ] Build announcement listing for users
- [ ] Write Pest tests for announcement system
- **Related User Stories:** US-082, US-083, US-084, US-085, US-086
- **Priority:** P1 - High

**TASK-032: Notification System**
- [ ] Create notifications table migration
- [ ] Implement in-app notification system
- [ ] Build notification center UI
- [ ] Create email notification templates
- [ ] Implement push notifications infrastructure
- [ ] Build notification preferences for users
- [ ] Create notification batching and scheduling
- [ ] Write Pest tests for notification system
- **Related User Stories:** US-055, US-060, US-164
- **Priority:** P1 - High

---

### 2.6 Task Management Enhancement

**TASK-033: Recurring Task Templates**
- [ ] Create task templates table migration
- [ ] Build recurring task configuration
- [ ] Implement task automation based on templates
- [ ] Create template management interface
- [ ] Build task generation from templates
- [ ] Write Pest tests for recurring tasks
- **Related User Stories:** US-069
- **Priority:** P1 - High

---

### 2.7 Mobile Optimization

**TASK-034: Responsive Design Implementation**
- [ ] Audit all pages for mobile responsiveness
- [ ] Optimize navigation for mobile devices
- [ ] Implement mobile-friendly forms
- [ ] Create mobile-optimized dashboards
- [ ] Build touch-friendly UI components
- [ ] Optimize images and assets for mobile
- [ ] Test on various devices and screen sizes
- [ ] Write Pest browser tests for mobile viewports
- **Related User Stories:** US-159, US-160, US-161, US-162, US-163
- **Priority:** P0 - Critical

---

## Phase 3: Scale (Months 8-10)

### 3.1 API Marketplace & External Integrations

**TASK-035: Public API Development**
- [ ] Design RESTful API structure
- [ ] Implement API authentication (OAuth2, API keys)
- [ ] Create API rate limiting
- [ ] Build API versioning (v1)
- [ ] Implement webhook system
- [ ] Create API documentation with examples
- [ ] Build API testing playground
- [ ] Write Pest tests for all API endpoints
- **Related User Stories:** US-155, US-156, US-157, US-158
- **Priority:** P1 - High

**TASK-036: Website Integration Tools**
- [ ] Create JavaScript enquiry form widget
- [ ] Build customizable form field configuration
- [ ] Implement automatic lead creation from forms
- [ ] Create lead source attribution system
- [ ] Build real-time lead notification system
- [ ] Create widget documentation and examples
- [ ] Write Pest tests for widget integration
- **Related User Stories:** US-150, US-151, US-152, US-153, US-154
- **Priority:** P1 - High

**TASK-037: SSO Implementation**
- [ ] Implement Single Sign-On support
- [ ] Configure SAML/OAuth2 providers
- [ ] Build SSO configuration interface
- [ ] Create user provisioning from SSO
- [ ] Write Pest tests for SSO
- **Related User Stories:** US-155
- **Priority:** P1 - High

---

### 3.2 Native Mobile Apps

**TASK-038: iOS Mobile App Development**
- [ ] Set up iOS project with React Native or Flutter
- [ ] Implement authentication
- [ ] Build core features (leads, applications, tasks, follow-ups)
- [ ] Implement offline mode for data viewing
- [ ] Create push notifications
- [ ] Build document upload via camera
- [ ] Implement biometric authentication
- [ ] Test and optimize performance
- [ ] Submit to App Store
- **Related User Stories:** US-165, Mobile support stories
- **Priority:** P2 - Medium (Phase 3)

**TASK-039: Android Mobile App Development**
- [ ] Set up Android project
- [ ] Implement authentication
- [ ] Build core features
- [ ] Implement offline mode
- [ ] Create push notifications
- [ ] Build document upload via camera
- [ ] Implement biometric authentication
- [ ] Test on various Android devices
- [ ] Submit to Google Play Store
- **Related User Stories:** US-165, Mobile support stories
- **Priority:** P2 - Medium (Phase 3)

---

### 3.3 Advanced Automation & AI

**TASK-040: AI-Powered Recommendations**
- [ ] Implement course recommendation engine based on student profile
- [ ] Create lead scoring and prioritization
- [ ] Build predictive analytics for application success
- [ ] Implement intelligent lead assignment
- [ ] Create automated document classification
- [ ] Build chatbot for student queries
- [ ] Write Pest tests for AI features
- **Related User Stories:** Enhancement to existing stories
- **Priority:** P2 - Medium (Phase 3)

**TASK-041: Advanced Workflow Automation**
- [ ] Create workflow automation builder
- [ ] Implement custom automation rules
- [ ] Build trigger-action system
- [ ] Create automation templates
- [ ] Implement conditional logic in automations
- [ ] Write Pest tests for automation system
- **Related User Stories:** Automation-related stories
- **Priority:** P2 - Medium (Phase 3)

---

### 3.4 Multi-Language Support

**TASK-042: Internationalization (i18n)**
- [ ] Set up Laravel localization
- [ ] Implement language switching
- [ ] Translate UI to Spanish
- [ ] Translate UI to Arabic (RTL support)
- [ ] Translate UI to Hindi
- [ ] Translate UI to Chinese
- [ ] Create translation management interface for admins
- [ ] Write Pest tests for i18n
- **Related User Stories:** Usability requirements
- **Priority:** P2 - Medium (Phase 3)

---

### 3.5 Enhanced White-Label Customization

**TASK-043: Advanced White-Label Features**
- [ ] Implement theme builder for agencies
- [ ] Create custom CSS injection
- [ ] Build custom homepage layouts
- [ ] Implement custom navigation configuration
- [ ] Create custom field management
- [ ] Build custom report templates
- [ ] Write Pest tests for customization features
- **Related User Stories:** US-013, US-014, US-015, US-016 (Enhanced)
- **Priority:** P2 - Medium (Phase 3)

---

## Technical Infrastructure

### 4.1 Performance Optimization

**TASK-044: Performance Tuning**
- [ ] Implement database query optimization
- [ ] Add database indexes for frequently queried fields
- [ ] Set up Redis for caching
- [ ] Implement query result caching
- [ ] Optimize asset loading with lazy loading
- [ ] Implement CDN for static assets
- [ ] Set up database connection pooling
- [ ] Implement API response caching
- [ ] Create performance monitoring
- [ ] Write performance tests
- **Related User Stories:** Non-functional requirements
- **Priority:** P0 - Critical

**TASK-045: Scalability Implementation**
- [ ] Set up cloud infrastructure (AWS/Azure/GCP)
- [ ] Implement auto-scaling groups
- [ ] Configure load balancing
- [ ] Set up database replication
- [ ] Implement queue workers (Laravel Horizon)
- [ ] Create job processing for background tasks
- [ ] Set up monitoring and alerting
- [ ] Write load tests
- **Related User Stories:** Non-functional requirements
- **Priority:** P0 - Critical

---

### 4.2 Security Hardening

**TASK-046: Security Implementation**
- [ ] Implement SSL/TLS encryption
- [ ] Set up data encryption at rest
- [ ] Configure security headers
- [ ] Implement XSS protection
- [ ] Set up SQL injection prevention
- [ ] Create GDPR compliance features (data export, right to be forgotten)
- [ ] Implement automated backups (daily, 30-day retention)
- [ ] Set up backup restoration process
- [ ] Create disaster recovery plan
- [ ] Conduct security audit and penetration testing
- [ ] Write security tests
- **Related User Stories:** Security requirements
- **Priority:** P0 - Critical

---

### 4.3 DevOps & Deployment

**TASK-047: CI/CD Pipeline**
- [ ] Set up GitHub Actions or GitLab CI
- [ ] Create automated testing pipeline
- [ ] Implement code quality checks (Pint, Larastan)
- [ ] Set up automated deployment to staging
- [ ] Create production deployment pipeline
- [ ] Implement rollback procedures
- [ ] Set up environment management
- [ ] Create deployment documentation
- **Related User Stories:** Infrastructure
- **Priority:** P0 - Critical

**TASK-048: Monitoring & Logging**
- [ ] Set up application logging
- [ ] Implement error tracking (Sentry or similar)
- [ ] Create performance monitoring (New Relic or similar)
- [ ] Set up uptime monitoring
- [ ] Implement log aggregation
- [ ] Create alerting for critical issues
- [ ] Build admin monitoring dashboard
- **Related User Stories:** Non-functional requirements
- **Priority:** P0 - Critical

---

## Testing & Quality Assurance

### 5.1 Automated Testing

**TASK-049: Comprehensive Test Suite**
- [ ] Write unit tests for all models
- [ ] Write unit tests for all actions
- [ ] Create feature tests for all API endpoints
- [ ] Write feature tests for all web routes
- [ ] Create Pest browser tests for critical user flows
- [ ] Write tests for all authentication flows
- [ ] Create tests for RBAC and permissions
- [ ] Write tests for all integrations
- [ ] Implement test data factories and seeders
- [ ] Achieve minimum 80% code coverage
- **Related User Stories:** All stories
- **Priority:** P0 - Critical

**TASK-050: Browser Testing Suite**
- [ ] Write Pest v4 browser tests for user registration/login
- [ ] Create browser tests for lead management flow
- [ ] Write browser tests for application creation flow
- [ ] Create browser tests for course search and comparison
- [ ] Write browser tests for student portal
- [ ] Create browser tests for associate portal
- [ ] Write browser tests for mobile responsive design
- [ ] Test across browsers (Chrome, Firefox, Safari, Edge)
- **Related User Stories:** All UI-related stories
- **Priority:** P0 - Critical

---

### 5.2 User Acceptance Testing

**TASK-051: UAT Preparation**
- [ ] Create UAT test scenarios for all user roles
- [ ] Prepare test data and environments
- [ ] Create UAT documentation
- [ ] Build UAT feedback collection system
- [ ] Conduct UAT sessions with stakeholders
- [ ] Document and fix UAT issues
- **Related User Stories:** All stories
- **Priority:** P0 - Critical

---

### 5.3 Documentation

**TASK-052: Technical Documentation**
- [ ] Create API documentation (OpenAPI/Swagger)
- [ ] Write developer setup guide
- [ ] Create architecture documentation
- [ ] Write deployment guide
- [ ] Create database schema documentation
- [ ] Write code contribution guidelines
- **Related User Stories:** Developer stories
- **Priority:** P1 - High

**TASK-053: User Documentation**
- [ ] Create user manual for each role
- [ ] Write admin configuration guide
- [ ] Create video tutorials for key features
- [ ] Build contextual help and tooltips throughout app
- [ ] Create FAQ documentation
- [ ] Write troubleshooting guide
- **Related User Stories:** Usability requirements
- **Priority:** P1 - High

---

## Summary Statistics

- **Total Tasks:** 54 major task groups (added Country Representation Management)
- **Phase 1 (MVP):** 19 task groups
- **Phase 2 (Enhancement):** 16 task groups
- **Phase 3 (Scale):** 7 task groups
- **Infrastructure:** 5 task groups
- **Testing & QA:** 5 task groups
- **Documentation:** 2 task groups

## Spatie Packages Used

- **spatie/laravel-permission:** Role-Based Access Control (RBAC)
- **spatie/laravel-activitylog:** User activity audit trail and model history
- **spatie/laravel-medialibrary:** Document uploads and media management

---

## Task Dependencies

### Critical Path Tasks (Must be completed in order):
1. TASK-001 → TASK-002 → TASK-003 (Foundation)
2. TASK-003 → TASK-003A (Database Schema → Country Representation)
3. TASK-003A → TASK-004 → TASK-005 (Countries & Representing Countries → Branches → RBAC)
4. TASK-003A → TASK-008 (Countries before institutions - **institutions belong to countries**)
5. TASK-008 → TASK-009 (Institutions before courses - **courses belong to institutions**)
6. TASK-009 → TASK-010 (Courses before search/comparison)
7. TASK-011 → TASK-012 → TASK-013 (Leads → Students → Applications)
8. TASK-013 → TASK-014 (Applications before workflows)
9. TASK-044 → TASK-045 (Performance before scale)

**Data Flow:**
- **All Countries:** Country (193 world countries auto-seeded - verified via MCP)
- **Institutions:** Country → Institution → Course → Application
- **Study Abroad Details:** Country → RepresentingCountry → RepCountryStatuses → SubStatuses
- **Status System:** ApplicationProcess (12 global templates) → RepCountryStatus (country instances) → SubStatus (sub-steps)
- **Verified Data (via Laravel Boost MCP):**
  - ✅ 193 countries auto-seeded
  - ✅ 12 global application status templates
  - ✅ Country-specific status instances with customizable names and order
  - ✅ Sub-statuses for detailed step tracking per country
  - ✅ Soft deletes on all 14 models/tables
- **Note:**
  - Only countries the organization represents have a RepresentingCountry record with detailed information
  - ApplicationProcesses are global templates that get instantiated per country via rep_country_status
  - Each country can customize status names, reorder statuses, and add country-specific sub-statuses
  - "New" status is protected and cannot be modified or deleted across all countries
  - All models support soft deletes for data recovery

### Parallel Tasks (Can be developed simultaneously):
- TASK-015 & TASK-016 (Follow-ups and Tasks)
- TASK-024, TASK-025, TASK-026, TASK-027 (All integrations)
- TASK-038 & TASK-039 (iOS and Android apps)

---

## Priority Legend
- **P0 - Critical:** Must have for MVP or core functionality
- **P1 - High:** Important for enhanced user experience
- **P2 - Medium:** Nice to have, can be in later phases

---

**Document Status:** Ready for Development

**End of Document**
