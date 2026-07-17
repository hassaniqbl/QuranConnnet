# MKH Teacher Addon

A WordPress plugin that extends MasterStudy LMS with comprehensive Teacher Profile functionality using ACF PRO.

## Description

MKH Teacher Addon provides a complete teacher profile system for MasterStudy LMS instructor accounts. All teacher data is stored as user meta for seamless integration with instructor accounts.

## Features

- **ACF PRO Integration**: All fields registered programmatically using `acf_add_local_field_group()`
- **User Meta Storage**: All data stored as user meta, not post meta
- **Dashboard Integration**: Seamless integration with MasterStudy Instructor Dashboard
- **Frontend Profile Editing**: Instructors can edit their profiles directly from the dashboard
- **Comprehensive Profile Sections**:
  - Basic Information (hourly rate, languages, fiqh, sect, photo, audio, video)
  - About Teacher (biography)
  - Teaching Skills (recitation, hifz, arabic, tajweed)
  - Employment History (repeater with institute, position, dates, description)
  - Certifications (repeater with title, issuer, year, file)
  - Ijazah (repeater with title, grantor, date, file)
- **Professional UI**: Organized with tabs and accordions for clean user experience
- **Role-Based**: Field group only visible to MasterStudy instructor users (`stm_lms_instructor`)

## Requirements

- WordPress 6.0+
- PHP 8.0+
- Advanced Custom Fields PRO (ACF PRO)
- MasterStudy LMS

## Installation

1. Upload the `mkh-teacher-addon` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Ensure ACF PRO is installed and activated
4. Deactivate and reactivate the plugin to flush rewrite rules and register the custom route

## Usage

### Instructor Dashboard Integration

Once activated, instructors will see a new "Edit Profile" menu item in their MasterStudy Instructor Dashboard sidebar. Clicking this will open the teacher profile editing page where they can:

- Update their hourly rate and teaching languages
- Upload profile photo, recitation audio, and intro video
- Write their biography and teaching skills
- Add employment history
- List certifications and upload certificates
- Record ijazah details and upload supporting documents

### Backend Access

The Teacher Profile fields also appear in the WordPress admin user edit screen for users with the `stm_lms_instructor` or `administrator` role.

### Accessing Fields Programmatically

All fields are stored as user meta. Use standard WordPress functions:

```php
// Get a single field value
$hourly_rate = get_user_meta( $user_id, 'hourly_rate', true );

// Get repeater field
$employment_history = get_user_meta( $user_id, 'employment_history', true );

// Get checkbox array
$languages = get_user_meta( $user_id, 'languages', true );
```

### Field Names

- `hourly_rate` - Hourly teaching rate (number)
- `languages` - Languages taught (checkbox array)
- `fiqh` - Madhhab followed (select)
- `sect` - Sect or school affiliation (text)
- `teacher_photo` - Teacher profile photo (image array)
- `recitation_audio` - Recitation audio file (file array)
- `intro_video_file` - Intro video file upload (file array)
- `intro_video_url` - Intro video URL (url)
- `about_teacher` - Teacher biography (textarea)
- `teaching_skills` - Teaching specializations (checkbox array)
- `employment_history` - Employment history (repeater)
- `certifications` - Certifications (repeater)
- `ijazah` - Ijazah records (repeater)

## Plugin Structure

```
mkh-teacher-addon/
├── mkh-teacher-addon.php       # Main plugin file
├── README.md                   # This file
├── inc/
│   └── acf/
│       └── teacher-profile-fields.php  # ACF field registration
├── includes/
│   ├── class-dashboard-menu.php       # Dashboard menu integration
│   └── class-template-loader.php      # Template loading and routing
└── templates/
    └── account/
        ├── teacher-profile.php        # Main profile page template
        └── parts/
            └── teacher-profile-form.php # ACF form rendering
```

## Configuration

### Instructor Role

The plugin uses `stm_lms_instructor` as the instructor role. If your installation uses a different role, modify the `$instructor_role` property in `inc/acf/teacher-profile-fields.php`:

```php
protected $instructor_role = 'your_custom_role';
```

### Custom Styling

The plugin includes inline CSS to match MasterStudy's dashboard styling. To customize the appearance, edit the styles in `templates/account/parts/teacher-profile-form.php`.

## Troubleshooting

### Menu item not appearing

1. Ensure you are logged in as an instructor
2. Deactivate and reactivate the plugin to flush rewrite rules
3. Clear any caching plugins
4. Check that ACF PRO is active

### 404 error on profile page

1. Go to Settings > Permalinks and click "Save Changes"
2. Deactivate and reactivate the plugin
3. Clear your browser cache

### Fields not saving

1. Verify ACF PRO is active
2. Check that the user has the instructor role
3. Ensure the field group key matches: `group_mkh_teacher_profile`

## Support

For support and updates, visit [Muslim Kids Time](https://muslimkidstime.com).

## License

GPL-2.0+

## Changelog

### 1.0.0
- Initial release
- ACF PRO field registration for Teacher Profile
- Six main profile sections
- User meta storage
- Role-based visibility
- MasterStudy Instructor Dashboard integration
- Frontend profile editing with ACF form
- Custom routing and template loading
