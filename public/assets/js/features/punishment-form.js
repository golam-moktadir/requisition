/**
 * JavaScript for the punishment form.
 * Initializes Select2, Flatpickr, file preview, remarks counter, handles AJAX for offence filtering and form submission,
 * and manages the history timeline "See More" functionality.
 */

document.addEventListener('DOMContentLoaded', function () {
    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // DOM Elements
    const $form = $('#punishment-form');

    const $actionSelect = $('#actionTypeSelect');
    const $startDateInput = $('#start_date');
    const $isEndDateCheckbox = $('#is_end_date');
    const $endDateInput = $('#end_date');
    const $startDateWrapper = $('#startDateWrapper');
    const $isEndDateWrapper = $('#isEndDate');
    const $endDateWrapper = $('#endDateWrapper');
    const $remarksInput = $('#remarks');
    const $attachmentInput = $('#attachment_path');

    const $viewHistoryButton = $('#view-history');
    const $closeHistoryModalButton = $('#close-history-modal');
    const $historyModal = $('#history-modal');

    // Get date plus N years from a given date string
    function getDatePlusYears(dateStr, years) {
        const date = new Date(dateStr);
        date.setFullYear(date.getFullYear() + years);
        const result = date.toISOString().split('T')[0];
        console.log(`[getDatePlusYears] Input: ${dateStr}, Years: ${years}, Result: ${result}`);
        return result;
    }

    // Show/hide date fields & set end_date logic
    function toggleDateFields() {
        const selectedAction = $actionSelect.val();
        console.log(`[toggleDateFields] Selected action: ${selectedAction}`);

        if (['suspension', 'warning', 'duty-off'].includes(selectedAction)) {
            $startDateWrapper.show();
            $isEndDateWrapper.show();

            const startDateVal = $startDateInput.val() || new Date().toISOString().split('T')[0];

            if ($isEndDateCheckbox.is(':checked')) {
                console.log('[toggleDateFields] "Is End Date" checked – enable manual end date');
                $endDateWrapper.show();
                if (endDatePicker) {
                    endDatePicker.set('minDate', startDateVal);
                    console.log(`[toggleDateFields] Set endDatePicker minDate to ${startDateVal}`);
                }
            } else if (selectedAction === 'duty-off') {
                console.log('[toggleDateFields] Duty-off, end date unchecked - auto-setting end_date to start_date + 99 years');
                $endDateWrapper.hide();
                if (startDateVal) {
                    const autoEnd = getDatePlusYears(startDateVal, 99);
                    $endDateInput.val(autoEnd);
                    console.log(`[toggleDateFields] Auto-set end_date: ${autoEnd}`);
                } else {
                    $endDateInput.val('');
                    console.log('[toggleDateFields] No start_date to calculate auto end_date');
                }
            } else {
                $endDateWrapper.hide();
                $endDateInput.val('');
            }
        } else {
            console.log('[toggleDateFields] Action is not suspension/warning/duty-off – hide all date fields');
            $startDateWrapper.hide();
            $isEndDateWrapper.hide();
            $endDateWrapper.hide();
            $startDateInput.val('');
            $endDateInput.val('');
            $isEndDateCheckbox.prop('checked', false);
        }
    }

    // Initialize Flatpickr for start_date
    const startDatePicker = flatpickr($startDateInput[0], {
        dateFormat: 'Y-m-d',
        minDate: new Date(new Date().setDate(new Date().getDate() - 7)), // 7 days back
        maxDate: new Date(new Date().setDate(new Date().getDate() + 3)), // 3 days forward
        defaultDate: $startDateInput.val() || null,
        onChange: function (selectedDates, dateStr) {
            console.log(`[startDatePicker.onChange] Selected start date: ${dateStr}`);
            if ($isEndDateCheckbox.is(':checked') && endDatePicker) {
                endDatePicker.set('minDate', dateStr || new Date());
                if ($endDateInput.val() && $endDateInput.val() < dateStr) {
                    $endDateInput.val(dateStr);
                    endDatePicker.setDate(dateStr, false);
                    console.log(`[startDatePicker.onChange] Adjusted end_date to: ${dateStr}`);
                }
            } else if ($actionSelect.val() === 'duty-off') {
                if (dateStr) {
                    const autoEndDate = getDatePlusYears(dateStr, 99);
                    $endDateInput.val(autoEndDate);
                    console.log(`[startDatePicker.onChange] Auto-set end_date to: ${autoEndDate}`);
                }
            }
        }
    });

    // Initialize Flatpickr for end_date
    let endDatePicker = null;
    if ($endDateInput[0]) {
        endDatePicker = flatpickr($endDateInput[0], {
            dateFormat: 'Y-m-d',
            minDate: $startDateInput.val() || new Date(),
            maxDate: new Date().toISOString().split('T')[0],
            defaultDate: $endDateInput.val() || null,
            onChange: function (selectedDates, dateStr) {
                console.log(`[endDatePicker.onChange] Selected end date: ${dateStr}`);
            }
        });
    }

    // Handle start_date manual changes
    $startDateInput.on('change', function () {
        console.log('[startDateInput.change] Start date changed manually');
        if (!$isEndDateCheckbox.is(':checked') && $actionSelect.val() === 'duty-off') {
            const startDate = $(this).val();
            if (startDate) {
                const autoEndDate = getDatePlusYears(startDate, 99);
                $endDateInput.val(autoEndDate);
                console.log(`[startDateInput.change] Auto-set end_date to: ${autoEndDate}`);
            } else {
                $endDateInput.val('');
                console.log('[startDateInput.change] Start date empty, cleared end_date');
            }
        }
    });

    // Bind events
    $actionSelect.on('change', toggleDateFields);
    $isEndDateCheckbox.on('change', toggleDateFields);
    toggleDateFields(); // Initial setup

    // Initialize Select2
    $('#operational_staff_id').select2({
        placeholder: 'Search Staff by ID',
        allowClear: true,
        width: '100%',
        theme: 'default'
    });
    
    $('#offence_id').select2({
        placeholder: 'Select an offence',
        allowClear: true,
        width: '100%',
        theme: 'default'
    });

    // Handle staff selection
    $('#operational_staff_id').on('change', function () {
        const staffId = $(this).val();
        const staffDetails = $(this).find('option:selected').data('staff-details');
        const $offenceSelect = $('#offence_id');

        // Update staff details display
        if (staffDetails) {
            $('#staff-type').text(staffDetails.type ?? 'N/A');
            $('#staff-name').text(staffDetails.full_name ?? 'N/A');
            $('#staff-id').text(staffDetails.id_no ?? 'N/A');
            $('#staff-phone').text(staffDetails.phone ?? 'N/A');
            $('#staff-birth-certificate').text(staffDetails.birth_certificate_no ?? 'N/A');
            $('#staff-nid').text(staffDetails.nid_no ?? 'N/A');
            $('#staff-license').text(staffDetails.driving_license_no ?? 'N/A');
            $('#staff-details').removeClass('hidden');
        } else {
            $('#staff-details').addClass('hidden');
        }

        // Fetch offences
        $offenceSelect.prop('disabled', true).empty().append('<option value="">Loading offences...</option>');
        if (staffId) {
            $.ajax({
                url: `/punishments/offences/${staffId}`,
                method: 'GET',
                success: function (response) {
                    $offenceSelect.empty().append('<option value="">Select an offence</option>');
                    if (response.success && response.data.offences.length) {
                        response.data.offences.forEach(function (offence) {
                            const option = new Option(offence.text, offence.id, false, false);
                            $(option).data('staff-details', offence.details);
                            $offenceSelect.append(option);
                        });
                    } else {
                        $offenceSelect.append('<option value="">No offences found</option>');
                    }
                    $offenceSelect.prop('disabled', false).val(null).trigger('change');
                },
                error: function (xhr) {
                    $offenceSelect.empty().append('<option value="">Failed to load offences</option>').prop('disabled', false);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.error || 'Failed to fetch offences. Please try again.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            });
        } else {
            $offenceSelect.empty().append('<option value="">Select a staff first</option>').prop('disabled', false).val(null).trigger('change');
        }
    });

    // Handle offence selection
    $('#offence_id').on('change', function () {
        const offenceDetails = $(this).find('option:selected').data('staff-details');
        if (offenceDetails) {
            $('#offence-type').text(offenceDetails.offence_type || 'N/A');
            $('#offence-date').text(offenceDetails.occurrence_date || 'N/A');
            $('#offence-description').text(offenceDetails.description || 'N/A');
            $('#offence-details').removeClass('hidden');
        } else {
            $('#offence-details').addClass('hidden');
        }
    });

    // Trigger initial staff selection
    if ($('#operational_staff_id').val()) {
        $('#operational_staff_id').trigger('change');
    }

    // Remarks character counter
    $remarksInput.on('input', function () {
        const length = $(this).val().length;
        $('#remarks-counter').text(`${length}/250`);
    }).trigger('input');

    // Handle file input
    $attachmentInput.on('change', function (e) {
        const file = e.target.files[0];
        const $preview = $('#preview_image');
        const $previewContainer = $('#image_preview');
        const $info = $('#file_info');
        const $fileName = $('#file_name');
        const $fileSize = $('#file_size');
        const $fileType = $('#file_type');
        const $fileError = $('#file_error');

        $previewContainer.hide();
        $info.hide();
        $fileError.hide();

        if (file) {
            // Validate file size
            const fileSizeKB = (file.size / 1024).toFixed(2);
            if (fileSizeKB > 512) {
                $fileError.text('File size must not exceed 512KB.').show();
                $(this).val('');
                return;
            }

            // Update file info
            $fileName.text(file.name);
            $fileSize.text(`${fileSizeKB} KB`);
            $fileType.text(file.type.split('/')[1].toUpperCase());
            $info.show();

            // Image preview
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $preview.attr('src', e.target.result);
                    $previewContainer.show();
                };
                reader.readAsDataURL(file);
            }
        }
    });

    // History Modal
    if ($viewHistoryButton.length && $historyModal.length) {
        $viewHistoryButton.on('click', () => {
            $historyModal.removeClass('hidden').addClass('flex');
        });

        $closeHistoryModalButton.on('click', () => {
            $historyModal.removeClass('flex').addClass('hidden');
        });

        $historyModal.on('click', (e) => {
            if (e.target === $historyModal[0]) {
                $historyModal.removeClass('flex').addClass('hidden');
            }
        });

        $(document).on('keydown', (e) => {
            if (e.key === 'Escape' && !$historyModal.hasClass('hidden')) {
                $historyModal.removeClass('flex').addClass('hidden');
            }
        });
    } else {
        console.warn('History modal elements not found.');
    }

    // Form submission with AJAX
    $form.on('submit', function (e) {
        e.preventDefault();
        const $submitButton = $(this).find('#submit-button');
        $submitButton.prop('disabled', true).text('Submitting...');

        $.ajax({
            url: $form.attr('action'),
            method: 'POST', // Use POST with _method=PUT
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: response.title || 'Success',
                        text: response.message || 'Punishment updated successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = response.redirect_to || '/punishments';
                    });
                } else {
                    $submitButton.prop('disabled', false).text('Update');
                    Swal.fire({
                        icon: 'error',
                        title: response.title || 'Error',
                        text: response.message || 'Unexpected response from server.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            },
            error: function (xhr) {
                $submitButton.prop('disabled', false).text('Update');
                const response = xhr.responseJSON || {};
                if (xhr.status === 422) {
                    // Handle validation errors
                    let errorText = response.message || 'Please correct the errors in the form.';
                    if (response.errors) {
                        errorText = Object.values(response.errors).flat().join('<br>');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: response.title || 'Validation Error',
                        html: errorText,
                        showConfirmButton: true
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: response.title || 'Error',
                        text: response.message || 'An error occurred. Please check the form and try again.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            }
        });
    });
});