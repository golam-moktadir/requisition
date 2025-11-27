/**
 * Vehicle History Table Module
 * Standalone JS file version
 * Handles filtering, on-screen pagination, PDF generation, printing, and modal display.
 */
document.addEventListener('DOMContentLoaded', function () {

    // ----- DOM References -----
    const historyTable = document.querySelector('#vehicleHistoryTable');
    const historyTableBody = historyTable?.querySelector('tbody') || null;
    const historyRows = historyTableBody ? Array.from(historyTableBody.querySelectorAll('tr')) : [];
    const historyPaginationControls = document.getElementById('historyPaginationControls');
    const cvContainer = document.querySelector('.cv-container');

    // ----- Staff & Vehicle Info -----
    const vehicleRegNo = cvContainer?.dataset.vehicleRegNo || 'unknown';
    const staffType = cvContainer?.dataset.staffType || 'unknown';
    const staffId = cvContainer?.dataset.staffId || 'unknown';
    const staffName = cvContainer?.dataset.staffName || 'unknown';

    let currentPage = 1;
    const rowsPerPage = 10; // on-screen pagination only
    let currentStatusFilter = 'all'; // 'all' or 'active'

    console.log('[Init] Vehicle Reg No:', vehicleRegNo, 'Staff:', staffName, staffType, staffId);
    console.log('[Init] Total Rows Found:', historyRows.length);

    // ----- Section Title -----
    const sectionTitle = document.querySelector('.vehicle-section-title');
    const updateTitle = () => {
        if (!sectionTitle) return;
        sectionTitle.textContent = `Assigned Vehicle History${currentStatusFilter === 'active' ? ' (Active)' : ''}`;
    };

    // ----- Status Filter -----
    const statusRadios = document.querySelectorAll('input[name="staffStatusFilter"]');
    statusRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            currentStatusFilter = this.value === '1' ? 'active' : 'all';
            currentPage = 1;
            updateTitle();
            filterAndPaginate();
        });
    });

    // ----- Filter Rows (screen) -----
    const getFilteredRows = () => {
        return historyRows.filter(row => {
            const statusText = row.querySelector('td:last-child span')?.textContent.trim().toLowerCase();
            return currentStatusFilter === 'all' || statusText === 'active';
        });
    };

    // ----- Pagination for Screen Only -----
    const filterAndPaginate = () => {
        if (!historyTableBody || !historyPaginationControls) return;

        const filteredRows = getFilteredRows();
        const totalPages = Math.max(1, Math.ceil(filteredRows.length / rowsPerPage));

        // hide all first
        historyRows.forEach(row => row.style.display = 'none');

        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        filteredRows.slice(start, end).forEach((row, i) => {
            row.style.display = '';
            row.querySelector('td:first-child').textContent = start + i + 1;
        });

        // Render Pagination Buttons
        historyPaginationControls.innerHTML = '';
        if (totalPages > 1) {
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = i;
                btn.className = [
                    'px-3 py-1 mx-1 text-sm rounded border transition',
                    i === currentPage ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                ].join(' ');
                btn.addEventListener('click', () => {
                    currentPage = i;
                    filterAndPaginate();
                });
                historyPaginationControls.appendChild(btn);
            }
        }
    };

    // ----- Initialize Table -----
    const checkedRadio = document.querySelector('input[name="staffStatusFilter"]:checked');
    if (checkedRadio) currentStatusFilter = checkedRadio.value === '1' ? 'active' : 'all';
    updateTitle();
    filterAndPaginate();

    // ----- Modal -----
    const modal = document.getElementById('vehicleHistoryModal');
    if (modal) modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });

    // ----- Utility: Preload Images -----
    const preloadImages = (images, callback) => {
        let remaining = images.length;
        if (!remaining) return callback();
        const loaded = () => { if (--remaining === 0) callback(); };
        Array.from(images).forEach(img => {
            if (img.complete) loaded();
            else { img.addEventListener('load', loaded); img.addEventListener('error', loaded); }
        });
    };

    // ----- PDF Filename -----
    const generatePDFFilename = () => `${staffType.toLowerCase()}_cv_${staffId}_${staffName.replace(/\s+/g, '_')}.pdf`;

    // ----- Generate PDF (All Rows by Default, filtered if active) -----
    const generatePDF = (element, opt, loading, clone) => {
        html2pdf()
            .set(opt)
            .from(element)
            .toPdf()
            .get('pdf')
            .then(pdf => {
                const totalPages = pdf.internal.getNumberOfPages();
                for (let i = 1; i <= totalPages; i++) {
                    pdf.setPage(i);
                    pdf.setFontSize(10);
                    pdf.setTextColor(150);
                    pdf.text(`Page ${i} of ${totalPages}`, pdf.internal.pageSize.getWidth() - 30, pdf.internal.pageSize.getHeight() - 10);
                }
            })
            .save()
            .finally(() => { loading?.remove(); clone?.remove(); });
    };

    window.downloadPDF = function () {
        try {
            const element = document.querySelector('.cv-container');
            if (!element) throw new Error('CV container not found');

            const loading = document.createElement('div');
            Object.assign(loading.style, {
                position: 'fixed', top: '0', left: '0', width: '100%', height: '100%',
                backgroundColor: 'rgba(0,0,0,0.7)', display: 'flex',
                justifyContent: 'center', alignItems: 'center',
                zIndex: '9999', color: 'white', fontSize: '1.5rem'
            });
            loading.textContent = 'Generating PDF... Please wait';
            document.body.appendChild(loading);

            const clone = element.cloneNode(true);
            document.body.appendChild(clone);
            Array.from(clone.querySelectorAll('.no-print')).forEach(el => el.remove());

            // --- Use all rows for PDF
            const tableBody = clone.querySelector('#vehicleHistoryTable tbody');
            if (tableBody) {
                tableBody.innerHTML = '';
                historyRows.forEach(row => {
                    const statusText = row.querySelector('td:last-child span')?.textContent.trim().toLowerCase();
                    if (currentStatusFilter === 'active' && statusText !== 'active') return;
                    const clonedRow = row.cloneNode(true);
                    clonedRow.querySelector('td:first-child').textContent = tableBody.children.length + 1;
                    tableBody.appendChild(clonedRow);
                });
            }

            const opt = {
                margin: [3, 4, 3, 4],
                filename: generatePDFFilename(),
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 1.5, scrollY: 0, useCORS: true, allowTaint: true },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak: { mode: ['css', 'legacy'], avoid: ['tr', 'thead', 'tbody', 'table'] }
            };

            preloadImages(clone.querySelectorAll('img'), () => generatePDF(clone, opt, loading, clone));

        } catch (error) {
            console.error('[PDF] Failed:', error);
            alert('Failed to generate PDF. Please try again.');
        }
    };

    // ----- Print CV (All Rows by Default, filtered if active) -----
    window.printCV = function () {
        try {
            const original = document.querySelector('.cv-container');
            if (!original) throw new Error('CV container not found');

            const clone = original.cloneNode(true);
            Object.assign(clone.style, { position: 'static', width: '190mm', margin: '0', padding: '4.25mm', background: 'white' });
            document.body.appendChild(clone);

            Array.from(document.body.children).forEach(el => { if (el !== clone) el.style.display = 'none'; });

            // --- Use all rows for print
            const tableBody = clone.querySelector('#vehicleHistoryTable tbody');
            if (tableBody) {
                tableBody.innerHTML = '';
                historyRows.forEach(row => {
                    const statusText = row.querySelector('td:last-child span')?.textContent.trim().toLowerCase();
                    if (currentStatusFilter === 'active' && statusText !== 'active') return;
                    const clonedRow = row.cloneNode(true);
                    clonedRow.querySelector('td:first-child').textContent = tableBody.children.length + 1;
                    tableBody.appendChild(clonedRow);
                });
            }

            preloadImages(clone.getElementsByTagName('img'), () => setTimeout(() => {
                window.print();
                clone.remove();
                Array.from(document.body.children).forEach(el => el.style.display = '');
            }, 500));

        } catch (error) {
            console.error('[Print] Failed:', error);
            alert('Failed to print CV.');
        }
    };

    // ----- Modal Controls -----
    window.showVehicleHistory = () => {
        if (modal) { modal.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    };
    window.closeModal = () => {
        if (modal) { modal.classList.add('hidden'); document.body.style.overflow = 'auto'; }
    };

});

// ----- Styles -----
const style = document.createElement('style');
style.innerHTML = `
@media print {
    thead{display:table-header-group;}
    tbody{display:table-row-group;}
    tr{page-break-inside:avoid;}
    table{page-break-inside:avoid;}
    #vehicleHistoryTable{width:100%;max-width:200mm;font-size:10px;border-collapse:collapse;margin-top:4mm;}
    #vehicleHistoryTable th,#vehicleHistoryTable td{padding:2mm 1mm;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
}
@media screen{
    .vehicle-history{overflow-x:auto;}
    #vehicleHistoryTable{width:100%;min-width:100%;}
}
`;
document.head.appendChild(style);
