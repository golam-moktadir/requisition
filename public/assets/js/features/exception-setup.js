document.addEventListener('DOMContentLoaded', () => {
    // Logger with color-coded levels
    const logger = {
        debug: (msg, data = {}) => console.debug(`%c[DEBUG] ${msg}`, 'color: blue', data),
        info: (msg, data = {}) => console.info(`%c[INFO] ${msg}`, 'color: green', data),
        warn: (msg, data = {}) => console.warn(`%c[WARN] ${msg}`, 'color: orange', data),
        error: (msg, err = {}) => console.error(`%c[ERROR] ${msg}`, 'color: red', err),
    };

    // Get form and critical elements
    const form = document.getElementById('departure-form');

    if (!form) {
        logger.error('Form element not found');
        return;
    }

    const coachValidationCheckbox = document.getElementById('enable_coach_validation');

    if (!coachValidationCheckbox) {
        logger.warn('Coach validation checkbox not found');
    }

    const resetButton = document.getElementById('reset-button');

    // Update checkbox value dynamically
    coachValidationCheckbox?.addEventListener('change', function () {
        this.value = this.checked ? '1' : '0';
    });

    // Feedback containers for UI messages
    function createFeedbackContainer(id, baseClass) {
        const container = document.createElement('div');
        container.id = id;
        container.className = `${baseClass} p-2 rounded-md mb-2 hidden`;
        form.prepend(container);
        return container;
    }

    const errorContainer = createFeedbackContainer('form-errors', 'bg-red-100 text-red-700');
    const successContainer = createFeedbackContainer('form-success', 'bg-green-100 text-green-700');

    function showFeedback(container, message, isError = true) {
        container.innerHTML = typeof message === 'string' ? `<p>${message}</p>` : message;
        container.classList.remove('hidden');
        if (isError) successContainer.classList.add('hidden');
        else errorContainer.classList.add('hidden');
    }

    function clearFeedback() {
        errorContainer.classList.add('hidden');
        successContainer.classList.add('hidden');
    }

    // Store initial state to allow form reset
    function storeInitialState() {
        Array.from(form.elements).forEach(el => {
            el.dataset.initialState = JSON.stringify({
                value: el.value,
                checked: el.checked,
                disabled: el.disabled,
            });
        });
        logger.debug('Initial form state stored');
    }

    // Reset form and clear errors
    function resetForm() {
        logger.info('Resetting form');
        form.style.opacity = '0.7';
        form.style.transition = 'opacity 0.3s ease';

        Array.from(form.elements).forEach(el => {
            try {
                const state = JSON.parse(el.dataset.initialState || '{}');
                if (el.type === 'checkbox' || el.type === 'radio') {
                    el.checked = state.checked || false;
                } else {
                    el.value = state.value || '';
                }
                el.disabled = state.disabled || false;
            } catch (e) {
                logger.warn(`Error resetting element ${el.name}`, e);
            }
        });

        // Handle special cases for disabling inputs
        document.querySelectorAll('.ac-bus-checkbox, .non-ac-bus-checkbox').forEach(cb => {
            const input = cb.closest('.ac-row')?.querySelector('.ac-value-input');
            if (input) {
                input.disabled = !cb.checked;
                input.classList.toggle('bg-gray-200', !cb.checked);
            }
        });

        document.querySelectorAll('.booth-checkbox').forEach(cb => {
            document.querySelectorAll(`[data-group="${cb.id}"] select`).forEach(select => {
                select.disabled = !cb.checked;
            });
        });

        clearFeedback();
        document.querySelectorAll('.city-error').forEach(el => el.classList.add('hidden'));

        setTimeout(() => (form.style.opacity = '1'), 300);
        logger.info('Form reset complete');
    }

    // Validate city section including coach number presence
    function validateCitySection(citySection, cityId, type) {
        const validationId = `${type}_${cityId}`;
        logger.debug(`Validating ${validationId}`);

        try {
            const dateInput = document.getElementById(`${type}_date`);
            const cityName = citySection.querySelector('.departure-header-cell')?.textContent.trim() || 'Unknown';
            const errorElement = document.getElementById(`${type}_error_${cityId}`) ||
                createCityErrorElement(citySection, validationId, cityName);

            let isValid = true;
            const errors = [];

            if (!dateInput) {
                errors.push(`${type.toUpperCase()} date input not found`);
                isValid = false;
            }

            const isDateSet = dateInput?.value.trim();
            const isValidationEnabled = coachValidationCheckbox?.checked ? 1 : 0;

            let missingSubroutes = [];

            if (isDateSet && isValidationEnabled) {
                // Coach number validation
                const coachInputs = citySection.querySelectorAll('input[name^="subroute_coaches["]');

                coachInputs.forEach(input => {
                    if (input.value.trim() === '') {
                        // Expect a data attribute "data-subroute-name" on inputs for meaningful names
                        const name = input.dataset.subrouteName || input.name;
                        missingSubroutes.push(name);
                    }
                });

                if (missingSubroutes.length) {
                    const msg = `Coach number missing for: ${missingSubroutes.join(', ')}.\nPlease check the corresponding information.`;
                    errors.push(msg);
                    isValid = false;
                }



                // Bus validation
                const hasBus = Array.from(
                    citySection.querySelectorAll(`input[name^="bus_type[${type}][${cityId}]"]`)
                ).some(cb => cb.checked);

                // Day validation
                const hasDay = Array.from(
                    citySection.querySelectorAll(`input[name^="bus_days[${type}][${cityId}]"]:not([data-day="all"])`)
                ).some(cb => !cb.disabled && cb.checked);

                // Booth validation
                const hasBooth = Array.from(
                    citySection.querySelectorAll(`input[name^="booth[${type}][${cityId}]"]`)
                ).some(cb => cb.checked);

            }

            if (errors.length) {
                errorElement.innerHTML = `<strong>${cityName} (${type.toUpperCase()}):</strong> ${errors.join(', ')}`;
                errorElement.classList.remove('hidden');
            } else {
                errorElement.classList.add('hidden');
            }

            return { isValid, errors, cityId, type, missingSubroutes };
        } catch (error) {
            logger.error(`Validation failed for ${validationId}`, error);
            return { isValid: false, errors: ['Validation error'], cityId, type };
        }
    }

    function createCityErrorElement(section, id, cityName) {
        const el = document.createElement('div');
        el.id = id;
        el.className = 'city-error text-red-600 text-sm mb-2 hidden';
        section.prepend(el);
        return el;
    }

    // Validate all cities on the form
    function validateAllCities() {
        logger.info('Starting form validation');

        let allValid = true;
        const allErrors = [];
        let allMissingSubroutes = [];
        const allMissingCoaches = [];

        const citySections = document.querySelectorAll('.city-grid-section');

        if (!citySections.length) {
            const error = 'No city sections found';
            logger.warn(error);
            return { isValid: false, errors: [error] };
        }

        citySections.forEach(section => {
            const cityId = section.querySelector('input[name="cities[]"]')?.value;
            if (!cityId) {
                allErrors.push('City ID missing');
                allValid = false;
                return;
            }

            // Validate only 'ac' type currently
            const result = validateCitySection(section, cityId, 'ac');
            if (!result.isValid) allErrors.push(...result.errors);

            // Collect missing coaches for combined alert
            if (result.missingSubroutes?.length) {
                allMissingSubroutes = allMissingSubroutes.concat(result.missingSubroutes);
            }

            allValid = result.isValid && allValid;
        });

        // Show alert once if any missing coaches found
        if (allMissingSubroutes.length > 0) {
            const uniqueSubroutes = [...new Set(allMissingSubroutes)];
            alert(`Coach number missing for all subroutes: ${uniqueSubroutes.join(', ')}.\nPlease enter coach numbers for all subroutes.`);
        }

        return { isValid: allValid, errors: allErrors };
    }

    // Form submission with validation and UI feedback
    function handleFormSubmit(e) {
        e.preventDefault();
        logger.info('Form submission started');
        clearFeedback();

        const { isValid, errors } = validateAllCities();
        if (!isValid) {
            showFeedback(errorContainer,
                'Please fix the following issues:<ul class="list-disc pl-5 mt-1">' +
                errors.map(e => `<li>${e}</li>`).join('') + '</ul>');
            return false;
        }

        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Processing...';

        form.submit();

        setTimeout(() => {
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        }, 5000);
    }

    // Change handler for dynamic validations and UI changes
    function handleChange(e) {
        const target = e.target;
        const citySection = target.closest('.city-grid-section');
        const cityId = citySection?.querySelector('input[name="cities[]"]')?.value;
        const cityName = citySection?.querySelector('.departure-header-cell')?.textContent.trim() || 'Unknown';

        try {
            logger.debug('Change detected', {
                element: target.name || target.id || target.className,
                type: target.type,
                value: target.value,
                checked: target.checked,
                cityId,
                cityName
            });

            if (target.id === 'enable_coach_validation') {
                validateAllCities();
                return;
            }



            if (target.classList.contains('ac-bus-checkbox') ||
                target.classList.contains('non-ac-bus-checkbox')) {
                const input = target.closest('.ac-row')?.querySelector('.ac-value-input');

                if (input) {
                    input.disabled = !target.checked;
                    input.value = target.checked ? (input.value || '1') : '';
                    console.log(`Input value set to:`, input.value);

                    input.classList.toggle('bg-gray-200', !target.checked);

                    // Add listener to log value changes on user input
                    input.addEventListener('input', (e) => {
                        console.log(`Input value changed to:`, e.target.value);
                    }, { once: true }); // Use `{ once: true }` to avoid multiple listeners on repeated toggles

                }
                validateCitySection(citySection, cityId,
                    target.name.includes('non_ac') ? 'non_ac' : 'ac');
                return;
            }

            if (target.classList.contains('ac-value-input')) {
                target.value = Math.min(99, Math.max(0, parseInt(target.value.replace(/\D/g, '') || 0)));
                return;
            }

            if (target.classList.contains('ac-day-checkbox') ||
                target.classList.contains('non-ac-day-checkbox')) {
                const busType = target.closest('.weekday-grid')?.dataset.section?.includes('non_ac')
                    ? 'non_ac' : 'ac';

                if (target.dataset.day === 'all') {
                    target.closest('.weekday-grid')
                        .querySelectorAll(`input[data-day]:not([data-day="all"])`)
                        .forEach(cb => { if (!cb.disabled) cb.checked = target.checked; });
                } else {
                    const allChecked = Array.from(
                        target.closest('.weekday-grid')
                            .querySelectorAll(`input[data-day]:not([data-day="all"])`)
                    ).every(cb => cb.checked || cb.disabled);

                    const allCheckbox = target.closest('.weekday-grid')
                        .querySelector('input[data-day="all"]');
                    if (allCheckbox && !allCheckbox.disabled) {
                        allCheckbox.checked = allChecked;
                    }
                }
                validateCitySection(citySection, cityId, busType);
                return;
            }

            if (target.classList.contains('booth-checkbox')) {
                document.querySelectorAll(`[data-group="${target.id}"] select`)
                    .forEach(select => select.disabled = !target.checked);

                validateCitySection(citySection, cityId,
                    target.name.includes('non_ac') ? 'non_ac' : 'ac');
                return;
            }








            if (target.id === 'non_ac_date' || target.id === 'ac_date') {
                validateAllCities();
            }
        } catch (error) {
            logger.error('Change handler error', error);
        }
    }

    // Initialize everything
    function initialize() {
        logger.info('Initializing form');
        // Date picker init could go here if needed

        // Initialize booth select disabling/enabling
        document.querySelectorAll('.booth-checkbox').forEach(cb => {
            document.querySelectorAll(`[data-group="${cb.id}"] select`).forEach(select => {
                select.disabled = !cb.checked;
            });
        });

        // Initialize bus checkbox related inputs
        document.querySelectorAll('.ac-bus-checkbox, .non-ac-bus-checkbox').forEach(cb => {
            const input = cb.closest('.ac-row')?.querySelector('.ac-value-input');
            if (input) {
                input.disabled = !cb.checked;
                input.classList.toggle('bg-gray-200', !cb.checked);
            }
        });

        form.addEventListener('change', handleChange);
        form.addEventListener('submit', handleFormSubmit);
        resetButton?.addEventListener('click', resetForm);

        storeInitialState();
        logger.info('Form initialization complete');
    }

    initialize();
});
