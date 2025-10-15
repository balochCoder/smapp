# Product Requirements Document (PRD)

## Study Abroad Education Consultancy Management Platform

**Version:** 1.0
**Date:** October 15, 2025
**Document Owner:** Product Team

---

## 1. Executive Summary

### 1.1 Product Overview
A comprehensive SaaS platform designed specifically for education consultancy agencies managing study abroad programs. The system enables multi-branch operations, streamlines student application processes, manages institutional partnerships, and provides end-to-end visibility across the entire student recruitment lifecycle.

### 1.2 Product Vision
To become the industry-leading platform that empowers education consultancies to scale their operations efficiently while delivering exceptional service to students pursuing international education opportunities.

### 1.3 Business Objectives
- Reduce manual administrative workload by 60%
- Increase application conversion rates through automated follow-ups
- Enable seamless multi-branch coordination and reporting
- Provide real-time visibility into business performance
- Enhance student experience through transparent application tracking

---

## 2. Target Users

### 2.1 Primary Users

**Head Office Level:**
- **Agency Administrators:** Overall system management and business oversight
- **Processing Office Staff:** Centralized application processing and documentation
- **Finance Team:** Financial tracking and invoicing across all branches

**Branch Office Level:**
- **Branch Managers:** Branch-level operations and team management
- **Counsellors:** Direct student interaction and application management
- **Front Office Staff:** Lead capture and initial student engagement
- **Associates:** Sub-agent partners working under specific branches

### 2.2 Secondary Users
- **Students:** Application status tracking and communication
- **University Partners:** Application status visibility

---

## 3. Core Functional Requirements

### 3.1 Organization & User Management

#### 3.1.1 Multi-Branch Management
**Priority:** P0 (Critical)

**Requirements:**
- Create and manage unlimited branch offices with hierarchical structure
- Configure branch-specific settings (currency, timezone, working hours)
- Define branch territories and operational regions
- Branch-level performance dashboards
- Inter-branch data visibility controls

**User Stories:**
- As an Administrator, I want to create new branch offices so that I can expand my operations geographically
- As a Branch Manager, I want to view only my branch's data so that I can focus on my team's performance

#### 3.1.2 Role-Based Access Control
**Priority:** P0 (Critical)

**Requirements:**
- Predefined roles: Admin, Branch Manager, Counsellor, Processing Officer, Front Office, Finance
- Custom role creation with granular permissions
- Multi-user access with concurrent session support
- IP whitelisting for enhanced security
- User activity logs and audit trails

**Acceptance Criteria:**
- Users can only access features permitted by their role
- IP restrictions can be configured at user or branch level
- All user actions are logged with timestamp and IP address

#### 3.1.3 White-Label Branding
**Priority:** P1 (High)

**Requirements:**
- Upload custom agency logo displayed on dashboard
- Customize color scheme and branding elements
- Agency-specific email templates
- Custom domain support for student portal

---

### 3.2 Institution & Course Management

#### 3.2.1 Institution Database
**Priority:** P0 (Critical)

**Requirements:**
- Comprehensive university/college profile management
- Support for institutions across multiple countries
- Store institution details: rankings, location, accreditation, facilities
- Upload and manage marketing materials (brochures, videos, images)
- Institution contact management
- Commission structure and partnership terms

**Data Fields:**
- Institution name, country, city, website
- Accreditation status
- Campus facilities
- Accommodation options
- Entry requirements
- Application fees and deadlines
- Marketing collateral library

#### 3.2.2 Course Catalog Management
**Priority:** P0 (Critical)

**Requirements:**
- Detailed course information database
- Course categorization (Level: UG/PG/Diploma, Subject area)
- Intake management (Fall, Spring, Summer intakes with dates)
- Tuition fees and scholarship information
- Entry requirements and English language criteria
- Course duration and credit hours
- Career outcomes and placement statistics

**Search Capabilities:**
- Advanced filtering: country, institution, subject, level, intake, budget
- Full-text search across course titles and descriptions
- Saved search filters
- Course comparison functionality (up to 5 courses)
- Export course comparison sheets to PDF

