// public/assets/js/features/departure-setup.js
document.addEventListener('DOMContentLoaded', () => {
    // ----------------------------- CONFIG -----------------------------
    const DEBUG = true;
    const TOAST_TIME = 4000;
    const LOG_BUFFER_MAX = 40;   // interactions before auto-flush
    const LOG_FLUSH_MS = 800;    // flush cadence

    // ----------------------------- LOGGER -----------------------------
    const log = {
        d: (msg, data) => DEBUG && console.debug(`%c[DEPARTURE-EDIT] ${ts()} ${msg}`, 'color:#1976d2', data ?? ''),
        i: (msg, data) => DEBUG && console.info(`%c[DEPARTURE-EDIT] ${ts()} ${msg}`, 'color:#2e7d32', data ?? ''),
        w: (msg, data) => DEBUG && console.warn(`%c[DEPARTURE-EDIT] ${ts()} ${msg}`, 'color:#f57c00', data ?? ''),
        e: (msg, err) => DEBUG && console.error(`%c[DEPARTURE-EDIT] ${ts()} ${msg}`, 'color:#d32f2f', err ?? ''),
        t: (label, rows) => DEBUG && Array.isArray(rows) && rows.length && console.table(rows)
    };
    const ts = () => new Date().toISOString().slice(11, 23);

    // Buffered interaction log
    const dbg = {
        enabled: DEBUG,
        buffer: [],
        timer: null,
        push(row) {
            if (!this.enabled) return;
            this.buffer.push({ t: ts(), ...row });
            if (this.buffer.length >= LOG_BUFFER_MAX) this.flush(true);
            if (!this.timer) this.timer = setTimeout(() => this.flush(), LOG_FLUSH_MS);
        },
        flush(force = false) {
            if (!this.enabled) return;
            if (!this.buffer.length && !force) return;
            const out = this.buffer.splice(0);
            console.groupCollapsed(`%c[DEPARTURE-EDIT] ${ts()} Interactions x${out.length}`, 'color:#757575');
            console.table(out);
            console.groupEnd();
            clearTimeout(this.timer); this.timer = null;
        }
    };
    window.DBG = {
        enable() { dbg.enabled = true; },
        disable() { dbg.enabled = false; },
        flush() { dbg.flush(true); }
    };

    // ----------------------------- ELEMENTS -----------------------------
    const form = document.getElementById('departure-form');
    if (!form) { log.e('Form element not found'); return; }

    const enableCityValidation = document.getElementById('enable_city_validation');
    const resetBtn = document.getElementById('reset-button');

    // feedback containers (top of form)
    const errorBox = ensureBox('form-errors', 'bg-red-100 text-red-700');
    const successBox = ensureBox('form-success', 'bg-green-100 text-green-700');

    function ensureBox(id, classes) {
        let box = document.getElementById(id);
        if (!box) {
            box = document.createElement('div');
            box.id = id;
            box.className = `${classes} p-2 rounded-md mb-2 hidden`;
            form.prepend(box);
        }
        return box;
    }
    const showFeedback = (isError, html) => {
        const show = isError ? errorBox : successBox;
        const hide = isError ? successBox : errorBox;
        show.innerHTML = html;
        show.classList.remove('hidden');
        hide.classList.add('hidden');
    };
    const clearFeedback = () => { errorBox.classList.add('hidden'); successBox.classList.add('hidden'); };

    // ----------------------------- HELPERS -----------------------------
    const $ = (sel, root = document) => root.querySelector(sel);
    const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));
    const citySections = () => $$('.city-grid-section');

    // Normalize class checks: support both underscores and dashes
    const isBusCheckbox = el =>
        el.classList.contains('ac-bus-checkbox') ||
        el.classList.contains('non_ac-bus-checkbox') ||
        /(^|\s)(ac|non[-_]ac)-bus-checkbox(\s|$)/.test(el.className);

    const isDayCheckbox = el =>
        el.classList.contains('ac-day-checkbox') ||
        el.classList.contains('non_ac-day-checkbox') ||
        /(^|\s)(ac|non[-_]ac)-day-checkbox(\s|$)/.test(el.className);

    const boothCheckboxCls = 'booth-checkbox';

    // Persist initial state for a true reset
    function storeInitialState() {
        Array.from(form.elements).forEach(el => {
            el.dataset.initialState = JSON.stringify({
                value: el.value,
                checked: el.checked,
                disabled: el.disabled
            });
        });
        log.d('Initial state stored');
    }

    function resetForm() {
        try {
            clearFeedback();
            form.style.opacity = '0.7';
            Array.from(form.elements).forEach(el => {
                try {
                    const s = JSON.parse(el.dataset.initialState || '{}');
                    if (el.type === 'checkbox' || el.type === 'radio') el.checked = !!s.checked;
                    else el.value = s.value ?? '';
                    el.disabled = !!s.disabled;
                } catch { }
            });
            syncAllDependentInputs();
            setTimeout(() => { form.style.opacity = '1'; }, 250);
            log.i('Form reset complete');
            dbg.push({ kind: 'reset', field: '#reset-button', value: true });
        } catch (e) {
            log.e('Reset failed', e);
        }
    }

    // ----------------------------- DATEPICKERS -----------------------------
    (function initDatePickers() {
        try {
            if (typeof flatpickr === 'undefined') throw new Error('Flatpickr not loaded');
            flatpickr('.flatpickr', {
                dateFormat: 'Y-m-d',
                allowInput: true,
                defaultDate: (el) => el.value || null,
                onChange: (_, dateStr, inst) => {
                    const id = inst.element?.id || 'unknown';
                    log.i(`Date changed: ${id}`, { newValue: dateStr });
                    dbg.push({ kind: 'date', field: '#' + id, value: dateStr, ...contextFor(inst.element) });
                }
            });
            log.i('Date pickers initialized');
        } catch (e) {
            log.e('Datepicker init failed', e);
            showFeedback(true, '<strong>Date picker failed to load.</strong> Please refresh the page.');
        }
    })();

    // ----------------------------- VALIDATION -----------------------------
    function validateCityType(section, cityId, type) {
        const dateInputId = type === 'non_ac' ? 'non_ac_date' : 'ac_date';
        const dateInput = document.getElementById(dateInputId);

        // Only AC date is considered; NON_AC is always cleared and ignored
        if (type === 'non_ac' && dateInput) {
            dateInput.value = ''; // visually blank out the NON_AC date field
        }

        const isDateSet = type === 'ac' && !!dateInput?.value?.trim();

        const cityName = section.querySelector('.departure-header-cell')?.textContent.trim() || 'Unknown';
        const errorId = `${type}_error_${cityId}`;
        let errorEl = document.getElementById(errorId);
        if (!errorEl) {
            errorEl = document.createElement('div');
            errorEl.id = errorId;
            errorEl.className = 'city-error text-red-600 text-sm mb-2 hidden';
            section.prepend(errorEl);
        }

        let errors = [];
        let isValid = true;

        if (isDateSet && enableCityValidation?.checked) {
            // buses
            const busSel = `input[name^="bus_type[${type}][${cityId}]"]:checked`;
            const hasBus = section.querySelectorAll(busSel).length > 0;

            // weekdays (except "all")
            const daySel = `input[name^="bus_days[${type}][${cityId}]"]:not([value="all"]):checked`;
            const hasDay = section.querySelectorAll(daySel).length > 0;

            // booths
            const boothSel = `input[name^="booth[${type}][${cityId}]"]:checked`;
            const hasBooth = section.querySelectorAll(boothSel).length > 0;

            if (!hasBus) errors.push('select at least one bus');
            if (!hasDay) errors.push('select at least one weekday');
            if (!hasBooth) errors.push('select at least one booth');

            isValid = hasBus && hasDay && hasBooth;
        }

        if (!isValid) {
            errorEl.innerHTML = `<strong>${cityName} (${type.toUpperCase()}):</strong> ${errors.join(', ')}`;
            errorEl.classList.remove('hidden');
        } else {
            errorEl.classList.add('hidden');
        }

        return { isValid, errors, type, cityId, cityName };
    }

    function validateAllCities() {
        const out = [];
        for (const section of citySections()) {
            const cityId = section.querySelector('input[name="cities[]"]')?.value;
            if (!cityId) continue;
            out.push(validateCityType(section, cityId, 'non_ac'));
            out.push(validateCityType(section, cityId, 'ac'));
        }
        const bad = out.filter(x => !x.isValid);
        if (bad.length) {
            log.w('Validation failed (per city/type)');
            log.t('Invalid city/type rows', bad.map(b => ({
                City: b.cityName,
                Type: b.type.toUpperCase(),
                Issues: b.errors.join('; ')
            })));
        }
        return { isValid: bad.length === 0, details: out };
    }

    // Inline field-level validation for numeric bus value inputs (0–99)
    function validateBusValueInput(input) {
        input.value = input.value.replace(/[^\d]/g, '');
        if (input.value.length > 2) input.value = input.value.slice(0, 2);
        const row = input.closest('.ac-row');
        const cb = row?.querySelector('input[type="checkbox"]');
        const needsValue = cb?.checked;
        input.classList.toggle('ring-2', needsValue && !input.value);
        input.classList.toggle('ring-red-500', needsValue && !input.value);
    }

    // ----------------------------- DEPENDENCIES SYNC -----------------------------
    function syncBusRow(row) {
        const cb = row?.querySelector('input[type="checkbox"]');
        const input = row?.querySelector('.ac-value-input');
        if (!cb || !input) return;
        const isChecked = cb.checked;
        input.disabled = !isChecked;
        input.classList.toggle('bg-gray-200', !isChecked);
        if (isChecked && (!input.value || input.value === '0')) input.value = '1';
        validateBusValueInput(input);
    }

    function syncBoothGroup(cb) {
        const selects = document.querySelectorAll(`[data-group="${cb.id}"] select`);
        selects.forEach(sel => {
            sel.disabled = !cb.checked;
            if (cb.checked && !sel.value) sel.value = '00';
        });
    }

    function syncWeekdayAll(container) {
        const allBox = container.querySelector('input[value="all"]');
        if (!allBox) return;
        const items = container.querySelectorAll('input[type="checkbox"]:not([value="all"])');
        const allChecked = Array.from(items).every(cb => cb.checked || cb.disabled);
        allBox.checked = allChecked;
    }

    function syncAllDependentInputs() {
        $$('.ac-row').forEach(syncBusRow);
        $$('.' + boothCheckboxCls).forEach(syncBoothGroup);
        $$('.weekday-grid').forEach(syncWeekdayAll);
    }

    // ----------------------------- INTERACTION CONTEXT -----------------------------
    function contextFor(el) {
        const section = el.closest('.city-grid-section');
        const cityId = section?.querySelector('input[name="cities[]"]')?.value ?? null;
        const city = section?.querySelector('.departure-header-cell')?.textContent?.trim() ?? null;

        let type = null;
        const n = el.name || '';
        if (n.includes('[non_ac]') || el.classList.contains('non_ac-day-checkbox') || el.classList.contains('non_ac-bus-checkbox')) type = 'non_ac';
        else if (n.includes('[ac]') || el.classList.contains('ac-day-checkbox') || el.classList.contains('ac-bus-checkbox')) type = 'ac';

        return { cityId, city, type };
    }

    function fieldId(el) {
        if (el.id) return '#' + el.id;
        if (el.name) return el.name;
        return el.tagName.toLowerCase() + '.' + (el.className || '').split(/\s+/).filter(Boolean).join('.');
    }

    function sectionSummary(section) {
        if (!section) return null;
        const cityId = section.querySelector('input[name="cities[]"]')?.value;
        const city = section.querySelector('.departure-header-cell')?.textContent?.trim();
        const c = (sel) => section.querySelectorAll(sel).length;
        const busesNonAc = c(`input[name^="bus_type[non_ac][${cityId}]"]:checked`);
        const busesAc = c(`input[name^="bus_type[ac][${cityId}]"]:checked`);
        const daysNonAc = c(`input[name^="bus_days[non_ac][${cityId}]"]:not([value="all"]):checked`);
        const daysAc = c(`input[name^="bus_days[ac][${cityId}]"]:not([value="all"]):checked`);
        const boothsNon = c(`input[name^="booth[non_ac][${cityId}]"]:checked`);
        const boothsAc = c(`input[name^="booth[ac][${cityId}]"]:checked`);
        return { city, busesNonAc, busesAc, daysNonAc, daysAc, boothsNon, boothsAc };
    }

    // ----------------------------- EVENTS -----------------------------
    form.addEventListener('change', (e) => {
        const t = e.target;

        // keep toggle value synced (1/0)
        if (t === enableCityValidation) t.value = t.checked ? '1' : '0';

        // bus checkbox toggles numeric input
        if (isBusCheckbox(t)) syncBusRow(t.closest('.ac-row'));

        // booth checkbox toggles time selects
        if (t.classList.contains(boothCheckboxCls)) syncBoothGroup(t);

        // weekday "all" checkbox
        if (isDayCheckbox(t)) {
            const grid = t.closest('.weekday-grid');
            if (grid) {
                if (t.value === 'all') {
                    grid.querySelectorAll('input[type="checkbox"]:not([value="all"])').forEach(cb => { if (!cb.disabled) cb.checked = t.checked; });
                } else {
                    syncWeekdayAll(grid);
                }
            }
        }

        // numeric bus value
        if (t.classList.contains('ac-value-input')) validateBusValueInput(t);

        // live validation
        const section = t.closest('.city-grid-section');
        const cityId = section?.querySelector('input[name="cities[]"]')?.value;
        if (cityId) {
            const non = validateCityType(section, cityId, 'non_ac');
            const ac = validateCityType(section, cityId, 'ac');
            // interaction + summary logs
            dbg.push({ kind: 'change', field: fieldId(t), value: (t.type === 'checkbox' ? t.checked : t.value), ...contextFor(t) });
            const sum = sectionSummary(section);
            if (sum) { console.groupCollapsed(`[DEPARTURE-EDIT] ${ts()} City summary: ${sum.city}`); console.table([sum]); console.groupEnd(); }
        } else {
            dbg.push({ kind: 'change', field: fieldId(t), value: (t.type === 'checkbox' ? t.checked : t.value) });
        }
    });

    form.addEventListener('input', (e) => {
        const t = e.target;
        if (t.classList.contains('ac-value-input')) validateBusValueInput(t);
        dbg.push({ kind: 'input', field: fieldId(t), value: t.value, ...contextFor(t) });
    });

    form.addEventListener('click', (e) => {
        const el = e.target;
        if (['INPUT', 'SELECT', 'BUTTON', 'LABEL'].includes(el.tagName)) {
            dbg.push({ kind: 'click', field: fieldId(el), value: (el.type === 'checkbox' ? el.checked : el.value), ...contextFor(el) });
        }
    });

    resetBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        resetForm();
    });

    form.addEventListener('submit', (e) => {
        clearFeedback();
        const { isValid, details } = validateAllCities();
        if (!isValid) {
            e.preventDefault();
            const issues = details
                .filter(d => !d.isValid)
                .map(d => `<li><strong>${d.cityName} (${d.type.toUpperCase()})</strong>: ${d.errors.join(', ')}</li>`)
                .join('');
            showFeedback(true, `<ul class="list-disc pl-5">${issues}</ul>`);
            const firstErr = $('.city-error:not(.hidden)');
            firstErr?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                const orig = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';
                setTimeout(() => { btn.disabled = false; btn.innerHTML = orig; }, 6000);
            }
        }
        dbg.push({ kind: 'submit', field: '#departure-form', value: isValid });
        dbg.flush(true);
    });

    // ----------------------------- INIT -----------------------------
    syncAllDependentInputs();
    storeInitialState();
    log.i('Departure-edit JS initialized');
});
