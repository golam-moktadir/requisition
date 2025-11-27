document.addEventListener('DOMContentLoaded', function () {
    // Event listener for adding new attachment input fields
    document.getElementById('add-attachment').addEventListener('click', addAttachmentInput);

    // Event delegation for removing attachment input fields
    document.getElementById('attachment-group').addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove')) {
            e.target.closest('.attachment-item').remove();
            reindexAttachments();
        }
    });

    // Get references to the description input and counter elements
    const descriptionInput = document.getElementById('description');
    const counter = document.getElementById('description-counter');

    if (descriptionInput && counter) {
        const maxLength = parseInt(descriptionInput.getAttribute('maxlength')) || 250;

        const updateCounter = () => {
            const currentLength = descriptionInput.value.length;
            counter.textContent = `${currentLength}/${maxLength}`;
        };

        descriptionInput.addEventListener('input', updateCounter);

        // Initialize counter on page load (in case old value is pre-filled)
        updateCounter();
    }

    // Initialize various UI components
    initSelect2();
    initFlatpickr();
    initAttachmentHandlers();

    // Add initial attachment fields on page load
    for (let i = 0; i < 5; i++) {
        addAttachmentInput();
    }
});

/**
 * Initializes the Select2 plugin for the driver ID dropdown.
 */
function initSelect2() {
    $('#operational_staff_id').select2({
        placeholder: "Search by ID No.",
        allowClear: true,
        width: '100%'
    }).on('change', function () {
        const staffDetails = $(this).find(':selected').data('staff-details');
        updateStaffDetails(staffDetails);
    });
}

/**
 * Initializes the Flatpickr plugin for date input fields.
 */
function initFlatpickr() {
    flatpickr('#occurrence_date', {
        dateFormat: "Y-m-d",
        maxDate: new Date(),
        minDate: new Date(new Date().setFullYear(new Date().getFullYear() - 1)),
    });
}

/**
 * Initializes event handlers for attachment input fields and form submission.
 */
function initAttachmentHandlers() {
    const attachmentGroup = document.getElementById('attachment-group');
    const form = document.getElementById('offence-form');

    // Add event listener to each initial file input for previewing
    attachmentGroup.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function () {
            previewFile(this);
        });
    });

    // Prevent form submission if no attachments are selected
    form.addEventListener('submit', function (e) {
        const hasFiles = [...attachmentGroup.querySelectorAll('input[type="file"]')]
            .some(input => input.files.length > 0);

        if (!hasFiles) {
            e.preventDefault();
            document.getElementById('attachment-error').classList.remove('hidden');
            attachmentGroup.querySelectorAll('input[type="file"]').forEach(input => {
                input.classList.add('border-red-500');
            });
        }
    });
}

/**
 * Adds a new attachment input field to the form.
 */
function addAttachmentInput() {
    const group = document.getElementById('attachment-group');
    const items = group.querySelectorAll('.attachment-item');
    const count = items.length;

    if (count >= 5) {
        alert('Maximum 5 attachments allowed');
        return;
    }

    const newItem = document.createElement('div');
    newItem.className = 'attachment-item';
    newItem.innerHTML = `
        <div class="attachment-input flex flex-col sm:flex-row gap-4 items-start w-full" data-index="${count}">
            <div class="flex-1 w-full">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Complainant Attachment ${count + 1}
                </label>
                <input type="file" name="complainant_attachments[]"
                    accept=".jpg,.jpeg,.png,.gif,.pdf"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-md shadow-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    data-index="${count}">
                <p class="mt-1 text-xs text-gray-500">
                    JPG, PNG, GIF (Max 512KB), PDF (Max 1MB)
                </p>
                <div id="file-info-${count}" class="text-xs text-gray-600 mt-2"></div>
                <div id="file-preview-${count}" class="file-preview mt-2"></div>
            </div>
            <button type="button"
                class="action-btn bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700 sm:mt-6 btn-remove">
                <i class="fas fa-trash mr-1"></i> Remove
            </button>
        </div>
    `;

    group.appendChild(newItem);

    // Add event listener for the new file input for previewing
    const input = newItem.querySelector('input[type="file"]');
    input.addEventListener('change', function () {
        previewFile(this);
    });
}

/**
 * Re-indexes the attachment input fields after removal.
 */