**User Stories:**
- As a Counsellor, I want to search courses by budget and subject so that I can quickly find suitable options for my students
- As a Counsellor, I want to generate a course comparison sheet so that I can help students make informed decisions

---

### 3.3 Lead & Application Management

#### 3.3.1 Lead Management System
**Priority:** P0 (Critical)

**Requirements:**
- Capture leads from multiple sources (manual entry, website forms, bulk upload)
- Lead assignment to counsellors (manual, round-robin, territory-based)
- Lead status tracking: New, Contacted, Qualified, Converted, Lost
- Lead source tracking and attribution
- Bulk lead upload via CSV/Excel (with validation)
- Transfer leads between counsellors within the same branch
- Duplicate lead detection and merging

**Lead Fields:**
- Personal information (name, email, phone, DOB)
- Academic background
- Preferred destination countries
- Budget range
- Intended intake
- Lead source and campaign
- Assigned counsellor

**Acceptance Criteria:**
- Bulk upload supports minimum 1000 leads at once
- Duplicate detection alerts counsellor before creating lead
- Lead assignment triggers automated email notification

#### 3.3.2 Application Management
**Priority:** P0 (Critical)

**Requirements:**
- **One Student, Multiple Applications:** Create multiple applications for same student without re-entering data
- Country-specific application workflows
- Custom application stages per country/institution
- Document checklist management
- Application status tracking with timeline view
- Transfer applications between branches
- Application history and activity log
- Conditional offer and final offer management

**Application Stages (Customizable):**
1. Documentation Collection
2. Application Preparation
3. Application Submitted
4. Awaiting Decision
5. Offer Received
6. Visa Processing
7. Enrolled

**One Student One Application Feature:**
- Student profile created once with complete information
- Add new applications by selecting: Country → Institution → Course → Intake
- Shared documents available across all applications for same student
- Unified student communication history

**User Stories:**
- As a Counsellor, I want to create a second application for an existing student without re-entering their details so that I can save time
- As a Processing Officer, I want to track all documents submitted for an application so that I know what's pending

---

### 3.4 Follow-up & Task Management

#### 3.4.1 Follow-up Management System
**Priority:** P0 (Critical)

**Requirements:**
- Manual follow-up scheduling with date, time, and notes
- Automated follow-up triggers based on lead/application status
- Follow-up reminders via email and in-app notifications
- Overdue follow-up alerts
- Follow-up history and outcome recording
- Google Calendar integration for follow-ups
- WhatsApp Web integration for communication

**Automated Follow-up Rules:**
- Lead not contacted in 24 hours → Reminder to counsellor
- Application documentation pending > 7 days → Student reminder
- Offer received but not accepted in 14 days → Follow-up alert
- Visa interview scheduled → Reminder 3 days before

**Acceptance Criteria:**
- Follow-ups sync bidirectionally with Google Calendar
- Users receive notification 30 minutes before scheduled follow-up
- WhatsApp integration allows click-to-chat from lead/application page

#### 3.4.2 Task Management
**Priority:** P1 (High)

**Requirements:**
- Create tasks with title, description, due date, assignee
- Task categories: Documentation, Follow-up, Internal, Urgent
- Task priority levels (Low, Medium, High, Critical)
- Task assignment to individual or team
- Subtask creation
- Task comments and updates
- Task completion workflow
- Recurring task templates

**Dashboards:**
- My Tasks view (personal task list)
- Team Tasks view (for managers)
- Overdue tasks report
- Task completion metrics

---

### 3.5 Associate & Partner Management

#### 3.5.1 Associate Partner Portal
**Priority:** P1 (High)

**Requirements:**
- Create associate partner accounts with login credentials
- Associate-specific dashboard with limited access
- Associates can submit applications on behalf of students
- View status of submitted applications
- Commission tracking per application
- Document upload capability for associates
- Communication thread with main agency

**Access Restrictions:**
- Associates cannot view other partners' applications
- Associates cannot access financial reports
- Associates see only approved institution list

---

### 3.6 Communication & Notifications

#### 3.6.1 Announcement Management
**Priority:** P1 (High)

