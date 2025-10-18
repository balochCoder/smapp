# TASK-014: Country-Specific Application Workflows

**Status:** ✅ **FULLY COMPLETED**  
**Priority:** P0 - Critical  
**Date Completed:** October 16, 2025  

---

## Checklist

- [x] Create application_processes table with global status templates (12 statuses)
- [x] Create rep_country_status table for country-specific status instances
- [x] Create sub_statuses table for status sub-steps
- [x] Seed 12 default application statuses
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

---

## Related User Stories

US-166, US-167, US-168, US-169, US-170, US-171, US-172, US-173

---

## Database Architecture

**application_processes:** 12 global status templates  
**rep_country_status:** Country-specific status instances (customizable names, order, active state)  
**sub_statuses:** Flexible sub-steps for each status per country  

**12 Global Statuses:**
1. New
2. Application On Hold
3. Pre-Application Process
4. Rejected by University
5. Application Submitted
6. Conditional Offer
7. Pending Interview
8. Unconditional Offer
9. Acceptance
10. Visa Processing
11. Enrolled
12. Dropped

---

## UI Features

- Drag-and-drop reordering with visual feedback
- Add/Edit/Delete status steps with dialogs
- Add/Edit/Delete sub-statuses with sheet view
- Real-time UI updates without page reload
- "New" status protection across all operations
- AlertDialog confirmations for deletions
- Smooth animations for sheets and dialogs

---

## Notes

The architecture allows each representing country to have its own customized workflow while sharing common status templates. Countries can add custom statuses, reorder steps, rename statuses, and define country-specific sub-statuses (e.g., "CAS Obtained" for UK, "I-20 Received" for USA).

---

**End of Document**

