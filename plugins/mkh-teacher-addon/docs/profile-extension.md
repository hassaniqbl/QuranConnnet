# MKH Teacher Add-On Profile Extension

This add-on extends the existing MasterStudy Instructor Profile without replacing MasterStudy save logic or creating a second profile page.

## Files Updated

- `includes/Profile/Profile.php`
- `includes/Profile/SaveProfile.php`
- `includes/Profile/Validation.php`
- `includes/Profile/PersonalInformation.php`
- `includes/Profile/ProfessionalInformation.php`
- `includes/Profile/Qualification.php`
- `includes/Profile/IntroVideo.php`
- `includes/Profile/DemoClass.php`
- `includes/Profile/Pricing.php`
- `assets/js/mkh-teacher-addon-profile.js`
- `assets/css/mkh-teacher-addon-profile.css`
- `docs/profile-extension.md`

## Integration Points

- `stm_lms_before_profile_buttons_all`
- `stm_lms_current_user_data`
- `wp_ajax_stm_lms_save_user_info`
- `show_user_profile`
- `edit_user_profile`
- `edit_user_profile_update`
- `wp_enqueue_scripts`
- `admin_enqueue_scripts`

## Filters Used

- `stm_lms_current_user_data`
- `mkh_teacher_addon_profile_completion_schema`
- `mkh_teacher_addon_profile_validation_errors`

## Save Flow

- The native MasterStudy `Save changes` button is preserved.
- The addon no longer uses a separate AJAX save endpoint.
- Custom fields are added to `window.profileForm` so MasterStudy's existing serializer includes them.
- An early callback on `wp_ajax_stm_lms_save_user_info` validates and saves the addon meta before the MasterStudy core handler completes the request.
- MasterStudy core remains responsible for its own profile fields.

## User Meta Keys

- `country` is reused from MasterStudy.
- `mkh_nationality`
- `mkh_timezone`
- `mkh_languages_spoken`
- `mkh_professional_headline`
- `mkh_teaching_experience_years`
- `mkh_highest_qualification`
- `mkh_institute_name`
- `mkh_graduation_year`
- `mkh_certificate_ids`
- `mkh_intro_video_youtube`
- `mkh_intro_video_vimeo`
- `mkh_offer_free_demo`
- `mkh_demo_duration`
- `mkh_monthly_package_price`
- `mkh_profile_completion_schema`

## Validation Rules

- Nationality, time zone, languages, professional headline, years of experience, qualification, free demo choice, and monthly price are required.
- Professional headline is limited to 120 characters.
- Institute name is limited to 120 characters.
- Graduation year must be a valid year when provided.
- Only one introduction video platform may be used at a time.
- YouTube and Vimeo URLs are validated against their host names.
- Demo duration is required only when free demo is set to Yes.
- Monthly price must be numeric and non-negative.

## Upload Handling

- Certificates are stored in the WordPress Media Library.
- Allowed types are PDF, JPG, JPEG, and PNG.
- Maximum file size is 5 MB per file.
- Certificate attachments are stored as attachment IDs in `mkh_certificate_ids`.
- Admin-only upload and removal controls are provided on the WordPress user profile screen.

## Extension Points

- `mkh_teacher_addon_profile_completion_schema` can be used later by a profile-completion module.
- `mkh_teacher_addon_profile_validation_errors` can be used to add extra validation rules.
- `stm_lms_current_user_data` is used to preload addon meta into the MasterStudy profile screen.

## Future Compatibility

- No MasterStudy core files are modified.
- The addon stores data only in WordPress user meta.
- The existing MasterStudy Bio/About field remains the canonical long-form bio field.
- The native MasterStudy profile layout and save button continue to drive the page experience.