**Requirements:**
- Create announcements for entire organization or specific branches/roles
- Schedule announcement publish date
- Pin important announcements
- Announcement expiry dates
- Rich text editor for announcements
- Attachment support

#### 3.6.2 Student Communication
**Priority:** P0 (Critical)

**Requirements:**
- Email communication from within the platform
- SMS notifications (integration with SMS gateway)
- WhatsApp messaging integration
- Automated email reminders for pending documents
- Application status update notifications to students
- Template library for common communications

---

### 3.7 Finance Management

#### 3.7.1 Financial Tracking
**Priority:** P0 (Critical)

**Requirements:**
- Track payments from students (application fees, service fees, tuition deposits)
- Track commission from institutions
- Installment plan management for student payments
- Payment status: Pending, Partial, Paid, Overdue
- Payment reminders to students
- Payment reconciliation

#### 3.7.2 Invoicing System
**Priority:** P1 (High)

**Requirements:**
- Generate invoices for students (service fees, consultation charges)
- Generate invoices for institutions (for services rendered)
- Customizable invoice templates with agency branding
- Multiple currency support
- Tax calculation (VAT/GST)
- Invoice status tracking
- Automated payment reminder emails

**Invoice Types:**
- Student service fee invoice
- Tuition deposit invoice
- Commission invoice to institution
- Partner commission invoice

---

### 3.8 Reporting & Analytics

#### 3.8.1 Standard Reports
**Priority:** P0 (Critical)

**Report Categories:**

**Lead Reports:**
- Lead source analysis
- Lead conversion funnel
- Counsellor performance (leads assigned vs converted)
- Lead aging report
- Lost lead reasons analysis

**Application Reports:**
- Applications by status
- Applications by country/institution
- Application success rate
- Processing time analysis
- Visa approval/rejection rates

**Branch Office Reports:**
- Branch-wise application volume
- Branch revenue and commission
- Branch-wise conversion rates
- Team productivity metrics

**Counsellor Reports:**
- Individual counsellor performance
- Applications processed per counsellor
- Conversion rates by counsellor
- Follow-up completion rates
- Target vs achievement

**Task Reports:**
- Completed tasks vs pending tasks
- Missed tasks by user
- Average task completion time
- Overdue tasks by priority

**Country-Specific Reports:**
- Annual applications by country
- Intake-wise applications (Fall/Spring/Summer)
- Popular destinations trending
- Country-wise success rates

**Financial Reports:**
- Revenue by branch/counsellor
- Commission tracking
- Outstanding payments
- Payment collection rate

#### 3.8.2 Business Intelligence Dashboard
**Priority:** P1 (High)

**Requirements:**
- Executive dashboard with key metrics
- Visual charts and graphs (line, bar, pie, funnel)
- Date range filtering
- Export reports to PDF/Excel
- Scheduled report delivery via email
- Custom report builder

**Key Metrics:**
- Total leads and conversion rate
- Active applications by stage
- Revenue (current month vs last month)
- Top performing counsellors
- Top destination countries
- Average processing time

---

### 3.9 Website Integration & APIs

#### 3.9.1 Student Portal API
**Priority:** P1 (High)

**Requirements:**
- Student login on agency website
- Application status tracking page embedded on website
- Document upload from student portal
- Timeline view of application progress
- Communication with counsellor via portal

**API Endpoints:**
- Student authentication
- Fetch application status
- Upload documents
- Retrieve messages
- Update student profile

#### 3.9.2 Enquiry Form Integration
**Priority:** P0 (Critical)

**Requirements:**
- JavaScript widget for embedding enquiry form on website
- Customizable form fields
- Form submissions automatically create leads in CRM
- Lead source attribution from website forms
- Real-time lead notification to assigned counsellor

#### 3.9.3 Staff Login API
**Priority:** P1 (High)

**Requirements:**
- Single Sign-On (SSO) support
- API for third-party integrations
- Webhook support for external systems
- API documentation with examples

---

### 3.10 Mobile & Tablet Support

#### 3.10.1 Responsive Design
**Priority:** P0 (Critical)

