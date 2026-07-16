# Muslim Kids Hub - MasterStudy LMS Technical Audit

Date: 2026-07-16
Scope: MasterStudy LMS WordPress plugin installed in this workspace

## 1. Architecture Overview

MasterStudy LMS uses a hybrid architecture:

- A legacy global layer under `_core/` and `lms/classes/` using `STM_LMS_*` classes and global helper functions.
- A newer namespaced layer under `includes/` using `MasterStudy\Lms\*` classes, a custom router, repositories, REST controllers, and plugin extension objects.
- A template-driven frontend layer under `_core/stm-lms-templates/` with `STM_LMS_Templates::show_lms_template()` and child-theme fallback support.
- A large asset layer under `_core/assets/` and `assets/` with prebuilt CSS/JS bundles, localized data, and page-specific handles.

This is update-sensitive code. The safest add-on strategy for `mkh-teacher-addon` is to extend by hooks, filters, template overrides, and new plugin assets only.

## 2. Plugin Structure

### 2.1 Main Entry Points

- `plugins/masterstudy-lms-learning-management-system/masterstudy-lms-learning-management-system.php`
- `plugins/masterstudy-lms-learning-management-system/includes/init.php`
- `plugins/masterstudy-lms-learning-management-system/_core/init.php`

### 2.2 Bootstrap Flow

1. Main plugin file defines version/path/url constants.
2. Composer autoload loads vendor dependencies.
3. `includes/init.php` initializes the namespaced `MasterStudy\Lms\Plugin` with a custom router.
4. `includes/actions.php` binds plugin lifecycle hooks, REST registration, addon loading, curriculum cleanup, WPML duplication, and block registration.
5. `includes/filters.php` wires REST/user/profile filters, page title handling, template behavior, multilingual support, and fallback email rendering.
6. `_core/init.php` loads the legacy LMS engine, CPT registration, templates, user manager, visual composer support, Elementor support, settings, admin helpers, and page assets.

### 2.3 Namespaces and Class Families

- Namespaced modern layer: `MasterStudy\Lms\*`
- Legacy classes/functions: `STM_LMS_*`
- Core plugin wrapper: `MasterStudy\Lms\Plugin`
- Routing layer: `MasterStudy\Lms\Routing\Router`, `Route`, middleware, route collections
- Repository layer: `MasterStudy\Lms\Repositories\*`
- HTTP/controller layer: `MasterStudy\Lms\Http\Controllers\*`
- Plugin extension layer: `MasterStudy\Lms\Plugin\Addon`, `Addons`, `PostType`, `Taxonomy`

### 2.4 Service and Helper Groups

- Plugin bootstrapping and taxonomy registration: `includes/Plugin.php`
- Addon registry: `includes/Plugin/Addons.php`
- CPT constants: `includes/Plugin/PostType.php`
- Taxonomy helpers: `includes/Plugin/Taxonomy.php`
- Template engine: `lms/classes/templates.php`
- User/account layer: `lms/classes/user.php`, `lms/classes/user_menu.php`, `lms/classes/instructors.php`, `lms/classes/students.php`
- Course, lesson, quiz, review, chat, cart, order, subscription classes: `lms/classes/*.php`

### 2.5 Template Loading

MasterStudy template resolution is centralized in `STM_LMS_Templates`:

- `locate_template()` first checks the active theme/child theme.
- Fallback path is the plugin template under `_core/stm-lms-templates/`.
- `single_template` swaps single templates for `stm-courses` and `stm-course-bundles`.
- `the_content` is filtered for configured archive pages.
- `taxonomy_template` maps the course taxonomy archive to the LMS template.

This is the key mechanism the add-on should reuse instead of copying core layouts.

## 3. User Flow

### 3.1 Roles

#### Instructor

The plugin creates the instructor role on `admin_init`:

- Role: `stm_lms_instructor`
- Purpose: course authoring and instructor dashboard access
- Capabilities:
  - `read`
  - `upload_files`
  - `publish_stm_lms_posts`
  - `edit_stm_lms_posts`
  - `delete_stm_lms_posts`
  - `edit_stm_lms_post`
  - `delete_stm_lms_post`
  - `read_stm_lms_posts`
  - `list_users`
  - `delete_others_stm_lms_posts` = false
  - `edit_others_stm_lms_posts` = false
  - `read_private_stm_lms_posts` = false

