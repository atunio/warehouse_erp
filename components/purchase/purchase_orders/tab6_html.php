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
                <a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=import_to_map_data&id=" . $id) ?>">
                    Import Diagnostic Data
                </a>
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
                    <span class="chip green lighten-5">
                        <span class="green-text">
                            <?php echo $disp_status_name; ?>
                        </span>
                    </span>
                </div>
                <?php
                /*
                <div class="input-field col m4 s12">
                    $entry_type = "diagnostic";  ?>
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
                </div>
                <?php 
                */ ?>
                <input type="hidden" name="d_total_pause_duration" id="d_total_pause_duration" value="0">
            </div>
            <?php
            if (isset($cmd6) &&  $cmd6 == "add" && isset($detail_id) && $detail_id != "") {  ?>
                <div class="row">
                    <div class="input-field col m4 s12">
                        <h6 class="media-heading"><span class=""><?php echo "<b>Tracking / Pro #: </b>" . $detail_id; ?></span></h6>
                    </div>
                </div>
        <?php }
        } ?>
    </div>
    <?php
    if (!isset($id) || (isset($id) && $id == '')) { ?>
        <div class="card-panel custom_padding_card_content_table_top_bottom">
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
        $sql        = " SELECT a.id
                        FROM purchase_order_detail_receive a
                         WHERE a.po_id = '" . $id . "' "; // echo $sql; 
        $result_log     = $db->query($conn, $sql);
        $count_log      = $db->counter($result_log);
        if ($count_log > 0) { ?>
            <form id="barcodeForm2" class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab6") ?>" method="post">
                <input type="hidden" name="is_Submit_tab6_2" value="Y" />
                <input type="hidden" name="cmd6" value="<?php if (isset($cmd6)) echo $cmd6; ?>" />
                <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                    echo encrypt($_SESSION['csrf_session']);
                                                                } ?>">
                <input type="hidden" name="duplication_check_token" value="<?php echo (time() . session_id()); ?>">

                <div class="card-panel custom_padding_card_content_table_top_bottom">
                    <div class="row">
                        <div class="col m8 s12">
                            <h6>Update Serial No from BarCode</h6>
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
                            <div class="input-field col m2 s12">
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
                            <div class="input-field col m2 s12">
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
                                $field_name     = "product_id_barcode_diagnostic";
                                $field_label    = "Product ID";
                                $sql            = " SELECT a.*, c.product_desc, d.category_name, c.product_uniqueid
                                                    FROM purchase_order_detail a 
                                                    INNER JOIN purchase_orders b ON b.id = a.po_id
                                                    INNER JOIN products c ON c.id = a.product_id
                                                    INNER JOIN product_categories d ON d.id = c.product_category
                                                    WHERE 1=1 
                                                    AND a.enabled = 1
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
                                        <option value="">Select</option>
                                        <?php
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
                                                    echo "" . $data_r2['product_uniqueid'];
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
                                        <span class="color-red"><?php
                                                                if (isset($error6[$field_name])) {
                                                                    echo $error6[$field_name];
                                                                } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="input-field col m2 s12">
                                <?php if (isset($id) && $id > 0 && (($cmd6 == 'add' || $cmd6 == '') && access("add_perm") == 1)  || ($cmd6 == 'edit' && access("edit_perm") == 1) || ($cmd6 == 'delete' && access("delete_perm") == 1)) { ?>
                                    <button class="btn waves-effect waves-light gradient-45deg-purple-deep-orange" type="submit" name="add">Submit</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <form id="serialno" class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab6") ?>" method="post">
                <input type="hidden" name="is_Submit_tab6_2_1" id="is_Submit_tab6_2_1" value="Y" />
                <input type="hidden" name="cmd6" value="<?php if (isset($cmd6)) echo $cmd6; ?>" />
                <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                    echo encrypt($_SESSION['csrf_session']);
                                                                } ?>">
                <input type="hidden" name="duplication_check_token" value="<?php echo (time() . session_id()); ?>">

                <div class="card-panel custom_padding_card_content_table_top_bottom">
                    <div class="row">
                        <div class="col m8 s12">
                            <h6>Broken device data</h6>
                        </div>
                        <div class="col m4 s12 show_broken_device_show_btn_tab6" style="<?php if (isset($is_Submit_tab6_2_1) && $is_Submit_tab6_2_1 == 'Y') {
                                                                                            echo "display: none;";
                                                                                        } else {;
                                                                                        } ?>">
                            <a href="javascript:void(0)" class="show_broken_device_section_tab6">Show Form</a>
                        </div>
                        <div class="col m4 s12 show_broken_device_hide_btn_tab6" style="<?php if (isset($is_Submit_tab6_2_1) && $is_Submit_tab6_2_1 == 'Y') {;
                                                                                        } else {
                                                                                            echo "display: none;";
                                                                                        } ?>">
                            <a href="javascript:void(0)" class="hide_broken_device_section_tab6">Hide Form</a>
                        </div>
                    </div>
                    <div id="broken_device_section_tab6" style="<?php if (isset($is_Submit_tab6_2_1) && $is_Submit_tab6_2_1 == 'Y') {;
                                                                } else {
                                                                    echo "display: none;";
                                                                } ?>">
                        <div class="row">
                            <div class="input-field col m12 s12"> </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m6 s12">
                                <?php
                                $field_name     = "product_id_boken_device";
                                $field_label    = "Product ID";
                                $sql            = " SELECT a.*, c.product_desc, d.category_name, c.product_uniqueid
                                                    FROM purchase_order_detail a 
                                                    INNER JOIN purchase_orders b ON b.id = a.po_id
                                                    INNER JOIN products c ON c.id = a.product_id
                                                    INNER JOIN product_categories d ON d.id = c.product_category
                                                    WHERE 1=1 
                                                    AND a.enabled = 1
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
                                        <option value="">Select</option>
                                        <?php
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
                                                    echo "" . $data_r2['product_uniqueid'];
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
                                        <span class="color-red">*<?php
                                                                    if (isset($error6[$field_name])) {
                                                                        echo $error6[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="input-field col m3 s12">
                                <?php
                                $field_name     = "serial_no_boken_device";
                                $field_label    = "Serial No";
                                ?>
                                <i class="material-icons prefix">description</i>
                                <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                    echo ${$field_name};
                                                                                                                } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                            echo ${$field_name . "_valid"};
                                                                                                                                        } ?>">
                                <label for="<?= $field_name; ?>">
                                    <?= $field_label; ?>
                                    <span class="color-red">* <?php
                                                                if (isset($error6[$field_name])) {
                                                                    echo $error6[$field_name];
                                                                } ?>
                                    </span>
                                </label>
                            </div>
                            <div class="input-field col m3 s12">
                                <?php
                                $field_name     = "sub_location_id_boken_device";
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
                        </div> <br>
                        <div class="row">
                            <div class="input-field col m2 s12">
                                <?php
                                $field_name     = "battery_boken_device";
                                $field_label    = "Battery";
                                ?>
                                <i class="material-icons prefix">description</i>
                                <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                    echo ${$field_name};
                                                                                                                } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                            echo ${$field_name . "_valid"};
                                                                                                                                        } ?>">
                                <label for="<?= $field_name; ?>">
                                    <?= $field_label; ?>
                                    <span class="color-red">* <?php
                                                                if (isset($error6[$field_name])) {
                                                                    echo $error6[$field_name];
                                                                } ?>
                                    </span>
                                </label>
                            </div>
                            <div class="input-field col m2 s12">
                                <?php
                                $field_name     = "body_grade_boken_device";
                                $field_label    = "Body Grade";
                                ?>
                                <i class="material-icons prefix">question_answer</i>
                                <div class="select2div">
                                    <select name="<?= $field_name ?>" id="<?= $field_name ?>" class="select2 browser-default">
                                        <option value="">Select</option>
                                        <option value="A" <?php if (isset(${$field_name}) && ${$field_name} == "A") { ?> selected="selected" <?php } ?>>A</option>
                                        <option value="B" <?php if (isset(${$field_name}) && ${$field_name} == "B") { ?> selected="selected" <?php } ?>>B</option>
                                        <option value="C" <?php if (isset(${$field_name}) && ${$field_name} == "C") { ?> selected="selected" <?php } ?>>C</option>
                                        <option value="D" <?php if (isset(${$field_name}) && ${$field_name} == "D") { ?> selected="selected" <?php } ?>>D</option>
                                    </select>
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red">*<?php
                                                                    if (isset($error6[$field_name])) {
                                                                        echo $error6[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="input-field col m2 s12">
                                <?php
                                $field_name     = "lcd_grade_boken_device";
                                $field_label    = "LCD Grade";
                                ?>
                                <i class="material-icons prefix">question_answer</i>
                                <div class="select2div">
                                    <select name="<?= $field_name ?>" id="<?= $field_name ?>" class="select2 browser-default">
                                        <option value="">Select</option>
                                        <option value="A" <?php if (isset(${$field_name}) && ${$field_name} == "A") { ?> selected="selected" <?php } ?>>A</option>
                                        <option value="B" <?php if (isset(${$field_name}) && ${$field_name} == "B") { ?> selected="selected" <?php } ?>>B</option>
                                        <option value="C" <?php if (isset(${$field_name}) && ${$field_name} == "C") { ?> selected="selected" <?php } ?>>C</option>
                                        <option value="D" <?php if (isset(${$field_name}) && ${$field_name} == "D") { ?> selected="selected" <?php } ?>>D</option>
                                    </select>
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red">*<?php
                                                                    if (isset($error6[$field_name])) {
                                                                        echo $error6[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="input-field col m2 s12">
                                <?php
                                $field_name     = "digitizer_grade_boken_device";
                                $field_label    = "Gigitizer Grade";
                                ?>
                                <i class="material-icons prefix">question_answer</i>
                                <div class="select2div">
                                    <select name="<?= $field_name ?>" id="<?= $field_name ?>" class="select2 browser-default">
                                        <option value="">Select</option>
                                        <option value="A" <?php if (isset(${$field_name}) && ${$field_name} == "A") { ?> selected="selected" <?php } ?>>A</option>
                                        <option value="B" <?php if (isset(${$field_name}) && ${$field_name} == "B") { ?> selected="selected" <?php } ?>>B</option>
                                        <option value="C" <?php if (isset(${$field_name}) && ${$field_name} == "C") { ?> selected="selected" <?php } ?>>C</option>
                                        <option value="D" <?php if (isset(${$field_name}) && ${$field_name} == "D") { ?> selected="selected" <?php } ?>>D</option>
                                    </select>
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red">*<?php
                                                                    if (isset($error6[$field_name])) {
                                                                        echo $error6[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="input-field col m2 s12">
                                <?php
                                $field_name     = "overall_grade_boken_device";
                                $field_label    = "Over All Grade";
                                ?>
                                <i class="material-icons prefix">question_answer</i>
                                <div class="select2div">
                                    <select name="<?= $field_name ?>" id="<?= $field_name ?>" class="select2 browser-default">
                                        <option value="">Select</option>
                                        <option value="A" <?php if (isset(${$field_name}) && ${$field_name} == "A") { ?> selected="selected" <?php } ?>>A</option>
                                        <option value="B" <?php if (isset(${$field_name}) && ${$field_name} == "B") { ?> selected="selected" <?php } ?>>B</option>
                                        <option value="C" <?php if (isset(${$field_name}) && ${$field_name} == "C") { ?> selected="selected" <?php } ?>>C</option>
                                        <option value="D" <?php if (isset(${$field_name}) && ${$field_name} == "D") { ?> selected="selected" <?php } ?>>D</option>
                                    </select>
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red">*<?php
                                                                    if (isset($error6[$field_name])) {
                                                                        echo $error6[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="input-field col m2 s12">
                                <?php
                                $field_name     = "inventory_status_boken_device";
                                $field_label    = "Inventory Status";
                                $sql_status     = "SELECT id, status_name
                                                    FROM  inventory_status  
                                                    WHERE enabled = 1
                                                    AND id IN (5,6)
                                                    Order BY id";
                                $result_status  = $db->query($conn, $sql_status);
                                $count_status   = $db->counter($result_status);
                                ?>
                                <i class="material-icons prefix">question_answer</i>
                                <div class="select2div">
                                    <select name="<?= $field_name ?>" id="<?= $field_name ?>" class="select2 browser-default">
                                        <option value="">Select</option>
                                        <?php
                                        if ($count_status > 0) {
                                            $row_status    = $db->fetch($result_status);
                                            foreach ($row_status as $data2) { ?>
                                                <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}[$i - 1]) && ${$field_name}[$i - 1] == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['status_name']; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red">*<?php
                                                                    if (isset($error6[$field_name])) {
                                                                        echo $error6[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m2 s12">
                                <?php
                                $field_name     = "ram_boken_device";
                                $field_label    = "RAM";
                                ?>
                                <i class="material-icons prefix">description</i>
                                <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                    echo ${$field_name};
                                                                                                                } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                            echo ${$field_name . "_valid"};
                                                                                                                                        } ?>">
                                <label for="<?= $field_name; ?>">
                                    <?= $field_label; ?>
                                    <span class="color-red"> <?php
                                                                if (isset($error6[$field_name])) {
                                                                    echo $error6[$field_name];
                                                                } ?>
                                    </span>
                                </label>
                            </div>
                            <div class="input-field col m2 s12">
                                <?php
                                $field_name     = "storage_boken_device";
                                $field_label    = "Storage";
                                ?>
                                <i class="material-icons prefix">description</i>
                                <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                    echo ${$field_name};
                                                                                                                } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                            echo ${$field_name . "_valid"};
                                                                                                                                        } ?>">
                                <label for="<?= $field_name; ?>">
                                    <?= $field_label; ?>
                                    <span class="color-red"> <?php
                                                                if (isset($error6[$field_name])) {
                                                                    echo $error6[$field_name];
                                                                } ?>
                                    </span>
                                </label>
                            </div>
                            <div class="input-field col m2 s12">
                                <?php
                                $field_name     = "processor_boken_device";
                                $field_label    = "Processor";
                                ?>
                                <i class="material-icons prefix">description</i>
                                <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                    echo ${$field_name};
                                                                                                                } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                            echo ${$field_name . "_valid"};
                                                                                                                                        } ?>">
                                <label for="<?= $field_name; ?>">
                                    <?= $field_label; ?>
                                    <span class="color-red"> <?php
                                                                if (isset($error6[$field_name])) {
                                                                    echo $error6[$field_name];
                                                                } ?>
                                    </span>
                                </label>
                            </div>
                            <div class="input-field col m2 s12">
                                <?php
                                $field_name     = "defects_or_notes_boken_device";
                                $field_label    = "Defect Or Notes";
                                ?>
                                <i class="material-icons prefix">description</i>
                                <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                    echo ${$field_name};
                                                                                                                } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                            echo ${$field_name . "_valid"};
                                                                                                                                        } ?>">
                                <label for="<?= $field_name; ?>">
                                    <?= $field_label; ?>
                                    <span class="color-red"> <?php
                                                                if (isset($error6[$field_name])) {
                                                                    echo $error6[$field_name];
                                                                } ?>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m12 s12 text_align_center">
                                <?php if (isset($id) && $id > 0 && (($cmd6 == 'add' || $cmd6 == '') && access("add_perm") == 1)  || ($cmd6 == 'edit' && access("edit_perm") == 1) || ($cmd6 == 'delete' && access("delete_perm") == 1)) { ?>
                                    <button class="btn waves-effect waves-light gradient-45deg-purple-deep-orange" type="submit" name="add">Update</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="card-panel custom_padding_card_content_table_top_bottom">
                <div class="row">
                    <div class="col m8 s12">
                        <h6>Fetch Data from PhoneCheck</h6>
                    </div>
                    <div class="col m4 s12 update_tested_devices_serial_from_phonechecker_show_btn_tab6" style="<?php if (isset($is_Submit_tab6_6) && $is_Submit_tab6_6 == 'Y') {
                                                                                                                    echo "display: none;";
                                                                                                                } else {;
                                                                                                                } ?>">
                        <a href="javascript:void(0)" class="show_update_tested_devices_serial_from_phonechecker_tab6">Show Form</a>
                    </div>
                    <div class="col m4 s12 update_tested_devices_serial_from_phonechecker_hide_btn_tab6" style="<?php if (isset($is_Submit_tab6_6) && $is_Submit_tab6_6 == 'Y') {;
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

                    <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab6") ?>" method="post">
                        <input type="hidden" name="is_Submit_tab6_6" value="Y" />
                        <input type="hidden" name="cmd6" value="<?php if (isset($cmd6)) echo $cmd6; ?>" />
                        <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                            echo encrypt($_SESSION['csrf_session']);
                                                                        } ?>">
                        <input type="hidden" name="duplication_check_token" value="<?php echo (time() . session_id()); ?>">
                        <br>
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
                                                    <?php echo $data_r2['username'];  ?><?php if ($data_r2['full_name'] != "") {
                                                                                            echo " (" . $data_r2['full_name'] . ")";
                                                                                        }  ?>
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
                                $field_label    = "PhoneCheck Diagnostic Date (d/m/Y)";
                                ?>
                                <i class="material-icons prefix">date_range</i>
                                <input id="<?= $field_id; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                echo ${$field_name};
                                                                                                            } else {
                                                                                                                echo date('d/m/Y');
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
                            <div class="input-field col m4 s12">
                                <?php if (isset($id) && $id > 0 && (($cmd6 == 'add' || $cmd6 == '') && access("add_perm") == 1)  || ($cmd6 == 'edit' && access("edit_perm") == 1) || ($cmd6 == 'delete' && access("delete_perm") == 1)) { ?>
                                    <button class="btn waves-effect waves-light gradient-45deg-purple-deep-orange" type="submit" name="add">Fetch Data</button>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m12 s12"></div>
                        </div>
                    </form>
                    <?php
                    $sql_preview = "SELECT a.*, c.product_uniqueid, IFNULL(d.order_price, '') AS  order_price,  IFNULL(d.id, '0') po_detail_id
                                    FROM phone_check_api_data a
                                    LEFT JOIN products c ON c.product_model_no = a.model_no 
                                    LEFT JOIN purchase_order_detail d ON d.product_id = c.id AND d.po_id = a.po_id AND d.enabled = 1
                                    WHERE a.po_id = '" . $id . "' 
                                    AND a.is_processed = 0
                                    ORDER BY c.enabled DESC, d.enabled DESC, a.model_no ";
                    // LEFT JOIN products c ON c.product_model_no = a.model_no AND c.product_uniqueid = a.sku_code
                    $result_preview    = $db->query($conn, $sql_preview);
                    $count_preview        = $db->counter($result_preview);
                    if ($count_preview > 0) {
                        $row_preview = $db->fetch($result_preview); ?>
                        <form method="post" autocomplete="off" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab6") ?>" method="post">
                            <input type="hidden" name="is_Submit2_preview" value="Y" />
                            <div id="Form-advance2" class="card card card-default scrollspy custom_margin_card_table_top custom_margin_card_table_bottom">
                                <div class="card-content custom_padding_card_content_table_top">
                                    <h6 class="card-title">Preview Fetched Data</h6><br>
                                    <div class="row">
                                        <table id="page-length-option1" class="display bordered striped addproducttable">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">S.No</th>
                                                    <th style="text-align: center;">
                                                        <label>
                                                            <input type="checkbox" id="all_checked" class="filled-in" name="all_checked" value="1" <?php if (isset($all_checked) && $all_checked == '1') {
                                                                                                                                                        echo "checked";
                                                                                                                                                    } ?> />
                                                            <span></span>
                                                        </label>
                                                    </th>
                                                    <th style="text-align: center;">Serial#</th>
                                                    <th>PO Product ID</th>
                                                    <th>Diagnostic Product ID</th>
                                                    <th>Diagnostic Model#</th>
                                                    <th>Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 0;
                                                foreach ($row_preview as $data) {
                                                    $phone_check_product_id = $data['product_uniqueid'];
                                                    $phone_check_model_no   = $data['model_no'];
                                                    $po_id                  = $data['po_id'];
                                                    $po_detail_id           = $data['po_detail_id'];
                                                    $product_item_price     = $data['order_price'];
                                                    $bulkserialNo[]         = $data['imei_no'];
                                                    $phone_check_api_data   = $data['phone_check_api_data'];
                                                    if (isset($phone_check_api_data) && $phone_check_api_data != null && $phone_check_api_data != '') {
                                                        $checked = "";
                                                        if ($po_detail_id > 0) {
                                                            $checked = "checked";
                                                        } ?>
                                                        <tr>
                                                            <td style="width:100px; text-align: center;"><?php echo $i + 1; ?></td>
                                                            <td style="width:80px; text-align: center;">
                                                                <?php
                                                                if (access("delete_perm") == 1) { ?>
                                                                    <label>
                                                                        <input type="checkbox" name="bulkserialNo[]" id="bulkserialNo[]" value="<?= $data['imei_no']; ?>" <?php if (isset($bulkserialNo) && in_array($data['imei_no'], $bulkserialNo)) {
                                                                                                                                                                                echo $checked;
                                                                                                                                                                            } ?> class="checkbox filled-in" />
                                                                        <span></span>
                                                                    </label>
                                                                <?php } ?>
                                                            </td>
                                                            <td style="width:150px;"><?php echo $data['imei_no']; ?></td>
                                                            <td>
                                                                <?php
                                                                if ($po_detail_id > 0) { ?>
                                                                    <select name="product_ids[<?= $data['imei_no']; ?>]" id="fetched_productids_<?php echo $i; ?>" class="">
                                                                        <option value="<?php echo $phone_check_product_id; ?>">ProductID: <?php echo $phone_check_product_id; ?>, Model#: <?php echo $phone_check_model_no; ?></option>
                                                                    </select>
                                                                <?php } else { ?>
                                                                    <select name="product_ids[<?= $data['imei_no']; ?>]" id="fetched_productids_<?php echo $i; ?>" class="select2 browser-default select2-hidden-accessible ">
                                                                        <option value="">Select</option>
                                                                        <?php
                                                                        $sql_pd03       = "	SELECT c.id, c.product_uniqueid, c.product_model_no
                                                                                            FROM  products c 
                                                                                            WHERE 1=1 AND c.enabled = 1 ";
                                                                        $result_pd03    = $db->query($conn, $sql_pd03);
                                                                        $count_pd03     = $db->counter($result_pd03);
                                                                        if ($count_pd03 > 0) {
                                                                            $row_pd03 = $db->fetch($result_pd03);
                                                                            foreach ($row_pd03 as $data_pd03) { ?>
                                                                                <option value="<?php echo $data_pd03['product_uniqueid']; ?>" <?php if ($phone_check_model_no == $data_pd03['product_model_no']) {
                                                                                                                                                    echo " selected ";
                                                                                                                                                } ?>>ProductID: <?php echo $data_pd03['product_uniqueid']; ?>, Model#: <?php echo $data_pd03['product_model_no']; ?></option>
                                                                        <?php }
                                                                        } ?>
                                                                    </select>
                                                                <?php }
                                                                $i++;
                                                                ?>
                                                            </td>
                                                            <td><?php echo $phone_check_product_id; ?></td>
                                                            <td>
                                                                <input type="hidden" name="model_nos[<?= $data['imei_no']; ?>]" value="<?php echo $phone_check_model_no; ?>">
                                                                <?php echo $phone_check_model_no; ?>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input type="text" name="prices[<?= $data['imei_no']; ?>]" id="prices_<?php echo $i; ?>" value=" <?php if ($product_item_price != "") echo number_format($product_item_price, 2); ?>" />
                                                                    <span></span>
                                                                </label>

                                                            </td>
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div><br><br>
                                    <div class="row">
                                        <div class="input-field col m2 s12">
                                            <?php if (($cmd == 'add' && access("add_perm") == 1)  || ($cmd == 'edit' && access("edit_perm") == 1)) { ?>
                                                <button class="btn cyan waves-effect waves-light right" type="submit" name="action" value="update_info">Process
                                                    <i class="material-icons right">send</i>
                                                </button>
                                            <?php } ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                    <?php } ?>
                </div>
            </div>
            <form id="barcodeForm2_1" class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab6") ?>" method="post">
                <input type="hidden" name="is_Submit_tab6_2_3" value="Y" />
                <input type="hidden" name="cmd6" value="<?php if (isset($cmd6)) echo $cmd6; ?>" />
                <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                    echo encrypt($_SESSION['csrf_session']);
                                                                } ?>">
                <input type="hidden" name="duplication_check_token" value="<?php echo (time() . session_id()); ?>">

                <div class="card-panel custom_padding_card_content_table_top_bottom">
                    <div class="row">
                        <div class="col m8 s12">
                            <h6>Process Fetched Data</h6>
                        </div>
                        <div class="col m4 s12 show_receive_from_barcode_show_btn_tab6_2" style="<?php if (isset($is_Submit_tab6_2_3) && $is_Submit_tab6_2_3 == 'Y') {
                                                                                                        echo "display: none;";
                                                                                                    } else {;
                                                                                                    } ?>">
                            <a href="javascript:void(0)" class="show_receive_from_barcode_section_tab6_2">Show Form</a>
                        </div>
                        <div class="col m4 s12 show_receive_from_barcode_hide_btn_tab6_2" style="<?php if (isset($is_Submit_tab6_2_3) && $is_Submit_tab6_2_3 == 'Y') {;
                                                                                                    } else {
                                                                                                        echo "display: none;";
                                                                                                    } ?>">
                            <a href="javascript:void(0)" class="hide_receive_from_barcode_section_tab6_2">Hide Form</a>
                        </div>
                    </div>
                    <div id="receive_from_barcode_section_tab6_2" style="<?php if (isset($is_Submit_tab6_2_3) && $is_Submit_tab6_2_3 == 'Y') {;
                                                                            } else {
                                                                                echo "display: none;";
                                                                            } ?>">
                        <div class="row">
                            <div class="input-field col m12 s12"> </div>
                        </div>
                        <div class="row">

                            <div class="input-field col m7 s12">
                                <?php
                                $field_name     = "diagnostic_fetch_id";
                                $field_label    = "Product";
                                $sql            = " SELECT * FROM (
                                                        SELECT a.id, a.serial_no, a.model_no, c.product_desc, c.product_uniqueid, d.category_name 
                                                        FROM purchase_order_detail_receive_diagnostic_fetch a 
                                                        INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
                                                        INNER JOIN products c ON c.id = b.product_id
                                                        LEFT JOIN product_categories d ON d.id = c.product_category
                                                        WHERE a.po_id = '" . $id . "'
                                                        AND a.is_processed = 0
                                                        AND a.enabled = 1 
                                                        AND b.enabled = 1

                                                        UNION ALL 

                                                        SELECT a.id, a.serial_no, a.model_no, c.product_desc, c.product_uniqueid, d.category_name 
                                                        FROM purchase_order_detail_receive_diagnostic_fetch a 
                                                        INNER JOIN products c ON c.id = a.product_id_not_in_po
                                                        LEFT JOIN product_categories d ON d.id = c.product_category
                                                        WHERE a.po_id = '" . $id . "'
                                                        AND a.enabled = 1
                                                        AND a.is_processed = 0
                                                    ) AS t1
                                                    ORDER BY product_uniqueid, serial_no ";
                                // echo $sql; 
                                $result_log2    = $db->query($conn, $sql);
                                $count_r2       = $db->counter($result_log2); ?>
                                <i class="material-icons prefix pt-1">add_shopping_cart</i>
                                <div class="select2div">
                                    <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                                                                    } ?>">
                                        <option value="">Select</option>
                                        <?php
                                        if ($count_r2 > 0) {
                                            $row_r2    = $db->fetch($result_log2);
                                            foreach ($row_r2 as $data_r2) {
                                                $detail_id_r1       = $data_r2['id'];
                                                $sql_rc1            = " SELECT a.* 
                                                                        FROM purchase_order_detail_receive a 
                                                                        WHERE 1=1 
                                                                        AND a.po_detail_id = '" . $detail_id_r1 . "'
                                                                        AND a.enabled = 1 "; //echo $sql_cl;
                                                $result_rc1         = $db->query($conn, $sql_rc1);
                                                $total_received_qty = $db->counter($result_rc1);  ?>
                                                <option value="<?php echo $data_r2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data_r2['id']) { ?> selected="selected" <?php } ?>>
                                                    <?php
                                                    echo "Product: " . $data_r2['product_desc'] . " (" . $data_r2['product_uniqueid'] . ") - ";
                                                    if ($data_r2['category_name'] != "") {
                                                        echo " (" . $data_r2['category_name'] . ") ";
                                                    }
                                                    echo " - Serial#: " . $data_r2['serial_no'] . ", Model#: " . $data_r2['model_no']; ?>
                                                </option>
                                        <?php
                                            }
                                        } ?>
                                    </select>
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red"> * <?php
                                                                    if (isset($error6[$field_name])) {
                                                                        echo $error6[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="input-field col m3 s12">
                                <?php
                                $field_name     = "sub_location_id_fetched";
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
                            <div class="input-field col m2 s12">
                                <?php if (isset($id) && $id > 0 && (($cmd6 == 'add' || $cmd6 == '') && access("add_perm") == 1)  || ($cmd6 == 'edit' && access("edit_perm") == 1) || ($cmd6 == 'delete' && access("delete_perm") == 1)) { ?>
                                    <button class="btn waves-effect waves-light gradient-45deg-purple-deep-orange" type="submit" name="add">Submit</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php
            $td_padding = "padding:5px 10px !important;";
            $sql            = " SELECT * FROM (
                                    SELECT 'ProductReceived' as record_type, '1' as total_qty_received, 
                                            a.*,  c.product_uniqueid, c.product_desc, d.category_name, 
                                            e.first_name, e.middle_name, e.last_name, e.username, g.sub_location_name, g.sub_location_type, 
                                            i.sub_location_name as sub_location_name_after_diagnostic, i.sub_location_type AS sub_location_type_after_diagnostic,
                                            h.status_name, c.product_category 
                                    FROM purchase_order_detail_receive a
                                    INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
                                    INNER JOIN purchase_orders b1 ON b1.id = b.po_id
                                    INNER JOIN products c ON c.id = b.product_id
                                    LEFT JOIN product_categories d ON d.id =c.product_category
                                    LEFT JOIN users e ON e.id = a.add_by_user_id
                                    LEFT JOIN warehouse_sub_locations g ON g.id = a.sub_location_id
                                    LEFT JOIN inventory_status h ON h.id = a.inventory_status
                                    LEFT JOIN warehouse_sub_locations i ON i.id = a.sub_location_id_after_diagnostic
                                    WHERE a.enabled = 1
                                    AND b.enabled = 1
                                    AND b.po_id = '" . $id . "'
                                    AND (a.recevied_product_category = 0 || a.recevied_product_category IS NULL || a.serial_no_barcode IS NOT NULL)

                                    UNION ALL

                                    SELECT 'CateogryReceived' AS record_type, COUNT(a.id) AS total_qty_received, 
                                        a.*, '' AS product_uniqueid, '' AS product_desc, d.category_name, 
                                        e.first_name, e.middle_name, e.last_name, e.username, g.sub_location_name, g.sub_location_type, 
                                        i.sub_location_name as sub_location_name_after_diagnostic, i.sub_location_type AS sub_location_type_after_diagnostic,
                                        h.status_name, a.recevied_product_category as product_category
                                    FROM purchase_order_detail_receive a 
                                    INNER JOIN purchase_orders b1 ON b1.id = a.po_id
                                    INNER JOIN product_categories d ON d.id = a.recevied_product_category  
                                    LEFT JOIN users e ON e.id = a.add_by_user_id
                                    LEFT JOIN warehouse_sub_locations g ON g.id = a.sub_location_id
                                    LEFT JOIN inventory_status h ON h.id = a.inventory_status
                                    LEFT JOIN warehouse_sub_locations i ON i.id = a.sub_location_id_after_diagnostic
                                    WHERE a.po_id = '" . $id . "'
                                    AND (a.serial_no_barcode = '' || a.serial_no_barcode IS NULL)
                                    GROUP BY a.recevied_product_category

                                    UNION ALL 

                                    SELECT 'ProductReceived' as record_type, '1' as total_qty_received, 
                                            a.*,  c.product_uniqueid, c.product_desc, d.category_name, 
                                            e.first_name, e.middle_name, e.last_name, e.username, g.sub_location_name, g.sub_location_type, 
                                            i.sub_location_name as sub_location_name_after_diagnostic, i.sub_location_type AS sub_location_type_after_diagnostic,
                                            h.status_name, c.product_category 
                                    FROM purchase_order_detail_receive a
                                    INNER JOIN purchase_orders b1 ON b1.id = a.po_id
                                    INNER JOIN products c ON c.id = a.product_id
                                    LEFT JOIN product_categories d ON d.id =c.product_category
                                    LEFT JOIN users e ON e.id = a.add_by_user_id
                                    LEFT JOIN warehouse_sub_locations g ON g.id = a.sub_location_id
                                    LEFT JOIN inventory_status h ON h.id = a.inventory_status
                                    LEFT JOIN warehouse_sub_locations i ON i.id = a.sub_location_id_after_diagnostic
                                    WHERE a.enabled = 1
                                    AND a.po_id = '" . $id . "' 
                                ) AS t1
                                ORDER BY record_type, product_category, sub_location_id, serial_no_barcode ";
            $result_log     = $db->query($conn, $sql);
            $count_log      = $db->counter($result_log);
            if ($count_log > 0) { ?>
                <div class="card-panel custom_padding_card_content_table_top_bottom">
                    <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab6") ?>" method="post">
                        <input type="hidden" name="is_Submit_tab6_7" value="Y" />
                        <input type="hidden" name="cmd6" value="<?php if (isset($cmd6)) echo $cmd6; ?>" />
                        <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                            echo encrypt($_SESSION['csrf_session']);
                                                                        } ?>">
                        <input type="hidden" name="duplication_check_token" value="<?php echo (time() . session_id()); ?>">

                        <div class="row">
                            <div class="col m3 s12">
                                <h5>Detail</h5>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            if (po_permisions("Move as Inventory") == 1) { ?>
                                <div class="col m2 s12">
                                    <label>
                                        <input type="checkbox" id="all_checked7" class="filled-in" name="all_checked7" value="1" <?php if (isset($all_checked7) && $all_checked7 == '1') {
                                                                                                                                        echo "checked";
                                                                                                                                    } ?> />
                                        <span></span>
                                    </label>
                                </div>
                            <?php } 
                            if (po_permisions("DiagnosticExport") == '1') {?>
                                <div class="col m2 s12">
                                    <a href="export/export_po_received_items.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" target="_blank" class="waves-effect waves-light  btn gradient-45deg-light-blue-cyan box-shadow-none border-round mr-1 mb-12">Export</a>
                                </div>
                            <?php }?>
                            <div class="col m8 s12">
                                <div class="text_align_right">
                                    <?php 
                                    $table_columns	= array('SNo','-', 'Product Detail', 'Qty', 'Serial No', 'Specification', 'Grading', 'Defects', 'Inventory Status');
                                    $k 				= 0;
                                    foreach($table_columns as $data_c1){?>
                                        <label>
                                            <input type="checkbox" value="<?= $k?>" name="table_columns[]" class="filled-in toggle-column" data-column="<?= set_table_headings($data_c1)?>" checked="checked">
                                            <span><?= $data_c1?></span>
                                        </label>&nbsp;&nbsp;
                                    <?php 
                                        $k++;
                                    }?> 
                                </div>
                            </div>
                        </div>
                        <div class="section section-data-tables">
                            <div class="row">
                                <div class="col m12 s12">
                                    <table id="page-length-option" class="display pagelength50_2 dataTable dtr-inline">
                                        <thead>
                                            <tr>
                                            <?php
                                                $headings = "";
                                                foreach($table_columns as $data_c){
                                                    if($data_c == 'SNo'){
                                                        $headings .= '<th class="sno_width_60 col-'.set_table_headings($data_c).'">'.$data_c.'</th>';
                                                    }
                                                    else if($data_c == '-'){
                                                        $headings .= '<th class="sno_width_60 col-'.set_table_headings($data_c).'">'.$data_c.'</th>';
                                                    }
                                                    else{
                                                        $headings .= '<th class="col-'.set_table_headings($data_c).'">'.$data_c.'</th> ';
                                                    }
                                                } 
                                                echo $headings;
                                                $headings2 = ' '; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = $checkbox_del = 0;
                                            if ($count_log > 0) {
                                                $row_cl1 = $db->fetch($result_log);
                                                foreach ($row_cl1 as $data) {
                                                    $detail_id2                 = $data['id'];
                                                    $po_detail_id               = $data['po_detail_id'];
                                                    $total_qty_received         = $data['total_qty_received'];
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
                                                    $price                = $data['price'];
                                                    $is_diagnost                = $data['is_diagnost'];
                                                    $is_import_diagnostic_data  = $data['is_import_diagnostic_data'];
                                                    $is_rma_processed           = $data['is_rma_processed'];
                                                    $edit_lock                  = $data['edit_lock']; ?>
                                                    <tr>
                                                        <td style="<?= $td_padding; ?>; text-align: center;" class="col-<?= set_table_headings($table_columns[0]);?>"><?php echo $i + 1; ?></td>
                                                        <td style="<?= $td_padding; ?>; text-align: center;" class="col-<?= set_table_headings($table_columns[1]);?>">
                                                            <?php
                                                            if ($serial_no_barcode != "" && $serial_no_barcode != null && po_permisions("Move as Inventory") == 1 && $edit_lock == "0" && $is_diagnost == "1") {
                                                                $checkbox_del++; ?>
                                                                <label>
                                                                    <input type="checkbox" name="ids_for_stock[]" id="ids_for_stock[]" value="<?= $detail_id2; ?>" class="checkbox7 filled-in" />
                                                                    <span></span>
                                                                </label>
                                                            <?php } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[2]);?>">
                                                            <?php
                                                            if ($product_uniqueid_main != "" && $product_uniqueid_main != null) {
                                                                echo $product_uniqueid_main . "<br>";
                                                            }
                                                            echo $data['product_desc'];
                                                            if ($data['category_name'] != "") {
                                                                echo " (" . $data['category_name'] . ")";
                                                            } ?>
                                                            <br>
                                                            <?php
                                                            if ($data['sub_location_name_after_diagnostic'] != "") {
                                                                echo " Location: " . $data['sub_location_name_after_diagnostic'];
                                                            } ?>
                                                            <?php
                                                            if ($data['sub_location_type_after_diagnostic'] != "") {
                                                                echo " (" . $data['sub_location_type_after_diagnostic'] . ")";
                                                            } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[3]);?>"><?php echo "" . $data['total_qty_received']; ?></td>
                                                        <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[4]);?>">
                                                            <?php
                                                            $color          = "purple";
                                                            $sql            = " SELECT a.* FROM vender_po_data a 
                                                                                WHERE a.enabled = 1 
                                                                                AND a.serial_no = '" . $serial_no_barcode . "' 
                                                                                AND a.po_id = '" . $id . "'  ";
                                                            $result_vebder  = $db->query($conn, $sql);
                                                            $count_vebder   = $db->counter($result_vebder);
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
                                                            <?php
                                                                if ($is_import_diagnostic_data == '0' && ($data['phone_check_api_data'] == NULL || $data['phone_check_api_data'] == "[]" || $data['phone_check_api_data'] == "" || $data['phone_check_api_data'] == '(NULL)' || $data['phone_check_api_data'] == '{"msg":"Failed to get device info results"}')) {
                                                                    $model_name = $model_no = $make_name = $carrier_name = $color_name = $battery = $body_grade = $lcd_grade = $digitizer_grade = $etching = $ram = $memory = $defectsCode = $overall_grade = $sku_code = "";
                                                                    $sql_pd01_4         = "	SELECT  a.*
                                                                                            FROM phone_check_api_data a 
                                                                                            WHERE a.enabled = 1 
                                                                                            AND a.imei_no = '" . $serial_no_barcode . "'
                                                                                            ORDER BY a.id DESC LIMIT 1";
                                                                    $result_pd01_4    = $db->query($conn, $sql_pd01_4);
                                                                    $count_pd01_4    = $db->counter($result_pd01_4);
                                                                    if ($count_pd01_4 > 0) {
                                                                        $row_pd01_4 = $db->fetch($result_pd01_4);
                                                                        include("db_phone_check_api_data.php");
                                                                    } else {
                                                                        $device_detail_array    = getinfo_phonecheck_imie($serial_no_barcode);
                                                                        $jsonData2              = json_encode($device_detail_array);
                                                                        if ($jsonData2 != '[]' && $jsonData2 != 'null' && $jsonData2 != null && $jsonData2 != '' && $jsonData2 != '{"msg":"token expired"}') {
                                                                            include("process_phonecheck_response.php");
                                                                            $is_diagnost = 1;

                                                                            update_po_detail_status($db, $conn, $po_detail_id, $diagnost_status_dynamic);
                                                                            update_po_status($db, $conn, $id, $diagnost_status_dynamic);
                                                                        } else {
                                                                            $inventory_status = $status_name = $jsonData2 = "";
                                                                        }
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
                                                                                                                                etching	                = '" . $etching . "',
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
                                                            } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[5]);?>">
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
                                                        <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[6]);?>">
                                                            <?php
                                                            if ($lcd_grade != '') {
                                                                echo "LCD: " . $lcd_grade . ", ";
                                                            }
                                                            if ($digitizer_grade != '') {
                                                                echo "Digitizer: " . $digitizer_grade . ", ";
                                                            }
                                                            if ($body_grade != '') {
                                                                echo "Body: " . $body_grade . "";
                                                            } ?>
                                                            <?php
                                                            $color  = "purple";
                                                            if ($count_vebder > 0) {
                                                                $vender_grade = $row_vender[0]['overall_grade'];
                                                                $color  = "red";
                                                                if ($overall_grade == $vender_grade) {
                                                                    $color  = "green";
                                                                } else { ?>
                                                                    <br>
                                                                    <span class="chip orange lighten-5">
                                                                        <span class="orange-text">
                                                                            Vendor Grade: <?php echo $vender_grade; ?></span>
                                                                    </span><br>
                                                                <?php
                                                                }
                                                            }
                                                            if ($overall_grade != "") { ?><br>
                                                                <span class="chip <?= $color; ?> lighten-5">
                                                                    <span class="<?= $color; ?>-text">
                                                                        Overall Grade: <?php echo $overall_grade; ?></span>
                                                                </span>
                                                            <?php } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[7]);?>">
                                                            <?php
                                                            // if ($price != '') { echo "Price: " . number_format($price, 2) . "<br>"; }
                                                            if ($defectsCode != '') {
                                                                echo "Defects: " . $defectsCode . "<br>";
                                                            } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[8]);?>">
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
                        if (po_permisions("Move as Inventory") == 1 && $checkbox_del > 0) { ?>
                            <div class="row">
                                <div class="input-field col m12 s12 text_align_center">
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
            <div class="card-panel custom_padding_card_content_table_top_bottom">
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

    function autoSubmit2_1(event) {
        var keycode_value = event.keyCode;
        if (keycode_value === 8 || keycode_value === 37 || keycode_value === 38 || keycode_value === 39 || keycode_value === 40 || keycode_value === 46 || keycode_value === 17 || keycode_value === 16 || keycode_value === 18 || keycode_value === 20 || keycode_value === 110 || (event.ctrlKey && (keycode_value === 65 || keycode_value === 67 || keycode_value === 88 || keycode_value === 88))) {

        } else {
            document.getElementById('barcodeForm2_1').submit();
        }
    }
</script>