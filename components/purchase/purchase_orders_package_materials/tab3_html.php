<div id="tab3_html" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab3')) {
                                        echo "block";
                                    } else {
                                        echo "none";
                                    } ?>;">
    <div class="card-panel" style="padding-top: 5px; padding-bottom: 5px; margin-top: 0px; margin-bottom: 5px;">
        <div class="row">
            <div class="input-field col m6 s12" style="margin-top: 3px; margin-bottom: 3px;">
                <h6 class="media-heading">
                    <?= $general_heading; ?> --> Receive
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
                            <h6>Receive Items</h6>
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
                            $field_name     = "logistic_id_for_receive";
                            $field_label    = "Box";
                            ?>
                            <div class="input-field col m6 s12">
                                <?php
                                $sql            = " SELECT e.id, e.tracking_no, e.box_no, e.box_qty, c.package_name, d.category_name, c.sku_code, IFNULL(SUM(f.enabled), 0) AS total_received
                                                    FROM package_materials_order_detail a 
                                                    INNER JOIN package_materials_orders b ON b.id = a.po_id
                                                    INNER JOIN packages c ON c.id = a.package_id
                                                    INNER JOIN product_categories d ON d.id = c.product_category
                                                    INNER JOIN package_materials_order_detail_logistics e ON e.po_detail_id = a.id
                                                    LEFT JOIN package_materials_order_detail_receive f ON f.po_detail_id = a.id
                                                    WHERE 1         = 1 
                                                    AND a.po_id     = '" . $id . "' 
                                                    AND e.edit_lock = 0
                                                    AND a.enabled   = 1
                                                    GROUP BY e.id
                                                    ORDER BY c.package_name, d.category_name "; // echo $sql;
                                $result_log2_2    = $db->query($conn, $sql);
                                $count_r2_2       = $db->counter($result_log2_2); ?>
                                <i class="material-icons prefix">add_shopping_cart</i>
                                <div class="select2div">
                                    <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                                                                    } ?>">
                                        <?php
                                        if ($count_r2_2 > 1 && $_SERVER['HTTP_HOST'] != HTTP_HOST_IP) { ?>
                                            <option value="">Select</option>
                                            <?php
                                        }
                                        if ($count_r2_2 > 0) {
                                            $row_r2_2    = $db->fetch($result_log2_2);
                                            foreach ($row_r2_2 as $data_r2) {
                                                $detail_id_r1       = $data_r2['id'];
                                                $box_no             = $data_r2['box_no'];
                                                $box_qty            = $data_r2['box_qty']; ?>
                                                <option value="<?php echo $data_r2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data_r2['id']) { ?> selected="selected" <?php } ?>>
                                                    <?php
                                                    echo "" . ucwords(strtolower($data_r2['package_name']));
                                                    if ($data_r2['category_name'] != "") {
                                                        echo " (" . $data_r2['category_name'] . ") ";
                                                    }
                                                    echo " - SKU: " . $data_r2['sku_code'] . " - ";
                                                    echo " - Tracking#: " . $data_r2['tracking_no'] . " - ";
                                                    echo " - Box#: " . $box_no;
                                                    echo " - Box Qty: " . $box_qty;
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
                            <div class="input-field col m3 s12">
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
                                                <option value="<?php echo $data_r2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data_r2['id']) { ?> selected="selected" <?php } ?>>
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
                            <div class="col m2 s12 text_align_center">
                                <br>
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
                                    b.order_case_pack, b.order_qty AS total_qty, COUNT(c2.id) AS total_received_qty, 
                                    CEIL(COUNT(c2.id)/b.order_case_pack) AS total_case_pack, f.id AS package_logistic_id, f.box_no, f.box_qty
                                FROM package_materials_orders a
                                INNER JOIN package_materials_order_detail b ON a.id = b.po_id
                                INNER JOIN package_materials_order_detail_logistics f ON f.po_detail_id = b.id 
                                INNER JOIN package_materials_order_detail_receive c2 ON c2.po_detail_id = b.id AND c2.logistic_id = f.id
                                INNER JOIN packages c ON c.id = b.package_id
                                LEFT JOIN product_categories d ON d.id =c.product_category
                                LEFT JOIN users e ON e.id = a.add_by_user_id
                                LEFT JOIN warehouse_sub_locations g ON g.id = c2.sub_location_id
                                WHERE a.id = '" . $id . "'
                                GROUP BY c2.po_detail_id, f.id, g.sub_location_name
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
                            <div class="col m2 s12">
                                <h5>Received Items</h5>
                            </div>
                            <div class="col m9 s12">
                                <div class="text_align_right">
                                    <?php
                                    $table_columns    = array('SNo', 'check_all', 'Package / Part Detail', 'SKU', 'Tracking No', 'Box No', 'Box Qty', 'Box Case Packs', 'Location', 'Received By / Receiving Date/Time');
                                    $k                 = 0;
                                    foreach ($table_columns as $data_c1) { ?>
                                        <label>
                                            <input type="checkbox" value="<?= $k ?>" name="table_columns[]" class="filled-in toggle-column" data-column="<?= set_table_headings($data_c1) ?>" checked="checked">
                                            <span><?= $data_c1 ?></span>
                                        </label>&nbsp;&nbsp;
                                    <?php
                                        $k++;
                                    } ?>
                                </div>
                            </div>
                            <div class="col m1 s12">
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
                                                $headings = "";
                                                foreach ($table_columns as $data_c) {
                                                    if ($data_c == 'SNo') {
                                                        $headings .= '<th class="sno_width_60 col-' . set_table_headings($data_c) . '">' . $data_c . '</th>';
                                                    } else if ($data_c == 'check_all') {
                                                        $headings .= '<th class="sno_width_60 col-' . set_table_headings($data_c) . '"></th>';
                                                    } else {
                                                        $headings .= '<th class="col-' . set_table_headings($data_c) . '">' . $data_c . '</th> ';
                                                    }
                                                }
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
                                                    $column_no  = 0;
                                                    $detail_id2 = $data['package_logistic_id'];  ?>
                                                    <tr>
                                                        <td class="text_align_center" style="<?= $td_padding; ?>;" class="col-<?= set_table_headings($table_columns[$column_no]); ?>">
                                                            <?php echo $i + 1;
                                                            $column_no++; ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[$column_no]); ?>">
                                                            <?php
                                                            $column_no++;
                                                            if (access("delete_perm") == 1 && $data['edit_lock'] == "0" && $data['is_diagnost'] == "0") { ?>
                                                                <label style="margin-left: 25px;">
                                                                    <input type="checkbox" name="receviedProductIds[]" id="receviedProductIds[]" value="<?= $detail_id2; ?>_<?= $data['sub_location_id']; ?>" class="checkbox6 filled-in" />
                                                                    <span></span>
                                                                </label>
                                                            <?php } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[$column_no]); ?>">
                                                            <?php echo ucwords(strtolower($data['package_name']));
                                                            $column_no++;
                                                            if ($data['category_name'] != "") {
                                                                echo " (" . $data['category_name'] . ") ";
                                                            } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>" style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[$column_no]); ?>"> <?php echo $data['sku_code']; ?></td>
                                                        <td style="<?= $td_padding; ?>" style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[$column_no]); ?>"><?php echo $data['tracking_no']; ?></td>
                                                        <td style="<?= $td_padding; ?>" style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[$column_no]); ?>"><?php echo $data['box_no']; ?></td>
                                                        <td style="<?= $td_padding; ?>" style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[$column_no]); ?>"><?php echo $data['box_qty']; ?></td>
                                                        <td class="text_align_center" style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[$column_no]); ?>"><?php echo $data['total_case_pack']; ?></td>
                                                        <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[$column_no]); ?>">
                                                            <?php
                                                            $column_no++;
                                                            echo $data['sub_location_name'];
                                                            if ($data['sub_location_type'] != "") {
                                                                echo " (" . $data['sub_location_type'] . ") ";
                                                            } ?>
                                                        </td>
                                                        <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[$column_no]); ?>">
                                                            <?php
                                                            $column_no++;
                                                            echo $data['first_name']; ?>
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