#### Student

- There is no custom student role.
- Students remain standard WordPress users, typically subscriber/customer-like users, with LMS progress stored in usermeta and custom tables.

#### Administrator

- Uses standard `administrator` role and `manage_options`.
- Has access to admin LMS screens, instructor management, and broader content control.

#### Recommendation

Custom capabilities should likely be added later for the teacher module, but not by changing the core role model now. The addon should introduce its own capability checks only when a real permission boundary is needed.

### 3.2 Registration Flow

Observed flow:

1. Frontend authorization UI renders via `components/authorization/main.php`.
2. Registration form fields come from:
   - `STM_LMS_Form_Builder::register_form_fields()`
   - `STM_LMS_Form_Builder::profile_default_fields_for_register()`
3. Form posts through AJAX action `stm_lms_register`.
4. Server-side validation checks:
   - nonce
   - recaptcha
   - required fields
   - username validity and uniqueness
   - email uniqueness
   - password strength and password confirmation
   - form-builder generated additional fields
5. Account creation uses `wp_create_user()` and `wp_signon()`.
6. Optional instructor onboarding uses the `be_instructor` flag and/or the separate instructor registration mode.
7. Hooks fire after registration:
   - `stm_lms_after_user_register`
   - `stm_lms_user_registered`

Important entry points for future field injection:

- `components/authorization/register-form.php`
- `STM_LMS_Form_Builder` field sources
- `stm_lms_after_user_register`
- `stm_lms_user_registered`
- `stm_lms_enqueue_register_script`
- `stm_lms_register` AJAX response filter

### 3.3 Login Flow

Observed flow:

- Login AJAX action: `stm_lms_login`
- Logout AJAX action: `stm_lms_logout`
- Login page URL resolves from LMS settings via `STM_LMS_User::login_page_url()`
- Login handler uses WordPress password verification and returns a JSON response filtered by `stm_lms_login`
- Social login integration can redirect through `wsl_hook_process_login_before_wp_safe_redirect`
- `wp_login` triggers post-login user handling

Future custom redirects can be added safely by using:

- `stm_lms_login`
- `wp_login`
- `wsl_hook_process_login_before_wp_safe_redirect`
- `stm_lms_user_session_limit_recovery` request handling in the auth UI

### 3.4 Instructor Dashboard

Observed dashboard shell:

- Template: `account/instructor/dashboard.php`
- Shared shell hooks:
  - `stm_lms_template_main`
  - `masterstudy_before_account`
  - `stm_lms_admin_after_wrapper_start`
  - `masterstudy_account_sidebar`
  - `masterstudy_show_analytics_templates`
  - `masterstudy_after_account`

Observed menu generation:

- Floating account menu is built by `STM_LMS_User_Menu`.
- Instructor menu items are driven by `stm_lms_menu_items`, `stm_lms_sorted_menu`, `stm_lms_float_menu_placed_items`, and `masterstudy_account_menu_section_labels`.
- Add-course visibility is gated by `stm_lms_enable_add_course`.
- React admin/instructor submenu items are filtered by:
  - `masterstudy_lms_instructor_react_menu_items`

Safe places to add future dashboard navigation:

- `stm_lms_menu_items`
- `stm_lms_sorted_menu`
- `stm_lms_float_menu_placed_items`
- `masterstudy_account_menu_section_labels`
- `masterstudy_lms_instructor_react_menu_items`

### 3.5 Instructor Profile

Private profile edit surface:

- `account/settings.php`
- `account/parts/profile.php`
- Settings sub-templates:
  - `avatar.php`
  - `bio.php`
  - `billing.php`
  - `change-password.php`
  - `custom-fields.php`
  - `display-name.php`
  - `email-notifications.php`
  - `name.php`
  - `position.php`
  - `profile-cover.php`
  - `socials.php`
  - `become-instructor-info.php`

Save flow:

- Frontend save endpoint: `wp_ajax_stm_lms_save_user_info`
- Admin profile save hooks:
  - `show_user_profile`
  - `edit_user_profile`
  - `edit_user_profile_update`
- `stm_lms_change_avatar`, `stm_lms_delete_avatar`, `stm_lms_change_cover`, `stm_lms_delete_cover` handle media-related updates

