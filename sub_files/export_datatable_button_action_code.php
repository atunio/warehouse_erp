buttons: [
    {
        extend: 'copy',
        text: 'Copy',
        exportOptions: {
            columns: ':visible', // Export only visible columns
            format: {
                body: function (data, row, column, node) {
                    // Extract text content from the cell, ignoring HTML tags
                    var tempDiv = $('<div>').html(data);
                    var textContent = tempDiv.text();

                    // Remove all white spaces and new lines
                    textContent = textContent.replace(/\s+/g, ' ').trim(); // Replace multiple spaces with a single space
                    textContent = textContent.replace(/[\r\n]+/g, ' '); // Remove new lines

                    return textContent; // Return cleaned text content
                }
            }
        },
        action: function (e, dt, button, config) {
            // Prevent the default copy action
            e.preventDefault();

            // Get the table data
            var visibleColumns = dt.columns(':visible').indexes();
            var data = dt.rows({ search: 'applied' }).data();

            // Build the TSV (tab-separated values) string
            var tsvData = '';

            // Add headers
            visibleColumns.each(function (index) {
                var headerText = dt.column(index).header().textContent;
                headerText = headerText.replace(/\s+/g, ' ').trim(); // Clean header text
                headerText = headerText.replace(/[\r\n]+/g, ' '); // Remove new lines
                tsvData += headerText + '\t';
            });
            tsvData = tsvData.trim() + '\n'; // Remove trailing tab and add newline

            // Add rows
            data.each(function (row) {
                visibleColumns.each(function (index) {
                    var cellData = row[index];
                    var tempDiv = $('<div>').html(cellData);
                    var textContent = tempDiv.text();

                    // Remove all white spaces and new lines
                    textContent = textContent.replace(/\s+/g, ' ').trim(); // Replace multiple spaces with a single space
                    textContent = textContent.replace(/[\r\n]+/g, ' '); // Remove new lines

                    tsvData += (textContent || '') + '\t'; // Add tab-separated cell data (use empty string if cell is empty)
                });
                tsvData = tsvData.trim() + '\n'; // Remove trailing tab and add newline
            });

            // Copy the TSV data to the clipboard
            navigator.clipboard.writeText(tsvData).then(function () {
                console.log('Data copied to clipboard:', tsvData); // Debugging

                // Get the number of rows copied
                var rowCount = dt.rows({ search: 'applied' }).count();

                // Show DataTables-style notification
                dt.buttons.info(
                    dt.i18n('buttons.copyTitle', 'Copy to clipboard'), // Title
                    dt.i18n('buttons.copyInfo', {
                        _: 'Copied %d rows to clipboard', // Message with row count
                        1: 'Copied 1 row to clipboard' // Singular form
                    }, rowCount), // Replace %d with rowCount
                    2000 // Display duration in milliseconds (2 seconds)
                );
            }).catch(function (error) {
                console.error('Failed to copy data:', error);
            });
        }
    },
    {
        extend: 'excel',
        text: 'Excel',
        filename: 'export_data_excel_<?php echo date("YmdHis")?>',
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