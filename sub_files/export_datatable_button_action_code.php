buttons: [
    {
        extend: 'copy',
        text: 'Copy',
        exportOptions: {
            columns: ':visible' // Export only visible columns
        },
        action: function (e, dt, button, config) {
            // Get Visible Columns
            var visibleColumns = [];
            dt.columns(':visible').every(function () {
                visibleColumns.push(this.header().textContent);
            });
            // Call the original action for Print
            $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, button, config);
        }
    },
    {
        extend: 'excel',
        text: 'Excel',
        filename: 'stock_excel_export',
        exportOptions: {
            columns: ':visible' // Export only visible columns
        },
        action: function (e, dt, button, config) {
            // Get Visible Columns
            var visibleColumns = [];
            dt.columns(':visible').every(function (index) {
                visibleColumns.push(this.header().textContent);
            });
            // Call the original action for Excel export
            $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
        }
    },
    {
        extend: 'pdf',
        text: 'PDF',
        filename: 'pdf_export',
        exportOptions: {
            columns: ':visible'  // Export only visible columns
        },
        action: function (e, dt, button, config) {
            // Get Visible Columns
            var visibleColumns = [];
            dt.columns(':visible').every(function () {
                visibleColumns.push(this.header().textContent);
            });
            // Call the original action for PDF export
            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
        }
    },
    {
        extend: 'print',
        text: 'Print',
        exportOptions: {
            columns: ':visible' // Export only visible columns
        },
        action: function (e, dt, button, config) {
            // Get Visible Columns
            var visibleColumns = [];
            dt.columns(':visible').every(function () {
                visibleColumns.push(this.header().textContent);
            });
            // Call the original action for Print
            $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
        }
    }
]