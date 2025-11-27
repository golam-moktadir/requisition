/**
 * Route Settings + Return Ticket Commission Manager
 * -------------------------------------------------
 * Centralized Debug Logger
 * Real-time or one-time Sync: Main Policy → Subroutes
 * Commission Add/Delete via AJAX
 * Compact JSON submission (avoids max_input_vars overflow)
 * Optimized, safe, and production-ready
 */

document.addEventListener("DOMContentLoaded", () => {
    // =========================================================
    // CENTRAL DEBUG LOGGER
    // =========================================================
    const DEBUG = {
        enabled: true, // turn false for production
        section(name) {
            const c = (label, color, ...args) =>
                this.enabled && console.log(`%c[${name}]`, `color:${color};font-weight:600`, ...args);
            return {
                log: (...a) => c(name, "#0ea5e9", ...a),
                info: (...a) => c(name, "#16a34a", ...a),
                warn: (...a) => c(name, "#eab308", ...a),
                error: (...a) => c(name, "#dc2626", ...a),
            };
        },
    };

    const LOG = {
        boot: DEBUG.section("Boot"),
        form: DEBUG.section("Form"),
        sync: DEBUG.section("Sync"),
        pay: DEBUG.section("Payment"),
        comm: DEBUG.section("Commission"),
    };

    // =========================================================
    // HELPERS
    // =========================================================
    const q = (sel, root = document) => root.querySelector(sel);
    const qa = (sel, root = document) => [...root.querySelectorAll(sel)];
    const csrf = () => q('meta[name="csrf-token"]')?.content || q('input[name="_token"]')?.value || "";

    const form = q("#routeSettingsForm");
    const setAllCheckbox = q("#setAllSubroutes");
    let isSyncEnabled = !!setAllCheckbox?.checked;

    // Fade out toasts
    qa("#toast-success, #toast-error").forEach((t) => {
        setTimeout(() => {
            t.style.transition = "opacity .4s ease";
            t.style.opacity = "0";
            setTimeout(() => t.remove(), 600);
        }, 3500);
    });

    // =========================================================
    // PAYMENT FIELD MANAGER
    // =========================================================
    const PaymentFieldManager = {
        init() {
            this.toggleMainPolicyFields();
            qa("tr.set-values[data-subroute-id]").forEach((r) => this.toggleSubrouteFields(r));
            this.bindEvents();
            LOG.pay.info("PaymentFieldManager initialized");
        },

        bindEvents() {
            // AC/Non-AC enforcement
            ["input[name='all[ac_online]']", "input[name='all[non_ac_online]']"].forEach((sel) =>
                q(sel)?.addEventListener("change", () => {
                    const ac = q("input[name='all[ac_online]']");
                    const non = q("input[name='all[non_ac_online]']");
                    if (!ac?.checked && !non?.checked) {
                        ac.checked = true;
                        this.alert("At least one (AC/Non-AC) must remain selected.");
                    }
                })
            );

            // Pre/Post enforcement
            ["input[name='all[pre_payment]']", "input[name='all[post_payment]']"].forEach((sel) =>
                q(sel)?.addEventListener("change", () => {
                    const pre = q("input[name='all[pre_payment]']");
                    const post = q("input[name='all[post_payment]']");
                    if (!pre?.checked && !post?.checked) {
                        pre.checked = true;
                        this.alert("At least one (Pre/Post Payment) must remain selected.");
                    }
                    this.toggleMainPolicyFields();
                    if (isSyncEnabled) RealTimeSync.applyOnce();
                })
            );

            // Subroute-level validation
            document.addEventListener("change", (e) => {
                const row = e.target.closest("tr.set-values[data-subroute-id]");
                if (!row) return;

                const ac = q('input[name*="[ac_online]"]', row);
                const non = q('input[name*="[non_ac_online]"]', row);
                const pre = q('input[name*="[pre_payment]"]', row);
                const post = q('input[name*="[post_payment]"]', row);

                if (e.target.matches('input[name*="[ac_online]"], input[name*="[non_ac_online]"]')) {
                    if (!ac?.checked && !non?.checked) {
                        ac.checked = true;
                        this.alert("Subroute: At least one (AC/Non-AC) must be selected.");
                    }
                }

                if (e.target.matches('input[name*="[pre_payment]"], input[name*="[post_payment]"]')) {
                    if (!pre?.checked && !post?.checked) {
                        pre.checked = true;
                        this.alert("Subroute: At least one (Pre/Post) must be selected.");
                    }
                    this.toggleSubrouteFields(row);
                }
            });
        },

        toggleMainPolicyFields() {
            this._toggleGroup("pre", q("input[name='all[pre_payment]']")?.checked);
            this._toggleGroup("post", q("input[name='all[post_payment]']")?.checked);
        },

        _toggleGroup(prefix, enabled) {
            [
                `select[name='all[${prefix}_cancel_unit]']`,
                `input[name='all[${prefix}_cancel_value]']`,
                `input[name='all[${prefix}_discount]']`,
                `select[name='all[${prefix}_seats]']`,
                `input[name='all[${prefix}_booking_time]']`,
            ].forEach((sel) => {
                const el = q(sel);
                if (!el) return;
                if (el.tagName === "SELECT") el.disabled = !enabled;
                else el.readOnly = !enabled;
                el.classList.toggle("opacity-60", !enabled);
                el.classList.toggle("bg-gray-100", !enabled);
            });
        },

        toggleSubrouteFields(row) {
            this._toggleRowGroup(row, "pre", q('input[name*="[pre_payment]"]', row)?.checked);
            this._toggleRowGroup(row, "post", q('input[name*="[post_payment]"]', row)?.checked);
        },

        _toggleRowGroup(row, prefix, enabled) {
            [
                `select[name*="[${prefix}_cancel_unit]"]`,
                `input[name*="[${prefix}_cancel_value]"]`,
                `input[name*="[${prefix}_discount]"]`,
                `select[name*="[${prefix}_seats]"]`,
                `input[name*="[${prefix}_booking_time]"]`,
            ].forEach((sel) =>
                qa(sel, row).forEach((el) => {
                    if (el.tagName === "SELECT") el.disabled = !enabled;
                    else el.readOnly = !enabled;
                    el.classList.toggle("opacity-60", !enabled);
                    el.classList.toggle("bg-gray-100", !enabled);
                })
            );
        },

        alert(msg) {
            LOG.pay.warn(msg);
            window.Swal
                ? Swal.fire({ icon: "warning", title: "Validation", text: msg })
                : alert(msg);
        },
    };

    // =========================================================
    // FAST SYNC SYSTEM (Instant + Realtime)
    // =========================================================
    const RealTimeSync = {
        // ---- Apply all main-policy values once ----
        applyOnce() {
            const mainEls = qa("[data-target]");
            if (!mainEls.length) return;
            const subRows = qa("tr.set-values[data-subroute-id]");
            const start = performance.now();

            subRows.forEach((row) => {
                const id = row.dataset.subrouteId;
                mainEls.forEach((el) => {
                    const key = el.dataset.target;
                    const target = q(`[name='subroutes[${id}][${key}]']`, row);
                    if (!target) return;

                    if (el.type === "checkbox") target.checked = el.checked;
                    else if (el.type === "radio" && target.value === el.value)
                        target.checked = el.checked;
                    else target.value = el.value;
                });
            });

            LOG.sync.info(
                `Applied main policy to ${subRows.length} subroutes in ${(performance.now() - start).toFixed(1)} ms`
            );
        },

        // ---- Enable or disable continuous realtime sync ----
        toggleRealtime(enable) {
            document.removeEventListener("input", this._syncHandler);
            document.removeEventListener("change", this._syncHandler);

            if (!enable) {
                LOG.sync.info("Realtime sync disabled.");
                return;
            }

            this._syncHandler = (e) => {
                const el = e.target;
                const key = el.dataset.target;
                if (!key) return;

                qa("tr.set-values[data-subroute-id]").forEach((row) => {
                    const id = row.dataset.subrouteId;
                    const target = q(`[name='subroutes[${id}][${key}]']`, row);
                    if (!target) return;

                    if (el.type === "checkbox") target.checked = el.checked;
                    else if (el.type === "radio" && target.value === el.value)
                        target.checked = el.checked;
                    else target.value = el.value;
                });
            };

            document.addEventListener("input", this._syncHandler);
            document.addEventListener("change", this._syncHandler);
            LOG.sync.info("Realtime sync enabled (Main → Subroutes)");
        },
    };

    // =========================================================
    // SYNC CHECKBOX HANDLER
    // =========================================================
    setAllCheckbox?.addEventListener("change", (e) => {
        isSyncEnabled = e.target.checked;

        if (isSyncEnabled) {
            // First apply all main-policy values
            RealTimeSync.applyOnce();

            // Then enable live updates for future changes
            RealTimeSync.toggleRealtime(true);

            Swal.fire({
                icon: "info",
                title: "Policies Applied",
                html: `
                <p>Main policy values have been applied and synced across all subroutes.</p>
                <hr style="margin:8px 0;">
                <p><b>Next:</b> Press <span style="color:#2563eb;">Submit</span> to store your changes.</p>
            `,
                timer: 5000, // (5 seconds)
                timerProgressBar: true,
                showConfirmButton: false,
            });
        } else {
            RealTimeSync.toggleRealtime(false);
            Swal.fire({
                icon: "info",
                title: "Individual Editing Enabled",
                html: `
                <p>Sync has been turned off. You can now edit subroutes individually.</p>
                <hr style="margin:8px 0;">
                <p><b>Remember:</b> Press <span style="color:#2563eb;">Submit</span> to save your changes.</p>
            `,
                timer: 5000, // (5 seconds)
                timerProgressBar: true,
                showConfirmButton: false,
            });
        }
    });

    // =========================================================
    // COMMISSION MANAGER
    // =========================================================
    const CommissionManager = {
        init() {
            document.addEventListener("click", this._handleClick.bind(this));
            LOG.comm.info("CommissionManager ready");
        },

        _handleClick(e) {
            const add = e.target.closest(".btn-add-commission");
            const del = e.target.closest(".delete-commission-btn");
            const toggleAdd = e.target.closest(".toggle-row-btn");
            const toggleClose = e.target.closest(".close-btn");
            if (add) return this._add(add);
            if (del) return this._delete(del);
            if (toggleAdd) return this._toggleRow(toggleAdd, true);
            if (toggleClose) return this._toggleRow(toggleClose, false);
        },

        _toggleRow(button, show) {
            const id = button.dataset.subrouteId;
            const row = q(`#exception-row-${id}`);
            const closeBtn = q(`#close-btn-${id}`);
            const addBtn = q(`.toggle-row-btn[data-subroute-id="${id}"]`);
            if (!row || !closeBtn || !addBtn) return;
            row.classList.toggle("hidden", !show);
            addBtn.classList.toggle("hidden", show);
            closeBtn.classList.toggle("hidden", !show);
        },

        async _add(btn) {
            const id = btn.dataset.subrouteId || "0";
            const name =
                btn.dataset.subrouteName ||
                q(`#exception-row-${id} input[name='subroute_name']`)?.value ||
                "All Subroutes";

            const pick = (b) => q(`#${b}_${id}`) || q(`#${b}`);
            const payload = {
                transport_id: q("input[name='transport_id']")?.value || "",
                subroute_id: id,
                subroute_name: name,
                return_commission_time: (pick("txtTime")?.value || "").trim(),
                time_format: (pick("selectTimeFormat")?.value || "").trim(),
                return_commission_amount: (pick("txtAmount")?.value || "").trim(),
                return_commission_amount_type:
                    q(`input[name='amount_type_${id}']:checked`)?.value ?? "0",
            };

            LOG.comm.log("Outgoing Commission Payload", payload);
            if (!payload.return_commission_time || !payload.return_commission_amount)
                return this._alert("Missing Fields", "Please enter both time and amount.", "warning");

            const fd = new FormData();
            fd.append("payload_json", JSON.stringify(payload));
            fd.append("_token", csrf());

            const loader = this._loader("Saving commission...");
            try {
                const res = await fetch(window.routes?.commissionsStore, {
                    method: "POST",
                    body: fd,
                    headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" },
                });
                const json = await res.json().catch(() => null);
                loader.remove();
                if (!res.ok || json?.success === false)
                    return this._alert("Error", json?.message || "Failed to add commission.", "error");

                Swal.fire({
                    icon: "success",
                    title: "Saved",
                    text: json.message,
                    timer: 1200,
                    showConfirmButton: false,
                });
                setTimeout(() => location.reload(), 800);
            } catch (err) {
                loader.remove();
                this._alert("Network Error", err.message || "Please try again.", "error");
            }
        },

        async _delete(btn) {
            const id = btn.dataset.id || btn.dataset.commissionId;
            if (!id) return;
            const confirm = await Swal.fire({
                title: "Delete Commission?",
                text: "This cannot be undone.",
                icon: "warning",
                showCancelButton: true,
            });
            if (!confirm.isConfirmed) return;
            const url = window.routes?.commissionsDestroy?.replace(":id", id);
            if (!url) return this._alert("Error", "Delete URL not configured.", "error");
            const res = await fetch(url, {
                method: "DELETE",
                headers: { "X-CSRF-TOKEN": csrf(), "X-Requested-With": "XMLHttpRequest", Accept: "application/json" },
            });
            if (res.ok) {
                Swal.fire({ icon: "success", title: "Deleted", timer: 900, showConfirmButton: false });
                setTimeout(() => location.reload(), 600);
            } else this._alert("Error", "Failed to delete commission.", "error");
        },

        _loader(text) {
            const el = document.createElement("div");
            el.textContent = text;
            Object.assign(el.style, {
                position: "fixed",
                top: "10px",
                right: "10px",
                background: "#007bff",
                color: "#fff",
                padding: "6px 12px",
                borderRadius: "4px",
                zIndex: 9999,
                fontSize: "12px",
                boxShadow: "0 2px 6px rgba(0,0,0,.15)",
            });
            document.body.appendChild(el);
            return { remove: () => el.remove() };
        },

        _alert(title, text, icon) {
            Swal.fire({ title, text, icon });
        },
    };

    // =========================================================
    // FORM SUBMITTER (Compact JSON)
    // =========================================================
    const FormSubmit = {
        init() {
            this.bindSanitize();
            this.bindSubmit();
            LOG.form.info("FormSubmit ready");
        },

        bindSanitize() {
            document.addEventListener("input", (e) => {
                const t = e.target;
                if (!(t instanceof HTMLInputElement)) return;
                if (/\[(pre|post)_(cancel_value|discount|booking_time)\]$/.test(t.name))
                    t.value = t.value.replace(/[^0-9]/g, "").slice(0, t.maxLength || 10);
            });
        },

        bindSubmit() {
            form?.addEventListener("submit", (e) => {
                e.preventDefault();

                const payload = {
                    transport_id: q("input[name='transport_id']")?.value || null,
                    all: {},
                    subroutes: [],
                    setAllSubroutes: setAllCheckbox?.checked || false,
                };

                qa("[name^='all[']").forEach((el) => {
                    const key = el.name.match(/\[([^\]]+)\]/)?.[1];
                    if (key) payload.all[key] = el.type === "checkbox" ? (el.checked ? 1 : 0) : el.value;
                });

                qa("tr.set-values[data-subroute-id]").forEach((row) => {
                    const id = row.dataset.subrouteId;
                    const obj = { subroute_id: id };
                    qa(`[name^='subroutes[${id}]']`, row).forEach((input) => {
                        const key = input.name.match(/\[([^\]]+)\]$/)?.[1];
                        if (key) obj[key] = input.type === "checkbox" ? (input.checked ? 1 : 0) : input.value;
                    });
                    payload.subroutes.push(obj);
                });

                LOG.form.info("Submitting compact JSON payload", payload);

                const fd = new FormData();
                fd.append("_token", csrf());
                fd.append("payload_json", JSON.stringify(payload));

                const loader = CommissionManager._loader("Saving policies...");
                fetch(form.action, {
                    method: "POST",
                    body: fd,
                    headers: { "X-Requested-With": "XMLHttpRequest", Accept: "application/json" },
                })
                    .then((r) => r.json())
                    .then((json) => {
                        loader.remove();
                        if (!json?.success)
                            return this.alert("Error", json?.message || "Failed to save policies.");
                        Swal.fire({ icon: "success", title: "Saved", text: json.message, timer: 1200, showConfirmButton: false });
                        if (json.redirect)
                            setTimeout(() => (window.location.href = json.redirect), 800);
                    })
                    .catch((err) => {
                        loader.remove();
                        this.alert("Network Error", err.message || "Please try again.");
                    });
            });
        },

        alert(title, text) {
            Swal.fire({ icon: "error", title, text });
        },
    };

    // =========================================================
    // INITIALIZATION
    // =========================================================
    PaymentFieldManager.init();
    CommissionManager.init();
    FormSubmit.init();

    LOG.boot.info("All systems initialized successfully");
});