Usermeta keys used heavily for instructor profile data:

- `position`
- `description`
- `facebook`
- `twitter`
- `instagram`
- `linkedin`
- `sum_rating`
- `total_reviews`
- `become_instructor`
- `submission_date`
- `submission_status`
- `stm_lms_ai_enabled`
- `stm_lms_user_banned`
- `stm_lms_user_avatar`

Additional profile sections can be added later by:

- extending the settings template shell
- hooking admin profile actions
- extending the form-builder configuration
- adding new usermeta keys in an addon namespace

### 3.6 Instructor Public Profile

Observed templates:

- `stm-lms-instructor-public.php`
- `stm-lms-student-public.php`

Instructor public profile data sources:

- user object and usermeta
- published courses by author
- instructor rating totals
- course tab flags from LMS settings
- message modal template
- public form-builder fields

Notable display points:

- instructor avatar and cover
- public name, position, socials
- course count
- rating stars
- tabs for courses, co-owned courses, bundles, and reviews
- public field block via `components/form-builder-fields/public-fields`

Useful extension points:

- `rest_prepare_user`
- `stm_lms_featured_teacher_image_{$instructor}`
- `masterstudy_add_analytics_link`
- `masterstudy_add_grades_link`
- template override of `stm-lms-instructor-public.php`
- public field injection through the form-builder layer

### 3.7 Course System

Custom post types:

- `stm-courses`
- `stm-lessons`
- `stm-quizzes`
- `stm-questions`
- `stm-reviews`
- `stm-orders`

Additional Pro / extended post types found in the codebase:

- `stm-assignments`
- `stm-certificates`
- `stm-course-bundles`
- `stm-ent-groups`
- `stm-payout`
- `stm-user-assignment`
- `stm-google-meets`

Course ownership and permissions:

- Course author is the instructor.
- Instructor ownership is checked against post author and the `stm_lms_instructor` role.
- Admins can override this path via `manage_options`.

Course builder and course editor extension points:

- `course-builder.php`
- `CourseBuilder.php` route group
- `CourseTemplate.php` route group
- `masterstudy_lms_course_tabs`
- `stm_lms_course_hydrate`
- `stm_lms_course_saved`
- `masterstudy_lms_course_update_access`
- `masterstudy_lms_course_player_register_assets`
- `masterstudy_course_page_header`

Conclusion for future development:

- The course system should remain unchanged in the teacher add-on phase.
- Add-on behavior should attach to metadata, tabs, and menu hooks rather than changing course storage or builder flows.

## 4. Database Overview

### 4.1 Core WordPress Tables Used

- `wp_users`
- `wp_usermeta`
- `wp_posts`
- `wp_postmeta`

### 4.2 What They Store

#### `wp_users`

- Authentication identity
- WordPress role assignment
- Login state and registration records

#### `wp_usermeta`

- Instructor profile data
- Public profile visibility
- Submission status
- Ratings counters
- Personal info / form-builder values
- Avatar and cover references
- Ban and AI access flags

#### `wp_posts`

- Courses
- Lessons
- Quizzes
- Questions
- Reviews
- Orders
- Other LMS entities represented as custom post types

#### `wp_postmeta`

- Course and lesson configuration
- Course page style
- pricing and sale data
- bundle references
- LMS integration metadata

### 4.3 Custom MasterStudy Tables

The plugin also uses a set of custom tables for progress, curriculum, chat, and enrollment data.

Registered table names include:

- `stm_lms_curriculum_sections`
- `stm_lms_curriculum_materials`
- `stm_lms_user_courses`
- `stm_lms_user_lessons`
- `stm_lms_user_quizzes`
- `stm_lms_user_quizzes_times`
- `stm_lms_user_answers`
- `stm_lms_lesson_marker_questions`
- `stm_lms_lesson_marker_user_answers`
- `stm_lms_user_cart`
- `stm_lms_user_chat`
- `stm_lms_user_conversation`
- `stm_lms_user_subscription_name`
- `stm_lms_user_searches`
- `stm_lms_user_searches_stats`
- `stm_lms_order_items`
- `stm_lms_user_bookmarks`
- `stm_lms_user_assignments_times`
- `stm_lms_user_assignments`

### 4.4 Table Ownership Summary

