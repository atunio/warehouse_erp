<div id="tab7_html" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab7')) {
                                        echo "block";
                                    } else {
                                        echo "none";
                                    } ?>;">
    <div class="card-panel">
        <div class="row">
            <div class="col s10 m12 l8">
                <h5 class="breadcrumbs mt-0 mb-0"><span>RMA</span></h5>
            </div>
        </div>
        <?php
        if (isset($id)) {  ?>
            <div class="row">
                <div class="input-field col m3 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>PO#: </b>" . $po_no; ?></span></h6>
                </div>
                <div class="input-field col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Vendor Invoice#: </b>" . $vender_invoice_no; ?></span></h6>
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
        if ($count_log > 0) {   ?>
            <?php //*/ 
            $td_padding = "padding:5px 10px !important;";
            $sql        = " SELECT * FROM(
                                SELECT  '1' as rec_sort, a.id, a.logistic_id, a.po_detail_id, a.edit_lock, a.serial_no_barcode,  a.base_product_id,  
                                        a.sub_product_id, a.model_name, a.model_no, a.make_name, a.carrier_name, a.color_name, a.battery, a.body_grade, 
                                        a.lcd_grade,  a.digitizer_grade,  a.overall_grade, a.ram, a.storage, a.processor, a.warranty, a.price, 
                                        a.defects_or_notes,  a.inventory_status,  a.sku_code,  a.phone_check_api_data,  a.is_diagnost, a.is_rma_processed, a.enabled,

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
                                AND j.p_inventory_status != '" . $tested_or_graded_status . "' 

                                UNION ALL 

                                SELECT  '1' as rec_sort, a.id, a.logistic_id, a.po_detail_id, a.edit_lock, a.serial_no_barcode,  a.base_product_id,  
                                        a.sub_product_id, a.model_name, a.model_no, a.make_name, a.carrier_name, a.color_name, a.battery, a.body_grade, 
                                        a.lcd_grade,  a.digitizer_grade,  a.overall_grade, a.ram, a.storage, a.processor, a.warranty, a.price, 
                                        a.defects_or_notes,  a.inventory_status,  a.sku_code,  a.phone_check_api_data,  a.is_diagnost, a.is_rma_processed, a.enabled,
                                         
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
                                AND j.p_inventory_status = '" . $tested_or_graded_status . "'
                                AND k.id IS NULL  

                                UNION ALL
                                
                                SELECT 
                                    '2' as rec_sort, '0' AS id, '0' AS logistic_id, '0' AS po_detail_id, '0' AS edit_lock, a.serial_no AS serial_no_barcode,  a.product_uniqueid AS base_product_id,
                                    '' AS sub_product_id, '' AS model_name, '' AS model_no, '' AS make_name, '' AS carrier_name, '' AS color_name, a.battery, '' AS body_grade, 
                                    '' AS lcd_grade,  '' AS digitizer_grade,  a.overall_grade, a.memory AS ram, a.storage, a.processor, '' AS warranty, a.price, 
                                    a.defects_or_notes,  a.status AS inventory_status,  '' AS sku_code,  '' AS phone_check_api_data,  '0' AS is_diagnost,  '0' AS is_rma_processed,  '1' AS enabled,
                                    
                                    '' AS product_desc, a.product_category AS category_name, 
                                    '' AS first_name, '' AS middle_name, '' AS last_name, '' AS username, '' AS tracking_no, '' AS sub_location_name, 
                                    '' AS sub_location_name_after_diagnostic, 
                                    a.price AS order_price,  a.status AS status_name, a.product_uniqueid
                                FROM vender_po_data a
                                WHERE po_id = 3
                                AND serial_no NOT IN(
                                    SELECT a.serial_no_barcode 
                                    FROM purchase_order_detail_receive a 
                                    INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
                                    WHERE b.po_id = '" . $id . "'
                                    AND is_diagnost = 1
                                )
                            ) AS t1
                            ORDER BY rec_sort, base_product_id, serial_no_barcode DESC ";
            // echo $sql;
            $result_log = $db->query($conn, $sql);
            $count_log  = $db->counter($result_log);
            if ($count_log > 0) { ?>
                <div class="card-panel">
                    <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab7") ?>" method="post">
                        <input type="hidden" name="is_Submit_tab7_7" value="Y" />
                        <input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
                        <input type="hidden" name="cmd6" value="<?php if (isset($cmd6)) echo $cmd6; ?>" />
                        <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                            echo encrypt($_SESSION['csrf_session']);
                                                                        } ?>">
                        <input type="hidden" name="duplication_check_token" value="<?php echo (time() . session_id()); ?>">
                        <input type="hidden" name="active_tab" value="tab7" />

                        <div class="row">
                            <div class="col m4 s12">
                                <h5>Reconcile</h5>
                            </div>
                        </div>

                        <div class="row">
                            <table class="bordered">
                                <tr>
                                    <th>Qty PO</th>
                                    <th>Qty Vender</th>
                                    <th>Received</th>
                                    <th>Diagnosed</th>
                                    <th>Inventory</th>
                                    <th>Serial# <br>in Vender</th>
                                    <th>Serial# <br>Not in Vender</th>
                                    <th>Reconcile</th>
                                    <th>Defected</th>
                                    <th>Tested/Graded</th>
                                    <th>Defected & <br>Not Reconcile<br>with Vender<br>Grade</th>
                                    <th>Tested/Graded & <br>Not Reconcile<br>with Vender<br>Grade</th>
                                </tr>
                                <?php
                                $sql        = " SELECT (
                                                    SELECT SUM(b.order_qty)
                                                    FROM purchase_order_detail b 
                                                    WHERE b.po_id = a.id
                                                    ) AS total_ordered,
                                                    COUNT(DISTINCT f1.id) AS total_vender_data,
                                                    COUNT(DISTINCT c.id) AS received,
                                                    COUNT(DISTINCT d.id) AS diagnosed,
                                                    COUNT(DISTINCT e.id) AS moved,
                                                    COUNT(DISTINCT f.id) AS reconciled,
                                                    COUNT(DISTINCT g.id) AS defected,
                                                     COUNT(DISTINCT i.id) AS defected_not_reconcile,
                                                    COUNT(DISTINCT j.id) AS not_defected_not_reconcile,
                                                    COUNT(DISTINCT k.id) AS serial_in_vender_data
                                                FROM 
                                                    purchase_orders a
                                                LEFT JOIN 
                                                    purchase_order_detail b ON b.po_id = a.id
                                                LEFT JOIN 
                                                    vender_po_data f1 ON f1.po_id = a.id
                                                LEFT JOIN 
                                                    purchase_order_detail_receive c ON c.po_detail_id = b.id
                                                LEFT JOIN 
                                                    purchase_order_detail_receive d ON c.po_detail_id = b.id AND d.is_diagnost = 1
                                                LEFT JOIN 
                                                    product_stock e ON e.receive_id = c.id
                                                LEFT JOIN 
                                                    inventory_status f2 ON  f2.id = e.p_inventory_status 
                                                LEFT JOIN 
                                                    product_stock f ON f.receive_id = c.id AND f1.serial_no = f.serial_no AND f2.status_name = f1.`status`
                                                LEFT JOIN 
                                                    product_stock g ON g.receive_id = c.id AND g.`p_inventory_status` = 6 
                                                LEFT JOIN 
                                                    product_stock i ON i.receive_id = c.id AND f1.serial_no = i.serial_no AND f2.status_name != f1.`status` AND i.`p_inventory_status` = 6
                                                LEFT JOIN 
                                                    product_stock j ON j.receive_id = c.id AND f1.serial_no = j.serial_no AND f2.status_name != f1.`status` AND j.`p_inventory_status` != 6
                                                LEFT JOIN 
                                                    product_stock k ON k.receive_id = c.id AND k.serial_no = f1.serial_no  
                                                WHERE 
                                                    a.id = '" . $id . "'  ";
                                $result_t1  = $db->query($conn, $sql);
                                $count_t1   = $db->counter($result_t1);
                                if ($count_t1 > 0) {
                                    $row_t1 = $db->fetch($result_t1);
                                    foreach ($row_t1 as $data_t1) { ?>
                                        <tr>
                                            <td>
                                                <span class="chip green lighten-5">
                                                    <span class="green-text"><?php echo $data_t1['total_ordered']; ?></span>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                if ($data_t1['total_ordered'] != $data_t1['total_vender_data']) { ?>
                                                    <span class="chip red lighten-5">
                                                        <span class="red-text"><?php echo $data_t1['total_vender_data']; ?></span>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="chip green lighten-5">
                                                        <span class="green-text"><?php echo $data_t1['total_vender_data']; ?></span>
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td>
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
                                                <?php
                                                if ($data_t1['total_ordered'] != $data_t1['diagnosed']) { ?>
                                                    <span class="chip red lighten-5">
                                                        <span class="red-text"><?php echo $data_t1['diagnosed']; ?></span>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="chip green lighten-5">
                                                        <span class="green-text"><?php echo $data_t1['diagnosed']; ?></span>
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($data_t1['total_ordered'] != $data_t1['moved']) { ?>
                                                    <span class="chip red lighten-5">
                                                        <span class="red-text"><?php echo $data_t1['moved']; ?></span>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="chip green lighten-5">
                                                        <span class="green-text"><?php echo $data_t1['moved']; ?></span>
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($data_t1['diagnosed'] != $data_t1['serial_in_vender_data']) { ?>
                                                    <span class="chip red lighten-5">
                                                        <span class="red-text"><?php echo $data_t1['serial_in_vender_data']; ?></span>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="chip green lighten-5">
                                                        <span class="green-text"><?php echo $data_t1['serial_in_vender_data']; ?></span>
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php
                                                $serial_not_in_vender = $data_t1['moved'] - $data_t1['serial_in_vender_data'];
                                                if ($serial_not_in_vender > 0) { ?>
                                                    <span class="chip red lighten-5">
                                                        <span class="red-text"><?php echo $serial_not_in_vender; ?></span>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="chip green lighten-5">
                                                        <span class="green-text">0</span>
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($data_t1['diagnosed'] != $data_t1['reconciled']) { ?>
                                                    <span class="chip red lighten-5">
                                                        <span class="red-text"><?php echo $data_t1['reconciled']; ?></span>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="chip green lighten-5">
                                                        <span class="green-text"><?php echo $data_t1['reconciled']; ?></span>
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php
                                                $defected = $data_t1['defected'];
                                                if ($defected > 0) { ?>
                                                    <span class="chip red lighten-5">
                                                        <span class="red-text"><?php echo $defected; ?></span>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="chip green lighten-5">
                                                        <span class="green-text">0</span>
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php
                                                $not_defected = $data_t1['moved'] - $data_t1['defected'];
                                                if ($data_t1['diagnosed'] != $not_defected) { ?>
                                                    <span class="chip red lighten-5">
                                                        <span class="red-text"><?php echo $not_defected; ?></span>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="chip green lighten-5">
                                                        <span class="green-text"><?php echo $not_defected; ?></span>
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php
                                                $defected_not_reconcile = $data_t1['defected_not_reconcile'];
                                                if ($defected_not_reconcile > 0) { ?>
                                                    <span class="chip red lighten-5">
                                                        <span class="red-text"><?php echo $defected_not_reconcile; ?></span>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="chip green lighten-5">
                                                        <span class="green-text">0</span>
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php
                                                $not_defected_not_reconcile = $data_t1['not_defected_not_reconcile'];
                                                if ($not_defected_not_reconcile > 0) { ?>
                                                    <span class="chip red lighten-5">
                                                        <span class="red-text"><?php echo $not_defected_not_reconcile; ?></span>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="chip green lighten-5">
                                                        <span class="green-text">0</span>
                                                    </span>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } ?>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col m4 s12"><br><br></div>
                        </div>
                        <div class="section section-data-tables">
                            <div class="row">
                                <div class="col m4 s12"></div>
                                <div class="col m3 s12">
                                    <a href="export/export_po_data_for_reconcile.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" class="waves-effect waves-light  btn gradient-45deg-light-blue-cyan box-shadow-none border-round mr-1 mb-1">Export in Excel</a>
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
                                    <table id="page-length-option" class="display2 ">
                                        <thead>
                                            <tr>
                                                <?php
                                                $headings = '<th class="sno_width_60">S.No</th>
                                                            <th>Product Base ID / Detail</th>
                                                            <th>Serial#</th>   
                                                            <th>Sub Product ID / Specification</th>
                                                             <th>Grading</th> 
                                                            <th>Defects</th> 
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
                                                    $sub_product_id_r           = $data['sub_product_id'];
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
                                                    $is_diagnost                = 0; ?>
                                                    <tr>
                                                        <td style="<?= $td_padding; ?>">
                                                            <?php echo $i + 1;
                                                            if ($serial_no_barcode != "" && $serial_no_barcode != null && po_permisions("RMA Process") == 1 && $detail_id2 > 0 && $inventory_status == 6 && $is_rma_processed == '0') { ?>
                                                                <label style="margin-left: 25px;">
                                                                    <input type="checkbox" name="ids_for_rma[]" id="ids_for_rma[]" <?php if (isset($ids_for_rma) && in_array($detail_id2, $ids_for_rma)) {
                                                                                                                                        echo "checked";
                                                                                                                                    } ?> value="<?= $detail_id2; ?>" class="checkbox8 filled-in" />
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
                                                                <?php echo $string_text; ?>
                                                            <?php } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>">
                                                            <?php echo $sub_product_id_r; ?><br>
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
                                                                if ($detail_id2 == '0') {
                                                                    $color  = "red";
                                                                } else if ($overall_grade == $vender_grade) {
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
                                                            <?php if ($defectsCode != '') {
                                                                echo "" . substr($defectsCode, 0, 50) . "..<br>";
                                                            } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>">
                                                            <?php
                                                            $color  = "purple";
                                                            if ($count_vebder > 0) {
                                                                $vender_status = $row_vender[0]['status'];
                                                                $color  = "red";
                                                                if ($detail_id2 == '0') {
                                                                    $color  = "red";
                                                                } else if ($status_name == $vender_status) {
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
                        if (po_permisions("RMA Process") == 1) {  ?>
                            <div class="row">
                                <div class="input-field col m1 s12"></div>
                                <div class="input-field col m3 s12">
                                    <?php
                                    $field_name     = "status_id_update_rma";
                                    $field_label     = "Status";
                                    $sql1             = "SELECT * FROM inventory_status WHERE enabled = 1 AND id IN(" . $rma_process_status . ") ORDER BY status_name ";
                                    $result1         = $db->query($conn, $sql1);
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
                                <div class="input-field col m3 s12">
                                    <?php if (isset($id) && $id > 0) { ?>
                                        <button class="waves-effect waves-light  btn gradient-45deg-purple-deep-orange box-shadow-none border-round mr-1 mb-1" type="submit" name="add">Update</button>
                                    <?php } ?>
                                </div>
                                <div class="input-field col m4 s12"></div>
                            </div>
                            <div class="row">
                                <div class="input-field col m12 s12"></div>
                            </div>
                        <?php
                        } ?>
                    </form>
                </div>
            <?php
            }
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