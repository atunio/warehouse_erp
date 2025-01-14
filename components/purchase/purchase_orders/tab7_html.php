<div id="tab7_html" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab7')) {
                                        echo "block";
                                    } else {
                                        echo "none";
                                    } ?>;">
    <div class="card-panel" style="padding-top: 5px; padding-bottom: 5px; margin-top: 0px; margin-bottom: 5px;">
        <div class="row">
            <div class="input-field col m6 s12" style="margin-top: 3px; margin-bottom: 3px;">
                <h6 class="media-heading">
                    <?= $general_heading; ?> => RMA
                </h6>
            </div>
            <div class="input-field col m6 s12" style="text-align: right; margin-top: 3px; margin-bottom: 3px;">
                <a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=import_rma_data&id=" . $id) ?>">
                    Import & RMA Data
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
                    <h6 class="media-heading"><span class=""><?php echo "<b>Vender Invoice#: </b>" . $vender_invoice_no; ?></span></h6>
                </div>
            </div>
        <?php }  ?>
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
        $td_padding     = "padding:5px 15px !important;";
        $sql            = " SELECT a.id
                            FROM purchase_order_detail_receive a 
                            INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
                            WHERE a.enabled = 1 
                            AND a.is_diagnost = 1
                            AND b.po_id = '" . $id . "' ";
        // echo $sql; 
        $result_log     = $db->query($conn, $sql);
        $count_log      = $db->counter($result_log);
        if ($count_log > 0) {   ?>
            <?php //*/ 
            $td_padding = "padding:5px 10px !important;";
            $sql        = " SELECT * FROM(
                                SELECT  '1' as rec_sort, a.id, a.logistic_id, a.po_detail_id, a.edit_lock, a.serial_no_barcode,  a.base_product_id,  
                                        a.sub_product_id, a.model_name, a.model_no, a.make_name, a.carrier_name, a.color_name, a.battery, a.body_grade, 
                                        a.lcd_grade,  a.digitizer_grade,  a.overall_grade, a.ram, a.storage, a.processor, a.warranty, a.price, 
                                        a.defects_or_notes,  a.inventory_status,  a.sku_code,  a.phone_check_api_data,  a.is_diagnost, a.is_rma_processed, a.is_rma_added, a.enabled,
                                        c.product_desc, d.category_name, 
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
                                INNER JOIN product_stock j ON j.receive_id = a.id
                                WHERE a.enabled = 1 
                                AND b.po_id = '" . $id . "'
                                AND a.inventory_status != '" . $tested_or_graded_status . "' 

                                UNION ALL 

                                SELECT  '1' as rec_sort, a.id, a.logistic_id, a.po_detail_id, a.edit_lock, a.serial_no_barcode,  a.base_product_id,  
                                        a.sub_product_id, a.model_name, a.model_no, a.make_name, a.carrier_name, a.color_name, a.battery, a.body_grade, 
                                        a.lcd_grade,  a.digitizer_grade,  a.overall_grade, a.ram, a.storage, a.processor, a.warranty, a.price, 
                                        a.defects_or_notes,  a.inventory_status,  a.sku_code,  a.phone_check_api_data,  a.is_diagnost, a.is_rma_processed, a.is_rma_added, a.enabled,
                                         
                                        c.product_desc, d.category_name, 
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
                                INNER JOIN product_stock j ON j.receive_id = a.id
                                LEFT JOIN vender_po_data k ON k.serial_no = a.serial_no_barcode
                                WHERE a.enabled = 1 
                                AND b.po_id = '" . $id . "'
                                AND a.inventory_status = '" . $tested_or_graded_status . "'
                                AND k.id IS NULL  

                                UNION ALL
                                
                                SELECT 
                                    '2' as rec_sort, '0' AS id, '0' AS logistic_id, '0' AS po_detail_id, '0' AS edit_lock, a.serial_no AS serial_no_barcode,  a.product_uniqueid AS base_product_id,
                                    '' AS sub_product_id, '' AS model_name, '' AS model_no, '' AS make_name, '' AS carrier_name, '' AS color_name, a.battery, '' AS body_grade, 
                                    '' AS lcd_grade,  '' AS digitizer_grade,  a.overall_grade, a.memory AS ram, a.storage, a.processor, '' AS warranty, a.price, 
                                    a.defects_or_notes,  a.status AS inventory_status,  '' AS sku_code,  '' AS phone_check_api_data,  '0' AS is_diagnost,  '0' AS is_rma_processed, '0' AS is_rma_added, '1' AS enabled,
                                    
                                    '' AS product_desc, a.product_category AS category_name, 
                                    '' AS first_name, '' AS middle_name, '' AS last_name, '' AS username, '' AS tracking_no, '' AS sub_location_name, 
                                    '' AS sub_location_name_after_diagnostic, 
                                    a.price AS order_price,  a.status AS status_name, a.product_uniqueid
                                FROM vender_po_data a
                                WHERE po_id = '" . $id . "'
                                AND serial_no NOT IN(
                                    SELECT a.serial_no_barcode 
                                    FROM purchase_order_detail_receive a 
                                    INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
                                    WHERE b.po_id = '" . $id . "'
                                    AND is_diagnost = 1
                                )
                            ) AS t1
                            ORDER BY rec_sort, is_rma_processed DESC, is_rma_added DESC, base_product_id, serial_no_barcode DESC ";
            // echo $sql;
            $result_log = $db->query($conn, $sql);
            $count_log  = $db->counter($result_log);
            if ($count_log > 0) { ?>
                <div class="card-panel">
                    <div class="row">
                        <div class="col m4 s12">
                            <h5>Reconcile</h5>
                        </div>
                        <div class="col m12 s12 text_align_center">
                            <a href="export/export_po_data_for_reconcile.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" class="waves-effect waves-light  btn gradient-45deg-light-blue-cyan box-shadow-none border-round mr-1 mb-1">Export in Reconcile</a>
                        </div>
                    </div>
                    <div class="row">
                        <table class="bordered">
                            <tr>
                                <th>Qty PO vs Received</th>
                                <th>A Grade (Expected vs Tested)</th>
                                <th>B Grade (Expected vs Tested)</th>
                                <th>C Grade (Expected vs Tested)</th>
                                <th>Defective (Expected vs Tested)</th>
                            </tr>
                            <?php
                            $sql        = " SELECT (
                                                SELECT SUM(b.order_qty)
                                                    FROM purchase_order_detail b 
                                                    WHERE b.po_id = a.id
                                                ) AS total_ordered,
                                                COUNT(DISTINCT c.id) AS received,
                                                COUNT(DISTINCT f1.id) AS expected_a_grade,
                                                COUNT(DISTINCT f2.id) AS expected_b_grade,
                                                COUNT(DISTINCT f3.id) AS expected_c_grade,
                                                COUNT(DISTINCT f4.id) AS expected_defected,

                                                COUNT(DISTINCT g.id) AS tested_defected,
                                                
                                                COUNT(DISTINCT i.id) AS tested_a_grade,
                                                COUNT(DISTINCT j.id) AS tested_b_grade,
                                                COUNT(DISTINCT k.id) AS tested_c_grade

                                                FROM purchase_orders a
                                                LEFT JOIN purchase_order_detail b ON b.po_id = a.id
                                                LEFT JOIN purchase_order_detail_receive c ON c.po_detail_id = b.id
                                                LEFT JOIN purchase_order_detail_receive g ON g.id = c.id AND g.inventory_status = 6 
                                                LEFT JOIN vender_po_data f1 ON f1.po_id = a.id AND (f1.overall_grade = 'A' || f1.overall_grade = 'A Grade')
                                                LEFT JOIN vender_po_data f2 ON f2.po_id = a.id AND (f2.overall_grade = 'B' || f2.overall_grade = 'B Grade')
                                                LEFT JOIN vender_po_data f3 ON f3.po_id = a.id AND (f3.overall_grade = 'C' || f3.overall_grade = 'C Grade')
                                                LEFT JOIN vender_po_data f4 ON f4.po_id = a.id AND f4.`status` = 'Defective'

                                                LEFT JOIN purchase_order_detail_receive i ON i.id = c.id AND i.overall_grade = 'A' 
                                                LEFT JOIN purchase_order_detail_receive j ON j.id = c.id AND j.overall_grade = 'B' 
                                                LEFT JOIN purchase_order_detail_receive k ON k.id = c.id AND k.overall_grade = 'C' 

                                                WHERE a.id = '" . $id . "' ";
                            $result_t1  = $db->query($conn, $sql);
                            $count_t1   = $db->counter($result_t1);
                            if ($count_t1 > 0) {
                                $row_t1 = $db->fetch($result_t1);
                                foreach ($row_t1 as $data_t1) { ?>
                                    <tr>
                                        <td>
                                            <span class="chip green lighten-5">
                                                <span class="green-text"><?php echo $data_t1['total_ordered']; ?></span>
                                            </span> vs &nbsp;
                                            <?php
                                            if ($data_t1['total_ordered'] != $data_t1['received']) { ?>
                                                <span class="chip red lighten-5">
                                                    <span class="red-text"><?php echo $data_t1['received']; ?></span>
                                                </span>
                                            <?php } else { ?>
                                                <span class="chip green lighten-5">
                                                    <span class="green-text"><?php echo $data_t1['received']; ?></span>
                                                </span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <span class="chip green lighten-5">
                                                <span class="green-text"><?php echo $data_t1['expected_a_grade']; ?></span>
                                            </span> vs &nbsp;
                                            <?php
                                            if ($data_t1['expected_a_grade'] != $data_t1['tested_a_grade']) { ?>
                                                <span class="chip red lighten-5">
                                                    <span class="red-text"><?php echo $data_t1['tested_a_grade']; ?></span>
                                                </span>
                                            <?php } else { ?>
                                                <span class="chip green lighten-5">
                                                    <span class="green-text"><?php echo $data_t1['tested_a_grade']; ?></span>
                                                </span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <span class="chip green lighten-5">
                                                <span class="green-text"><?php echo $data_t1['expected_b_grade']; ?></span>
                                            </span> vs&nbsp;
                                            <?php
                                            if ($data_t1['expected_b_grade'] != $data_t1['tested_b_grade']) { ?>
                                                <span class="chip red lighten-5">
                                                    <span class="red-text"><?php echo $data_t1['tested_b_grade']; ?></span>
                                                </span>
                                            <?php } else { ?>
                                                <span class="chip green lighten-5">
                                                    <span class="green-text"><?php echo $data_t1['tested_b_grade']; ?></span>
                                                </span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <span class="chip green lighten-5">
                                                <span class="green-text"><?php echo $data_t1['expected_c_grade']; ?></span>
                                            </span> vs &nbsp;
                                            <?php
                                            if ($data_t1['expected_c_grade'] != $data_t1['tested_c_grade']) { ?>
                                                <span class="chip red lighten-5">
                                                    <span class="red-text"><?php echo $data_t1['tested_c_grade']; ?></span>
                                                </span>
                                            <?php } else { ?>
                                                <span class="chip green lighten-5">
                                                    <span class="green-text"><?php echo $data_t1['tested_c_grade']; ?></span>
                                                </span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <span class="chip green lighten-5">
                                                <span class="green-text"><?php echo $data_t1['expected_defected']; ?></span>
                                            </span> vs &nbsp;
                                            <?php
                                            if ($data_t1['expected_defected'] != $data_t1['tested_defected']) { ?>
                                                <span class="chip red lighten-5">
                                                    <span class="red-text"><?php echo $data_t1['tested_defected']; ?></span>
                                                </span>
                                            <?php } else { ?>
                                                <span class="chip green lighten-5">
                                                    <span class="green-text"><?php echo $data_t1['tested_defected']; ?></span>
                                                </span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } ?>
                        </table>
                    </div>
                </div>
            <?php
            } ?>
            <form id="barcodeForm2" class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab7") ?>" method="post">
                <input type="hidden" name="is_Submit_tab7_2" value="Y" />
                <input type="hidden" name="cmd7" value="<?php if (isset($cmd7)) echo $cmd7; ?>" />
                <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                    echo encrypt($_SESSION['csrf_session']);
                                                                } ?>">
                <input type="hidden" name="duplication_check_token" value="<?php echo (time() . session_id()); ?>">

                <div class="card-panel">
                    <div class="row">
                        <div class="col m8 s12">
                            <h5>Update RMA using BarCode</h5>
                        </div>
                        <div class="col m4 s12 show_receive_from_barcode_show_btn_tab7" style="<?php if (isset($is_Submit_tab7_2) && $is_Submit_tab7_2 == 'Y') {
                                                                                                    echo "display: none;";
                                                                                                } else {;
                                                                                                } ?>">
                            <a href="#" class="show_receive_from_barcode_section_tab7">Show Form</a>
                        </div>
                        <div class="col m4 s12 show_receive_from_barcode_hide_btn_tab7" style="<?php if (isset($is_Submit_tab7_2) && $is_Submit_tab7_2 == 'Y') {;
                                                                                                } else {
                                                                                                    echo "display: none;";
                                                                                                } ?>">
                            <a href="#" class="hide_receive_from_barcode_section_tab7">Hide Form</a>
                        </div>
                    </div>
                    <div id="receive_from_barcode_section_tab7" style="<?php if (isset($is_Submit_tab7_2) && $is_Submit_tab7_2 == 'Y') {;
                                                                        } else {
                                                                            echo "display: none;";
                                                                        } ?>">
                        <div class="row">
                            <div class="input-field col m12 s12"> </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m12 s12">
                                <?php
                                $field_name     = "receive_id_barcode_rma";
                                $field_label    = "Product";
                                $sql            = " SELECT * FROM(
                                                        SELECT  a.id, a.po_detail_id, a.edit_lock, a.serial_no_barcode, a.base_product_id,  
                                                                a.sub_product_id, c.product_desc, d.category_name,  c.product_uniqueid, a.is_rma_processed, a.price
                                                        FROM purchase_order_detail_receive a
                                                        INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
                                                        INNER JOIN products c ON c.id = b.product_id
                                                        LEFT JOIN product_categories d ON d.id =c.product_category
                                                        LEFT JOIN inventory_status h ON h.id = a.inventory_status
                                                        LEFT JOIN warehouse_sub_locations i ON i.id = a.sub_location_id_after_diagnostic
                                                        INNER JOIN product_stock j ON j.receive_id = a.id
                                                        WHERE a.enabled = 1 
                                                        AND b.po_id = '" . $id . "'
                                                        AND a.inventory_status != '" . $tested_or_graded_status . "'  
                                                    ) AS t1
                                                    ORDER BY  is_rma_processed, base_product_id, serial_no_barcode DESC ";
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
                                                $product_uniqueid_r1  = $data_r2['product_uniqueid'];  ?>

                                                <option value="<?php echo $data_r2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data_r2['id']) { ?> selected="selected" <?php } ?>>
                                                    <?php
                                                    echo $product_uniqueid_r1;
                                                    echo " - " . $data_r2['product_desc'];
                                                    if ($data_r2['category_name'] != "") {
                                                        echo " (" . $data_r2['category_name'] . ")";
                                                    }
                                                    if ($data_r2['serial_no_barcode'] != "") {
                                                        echo ", Serial#: " . $data_r2['serial_no_barcode'] . " - ";
                                                    }
                                                    if ($data_r2['price'] != "") {
                                                        echo " PO Price: " . number_format($data_r2['price'], 2) . "";
                                                    } ?>
                                                </option>
                                        <?php
                                            }
                                        } ?>
                                    </select>
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red">* <?php
                                                                    if (isset($error7[$field_name])) {
                                                                        echo $error7[$field_name];
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
                            <div class="input-field col m3 s12">
                                <?php
                                $field_name         = "status_id_rma";
                                $field_label        = "Status";
                                $sql1               = "SELECT * FROM inventory_status WHERE enabled = 1 AND id IN(" . $rma_process_status . ") ORDER BY status_name ";
                                $result1            = $db->query($conn, $sql1);
                                $count1             = $db->counter($result1);
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
                                                <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['status_name']; ?> </option>
                                        <?php }
                                        } ?>
                                    </select>
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red">* <?php
                                                                    if (isset($error7[$field_name])) {
                                                                        echo $error7[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="input-field col m3 s12 new_value" style="<?php if (!isset($status_id_rma) || (isset($status_id_rma) && $status_id_rma != '19' && $status_id_rma != '18' && $status_id_rma != '22' && $status_id_rma != '23' && $status_id_rma != '24')) {
                                                                                        echo "display: none;";
                                                                                    } ?>">
                                <?php
                                $field_name     = "new_value";
                                $field_label    = "New Value";
                                ?>
                                <i class="material-icons prefix">attach_money</i>
                                <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                    echo ${$field_name};
                                                                                                                } ?>" class="twoDecimalNumber  validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                            echo ${$field_name . "_valid"};
                                                                                                                                                        } ?>">
                                <label for="<?= $field_name; ?>">
                                    <?= $field_label; ?>
                                    <span class="color-red">* <?php
                                                                if (isset($error7[$field_name])) {
                                                                    echo $error7[$field_name];
                                                                } ?>
                                    </span>
                                </label>
                            </div>
                            <div class="input-field col m3 s12 tracking_no_rma" style="<?php if (!isset($status_id_rma) || (isset($status_id_rma) && $status_id_rma == '19' || $status_id_rma == '18' || $status_id_rma == '22' || $status_id_rma == '23' || $status_id_rma == '24') || $status_id_rma == '') {
                                                                                            echo "display: none;";
                                                                                        } ?>">
                                <?php
                                $field_name     = "tracking_no_rma";
                                $field_label    = "Tracking#";
                                ?>
                                <i class="material-icons prefix">add_shopping_cart</i>
                                <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                    echo ${$field_name};
                                                                                                                } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                            echo ${$field_name . "_valid"};
                                                                                                                                        } ?>">
                                <label for="<?= $field_name; ?>">
                                    <?= $field_label; ?>
                                    <span class="color-red">* <?php
                                                                if (isset($error7[$field_name])) {
                                                                    echo $error7[$field_name];
                                                                } ?>
                                    </span>
                                </label>
                            </div>
                            <div class="input-field col m4 s12 sub_location_id_barcode_rma" style="<?php if (!isset($status_id_rma) || (isset($status_id_rma) && $status_id_rma != '19' && $status_id_rma != '18' && $status_id_rma != '22' && $status_id_rma != '23' && $status_id_rma != '24') || $status_id_rma == '') {
                                                                                                        echo "display: none;";
                                                                                                    } ?>">
                                <?php
                                $field_name     = "sub_location_id_barcode_rma";
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
                                                                    if (isset($error7[$field_name])) {
                                                                        echo $error7[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row repair_type" style="<?php if (!isset($status_id_rma) || (isset($status_id_rma) && $status_id_rma != '19') || $status_id_rma == '') {
                                                                echo "display: none;";
                                                            } ?>">

                            <div class="input-field col m3 s12">
                                <?php
                                $field_name     = "repair_type";
                                $field_label    = "Repair Type";
                                $sql1           = "SELECT * FROM repair_types WHERE enabled = 1  ORDER BY repair_type_name ";
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
                                                <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['repair_type_name']; ?> </option>
                                        <?php }
                                        } ?>
                                    </select>
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red">* <?php
                                                                    if (isset($error7[$field_name])) {
                                                                        echo $error7[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="input-field col m1 s12 repair_type" style="<?php if (!isset($status_id_rma) || (isset($status_id_rma) && $status_id_rma != '19') || $status_id_rma == '') {
                                                                                        echo "display: none;";
                                                                                    } ?>">
                                <a class="waves-effect waves-light btn modal-trigger mb-2 mr-1" href="#repair_type_add_modal">Add New</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m12 s12 text_align_center">
                                <?php if (isset($id) && $id > 0 && (($cmd7 == 'add' || $cmd7 == '') && access("add_perm") == 1)  || ($cmd7 == 'edit' && access("edit_perm") == 1) || ($cmd7 == 'delete' && access("delete_perm") == 1)) { ?>
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
            <div class="card-panel">
                <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab7") ?>" method="post">
                    <input type="hidden" name="is_Submit_tab7_3" value="Y" />
                    <input type="hidden" name="cmd7" value="<?php if (isset($cmd7)) echo $cmd7; ?>" />
                    <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                        echo encrypt($_SESSION['csrf_session']);
                                                                    } ?>">
                    <input type="hidden" name="duplication_check_token" value="<?php echo (time() . session_id()); ?>">
                    <input type="hidden" name="active_tab" value="tab7" />
                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col m12 s12 text_align_center">
                                <a href="export/export_rma_data.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" class="waves-effect waves-light  btn gradient-45deg-light-blue-cyan box-shadow-none border-round mr-1 mb-1">Export in Excel</a>
                            </div>
                            <br>
                        </div>
                        <?php
                        if (po_permisions("RMA Process") == 1) { ?>
                            <div class="row">
                                <div class="col m12 s12">
                                    <label>
                                        <input type="checkbox" id="all_checked8" class="filled-in" name="all_checked8" value="1" <?php if (isset($all_checked8) && $all_checked8 == '1') {
                                                                                                                                        echo "checked";
                                                                                                                                    } ?> />
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        <?php
                        }   ?>
                        <div class="row">
                            <div class="col m12 s12">
                                <table id="page-length-option" class=" display pagelength50 dataTable dtr-inline">
                                    <thead>
                                        <tr>
                                            <?php
                                            $headings = '<th class="sno_width_60">S.No</th>
                                                        <th>Product ID / <br>Product Detail</th>
                                                        <th>Serial# / Status</th>
                                                        <th>Specification / <br>Defects</th>
                                                        <th>Grading</th>
                                                        <th>RMA Detail</th>';
                                            echo $headings;
                                            $headings2 = ' '; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = $total_pending_rma = 0;
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
                                                $is_rma_processed           = $data['is_rma_processed'];
                                                $is_rma_added               = $data['is_rma_added'];
                                                $is_diagnost                = 0; ?>
                                                <tr>
                                                    <td style="<?= $td_padding; ?>">
                                                        <?php echo $i + 1;
                                                        if ($serial_no_barcode != "" && $serial_no_barcode != null && po_permisions("RMA Process") == 1 && $detail_id2 > 0 && $inventory_status == 6 && $is_rma_processed == 0 && $is_rma_added == 1) {
                                                            $total_pending_rma++; ?>
                                                            <label style="margin-left: 25px;" id="checkbox_no_<?= $detail_id2; ?>">
                                                                <input type="checkbox" name="ids_for_rma[]" id="ids_for_rma[]" <?php if (isset($ids_for_rma) && in_array($detail_id2, $ids_for_rma)) {
                                                                                                                                    echo "checked";
                                                                                                                                } ?> value="<?= $detail_id2; ?>" class="checkbox8 filled-in" />
                                                                <span></span>
                                                            </label>
                                                        <?php } ?>
                                                    </td>
                                                    <td style="<?= $td_padding; ?>">
                                                        <?php echo $data['base_product_id']; ?>
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
                                                        $color          = "purple";
                                                        $string_text    = "Not Found In Vender";
                                                        $sql            = " SELECT a.*
                                                                            FROM vender_po_data a
                                                                            WHERE a.enabled = 1
                                                                            AND a.serial_no = '" . $serial_no_barcode . "'
                                                                            AND a.po_id             = '" . $id . "'  ";
                                                        $result_vebder = $db->query($conn, $sql);
                                                        $count_vebder  = $db->counter($result_vebder);
                                                        if ($count_vebder > 0) {
                                                            $row_vender = $db->fetch($result_vebder);
                                                        }

                                                        if ($detail_id2 == '0') {
                                                            $color  = "red";
                                                            $string_text = "Not Found In ERP";
                                                        } else if ($count_vebder > 0) {
                                                            $color  = "green";
                                                            $string_text = "Found Vender";
                                                        }
                                                        if ($serial_no_barcode != "") { ?>
                                                            <span class="chip <?= $color; ?> lighten-5">
                                                                <span class="<?= $color; ?>-text"><?php echo $serial_no_barcode; ?></span>
                                                            </span>
                                                            <br>
                                                            <?php
                                                            $color  = "purple";
                                                            if ($count_vebder > 0) {
                                                                $vender_status = $row_vender[0]['status'];
                                                                $color  = "red";
                                                                if ($detail_id2 == '0') {
                                                                    $color  = "red";
                                                                    echo $string_text . "<br>";
                                                                } else if ($status_name == $vender_status) {
                                                                    $color  = "green";
                                                                } else { ?>
                                                                    <span class="chip orange lighten-5">
                                                                        <span class="orange-text">Vender Status: <?php echo $vender_status; ?></span>
                                                                    </span><br>
                                                            <?php }
                                                            } else {
                                                                echo $string_text . "<br>";
                                                            } ?>
                                                            <span class="chip <?= $color; ?> lighten-5">
                                                                <span class="<?= $color; ?>-text"><?php echo $status_name; ?></span>
                                                            </span>
                                                        <?php } ?>
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
                                                        <br>
                                                        <?php if ($defectsCode != '') {
                                                            echo "Defect: " . substr($defectsCode, 0, 50) . "..<br>";
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
                                                            if ($detail_id2 == '0') {
                                                                $color  = "red";
                                                            } else if ($overall_grade == $vender_grade) {
                                                                $color  = "green";
                                                            } else { ?>
                                                                <span class="chip orange lighten-5">
                                                                    <span class="orange-text">
                                                                        Vender Grade: <?php echo $vender_grade; ?></span>
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
                                                        <?php
                                                        if ($serial_no_barcode != "" && $serial_no_barcode != null && po_permisions("RMA Process") == 1 && $detail_id2 > 0 && $inventory_status == 6) { ?>
                                                            <?php
                                                            $is_repaired_check      = 0;
                                                            $rma_status_name   = $rma_new_value =  $rma_repair_type_name =  $rma_sub_location_name = $rma_tracking_no = "";
                                                            $sql1       = " SELECT a.*, b.status_name, c.repair_type_name, d.sub_location_name, d.sub_location_type
                                                                            FROM purchase_order_detail_receive_rma a
                                                                            INNER JOIN inventory_status b ON b.id = a.status_id
                                                                            LEFT JOIN repair_types c ON c.id = a.repair_type
                                                                            LEFT JOIN warehouse_sub_locations d ON d.id = a.sub_location_id
                                                                            WHERE a.enabled   = 1 
                                                                            AND a.receive_id  = '" . $detail_id2 . "' ";
                                                            $result1    = $db->query($conn, $sql1);
                                                            $count1     = $db->counter($result1);
                                                            if ($count1 > 0) {
                                                                $row1 = $db->fetch($result1);
                                                                $rma_status_name        = $row1['0']['status_name'];
                                                                $rma_new_value          = $row1['0']['new_value'];
                                                                $rma_repair_type_name   = $row1['0']['repair_type_name'];
                                                                $rma_sub_location_name  = $row1['0']['sub_location_name'];
                                                                $rma_tracking_no        = $row1['0']['tracking_no'];
                                                                if ($row1['0']['sub_location_type'] != "") {
                                                                    $rma_sub_location_name .= " (" . $row1['0']['sub_location_type'] . ")";
                                                                }
                                                            } ?>
                                                            <?php
                                                            if ($rma_status_name != "" && $rma_status_name != null && $rma_status_name > 0) { ?>
                                                                <span class="chip blue lighten-5">
                                                                    <span class="blue-text"><?php echo $rma_status_name; ?></span>
                                                                </span>
                                                        <?php }
                                                            if ($rma_new_value != "" && $rma_new_value != null && $rma_new_value > 0) {
                                                                echo "<br><b>New Value: </b>" . $rma_new_value;
                                                            }
                                                            if ($rma_status_name == 'Repair' && $rma_repair_type_name != "" && $rma_repair_type_name != null) {
                                                                echo "<br><b>Repair Type: </b>" . $rma_repair_type_name;
                                                            }
                                                            if (($rma_status_name == 'Repair' || $rma_status_name == 'Vendor Denied')  && $rma_sub_location_name != "" && $rma_sub_location_name != null) {
                                                                echo "<br><b>New Location: </b>" . $rma_sub_location_name;
                                                            }
                                                            if ($rma_status_name != 'Repair' && $rma_tracking_no != "" && $rma_tracking_no != null) {
                                                                echo "<br><b>Tracking No: </b>" . $rma_tracking_no;
                                                            }
                                                        } ?>
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
                    if (po_permisions("RMA Process") == 1) {  ?>
                        <div class="row">
                            <div class="input-field col m12 s12 text_align_center">
                                <?php if (isset($id) && $id > 0) { ?>
                                    <button class="mb-6 btn waves-effect waves-light gradient-45deg-purple-deep-orange" type="submit" name="add">Process RMA</button>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m12 s12"></div>
                        </div>
                    <?php
                    } ?>
                </form>
            </div>
        <?php
        } else { ?>
            <div class="card-panel">
                <div class="row">
                    <div class="col 24 s12"><br>
                        <div class="card-alert card red lighten-5">
                            <div class="card-content red-text">
                                <p>Nothing diagnose yet. </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php }
    } ?>
</div>