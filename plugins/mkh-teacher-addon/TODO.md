# mkh-teacher-addon — Teacher Profile fix (end-to-end)

## Completed (investigation)
- Identified JS payload patching risk in `assets/js/mkh-teacher-addon-profile.js`.
- Inspected backend save handler `includes/Profile/SaveProfile.php` and validation `includes/Profile/Validation.php`.
- Confirmed languages array normalization and request-shape ambiguity between fetch JSON vs `$_POST`.

## Next (implementation)
1. ✅ **Fix validation:** improve/robustify `includes/Profile/Validation.php` language canonicalization.
2. ✅ **Fix JS payload merging:** preserve MasterStudy request schema and merge into the correct structure without re-wrapping.
3. ✅ **Canonicalize languages payload:** ensure JS sends/keeps `mkh_languages_spoken` as an array; PHP canonicalizes for JSON submissions too.
4. ✅ Re-enabled backend validation after payload-shape fixes (diagnostic removal reverted).

5. **Test matrix:**
   - With languages selection (single + multiple)
   - With video URL conflict cases
   - With free demo = yes/no and duration visibility
   - Pricing edge cases (0, decimals)
   - Reload auto-population


## Files likely to change
- `plugins/mkh-teacher-addon/assets/js/mkh-teacher-addon-profile.js`
- `plugins/mkh-teacher-addon/includes/Profile/Validation.php`
- `plugins/mkh-teacher-addon/includes/Profile/SaveProfile.php`

