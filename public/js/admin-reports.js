// Shared helper for admin report pages: initializes a DataTable in AJAX mode,
// reloads it (without a page navigation) whenever a filter input changes, and
// wires an "Export CSV" button to open the export endpoint with the same
// filters applied as a query string.
function initReportTable(opts) {
    const filterIds = opts.filterIds || [];

    function collectFilters() {
        const data = {};
        filterIds.forEach(function (id) {
            const el = document.getElementById(id);
            if (el) data[id] = el.value;
        });
        return data;
    }

    const table = $(opts.tableSelector).DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: opts.ajaxUrl,
            data: function (d) {
                Object.assign(d, collectFilters());
            },
            dataSrc: 'data',
        },
        columns: opts.columns,
        order: opts.order || [],
        responsive: true,
        autoWidth: false,
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],
        language: {
            search: '',
            searchPlaceholder: 'Search results...',
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...',
            info: 'Showing _START_ to _END_ of _TOTAL_ entries',
            emptyTable: opts.emptyMessage || 'No records found for the selected filters.',
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last: '<i class="fas fa-angle-double-right"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>',
            },
        },
        columnDefs: opts.columnDefs || [],
    });

    let debounceTimer;
    filterIds.forEach(function (id) {
        const el = document.getElementById(id);
        if (!el) return;
        const isTextInput = el.tagName === 'INPUT' && (el.type === 'text' || el.type === 'number' || el.type === 'date');
        el.addEventListener(isTextInput ? 'input' : 'change', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () { table.ajax.reload(); }, isTextInput ? 450 : 0);
        });
    });

    const resetBtn = document.getElementById(opts.resetBtnId || 'resetFiltersBtn');
    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            filterIds.forEach(function (id) {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            table.ajax.reload();
        });
    }

    const exportBtn = document.getElementById(opts.exportBtnId || 'exportCsvBtn');
    if (exportBtn && opts.exportUrl) {
        exportBtn.addEventListener('click', function () {
            const params = new URLSearchParams(collectFilters()).toString();
            window.location.href = opts.exportUrl + (params ? '?' + params : '');
        });
    }

    return table;
}
