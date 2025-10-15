# User Stories - Study Abroad Education Consultancy Management Platform

**Version:** 1.0
**Date:** October 15, 2025
**Based on:** Product Requirements Document v1.0

---

## Table of Contents
1. [Organization & User Management](#1-organization--user-management)
2. [Institution & Course Management](#2-institution--course-management)
3. [Lead & Application Management](#3-lead--application-management)
4. [Follow-up & Task Management](#4-follow-up--task-management)
5. [Associate & Partner Management](#5-associate--partner-management)
6. [Communication & Notifications](#6-communication--notifications)
7. [Finance Management](#7-finance-management)
8. [Reporting & Analytics](#8-reporting--analytics)
9. [Website Integration & Student Portal](#9-website-integration--student-portal)
10. [Mobile & Tablet Support](#10-mobile--tablet-support)
11. [Country-Specific Application Processes](#11-country-specific-application-processes)

---

## 1. Organization & User Management

### 1.1 Multi-Branch Management

**US-001:** As an **Administrator**, I want to create new branch offices with specific settings (currency, timezone, working hours) so that I can expand my operations geographically and manage regional differences.

**US-002:** As an **Administrator**, I want to define branch territories and operational regions so that I can clearly demarcate service areas and avoid conflicts.

**US-003:** As an **Administrator**, I want to configure inter-branch data visibility controls so that I can manage information sharing appropriately across the organization.

**US-004:** As a **Branch Manager**, I want to view only my branch's data and performance metrics so that I can focus on my team's operations without distractions.

**US-005:** As an **Administrator**, I want to view consolidated performance dashboards across all branches so that I can monitor overall business health.

**US-006:** As a **Branch Manager**, I want to customize my branch-specific settings so that I can adapt to local operational requirements.

### 1.2 Role-Based Access Control

**US-007:** As an **Administrator**, I want to create custom roles with granular permissions so that I can tailor access levels to specific job functions.

**US-008:** As an **Administrator**, I want to assign predefined roles (Admin, Branch Manager, Counsellor, Processing Officer, Front Office, Finance) to users so that I can quickly onboard staff with appropriate access.

**US-009:** As an **Administrator**, I want to enable IP whitelisting for specific users or branches so that I can enhance security for sensitive operations.

**US-010:** As an **Administrator**, I want to view user activity logs with timestamps and IP addresses so that I can audit user actions and maintain security.

**US-011:** As a **User**, I want to have concurrent session support so that I can access the system from multiple devices when needed.

**US-012:** As an **Administrator**, I want users to only access features permitted by their role so that data security and operational integrity are maintained.

### 1.3 White-Label Branding

**US-013:** As an **Administrator**, I want to upload my agency logo so that it appears on all dashboards and reflects my brand identity.

**US-014:** As an **Administrator**, I want to customize the color scheme and branding elements so that the platform matches my agency's visual identity.

**US-015:** As an **Administrator**, I want to create agency-specific email templates so that all communications maintain consistent branding.

**US-016:** As an **Administrator**, I want to configure a custom domain for the student portal so that students access services through my branded URL.

---

## 2. Institution & Course Management

### 2.1 Institution Database

**US-017:** As an **Administrator**, I want to create comprehensive university/college profiles with rankings, location, accreditation, and facilities so that counsellors have complete information for student guidance.

**US-018:** As an **Administrator**, I want to store institution contact information and partnership terms so that my team can easily reach out to partners.

**US-019:** As an **Administrator**, I want to upload marketing materials (brochures, videos, images) for institutions so that counsellors can share promotional content with students.

**US-020:** As an **Administrator**, I want to define commission structures for each institution so that financial tracking is accurate.

**US-021:** As a **Counsellor**, I want to view complete institution profiles including facilities, accommodation options, and entry requirements so that I can provide accurate information to students.

**US-022:** As an **Administrator**, I want to manage institutions across multiple countries so that I can support diverse student destinations.

### 2.2 Course Catalog Management

**US-023:** As an **Administrator**, I want to add detailed course information including categorization, intake dates, tuition fees, and entry requirements so that counsellors can easily find suitable programs.

**US-024:** As an **Administrator**, I want to specify course duration, credit hours, and career outcomes so that students understand program expectations.

**US-025:** As a **Counsellor**, I want to search courses using advanced filters (country, institution, subject, level, intake, budget) so that I can quickly find suitable options for my students.

**US-026:** As a **Counsellor**, I want to use full-text search across course titles and descriptions so that I can discover relevant programs efficiently.

**US-027:** As a **Counsellor**, I want to save search filters so that I can quickly re-run common searches without re-entering criteria.

**US-028:** As a **Counsellor**, I want to compare up to 5 courses side-by-side so that I can help students make informed decisions.

**US-029:** As a **Counsellor**, I want to export course comparison sheets to PDF so that I can share detailed comparisons with students.

**US-030:** As an **Administrator**, I want to specify scholarship information and English language criteria for courses so that students understand funding opportunities and language requirements.

---

## 3. Lead & Application Management

### 3.1 Lead Management System

**US-031:** As a **Front Office Staff**, I want to manually capture leads with personal information, academic background, and preferences so that we can track potential students.

**US-032:** As an **Administrator**, I want leads to be automatically captured from website forms so that we don't miss any enquiries.

**US-033:** As a **Branch Manager**, I want to bulk upload leads via CSV/Excel with validation so that I can efficiently import data from marketing campaigns.

**US-034:** As a **Branch Manager**, I want to assign leads to counsellors manually, via round-robin, or territory-based rules so that workload is distributed effectively.

**US-035:** As a **Counsellor**, I want to receive automatic email notifications when leads are assigned to me so that I can respond promptly.

**US-036:** As a **Counsellor**, I want to track lead status (New, Contacted, Qualified, Converted, Lost) so that I can manage my pipeline effectively.

**US-037:** As a **Counsellor**, I want to see lead source tracking and attribution so that I understand where my leads are coming from.

**US-038:** As a **Branch Manager**, I want to transfer leads between counsellors within my branch so that I can rebalance workload as needed.

**US-039:** As a **Counsellor**, I want duplicate lead detection and alerts before creating a lead so that I avoid redundant entries.

**US-040:** As a **Branch Manager**, I want to merge duplicate leads so that I maintain clean data.

**US-041:** As a **Counsellor**, I want to capture budget range and intended intake for leads so that I can prioritize and qualify leads effectively.

### 3.2 Application Management

**US-042:** As a **Counsellor**, I want to create a student profile once and add multiple applications without re-entering data so that I can save time and reduce errors.

**US-043:** As a **Counsellor**, I want to create new applications by simply selecting Country → Institution → Course → Intake so that the process is quick and streamlined.

**US-044:** As a **Counsellor**, I want shared documents to be available across all applications for the same student so that I don't need to upload documents multiple times.

**US-045:** As a **Counsellor**, I want to view a unified communication history for each student across all their applications so that I have complete context.

**US-046:** As an **Administrator**, I want to configure country-specific application workflows with custom stages so that processes match regional requirements.

**US-047:** As a **Processing Officer**, I want to manage document checklists for each application so that I can track what's been submitted and what's pending.

**US-048:** As a **Counsellor**, I want to view application status tracking with a timeline view so that I can see progress at a glance.

**US-049:** As a **Branch Manager**, I want to transfer applications between branches so that I can manage workload or relocate student cases as needed.

**US-050:** As a **Processing Officer**, I want to view complete application history and activity logs so that I can understand all actions taken.

**US-051:** As a **Counsellor**, I want to manage conditional offers and final offers separately so that I can track acceptance milestones.

**US-052:** As a **Counsellor**, I want to see application stages progress through: Documentation Collection → Application Preparation → Application Submitted → Awaiting Decision → Offer Received → Visa Processing → Enrolled so that I can guide students through the journey.

---

## 4. Follow-up & Task Management

### 4.1 Follow-up Management System

**US-053:** As a **Counsellor**, I want to manually schedule follow-ups with date, time, and notes so that I can plan my student interactions.

**US-054:** As a **Counsellor**, I want automated follow-up triggers based on lead/application status so that I never miss critical touchpoints.

**US-055:** As a **Counsellor**, I want to receive follow-up reminders via email and in-app notifications so that I stay on top of my schedule.

**US-056:** As a **Counsellor**, I want to see overdue follow-up alerts so that I can prioritize delayed tasks.

**US-057:** As a **Counsellor**, I want to record follow-up history and outcomes so that I maintain a complete interaction record.

**US-058:** As a **Counsellor**, I want my follow-ups to sync bidirectionally with Google Calendar so that I can manage my schedule in one place.

**US-059:** As a **Counsellor**, I want WhatsApp Web integration so that I can click-to-chat directly from the lead or application page.

**US-060:** As a **Counsellor**, I want to receive notifications 30 minutes before scheduled follow-ups so that I can prepare for the interaction.

**US-061:** As a **Counsellor**, I want automated reminders when: leads aren't contacted in 24 hours, documentation is pending >7 days, offers aren't accepted in 14 days, or visa interviews are approaching so that I maintain service standards.

### 4.2 Task Management

**US-062:** As a **Branch Manager**, I want to create tasks with title, description, due date, and assignee so that I can delegate work effectively.

**US-063:** As a **User**, I want to categorize tasks (Documentation, Follow-up, Internal, Urgent) so that I can organize my workload.

**US-064:** As a **User**, I want to set task priority levels (Low, Medium, High, Critical) so that I can focus on what's important.

**US-065:** As a **Branch Manager**, I want to assign tasks to individuals or teams so that I can distribute work appropriately.

**US-066:** As a **User**, I want to create subtasks so that I can break down complex tasks into manageable steps.

**US-067:** As a **User**, I want to add comments and updates to tasks so that I can collaborate with colleagues.

**US-068:** As a **User**, I want to follow a task completion workflow so that progress is tracked systematically.

**US-069:** As a **Branch Manager**, I want to create recurring task templates so that I can automate routine assignments.

**US-070:** As a **User**, I want to view "My Tasks" so that I can see my personal to-do list.

**US-071:** As a **Branch Manager**, I want to view "Team Tasks" so that I can monitor my team's workload.

**US-072:** As a **Branch Manager**, I want to see overdue tasks reports and task completion metrics so that I can assess team productivity.

---

## 5. Associate & Partner Management

### 5.1 Associate Partner Portal

**US-073:** As an **Administrator**, I want to create associate partner accounts with login credentials so that sub-agents can access the system.

**US-074:** As an **Associate Partner**, I want a dedicated dashboard with limited access so that I can manage my students effectively.

**US-075:** As an **Associate Partner**, I want to submit applications on behalf of students so that I can provide comprehensive service.

**US-076:** As an **Associate Partner**, I want to view the status of my submitted applications so that I can update students on progress.

**US-077:** As an **Associate Partner**, I want to track my commission per application so that I can monitor my earnings.

**US-078:** As an **Associate Partner**, I want to upload documents for applications so that I can support the application process.

**US-079:** As an **Associate Partner**, I want to communicate with the main agency through a dedicated thread so that I can get support when needed.

**US-080:** As an **Administrator**, I want associates to only see their own applications and approved institutions so that business confidentiality is maintained.

**US-081:** As an **Administrator**, I want to prevent associates from accessing financial reports and other partners' data so that sensitive information is protected.

---

## 6. Communication & Notifications

### 6.1 Announcement Management

**US-082:** As an **Administrator**, I want to create announcements for the entire organization or specific branches/roles so that I can communicate important updates.

**US-083:** As an **Administrator**, I want to schedule announcement publish dates so that I can plan communications in advance.

**US-084:** As an **Administrator**, I want to pin important announcements so that they remain visible to users.

**US-085:** As an **Administrator**, I want to set announcement expiry dates so that outdated information is automatically removed.

**US-086:** As an **Administrator**, I want to use a rich text editor and attach files to announcements so that I can create comprehensive communications.

### 6.2 Student Communication

**US-087:** As a **Counsellor**, I want to send emails to students from within the platform so that all communications are logged and trackable.

**US-088:** As a **Counsellor**, I want to send SMS notifications to students so that I can reach them quickly for urgent matters.

**US-089:** As a **Counsellor**, I want to use WhatsApp messaging integration so that I can communicate on students' preferred channel.

**US-090:** As a **Processing Officer**, I want automated email reminders sent to students for pending documents so that I can reduce manual follow-up work.

**US-091:** As a **Counsellor**, I want students to receive automatic notifications when their application status updates so that they stay informed.

**US-092:** As a **Counsellor**, I want to use a template library for common communications so that I can respond efficiently while maintaining quality.

---

## 7. Finance Management

### 7.1 Financial Tracking

**US-093:** As a **Finance Team Member**, I want to track payments from students (application fees, service fees, tuition deposits) so that I can monitor revenue.

**US-094:** As a **Finance Team Member**, I want to track commission from institutions so that I can reconcile partner payments.

**US-095:** As a **Finance Team Member**, I want to manage installment plans for student payments so that I can offer flexible payment options.

**US-096:** As a **Finance Team Member**, I want to track payment status (Pending, Partial, Paid, Overdue) so that I can identify collection issues.

**US-097:** As a **Finance Team Member**, I want to send automated payment reminders to students so that I can improve collection rates.

**US-098:** As a **Finance Team Member**, I want to perform payment reconciliation so that I can ensure financial accuracy.

### 7.2 Invoicing System

**US-099:** As a **Finance Team Member**, I want to generate invoices for students (service fees, consultation charges) so that I can formalize payment requests.

**US-100:** As a **Finance Team Member**, I want to generate invoices for institutions for services rendered so that I can bill partners appropriately.

**US-101:** As an **Administrator**, I want to customize invoice templates with agency branding so that invoices reflect our professional identity.

**US-102:** As a **Finance Team Member**, I want to support multiple currencies so that I can invoice students in their local currency.

**US-103:** As a **Finance Team Member**, I want to calculate taxes (VAT/GST) automatically so that invoices are compliant and accurate.

**US-104:** As a **Finance Team Member**, I want to track invoice status so that I know which invoices are paid, pending, or overdue.

**US-105:** As a **Finance Team Member**, I want to send automated payment reminder emails so that I can reduce manual collection efforts.

**US-106:** As a **Finance Team Member**, I want to generate different invoice types (student service fee, tuition deposit, commission, partner commission) so that all financial transactions are properly documented.

---

## 8. Reporting & Analytics

### 8.1 Lead Reports

**US-107:** As a **Branch Manager**, I want to view lead source analysis so that I can understand which marketing channels are most effective.

**US-108:** As an **Administrator**, I want to view the lead conversion funnel so that I can identify bottlenecks in the sales process.

**US-109:** As a **Branch Manager**, I want to see counsellor performance (leads assigned vs converted) so that I can evaluate individual contributions.

**US-110:** As a **Branch Manager**, I want to view lead aging reports so that I can identify stale leads that need attention.

**US-111:** As an **Administrator**, I want to analyze lost lead reasons so that I can improve our service offering.

### 8.2 Application Reports

**US-112:** As a **Branch Manager**, I want to view applications by status so that I can understand pipeline health.

**US-113:** As an **Administrator**, I want to see applications by country/institution so that I can identify partnership opportunities.

**US-114:** As an **Administrator**, I want to track application success rates so that I can measure operational effectiveness.

**US-115:** As a **Processing Officer**, I want to analyze processing time so that I can identify efficiency improvements.

**US-116:** As a **Branch Manager**, I want to view visa approval/rejection rates so that I can assess country-specific success rates.

### 8.3 Branch & Team Reports

**US-117:** As an **Administrator**, I want to view branch-wise application volume so that I can compare branch performance.

**US-118:** As an **Administrator**, I want to see branch revenue and commission reports so that I can assess profitability by location.

**US-119:** As an **Administrator**, I want to track branch-wise conversion rates so that I can identify top-performing branches.

**US-120:** As a **Branch Manager**, I want to view team productivity metrics so that I can manage my team effectively.

### 8.4 Counsellor Reports

**US-121:** As a **Branch Manager**, I want to view individual counsellor performance so that I can provide targeted coaching.

**US-122:** As a **Branch Manager**, I want to see applications processed per counsellor so that I can understand workload distribution.

**US-123:** As a **Branch Manager**, I want to track conversion rates by counsellor so that I can identify top performers.

**US-124:** As a **Branch Manager**, I want to monitor follow-up completion rates so that I can ensure service standards.

**US-125:** As a **Branch Manager**, I want to compare target vs achievement for each counsellor so that I can manage performance.

### 8.5 Task & Country Reports

**US-126:** As a **Branch Manager**, I want to view completed vs pending tasks so that I can assess team productivity.

**US-127:** As an **Administrator**, I want to see missed tasks by user so that I can identify training needs.

**US-128:** As a **Branch Manager**, I want to view average task completion time so that I can set realistic expectations.

**US-129:** As an **Administrator**, I want to view overdue tasks by priority so that I can escalate critical items.

**US-130:** As an **Administrator**, I want to see annual applications by country so that I can identify trending destinations.

**US-131:** As an **Administrator**, I want to view intake-wise applications (Fall/Spring/Summer) so that I can plan resource allocation.

**US-132:** As an **Administrator**, I want to track country-wise success rates so that I can focus on high-performing destinations.

### 8.6 Financial Reports

**US-133:** As a **Finance Team Member**, I want to view revenue by branch/counsellor so that I can understand contribution to profitability.

**US-134:** As a **Finance Team Member**, I want to track commission so that I can manage partner payments.

**US-135:** As a **Finance Team Member**, I want to see outstanding payments so that I can prioritize collection efforts.

**US-136:** As a **Finance Team Member**, I want to calculate payment collection rates so that I can measure financial health.

### 8.7 Business Intelligence Dashboard

**US-137:** As an **Administrator**, I want an executive dashboard with key metrics so that I can monitor business health at a glance.

**US-138:** As a **User**, I want to view visual charts and graphs (line, bar, pie, funnel) so that I can understand trends quickly.

**US-139:** As a **User**, I want to filter reports by date range so that I can analyze specific time periods.

**US-140:** As a **User**, I want to export reports to PDF/Excel so that I can share insights with stakeholders.

**US-141:** As an **Administrator**, I want to schedule report delivery via email so that I receive regular business updates.

**US-142:** As an **Administrator**, I want to use a custom report builder so that I can create specific analyses for my needs.

**US-143:** As an **Administrator**, I want to view key metrics (total leads, conversion rate, active applications, revenue trends, top performers, top destinations, average processing time) so that I can make data-driven decisions.

---

## 9. Website Integration & Student Portal

### 9.1 Student Portal API

**US-144:** As a **Student**, I want to log in to the agency website so that I can access my application information.

**US-145:** As a **Student**, I want to view my application status on the agency website so that I can track progress without contacting my counsellor.

**US-146:** As a **Student**, I want to upload documents through the student portal so that I can submit required materials conveniently.

**US-147:** As a **Student**, I want to see a timeline view of my application progress so that I understand where I am in the process.

**US-148:** As a **Student**, I want to communicate with my counsellor via the portal so that I have a direct communication channel.

**US-149:** As an **Administrator**, I want API endpoints for student authentication, application status, document uploads, messages, and profile updates so that I can integrate with our agency website.

### 9.2 Enquiry Form Integration

**US-150:** As an **Administrator**, I want a JavaScript widget for embedding enquiry forms on our website so that we can capture leads automatically.

**US-151:** As an **Administrator**, I want to customize enquiry form fields so that we collect relevant information.

**US-152:** As a **Counsellor**, I want form submissions to automatically create leads in the CRM so that I don't miss enquiries.

**US-153:** As a **Branch Manager**, I want lead source attribution from website forms so that I can measure marketing effectiveness.

**US-154:** As a **Counsellor**, I want real-time lead notifications when website forms are submitted so that I can respond immediately.

### 9.3 Staff Login API

**US-155:** As an **Administrator**, I want Single Sign-On (SSO) support so that staff can access the system seamlessly.

**US-156:** As an **Administrator**, I want API access for third-party integrations so that I can connect with other business tools.

**US-157:** As a **Developer**, I want webhook support so that external systems can receive real-time updates.

**US-158:** As a **Developer**, I want comprehensive API documentation with examples so that I can integrate efficiently.

---

## 10. Mobile & Tablet Support

### 10.1 Responsive Design & Mobile Features

**US-159:** As a **Counsellor**, I want to access the platform on my smartphone with a fully responsive interface so that I can work on the go.

**US-160:** As a **Counsellor**, I want to view leads and applications on my tablet so that I can work from anywhere.

**US-161:** As a **Counsellor**, I want to update follow-ups on my mobile device so that I can stay current with my schedule.

**US-162:** As a **Counsellor**, I want to make notes and schedule tasks from my phone so that I can capture information immediately.

**US-163:** As a **Counsellor**, I want to upload documents via my phone's camera so that I can submit paperwork quickly.

**US-164:** As a **Counsellor**, I want to receive push notifications for urgent tasks on my mobile device so that I never miss critical items.

**US-165:** As a **Counsellor**, I want offline mode for viewing data (in the mobile app) so that I can access information without internet connectivity.

---

## 11. Country-Specific Application Processes

### 11.1 Custom Workflow Configuration

**US-166:** As an **Administrator**, I want to define application stages per country (e.g., UK vs Canada vs Australia) so that workflows match regional requirements.

**US-167:** As a **Processing Officer**, I want country-specific document requirements automatically applied so that I ensure compliance.

**US-168:** As a **Counsellor**, I want to track visa processes specific to each country so that I can guide students accurately.

**US-169:** As an **Administrator**, I want to configure country-specific deadlines and timelines so that the system alerts users appropriately.

**US-170:** As a **Counsellor**, I want pre-populated templates for country-specific SOPs and LORs so that I can create documents efficiently.

**US-171:** As a **Counsellor**, I want to follow UK-specific workflow (Application → Offer → CAS → Visa Application → Visa Decision) so that I comply with UK requirements.

**US-172:** As a **Counsellor**, I want to follow Canada-specific workflow (Application → Offer → GIC → Visa Application → Biometrics → Visa Decision) so that I comply with Canadian requirements.

**US-173:** As a **Counsellor**, I want to follow Australia-specific workflow (Application → Offer → COE → GTE → Visa Application → Visa Grant) so that I comply with Australian requirements.

---

## Appendix: User Story Mapping by Role

### Administrator
US-001 through US-006, US-007 through US-012, US-013 through US-016, US-017 through US-022, US-023 through US-024, US-030, US-032, US-033, US-046, US-073, US-080, US-081, US-082 through US-086, US-101, US-108, US-111, US-113 through US-114, US-117 through US-119, US-129 through US-132, US-137, US-141 through US-143, US-149 through US-151, US-155 through US-158, US-166, US-169, US-170

### Branch Manager
US-004, US-006, US-033, US-034, US-038, US-040, US-049, US-062, US-069, US-071, US-072, US-107 through US-111, US-112, US-116 through US-125, US-126 through US-128, US-153

### Counsellor
US-021, US-025 through US-029, US-031, US-035 through US-039, US-041 through US-045, US-048, US-051, US-052, US-053 through US-061, US-063 through US-068, US-070, US-087 through US-092, US-159 through US-165, US-168, US-171 through US-173

### Front Office Staff
US-031

### Processing Officer
US-047, US-050, US-091, US-115, US-167

### Finance Team
US-093 through US-106, US-133 through US-136

### Associate Partner
US-074 through US-079

### Student
US-144 through US-148

### Developer
US-157, US-158

---

**Total User Stories:** 173

**Document Status:** Ready for Review

**End of Document**



