
/**
 * Initialize the form with default values and event listeners
 */
document.addEventListener('DOMContentLoaded', function () {
    // Initialize form calculations and visibility
    calculateRowColumn();
    updateColsInRightOptions();
    toggleBusType();

    // Set up event listeners
    setupEventListeners();

    // Initialize form validation
    initializeFormValidation();

    calculateRowsAndExtraSeats();

    document.getElementById('bus_total_seat')?.addEventListener('input', calculateRowsAndExtraSeats);
    document.getElementById('bus_total_col')?.addEventListener('input', calculateRowsAndExtraSeats);

    toggleEntranceFields();

    document.getElementById('total_entrances')?.addEventListener('change', toggleEntranceFields);
});

/**
 * Set up all event listeners for the form
 */
function setupEventListeners() {
    // AC Option radio buttons
    document.querySelectorAll('input[name="bus_is_ac"]').forEach(radio => {
        radio.addEventListener('change', toggleBusType);
    });

    // Seat and column calculations
    document.getElementById('bus_total_seat').addEventListener('input', calculateRowColumn);
    document.getElementById('bus_total_col').addEventListener('input', function () {
        calculateRowColumn();
        updateColsInRightOptions();
    });

    // Extra seats toggle
    document.getElementById('total_extra_seats').addEventListener('change', toggleExtraSeats);

    // Entrance toggle
    // document.getElementById('total_entrances').addEventListener('change', toggleEntrance2);
}

/**
 * Toggle between AC and Non-AC class selection
 */
function toggleBusType() {
    const selectedType = document.querySelector('input[name="bus_is_ac"]:checked');
    const acBox = document.getElementById('acClassBox');
    const nonAcBox = document.getElementById('nonAcClassBox');
    const seatInput = document.getElementById('busSeatClassIdHidden');

    if (!selectedType) return;

    const isAc = selectedType.value === '1';

    // Toggle visibility
    acBox.classList.toggle('hidden', !isAc);
    nonAcBox.classList.toggle('hidden', isAc);

    // Auto-pick default value
    const selectedDropdown = isAc ? document.getElementById('acSelect') : document.getElementById('nonAcSelect');
    const defaultOption = selectedDropdown.querySelector('option[selected]') ||
        selectedDropdown.querySelector('option:not([disabled])');

    if (defaultOption) {
        selectedDropdown.value = defaultOption.value;
        seatInput.value = defaultOption.value;
        console.log(`Bus AC Type: ${isAc ? 'AC' : 'Non-AC'}`);
        console.log(`Selected Class: ${defaultOption.text} (${defaultOption.value})`);
    }
}

function syncBusSeatId(selectElement) {
    const seatInput = document.getElementById('busSeatClassIdHidden');
    seatInput.value = selectElement.value;

    console.log(
        `User selected: ${selectElement.options[selectElement.selectedIndex].text} (ID: ${selectElement.value})`
    );
}

/**
 * Calculate total rows based on seats and columns
 */
function calculateRowColumn() {
    const seats = parseInt(document.getElementById('bus_total_seat').value) || 0;
    const columns = parseInt(document.getElementById('bus_total_col').value) || 1;
    const totalRows = columns > 0 ? Math.ceil(seats / columns) : 0;
    document.getElementById('bus_total_row').value = totalRows;
}

/**
 * Update the columns in right options based on total columns
 */
function updateColsInRightOptions() {
    const totalCols = parseInt(document.getElementById('bus_total_col')?.value || 0);
    const select = document.getElementById('bus_col_in_right');
    // Backup current value
    const currentValue = select?.value;

    // Clear existing options
    select.innerHTML = '';

    // Only proceed if totalCols > 1 (we need at least 2 to split)
    if (totalCols > 1) {
        for (let i = 1; i < totalCols; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = `${i} Column${i > 1 ? 's' : ''}`;
            if (i.toString() === currentValue) {
                option.selected = true;
            }
            select.appendChild(option);
        }

        // If previously selected value is no longer valid, select first option
        if (!select.value && select.options.length > 0) {
            select.options[0].selected = true;
        }
    } else {
        // If totalCols <= 1, add a disabled default option
        const option = document.createElement('option');
        option.value = '';
        option.disabled = true;
        option.selected = true;
        option.textContent = 'Not applicable';
        select.appendChild(option);
    }
}

/**
 * Toggle visibility of extra seat fields based on selection
 */
function toggleExtraSeats() {
    const count = parseInt(document.getElementById('total_extra_seats').value) || 0;
    const extraSeatRows = document.querySelectorAll('.extra-seat-row');

    extraSeatRows.forEach((row, index) => {
        row.style.display = index < count ? 'block' : 'none';
    });
}

/**
 * Toggle visibility of second entrance field
 */
function toggleEntrance2() {
    const count = parseInt(document.getElementById('total_entrances').value);
    const entrance2Row = document.getElementById('entrance2-row');
    entrance2Row.style.display = count === 2 ? 'block' : 'none';
}

/**
 * Initialize form validation
 */