- Instructors are not stored in a custom table.
- Students are not stored in a custom table.
- Courses, lessons, quizzes, and reviews stay in WordPress posts/postmeta.
- Enrollment and progress are split between post data, postmeta, usermeta, and custom progress tables.

## 5. Hooks & Filters

This section lists the hooks most relevant to the future teacher module. The plugin contains many more internal hooks, but these are the safest and most valuable extension points for add-on work.

### 5.1 Registration and Login

Actions:

- `stm_lms_enqueue_login_script`
- `stm_lms_enqueue_register_script`
- `stm_lms_after_user_register`
- `stm_lms_user_registered`
- `wp_login`
- `user_register`
- `show_user_profile`
- `edit_user_profile`
- `edit_user_profile_update`
- `wsl_hook_process_login_before_wp_safe_redirect`

Filters:

- `stm_lms_login`
- `masterstudy_authorization_demo_login`
- `stm_lms_filter_email_data`
- `stm_lms_settings`
- `stm_lms_instructors_page`
- `stm_lms_courses_page`

### 5.2 Dashboard and Menu

Actions:

- `masterstudy_before_account`
- `masterstudy_account_sidebar`
- `masterstudy_after_account`
- `stm_lms_before_profile_buttons_all`
- `stm_lms_template_main`

Filters:

- `stm_lms_menu_items`
- `stm_lms_sorted_menu`
- `stm_lms_float_menu_placed_items`
- `stm_lms_float_menu_enabled`
- `stm_lms_enable_add_course`
- `masterstudy_account_menu_section_labels`
- `masterstudy_lms_instructor_react_menu_items`

### 5.3 Profile and Public Profile

Actions:

- `stm_lms_before_profile_buttons_all`
- `show_user_profile`
- `edit_user_profile`
- `edit_user_profile_update`

Filters:

- `rest_prepare_user`
- `masterstudy_add_analytics_link`
- `masterstudy_add_grades_link`
- `stm_lms_featured_teacher_image_{$instructor}`

### 5.4 Course and Learning Flow

Actions:

- `masterstudy_lms_course_player_register_assets`
- `stm_lms_before_item_template_start`
- `stm_lms_before_item_lesson_start`
- `stm_lms_lesson_started`
- `stm_lms_template_main_after`
- `stm_lms_course_saved`
- `stm_lms_progress_updated`
- `stm_lms_single_course_start`

Filters:

- `stm_lms_course_tabs`
- `stm_lms_course_hydrate`
- `stm_lms_lesson_hydrate`
- `stm_lms_question_hydrate`
- `masterstudy_course_page_header`
- `masterstudy_lms_course_item_content`
- `masterstudy_lms_course_player_complete_button_class`
- `masterstudy_lms_lesson_curriculum_data`

### 5.5 REST Endpoints

REST namespace:

- `masterstudy-lms/v2`

Route groups:

- `Public.php`
- `Courses.php`
- `Students.php`
- `Lessons.php`
- `LessonsAdmin.php`
- `Quizzes.php`
- `QuizzesAdmin.php`
- `Questions.php`
- `Media.php`
- `CourseBuilder.php`
- `CourseTemplate.php`
- `Comments.php`
- `Blocks.php`
- `Orders.php`
- `AdminPosts.php`
- `AdminUsers.php`
- `AdminInstructors.php`
- `AdminMenu.php`
- `AdminAppSettings.php`
- `Statistics.php`
- `Public.php`
- `OrderUpdate.php`
- `Reviews.php`
- `CourseCategories.php`

High-value public routes:

- `GET /courses`
- `GET /course-categories`
- `GET /users`
- `GET /orders`
- `GET /enrolled-quizzes`
- `GET /quiz/attempts`
- `GET /quiz/attempt`
- `GET /instructor-public-courses`
- `GET /instructor-reviews`
- `GET /student-courses`
- `GET /student/stats/{student_id}`

High-value course authoring routes:

- `GET /courses/new`
- `GET /instructor-courses`
- `POST /courses/create`
- `POST /courses/category`
- `GET /courses/{course_id}/edit`
- `GET /courses/{course_id}/settings`
- `PUT /courses/{course_id}/settings`
- `GET /courses/{course_id}/curriculum`
- `POST /courses/{course_id}/curriculum/section`
- `PUT /courses/{course_id}/curriculum/section`
- `DELETE /courses/{course_id}/curriculum/section/{section_id}`
- `POST /courses/{course_id}/curriculum/material`
- `PUT /courses/{course_id}/curriculum/material`
- `DELETE /courses/{course_id}/curriculum/material/{material_id}`

