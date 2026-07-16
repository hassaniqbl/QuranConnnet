(function (window, document) {
	'use strict';

	const config = window.mkhTeacherAddon || {};
	const formSelector = '#masterstudy-authorization-form-register .masterstudy-authorization__form-wrapper';
	const fieldDefinitions = Array.isArray(config.fields) ? config.fields : Object.values(config.fields || {});
	const genderOptions = Array.isArray(config.genderOptions) ? config.genderOptions : [];

	function ready(callback) {
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', callback, { once: true });
			return;
		}

		callback();
	}

	function createFieldWrapper(field) {
		const wrapper = document.createElement('div');
		wrapper.className = 'masterstudy-authorization__form-field mkh-teacher-addon__field';
		wrapper.dataset.mkhField = field.slug;

		if (field.type === 'select') {
			const select = document.createElement('select');
			select.name = field.slug;
			select.className = 'masterstudy-authorization__form-input mkh-teacher-addon__select';

			const placeholder = document.createElement('option');
			placeholder.value = '';
			placeholder.disabled = true;
			placeholder.selected = true;
			placeholder.hidden = true;
			placeholder.textContent = field.placeholder || field.label || field.slug;
			select.appendChild(placeholder);

			(genderOptions.length ? genderOptions : []).forEach((optionData) => {
				const option = document.createElement('option');
				option.value = optionData.value;
				option.textContent = optionData.label;
				select.appendChild(option);
			});

			wrapper.appendChild(select);
			return wrapper;
		}

		const input = document.createElement('input');
		input.type = field.type === 'tel' ? 'tel' : 'text';
		input.name = field.slug;
		input.className = 'masterstudy-authorization__form-input';
		input.placeholder = field.placeholder || field.label || field.slug;
		input.autocomplete = 'off';
		wrapper.appendChild(input);

		return wrapper;
	}

	function ensureFieldDefinition(field) {
		if (!window.authorization_data) {
			return;
		}

		if (!window.authorization_data.default_fields || typeof window.authorization_data.default_fields !== 'object') {
			window.authorization_data.default_fields = {};
		}

		if (!window.authorization_data.default_fields[field.slug]) {
			window.authorization_data.default_fields[field.slug] = {
				label: field.label,
				placeholder: field.placeholder,
				required: Boolean(field.required),
				type: field.type,
			};
			return;
		}

		window.authorization_data.default_fields[field.slug].label = field.label;
		window.authorization_data.default_fields[field.slug].placeholder = field.placeholder;
		window.authorization_data.default_fields[field.slug].required = Boolean(field.required);
		window.authorization_data.default_fields[field.slug].type = field.type;
	}

	function ensurePrivacyCheckbox(formWrapper) {
		if (formWrapper.querySelector('input[name="privacy_policy"]')) {
			return;
		}

		const actions = formWrapper.querySelector('.masterstudy-authorization__actions');
		if (!actions) {
			return;
		}

		const block = document.createElement('div');
		block.className = 'masterstudy-authorization__gdpr mkh-teacher-addon__terms';

		const checkboxWrap = document.createElement('div');
		checkboxWrap.className = 'masterstudy-authorization__checkbox';

		const input = document.createElement('input');
		input.type = 'checkbox';
		input.name = 'privacy_policy';
		input.id = 'mkh-teacher-addon-privacy-policy';
		checkboxWrap.appendChild(input);

		const wrapper = document.createElement('span');
		wrapper.className = 'masterstudy-authorization__checkbox-wrapper';
		checkboxWrap.appendChild(wrapper);

		const label = document.createElement('span');
		label.className = 'masterstudy-authorization__gdpr-text';
		label.textContent = config.termsLabel || 'I agree to the Terms & Conditions and Privacy Policy.';

		block.appendChild(checkboxWrap);
		block.appendChild(label);

		actions.parentNode.insertBefore(block, actions);
	}

	function injectFields() {
		const formWrapper = document.querySelector(formSelector);
		if (!formWrapper) {
			return;
		}

		fieldDefinitions.forEach((field) => {
			if (!field || !field.slug) {
				return;
			}

			ensureFieldDefinition(field);

			if (formWrapper.querySelector('[name="' + field.slug + '"]')) {
				return;
			}

			const insertionPoint = formWrapper.querySelector('.masterstudy-authorization__instructor') || formWrapper.querySelector('.masterstudy-authorization__actions');
			const wrapper = createFieldWrapper(field);

			if (insertionPoint && insertionPoint.parentNode) {
				insertionPoint.parentNode.insertBefore(wrapper, insertionPoint);
			} else {
				formWrapper.appendChild(wrapper);
			}
		});

		ensurePrivacyCheckbox(formWrapper);
	}

	ready(injectFields);
})(window, document);