function initializeFormValidation() {
    const form = document.getElementById('busStructureForm');

    // Validate bus name (3-50 characters)
    const busName = document.getElementById('bus_name');
    busName.addEventListener('blur', function () {
        if (busName.value.length < 3 || busName.value.length > 50) {
            busName.classList.add('input-error');
        } else {
            busName.classList.remove('input-error');
        }
    });

    // Validate total seats (1-999)
    const totalSeats = document.getElementById('bus_total_seat');
    totalSeats.addEventListener('blur', function () {
        const value = parseInt(totalSeats.value) || 0;
        if (value < 1 || value > 999) {
            totalSeats.classList.add('input-error');
        } else {
            totalSeats.classList.remove('input-error');
        }
    });

    // Validate total columns (1-10)
    const totalColumns = document.getElementById('bus_total_col');
    totalColumns.addEventListener('blur', function () {
        const value = parseInt(totalColumns.value) || 0;
        if (value < 1 || value > 10) {
            totalColumns.classList.add('input-error');
        } else {
            totalColumns.classList.remove('input-error');
        }
    });

    // Form submission validation
    form.addEventListener('submit', function (e) {
        let isValid = true;

        // Validate bus name
        if (busName.value.length < 3 || busName.value.length > 50) {
            busName.classList.add('input-error');
            isValid = false;
        }

        // Validate total seats
        const seatsValue = parseInt(totalSeats.value) || 0;
        if (seatsValue < 1 || seatsValue > 999) {
            totalSeats.classList.add('input-error');
            isValid = false;
        }

        // Validate total columns
        const colsValue = parseInt(totalColumns.value) || 0;
        if (colsValue < 1 || colsValue > 10) {
            totalColumns.classList.add('input-error');
            isValid = false;
        }

        // Check if columns can accommodate seats
        if (colsValue > 0 && seatsValue > 0) {
            const rowsNeeded = Math.ceil(seatsValue / colsValue);
            if (rowsNeeded > 100) { // Arbitrary max rows limit
                alert('The combination of seats and columns results in too many rows. Please adjust.');
                isValid = false;
            }
        }

        if (!isValid) {
            e.preventDefault();
            alert('Please correct the errors before submitting.');
        }
    });
}

function toggleExtraSeatFields(extraSeats) {
    for (let i = 1; i <= 3; i++) {
        const select = document.getElementById(`bus_extra_seat_position${i}`);
        if (select) {
            const enabled = i <= extraSeats;
            select.disabled = !enabled;

            if (enabled) {
                select.classList.remove('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');
                select.classList.add('bg-white', 'text-black', 'border-gray-400');
            } else {
                select.classList.add('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');
                select.classList.remove('bg-white', 'text-black', 'border-gray-400');
            }
        }
    }
}

function calculateRowsAndExtraSeats() {
    const totalSeats = parseInt(document.getElementById('bus_total_seat')?.value || 0);
    const totalColumns = parseInt(document.getElementById('bus_total_col')?.value || 0);

    const rowsInput = document.getElementById('bus_total_row');
    const extraSeatsDisplay = document.getElementById('total_extra_seats_display');
    const extraSeatsHidden = document.getElementById('total_extra_seats');

    if (totalSeats > 0 && totalColumns > 0) {
        const totalRows = Math.floor(totalSeats / totalColumns);
        const extraSeats = totalSeats % totalColumns;

        if (extraSeats > 3) {
            alert("Number of extra seats cannot exceed 3.");
            rowsInput.value = '';
            extraSeatsDisplay.value = '';
            extraSeatsHidden.value = '';
            toggleExtraSeatFields(0); // disable all
            return;
        }

        rowsInput.value = totalRows;
        extraSeatsDisplay.value = extraSeats;
        extraSeatsHidden.value = extraSeats;
        toggleExtraSeatFields(extraSeats); // dynamically enable only needed

        console.log(`Total Seats: ${totalSeats}, Columns: ${totalColumns}`);
        console.log(`Calculated Rows: ${totalRows}, Extra Seats: ${extraSeats}`);
    } else {
        rowsInput.value = '';
        extraSeatsDisplay.value = '';
        extraSeatsHidden.value = '';
        toggleExtraSeatFields(0);
    }
}

function toggleEntranceFields() {
    const totalEntrances = parseInt(document.getElementById('total_entrances')?.value || 1);
    const entrance2 = document.getElementById('bus_entrance_position2');

    if (!entrance2) return;

    if (totalEntrances === 2) {
        entrance2.disabled = false;
        entrance2.classList.remove('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');
        entrance2.classList.add('bg-white', 'text-black', 'border-gray-400');
    } else {
        entrance2.disabled = true;
        entrance2.classList.add('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');
        entrance2.classList.remove('bg-white', 'text-black', 'border-gray-400');
    }
}

function clearFormFilters() {
    const form = document.getElementById('busStructureForm');

    // Clear all text/select/radio/etc. manually if needed
    form.reset(); // Basic reset

    // Also clear all select values (especially if using Blade components or JS-enhanced selects)
    const selects = form.querySelectorAll('select');
    selects.forEach(select => {
        select.selectedIndex = 0;
    });

    // Optionally, auto-submit after reset:
    // form.submit();
}
