document.addEventListener('DOMContentLoaded', () => {
    // Enhanced logging with color coding and better formatting
    const logger = {
        debug: (message, data = {}) => console.debug(`%c[DEBUG] ${message}`, 'color: blue', data),
        info: (message, data = {}) => console.info(`%c[INFO] ${message}`, 'color: green', data),
        warn: (message, data = {}) => console.warn(`%c[WARN] ${message}`, 'color: orange', data),
        error: (message, error = {}) => console.error(`%c[ERROR] ${message}`, 'color: red', error),
        logFormState: () => {
            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => data[key] = value);
            logger.debug('Current Form State:', data);
        }
    };

    // Initialize Flatpickr with better error handling
    function initializeDatePickers() {
        try {
            if (typeof flatpickr === 'undefined') {
                throw new Error('Flatpickr not loaded');
            }

            const dateInputs = document.querySelectorAll('.flatpickr');
            if (!dateInputs.length) {
                logger.warn('No date inputs found');
                return;
            }

            flatpickr('.flatpickr', {
                dateFormat: 'Y-m-d',
                allowInput: true,
                defaultDate: (elem) => elem.value || null,
                onChange: (dates, dateStr, instance) => {
                    logger.info(`Date changed: ${instance.element.id}`, {
                        newValue: dateStr,
                        previousValue: instance.element.dataset.lastValue || 'null'
                    });
                    instance.element.dataset.lastValue = dateStr;
                }
            });
            logger.info('Date pickers initialized');
        } catch (error) {
            logger.error('Date picker initialization failed', error);
            showGlobalError('Date picker initialization failed. Please refresh the page.');
        }
    }

    // DOM Elements with null checks
    const form = document.getElementById('departure-form');
    if (!form) {
        logger.error('Form element not found');
        return;
    }

    const enableCityValidation = document.getElementById('enable_city_validation');
    const resetButton = document.getElementById('reset-button');

    enableCityValidation.addEventListener('change', function () {
        // Dynamically update the value on check/uncheck
        enableCityValidation.value = this.checked ? '1' : '0';
    });


    // Create feedback containers
    function createFeedbackContainer(id, baseClass) {
        const container = document.createElement('div');
        container.id = id;
        container.className = `${baseClass} p-2 rounded-md mb-2 hidden`;
        form.prepend(container);
        return container;
    }

    const errorContainer = createFeedbackContainer('form-errors', 'bg-red-100 text-red-700');
    const successContainer = createFeedbackContainer('form-success', 'bg-green-100 text-green-700');

    // UI Feedback functions
    function showFeedback(container, message, isError = true) {
        container.innerHTML = typeof message === 'string' ? `<p>${message}</p>` : message;
        container.classList.remove('hidden');
        (isError ? successContainer : errorContainer).classList.add('hidden');
    }

    function clearFeedback() {
        errorContainer.classList.add('hidden');
        successContainer.classList.add('hidden');
    }

    // Form State Management
    function storeInitialState() {
        Array.from(form.elements).forEach(element => {
            const state = {
                value: element.value,
                checked: element.checked,
                disabled: element.disabled
            };
            element.dataset.initialState = JSON.stringify(state);
        });
        logger.debug('Initial form state stored');
    }

    function resetForm() {
        logger.info('Resetting form');

        // Animation
        form.style.opacity = '0.7';
        form.style.transition = 'opacity 0.3s ease';

        // Reset all elements
        Array.from(form.elements).forEach(element => {
            try {
                const initialState = JSON.parse(element.dataset.initialState || '{}');
                if (element.type === 'checkbox' || element.type === 'radio') {
                    element.checked = initialState.checked || false;
                } else {
                    element.value = initialState.value || '';
                }
                element.disabled = initialState.disabled || false;
            } catch (e) {
                logger.warn(`Error resetting ${element.name}`, e);
            }
        });

        // Special cases
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

        // Clear feedback and errors
        clearFeedback();
        document.querySelectorAll('.city-error').forEach(el => el.classList.add('hidden'));

        // Complete animation
        setTimeout(() => form.style.opacity = '1', 300);
        logger.info('Form reset complete');
    }

    // Validation System
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

            // Date validation
            if (!dateInput) {
                errors.push(`${type.toUpperCase()} date input not found`);
                isValid = false;
            }

            const isDateSet = dateInput?.value.trim();
            const isValidationEnabled = enableCityValidation?.checked ? 1 : 0;

            if (isDateSet && isValidationEnabled) {
                // Bus validation
                const hasBus = Array.from(
                    citySection.querySelectorAll(`input[name^="bus_type[${type}][${cityId}]"]`)
                ).some(cb => cb.checked);

                if (!hasBus) errors.push('At least one bus must be selected');

                // Day validation
                const hasDay = Array.from(
                    citySection.querySelectorAll(`input[name^="bus_days[${type}][${cityId}]"]:not([data-day="all"])`)
                ).some(cb => !cb.disabled && cb.checked);

                if (!hasDay) errors.push('At least one weekday must be selected');

                // Booth validation
                const hasBooth = Array.from(
                    citySection.querySelectorAll(`input[name^="booth[${type}][${cityId}]"]`)
                ).some(cb => cb.checked);

                if (!hasBooth) errors.push('At least one booth must be selected');

                isValid = hasBus && hasDay && hasBooth;
            }

            // Update error display
            if (errors.length) {
                errorElement.innerHTML = `<strong>${cityName} (${type.toUpperCase()}):</strong> ${errors.join(', ')}`;
                errorElement.classList.remove('hidden');
            } else {
                errorElement.classList.add('hidden');
            }

            return { isValid, errors, cityId, type };

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

    function validateAllCities() {
        logger.info('Starting form validation');

        let allValid = true;
        const allErrors = [];
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

            const nonAcResult = validateCitySection(section, cityId, 'non_ac');
            const acResult = validateCitySection(section, cityId, 'ac');

            if (!nonAcResult.isValid) allErrors.push(...nonAcResult.errors);
            if (!acResult.isValid) allErrors.push(...acResult.errors);

            allValid = nonAcResult.isValid && acResult.isValid && allValid;
        });

        logger.info('Validation complete', { isValid: allValid, errorCount: allErrors.length });
        return { isValid: allValid, errors: allErrors };
    }

    // Modified Form Submission - Traditional POST with validation
    function handleFormSubmit(e) {
        e.preventDefault();
        logger.info('Form submission started');
        clearFeedback();

        // Validate first
        const { isValid, errors } = validateAllCities();
        if (!isValid) {
            showFeedback(errorContainer,
                'Please fix the following issues:<ul class="list-disc pl-5 mt-1">' +
                errors.map(e => `<li>${e}</li>`).join('') + '</ul>');
            return false;
        }

        // Show loading state
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Processing...';

        // Submit the form traditionally
        form.submit();

        // Reset button after timeout in case submission fails
        setTimeout(() => {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        }, 5000);
    }

    // Event Handlers
    function handleChange(e) {
        const target = e.target;
        const citySection = target.closest('.city-grid-section');
        const cityId = citySection?.querySelector('input[name="cities[]"]')?.value;
        const cityName = citySection?.querySelector('.departure-header-cell')?.textContent.trim() || 'Unknown';

        try {
            // Log the change
            logger.debug('Change detected', {
                element: target.name || target.id || target.className,
                type: target.type,
                value: target.value,
                checked: target.checked,
                cityId,
                cityName
            });

            // Handle specific element types
            if (target.id === 'enable_city_validation') {
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

    // Initialize the form
    function initialize() {
        logger.info('Initializing form');

        initializeDatePickers();

        // Initialize booth checkboxes
        document.querySelectorAll('.booth-checkbox').forEach(checkbox => {
            document.querySelectorAll(`[data-group="${checkbox.id}"] select`)
                .forEach(select => select.disabled = !checkbox.checked);
        });

        // Initialize bus checkboxes
        document.querySelectorAll('.ac-bus-checkbox, .non-ac-bus-checkbox').forEach(checkbox => {
            const input = checkbox.closest('.ac-row')?.querySelector('.ac-value-input');
            if (input) {
                input.disabled = !checkbox.checked;
                input.classList.toggle('bg-gray-200', !checkbox.checked);
            }
        });

        // Set up event listeners
        form.addEventListener('change', handleChange);
        form.addEventListener('submit', handleFormSubmit);

        if (resetButton) {
            resetButton.addEventListener('click', resetForm);
        }

        // Store initial state
        storeInitialState();
        logger.info('Form initialization complete');
    }

    // Start the application
    initialize();
});


