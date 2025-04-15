<script language="JavaScript">
    $(document).ready(function() {
        function getHighlightedOption(identify) {
            let highlighted = $('.select2-results__option--highlighted');
            if (highlighted.length) {
                var complete_id = highlighted.attr('id');
                // console.log("complete_id:", complete_id);
                let regex = /^select2-[\w-]+-result-(.+)$/;
                let match = complete_id.match(regex);
                let firstBreakIndex = match[1].indexOf('-'); // Find first occurrence of '-'
                var id = firstBreakIndex !== -1 ? match[1].substring(firstBreakIndex + 1) : match[1];
                $(document).on('keydown', function(e) {
                    if (e.key === 'Tab') {

                        $(identify).val(id).trigger('change');
                    }
                });
            }
        }
        // Detect changes using arrow keys, Enter, or Tab
        $(document).on('keydown', function(e) {
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp' || e.key === 'Enter' || e.key === 'Tab') {
                setTimeout(getHighlightedOption, 50); // Delay to allow UI update
            }
        });
        // Detect when user hovers over an option
        $(document).on('mouseenter', '.select2-results__option', function() {
            let openSelectId = $('.select2-container--open').prev('select').attr('id');
            console.log("openSelectId:", openSelectId);
            getHighlightedOption('#' + openSelectId);
        });



        $("input").keyup(function() {
            var max = parseFloat($(this).attr('max'));
            var min = parseFloat($(this).attr('min'));
            if ($(this).val() > max) {
                $(this).val(max);
            } else if ($(this).val() < min) {
                $(this).val(min);
            }
        });

        function handleButtonAction(originalAction, e, dt, button, config) {
            // Get Visible Columns
            var visibleColumns = [];
            dt.columns(':visible').every(function() {
                visibleColumns.push(this.header().textContent);
            });

            // Call the original action for the button
            originalAction.call(this, e, dt, button, config);
        }

        buttons: [{
                extend: 'copy',
                text: 'Copy',
                exportOptions: {
                    columns: ':visible' // Export only visible columns
                },
                action: function(e, dt, button, config) {
                    handleButtonAction($.fn.dataTable.ext.buttons.copyHtml5.action, e, dt, button, config);
                }
            },
            {
                extend: 'excel',
                text: 'Excel',
                filename: 'stock_excel_export',
                exportOptions: {
                    columns: ':visible' // Export only visible columns
                },
                action: function(e, dt, button, config) {
                    handleButtonAction($.fn.dataTable.ext.buttons.excelHtml5.action, e, dt, button, config);
                }
            },
            {
                extend: 'pdf',
                text: 'PDF',
                filename: 'pdf_export',
                exportOptions: {
                    columns: ':visible' // Export only visible columns
                },
                action: function(e, dt, button, config) {
                    handleButtonAction($.fn.dataTable.ext.buttons.pdfHtml5.action, e, dt, button, config);
                }
            },
            {
                extend: 'print',
                text: 'Print',
                exportOptions: {
                    columns: ':visible' // Export only visible columns
                },
                action: function(e, dt, button, config) {
                    handleButtonAction($.fn.dataTable.ext.buttons.print.action, e, dt, button, config);
                }
            }
        ]

        $('table.display2').DataTable({
            "responsive": true,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: '<"top"B><"clear"><"top"l><"clear">frt<"bottom"ip><"clear"><br>', // Add buttons to the DOM
            <?php include('sub_files/export_datatable_button_action_code.php') ?>
        });
        $('table.display3').DataTable({
            "responsive": true,
            "lengthMenu": [
                [100, 150, 200, -1],
                [100, 150, 200, "All"]
            ],
            dom: '<"top"B><"clear"><"top"l><"clear">frt<"bottom"ip><"clear"><br>', // Add buttons to the DOM
            <?php include('sub_files/export_datatable_button_action_code.php') ?>
        });
        $("table.pagelength50").dataTable().fnDestroy();
        $("table.pagelength100").dataTable().fnDestroy();
        $('table.pagelength100').DataTable({
            "responsive": true,
            "lengthMenu": [
                [100, 150, 200, -1],
                [100, 150, 200, "All"]
            ],
            dom: '<"top"B><"clear"><"top"l><"clear">frt<"bottom"ip><"clear"><br>', // Add buttons to the DOM
            <?php include('sub_files/export_datatable_button_action_code.php') ?>
        });
        $("table.pagelength50").dataTable().fnDestroy();

        $('table.pagelength50').DataTable({
            "responsive": true,
            "lengthMenu": [
                [50, 100, 200, -1],
                [50, 100, 200, "All"]
            ],
            dom: '<"top"B><"clear"><"top"l><"clear">frt<"bottom"ip><"clear"><br>', // Add buttons to the DOM
            <?php include('sub_files/export_datatable_button_action_code.php') ?>
        });

        $("table.pagelength50_2").dataTable().fnDestroy();
        $('table.pagelength50_2').DataTable({
            "responsive": true,
            "lengthMenu": [
                [50, 100, 200, -1],
                [50, 100, 200, "All"]
            ],
            dom: '<"top"B><"clear"><"top"l><"clear">frt<"bottom"ip><"clear"><br>', // Add buttons to the DOM
            <?php include('sub_files/export_datatable_button_action_code.php') ?>
        });
        $("table.pagelength50_3").dataTable().fnDestroy();
        $('table.pagelength50_3').DataTable({
            "responsive": true,
            "lengthMenu": [
                [50, 100, 200, -1],
                [50, 100, 200, "All"]
            ],
            dom: '<"top"B><"clear"><"top"l><"clear">frt<"bottom"ip><"clear"><br>', // Add buttons to the DOM
            <?php include('sub_files/export_datatable_button_action_code.php') ?>
        });
        $("table.pagelength50_4").dataTable().fnDestroy();
        $('table.pagelength50_4').DataTable({
            "responsive": true,
            "lengthMenu": [
                [50, 100, 200, -1],
                [50, 100, 200, "All"]
            ],
            dom: '<"top"B><"clear"><"top"l><"clear">frt<"bottom"ip><"clear"><br>', // Add buttons to the DOM
            <?php include('sub_files/export_datatable_button_action_code.php') ?>
        });
        $("table.pagelength50_5").dataTable().fnDestroy();
        $('table.pagelength50_5').DataTable({
            "responsive": true,
            "lengthMenu": [
                [50, 100, 200, -1],
                [50, 100, 200, "All"]
            ],
            dom: '<"top"B><"clear"><"top"l><"clear">frt<"bottom"ip><"clear"><br>', // Add buttons to the DOM
            <?php include('sub_files/export_datatable_button_action_code.php') ?>
        });
        $("table.pagelength50_6").dataTable().fnDestroy();
        $('table.pagelength50_6').DataTable({
            "responsive": true,
            "lengthMenu": [
                [50, 100, 200, -1],
                [50, 100, 200, "All"]
            ],
            dom: '<"top"B><"clear"><"top"l><"clear">frt<"bottom"ip><"clear"><br>', // Add buttons to the DOM
            <?php include('sub_files/export_datatable_button_action_code.php') ?>
        });
        $("table.pagelength50_7").dataTable().fnDestroy();
        $('table.pagelength50_7').DataTable({
            "responsive": true,
            "lengthMenu": [
                [50, 100, 200, -1],
                [50, 100, 200, "All"]
            ],
            dom: '<"top"B><"clear"><"top"l><"clear">frt<"bottom"ip><"clear"><br>', // Add buttons to the DOM
            <?php include('sub_files/export_datatable_button_action_code.php') ?>
        });
        $("table.pagelength50_8").dataTable().fnDestroy();
        $('table.pagelength50_8').DataTable({
            "responsive": true,
            "lengthMenu": [
                [50, 100, 200, -1],
                [50, 100, 200, "All"]
            ],
            dom: '<"top"B><"clear"><"top"l><"clear">frt<"bottom"ip><"clear"><br>', // Add buttons to the DOM
            <?php include('sub_files/export_datatable_button_action_code.php') ?>
        });
        $("table.pagelength50_9").dataTable().fnDestroy();
        $('table.pagelength50_9').DataTable({
            "responsive": true,
            "lengthMenu": [
                [50, 100, 200, -1],
                [50, 100, 200, "All"]
            ],
            dom: '<"top"B><"clear"><"top"l><"clear">frt<"bottom"ip><"clear"><br>', // Add buttons to the DOM
            <?php include('sub_files/export_datatable_button_action_code.php') ?>
        });
        $("table.pagelength50_10").dataTable().fnDestroy();
        $('table.pagelength50_10').DataTable({
            "responsive": true,
            "lengthMenu": [
                [50, 100, 200, -1],
                [50, 100, 200, "All"]
            ],
            dom: '<"top"B><"clear"><"top"l><"clear">frt<"bottom"ip><"clear"><br>', // Add buttons to the DOM
            <?php include('sub_files/export_datatable_button_action_code.php') ?>
        });
        $("table.simpledatatable_pagelength100").dataTable().fnDestroy();
        $('table.simpledatatable_pagelength100').DataTable({
            "responsive": true,
            "lengthMenu": [
                [100, 250, 500, -1],
                [100, 250, 500, "All"]
            ],
            dom: '<"top"B><"clear"><"top"l><"clear">frt<"bottom"ip><"clear"><br>', // Add buttons to the DOM
            <?php include('sub_files/export_datatable_button_action_code.php') ?>
        });
        $("table.simpledatatable_pagelength1000_1").dataTable().fnDestroy();
        $('table.simpledatatable_pagelength1000_1').DataTable({
            "responsive": true,
            "lengthMenu": [
                [1000, 2000, 3000, -1],
                [1000, 2000, 3000, "All"]
            ],
            dom: '<"top"B><"clear"><"top"l><"clear">frt<"bottom"ip><"clear"><br>', // Add buttons to the DOM
            buttons: [{
                    extend: 'copy',
                    text: 'Copy',
                    exportOptions: {
                        columns: function(idx, data, node) {
                            // Export only visible columns except the first one (index 0)
                            <?php
                            if (isset($module_id) && $module_id == '18') { ?>
                                return idx !== 0 && $(node).is(':visible');
                            <?php } else { ?>
                                return $(node).is(':visible');
                            <?php } ?>
                        },
                        format: {
                            body: function(data, row, column, node) {
                                // Create a temporary div with the cell's HTML content
                                var tempDiv = $('<td>').html(data);
                                // Find all anchor tags and replace them with their inner text
                                tempDiv.find('a').each(function() {
                                    $(this).replaceWith($(this).text());
                                });
                                // Return the cleaned text content
                                return tempDiv.text().trim();
                            }
                        }
                    },
                    action: function(e, dt, button, config) {
                        // Get Visible Columns excluding the first one (index 0)
                        var visibleColumns = [];
                        dt.columns(':visible').every(function(index) {
                            if (index !== 0) {
                                visibleColumns.push(this.header().textContent);
                            }
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
                        columns: function(idx, data, node) {
                            // Export only visible columns except the first one (index 0)
                            <?php
                            if (isset($module_id) && $module_id == '18') { ?>
                                return idx !== 0 && $(node).is(':visible');
                            <?php } else { ?>
                                return $(node).is(':visible');
                            <?php } ?>
                        }
                    },
                    action: function(e, dt, button, config) {
                        // Get Visible Columns excluding the first one (index 0)
                        var visibleColumns = [];
                        dt.columns(':visible').every(function(index) {
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
                        columns: function(idx, data, node) {
                            // Export only visible columns except the first one (index 0)
                            <?php
                            if (isset($module_id) && $module_id == '18') { ?>
                                return idx !== 0 && $(node).is(':visible');
                            <?php } else { ?>
                                return $(node).is(':visible');
                            <?php } ?>
                        }
                    },
                    action: function(e, dt, button, config) {
                        // Get Visible Columns excluding the first one (index 0)
                        var visibleColumns = [];
                        dt.columns(':visible').every(function(index) {
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
                        columns: function(idx, data, node) {
                            // Export only visible columns except the first one (index 0)
                            <?php
                            if (isset($module_id) && $module_id == '18') { ?>
                                return idx !== 0 && $(node).is(':visible');
                            <?php } else { ?>
                                return $(node).is(':visible');
                            <?php } ?>
                        }
                    },
                    action: function(e, dt, button, config) {
                        // Get Visible Columns excluding the first one (index 0)
                        var visibleColumns = [];
                        dt.columns(':visible').every(function(index) {
                            visibleColumns.push(this.header().textContent);
                        });
                        // Call the original action for Print
                        $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
                    }
                }
            ],
            ordering: false
        });

        // timepicker
        $('.timepicker').timepicker();
    });
</script>
<?php
if (isset($module) && ($module == 'sub_user_roles')) { ?>
    <script language="JavaScript">

    </script>
<?php } ?>
<?php
if (isset($module)) { ?>
    <script type="text/javascript">
        $("#all_checked_subTab").click(function() {
            if ($(this).prop("checked")) {
                $(".checkbox").prop("checked", true);
            } else {
                $(".checkbox").prop("checked", false);
            }
        });
        $(".checkbox").click(function() {
            // $("#38").prop("checked", true);
            var className = $(this).attr('class');
            var result = className.split(" ");
            $.each(result, function(key, value) {
                if (value != 'checkbox') {
                    $("#" + value).prop("checked", true);
                }
            });
            var menu_id = $(this).attr("id");
            if ($(this).prop("checked")) {
                $("." + menu_id).prop("checked", true);
            } else {
                $("." + menu_id).prop("checked", false);
            }
        });

        $("#all_checked").click(function() {
            if ($(this).prop("checked")) {
                $(".checkbox").prop("checked", true);
            } else {
                $(".checkbox").prop("checked", false);
            }
        });
        $("#all_checked2").click(function() {
            if ($(this).prop("checked")) {
                $(".checkbox2").prop("checked", true);
            } else {
                $(".checkbox2").prop("checked", false);
            }
        });
        $("#all_checked3").click(function() {
            if ($(this).prop("checked")) {
                $(".checkbox3").prop("checked", true);
            } else {
                $(".checkbox3").prop("checked", false);
            }
        });
        $("#all_checked4").click(function() {
            if ($(this).prop("checked")) {
                $(".checkbox4").prop("checked", true);
            } else {
                $(".checkbox4").prop("checked", false);
            }
        });
        $("#all_checked5").click(function() {
            if ($(this).prop("checked")) {
                $(".checkbox5").prop("checked", true);
            } else {
                $(".checkbox5").prop("checked", false);
            }
        });
        $("#all_checked6").click(function() {
            if ($(this).prop("checked")) {
                $(".checkbox6").prop("checked", true);
            } else {
                $(".checkbox6").prop("checked", false);
            }
        });
        $("#all_checked7").click(function() {
            if ($(this).prop("checked")) {
                $(".checkbox7").prop("checked", true);
            } else {
                $(".checkbox7").prop("checked", false);
            }
        });
        $("#all_checked8").click(function() {
            if ($(this).prop("checked")) {
                $(".checkbox8").prop("checked", true);
            } else {
                $(".checkbox8").prop("checked", false);
            }
        });
        $("#all_checked9").click(function() {
            if ($(this).prop("checked")) {
                $(".checkbox9").prop("checked", true);
            } else {
                $(".checkbox9").prop("checked", false);
            }
        });
        $("#all_checked10").click(function() {
            if ($(this).prop("checked")) {
                $(".checkbox10").prop("checked", true);
            } else {
                $(".checkbox10").prop("checked", false);
            }
        });

        function change_status(e, record_id) {
            var value = $(e).html();
            value = $.trim(value);
            if (value == 'Enable') {
                value2 = 'Disable';
                value = '0';

            } else if (value == 'Disable') {
                value2 = 'Enable';
                value = '1';
            } else if (value == 'Show') {
                value = 'Disable';
                value2 = 'Hide';
            } else if (value == 'Hide') {
                value = 'Enable';
                value2 = 'Show';
            } else if (value == 'Yes') {
                value2 = 'No';
                value = '0';

            } else if (value == 'No') {
                value2 = 'Yes';
                value = '1';
            }
            MaterialDialog.dialog(
                "Do you want to change the status of the record?", {
                    title: "",
                    modalType: "modal-fixed-footer", // Can be empty, modal-fixed-footer or bottom-sheet
                    buttons: {
                        // Use by default close and confirm buttons
                        close: {
                            className: "blue",
                            text: "Cancel",
                            callback: function() {}
                        },
                        confirm: {
                            className: "red",
                            text: "Yes",
                            modalClose: true,
                            callback: function() {
                                var module = "<?php echo $module; ?>";
                                $.post('components/<?php echo $module_folder_directory; ?><?php echo $module; ?>/index.php', {
                                    record_id: record_id,
                                    type: "update",
                                    module: module,
                                    value: value
                                }, function(res) {
                                    if (res) {
                                        $(e).html(value2)
                                    }
                                });
                            }
                        }
                    }
                }
            );
        }
        $(document).ready(function() {
            $(".sno_width_30").css("width", "30px");
            $(".sno_width_40").css("width", "40px");
            $(".sno_width_50").css("width", "50px");
            $(".sno_width_60").css("width", "60px");
            $(".sno_width_30").css("min-width", "10px");
        });

        $(".twoDecimalNumber").keyup(function() {
            var numberDecimal1 = this.value;
            var numberDecima2 = numberDecimal1.match(/^\d+\.?\d{0,2}/);
            $(this).val(numberDecima2);
        });

        $(".oneDecimalNumber").keyup(function() {
            var numberDecimal1 = this.value;
            var numberDecima2 = numberDecimal1.match(/^\d+\.?\d{0,1}/);
            $(this).val(numberDecima2);
        });

        $(".zeorDecimalNumber").keyup(function() {
            var numberDecimal1 = this.value;
            var numberDecima2 = numberDecimal1.match(/^\d+\.?\d{0,0}/);
            $(this).val(numberDecima2);
        });
    </script>
<?php } ?>
<script type="text/javascript">
    window.onload = function() {
        document.getElementById("loader").style.display = "none";
        document.getElementById("loader-bg").style.display = "none";
    };
</script>