 <div id="tab5_html" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab5')) {
                                            echo "block";
                                        } else {
                                            echo "none";
                                        } ?>;">
     <div class="card-panel" style="padding-top: 5px; padding-bottom: 5px; margin-top: 0px; margin-bottom: 5px;">
         <div class="row">
             <div class="input-field col m6 s12" style="margin-top: 3px; margin-bottom: 3px;">
                 <h6 class="media-heading">
                     <?= $general_heading; ?> => Receive
                 </h6>
             </div>
             <div class="input-field col m6 s12" style="text-align: right; margin-top: 3px; margin-bottom: 3px;">
                 <?php include("tab_action_btns.php"); ?>
             </div>
         </div>
         <?php
            if (isset($id) && isset($return_no)) {  ?>
             <div class="row">
                 <div class="input-field col m4 s12">
                     <h6 class="media-heading"><span class=""><?php echo "<b>Return#:</b>" . $return_no; ?></span></h6>
                 </div>
                 <div class="input-field col m4 s12">
                     <h6 class="media-heading"><span class=""><?php echo "<b>Vendor Invoice#: </b>" . $removal_order_id; ?></span></h6>
                 </div>

                 <div class="input-field col m4 s12">
                     <?php  ?>
                     <input type="hidden" name="total_pause_duration" id="total_pause_duration" value="0">
                 </div>
             </div>
             <?php
                if (isset($cmd5) &&  $cmd5 == "add" && isset($detail_id) && $detail_id != "") {  ?>
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

            $sql            = " SELECT a.*, c.status_name, d.sub_location_name, d.sub_location_type
                                FROM return_order_detail_logistics a
                                LEFT JOIN inventory_status c ON c.id = a.logistics_status
                                LEFT JOIN warehouse_sub_locations d ON d.id = a.sub_location_id
                                WHERE a.return_id = '" . $id . "'
                                 ORDER BY a.tracking_no ";
            $result_log     = $db->query($conn, $sql);
            $count_log      = $db->counter($result_log);
            if ($count_log > 0) { ?>

             <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab5") ?>" method="post">
                 <input type="hidden" name="is_Submit_tab5" value="Y" />
                 <input type="hidden" name="cmd5" value="<?php if (isset($cmd5)) echo $cmd5; ?>" />
                 <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                    echo encrypt($_SESSION['csrf_session']);
                                                                } ?>">
                 <input type="hidden" name="duplication_check_token" value="<?php echo (time() . session_id()); ?>">
                 <input type="hidden" name="active_tab" value="tab5" />
                 <?php
                    $sql        = " SELECT a.*, c.status_name, d.sub_location_name, d.sub_location_type
                                        FROM return_order_detail_logistics a
                                        LEFT JOIN inventory_status c ON c.id = a.logistics_status
                                        LEFT JOIN warehouse_sub_locations d ON d.id = a.sub_location_id
                                        WHERE a.return_id = '" . $id . "'
                                         ORDER BY a.tracking_no ";
                    // echo $sql; 
                    $result_log     = $db->query($conn, $sql);
                    $count_log      = $db->counter($result_log);
                    if ($count_log > 0) { ?>
                     <div class="card-panel custom_padding_card_content_table_top_bottom">
                         <div class="row">
                             <div class="col m6 s12">
                                 <h6>Return Receive by Category</h6>
                             </div>
                             <div class="col m6 s12 show_receive_as_category_show_btn" style="<?php if (isset($is_Submit_tab5) && $is_Submit_tab5 == 'Y') {
                                                                                                    echo "display: none;";
                                                                                                } else {;
                                                                                                } ?>">
                                 <a href="javascript:void(0)" class="show_receive_as_category_section">Show Form</a>
                             </div>
                             <div class="col m6 s12 show_receive_as_category_hide_btn" style="<?php if (isset($is_Submit_tab5) && $is_Submit_tab5 == 'Y') {;
                                                                                                } else {
                                                                                                    echo "display: none;";
                                                                                                } ?>">
                                 <a href="javascript:void(0)" class="hide_receive_as_category_section">Hide Form</a>
                             </div>
                         </div>
                         <div id="receive_as_category_section" style="<?php if (isset($is_Submit_tab5) && $is_Submit_tab5 == 'Y') {;
                                                                        } else {
                                                                            echo "display: none;";
                                                                        } ?>">
                             <div class="row">
                                 <?php
                                    if (isset($cmd5) &&  $cmd5 == "add" && isset($detail_id) && $detail_id != "") {  ?>
                                     <div class="col m4 s12"><br><br>
                                         <a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=" . $cmd . "&cmd5=" . $cmd5 . "&active_tab=tab5&id=" . $id) ?>">All Tracking / Pro #</a>
                                     </div> <br>
                                 <?php } ?>
                             </div>
                             <div class="row">
                                 <div class="input-field col m12 s12"> </div>
                             </div>

                             <?php
                              ?>
                             <div class="row">
                                 <div class="input-field col m12 s12"> </div>
                             </div>
                             <?php
                                $sql_r1     = "	SELECT a.id, c.product_uniqueid, c.product_desc, c.product_category, d.category_name, a.return_qty AS order_qty
                                                FROM return_items_detail a 
                                                INNER JOIN returns b ON b.id = a.return_id
                                                INNER JOIN products c ON c.id = a.product_id
                                                INNER JOIN product_categories d ON d.id = c.product_category
                                                WHERE 1=1 
                                                AND a.return_id = '" . $id . "' 
                                                 ORDER BY d.category_name, c.product_uniqueid"; //echo $sql_cl; die;
                                $result_r1  = $db->query($conn, $sql_r1);
                                $count_r1   = $db->counter($result_r1);
                                if ($count_r1 > 0) { ?>
                                 <div class="row">
                                     <div class="col s12">
                                         <table id="page-length-option1" class=" bordered addproducttable">
                                             <thead>
                                                 <tr>
                                                     <?php
                                                        $headings = '<th>Category</th>
                                                                    <th>Product ID</th>
                                                                    <th>Description</th>
                                                                    <th>Expected Receive</th>
                                                                    <th>Total Received Yet</th>
                                                                    <th>Receiving Qty <span class="color-red">*</span> </th>
                                                                    <th>Location <span class="color-red">*</span></th>';
                                                        echo $headings; ?>
                                                 </tr>
                                             </thead>
                                             <tbody>
                                                 <?php
                                                    $i = 0;
                                                    if ($count_r1 > 0) {
                                                        $row_cl_r1 = $db->fetch($result_r1);
                                                        foreach ($row_cl_r1 as $data_r1) {
                                                            $detail_id_r1       = $data_r1['id'];
                                                            $order_qty          = $data_r1['order_qty'];
                                                            $sql_rc1            = "	SELECT a.*
                                                                                    FROM return_items_detail_receive a 
                                                                                    INNER JOIN  return_items_detail b  ON a.ro_detail_id = b.id
                                                                                    INNER JOIN products c ON c.id = b.product_id
                                                                                    WHERE 1=1 
                                                                                    AND a.ro_detail_id =  '" . $detail_id_r1 . "'
                                                                                    AND b.return_id = '" . $id . "' 
                                                                                    AND a.enabled = 1 "; //echo $sql_rc1;
                                                            $result_rc1         = $db->query($conn, $sql_rc1);
                                                            $total_received_qty = $db->counter($result_rc1);  ?>
                                                         <tr>
                                                             <td style="width: 400px;">
                                                                <?php
                                                                if ($data_r1['category_name'] != '') {
                                                                    echo "" . $data_r1['category_name'] . "";
                                                                } else {
                                                                    echo "No Category";
                                                                } ?>
                                                             </td>
                                                             <td style="width: 400px;"><?php echo $data_r1['product_uniqueid'];?></td>
                                                             <td style="width: 400px;"><?php echo $data_r1['product_desc'];?></td>
                                                             <td style="width: 150px; text-align: center;"><?php echo $order_qty; ?></td>
                                                             <td style="width: 180px; text-align: center;"><?php echo $total_received_qty; ?></td>
                                                             <td style="width: 150px;">
                                                                 <?php
                                                                    $field_name             = "receiving_qties";
                                                                    $field_label            = "Receiving Qty";
                                                                    $receiving_qty_value    = "";

                                                                    if (isset(${$field_name}[$detail_id_r1]) && ${$field_name}[$detail_id_r1] > 0) {
                                                                        $receiving_qty_value = ${$field_name}[$detail_id_r1];
                                                                    }
                                                                    if ($_SERVER['HTTP_HOST'] == 'localhost' && $receiving_qty_value == "") {
                                                                        $receiving_qty_value = 2;
                                                                    }
                                                                    if (isset($error5[$field_name])) { ?>
                                                                     <span class="color-red"><?php echo $error5[$field_name]; ?></span>
                                                                 <?php } ?>
                                                                 <input type="number" placeholder="<?= $field_label; ?>" class="" name="<?= $field_name; ?>[<?= $detail_id_r1; ?>]" value="<?= $receiving_qty_value; ?>" style=" text-align: center;" />
                                                             </td>

                                                             <td style="width: 200px;">
                                                                 <?php
                                                                    $field_name             = "receiving_location";
                                                                    $field_label            = "Location";
                                                                    $receiving_location_val = "";

                                                                    if (isset(${$field_name}[$detail_id_r1]) && ${$field_name}[$detail_id_r1] > 0) {
                                                                        $receiving_location_val = ${$field_name}[$detail_id_r1];
                                                                    }

                                                                    $sql1           = "SELECT * FROM warehouse_sub_locations a WHERE a.enabled = 1  ORDER BY sub_location_name ";
                                                                    $result1        = $db->query($conn, $sql1);
                                                                    $count1         = $db->counter($result1);
                                                                    
                                                                    if ($_SERVER['HTTP_HOST'] == 'localhost' && $receiving_location_val == "") {
                                                                        $receiving_location_val = 1737;
                                                                    }
                                                                    ?>
                                                                 <span class="color-red"><?php
                                                                                            if (isset($error5[$field_name])) {
                                                                                                echo $error5[$field_name];
                                                                                            } ?>
                                                                 </span>
                                                                 <select id="<?= $field_name; ?>[<?= $detail_id_r1; ?>]" name="<?= $field_name; ?>[<?= $detail_id_r1; ?>]" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                                                                                                                                                } ?>">
                                                                     <option value="">Select</option>
                                                                     <?php
                                                                        if ($count1 > 0) {
                                                                            $row1    = $db->fetch($result1);
                                                                            foreach ($row1 as $data2) { ?>
                                                                             <option value="<?php echo $data2['id']; ?>" <?php if (isset($receiving_location_val) && $receiving_location_val == $data2['id']) { ?> selected="selected" <?php } ?>>
                                                                                 <?php echo $data2['sub_location_name'];
                                                                                    if ($data2['sub_location_type'] != "") {
                                                                                        echo " (" . ucwords(strtolower($data2['sub_location_type'])) . ")";
                                                                                    } ?>
                                                                             </option>
                                                                     <?php }
                                                                        } ?>
                                                                 </select>
                                                             </td>
                                                         </tr>
                                                 <?php $i++;
                                                        }
                                                    } ?>
                                         </table>
                                     </div>
                                 </div>
                             <?php } ?>
                             <div class="row">
                                 <div class="input-field col m12 s12"></div>
                             </div>
                             <div class="row">
                                 <div class="input-field col m12 s12 text_align_center">
                                     <?php if (isset($id) && $id > 0 && (($cmd5 == 'add' || $cmd5 == '') && access("add_perm") == 1)  || ($cmd5 == 'edit' && access("edit_perm") == 1) || ($cmd5 == 'delete' && access("delete_perm") == 1)) { ?>
                                         <button class="btn waves-effect waves-light gradient-45deg-purple-deep-orange" type="submit" name="add">Return Receive as Category</button>
                                     <?php } ?>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="input-field col m12 s12"></div>
                             </div>
                         </div>
                     </div>
                 <?php
                    } else { ?>
                     <div class="card-panel custom_padding_card_content_table_top_bottom">
                         <div class="row">
                             <div class="col 24 s12"><br>
                                 <div class="card-alert card red lighten-5">
                                     <div class="card-content red-text">
                                         <p>No arrival information is available. </p>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 <?php } ?>
             </form>

            
             <form id="barcodeForm" class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab5") ?>" method="post">
                 <input type="hidden" name="is_Submit_tab5_2" value="Y" />
                 <input type="hidden" name="cmd5" value="<?php if (isset($cmd5)) echo $cmd5; ?>" />
                 <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                    echo encrypt($_SESSION['csrf_session']);
                                                                } ?>">
                 <input type="hidden" name="duplication_check_token" value="<?php echo (time() . session_id()); ?>">

                 <div class="card-panel custom_padding_card_content_table_top_bottom">
                     <div class="row">
                         <div class="col m6 s12">
                             <h6>Return Receive from BarCode</h6>
                         </div>
                         <div class="col m6 s12 show_receive_from_barcode_show_btn" style="<?php if (isset($is_Submit_tab5_2) && $is_Submit_tab5_2 == 'Y') {
                                                                                                echo "display: none;";
                                                                                            } else {;
                                                                                            } ?>">
                             <a href="javascript:void(0)" class="show_receive_from_barcode_section">Show Form</a>
                         </div>
                         <div class="col m6 s12 show_receive_from_barcode_hide_btn" style="<?php if (isset($is_Submit_tab5_2) && $is_Submit_tab5_2 == 'Y') {;
                                                                                            } else {
                                                                                                echo "display: none;";
                                                                                            } ?>">
                             <a href="javascript:void(0)" class="hide_receive_from_barcode_section">Hide Form</a>
                         </div>
                     </div>
                     <div id="receive_from_barcode_section" style="<?php if (isset($is_Submit_tab5_2) && $is_Submit_tab5_2 == 'Y') {;
                                                                    } else {
                                                                        echo "display: none;";
                                                                    } ?>">
                         <div class="row">
                             <div class="input-field col m12 s12"> </div>
                         </div>
                         <div class="row">
                             <div class="input-field col m8 s12">
                                 <?php
                                    $field_name     = "product_id_barcode";
                                    $field_label    = "Return Product ID";

                                    $sql            = " SELECT a.*, c.product_desc, d.category_name, c.product_uniqueid 
                                                    FROM return_items_detail a 
                                                    INNER JOIN returns b ON b.id = a.return_id
                                                    INNER JOIN products c ON c.id = a.product_id
                                                    INNER JOIN product_categories d ON d.id = c.product_category
                                                    WHERE 1=1 
                                                    AND a.return_id = '" . $id . "' 
                                                    ORDER BY c.product_uniqueid, a.product_condition ";
                                    // echo $sql; 
                                    $result_log2    = $db->query($conn, $sql);
                                    $count_r2       = $db->counter($result_log2);
                                    ?>
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
                                                    $order_qty          = $data_r2['return_qty'];
                                                    $sql_rc1            = "	SELECT a.* 
                                                                            FROM return_items_detail_receive a 
                                                                            WHERE 1=1 
                                                                            AND a.ro_detail_id = '" . $detail_id_r1 . "'
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
                                         <span class="color-red">* <?php
                                                                    if (isset($error5[$field_name])) {
                                                                        echo $error5[$field_name];
                                                                    } ?>
                                         </span>
                                     </label>
                                 </div>
                             </div>
                             <div class="input-field col m2 s12">
                                 <?php
                                    $field_name     = "sub_location_id_barcode";
                                    $field_label    = "Return Location";
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
                                                                    if (isset($error5[$field_name])) {
                                                                        echo $error5[$field_name];
                                                                    } ?>
                                         </span>
                                     </label>
                                 </div>
                             </div>
                             <div class="input-field col m2 s12">
                                 <?php
                                    $field_name     = "serial_no_barcode";
                                    $field_label    = "Return Product Bar Code";
                                    ?>
                                 <i class="material-icons prefix">description</i>
                                 <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                    echo ${$field_name};
                                                                                                                } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                            echo ${$field_name . "_valid"};
                                                                                                                                        } ?>" onkeyup="autoSubmit(event)" autofocus>
                                 <label for="<?= $field_name; ?>">
                                     <?= $field_label; ?>
                                     <span class="color-red">* <?php
                                                                if (isset($error[$field_name])) {
                                                                    echo $error[$field_name];
                                                                } ?>
                                     </span>
                                 </label>
                             </div>
                         </div>
                         <div class="row">
                             <div class="input-field col m12 s12 text_align_center">
                                 <?php if (isset($id) && $id > 0 && (($cmd5 == 'add' || $cmd5 == '') && access("add_perm") == 1)  || ($cmd5 == 'edit' && access("edit_perm") == 1) || ($cmd5 == 'delete' && access("delete_perm") == 1)) { ?>
                                     <button class="btn waves-effect waves-light gradient-45deg-purple-deep-orange" type="submit" name="add">Receive with BarCode</button>
                                 <?php } ?>
                             </div>
                         </div>
                         <div class="row">
                             <div class="input-field col m12 s12"></div>
                         </div>
                     </div>
                 </div>
             </form>
             <?php 
                $td_padding = "padding:5px 10px !important;";
                $sql            = " SELECT * FROM ( 
                                        SELECT 'ProductReceived' as record_type, 'PO Product' as product_type, '1' as total_qty_received, a.*, c.product_desc, c.product_uniqueid, d.category_name, 
                                            e.first_name, e.middle_name, e.last_name, e.username, g.sub_location_name, g.sub_location_type, b1.is_pricing_done, c.product_category
                                        FROM return_items_detail_receive a
                                        INNER JOIN return_items_detail b ON b.id = a.ro_detail_id
                                        INNER JOIN returns b1 ON b1.id = b.return_id
                                        INNER JOIN products c ON c.id = b.product_id
                                        LEFT JOIN product_categories d ON d.id =c.product_category
                                        LEFT JOIN users e ON e.id = a.add_by_user_id
                                        LEFT JOIN warehouse_sub_locations g ON g.id = a.sub_location_id
                                        WHERE a.enabled = 1 
                                        AND b.return_id = '" . $id . "'
                                        AND (a.recevied_product_category = 0 || a.recevied_product_category IS NULL || a.serial_no_barcode IS NOT NULL)
                                        
                                        UNION ALL

                                        SELECT 'ProductReceived' as record_type, 'Added During Diagnostic' as product_type, '1' as total_qty_received, a.*, c.product_desc, c.product_uniqueid, d.category_name, 
                                            e.first_name, e.middle_name, e.last_name, e.username, g.sub_location_name, g.sub_location_type, b1.is_pricing_done, c.product_category
                                        FROM return_items_detail_receive a
                                        INNER JOIN returns b1 ON b1.id = a.return_id
                                        INNER JOIN products c ON c.id = a.product_id
                                        LEFT JOIN product_categories d ON d.id =c.product_category
                                        LEFT JOIN users e ON e.id = a.add_by_user_id
                                        LEFT JOIN warehouse_sub_locations g ON g.id = a.sub_location_id
                                        WHERE a.enabled = 1 
                                        AND a.return_id = '" . $id . "'
                                         
                                        UNION ALL

                                        SELECT 'CateogryReceived' AS record_type, 'PO Product' as product_type, COUNT(a.id) AS total_qty_received, a.*, '' AS product_desc, '' AS product_uniqueid, d.category_name, 
                                            e.first_name, e.middle_name, e.last_name, e.username, g.sub_location_name, g.sub_location_type, b1.is_pricing_done, a.recevied_product_category AS product_category 
                                        FROM return_items_detail_receive a 
                                        INNER JOIN returns b1 ON b1.id = a.return_id
                                        INNER JOIN product_categories d ON d.id = a.recevied_product_category  
                                        LEFT JOIN users e ON e.id = a.add_by_user_id
                                        LEFT JOIN warehouse_sub_locations g ON g.id = a.sub_location_id
                                        WHERE a.return_id = '" . $id . "'
                                        AND (a.serial_no_barcode = '' || a.serial_no_barcode IS NULL)
                                        GROUP BY a.recevied_product_category
                                    ) AS t1
                                    ORDER BY product_type DESC, record_type, product_category, sub_location_id, serial_no_barcode ";
                $result_log     = $db->query($conn, $sql);
                $count_log      = $db->counter($result_log);
                if ($count_log > 0) { ?>
                 <div class="card-panel custom_padding_card_content_table_top_bottom">
                     <div class="row">
                         <div class="col m12 s12">
                             <h6>Retun Location & Category Wise Total</h6>
                         </div>
                     </div>
                     <div class="row">
                         <table class="bordered">
                             <tr>
                                 <th> Category</th>
                                 <th> Location</th>
                                 <th> Qty</th>
                                 <th>Actions</th>
                             </tr>
                             <?php
                                $sql        =   "SELECT sub_location_id, sub_location_name, sub_location_type, product_category, category_name, SUM(total_products) AS total_products
                                                    FROM (
                                                    SELECT a.sub_location_id, e.sub_location_name, e.sub_location_type, c.product_category, d.`category_name`, COUNT(a.id) AS total_products
                                                    FROM return_items_detail b 
                                                    INNER JOIN products c ON c.id = b.product_id
                                                    INNER JOIN return_items_detail_receive a ON a.`ro_detail_id` = b.id
                                                    LEFT JOIN warehouse_sub_locations e ON e.id = a.sub_location_id
                                                    INNER JOIN product_categories d ON d.id = c.product_category  
                                                    WHERE a.enabled = 1 
                                                    AND b.return_id = '" . $id . "'
                                                    AND a.`receive_type` != 'CateogryReceived'
                                                    GROUP BY c.product_category

                                                    UNION ALL 

                                                    SELECT a.sub_location_id, e.sub_location_name, e.sub_location_type, a.recevied_product_category AS product_category, d.`category_name`, COUNT(a.id) AS total_products
                                                    FROM return_items_detail_receive a 
                                                    INNER JOIN returns b1 ON b1.id = a.return_id
                                                    INNER JOIN product_categories d ON d.id = a.recevied_product_category  
                                                    LEFT JOIN warehouse_sub_locations e ON e.id = a.sub_location_id
                                                    WHERE a.return_id = '" . $id . "'
                                                    GROUP BY a.recevied_product_category
                                                ) AS t1
                                                GROUP BY category_name, sub_location_name
                                                ORDER BY category_name, sub_location_name ";
                                $result_t1  = $db->query($conn, $sql);
                                $count_t1   = $db->counter($result_t1);
                                if ($count_t1 > 0) {
                                    if ($count_log > 0) {
                                        $row_t1 = $db->fetch($result_t1);
                                        foreach ($row_t1 as $data_t1) {
                                            $detail_id2             = $data_t1['sub_location_id'];
                                            $product_category_rc2   = $data_t1['product_category']; ?>
                                         <tr>
                                             <td><?php echo $data_t1['category_name']; ?></td>
                                             <td>
                                                 <?php echo $data_t1['sub_location_name']; ?>
                                                 <?php
                                                    if ($data_t1['sub_location_type'] != "") {
                                                        echo " (" . $data_t1['sub_location_type'] . ")";
                                                    } ?>
                                             </td>
                                             <td><?php echo $data_t1['total_products']; ?></td>
                                             <td>
                                                 <a href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/print_receive_labels_pdf.php?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&id=" . $id . "&sub_location_id=" . $detail_id2 . "&product_category=" . $product_category_rc2)  ?>" target="_blank">
                                                     <i class="material-icons dp48">print</i>
                                                 </a>
                                             </td>
                                         </tr>
                             <?php
                                        }
                                    }
                                } ?>
                         </table>
                     </div>
                 </div>
                 <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab5") ?>" method="post">
                     <input type="hidden" name="is_Submit_tab5_4_2" value="Y" />
                     <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                        echo encrypt($_SESSION['csrf_session']);
                                                                    } ?>">
                     <input type="hidden" name="duplication_check_token" value="<?php echo (time() . session_id()); ?>">
                     <input type="hidden" name="active_tab" value="tab5" />
                     <div class="card-panel custom_padding_card_content_table_top_bottom">
                         <div class="row">
                             <div class="col m12 s12">
                                 <h6>Return Received Products</h6>
                             </div>
                         </div>
                        
                         <br>
                         <div class="row">
                            <div class="col m2 s12">
                                <label>
                                    <input type="checkbox" id="all_checked6" class="filled-in" name="all_checked6" value="1" <?php if (isset($all_checked6) && $all_checked6 == '1') {
                                                                                                                                echo "checked";
                                                                                                                            } ?> />
                                    <span></span>
                                </label>
                            </div>
                            <div class="col m10 s12">
                                <div class="text_align_right">
                                    <?php 
                                    $table_columns	= array('SNo', 'check_all', 'Type', 'Serial NO', 'Product Base ID', 'Product Detail', 'Location', 'Qty', 'Received By', 'Receiving Date/Time');
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
                                     <table id="page-length-option" class="display pagelength100 dataTable dtr-inline">
                                         <thead>
                                             <tr>
                                                 <?php
                                                    $headings = "";
													foreach($table_columns as $data_c){
														if($data_c == 'SNo'){
															$headings .= '<th class="sno_width_60 col-'.set_table_headings($data_c).'">'.$data_c.'</th>';
														}else if($data_c == 'checl_all'){
                                                            $headings .= '<th class="sno_width_60 col-'.set_table_headings($data_c).'"></th>';
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
                                                        $detail_id2 = $data['id'];
                                                        if ($data['record_type'] == 'CateogryReceived') {
                                                            $detail_id2 = $data['product_category'];
                                                        } ?>
                                                     <tr>
                                                         <td style="width:80px; <?= $td_padding; ?>; text-align: center;" class="col-<?= set_table_headings($table_columns[0]);?>"><?php echo $i + 1; ?></td>
                                                         <td style="width:80px; <?= $td_padding; ?>; text-align: center;" class="col-<?= set_table_headings($table_columns[1]);?>">
                                                             <?php
                                                                if (access("delete_perm") == 1 && (($data['edit_lock'] == "0" && $data['is_diagnost'] == "0") || ($data['is_diagnostic_bypass'] == 1 && $data['is_pricing_done'] == 0))) {
                                                                    $checkbox_del++; ?>
                                                                 <label>
                                                                     <input type="checkbox" name="receviedProductIds[]" id="receviedProductIds[]" value="<?= $data['record_type']; ?>-<?= $detail_id2; ?>" class="checkbox6 filled-in" />
                                                                     <span></span>
                                                                 </label>
                                                             <?php } ?>
                                                         </td>
                                                         <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[2]);?>"><?php echo $data['product_type']; ?></td>
                                                         <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[3]);?>">
                                                             <?php
                                                                $color              = "color-red";
                                                                $serial_no_barcode  = $data['serial_no_barcode'];
                                                                if ($data['serial_no_barcode'] != "" && $data['serial_no_barcode'] != null) { 
                                                                    echo "<span class='" . $color . "'>" . $serial_no_barcode . "</span>";
                                                                } ?>
                                                         </td>
                                                         <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[4]);?>"><?php echo $data['product_uniqueid']; ?></td>
                                                         <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[5]);?>">
                                                             <?php echo $data['product_desc']; ?>
                                                             <?php
                                                                if ($data['category_name'] != "") {
                                                                    if ($data['record_type'] == "CateogryReceived") {
                                                                        echo "" . $data['category_name'] . "";
                                                                    } else {
                                                                        echo " (" . $data['category_name'] . ")";
                                                                    }
                                                                } ?>
                                                         </td>
                                                         <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[6]);?>">
                                                             <?php echo $data['sub_location_name']; ?>
                                                             <?php
                                                                if ($data['sub_location_type'] != "") {
                                                                    echo " (" . $data['sub_location_type'] . ")";
                                                                } ?>
                                                         </td>
                                                         <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[7]);?>"><?php echo $data['total_qty_received']; ?></td>
                                                         <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[8]);?>">
                                                             <?php echo $data['first_name']; ?>
                                                             (<?php echo $data['username']; ?>)
                                                         </td>
                                                         <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[9]);?>">
                                                             <?php echo dateformat1_with_time($data['add_date']); ?>
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

                         <div class="row">
                             <div class="input-field col m12 s12 text_align_center">
                                 <?php if (isset($id) && $id > 0 &&  access("delete_perm") == 1 && $checkbox_del > 0) { ?>
                                     <button class="btn waves-effect waves-light gradient-45deg-purple-deep-orange" type="submit" name="deletepserial">Delete</button>
                                 <?php } ?>
                             </div>
                         </div>
                         <div class="row">
                             <div class="input-field col m12 s12"></div>
                         </div>
                     </div>
                 </form>
             <?php }
            } else { ?>
             <div class="card-panel custom_padding_card_content_table_top_bottom">
                 <div class="row">
                     <div class="col 24 s12"><br>
                         <div class="card-alert card red lighten-5">
                             <div class="card-content red-text">
                                 <p>Nothing arrive yet. </p>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
     <?php
            }
        } ?>
 </div>
 <script>
     function autoSubmit(event) {
         var keycode_value = event.keyCode;
         if (keycode_value === 8 || keycode_value === 37 || keycode_value === 38 || keycode_value === 39 || keycode_value === 40 || keycode_value === 46 || keycode_value === 17 || keycode_value === 16 || keycode_value === 18 || keycode_value === 20 || keycode_value === 110 || (event.ctrlKey && (keycode_value === 65 || keycode_value === 67 || keycode_value === 88 || keycode_value === 88))) {

         } else {
             document.getElementById('barcodeForm').submit();
         }
     }
 </script>