High-value student management routes:

- `GET /students/`
- `DELETE /students/delete/`
- `GET /students/{course_id}`
- `GET /students/{course_id}/stats`
- `GET /student/progress/{course_id}/{student_id}`
- `PUT /student/progress/{course_id}/{student_id}`
- `DELETE /student/progress/{course_id}/{student_id}`

### 5.6 AJAX Actions

Registration and account:

- `stm_lms_login`
- `stm_lms_logout`
- `stm_lms_register`
- `stm_lms_become_instructor`
- `stm_lms_enterprise`
- `stm_lms_lost_password`
- `stm_lms_restore_password`
- `stm_lms_save_user_info`
- `stm_lms_change_avatar`
- `stm_lms_delete_avatar`
- `stm_lms_change_cover`
- `stm_lms_delete_cover`
- `stm_lms_hide_become_instructor_notice`

Instructor dashboard:

- `stm_lms_get_instructor_courses`
- `stm_lms_change_lms_author`
- `stm_lms_add_student_manually`
- `stm_lms_change_course_status`
- `stm_lms_get_users_submissions`
- `stm_lms_update_user_status`
- `stm_lms_ban_user`
- `stm_lms_toggle_user_ai_access`
- `stm_lms_toggle_users_ai_access`
- `stm_lms_create_announcement`

Course and learning:

- `stm_lms_load_modal`
- `stm_lms_load_content`
- `stm_lms_complete_lesson`
- `stm_lms_total_progress`
- `stm_lms_answer_video_lesson`
- `stm_lms_start_quiz`
- `stm_lms_user_answers`
- `stm_lms_add_h5p_result`
- `stm_lms_add_review`
- `stm_lms_get_reviews`
- `stm_lms_add_to_cart`
- `stm_lms_delete_from_cart`
- `stm_lms_purchase`
- `stm_lms_use_membership`
- `stm_lms_get_course_cookie_redirect`
- `stm_lms_add_bookmark`
- `stm_lms_update_bookmark`
- `stm_lms_remove_bookmark`
- `stm_lms_send_message`
- `stm_lms_get_user_conversations`
- `stm_lms_get_user_messages`
- `stm_lms_clear_new_messages`

Admin and utility:

- `wpcfto_save_settings`
- `wpcfto_get_settings`
- `wpcfto_upload_file`
- `wpcfto_search_posts`
- `ms_lms_courses_archive_filter`
- `ms_lms_courses_grid_sorting`
- `ms_lms_courses_carousel_sorting`
- `ms_lms_blog_pagination`

## 6. Template Overrides

### 6.1 Hierarchy

Template resolution order:

1. Child theme / active theme override under `stm-lms-templates/`
2. Plugin fallback under `_core/stm-lms-templates/`
3. Special case rendering for Elementor and Visual Composer where applicable

### 6.2 Override Mechanisms

- `STM_LMS_Templates::locate_template()` handles the theme/plugin lookup.
- `STM_LMS_Templates::load_lms_template()` buffers output and exposes a template-specific filter.
- `stm_lms_template_name` and `stm_lms_template_file` can alter lookup behavior.
- `vc_locate_template()` supports Visual Composer template fallback.

### 6.3 Child Theme Compatibility

The plugin is already child-theme friendly because it uses `locate_template()` before plugin fallback. That means `mkh-teacher-addon` should prefer:

- filters and actions first
- theme overrides second
- direct template copy only when there is no viable hook

## 7. Reusable Components

These are the components the teacher add-on can safely reuse:

- User auth modal and login/register forms
- Form Builder field sets for registration and profile screens
- Instructor public profile shell
- Student public profile shell
- Dashboard sidebar and floating menu
- Course card templates
- Message modal
- Pagination, select, search, and button components
- REST router and middleware
- Course authoring React pages
- Custom post types and taxonomies
- Usermeta and postmeta storage model

## 8. Extension Points

Best extension points for `mkh-teacher-addon`:

