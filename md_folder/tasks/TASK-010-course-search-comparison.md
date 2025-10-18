# TASK-010: Course Search & Comparison Features

**Status:** ⚠️ **PENDING** (0% Complete)  
**Priority:** P0 - Critical  

---

## Pending Implementation

- [ ] Build advanced filter UI with shadcn/ui (representing country, institution, subject, level, intake, budget)
- [ ] Implement cascading filters (Country → Institutions → Courses)
- [ ] Implement saved search filters functionality
- [ ] Create course comparison interface (up to 5 courses)
- [ ] Build side-by-side comparison view with shadcn/ui
- [ ] Show country and institution info in comparison
- [ ] Implement PDF export for course comparisons
- [ ] Add course comparison to favorites/bookmarks
- [ ] Write Pest tests for search and comparison

---

## Related User Stories

US-027, US-028, US-029

---

## Features

### Cascading Filters
1. Select Country (representing countries)
2. See Institutions in that country
3. See Courses in those institutions
4. Apply additional filters (level, subject, budget, intake)

### Course Comparison
- Select up to 5 courses
- Side-by-side comparison table
- Show tuition, duration, requirements, etc.
- Export to PDF
- Save for later

---

## Notes

- Filters are cascading: Select Country → See Institutions → See Courses
- All data already tenant-scoped
- Depends on TASK-008 and TASK-009 being complete

---

**Estimated Time:** 2-3 hours

---

**End of Document**

