<div id="tab3_html" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab3')) {
                                        echo "block";
                                    } else {
                                        echo "none";
                                    } ?>;">
    <div class="card-panel" style="padding-top: 5px; padding-bottom: 5px; margin-top: 0px; margin-bottom: 5px;">
        <div class="row">
            <div class="input-field col m6 s12" style="margin-top: 3px; margin-bottom: 3px;">
                <h6 class="media-heading">
                    <?= $general_heading; ?> => Arrival
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
                    <h6 class="media-heading"><span class=""><?php echo "<b>PO#:</b>" . $po_no; ?></span></h6>
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
            </div>
        <?php }  ?>
    </div>

    <?php
    if (!isset($id) || (isset($id) && $id == '')) { ?>
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
    <?php } else if (isset($id) && $id > 0 && isset($cmd3_1) && $cmd3_1 == 'edit') { ?>
        <div class="card-panel">
            <h5>Update Single Record</h5>
            <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&cmd3_1=edit&detail_id=" . $detail_id . "&active_tab=tab3") ?>" method="post">
                <input type="hidden" name="is_Submit_tab3_1" value="Y" />
                  <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                    echo encrypt($_SESSION['csrf_session']);
                                                                } ?>">
                <div class="row">
                    
                    <div class="input-field col m3 s12">
                        <?php
                        $field_name     = "update_logistics_id";
                        $field_label    = "Tracking#";
                        $sql3            = "SELECT a.* FROM purchase_order_detail_logistics a WHERE a.po_id = '" . $id . "'";
                        if (isset($detail_id) && $detail_id != "" && isset($cmd3) && $cmd3 == "add") {
                            $sql3        .= " AND a.tracking_no = '" . $detail_id . "'";
                        }
                        $sql3           .= " ORDER BY a.tracking_no ";
                        // echo $sql3;
                        $result3        = $db->query($conn, $sql3);
                        $count3         = $db->counter($result3);
                        ?>
                        <i class="material-icons prefix">question_answer</i>
                        <div class="select2div">
                            <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                echo ${$field_name . "_valid"};
                                                                                                                                                            } ?>">
                                <option value="">Select</option>
                                <?php
                                if ($count3 > 0) {
                                    $row3    = $db->fetch($result3);
                                    foreach ($row3 as $data2) { ?>
                                        <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>>
                                            <?php echo $data2['tracking_no']; ?>
                                        </option>
                                <?php }
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
                    <div class="input-field col m3 s12">
                        <?php
                        $field_name     = "update_sub_location_id";
                        $field_label    = "Location";
                        $sql1           = "SELECT * FROM warehouse_sub_locations a WHERE a.enabled = 1 AND a.purpose = 'Arriving' ORDER BY sub_location_name ";
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
                                                            if (isset($error3[$field_name])) {
                                                                echo $error3[$field_name];
                                                            } ?>
                                </span>
                            </label>
                        </div>
                    </div>
                    <?php
                    $field_name     = "update_arrived_date";
                    if (!isset(${$field_name})) {
                        ${$field_name} = date('d/m/Y');
                    }
                    $field_label     = "Arrival Date (d/m/Y)";
                    ?>
                    <div class="input-field col m2 s12">
                        <i class="material-icons prefix">date_range</i>
                        <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                            echo ${$field_name};
                                                                                                        } ?>" class=" datepicker validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                echo ${$field_name . "_valid"};
                                                                                                                                            } ?>">
                        <label for="<?= $field_name; ?>">
                            <?= $field_label; ?>
                            <span class="color-red"> *<?php
                                                        if (isset($error3[$field_name])) {
                                                            echo $error3[$field_name];
                                                        } ?>
                            </span>
                        </label>
                    </div>
                    <?php
                    $field_name     = "update_no_of_box_arried";
                    $field_label     = "No of Boxes";
                    ?>
                    <div class="input-field col m2 s12">
                        <i class="material-icons prefix">question_answer</i>
                        <input id="<?= $field_name; ?>" type="number" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                            echo ${$field_name};
                                                                                                        } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                                } ?>">
                        <label for="<?= $field_name; ?>">
                            <?= $field_label; ?>
                            <span class="color-red"> *<?php
                                                        if (isset($error3[$field_name])) {
                                                            echo $error3[$field_name];
                                                        } ?>
                            </span>
                        </label>
                    </div>
                    <?php
                    $field_name     = "update_bill_of_landing";
                    $field_label     = "Bill of Landing";
                    ?>
                    <div class="input-field col m2 s12">
                        <div class="file-field input-field">
                            <div class="btn">
                                <span>Browse</span>
                                <input type="hidden" name="old_<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                echo ${$field_name};
                                                                                            } ?>">
                                <input type="file" name="<?= $field_name; ?>" />
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text"
                                    placeholder="<?= $field_label; ?>" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col m6 s12 text_align_right">
                        <?php if (access("add_perm") == 1) { ?>
                            <button class="mb-6 btn waves-effect waves-light gradient-45deg-purple-deep-orange" type="submit" name="update_arrival">Update</button>
                        <?php } ?>
                    </div>
                    <div class="input-field col m6 s12"><br>
                        <a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&cmd2=add&active_tab=tab3") ?>">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
        <?php
    }
    $td_padding = "padding:5px 15px !important;";
    if (isset($id) && $id > 0) { 
        $sql            = " SELECT a.*, b.sub_location_name, b.sub_location_type, c.tracking_no, c.no_of_boxes
                            FROM purchase_order_detail_logistics_receiving a
                            LEFT JOIN warehouse_sub_locations b ON b.id = a.sub_location_id
                            LEFT JOIN purchase_order_detail_logistics c ON c.id = a.logistics_id
                            WHERE a.po_id = '" . $id . "'
                            ORDER BY DATE_FORMAT(a.arrived_date, '%Y%m%d'), a.id ";  
        $result_log     = $db->query($conn, $sql);
        $count_log      = $db->counter($result_log);
        if ($count_log > 0) { ?>
            <div class="card-panel">
                <div class="row">
                    <div class="col m4 s12">
                        <h5>Arrival Detail</h5>
                    </div>
                    <?php
                    if (isset($cmd3) &&  $cmd3 == "add" && isset($detail_id) && $detail_id != "") {  ?>
                        <div class="col m4 s12"><br><br>
                            <a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=" . $cmd . "&cmd3=" . $cmd3 . "&active_tab=tab3&id=" . $id) ?>">All Tracking / Pro #</a>
                        </div> <br>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col m12 s12">
                        <table class="bordered ">
                            <thead>
                                <tr>
                                    <?php
                                    $headings = '	<th>Tracking#</th>
                                                    <th>Arrived Date</th>
                                                    <th>Location</th>
                                                     <th>Arrived Boxes</th>
                                                    <th>Actions</th>';
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
                                        $detail_id2 = $data['id'];
                                        $detail_id1 = $data['logistics_id'];
                                        $sql_pd1                = "	SELECT a.*
                                                                    FROM purchase_order_detail_receive a
                                                                    WHERE a.logistic_id = '" . $detail_id1 . "' ";
                                        $result_pd1             = $db->query($conn, $sql_pd1);
                                        $is_logistic_received   = $db->counter($result_pd1);  ?>
                                        <tr>
                                            <td style="<?= $td_padding; ?>"><?php echo $data['tracking_no']; ?></td>
                                            <td style="<?= $td_padding; ?>"><?php echo dateformat2($data['arrived_date']); ?></td>
                                            <td style="<?= $td_padding; ?>">
                                                <?php
                                                echo $data['sub_location_name'];
                                                $sub_location_type = $data['sub_location_type'];
                                                if (isset($sub_location_type) && $sub_location_type != "") {
                                                    echo " ( " . $sub_location_type . " )";
                                                } ?>
                                            </td>
                                             <td style="text-align: center; <?= $td_padding; ?>"><?php echo $data['no_of_box_arried']; ?></td> 
                                            <td style="<?= $td_padding; ?>">
                                                <a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=" . $cmd . "&cmd3_1=edit&active_tab=tab3&id=" . $id . "&detail_id=" . $detail_id2) ?>">
                                                    <i class="material-icons dp48">edit</i>
                                                </a> &nbsp;
                                                <?php
                                                if (access("delete_perm") == 1 && $data['arrived_date'] != "" && $data['arrived_date'] != null) {
                                                    if ($is_logistic_received == 0) { ?>
                                                        <a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=" . $cmd . "&cmd3_1=delete&active_tab=tab3&id=" . $id . "&detail_id=" . $detail_id2) ?>">
                                                            <i class="material-icons dp48">delete</i>
                                                        </a>
                                                    <?php
                                                    }
                                                }
                                                if ($data['arrived_date'] != NULL && $data['arrived_date'] != '') {  ?>
                                                    &nbsp;&nbsp;
                                                    <a href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/printlabels_pdf.php?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&id=" . $id . "&detail_id=" . $detail_id2) ?>" target="_blank">
                                                        <i class="material-icons dp48">print</i>
                                                    </a>
                                                <?php
                                                }
                                                $bill_of_landing = $data['bill_of_landing'];
                                                if (isset($bill_of_landing) && $bill_of_landing != "") { ?>
                                                    &nbsp;
                                                    <a target="_blank" href="app-assets/bills_of_landing/<?php echo ($bill_of_landing); ?>">
                                                        <i class="material-icons dp48">folder</i>
                                                    </a>
                                                <?php
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
        <?php }
    }
    if (isset($id) && $id > 0 && isset($cmd) && !isset($cmd3_1)) { ?>
        <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=edit&id=" . $id . "&cmd2=add&active_tab=tab3") ?>" method="post" enctype="multipart/form-data">
            <div class="card-panel">
                <input type="hidden" name="is_Submit_tab3" value="Y" />
                 <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                    echo encrypt($_SESSION['csrf_session']);
                                                                } ?>">
                <div class="row">
                    
                    <div class="input-field col m3 s12">
                        <?php
                        $field_name     = "logistics_id";
                        $field_label    = "Tracking#";
                        $sql3           = " SELECT a.*FROM purchase_order_detail_logistics a WHERE a.po_id = '" . $id . "'";
                        if (isset($detail_id) && $detail_id != "" && isset($cmd3) && $cmd3 == "add") {
                            $sql3        .= " AND a.tracking_no = '" . $detail_id . "'";
                        }
                        $sql3           .= " ORDER BY a.tracking_no ";
                        // echo $sql3;
                        $result3        = $db->query($conn, $sql3);
                        $count3         = $db->counter($result3);
                        ?>
                        <i class="material-icons prefix">question_answer</i>
                        <div class="select2div">
                            <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                echo ${$field_name . "_valid"};
                                                                                                                                                            } ?>">
                                <option value="">Select</option>
                                <?php
                                if ($count3 > 0) {
                                    $row3    = $db->fetch($result3);
                                    foreach ($row3 as $data2) { ?>
                                        <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>>
                                            <?php echo $data2['tracking_no']; ?>
                                        </option>
                                <?php }
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
                    <div class="input-field col m3 s12">
                        <?php
                        $field_name     = "sub_location_id";
                        $field_label    = "Location";
                        $sql1           = "SELECT * FROM warehouse_sub_locations a WHERE a.enabled = 1  AND a.purpose = 'Arriving' ORDER BY sub_location_name ";
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
                                                            if (isset($error3[$field_name])) {
                                                                echo $error3[$field_name];
                                                            } ?>
                                </span>
                            </label>
                        </div>
                    </div>
                    <?php
                    $field_name     = "arrived_date";
                    if (!isset(${$field_name})) {
                        ${$field_name} = date('d/m/Y');
                    }
                    $field_label     = "Arrival Date (d/m/Y)";
                    ?>
                    <div class="input-field col m2 s12">
                        <i class="material-icons prefix">date_range</i>
                        <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                            echo ${$field_name};
                                                                                                        } ?>" class=" datepicker validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                echo ${$field_name . "_valid"};
                                                                                                                                            } ?>">
                        <label for="<?= $field_name; ?>">
                            <?= $field_label; ?>
                            <span class="color-red"> *<?php
                                                        if (isset($error3[$field_name])) {
                                                            echo $error3[$field_name];
                                                        } ?>
                            </span>
                        </label>
                    </div>
                    <?php
                    $field_name     = "no_of_box_arried";
                    $field_label     = "No of Boxes";
                    ?>
                    <div class="input-field col m2 s12">
                        <i class="material-icons prefix">question_answer</i>
                        <input id="<?= $field_name; ?>" type="number" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                            echo ${$field_name};
                                                                                                        } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                                } ?>">
                        <label for="<?= $field_name; ?>">
                            <?= $field_label; ?>
                            <span class="color-red"> *<?php
                                                        if (isset($error3[$field_name])) {
                                                            echo $error3[$field_name];
                                                        } ?>
                            </span>
                        </label>
                    </div>
                    <?php
                    $field_name     = "bill_of_landing";
                    $field_label     = "Bill of Landing";
                    ?>
                    <div class="input-field col m2 s12">
                        <div class="file-field input-field">
                            <div class="btn">
                                <span>Browse</span>
                                <input type="hidden" name="old_<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                echo ${$field_name};
                                                                                            } ?>">
                                <input type="file" name="<?= $field_name; ?>" />
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text"
                                    placeholder="<?= $field_label; ?>" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col m12 s12 text_align_center">
                        <?php if (isset($id) && $id > 0 && access("edit_perm") == 1) { ?>
                            <button class="mb-6 btn waves-effect waves-light gradient-45deg-purple-deep-orange" type="submit" name="add">Create</button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </form>
    <?php
    } ?>
</div>