- Registration form field injection through the form-builder data providers and authorization templates
- Login redirect customization through login/session hooks
- New dashboard menu items through user-menu filters and instructor React menu filters
- Additional profile sections through user profile actions and template overrides
- Additional public profile content through public profile templates and `rest_prepare_user`
- Teacher-only course metadata through existing course settings and course tabs
- Teacher status, verification, and badges through usermeta and dashboard UI, not custom tables
- Notification and email augmentation through `stm_lms_filter_email_data` and registration/instructor hooks

## 9. Feature Gap Analysis

| Feature | Available in MasterStudy | Partially Available | Missing | Recommended Action |
| --- | --- | --- | --- | --- |
| Teacher Registration | Yes | Reuse | No | Reuse existing registration and instructor request flow |
| Teacher Availability | No | No | Yes | Build in add-on later |
| Teacher Verification | No | No | Yes | Build in add-on later |
| Profile Completion | No | Partial | Yes | Extend profile templates and usermeta |
| Teacher Badges | No | No | Yes | Build in add-on later |
| Demo Booking | No | No | Yes | Build in add-on later |
| Teacher Documents | Yes | Partial | Extend | Reuse form-builder file fields and usermeta |
| Teacher Reviews | Yes | Partial | Extend | Reuse public profile reviews and instructor rating data |
| Teacher Analytics | Yes | Partial | Extend | Reuse statistics, rating, and course reporting data |
| Teacher Messaging | Yes | Partial | Review Existing | Reuse chat and message modal flows |
| Instructor Dashboard | Yes | Reuse | No | Extend menu and widgets only |
| Instructor Public Profile | Yes | Reuse | No | Extend template and usermeta |
| Course Creation | Yes | Reuse | No | Keep unchanged |
| Course Ownership | Yes | Reuse | No | Keep unchanged |
| Custom Roles | No | No | Yes | Do not add now unless future permission model requires it |

## 10. Recommendations

1. Keep `mkh-teacher-addon` fully update-safe and isolated from MasterStudy core.
2. Use WordPress hooks, filters, and template overrides instead of copying core logic.
3. Store teacher-specific state in `wp_usermeta` unless there is a proven scaling reason not to.
4. Reuse MasterStudy registration, instructor, dashboard, and public profile entry points.
5. Add custom capabilities only when a real permission boundary is defined.
6. Keep course creation, lesson editing, quiz editing, and curriculum storage unchanged.
7. Load custom assets only on teacher-related pages and only after checking for existing MasterStudy handles.
8. Treat MasterStudy Pro behavior as conditional and test addon paths with Pro enabled and disabled.

## 11. Potential Risks

- The codebase is hybrid: some features live in legacy globals while others live in namespaced controllers and repositories.
- Several UI surfaces can be rendered from multiple stacks: PHP templates, Vue assets, React admin pages, Elementor widgets, and shortcodes.
- Many extension points are implicit rather than formally documented, so a narrow hook can be more stable than a deep template fork.
- Some behavior is addon-gated, so the same screen may render differently depending on enabled MasterStudy modules.
- Role and capability checks are hard-coded in many places, especially around `stm_lms_instructor` and `manage_options`.
- Public profile data is distributed across usermeta, postmeta, REST responses, and template variables, which means a teacher addon must be consistent across all three layers.

## 12. Best Practices

- Namespace the addon cleanly, for example `MuslimKidsHub\TeacherAddon`.
- Mirror the plugin's hook-first architecture rather than introducing direct core modifications.
- Use descriptive, prefixed option keys and meta keys.
- Keep UI additions modular and page-scoped.
- Keep validation server-side, even when the existing UI is JavaScript-heavy.
- Prefer `current_user_can()` and explicit middleware over role string checks where possible.
- Avoid custom tables until there is a demonstrated need for reporting, history, or high-volume write patterns.
- Keep future teacher-specific data aligned with existing MasterStudy user and course records.

## 13. Add-On Strategy Summary

The right shape for `mkh-teacher-addon` is:

- a thin integration layer
- a set of teacher-oriented hooks and template extensions
- no edits to MasterStudy core
- no duplicated course builder or auth logic
- no new roles or tables during phase 1

This gives the project a safe foundation for the next phase, where teacher-specific UX and business rules can be added without re-analyzing the whole MasterStudy plugin.
