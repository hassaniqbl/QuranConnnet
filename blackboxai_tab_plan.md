# BlackboxAI plan: About Teacher tab (MasterStudy)

## Current blockers
- `themes/ms-lms-starter-theme/inc/teacher-profile-public-display.php` appears corrupted/malformed from earlier edits.
- Adding the tab now would likely keep the instructor page failing before tab UI renders.

## Step A: Restore teacher-profile-public-display.php
- Replace the file with a clean, syntactically correct template.
- Load all ACF values once using `get_field( ..., 'user_' . $instructor_id )`.
- Guard against array/object values when they might be returned by ACF select/radio fields:
  - `is_scalar($value)` required before using as array offset.
- Render sections only when data exists.
- Escape output, validate media URLs.
- Photo fallback:
  - If no custom `teacher_photo` exists, use `$instructor['avatar']` fallback (passed in or computed) — will implement carefully to keep signature `mkh_display_teacher_profile_info($instructor_id)`.

## Step B: Add “About Teacher” tab
- Update `themes/ms-lms-starter-theme/stm-lms-instructor-public.php`:
  - Add new entry to `$instructor_tabs` with id `about-teacher` and title “About Teacher”.
  - Determine how `components/tabs` expects active tab index / content rendering.
  - Ensure Courses/Reviews remain intact.
- Add corresponding tab panel content using the same pattern as existing tabs (likely content is already present below tab component, so the tab component may switch visibility client-side).

## Step C: Verification
- Reload instructor public profile.
- Confirm tabs render and clicking changes displayed content.
- Confirm no PHP fatal errors or warnings.

