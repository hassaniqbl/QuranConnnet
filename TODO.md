# TODO

## Fix corrupted teacher profile display template
- [ ] Restore `themes/ms-lms-starter-theme/inc/teacher-profile-public-display.php` to valid PHP/HTML.
- [ ] Keep runtime crash guards: never use non-scalar ACF values as array offsets (fiqh/teaching_skills/language select values).

## Add new MasterStudy tab: About Teacher
- [ ] Update `themes/ms-lms-starter-theme/stm-lms-templates/stm-lms-instructor-public.php` to add third tab in `$instructor_tabs`.
- [ ] Ensure tab content shows teacher profile info when active (reuse existing `components/tabs`).
- [ ] Avoid breaking Courses/Reviews tabs and routing.

## Verification
- [ ] Reload instructor public profile page.
- [ ] Confirm fatal error is gone.
- [ ] Confirm About Teacher tab appears and renders sections.
- [ ] Confirm empty sections are hidden.
- [ ] Confirm avatar fallback works for teacher photo.

