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
                <div class=" col m2 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>PO#:</b>" . $po_no; ?></span></h6>
                </div>
                <div class=" col m3 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Order Date: </b>" . $order_date_disp; ?></span></h6>
                </div>
                <div class=" col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Vendor Invoice#: </b>" . $vender_invoice_no; ?></span></h6>
                </div>
                <div class="input-field col m3 s12">
                    <span class="chip green lighten-5">
                        <span class="green-text">
                            <?php echo $disp_status_name; ?>
                        </span>
                    </span>
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
        $sql            = " SELECT a.*
                            FROM package_materials_order_detail_logistics a
                            WHERE a.po_id = '" . $id . "' 
                            ORDER BY a.tracking_no "; // echo $sql; 
        $result_log     = $db->query($conn, $sql);
        $count_log      = $db->counter($result_log);
        if ($count_log > 0) { ?>
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
                            <?php
                            $field_name     = "package_order_detail_id";
                            $field_label    = "Package";
                            ?>
                            <div class="input-field col m8 s12">
                                <?php
                                $sql            = " SELECT a.*, e.tracking_no, c.package_name, d.category_name, c.sku_code, IFNULL(sum(f.enabled), 0) as total_received
                                                    FROM package_materials_order_detail a 
                                                    INNER JOIN package_materials_orders b ON b.id = a.po_id
                                                    INNER JOIN packages c ON c.id = a.package_id
                                                    INNER JOIN product_categories d ON d.id = c.product_category
                                                    INNER JOIN package_materials_order_detail_logistics e ON e.po_detail_id = a.id
                                                    LEFT JOIN package_materials_order_detail_receive f ON f.po_detail_id = a.id
                                                    WHERE 1=1 
                                                    AND a.po_id = '" . $id . "' 
                                                    AND a.enabled = 1
                                                    GROUP BY a.id
                                                    ORDER BY c.package_name, d.category_name ";
                                // echo $sql;
                                $result_log2_2    = $db->query($conn, $sql);
                                $count_r2_2       = $db->counter($result_log2_2); ?>
                                <i class="material-icons prefix">add_shopping_cart</i>
                                <div class="select2div">
                                    <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                                                                    } ?>">
                                        <?php
                                        if ($count_r2_2 > 1) { ?>
                                            <option value="">Select</option>
                                            <?php
                                        }
                                        if ($count_r2_2 > 0) {
                                            $row_r2_2    = $db->fetch($result_log2_2);
                                            foreach ($row_r2_2 as $data_r2) {
                                                $detail_id_r1       = $data_r2['id'];
                                                $order_qty          = $data_r2['order_qty'];
                                                $total_received     = $data_r2['total_received'];
                                                $order_case_pack    = $data_r2['order_case_pack']; ?>
                                                <option value="<?php echo $data_r2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data_r2['id']) { ?> selected="selected" <?php } ?>>
                                                    <?php
                                                    echo "Tracking#: " . $data_r2['tracking_no'] . " - ";
                                                    echo "" . ucwords(strtolower($data_r2['package_name']));
                                                    if ($data_r2['category_name'] != "") {
                                                        echo " (" . $data_r2['category_name'] . ") ";
                                                    }
                                                    echo " - Expected Qty: " . $order_qty;
                                                    if ($order_qty > 0 && $order_case_pack > 0) {
                                                        echo " - Expected Case Packs: " . ceil($order_qty / $order_case_pack);
                                                    }
                                                    if ($total_received > 0 && $order_case_pack > 0) {
                                                        echo " - Received Case Packs: " . ceil($total_received / $order_case_pack);
                                                    }

                                                    ?>
                                                </option>
                                        <?php }
                                        } ?>
                                    </select>
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red"> * <?php
                                                                    if (isset($error3[$field_name])) {
                                                                        echo $error3[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <?php
                            $field_name     = "receiving_location";
                            $field_label    = "Location";
                            ?>
                            <div class="input-field col m4 s12">
                                <?php
                                $sql            = " SELECT * FROM warehouse_sub_locations a WHERE a.enabled = 1  ORDER BY sub_location_name "; // echo $sql; 
                                $result_log2_1  = $db->query($conn, $sql);
                                $count_r2       = $db->counter($result_log2_1); ?>
                                <i class="material-icons prefix">add_shopping_cart</i>
                                <div class="select2div">
                                    <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                                                                    } ?>">
                                        <option value="">Select</option>
                                        <?php
                                        if ($count_r2 > 0) {
                                            $row_r2    = $db->fetch($result_log2_1);
                                            foreach ($row_r2 as $data_r2) {  ?>
                                                <option value="<?php echo $data_r2['id']; ?>" <?php if (isset($receiving_location_val) && $receiving_location_val == $data_r2['id']) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $data_r2['sub_location_name'];
                                                    if ($data_r2['sub_location_type'] != "") {
                                                        echo " (" . ucwords(strtolower($data_r2['sub_location_type'])) . ")";
                                                    } ?>
                                                </option>
                                        <?php }
                                        } ?>
                                    </select>
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red"> * <?php
                                                                    if (isset($error3[$field_name])) {
                                                                        echo $error3[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
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
            $sql            = " SELECT  c2.*, c.package_name, d.category_name, c.sku_code,
                                        e.first_name, e.middle_name, e.last_name, e.username, f.tracking_no, g.sub_location_name, g.sub_location_type,
                                        c2.po_detail_id, order_case_pack, b.order_qty AS total_qty, COUNT(c2.id) AS total_received_qty, 
                                        CEIL(COUNT(c2.id)/b.order_case_pack) AS total_case_pack, f.id as package_logistic_id
                                FROM package_materials_orders a
                                INNER JOIN package_materials_order_detail b ON a.id = b.po_id
                                INNER JOIN package_materials_order_detail_receive c2 ON c2.po_detail_id = b.id
                                INNER JOIN packages c ON c.id = b.package_id
                                LEFT JOIN product_categories d ON d.id =c.product_category
                                LEFT JOIN users e ON e.id = a.add_by_user_id
                                INNER JOIN package_materials_order_detail_logistics f ON f.po_detail_id = b.id
                                LEFT JOIN warehouse_sub_locations g ON g.id = c2.sub_location_id
                                WHERE a.id = '" . $id . "'  
                                GROUP BY c2.po_detail_id, g.sub_location_name
                                ORDER BY d.category_name, c.package_name, g.sub_location_name ";
            $result_log2     = $db->query($conn, $sql);
            $count_log2      = $db->counter($result_log2);
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
                                <a class="btn waves-effect waves-light gradient-45deg-purple-deep-orange" href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/print_packages_receive_labels_pdf.php?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&id=" . $id)  ?>" target="_blank">
                                    <i class="material-icons dp48">print</i>
                                </a>
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
                                                                <th>Package / Part Detail</th>
                                                                <th>SKU</th>
                                                                <th>Received Qty</th>
                                                                <th>Received Case Packs</th>
                                                                <th>Location</th>
                                                                <th>
                                                                    Received By</br>
                                                                    Receiving Date/Time
                                                                </th> ';
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
                                                    $detail_id2             = $data['po_detail_id'];
                                                    $package_logistic_id    = $data['package_logistic_id']; ?>
                                                    <tr>
                                                        <td class="text_align_center" style="<?= $td_padding; ?>;"><?php echo $i + 1; ?></td>
                                                        <td style="<?= $td_padding; ?>">
                                                            <?php
                                                            if (access("delete_perm") == 1 && $data['edit_lock'] == "0" && $data['is_diagnost'] == "0") { ?>
                                                                <label style="margin-left: 25px;">
                                                                    <input type="checkbox" name="receviedProductIds[]" id="receviedProductIds[]" value="<?= $detail_id2; ?>_<?= $data['sub_location_id']; ?>" class="checkbox6 filled-in" />
                                                                    <span></span>
                                                                </label>
                                                            <?php } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>"><?php echo $data['tracking_no']; ?></td>
                                                        <td style="<?= $td_padding; ?>">
                                                            <?php echo ucwords(strtolower($data['package_name'])); ?>
                                                            <?php
                                                            if ($data['category_name'] != "") {
                                                                echo " (" . $data['category_name'] . ") ";
                                                            } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>"> <?php echo $data['sku_code']; ?></td>
                                                        <td class="text_align_center" style="<?= $td_padding; ?>"><?php echo $data['total_received_qty']; ?></td>
                                                        <td class="text_align_center" style="<?= $td_padding; ?>"><?php echo $data['total_case_pack']; ?></td>
                                                        <td style="<?= $td_padding; ?>">

                                                            <?php echo $data['sub_location_name']; ?>
                                                            <?php
                                                            if ($data['sub_location_type'] != "") {
                                                                echo " (" . $data['sub_location_type'] . ") ";
                                                            } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>">
                                                            <?php echo $data['first_name']; ?>
                                                            ( <?php echo $data['username']; ?> )
                                                            <br>
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