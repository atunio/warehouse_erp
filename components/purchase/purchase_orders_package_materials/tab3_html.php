<div id="tab3_html" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab3')) {
                                        echo "block";
                                    } else {
                                        echo "none";
                                    } ?>;">
    <div class="card-panel" style="padding-top: 5px; padding-bottom: 5px; margin-top: 0px; margin-bottom: 5px;">
        <div class="row">
            <div class="input-field col m6 s12" style="margin-top: 3px; margin-bottom: 3px;">
                <h6 class="media-heading">
                    <?= $general_heading; ?> --> Logistics
                </h6>
            </div>
            <div class="input-field col m6 s12" style="text-align: right; margin-top: 3px; margin-bottom: 3px;">
                <?php include("tab_action_btns.php"); ?>
            </div>
        </div>
        <?php
        if (isset($id) && isset($po_no)) {  ?>
            <div class="row">
                <div class=" col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>PO#:</b>" . $po_no; ?></span></h6>
                </div>
                <div class=" col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Order Date: </b>" . $order_date_disp; ?></span></h6>
                </div>
                <div class=" col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Vendor Invoice#: </b>" . $vender_invoice_no; ?></span></h6>
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
        $td_padding = "padding:5px 15px !important;";

        $sql            = " SELECT a.*, c.status_name, d.sub_location_name, d.sub_location_type
                            FROM package_materials_order_detail_logistics a
                            LEFT JOIN inventory_status c ON c.id = a.logistics_status
                            LEFT JOIN warehouse_sub_locations d ON d.id = a.sub_location_id
                            WHERE a.po_id = '" . $id . "' 
                            ORDER BY a.tracking_no ";
        // echo $sql; 
        $result_log     = $db->query($conn, $sql);
        $count_log      = $db->counter($result_log);
        if ($count_log > 0) {
            $sql            = " SELECT a.*, c.package_name, d.category_name, 
                                        e.first_name, e.middle_name, e.last_name, e.username, f.tracking_no, g.sub_location_name
                                FROM package_materials_order_detail_receive a
                                INNER JOIN package_materials_order_detail b ON b.id = a.po_detail_id
                                INNER JOIN packages c ON c.id = b.package_id
                                LEFT JOIN product_categories d ON d.id =c.product_category
                                LEFT JOIN users e ON e.id = a.add_by_user_id
                                INNER JOIN package_materials_order_detail_logistics f ON f.id = a.logistic_id
                                LEFT JOIN warehouse_sub_locations g ON g.id = a.sub_location_id
                                WHERE a.enabled = 1 
                                AND b.po_id = '" . $id . "'
                                ORDER BY a.serial_no_barcode DESC, g.sub_location_name, a.id DESC ";
            $result_log2     = $db->query($conn, $sql);
            $count_log2      = $db->counter($result_log2); ?>
            <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab3") ?>" method="post">
                <input type="hidden" name="is_Submit_tab3" value="Y" />
                <input type="hidden" name="cmd3" value="<?php if (isset($cmd3)) echo $cmd3; ?>" />
                <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                    echo encrypt($_SESSION['csrf_session']);
                                                                } ?>">
                <input type="hidden" name="duplication_check_token" value="<?php echo (time() . session_id()); ?>">
                <input type="hidden" name="active_tab" value="tab3" />
                <div class="card-panel">
                    <div class="row">
                        <div class="col m6 s12">
                            <h5>Receive Items</h5>
                        </div>
                        <div class="col m6 s12 show_receive_as_category_show_btn" style="display: none <?php if (isset($is_Submit_tab3) && $is_Submit_tab3 == 'Y') {
                                                                                                            //echo "display: none;";
                                                                                                        } else {;
                                                                                                        } ?>">
                            <a href="javascript:void(0)" class="show_receive_as_category_section">Show Form</a>
                        </div>
                        <div class="col m6 s12 show_receive_as_category_hide_btn" style="display: none  <?php if (isset($is_Submit_tab3) && $is_Submit_tab3 == 'Y') {;
                                                                                                        } else {
                                                                                                            //echo "display: none;";
                                                                                                        } ?>">
                            <a href="javascript:void(0)" class="hide_receive_as_category_section">Hide Form</a>
                        </div>
                    </div>
                    <div id="receive_as_category_section" style="<?php if (isset($is_Submit_tab3) && $is_Submit_tab3 == 'Y') {;
                                                                    } else {
                                                                        //echo "display: none;";
                                                                    } ?>">
                        <div class="row">
                            <?php
                            if (isset($cmd3) &&  $cmd3 == "add" && isset($detail_id) && $detail_id != "") {  ?>
                                <div class="col m4 s12"><br><br>
                                    <a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=" . $cmd . "&cmd3=" . $cmd3 . "&active_tab=tab3&id=" . $id) ?>">All Tracking / Pro #</a>
                                </div> <br>
                            <?php } ?>
                        </div>
                        <div class="row">
                            <div class="input-field col m12 s12"> </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m4 s12">
                                <?php
                                $field_name     = "logistic_id";
                                $field_label    = "Tracking No";
                                $count_r2     = $count_log;
                                ?>
                                <i class="material-icons prefix pt-1">location_on</i>
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
                                            $row_r2    = $db->fetch($result_log);
                                            foreach ($row_r2 as $data_r2) { ?>
                                                <option value="<?php echo $data_r2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data_r2['id']) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $data_r2['tracking_no']; ?>
                                                </option>
                                        <?php
                                            }
                                        } ?>
                                    </select>
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red">* <?php
                                                                    if (isset($error3[$field_name])) {
                                                                        echo $error3[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <?php
                            if ($count_log2 > 0) { ?>
                                <div class="input-field col m1 s12"></div>
                                <div class="input-field col m4 s12">
                                    <a class="btn waves-effect waves-light gradient-45deg-purple-deep-orange" href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/print_packages_receive_labels_pdf.php?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&id=" . $id)  ?>" target="_blank">
                                        <i class="material-icons dp48">print</i>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="row">
                            <div class="input-field col m12 s12"> </div>
                        </div>
                        <?php
                        $sql_r1     = "	SELECT a.*, c.package_name, d.category_name
                                            FROM package_materials_order_detail a 
                                            INNER JOIN package_materials_orders b ON b.id = a.po_id
                                            INNER JOIN packages c ON c.id = a.package_id
                                            INNER JOIN product_categories d ON d.id = c.product_category
                                            WHERE 1=1 
                                            AND a.po_id = '" . $id . "' 
                                            ORDER BY d.category_name, c.package_name"; //echo $sql_cl;
                        $result_r1  = $db->query($conn, $sql_r1);
                        $count_r1   = $db->counter($result_r1);
                        if ($count_r1 > 0) { ?>
                            <div class="row">
                                <div class="col s12">
                                    <table id="page-length-option1" class=" bordered">
                                        <thead>
                                            <tr>
                                                <?php
                                                $headings = '<th>Item Details</th>
                                                            <th>Order Qty</th>
                                                            <th>Total Received Yet</th>
                                                            <th>Receiving Qty</th> 
                                                            <th>Location</th>';
                                                echo $headings; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 0;
                                            if ($count_r1 > 0) {
                                                $row_cl_r1 = $db->fetch($result_r1);
                                                foreach ($row_cl_r1 as $data_r1) {
                                                    $detail_id_r1   = $data_r1['id'];
                                                    $order_qty      = $data_r1['order_qty'];

                                                    $sql_rc1            = "	SELECT a.* 
                                                                                FROM package_materials_order_detail_receive a 
                                                                                WHERE 1=1 
                                                                                AND a.po_detail_id = '" . $detail_id_r1 . "'
                                                                                AND a.enabled = 1 "; //echo $sql_cl;
                                                    $result_rc1         = $db->query($conn, $sql_rc1);
                                                    $total_received_qty = $db->counter($result_rc1);  ?>
                                                    <tr>
                                                        <td style="width: 400px;">
                                                            <?php
                                                            echo $data_r1['package_name'];
                                                            if ($data_r1['category_name'] != '') {
                                                                echo "(" . $data_r1['category_name'] . ")";
                                                            } ?>
                                                        </td>
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

                                                            if (isset($error3[$field_name])) { ?>
                                                                <span class="color-red"><?php
                                                                                        echo $error3[$field_name]; ?>
                                                                </span>
                                                            <?php
                                                            } ?>

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
                                                            ?>
                                                            <span class="color-red"><?php
                                                                                    if (isset($error3[$field_name])) {
                                                                                        echo $error3[$field_name];
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
                            <div class="col m12 s12 text_align_center">
                                <?php if (isset($id) && $id > 0 && (($cmd3 == 'add' || $cmd3 == '') && access("add_perm") == 1)  || ($cmd3 == 'edit' && access("edit_perm") == 1) || ($cmd3 == 'delete' && access("delete_perm") == 1)) { ?>
                                    <button class="btn waves-effect waves-light gradient-45deg-purple-deep-orange" type="submit" name="add">Receive</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php
            $td_padding = "padding:5px 10px !important;";
            if ($count_log2 > 0) { ?>
                <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab3") ?>" method="post">
                    <input type="hidden" name="is_Submit_tab3_4_2" value="Y" />
                    <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                        echo encrypt($_SESSION['csrf_session']);
                                                                    } ?>">
                    <input type="hidden" name="duplication_check_token" value="<?php echo (time() . session_id()); ?>">
                    <input type="hidden" name="active_tab" value="tab3" />
                    <div class="card-panel">
                        <div class="row">
                            <div class="col m12 s12">
                                <h5>Received Items</h5>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col m12 s12">
                                <label>
                                    <input type="checkbox" id="all_checked6" class="filled-in" name="all_checked6" value="1" <?php if (isset($all_checked6) && $all_checked6 == '1') {
                                                                                                                                    echo "checked";
                                                                                                                                } ?> />
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="section section-data-tables">
                            <div class="row">
                                <div class="col m12 s12">
                                    <table id="page-length-option" class="display pagelength50_3 dataTable dtr-inline">
                                        <thead>
                                            <tr>
                                                <?php
                                                $headings = '   <th class="sno_width_60 text_align_center" >S.No</th>
                                                                <th></th>
                                                                <th>Tracking#</th>
                                                                <th>Package / Part Name</th>
                                                                <th>Category</th>
                                                                <th>Location</th>
                                                                <th>Received By</th>
                                                                <th>Receiving Date/Time</th>';
                                                echo $headings;
                                                $headings2 = ' '; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 0;
                                            if ($count_log2 > 0) {
                                                $row_cl1 = $db->fetch($result_log2);
                                                foreach ($row_cl1 as $data) {
                                                    $detail_id2 = $data['id']; ?>
                                                    <tr>
                                                        <td class="text_align_center" style="<?= $td_padding; ?>;"><?php echo $i + 1; ?></td>
                                                        <td style="<?= $td_padding; ?>">
                                                            <?php
                                                            if (access("delete_perm") == 1 && $data['edit_lock'] == "0" && $data['is_diagnost'] == "0") { ?>
                                                                <label style="margin-left: 25px;">
                                                                    <input type="checkbox" name="receviedProductIds[]" id="receviedProductIds[]" value="<?= $detail_id2; ?>" class="checkbox6 filled-in" />
                                                                    <span></span>
                                                                </label>
                                                            <?php } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>"><?php echo $data['tracking_no']; ?></td>
                                                        <td style="<?= $td_padding; ?>"><?php echo $data['package_name']; ?></td>
                                                        <td style="<?= $td_padding; ?>">
                                                            <?php
                                                            if ($data['category_name'] != "") {
                                                                echo "" . $data['category_name'] . "";
                                                            } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>"><?php echo $data['sub_location_name']; ?></td>
                                                        <td style="<?= $td_padding; ?>">
                                                            <?php echo $data['first_name']; ?>
                                                            ( <?php echo $data['username']; ?> )
                                                        </td>
                                                        <td style="<?= $td_padding; ?>">
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
                                <?php if (isset($id) && $id > 0 &&  access("delete_perm") == 1) { ?>
                                    <button class="btn waves-effect waves-light gradient-45deg-purple-deep-orange" type="submit" name="deletepserial">Delete</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </form>
            <?php }
        } else { ?>
            <div class="card-panel">
                <div class="row">
                    <div class="col 24 s12"><br>
                        <div class="card-alert card red lighten-5">
                            <div class="card-content red-text">
                                <p>No logistics information available yet. </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php
        }
    } ?>
</div>