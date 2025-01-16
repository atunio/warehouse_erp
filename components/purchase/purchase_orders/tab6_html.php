<div id="tab6_html" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab6')) {
                                        echo "block";
                                    } else {
                                        echo "none";
                                    } ?>;">
    <div class="card-panel" style="padding-top: 5px; padding-bottom: 5px; margin-top: 0px; margin-bottom: 5px;">
        <div class="row">
            <div class="input-field col m6 s12" style="margin-top: 3px; margin-bottom: 3px;">
                <h6 class="media-heading">
                    <?= $general_heading; ?> => Diagnostic
                </h6>
            </div>
            <div class="input-field col m6 s12" style="text-align: right; margin-top: 3px; margin-bottom: 3px;">
                <?php include("tab_action_btns.php"); ?>
            </div>
        </div>
        <?php
        if (isset($id) && isset($po_no)) {  ?>
            <div class="row">
                <div class="input-field col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>PO#: </b>" . $po_no; ?></span></h6>
                </div>
                <div class="input-field col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Vendor Invoice#: </b>" . $vender_invoice_no; ?></span></h6>
                </div>

                <div class="input-field col m4 s12">
                    <?php $entry_type = "diagnostic";  ?>
                    <a class="btn gradient-45deg-light-blue-cyan timer_<?= $entry_type; ?>" title="Timer" href="javascript:void(0)" id="timer_<?= $entry_type; ?>_<?= $id ?>"
                        <?php
                        if (
                            !isset($_SESSION['is_start']) ||
                            !isset($_SESSION[$entry_type]) ||
                            (isset($_SESSION[$entry_type]) && $_SESSION[$entry_type] != $entry_type)
                        ) { ?> style="display: none;" <?php } ?>>00:00:00 </a>
                    <a class="btn gradient-45deg-green-teal startButton_<?= $entry_type; ?>" title="Start <?= $entry_type; ?>" href="javascript:void(0)" id="startButton_<?= $entry_type; ?>_<?= $id ?>" onclick="startTimer(<?= $id ?>, '<?= $entry_type ?>')" style="<?php
                                                                                                                                                                                                                                                                        if ((
                                                                                                                                                                                                                                                                            isset($_SESSION['is_start']) && $_SESSION['is_start'] == 1) && (isset($_SESSION[$entry_type]) && $_SESSION[$entry_type] == $entry_type)) {
                                                                                                                                                                                                                                                                            echo "display: none;";
                                                                                                                                                                                                                                                                        } ?>">
                        Start
                    </a> &nbsp;
                    <a class="btn gradient-45deg-red-pink stopButton_<?= $entry_type; ?>" title="Stop <?= $entry_type; ?>" href="javascript:void(0)" id="stopButton_<?= $entry_type; ?>_<?= $id ?>" onclick="stopTimer(<?= $id ?>, '<?= $entry_type ?>' )" style=" <?php
                                                                                                                                                                                                                                                                    if (!isset($_SESSION['is_start']) || !isset($_SESSION[$entry_type])) {
                                                                                                                                                                                                                                                                        echo "display: none; ";
                                                                                                                                                                                                                                                                    } else if (isset($_SESSION['is_start']) && $_SESSION['is_start'] != 1 && isset($_SESSION[$entry_type]) && $_SESSION[$entry_type] !=  $entry_type || (isset($_SESSION['d_is_paused']) && $_SESSION['d_is_paused'] == '1')) {
                                                                                                                                                                                                                                                                        echo "display: none;";
                                                                                                                                                                                                                                                                    } ?> ">
                        Stop
                    </a>&nbsp;
                    <a class="btn gradient-45deg-amber-amber pauseButton_<?= $entry_type; ?>" title="Pause Timer" href="javascript:void(0)" id="pauseButton_<?= $entry_type; ?>_<?= $id ?>" onclick="pauseTimer(<?= $id ?>, '<?= $entry_type ?>')" style="<?php
                                                                                                                                                                                                                                                            if (!isset($_SESSION['is_start']) || !isset($_SESSION[$entry_type])) {
                                                                                                                                                                                                                                                                echo "display: none; ";
                                                                                                                                                                                                                                                            } else if (isset($_SESSION[$entry_type]) && $_SESSION[$entry_type] ==  $entry_type && (isset($_SESSION['d_is_paused']) && $_SESSION['d_is_paused'] == '1')) {
                                                                                                                                                                                                                                                                echo "display: none;";
                                                                                                                                                                                                                                                            } ?> ">
                        Pause
                    </a>&nbsp;
                    <a class="btn gradient-45deg-green-teal resumeButton_<?= $entry_type; ?>" title="Resume <?= $entry_type; ?>" href="javascript:void(0)" id="resumeButton_<?= $entry_type; ?>_<?= $id ?>" onclick="resumeTimer(<?= $id ?>, '<?= $entry_type ?>')" style="<?php
                                                                                                                                                                                                                                                                            if (!isset($_SESSION['d_is_paused']) || (isset($_SESSION['d_is_paused']) && $_SESSION['d_is_paused'] == '0') && (!isset($_SESSION[$entry_type]) || (isset($_SESSION[$entry_type]) && $_SESSION[$entry_type] == $entry_type))) {
                                                                                                                                                                                                                                                                                echo "display: none;";
                                                                                                                                                                                                                                                                            } ?> ">Resume <?php //echo $_SESSION[$entry_type]; 
                                                                                                                                                                                                                                                                                            ?>
                    </a>&nbsp;
                    <input type="hidden" name="d_total_pause_duration" id="d_total_pause_duration" value="0">
                </div>
            </div>
            <?php
            if (isset($cmd6) &&  $cmd6 == "add" && isset($detail_id) && $detail_id != "") {  ?>
                <div class="row">
                    <div class="input-field col m4 s12">
                        <h6 class="media-heading"><span class=""><?php echo "<b>Tracking / Pro #: </b>" . $detail_id; ?></span></h6>
                    </div>
                </div>
        <?php }
        }  ?>
    </div>
    <?php
    if (!isset($id)) { ?>
        <div class="card-panel">
            <div class="row">
                <!-- Search for small screen-->
                <div class="container">
                    <div class="card-alert card red">
                        <div class="card-content white-text">
                            <p>Please add master record first</p>
                        </div>
                        <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        $td_padding = "padding:5px 15px !important;";

        $sql        = " SELECT a.*, c.status_name, d.sub_location_name, d.sub_location_type
                        FROM purchase_order_detail_logistics a
                        LEFT JOIN inventory_status c ON c.id = a.logistics_status
                        LEFT JOIN warehouse_sub_locations d ON d.id = a.sub_location_id
                        WHERE a.po_id = '" . $id . "'
                        AND a.arrived_date IS NOT NULL
                        ORDER BY a.tracking_no ";
        // echo $sql; 
        $result_log     = $db->query($conn, $sql);
        $count_log      = $db->counter($result_log);
        if ($count_log > 0) { ?>

            <?php ///*
            ?>
            <form id="barcodeForm2" class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab6") ?>" method="post">
                <input type="hidden" name="is_Submit_tab6_2" value="Y" />
                <input type="hidden" name="cmd6" value="<?php if (isset($cmd6)) echo $cmd6; ?>" />
                <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                    echo encrypt($_SESSION['csrf_session']);
                                                                } ?>">
                <input type="hidden" name="duplication_check_token" value="<?php echo (time() . session_id()); ?>">
                <input type="hidden" name="active_tab" value="tab6" />

                <div class="card-panel">
                    <div class="row">
                        <div class="col m8 s12">
                            <h5>Update Serial No from BarCode</h5>
                        </div>
                        <div class="col m4 s12 show_receive_from_barcode_show_btn_tab6" style="<?php if (isset($is_Submit_tab6_2) && $is_Submit_tab6_2 == 'Y') {
                                                                                                    echo "display: none;";
                                                                                                } else {;
                                                                                                } ?>">
                            <a href="javascript:void(0)" class="show_receive_from_barcode_section_tab6">Show Form</a>
                        </div>
                        <div class="col m4 s12 show_receive_from_barcode_hide_btn_tab6" style="<?php if (isset($is_Submit_tab6_2) && $is_Submit_tab6_2 == 'Y') {;
                                                                                                } else {
                                                                                                    echo "display: none;";
                                                                                                } ?>">
                            <a href="javascript:void(0)" class="hide_receive_from_barcode_section_tab6">Hide Form</a>
                        </div>
                    </div>
                    <div id="receive_from_barcode_section_tab6" style="<?php if (isset($is_Submit_tab6_2) && $is_Submit_tab6_2 == 'Y') {;
                                                                        } else {
                                                                            echo "display: none;";
                                                                        } ?>">
                        <div class="row">
                            <div class="input-field col m12 s12"> </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m12 s12">
                                <?php
                                $field_name     = "product_id_barcode_diagnostic";
                                $field_label    = "Product ID";
                                $sql            = " SELECT a.*, c.product_desc, d.category_name, c.product_uniqueid
                                                    FROM purchase_order_detail a 
                                                    INNER JOIN purchase_orders b ON b.id = a.po_id
                                                    INNER JOIN products c ON c.id = a.product_id
                                                    INNER JOIN product_categories d ON d.id = c.product_category
                                                    WHERE 1=1 
                                                    AND a.po_id = '" . $id . "' 
                                                    ORDER BY c.product_uniqueid, a.product_condition ";
                                // echo $sql; 
                                $result_log2    = $db->query($conn, $sql);
                                $count_r2       = $db->counter($result_log2); ?>
                                <i class="material-icons prefix pt-1">add_shopping_cart</i>
                                <div class="select2div">
                                    <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                                                                    } ?>">
                                        <?php
                                        if ($count_r2 > 1) { ?>
                                            <option value="">Select</option>
                                            <?php
                                        }
                                        if ($count_r2 > 0) {
                                            $row_r2    = $db->fetch($result_log2);
                                            foreach ($row_r2 as $data_r2) {

                                                $detail_id_r1       = $data_r2['id'];
                                                $order_qty          = $data_r2['order_qty'];

                                                $sql_rc1            = "	SELECT a.* 
                                                                        FROM purchase_order_detail_receive a 
                                                                        WHERE 1=1 
                                                                        AND a.po_detail_id = '" . $detail_id_r1 . "'
                                                                        AND a.enabled = 1 "; //echo $sql_cl;
                                                $result_rc1         = $db->query($conn, $sql_rc1);
                                                $total_received_qty = $db->counter($result_rc1);  ?>

                                                <option value="<?php echo $data_r2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data_r2['id']) { ?> selected="selected" <?php } ?>>
                                                    <?php
                                                    echo " Product ID: " . $data_r2['product_uniqueid'];
                                                    echo " -  Product: " . $data_r2['product_desc'];
                                                    if ($data_r2['category_name'] != "") {
                                                        echo " (" . $data_r2['category_name'] . ") ";
                                                    }
                                                    echo " - Order QTY: " . $order_qty . ", Total Received Yet: " . $total_received_qty; ?>
                                                </option>
                                        <?php
                                            }
                                        } ?>
                                    </select>
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red">* <?php
                                                                    if (isset($error6[$field_name])) {
                                                                        echo $error6[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m4 s12"></div>
                        </div>
                        <div class="row">
                            <div class="input-field col m6 s12">
                                <?php
                                $field_name     = "sub_location_id_barcode_diagnostic";
                                $field_label    = "Location";
                                $sql1           = "SELECT * FROM warehouse_sub_locations a WHERE a.enabled = 1  ORDER BY sub_location_name ";
                                $result1        = $db->query($conn, $sql1);
                                $count1         = $db->counter($result1);
                                ?>
                                <i class="material-icons prefix">question_answer</i>
                                <div class="select2div">
                                    <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                                                                    } ?>">
                                        <option value="">Select</option>
                                        <?php
                                        if ($count1 > 0) {
                                            $row1    = $db->fetch($result1);
                                            foreach ($row1 as $data2) { ?>
                                                <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $data2['sub_location_name'];
                                                    if ($data2['sub_location_type'] != "") {
                                                        echo " (" . ucwords(strtolower($data2['sub_location_type'])) . ")";
                                                    } ?>
                                                </option>
                                        <?php }
                                        } ?>
                                    </select>
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red">* <?php
                                                                    if (isset($error6[$field_name])) {
                                                                        echo $error6[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="input-field col m6 s12">
                                <?php
                                $field_name     = "serial_no_barcode_diagnostic";
                                $field_label    = "Bar Code";
                                ?>
                                <i class="material-icons prefix">description</i>
                                <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                    echo ${$field_name};
                                                                                                                } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                            echo ${$field_name . "_valid"};
                                                                                                                                        } ?>" onkeyup="autoSubmit2(event)" autofocus>
                                <label for="<?= $field_name; ?>">
                                    <?= $field_label; ?>
                                    <span class="color-red">* <?php
                                                                if (isset($error6[$field_name])) {
                                                                    echo $error6[$field_name];
                                                                } ?>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m12 s12"></div>
                        </div>
                        <div class="row">
                            <div class="input-field col m12 s12">
                                <?php if (isset($id) && $id > 0 && (($cmd6 == 'add' || $cmd6 == '') && access("add_perm") == 1)  || ($cmd6 == 'edit' && access("edit_perm") == 1) || ($cmd6 == 'delete' && access("delete_perm") == 1)) { ?>
                                    <button class="mb-6 btn waves-effect waves-light gradient-45deg-purple-deep-orange" type="submit" name="add">Update</button>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m12 s12"></div>
                        </div>
                    </div>
                </div>
            </form>
            <?php //*/ 
            ?>

            <?php
            $sql        = " SELECT a.*, c.status_name, d.sub_location_name, d.sub_location_type
                            FROM purchase_order_detail_logistics a
                            LEFT JOIN inventory_status c ON c.id = a.logistics_status
                            LEFT JOIN warehouse_sub_locations d ON d.id = a.sub_location_id
                            WHERE a.po_id = '" . $id . "'
                            AND a.arrived_date IS NOT NULL
                            ORDER BY a.tracking_no ";
            // echo $sql; 
            $result_log     = $db->query($conn, $sql);
            $count_log      = $db->counter($result_log);
            if ($count_log > 0) { ?>
            <?php
            } else { ?>
                <div class="card-panel">
                    <div class="row">
                        <div class="col 24 s12"><br>
                            <div class="card-alert card red lighten-5">
                                <div class="card-content red-text">
                                    <p>No arrival received yet. </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php ///*
            ?>
            <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab6") ?>" method="post">
                <input type="hidden" name="is_Submit_tab6_5" value="Y" />
                <input type="hidden" name="cmd6" value="<?php if (isset($cmd6)) echo $cmd6; ?>" />
                <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                    echo encrypt($_SESSION['csrf_session']);
                                                                } ?>">
                <input type="hidden" name="duplication_check_token" value="<?php echo (time() . session_id()); ?>">
                <input type="hidden" name="active_tab" value="tab6" />

                <div class="card-panel">
                    <div class="row">
                        <div class="col m8 s12">
                            <h5>Update Serial Numbers Manually</h5>
                        </div>
                        <div class="col m4 s12 show_receive_as_manual_barcodes_show_btn_tab6" style="<?php if (isset($is_Submit_tab6_5) && $is_Submit_tab6_5 == 'Y') {
                                                                                                            echo "display: none;";
                                                                                                        } else {;
                                                                                                        } ?>">
                            <a href="javascript:void(0)" class="show_receive_as_manual_barcodes_section_tab6">Show Form</a>
                        </div>
                        <div class="col m4 s12 show_receive_as_manual_barcodes_hide_btn_tab6" style="<?php if (isset($is_Submit_tab6_5) && $is_Submit_tab6_5 == 'Y') {;
                                                                                                        } else {
                                                                                                            echo "display: none;";
                                                                                                        } ?>">
                            <a href="javascript:void(0)" class="hide_receive_as_manual_barcodes_section_tab6">Hide Form</a>
                        </div>
                    </div>
                    <div id="receive_as_manual_barcodes_section_tab6" style="<?php if (isset($is_Submit_tab6_5) && $is_Submit_tab6_5 == 'Y') {;
                                                                                } else {
                                                                                    echo "display: none;";
                                                                                } ?>">
                        <div class="row">
                            <div class="input-field col m12 s12"> </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m9 s12">
                                <?php
                                $field_name     = "product_id_manual_diagnostic";
                                $field_label    = "Product ID";
                                $sql            = " SELECT a.*, c.product_desc, d.category_name, c.product_uniqueid
                                                    FROM purchase_order_detail a 
                                                    INNER JOIN purchase_orders b ON b.id = a.po_id
                                                    INNER JOIN products c ON c.id = a.product_id
                                                    INNER JOIN product_categories d ON d.id = c.product_category
                                                    WHERE 1=1 
                                                    AND a.po_id = '" . $id . "' 
                                                    ORDER BY c.product_uniqueid, a.product_condition "; // echo $sql; 
                                $result_log2    = $db->query($conn, $sql);
                                $count_r2       = $db->counter($result_log2); ?>

                                <i class="material-icons prefix pt-1">add_shopping_cart</i>
                                <div class="select2div">
                                    <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                                                                    } ?>">
                                        <?php
                                        if ($count_r2 > 1) { ?>
                                            <option value="">Select</option>
                                            <?php
                                        }
                                        if ($count_r2 > 0) {
                                            $row_r2    = $db->fetch($result_log2);
                                            foreach ($row_r2 as $data_r2) {

                                                $detail_id_r1       = $data_r2['id'];
                                                $order_qty          = $data_r2['order_qty'];

                                                $sql_rc1            = "	SELECT a.* 
                                                                        FROM purchase_order_detail_receive a 
                                                                        WHERE 1=1 
                                                                        AND a.po_detail_id = '" . $detail_id_r1 . "'
                                                                        AND a.enabled = 1 "; //echo $sql_cl;
                                                $result_rc1         = $db->query($conn, $sql_rc1);
                                                $total_received_qty = $db->counter($result_rc1);  ?>

                                                <option value="<?php echo $data_r2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data_r2['id']) { ?> selected="selected" <?php } ?>>
                                                    <?php
                                                    echo " Product ID: " . $data_r2['product_uniqueid'];
                                                    echo " -  Product: " . $data_r2['product_desc'];
                                                    if ($data_r2['category_name'] != "") {
                                                        echo " (" . $data_r2['category_name'] . ") ";
                                                    }
                                                    echo " - Order QTY: " . $order_qty . ", Total Received Yet: " . $total_received_qty; ?>
                                                </option>
                                        <?php
                                            }
                                        } ?>
                                    </select>
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red">* <?php
                                                                    if (isset($error6[$field_name])) {
                                                                        echo $error6[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div class="input-field col m3 s12">
                                <?php
                                $field_name     = "sub_location_id_manual_diagnostic";
                                $field_label    = "Location";
                                $sql1           = "SELECT * FROM warehouse_sub_locations a WHERE a.enabled = 1  ORDER BY sub_location_name ";
                                $result1        = $db->query($conn, $sql1);
                                $count1         = $db->counter($result1);
                                ?>
                                <i class="material-icons prefix">question_answer</i>
                                <div class="select2div">
                                    <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                                                                    } ?>">
                                        <option value="">Select</option>
                                        <?php
                                        if ($count1 > 0) {
                                            $row1    = $db->fetch($result1);
                                            foreach ($row1 as $data2) { ?>
                                                <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $data2['sub_location_name'];
                                                    if ($data2['sub_location_type'] != "") {
                                                        echo " (" . ucwords(strtolower($data2['sub_location_type'])) . ")";
                                                    } ?>
                                                </option>
                                        <?php }
                                        } ?>
                                    </select>
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red">* <?php
                                                                    if (isset($error6[$field_name])) {
                                                                        echo $error6[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m12 s12">&nbsp;</div>
                        </div>
                        <div class="row">
                            <?php
                            $max = 2;
                            if (isset($serial_no_manual_diagnostic)) {
                                $serial_no_manual_diagnostic = array_filter($serial_no_manual_diagnostic);
                                $max = sizeof($serial_no_manual_diagnostic) - 1;
                            }
                            for ($i = 0; $i < 100; $i++) {
                                $style = $style2 = "";
                                if ($i > $max) {
                                    $style = "display: none;";
                                }
                                if ($i > $max || $i < $max) {
                                    $style2 = "display: none;";
                                }
                                $i2 = $i + 1; ?>
                                <div class="input-field col m3 s12 serial_no_manual_diagnostic_input_<?= $i2; ?>" style="<?= $style; ?>">
                                    <?php
                                    $field_name     = "serial_no_manual_diagnostic";
                                    $field_id       = $field_name . "_" . $i2;
                                    $field_label    = "Serial No";
                                    ?>
                                    <i class="material-icons prefix">description</i>
                                    <input id="<?= $field_id; ?>" type="text" name="<?= $field_name; ?>[]" value="<?php if (isset($serial_no_manual_diagnostic[$i])) {
                                                                                                                        echo $serial_no_manual_diagnostic[$i];
                                                                                                                    } ?>" class="validate ">
                                    <label for="<?= $field_id; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red">* <?php
                                                                    if (isset($error6["field_name_" . $i2])) {
                                                                        echo $error6["field_name_" . $i2];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                                <div style="<?= $style; ?>" class=" input-field col m1 s12 button_div_serial_no_manual_diagnostic" id="button_div_serial_no_manual_diagnostic_<?= $i2; ?>">
                                    <a href="#." style="<?= $style2; ?> font-size: 30px;" class="add_<?= $field_name; ?> add_<?= $field_name; ?>_<?= $i2; ?>" id="add_<?= $field_name; ?>^<?= $i2; ?>">+</a>
                                    &nbsp;
                                    <a href="#." style="<?= $style; ?> font-size: 30px;" class="minus_<?= $field_name; ?> minus_<?= $field_name; ?>_<?= $i2; ?>" id="minus_<?= $field_name; ?>^<?= $i2; ?>">-</a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="row">
                            <div class="input-field col m12 s12">
                                <?php if (isset($id) && $id > 0 && (($cmd6 == 'add' || $cmd6 == '') && access("add_perm") == 1)  || ($cmd6 == 'edit' && access("edit_perm") == 1) || ($cmd6 == 'delete' && access("delete_perm") == 1)) { ?>
                                    <button class="mb-6 btn waves-effect waves-light gradient-45deg-purple-deep-orange" type="submit" name="add">Update</button>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m12 s12"></div>
                        </div>
                    </div>
                </div>
            </form>
            <?php //*/ 
            /*?>
            <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab6") ?>" method="post">
                <input type="hidden" name="is_Submit_tab6_6" value="Y" />
                <input type="hidden" name="cmd6" value="<?php if (isset($cmd6)) echo $cmd6; ?>" />
                <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                    echo encrypt($_SESSION['csrf_session']);
                                                                } ?>">
                <input type="hidden" name="duplication_check_token" value="<?php echo (time() . session_id()); ?>">
                <input type="hidden" name="active_tab" value="tab6" />

                <div class="card-panel">
                    <div class="row">
                        <div class="col m6 s12">
                            <h5>Fetch Data from PhoneCheck</h5>
                        </div>
                        <div class="col m6 s12 update_tested_devices_serial_from_phonechecker_show_btn_tab6" style="<?php if (isset($is_Submit_tab6_6) && $is_Submit_tab6_6 == 'Y') {
                                                                                                                        echo "display: none;";
                                                                                                                    } else {;
                                                                                                                    } ?>">
                            <a href="javascript:void(0)" class="show_update_tested_devices_serial_from_phonechecker_tab6">Show Form</a>
                        </div>
                        <div class="col m6 s12 update_tested_devices_serial_from_phonechecker_hide_btn_tab6" style="<?php if (isset($is_Submit_tab6_6) && $is_Submit_tab6_6 == 'Y') {;
                                                                                                                    } else {
                                                                                                                        echo "display: none;";
                                                                                                                    } ?>">
                            <a href="javascript:void(0)" class="hide_update_tested_devices_serial_from_phonechecker_tab6">Hide Form</a>
                        </div>
                    </div>
                    <div id="update_tested_devices_serial_from_phonechecker_tab6" style="<?php if (isset($is_Submit_tab6_6) && $is_Submit_tab6_6 == 'Y') {;
                                                                                            } else {
                                                                                                echo "display: none;";
                                                                                            } ?>">
                        <div class="row">
                            <div class="input-field col m12 s12"> </div>
                        </div>
                        <?php /*?>
                        <div class="row">
                            <div class="input-field col m12 s12">
                                <?php
                                $field_name     = "diagnostic_invoice_no";
                                $field_label    = "Product ID";
                                $sql            = " SELECT a.*, c.product_desc, d.category_name, c.product_uniqueid
                                                    FROM purchase_order_detail a 
                                                    INNER JOIN purchase_orders b ON b.id = a.po_id
                                                    INNER JOIN products c ON c.id = a.product_id
                                                    INNER JOIN product_categories d ON d.id = c.product_category
                                                    WHERE 1=1 
                                                    AND a.po_id = '" . $id . "' 
                                                    ORDER BY c.product_uniqueid, a.product_condition "; // echo $sql; 
                                $result_log2    = $db->query($conn, $sql);
                                $count_r2       = $db->counter($result_log2); ?>

                                <i class="material-icons prefix pt-1">add_shopping_cart</i>
                                <div class="select2div">
                                    <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                                                                    } ?>">
                                        <?php
                                        if ($count_r2 > 1) { ?>
                                            <option value="">Select</option>
                                            <?php
                                        }
                                        if ($count_r2 > 0) {
                                            $row_r2    = $db->fetch($result_log2);
                                            foreach ($row_r2 as $data_r2) {

                                                $detail_id_r1       = $data_r2['id'];
                                                $order_qty          = $data_r2['order_qty'];

                                                $sql_rc1            = "	SELECT a.* 
                                                                        FROM purchase_order_detail_receive a 
                                                                        WHERE 1=1 
                                                                        AND a.po_detail_id = '" . $detail_id_r1 . "'
                                                                        AND a.enabled = 1 "; //echo $sql_cl;
                                                $result_rc1         = $db->query($conn, $sql_rc1);
                                                $total_received_qty = $db->counter($result_rc1);  ?>

                                                <option value="<?php echo $data_r2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data_r2['id']) { ?> selected="selected" <?php } ?>>
                                                    <?php
                                                    echo " Product ID: " . $data_r2['product_uniqueid'];
                                                    echo " -  Product: " . $data_r2['product_desc'];
                                                    if ($data_r2['category_name'] != "") {
                                                        echo " (" . $data_r2['category_name'] . ") ";
                                                    }
                                                    echo " - Order QTY: " . $order_qty . ", Total Received Yet: " . $total_received_qty; ?>
                                                </option>
                                        <?php
                                            }
                                        } ?>
                                    </select>
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red">* <?php
                                                                    if (isset($error6[$field_name])) {
                                                                        echo $error6[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m12 s12">&nbsp;</div>
                        </div>
                        <?php   
                <div class="row">
                    <div class="input-field col m4 s12">
                        <?php
                        $field_name     = "phone_check_username";
                        $field_label    = "PhoneCheck User";
                        $sql            = " SELECT a.*
                                            FROM phone_check_users a 
                                            WHERE 1=1 
                                            AND a.enabled = '1' 
                                            ORDER BY a.username"; // echo $sql; 
                        $result_log2    = $db->query($conn, $sql);
                        $count_r2       = $db->counter($result_log2); ?>
                        <i class="material-icons prefix pt-1">description</i>
                        <div class="select2div">
                            <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible  validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                echo ${$field_name . "_valid"};
                                                                                                                                                            } ?>">
                                <?php
                                if ($count_r2 > 1) { ?>
                                    <option value="">Select</option>
                                    <?php
                                }
                                if ($count_r2 > 0) {
                                    $row_r2    = $db->fetch($result_log2);
                                    foreach ($row_r2 as $data_r2) { ?>
                                        <option value="<?php echo $data_r2['username']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data_r2['username']) { ?> selected="selected" <?php } ?>>
                                            <?php echo $data_r2['username'];  ?>
                                        </option>
                                <?php
                                    }
                                } ?>
                            </select>
                            <label for="<?= $field_name; ?>">
                                <?= $field_label; ?>
                                <span class="color-red">* <?php
                                                            if (isset($error6[$field_name])) {
                                                                echo $error6[$field_name];
                                                            } ?>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="input-field col m4 s12">
                        <?php
                        $field_name     = "diagnostic_date";
                        $field_id       = $field_name;
                        $field_label    = "PhoneCheck Diagnostic Date ";
                        ?>
                        <i class="material-icons prefix">date_range</i>
                        <input id="<?= $field_id; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                        echo ${$field_name};
                                                                                                    } else {
                                                                                                        echo date('m/d/Y');
                                                                                                    } ?>" class="datepicker validate ">
                        <label for="<?= $field_id; ?>">
                            <?= $field_label; ?>
                            <span class="color-red">* <?php
                                                        if (isset($error6[$field_name])) {
                                                            echo $error6[$field_name];
                                                        } ?>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col m4 s12"></div>
                    <div class="input-field col m4 s12">
                        <?php if (isset($id) && $id > 0 && (($cmd6 == 'add' || $cmd6 == '') && access("add_perm") == 1)  || ($cmd6 == 'edit' && access("edit_perm") == 1) || ($cmd6 == 'delete' && access("delete_perm") == 1)) { ?>
                            <button class="mb-6 btn waves-effect waves-light gradient-45deg-purple-deep-orange" type="submit" name="add">Update</button>
                        <?php } ?>
                    </div>
                    <div class="input-field col m4 s12"></div>
                </div>
                <div class="row">
                    <div class="input-field col m12 s12"></div>
                </div>
            </form>
            <?php */
            $td_padding = "padding:5px 10px !important;";
            $sql            = " SELECT a.*, c.product_desc, a.base_product_id, d.category_name, 
                                        e.first_name, e.middle_name, e.last_name, e.username, f.tracking_no, g.sub_location_name, 
                                        i.sub_location_name as sub_location_name_after_diagnostic,
                                        b.order_price, h.status_name, c.product_uniqueid
                                FROM purchase_order_detail_receive a 
                                INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
                                INNER JOIN products c ON c.id = b.product_id
                                LEFT JOIN product_categories d ON d.id =c.product_category
                                LEFT JOIN users e ON e.id = a.add_by_user_id
                                INNER JOIN purchase_order_detail_logistics f ON f.id = a.logistic_id
                                LEFT JOIN warehouse_sub_locations g ON g.id = a.sub_location_id
                                LEFT JOIN inventory_status h ON h.id = a.inventory_status
                                LEFT JOIN warehouse_sub_locations i ON i.id = a.sub_location_id_after_diagnostic
                                WHERE a.enabled = 1 
                                AND b.po_id = '" . $id . "'
                                ORDER BY a.base_product_id, a.serial_no_barcode DESC, g.sub_location_name, a.id DESC ";
            $result_log     = $db->query($conn, $sql);
            $count_log      = $db->counter($result_log);
            if ($count_log > 0) { ?>
                <div class="card-panel">
                    <div class="row">
                        <div class="col m12 s12">
                            <a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=import_to_map_data&id=" . $id) ?>" class="btn waves-effect waves-light gradient-45deg-amber-amber">Import & Map Diagnostic Data</a>
                        </div>
                    </div>
                </div>
                <div class="card-panel">
                    <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab6") ?>" method="post">
                        <input type="hidden" name="is_Submit_tab6_7" value="Y" />
                        <input type="hidden" name="cmd6" value="<?php if (isset($cmd6)) echo $cmd6; ?>" />
                        <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                            echo encrypt($_SESSION['csrf_session']);
                                                                        } ?>">
                        <input type="hidden" name="duplication_check_token" value="<?php echo (time() . session_id()); ?>">
                        <input type="hidden" name="active_tab" value="tab6" />

                        <div class="row">
                            <div class="col m4 s12">
                                <h5>Received Products</h5>
                            </div>
                            <div class="col m8 s12">
                                <a href="export/export_po_received_items.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" class="waves-effect waves-light  btn gradient-45deg-light-blue-cyan box-shadow-none border-round mr-1 mb-12">Export in Excel</a>
                            </div>
                        </div>
                        <?php
                        if (po_permisions("Move as Inventory") == 1) { ?>
                            <div class="row">
                                <div class="col m12 s12">
                                    <label>
                                        <input type="checkbox" id="all_checked7" class="filled-in" name="all_checked7" value="1" <?php if (isset($all_checked7) && $all_checked7 == '1') {
                                                                                                                                        echo "checked";
                                                                                                                                    } ?> />
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="section section-data-tables">
                            <div class="row">
                                <div class="col m12 s12">
                                    <table id="page-length-option" class="display pagelength50_2 dataTable dtr-inline">
                                        <thead>
                                            <tr>
                                                <?php
                                                $headings = '<th class="sno_width_60">S.No</th>
                                                            <th>Product ID / <br>Product Detail</th>
                                                            <th>Serial#</th>   
                                                            <th>Specification</th>
                                                             <th>Grading</th> 
                                                            <th>Warranty / <br>Price / <br>Defects</th> 
                                                             <th>Inventory Status</th>';
                                                echo $headings;
                                                $headings2 = ' '; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 0;
                                            if ($count_log > 0) {
                                                $row_cl1 = $db->fetch($result_log);
                                                foreach ($row_cl1 as $data) {
                                                    $detail_id2                 = $data['id'];
                                                    $po_detail_id               = $data['po_detail_id'];
                                                    $product_uniqueid_main      = $data['product_uniqueid'];
                                                    $battery                    = $data['battery'];
                                                    $body_grade                 = $data['body_grade'];
                                                    $lcd_grade                  = $data['lcd_grade'];
                                                    $digitizer_grade            = $data['digitizer_grade'];
                                                    $ram                        = $data['ram'];
                                                    $memory                     = $data['storage'];
                                                    $defectsCode                = $data['defects_or_notes'];
                                                    $inventory_status           = $data['inventory_status'];
                                                    $status_name                = $data['status_name'];
                                                    $overall_grade              = $data['overall_grade'];
                                                    $serial_no_barcode          = $data['serial_no_barcode'];
                                                    $processor                  = $data['processor'];
                                                    $warranty                   = $data['warranty'];
                                                    $order_price                = $data['order_price'];
                                                    $is_diagnost                = $data['is_diagnost'];
                                                    $is_import_diagnostic_data  = $data['is_import_diagnostic_data'];
                                                    $is_rma_processed           = $data['is_rma_processed'];
                                                    $edit_lock                  = $data['edit_lock']; ?>
                                                    <tr>
                                                        <td style="<?= $td_padding; ?>">
                                                            <?php echo $i + 1;
                                                            if ($serial_no_barcode != "" && $serial_no_barcode != null && po_permisions("Move as Inventory") == 1 && $edit_lock == "0" && $is_diagnost == "1") { ?>
                                                                <label style="margin-left: 25px;">
                                                                    <input type="checkbox" name="ids_for_stock[]" id="ids_for_stock[]" value="<?= $detail_id2; ?>" class="checkbox7 filled-in" />
                                                                    <span></span>
                                                                </label>
                                                            <?php } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>">
                                                            <?php
                                                            echo $data['base_product_id']; ?>
                                                            <br>
                                                            <?php
                                                            echo $data['product_desc'];
                                                            if ($data['category_name'] != "") {
                                                                echo " (" . $data['category_name'] . ")";
                                                            } ?>
                                                            <br>
                                                            <?php
                                                            if ($data['sub_location_name_after_diagnostic'] != "") {
                                                                echo " Location: " . $data['sub_location_name_after_diagnostic'];
                                                            } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>">
                                                            <?php
                                                            $color  = "purple";

                                                            $sql                = " SELECT a.*
                                                                                    FROM vender_po_data a
                                                                                    WHERE a.enabled = 1
                                                                                    AND a.serial_no = '" . $serial_no_barcode . "'
                                                                                    AND a.po_id             = '" . $id . "'  ";
                                                            $result_vebder = $db->query($conn, $sql);
                                                            $count_vebder  = $db->counter($result_vebder);
                                                            if ($count_vebder > 0) {
                                                                $row_vender = $db->fetch($result_vebder);
                                                            }
                                                            if ($count_vebder > 0) {
                                                                $color  = "green";
                                                            }
                                                            if ($serial_no_barcode != "") { ?>
                                                                <span class="chip <?= $color; ?> lighten-5">
                                                                    <span class="<?= $color; ?>-text"><?php echo $serial_no_barcode; ?></span>
                                                                </span>
                                                            <?php } ?>
                                                            <?php
                                                            if ($serial_no_barcode != "") {
                                                                ///*
                                                                if ($is_import_diagnostic_data == '0' && ($data['phone_check_api_data'] == NULL || $data['phone_check_api_data'] == "[]" || $data['phone_check_api_data'] == "" || $data['phone_check_api_data'] == '(NULL)' || $data['phone_check_api_data'] == '{"msg":"Failed to get device info results"}')) {
                                                                    $model_name = $model_no = $make_name = $carrier_name = $color_name = $battery = $body_grade = $lcd_grade = $digitizer_grade = $ram = $memory = $defectsCode = $overall_grade = "";
                                                                    $sql_pd01_4         = "	SELECT  a.*
                                                                                            FROM phone_check_api_data a 
                                                                                            WHERE a.enabled = 1 
                                                                                            AND a.imei_no = '" . $serial_no_barcode . "'
                                                                                            ORDER BY a.id DESC LIMIT 1";

                                                                    $result_pd01_4    = $db->query($conn, $sql_pd01_4);
                                                                    $count_pd01_4    = $db->counter($result_pd01_4);
                                                                    if ($count_pd01_4 > 0) {
                                                                        $row_pd01_4                     = $db->fetch($result_pd01_4);
                                                                        $jsonData2                      = $row_pd01_4[0]['phone_check_api_data'];
                                                                        $model_name                     = $row_pd01_4[0]['model_name'];
                                                                        $model_no                       = $row_pd01_4[0]['model_no'];
                                                                        $make_name                      = $row_pd01_4[0]['make_name'];
                                                                        $carrier_name                   = $row_pd01_4[0]['carrier_name'];
                                                                        $color_name                     = $row_pd01_4[0]['color_name'];
                                                                        $battery                        = $row_pd01_4[0]['battery'];
                                                                        $body_grade                     = $row_pd01_4[0]['body_grade'];
                                                                        $lcd_grade                      = $row_pd01_4[0]['lcd_grade'];
                                                                        $digitizer_grade                = $row_pd01_4[0]['digitizer_grade'];
                                                                        $ram                            = $row_pd01_4[0]['ram'];
                                                                        $memory                         = $row_pd01_4[0]['memory'];
                                                                        $defectsCode                    = $row_pd01_4[0]['defectsCode'];
                                                                        $overall_grade                  = $row_pd01_4[0]['overall_grade'];
                                                                        $is_diagnost                    = 1;

                                                                        $inventory_status = '6';
                                                                        $status_name = "Defective";
                                                                        if ($defectsCode == '' || $defectsCode == NULL) {
                                                                            if ($battery != "" && $battery >= '60') {
                                                                                $inventory_status = '5';
                                                                                $status_name = "Tested/Graded";
                                                                            }
                                                                        }
                                                                    } else {
                                                                        $device_detail_array    = getinfo_phonecheck_imie($serial_no_barcode);
                                                                        $jsonData2              = json_encode($device_detail_array);
                                                                        if ($jsonData2 != '[]') {
                                                                            foreach ($device_detail_array as $key1 => $data_api) {
                                                                                foreach ($data_api as $key2 => $data2) {
                                                                                    if ($key2 == 'BatteryHealthPercentage') {
                                                                                        $battery = $data2;
                                                                                    }
                                                                                    if ($key2 == 'Model') {
                                                                                        $model_name = str_replace('"', '', $data2);
                                                                                    }
                                                                                    if ($key2 == 'Model#') {
                                                                                        $model_no = $data2;
                                                                                    }
                                                                                    if ($key2 == 'Make') {
                                                                                        $make_name = $data2;
                                                                                    }
                                                                                    if ($key2 == 'Carrier') {
                                                                                        $carrier_name = $data2;
                                                                                    }
                                                                                    if ($key2 == 'Color') {
                                                                                        $color_name = $data2;
                                                                                    }
                                                                                    if ($key2 == 'Cosmetics') {
                                                                                        $body_grade = $lcd_grade = $digitizer_grade = "";
                                                                                        if ($data2 != "") {
                                                                                            $pass_array         = explode(",", $data2);
                                                                                            if (sizeof($pass_array) == 3) {
                                                                                                $body_grade         = $pass_array[0];
                                                                                                $lcd_grade          = $pass_array[1];
                                                                                                $digitizer_grade    = $pass_array[2];
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                    if ($key2 == 'Ram') {
                                                                                        $ram = $data2;
                                                                                    }
                                                                                    if ($key2 == 'Memory') {
                                                                                        $memory = $data2;
                                                                                    }
                                                                                    if ($key2 == 'DefectsCode') {
                                                                                        $defectsCode = $data2;
                                                                                    }
                                                                                    if ($key2 == 'Grade') {
                                                                                        $overall_grade = $data2;
                                                                                    }
                                                                                }
                                                                            }
                                                                            $inventory_status = '6';
                                                                            $status_name = "Defective";
                                                                            if ($defectsCode == '' || $defectsCode == NULL) {
                                                                                if ($battery != "" && $battery >= '60') {
                                                                                    $inventory_status = '5';
                                                                                    $status_name = "Tested/Graded";
                                                                                }
                                                                            }

                                                                            if ($battery > '60' && ($lcd_grade == 'D' || $digitizer_grade == 'D' || $body_grade == 'D')) {
                                                                                $overall_grade = "D";
                                                                            } else if ($battery != "" && $battery >= '70') {
                                                                                if ($lcd_grade == 'A' && $digitizer_grade == 'A' && $body_grade == 'A') {
                                                                                    $overall_grade = "A";
                                                                                }
                                                                                if ($lcd_grade == 'A' && $digitizer_grade == 'A' && $body_grade == 'B') {
                                                                                    $overall_grade = "B";
                                                                                }
                                                                                if ($lcd_grade == 'A' && $digitizer_grade == 'A' && $body_grade == 'C') {
                                                                                    $overall_grade = "C";
                                                                                }

                                                                                if ($lcd_grade == 'B' && $digitizer_grade == 'A' && $body_grade == 'A') {
                                                                                    $overall_grade = "B";
                                                                                }
                                                                                if ($lcd_grade == 'B' && $digitizer_grade == 'A' && $body_grade == 'B') {
                                                                                    $overall_grade = "B";
                                                                                }
                                                                                if ($lcd_grade == 'B' && $digitizer_grade == 'A' && $body_grade == 'C') {
                                                                                    $overall_grade = "C";
                                                                                }

                                                                                if ($lcd_grade == 'C' && $digitizer_grade == 'A' && $body_grade == 'A') {
                                                                                    $overall_grade = "C";
                                                                                }
                                                                                if ($lcd_grade == 'C' && $digitizer_grade == 'A' && $body_grade == 'B') {
                                                                                    $overall_grade = "C";
                                                                                }
                                                                                if ($lcd_grade == 'C' && $digitizer_grade == 'A' && $body_grade == 'C') {
                                                                                    $overall_grade = "C";
                                                                                }

                                                                                if ($lcd_grade == 'A' && $digitizer_grade == 'B' && $body_grade == 'A') {
                                                                                    $overall_grade = "B";
                                                                                }
                                                                                if ($lcd_grade == 'B' && $digitizer_grade == 'B' && $body_grade == 'B') {
                                                                                    $overall_grade = "B";
                                                                                }
                                                                                if ($lcd_grade == 'C' && $digitizer_grade == 'B' && $body_grade == 'C') {
                                                                                    $overall_grade = "C";
                                                                                }

                                                                                if ($lcd_grade == 'A' && $digitizer_grade == 'C' && $body_grade == 'A') {
                                                                                    $overall_grade = "C";
                                                                                }
                                                                                if ($lcd_grade == 'A' && $digitizer_grade == 'C' && $body_grade == 'B') {
                                                                                    $overall_grade = "C";
                                                                                }
                                                                                if ($lcd_grade == 'A' && $digitizer_grade == 'C' && $body_grade == 'C') {
                                                                                    $overall_grade = "C";
                                                                                }
                                                                            } else if ($battery < '70' && $battery >= '60') {
                                                                                if ($lcd_grade == 'A' && $digitizer_grade == 'A' && $body_grade == 'A') {
                                                                                    $overall_grade = "B";
                                                                                }
                                                                                if ($lcd_grade == 'B' && $digitizer_grade == 'A' && $body_grade == 'B') {
                                                                                    $overall_grade = "B";
                                                                                }
                                                                                if ($lcd_grade == 'C' && $digitizer_grade == 'A' && $body_grade == 'C') {
                                                                                    $overall_grade = "C";
                                                                                }

                                                                                if ($lcd_grade == 'A' && $digitizer_grade == 'B' && $body_grade == 'A') {
                                                                                    $overall_grade = "B";
                                                                                }
                                                                                if ($lcd_grade == 'B' && $digitizer_grade == 'B' && $body_grade == 'B') {
                                                                                    $overall_grade = "B";
                                                                                }
                                                                                if ($lcd_grade == 'C' && $digitizer_grade == 'B' && $body_grade == 'C') {
                                                                                    $overall_grade = "C";
                                                                                }

                                                                                if ($lcd_grade == 'A' && $digitizer_grade == 'C' && $body_grade == 'A') {
                                                                                    $overall_grade = "C";
                                                                                }
                                                                                if ($lcd_grade == 'B' && $digitizer_grade == 'C' && $body_grade == 'B') {
                                                                                    $overall_grade = "C";
                                                                                }
                                                                                if ($lcd_grade == 'C' && $digitizer_grade == 'C' && $body_grade == 'C') {
                                                                                    $overall_grade = "C";
                                                                                }
                                                                            }

                                                                            $sql = "INSERT INTO phone_check_api_data(imei_no, model_name, model_no, make_name, 
                                                                                                                    carrier_name, color_name, battery, body_grade, lcd_grade, digitizer_grade, 
                                                                                                                    `ram`, `memory`, defectsCode, overall_grade, phone_check_api_data, add_date, add_by, add_by_user_id, add_ip)
                                                                                    VALUES	('" . $serial_no_barcode . "', '" . $model_name . "', '" . $model_no . "','" . $make_name . "', 
                                                                                            '" . $carrier_name . "', '" . $color_name . "','" . $battery . "', '" . $body_grade . "', '" . $lcd_grade . "', '" . $digitizer_grade . "', 
                                                                                            '" . $ram . "', '" . $memory . "',  '" . $defectsCode . "',  '" . $overall_grade . "', '" . $jsonData2 . "', 
                                                                                            '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "')";
                                                                            // echo $sql;die;
                                                                            $db->query($conn, $sql);
                                                                            $is_diagnost    = 1;

                                                                            update_po_detail_status($db, $conn, $po_detail_id, $diagnost_status_dynamic);
                                                                            update_po_status($db, $conn, $id, $diagnost_status_dynamic);
                                                                        } else {
                                                                            $inventory_status = '';
                                                                            $status_name = "";
                                                                        }
                                                                    }

                                                                    if ($battery != "" && $battery < '60') {
                                                                        $defectsCode .= ' - Battery Health is less then 60%';
                                                                    }
                                                                    if ($battery > '60' && ($lcd_grade == 'D' || $digitizer_grade == 'D' || $body_grade == 'D')) {
                                                                        $overall_grade = "D";
                                                                    } else if ($battery != "" && $battery >= '70') {
                                                                        if ($lcd_grade == 'A' && $digitizer_grade == 'A' && $body_grade == 'A') {
                                                                            $overall_grade = "A";
                                                                        }
                                                                        if ($lcd_grade == 'A' && $digitizer_grade == 'A' && $body_grade == 'B') {
                                                                            $overall_grade = "B";
                                                                        }
                                                                        if ($lcd_grade == 'A' && $digitizer_grade == 'A' && $body_grade == 'C') {
                                                                            $overall_grade = "C";
                                                                        }

                                                                        if ($lcd_grade == 'B' && $digitizer_grade == 'A' && $body_grade == 'A') {
                                                                            $overall_grade = "B";
                                                                        }
                                                                        if ($lcd_grade == 'B' && $digitizer_grade == 'A' && $body_grade == 'B') {
                                                                            $overall_grade = "B";
                                                                        }
                                                                        if ($lcd_grade == 'B' && $digitizer_grade == 'A' && $body_grade == 'C') {
                                                                            $overall_grade = "C";
                                                                        }

                                                                        if ($lcd_grade == 'C' && $digitizer_grade == 'A' && $body_grade == 'A') {
                                                                            $overall_grade = "C";
                                                                        }
                                                                        if ($lcd_grade == 'C' && $digitizer_grade == 'A' && $body_grade == 'B') {
                                                                            $overall_grade = "C";
                                                                        }
                                                                        if ($lcd_grade == 'C' && $digitizer_grade == 'A' && $body_grade == 'C') {
                                                                            $overall_grade = "C";
                                                                        }

                                                                        if ($lcd_grade == 'A' && $digitizer_grade == 'B' && $body_grade == 'A') {
                                                                            $overall_grade = "B";
                                                                        }
                                                                        if ($lcd_grade == 'B' && $digitizer_grade == 'B' && $body_grade == 'B') {
                                                                            $overall_grade = "B";
                                                                        }
                                                                        if ($lcd_grade == 'C' && $digitizer_grade == 'B' && $body_grade == 'C') {
                                                                            $overall_grade = "C";
                                                                        }

                                                                        if ($lcd_grade == 'A' && $digitizer_grade == 'C' && $body_grade == 'A') {
                                                                            $overall_grade = "C";
                                                                        }
                                                                        if ($lcd_grade == 'A' && $digitizer_grade == 'C' && $body_grade == 'B') {
                                                                            $overall_grade = "C";
                                                                        }
                                                                        if ($lcd_grade == 'A' && $digitizer_grade == 'C' && $body_grade == 'C') {
                                                                            $overall_grade = "C";
                                                                        }
                                                                    } else if ($battery < '70' && $battery >= '60') {
                                                                        if ($lcd_grade == 'A' && $digitizer_grade == 'A' && $body_grade == 'A') {
                                                                            $overall_grade = "B";
                                                                        }
                                                                        if ($lcd_grade == 'B' && $digitizer_grade == 'A' && $body_grade == 'B') {
                                                                            $overall_grade = "B";
                                                                        }
                                                                        if ($lcd_grade == 'C' && $digitizer_grade == 'A' && $body_grade == 'C') {
                                                                            $overall_grade = "C";
                                                                        }

                                                                        if ($lcd_grade == 'A' && $digitizer_grade == 'B' && $body_grade == 'A') {
                                                                            $overall_grade = "B";
                                                                        }
                                                                        if ($lcd_grade == 'B' && $digitizer_grade == 'B' && $body_grade == 'B') {
                                                                            $overall_grade = "B";
                                                                        }
                                                                        if ($lcd_grade == 'C' && $digitizer_grade == 'B' && $body_grade == 'C') {
                                                                            $overall_grade = "C";
                                                                        }

                                                                        if ($lcd_grade == 'A' && $digitizer_grade == 'C' && $body_grade == 'A') {
                                                                            $overall_grade = "C";
                                                                        }
                                                                        if ($lcd_grade == 'B' && $digitizer_grade == 'C' && $body_grade == 'B') {
                                                                            $overall_grade = "C";
                                                                        }
                                                                        if ($lcd_grade == 'C' && $digitizer_grade == 'C' && $body_grade == 'C') {
                                                                            $overall_grade = "C";
                                                                        }
                                                                    }

                                                                    if ($jsonData2 != '[]' && $overall_grade != "") {
                                                                        $sub_product_id_r = $product_uniqueid_main . "-" . $overall_grade;
                                                                    } else {
                                                                        $sub_product_id_r = $product_uniqueid_main;
                                                                    }

                                                                    $sql_c_up    = "UPDATE  purchase_order_detail_receive SET	phone_check_api_data	= '" . $jsonData2 . "',
                                                                                                                                model_name				= '" . $model_name . "',
                                                                                                                                make_name				= '" . $make_name . "',
                                                                                                                                model_no				= '" . $model_no . "',
                                                                                                                                carrier_name			= '" . $carrier_name . "',
                                                                                                                                color_name				= '" . $color_name . "',
                                                                                                                                battery					= '" . $battery . "',
                                                                                                                                body_grade	            = '" . $body_grade . "',
                                                                                                                                lcd_grade				= '" . $lcd_grade . "',
                                                                                                                                digitizer_grade	        = '" . $digitizer_grade . "',
                                                                                                                                ram						= '" . $ram . "',
                                                                                                                                storage					= '" . $memory . "',
                                                                                                                                defects_or_notes		= '" . $defectsCode . "',
                                                                                                                                overall_grade		    = '" . $overall_grade . "', 
                                                                                                                                inventory_status		= '" . $inventory_status . "', 
                                                                                                                                serial_no_barcode		= '" . $serial_no_barcode . "',
                                                                                                                                is_diagnost		        = '" . $is_diagnost . "',

                                                                                                                                update_timezone		    = '" . $timezone . "',
                                                                                                                                update_date			    = '" . $add_date . "',
                                                                                                                                update_by_user_id	    = '" . $_SESSION['user_id'] . "',
                                                                                                                                update_by			    = '" . $_SESSION['username'] . "',
                                                                                                                                update_ip			    = '" . $add_ip . "'
                                                                                WHERE id = '" . $detail_id2 . "' ";
                                                                    // echo "<br><br>" . $sql_c_up;
                                                                    $db->query($conn, $sql_c_up);
                                                                }
                                                                //*/
                                                            } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>">
                                                            <?php if ($battery > '0') {
                                                                echo "Battery: " . $battery . "%<br>";
                                                            } ?>
                                                            <?php if ($memory != '') {
                                                                echo "Storage: " . $memory . "<br>";
                                                            } ?>
                                                            <?php if ($ram != '') {
                                                                echo "RAM: " . $ram . "<br>";
                                                            } ?>
                                                            <?php if ($processor != '') {
                                                                echo "Processor: " . $processor;
                                                            } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>">
                                                            <?php if ($body_grade != '') {
                                                                echo "Body: " . $body_grade . "<br>";
                                                            } ?>
                                                            <?php if ($lcd_grade != '') {
                                                                echo "LCD: " . $lcd_grade . "<br>";
                                                            } ?>
                                                            <?php if ($digitizer_grade != '') {
                                                                echo "Digitizer: " . $digitizer_grade . "<br>";
                                                            } ?>
                                                            <?php
                                                            $color  = "purple";
                                                            if ($count_vebder > 0) {
                                                                $vender_grade = $row_vender[0]['overall_grade'];
                                                                $color  = "red";
                                                                if ($overall_grade == $vender_grade) {
                                                                    $color  = "green";
                                                                } else { ?>
                                                                    <span class="chip orange lighten-5">
                                                                        <span class="orange-text">
                                                                            Vendor Grade: <?php echo $vender_grade; ?></span>
                                                                    </span><br>
                                                                <?php

                                                                }
                                                            }
                                                            if ($overall_grade != "") { ?>
                                                                <span class="chip <?= $color; ?> lighten-5">
                                                                    <span class="<?= $color; ?>-text">
                                                                        Overall Grade: <?php echo $overall_grade; ?></span>
                                                                </span>
                                                            <?php } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>">
                                                            <?php if ($warranty != null || $warranty != '') {
                                                                echo "Warranty: " . $warranty . "<br>";
                                                            } ?>
                                                            <?php if ($order_price != '') {
                                                                echo "Price: " . number_format($order_price, 2) . "<br>";
                                                            } ?>
                                                            <?php if ($defectsCode != '') {
                                                                echo "Defects: " . $defectsCode . "<br>";
                                                            } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>">
                                                            <?php
                                                            $color  = "purple";
                                                            if ($count_vebder > 0) {
                                                                $vender_status = $row_vender[0]['status'];
                                                                $color  = "red";
                                                                if ($status_name == $vender_status) {
                                                                    $color  = "green";
                                                                } else { ?>
                                                                    <span class="chip orange lighten-5">
                                                                        <span class="orange-text">Vendor Status: <?php echo $vender_status; ?></span>
                                                                    </span><br>
                                                                <?php
                                                                }
                                                            }
                                                            if ($status_name != "") { ?>
                                                                <span class="chip <?= $color; ?> lighten-5">
                                                                    <span class="<?= $color; ?>-text"><?php echo $status_name; ?></span>
                                                                </span>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                            <?php
                                                    $i++;
                                                }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php
                        if (po_permisions("Move as Inventory") == 1) { ?>
                            <div class="row">
                                <div class="input-field col m12 s12">
                                    <?php if (isset($id) && $id > 0) { ?>
                                        <button class="mb-6 btn waves-effect waves-light gradient-45deg-purple-deep-orange" type="submit" name="add">Process Diagnostic</button>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col m12 s12"></div>
                            </div>
                        <?php } ?>
                    </form>
                </div>
            <?php }
        } else { ?>
            <div class="card-panel">
                <div class="row">
                    <div class="col 24 s12"><br>
                        <div class="card-alert card red lighten-5">
                            <div class="card-content red-text">
                                <p>Nothing receive yet. </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php }
    } ?>
</div>
<script>
    function autoSubmit2(event) {
        var keycode_value = event.keyCode;
        if (keycode_value === 8 || keycode_value === 37 || keycode_value === 38 || keycode_value === 39 || keycode_value === 40 || keycode_value === 46 || keycode_value === 17 || keycode_value === 16 || keycode_value === 18 || keycode_value === 20 || keycode_value === 110 || (event.ctrlKey && (keycode_value === 65 || keycode_value === 67 || keycode_value === 88 || keycode_value === 88))) {

        } else {
            document.getElementById('barcodeForm2').submit();
        }
    }
</script>