**Requirements:**
- Fully responsive web interface for smartphones and tablets
- Native mobile app (iOS/Android) - Phase 2
- Key mobile features:
  - View leads and applications
  - Update follow-ups on the go
  - Make notes and schedule tasks
  - Upload documents via camera
  - Push notifications for urgent tasks
  - Offline mode for viewing data (mobile app)

---

### 3.11 Country-Specific Application Processes

#### 3.11.1 Custom Workflow Configuration
**Priority:** P0 (Critical)

**Requirements:**
- Define application stages per country (e.g., UK vs Canada vs Australia)
- Country-specific document requirements
- Visa process tracking specific to country
- Country-specific deadlines and timelines
- Pre-populated templates for country-specific SOPs and LORs

**Examples:**
- **UK:** Application → Offer → CAS → Visa Application → Visa Decision
- **Canada:** Application → Offer → GIC → Visa Application → Biometrics → Visa Decision
- **Australia:** Application → Offer → COE → GTE → Visa Application → Visa Grant

---

## 4. Non-Functional Requirements

### 4.1 Performance
- Page load time < 2 seconds for standard operations
- Support 1000+ concurrent users
- Bulk operations (lead upload) complete within 5 minutes for 1000 records
- Report generation < 10 seconds for standard reports

### 4.2 Security
- SSL/TLS encryption for all data transmission
- Data encryption at rest
- Role-based access control (RBAC)
- IP whitelisting support
- Two-factor authentication (2FA) for admin users
- Regular security audits and penetration testing
- GDPR compliance for handling personal data
- Automated backups every 24 hours with 30-day retention

### 4.3 Scalability
- Cloud-based infrastructure (AWS/Azure/GCP)
- Auto-scaling based on load
- Support for unlimited branches and users
- Database optimization for large datasets (1M+ leads)

### 4.4 Availability
- 99.9% uptime SLA
- Scheduled maintenance windows with advance notice
- Disaster recovery plan with RTO < 4 hours

### 4.5 Usability
- Intuitive UI requiring minimal training
- Contextual help and tooltips
- Video tutorial library
- Comprehensive user documentation
- Multi-language support (English, Spanish, Arabic, Hindi, Chinese) - Phase 2

### 4.6 Compatibility
- Modern web browsers: Chrome, Firefox, Safari, Edge (latest 2 versions)
- Responsive design for tablets (iPad, Android tablets)
- Mobile browsers for smartphone access

---

## 5. Integration Requirements

### 5.1 Required Integrations
- **Email:** SMTP integration for sending emails
- **SMS:** Integration with SMS gateway providers (Twilio, etc.)
- **WhatsApp:** WhatsApp Business API integration
- **Google Calendar:** Bidirectional sync for follow-ups
- **Payment Gateways:** Stripe, PayPal, Razorpay for online payments
- **Cloud Storage:** AWS S3 / Azure Blob for document storage
- **Google Drive/Dropbox:** Optional for document backup

### 5.2 Future Integrations (Phase 2)
- Video calling (Zoom/Teams integration)
- E-signature (DocuSign)
- Accounting software (QuickBooks, Xero)
- Marketing automation platforms

---

## 6. Data Model (Key Entities)

### 6.1 Core Entities
- **Organization:** Agency details, branding, settings
- **Branch:** Branch offices under organization
- **User:** Staff members with roles and permissions
- **Student:** Student profiles with personal and academic details
- **Lead:** Potential student enquiries
- **Application:** Student applications to institutions
- **Institution:** Universities and colleges
- **Course:** Academic programs offered by institutions
- **Task:** To-do items assigned to users
- **Follow-up:** Scheduled interactions with leads/students
- **Document:** Files uploaded for applications
- **Payment:** Financial transactions
- **Invoice:** Bills generated for students or institutions
- **Report:** Generated analytics and insights

---

## 7. User Interface Requirements

### 7.1 Dashboard
- Role-specific dashboard widgets
- Key metrics prominently displayed
- Quick action buttons (Add Lead, Create Application, etc.)
- Recent activity feed
- Pending tasks and follow-ups section

### 7.2 Navigation
- Sidebar navigation with grouped menu items
- Breadcrumb navigation for nested pages
- Global search functionality
- Quick filters on list views

