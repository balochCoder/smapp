# System Architecture Overview

**Study Abroad Education Consultancy Management Platform**
**Version:** 0.2.0
**Date:** October 15, 2025

---

## Table of Contents
1. [Core Architecture](#core-architecture)
2. [Data Hierarchy](#data-hierarchy)
3. [Spatie Packages Integration](#spatie-packages-integration)
4. [Database Relationships](#database-relationships)
5. [Application Flow](#application-flow)
6. [Key Design Decisions](#key-design-decisions)

---

## Core Architecture

### System Overview
The platform is built on **Laravel 12** with **Inertia.js v2 (React)**, using **shadcn/ui** components for a modern, consistent UI.

### Tech Stack
- **Backend:** Laravel 12 (PHP 8.4.13)
- **Frontend:** React (via Inertia.js v2)
- **Database:** SQLite (dev), PostgreSQL/MySQL (production)
- **UI Framework:** Tailwind CSS + shadcn/ui
- **Testing:** Pest v4
- **Code Quality:** Laravel Pint, Larastan

---

## Data Hierarchy

### Primary Entity Relationship
```
Organization (Your Agency)
    ↓
Representing Countries (Countries you represent)
    ↓
Institutions (Partner Universities in those countries)
    ↓
Courses (Programs offered by institutions)
    ↓
Applications (Student applications to courses)
```

###Detailed Flow

```
┌─────────────────────────┐
│   Organization          │  ← Your consultancy agency
│   (Single instance)     │
└───────────┬─────────────┘
            │
            ↓
┌───────────────────────────────────────────┐
│   Representing Countries                  │
│   ┌─────┐  ┌─────┐  ┌─────┐  ┌─────┐    │
│   │ UK  │  │ USA │  │ CAN │  │ AUS │    │  ← Countries you represent
│   └─────┘  └─────┘  └─────┘  └─────┘    │
└────┬──────────┬──────────┬────────┬──────┘
     │          │          │        │
     ↓          ↓          ↓        ↓
┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐
│Oxford   │ │Harvard  │ │Toronto  │ │Sydney   │  ← Partner institutions
│UCL      │ │MIT      │ │McGill   │ │Melb.    │     (belongs to country)
│Imperial │ │Yale     │ │UBC      │ │ANU      │
└────┬────┘ └────┬────┘ └────┬────┘ └────┬────┘
     │           │           │           │
     ↓           ↓           ↓           ↓
┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐
│CS MSc    │ │MBA       │ │Business  │ │Medicine  │  ← Courses
│Law LLM   │ │Data Sci  │ │Eng MSc   │ │CS Master │     (belongs to institution)
│MBA       │ │Medicine  │ │MBA       │ │MBA       │
└────┬─────┘ └────┬─────┘ └────┬─────┘ └────┬─────┘
     │            │            │            │
     └────────────┴────────────┴────────────┘
                       │
                       ↓
              ┌────────────────┐
              │  Applications  │  ← Student applications
              │  (One Student, │     to specific courses
              │   Multiple     │
              │   Applications)│
              └────────────────┘
```

---

## Spatie Packages Integration

### 1. Spatie Laravel Permission (RBAC)
**Package:** `spatie/laravel-permission`
**Purpose:** Role-Based Access Control

**Usage:**
- **Roles:** Admin, Branch Manager, Counsellor, Processing Officer, Front Office, Finance
- **Permissions:** Granular permissions (e.g., create-lead, edit-application, view-financial-reports)
- **Models:** User model uses `HasRoles` trait
- **Middleware:** Permission and role middleware for route protection

**Example:**
```php
// Assign role to user
$user->assignRole('Counsellor');

// Check permission
if ($user->can('create-application')) {
    // Allow action
}

// In routes
Route::middleware(['permission:manage-branches'])->group(function() {
    // Protected routes
});
```

---

### 2. Spatie Laravel Medialibrary (Document Management)
**Package:** `spatie/laravel-medialibrary`
**Purpose:** File uploads and media management

**Usage:**
- **Student Documents:** Passport, transcripts, certificates, English test scores
- **Application Documents:** SOPs, LORs, offer letters, visa documents
- **Institution Marketing:** Brochures, campus images, promotional videos
- **Shared Documents:** Documents available across multiple applications for the same student

**Models Using Media Library:**
- Student
- Application
- Institution

**Media Collections:**
```php
// Student model
$student->addMedia($file)
    ->toMediaCollection('passport');

$student->addMedia($file)
    ->toMediaCollection('transcripts');

// Application model
$application->addMedia($file)
    ->toMediaCollection('offer_letters');

$application->addMedia($file)
    ->toMediaCollection('visa_documents');

// Institution model
$institution->addMedia($file)
    ->toMediaCollection('brochures');

$institution->addMedia($file)
    ->toMediaCollection('campus_images');
```

---

### 3. Spatie Laravel Activitylog (Audit Trail)
**Package:** `spatie/laravel-activitylog`
**Purpose:** Track all user actions and model changes

**Usage:**
- **Activity Logging:** All CRUD operations on critical models
- **Change Tracking:** Before/after values for model updates
- **User Actions:** Who did what, when, and from which IP
- **Application Timeline:** Visual timeline of all application actions

**Models Using Activity Log:**
- Lead
- Application
- Student
- Institution
- Course
- User

**Example:**
```php
// Application model
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Application extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'current_stage', 'notes'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}

// Retrieve activity
$activities = $application->activities;

// Custom log entry
activity()
    ->performedOn($application)
    ->causedBy($user)
    ->withProperties(['old_status' => 'submitted', 'new_status' => 'offer_received'])
    ->log('Application status updated to Offer Received');
```

---

## Database Relationships

### Core Relationships

#### One-to-Many
```php
// Country has many Institutions
Country::class
    ↓ hasMany
Institution::class (country_id)

// Institution has many Courses
Institution::class
    ↓ hasMany
Course::class (institution_id)

// Student has many Applications
Student::class
    ↓ hasMany
Application::class (student_id)

// Course has many Applications
Course::class
    ↓ hasMany
Application::class (course_id)

// Branch has many Users, Leads, Applications
Branch::class
    ↓ hasMany
User::class, Lead::class, Application::class
```

#### Polymorphic Relationships
```php
// Documents (using Spatie Media Library)
Student, Application, Institution
    ↓ morphMany
Media::class

// Activity Logs (using Spatie Activitylog)
Lead, Application, Student, etc.
    ↓ morphMany
Activity::class

// Tasks
Lead, Application, Student
    ↓ morphMany
Task::class

// Follow-ups
Lead, Application, Student
    ↓ morphMany
FollowUp::class
```

#### Many-to-Many (via Spatie Permission)
```php
// User roles and permissions
User::class
    ↔ belongsToMany
Role::class, Permission::class
```

---

## Application Flow

### 1. Lead Capture
```
Website Form / Manual Entry / Bulk Upload
    ↓
Lead Created (with preferred countries)
    ↓
Assigned to Counsellor
    ↓
Follow-ups scheduled
    ↓
Lead Qualified
```

### 2. Student Onboarding
```
Lead Converted to Student
    ↓
Student Profile Created (with Spatie Media Library)
    ↓
Documents Uploaded (passport, transcripts, etc.)
    ↓
Academic background entered
    ↓
Ready for Applications
```

### 3. Application Creation (Cascading Selection)
```
Step 1: Select Representing Country
    ↓
Step 2: Select Institution (filtered by selected country)
    ↓
Step 3: Select Course (filtered by selected institution)
    ↓
Step 4: Select Intake (Fall/Spring/Summer)
    ↓
Application Created
    ↓
Country-specific workflow applied
    ↓
Document checklist generated
```

### 4. Application Processing
```
Application Created (Draft)
    ↓
Documents Collected (using Spatie Media Library)
    ↓
Application Submitted to Institution
    ↓
Country-specific stages (e.g., UK: CAS, Canada: GIC, Australia: COE)
    ↓
Offer Received
    ↓
Visa Processing
    ↓
Enrolled

(All changes tracked by Spatie Activitylog)
```

### 5. One Student, Multiple Applications
```
Student Profile (Created Once)
    ├── Application 1: UK → Oxford → CS MSc → Fall 2026
    ├── Application 2: USA → MIT → Data Science → Spring 2026
    ├── Application 3: Canada → Toronto → MBA → Fall 2026
    └── Application 4: Australia → Sydney → CS Master → Fall 2026

Shared Documents across all applications:
- Passport
- Academic Transcripts
- English Test Scores
- Resume

Unified Communication History across all applications
```

---

## Key Design Decisions

### 1. Country as Foundation Entity
**Decision:** Representing countries are the primary entity that dictates available institutions and courses.

**Rationale:**
- Education consultancies represent specific countries
- Each country has unique visa processes and requirements
- Institutions operate within specific countries
- Filtering becomes hierarchical and organized

**Impact:**
- Clear data hierarchy
- Easier to manage country-specific workflows
- Better reporting by country/region
- Scalable for adding new countries

---

### 2. Institutions Belong to Countries
**Decision:** Institutions have a `country_id` foreign key.

**Rationale:**
- Universities are physically located in specific countries
- Country determines visa process and requirements
- Natural grouping for consultancy operations

**Impact:**
- Cascading filters: Select country → See only institutions in that country
- Country-specific commission structures
- Better organization of partner institutions

---

### 3. Using Spatie Packages
**Decision:** Use Spatie packages instead of custom implementations for RBAC, media management, and activity logging.

**Rationale:**
- **Battle-tested:** Spatie packages are industry standard
- **Maintained:** Regular updates and security patches
- **Feature-rich:** Comprehensive functionality out of the box
- **Community support:** Large community and excellent documentation
- **Time-saving:** Focus on business logic, not infrastructure

**Impact:**
- Faster development
- Higher code quality
- Better security
- Easier maintenance
- Standard Laravel patterns

---

### 4. One Student, Multiple Applications
**Decision:** Student profile is created once, with multiple applications referencing the same student.

**Rationale:**
- Students often apply to multiple universities
- Avoid duplicate data entry
- Shared documents across applications
- Unified communication history

**Impact:**
- Better user experience for counsellors
- Data integrity (single source of truth)
- Easier tracking of student's overall journey
- Reduced storage (shared documents)

---

### 5. Spatie Media Library for All File Uploads
**Decision:** Use Spatie Media Library instead of manual file storage.

**Rationale:**
- Automatic file management
- Multiple file formats support
- Media conversions and thumbnails
- Organized collections
- Easy retrieval and display

**Impact:**
- Consistent file handling across the app
- Better file organization
- Automatic cleanup
- Image optimization
- Flexible file types

---

### 6. Spatie Activitylog for Complete Audit Trail
**Decision:** Track all model changes and user actions using Spatie Activitylog.

**Rationale:**
- Compliance requirements (data audit)
- Transparency for students
- Debugging and support
- Performance tracking
- Security monitoring

**Impact:**
- Complete application timeline
- User accountability
- Better customer support
- Compliance with regulations
- Data recovery capability

---

## Security Architecture

### Authentication & Authorization
- **Authentication:** Laravel Fortify (registration, login, password reset, 2FA)
- **Authorization:** Spatie Laravel Permission (roles and permissions)
- **Session Management:** Laravel default (database sessions)
- **CSRF Protection:** Laravel built-in
- **IP Whitelisting:** Custom middleware (per user/branch)

### Data Protection
- **Encryption:** All sensitive data encrypted at rest
- **File Security:** Spatie Media Library with secure storage
- **Audit Trail:** Spatie Activitylog for all actions
- **Input Validation:** Laravel Form Requests
- **SQL Injection Prevention:** Laravel Eloquent ORM

---

## Performance Considerations

### Database Optimization
- Indexes on foreign keys and frequently queried fields
- Eager loading to prevent N+1 queries
- Database query caching (Redis)
- Pagination for large datasets

### File Storage
- Cloud storage (AWS S3/Azure Blob) for production
- CDN for static assets and media files
- Image optimization via Spatie Media Library
- Lazy loading for images

### Caching Strategy
- Redis for application cache
- Query result caching
- View caching
- Route caching

---

## Scalability Plan

### Horizontal Scaling
- Load balancer for multiple application servers
- Separate database server
- Separate cache server (Redis)
- Queue workers on dedicated servers

### Vertical Scaling
- Database optimization (indexes, query optimization)
- Code optimization (N+1 prevention, caching)
- Asset optimization (CDN, lazy loading)

---

## Testing Strategy

### Unit Tests (Pest)
- Model methods
- Actions
- Helpers
- Utilities

### Feature Tests (Pest)
- API endpoints
- Form validations
- Business logic
- Integrations

### Browser Tests (Pest v4)
- Critical user flows
- Application creation process
- Document uploads
- Multi-step forms
- Mobile responsiveness

---

**Document Status:** Active Reference
**Last Updated:** October 15, 2025
**Next Review:** As features are implemented

---

**End of Architecture Document**