function reindexAttachments() {
    const items = document.querySelectorAll('.attachment-item');
    items.forEach((item, index) => {
        const label = item.querySelector('label');
        const input = item.querySelector('input[type="file"]');
        const fileInfo = item.querySelector('.file-info');
        const filePreview = item.querySelector('.file-preview');

        label.textContent = `Accuser Attachment ${index + 1}`;
        input.dataset.index = index;
        fileInfo.id = `file-info-${index}`;
        filePreview.id = `file-preview-${index}`;
    });

    // Ensure at least one attachment field is present
    if (items.length === 0) {
        addAttachmentInput();
    }
}

/**
 * Handles the preview of selected files and validates file types and sizes.
 * @param {HTMLInputElement} input - The file input element.
 */
function previewFile(input) {
    const index = input.dataset.index;
    const fileInfo = document.getElementById(`file-info-${index}`);
    const filePreview = document.getElementById(`file-preview-${index}`);
    const file = input.files[0];

    // Clear previous preview and file info
    fileInfo.innerHTML = '';
    filePreview.innerHTML = '';

    if (!file) {
        return;
    }

    const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
    const maxImageSize = 512 * 1024; // 512KB in bytes
    const maxPdfSize = 1024 * 1024;   // 1MB in bytes

    if (!validImageTypes.includes(file.type) && file.type !== 'application/pdf') {
        alert('Invalid file type. Please upload JPG, PNG, GIF, or PDF.');
        input.value = '';
        return;
    }

    if (validImageTypes.includes(file.type) && file.size > maxImageSize) {
        alert('Image size exceeds 512KB limit.');
        input.value = '';
        return;
    }

    if (file.type === 'application/pdf' && file.size > maxPdfSize) {
        alert('PDF size exceeds 1MB limit.');
        input.value = '';
        return;
    }

    fileInfo.innerHTML = `
        <p><strong>File:</strong> ${file.name}</p>
        <p><strong>Size:</strong> ${(file.size / 1024).toFixed(2)} KB</p>
        <p><strong>Type:</strong> ${file.type.split('/')[1].toUpperCase()}</p>
    `;

    // Display image preview if it's an image
    if (validImageTypes.includes(file.type)) {
        const reader = new FileReader();
        reader.onload = function (e) {
            filePreview.innerHTML = `<img src="${e.target.result}" alt="Preview" style="max-width: 100px; max-height: 100px;">`;
        };
        reader.readAsDataURL(file);
    } else if (file.type === 'application/pdf') {
        filePreview.innerHTML = '<i class="fas fa-file-pdf" style="font-size: 2em; color: #dc2626;"></i> PDF File';
    }

    input.classList.remove('border-red-500');
    document.getElementById('attachment-error').classList.add('hidden');
}

/**
 * Updates the displayed driver details based on the selected driver ID.
 * @param {object} details - An object containing driver information (full_name, id_no, driving_license_no).
 */

/**
    * Handle staff selection: display details and fetch offences.
    */
$('#operational_staff_id').on('change', function () {
    const staffId = $(this).val();
    const staffIdDetails = $(this).find('option:selected').data('staff-details');

    // Update staff details display
    if (staffIdDetails) {
        $('#staff-type').text(staffIdDetails.type ?? 'N/A');
        $('#staff-name').text(staffIdDetails.full_name ?? 'N/A');
        $('#staff-id').text(staffIdDetails.id_no ?? 'N/A');
        $('#staff-phone').text(staffIdDetails.phone ?? 'N/A');
        $('#staff-birth-certificate').text(staffIdDetails.birth_certificate_no ?? 'N/A');
        $('#staff-nid').text(staffIdDetails.nid_no ?? 'N/A');
        $('#staff-license').text(staffIdDetails.driving_license_no ?? 'N/A');
        $('#staff-details').removeClass('hidden');
    } else {
        $('#staff-details').addClass('hidden');
    }

});

function updateStaffDetails(details) {
    const container = document.getElementById('staff-details');
    if (!details) {
        container.classList.add('hidden');
        return;
    }

    // document.getElementById('staff-name').textContent = details.full_name;
    // document.getElementById('staff-id-display').textContent = details.id_no;
    // document.getElementById('staff-license').textContent = details.driving_license_no;

    container.classList.remove('hidden');

}