### 7.3 Forms
- Clean, uncluttered form design
- Auto-save functionality for long forms
- Field validation with helpful error messages
- Conditional field display based on selections
- Progress indicator for multi-step forms

---

## 8. Success Metrics & KPIs

### 8.1 Product Metrics
- User adoption rate: 80% of staff actively using platform within 3 months
- Daily active users (DAU) growth
- Feature utilization rate across modules
- Average time to complete application: Reduce by 40%

### 8.2 Business Metrics
- Lead conversion rate improvement: Target 15% increase
- Application processing time: Reduce by 50%
- Revenue per counsellor: Increase by 25%
- Customer satisfaction score (CSAT): Target 4.5/5
- Net Promoter Score (NPS): Target 50+

---

## 9. Implementation Roadmap

### Phase 1 (Months 1-4): MVP
- User management and RBAC
- Lead and application management
- Institution and course catalog
- Basic follow-up system
- Standard reports
- Student portal for tracking

### Phase 2 (Months 5-7): Enhancement
- Finance and invoicing module
- Advanced reporting and analytics
- WhatsApp and Google Calendar integration
- Associate partner portal
- Task management system
- Mobile optimization

### Phase 3 (Months 8-10): Scale
- API marketplace for third-party integrations
- Native mobile apps (iOS/Android)
- Advanced automation and AI-powered recommendations
- Multi-language support
- White-label customization options

---

## 10. Risk Assessment

### 10.1 Technical Risks
| Risk | Impact | Mitigation |
|------|--------|------------|
| Data migration from existing systems | High | Develop robust import tools and provide migration support |
| Integration failures with third-party APIs | Medium | Build fallback mechanisms and error handling |
| Performance degradation with scale | High | Regular load testing and optimization |

### 10.2 Business Risks
| Risk | Impact | Mitigation |
|------|--------|------------|
| User resistance to change | Medium | Comprehensive training and change management |
| Competition from established players | High | Focus on niche features and superior UX |
| Compliance with data regulations | High | Legal review and compliance by design |

---

## 11. Assumptions & Dependencies

### 11.1 Assumptions
- Users have reliable internet connectivity
- Users have modern devices (laptops/tablets/smartphones)
- Institutions provide timely updates on course information
- Third-party API services maintain uptime

### 11.2 Dependencies
- Third-party API availability (WhatsApp, SMS gateway, payment providers)
- Cloud infrastructure provider SLA
- User training and onboarding support

---

## 12. Glossary

- **CRM:** Customer Relationship Management
- **SOP:** Statement of Purpose
- **LOR:** Letter of Recommendation
- **COE:** Confirmation of Enrolment (Australia)
- **CAS:** Confirmation of Acceptance for Studies (UK)
- **GIC:** Guaranteed Investment Certificate (Canada)
- **GTE:** Genuine Temporary Entrant (Australia)
- **Intake:** Academic term start date (Fall/Spring/Summer)
- **UG:** Undergraduate
- **PG:** Postgraduate

---

## 13. Appendix

### 13.1 Sample Workflows

**Workflow 1: Lead to Application Conversion**
1. Lead captured via website form → Auto-assigned to counsellor
2. Counsellor contacts student within 24 hours
3. Student profile created with academic details
4. Course search and comparison
5. Application created for selected course
6. Documents collected via checklist
7. Application submitted to institution
8. Track application status through stages
9. Offer received → Student accepts
10. Visa process initiated
11. Enrolled → Application marked complete

**Workflow 2: Multi-Country Application for Single Student**
1. Student profile exists with UK application in progress
2. Counsellor creates second application for same student
3. Select "Add New Application" from student profile
4. Choose country: Canada
5. Select institution and course
6. Select intake
7. Existing documents auto-populated
8. Both UK and Canada applications tracked independently
9. Unified communication history across both applications

---

## 14. Approval

**Prepared by:** Product Management Team
**Review Required:** Engineering, Design, Sales, Customer Success
**Final Approval:** CEO/CTO

**Document Status:** Draft for Review

---

**End of Document**
