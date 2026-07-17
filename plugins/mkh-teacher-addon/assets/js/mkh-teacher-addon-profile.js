(function ($) {
  'use strict';

  var config = window.mkhTeacherAddonProfile || {};
  var root = document.getElementById('mkh-teacher-addon-profile');

  if (!root) {
    return;
  }

  var fields = Array.isArray(config.profileFields) ? config.profileFields : [];
  var saveButtonSelector = config.saveButtonSelector || '[data-id="masterstudy-account-settings-save"]';
  var actionsSelector = config.actionsSelector || '.masterstudy-account-settings__actions';
  var statusNode = root.querySelector('[data-mkh-status]');
  var fieldMap = buildFieldMap(fields);
  var hasPatchedProfileSave = false;

  mergeProfileForm(fields);
  moveSaveActions();
  patchProfileSaveRequest();
  bindFieldState();
  syncInitialState();

  function mergeProfileForm(profileFields) {
    if (!profileFields.length) {
      return;
    }

    if (!Array.isArray(window.profileForm)) {
      window.profileForm = [];
    }

    var existing = new Set(window.profileForm.map(function (field) {
      return field && field.id ? field.id : '';
    }));

    profileFields.forEach(function (field) {
      if (!field || !field.id || existing.has(field.id)) {
        return;
      }

      window.profileForm.push(field);
      existing.add(field.id);
    });
  }

  function buildFieldMap(profileFields) {
    var map = {};

    profileFields.forEach(function (field) {
      if (!field || !field.id) {
        return;
      }

      map[field.id] = field;
    });

    return map;
  }

  function moveSaveActions() {
    var account = document.getElementById('masterstudy-account-settings');
    var actions = document.querySelector(actionsSelector);

    if (!account || !actions || !actions.parentNode) {
      return;
    }

    if (root.parentNode !== account) {
      account.insertBefore(root, actions);
    } else {
      account.insertBefore(root, actions);
    }
  }

  function showStatus(message, type) {
    if (!statusNode) {
      return;
    }

    statusNode.className = 'alert mkh-teacher-addon__status';
    statusNode.classList.add(type === 'success' ? 'alert-success' : 'alert-danger');
    statusNode.textContent = message || '';
    statusNode.classList.remove('d-none');
  }

  function hideStatus() {
    if (!statusNode) {
      return;
    }

    statusNode.className = 'alert mkh-teacher-addon__status d-none';
    statusNode.textContent = '';
  }

  function fieldName(field) {
    return (field && field.slug ? field.slug : '').replace(/\[\]$/, '');
  }

  function getFieldNodes(name) {
    // Search inside addon root first, but also allow global lookup for native MasterStudy fields
    // that may live outside our injected sections.
    var selectors = [
      '[name="' + name + '"]',
      '[name="' + name + '[]"]',
      '[name^="' + name + '"]'
    ];

    var scoped = root.querySelectorAll(selectors.join(', '));
    if (scoped && scoped.length) {
      return scoped;
    }

    return document.querySelectorAll(selectors.join(', '));
  }


  function getFieldValue(name) {
    var nodes = getFieldNodes(name);
    if (!nodes.length) {
      return '';
    }

    var node = nodes[0];

    if (node.tagName === 'SELECT' && node.multiple) {
      return Array.prototype.slice.call(node.selectedOptions).map(function (option) {
        return option.value;
      });
    }

    if (node.type === 'radio') {
      var checked = root.querySelector('[name="' + name + '"]:checked');
      return checked ? checked.value : '';
    }

    return node.value;
  }

  function setFieldInvalid(name, invalid) {
    var nodes = getFieldNodes(name);
    if (!nodes.length) {
      return;
    }

    Array.prototype.forEach.call(nodes, function (node) {
      node.classList.toggle('is-invalid', invalid);
    });
  }

  function normalizeArray(value) {
    if (Array.isArray(value)) {
      return value.filter(Boolean);
    }

    if (typeof value === 'string' && value) {
      return value.split(',').map(function (item) {
        return item.trim();
      }).filter(Boolean);
    }

    return [];
  }

  function validateFields(fields) {
    var errors = [];
    var effectiveFields = Object.assign({}, fields || {});
    var fallbackFieldNames = ['mkh_nationality', 'mkh_timezone', 'mkh_languages_spoken', 'mkh_professional_headline', 'mkh_teaching_experience_years', 'mkh_highest_qualification', 'mkh_institute_name', 'mkh_graduation_year', 'mkh_intro_video_youtube', 'mkh_intro_video_vimeo', 'mkh_offer_free_demo', 'mkh_demo_duration', 'mkh_monthly_package_price'];

    fallbackFieldNames.forEach(function (name) {
      if (!(name in effectiveFields)) {
        effectiveFields[name] = getFieldValue(name);
      }
    });

    if (Object.keys(fieldMap).length) {
      Object.keys(fieldMap).forEach(function (key) {
        var field = fieldMap[key];
        var name = fieldName(field);
        effectiveFields[name] = getFieldValue(name);
      });
    }

    var headline = (effectiveFields.mkh_professional_headline || '').trim();
    var languages = normalizeArray(effectiveFields.mkh_languages_spoken);
    var timezone = (effectiveFields.mkh_timezone || '').trim();
    var nationality = (effectiveFields.mkh_nationality || '').trim();
    var experience = (effectiveFields.mkh_teaching_experience_years || '').trim();
    var qualification = (effectiveFields.mkh_highest_qualification || '').trim();
    var institute = (effectiveFields.mkh_institute_name || '').trim();
    var graduationYear = (effectiveFields.mkh_graduation_year || '').trim();
    var youtube = (effectiveFields.mkh_intro_video_youtube || '').trim();
    var vimeo = (effectiveFields.mkh_intro_video_vimeo || '').trim();
    var freeDemo = (effectiveFields.mkh_offer_free_demo || '').trim();
    var duration = (effectiveFields.mkh_demo_duration || '').trim();
    var price = (effectiveFields.mkh_monthly_package_price || '').trim();

    Object.keys(fieldMap).forEach(function (key) {
      setFieldInvalid(fieldName(fieldMap[key]), false);
    });

    if (!nationality) {
      errors.push({ field: 'mkh_nationality', message: 'Nationality is required.' });
    }

    if (timezone && !/^[A-Za-z_\/\-0-9]+$/.test(timezone)) {
      errors.push({ field: 'mkh_timezone', message: 'Please select a valid time zone.' });
    }

    if (!languages.length) {
      errors.push({ field: 'mkh_languages_spoken', message: 'Please select at least one language.' });
    }

    if (!headline || headline.length > 120) {
      errors.push({ field: 'mkh_professional_headline', message: 'Professional headline must be 120 characters or fewer.' });
    }

    if (!experience) {
      errors.push({ field: 'mkh_teaching_experience_years', message: 'Teaching experience is required.' });
    }

    if (!qualification) {
      errors.push({ field: 'mkh_highest_qualification', message: 'Highest qualification is required.' });
    }

    if (institute.length > 120) {
      errors.push({ field: 'mkh_institute_name', message: 'Institute name must be 120 characters or fewer.' });
    }

    if (graduationYear && !/^(19[5-9]\d|20[0-9]\d)$/.test(graduationYear)) {
      errors.push({ field: 'mkh_graduation_year', message: 'Please select a valid graduation year.' });
    }

    if (youtube && vimeo) {
      errors.push({ field: 'mkh_intro_video_vimeo', message: config.strings && config.strings.videoConflict ? config.strings.videoConflict : 'Choose only one video platform at a time.' });
    }

    if (youtube && !isValidVideoUrl(youtube, 'youtube')) {
      errors.push({ field: 'mkh_intro_video_youtube', message: 'Please enter a valid YouTube URL.' });
    }

    if (vimeo && !isValidVideoUrl(vimeo, 'vimeo')) {
      errors.push({ field: 'mkh_intro_video_vimeo', message: 'Please enter a valid Vimeo URL.' });
    }

    if (freeDemo !== 'yes' && freeDemo !== 'no') {
      errors.push({ field: 'mkh_offer_free_demo', message: 'Please choose whether you offer a free demo.' });
    }

    if (freeDemo === 'yes' && !duration) {
      errors.push({ field: 'mkh_demo_duration', message: 'Please choose a demo duration.' });
    }

    if (!/^\d+(\.\d+)?$/.test(price) || Number(price) < 0) {
      errors.push({ field: 'mkh_monthly_package_price', message: 'Please enter a valid monthly package price.' });
    }

    if (errors.length) {
      errors.forEach(function (error) {
        setFieldInvalid(error.field, true);
      });
    }

    return errors;
  }

  function isValidVideoUrl(url, platform) {
    try {
      var parsed = new URL(url, window.location.href);
      var host = parsed.hostname.toLowerCase();

      if (platform === 'youtube') {
        return host === 'youtube.com' || host === 'www.youtube.com' || host === 'youtu.be' || host.endsWith('.youtube.com');
      }

      if (platform === 'vimeo') {
        return host === 'vimeo.com' || host === 'www.vimeo.com' || host.endsWith('.vimeo.com');
      }
    } catch (e) {
      return false;
    }

    return false;
  }

  function collectFields() {
    var payload = {};

    Object.keys(fieldMap).forEach(function (key) {
      var field = fieldMap[key];
      var name = fieldName(field);
      var value = getFieldValue(name);
      payload[name] = value;
    });

    var extraNames = ['mkh_nationality', 'mkh_timezone', 'mkh_languages_spoken', 'mkh_professional_headline', 'mkh_teaching_experience_years', 'mkh_highest_qualification', 'mkh_institute_name', 'mkh_graduation_year', 'mkh_intro_video_youtube', 'mkh_intro_video_vimeo', 'mkh_offer_free_demo', 'mkh_demo_duration', 'mkh_monthly_package_price'];

    extraNames.forEach(function (name) {
      if (!(name in payload)) {
        payload[name] = getFieldValue(name);
      }
    });

    // Ensure checkbox groups are always represented as an array.
    payload.mkh_languages_spoken = normalizeArray(payload.mkh_languages_spoken);

    return payload;
  }


  function shouldPatchProfileRequest(url) {
    if (!url) {
      return false;
    }

    if (typeof url !== 'string') {
      return false;
    }

    return url.indexOf('stm_lms_save_user_info') !== -1 || url.indexOf('action=stm_lms_save_user_info') !== -1;
  }

  function patchProfileSaveRequest() {
    if (hasPatchedProfileSave || typeof window.fetch !== 'function') {
      return;
    }

    var originalFetch = window.fetch.bind(window);

    window.fetch = function (url, options) {
      var requestOptions = options || {};
      var customFields = collectFields();

      // Only patch requests that are meant to save the profile.
      if (!shouldPatchProfileRequest(url) || !customFields || !Object.keys(customFields).length) {
        return originalFetch(url, requestOptions);
      }

      var requestBody = requestOptions.body;
      // Preserve MasterStudy request schema as much as possible.
      // We will merge custom fields into the existing parsed structure without re-wrapping it.
      var parsedBody = null;
      var payload = null;

      // Start from MasterStudy's existing JSON payload when possible,
      // so we don't accidentally drop/replace fields (especially arrays).
      if (requestBody && typeof requestBody === 'string') {
        try {
          parsedBody = JSON.parse(requestBody);
        } catch (e) {
          parsedBody = null;
        }
      } else if (requestBody && typeof requestBody === 'object') {
        // If it's already an object (plain object), keep it. If it's FormData, we can't safely merge it.
        var isPlain = Object.prototype.toString.call(requestBody) === '[object Object]';
        payload = isPlain ? requestBody : null;
        parsedBody = isPlain ? requestBody : null;
      }

      // Decide where to merge custom fields.
      // MasterStudy may use either:
      //  - payload.meta
      //  - payload.fields
      //  - payload.profile
      //  - payload being the fields object itself
      // We preserve the existing structure and only add keys where they belong.
      if (parsedBody && typeof parsedBody === 'object' && !Array.isArray(parsedBody)) {
        payload = parsedBody;
      }

      if (!payload || typeof payload !== 'object') {
        payload = {};
      }

      // Ensure canonical languages key as an array.
      customFields.mkh_languages_spoken = normalizeArray(customFields.mkh_languages_spoken);

      var target = payload;
      if (payload && typeof payload === 'object') {
        if (payload.meta && typeof payload.meta === 'object' && !Array.isArray(payload.meta)) {
          target = payload.meta;
        } else if (payload.fields && typeof payload.fields === 'object' && !Array.isArray(payload.fields)) {
          target = payload.fields;
        } else if (payload.profile && typeof payload.profile === 'object' && !Array.isArray(payload.profile)) {
          target = payload.profile;
        }
      }

      Object.keys(customFields).forEach(function (fieldName) {
        target[fieldName] = customFields[fieldName];
      });

      // Keep top-level key in sync for back-compat with older normalization.
      payload.mkh_languages_spoken = normalizeArray(payload.mkh_languages_spoken || customFields.mkh_languages_spoken);

      requestOptions = Object.assign({}, requestOptions, {
        body: JSON.stringify(payload)
      });

      return originalFetch(url, requestOptions);
    };

    hasPatchedProfileSave = true;
  }

  function syncInitialState() {
    toggleDemoDuration();
    updateVideoPreview();
  }

  function toggleDemoDuration() {
    var selected = root.querySelector('[name="mkh_offer_free_demo"]:checked');
    var durationNodes = getFieldNodes('mkh_demo_duration');

    if (!durationNodes.length) {
      return;
    }

    var wrapper = durationNodes[0].closest('.masterstudy-account-settings__field');
    if (!wrapper) {
      return;
    }

    var show = selected && selected.value === 'yes';
    wrapper.classList.toggle('d-none', !show);

    if (!show) {
      durationNodes.forEach(function (node) {
        if (node.tagName === 'SELECT') {
          node.value = '';
        } else if (node.type === 'radio') {
          node.checked = false;
        }
      });
    }
  }

  function updateVideoPreview() {
    var preview = root.querySelector('[data-mkh-video-preview]');
    if (!preview) {
      return;
    }

    var youtube = (getFieldValue('mkh_intro_video_youtube') || '').trim();
    var vimeo = (getFieldValue('mkh_intro_video_vimeo') || '').trim();
    var embed = '';

    if (youtube) {
      var youtubeIdMatch = youtube.match(/(?:v=|youtu\.be\/)([^&?/]+)/i);
      var youtubeId = youtubeIdMatch ? youtubeIdMatch[1] : '';
      if (youtubeId) {
        embed = '<iframe src="https://www.youtube-nocookie.com/embed/' + encodeURIComponent(youtubeId) + '" title="YouTube video preview" allowfullscreen></iframe>';
      }
    } else if (vimeo) {
      var vimeoMatch = vimeo.match(/vimeo\.com\/(?:video\/)?(\d+)/i);
      var vimeoId = vimeoMatch ? vimeoMatch[1] : '';
      if (vimeoId) {
        embed = '<iframe src="https://player.vimeo.com/video/' + encodeURIComponent(vimeoId) + '" title="Vimeo video preview" allowfullscreen></iframe>';
      }
    }

    if (embed) {
      preview.innerHTML = embed;
      preview.classList.add('mkh-teacher-addon__video-preview_has-video');
    } else {
      preview.innerHTML = '<div class="d-flex align-items-center justify-content-center text-muted h-100 px-3 text-center">Video preview will appear here after saving.</div>';
      preview.classList.remove('mkh-teacher-addon__video-preview_has-video');
    }
  }

  function bindFieldState() {
    root.querySelectorAll('[name="mkh_offer_free_demo"]').forEach(function (node) {
      node.addEventListener('change', function () {
        toggleDemoDuration();
        hideStatus();
      });
    });

    ['mkh_intro_video_youtube', 'mkh_intro_video_vimeo'].forEach(function (name) {
      getFieldNodes(name).forEach(function (node) {
        node.addEventListener('input', updateVideoPreview);
      });
    });

    var headline = root.querySelector('[name="mkh_professional_headline"]');
    if (headline) {
      headline.setAttribute('maxlength', '120');
    }

    var saveButton = document.querySelector(saveButtonSelector);
    if (saveButton) {
      document.addEventListener('click', function (event) {
        if (event.target !== saveButton && !saveButton.contains(event.target)) {
          return;
        }

        var payload = collectFields();
        var errors = validateFields(payload);

        if (errors.length) {
          event.preventDefault();
          event.stopImmediatePropagation();
          showStatus(errors[0].message, 'error');
          return;
        }

        hideStatus();
      }, true);
    }
  }
})(jQuery);
