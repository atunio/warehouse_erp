<div id="tab3_html" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab3')) {
                                        echo "block";
                                    } else {
                                        echo "none";
                                    } ?>;">
    <div class="card-panel" style="padding-top: 5px; padding-bottom: 5px;">
        <h5 class="media-heading">
            <?= $general_heading;?> => Arrival &nbsp;&nbsp;
            <a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>" data-target="dropdown1">
                Back PO List
            </a>
        </h5> 
        <?php
        if (isset($id) && isset($po_no)) {  ?>
            <div class="row">
                <div class="input-field col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Purchase Order No: </b>" . $po_no; ?></span></h6>
                </div>
                <div class="input-field col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Vender Invoice No: </b>" . $vender_invoice_no; ?></span></h6>
                </div>
            </div>
            <?php
            if (isset($cmd3) &&  $cmd3 == "add" && isset($detail_id) && $detail_id != "") {  ?>
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
    }
    $td_padding = "padding:5px 15px !important;";
    if (isset($id)) {
        $orderby    = " ORDER BY a.tracking_no ";
        $sql        = " SELECT a.*, c.status_name, d.sub_location_name, d.sub_location_type
                        FROM purchase_order_detail_logistics a
                        LEFT JOIN inventory_status c ON c.id = a.logistics_status
                        LEFT JOIN warehouse_sub_locations d ON d.id = a.sub_location_id
                        WHERE a.po_id = '" . $id . "'";
        if (isset($detail_id) && $detail_id != "" && isset($cmd3) && $cmd3 == "add") {
            $sql        .= " AND a.tracking_no = '" . $detail_id . "'";
        }
        $sql2           = $sql;
        $sql2           .= " AND a.arrived_date IS NOT NULL";
        $sql           .= $orderby;
        $sql2           .= $orderby;
        // echo $sql;
        // $result_log2     = $db->query($conn, $sql2);
        // $count_log2      = $db->counter($result_log2);
        /*
        if ($count_log2 > 0) { ?>
            <div class="card-panel">
                <form action="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/printlabels_pdf.php?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&id=" . $id) ?>" method="post" target="_blank">

                    <div class="row">
                        <div class="col m4 s12">
                            <h5>Print Labels</h5>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        $field_name     = "no_of_labels";
                        $field_label     = "No of Labels";
                        ?>
                        <div class="input-field col m4 s12">
                            <i class="material-icons prefix">description</i>
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
                        <div class="input-field col m2 s12">
                            <?php
                            if (isset($id) && $id > 0 && access("print_perm") == 1) { ?>
                                <br>
                                <button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col m12 s12" type="submit" name="add">Print Labels</button>
                            <?php } ?>
                        </div>
                        <div class="input-field col m4 s12"></div>
                    </div>
                </form>
            </div>
        <?php
        } 
        */ ?>
        <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab3") ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="is_Submit_tab3" value="Y" />
            <input type="hidden" name="po_id" value="<?php if (isset($po_id)) echo $po_id; ?>" />
            <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                echo encrypt($_SESSION['csrf_session']);
                                                            } ?>">
            <input type="hidden" name="active_tab" value="tab3" />
            <?php
            $result_log     = $db->query($conn, $sql);
            $count_log      = $db->counter($result_log);
            if ($count_log > 0) { ?>
                <div class="card-panel">
                    <div class="row">
                        <div class="col m4 s12">
                            <h5>Logistics Detail</h5>
                        </div>
                        <?php
                        if (isset($cmd3) &&  $cmd3 == "add" && isset($detail_id) && $detail_id != "") {  ?>
                            <div class="col m4 s12"><br><br>
                                <a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=" . $cmd . "&cmd3=" . $cmd3 . "&active_tab=tab3&id=" . $id) ?>">All Tracking / Pro #</a>
                            </div> <br>
                        <?php } ?>
                    </div>

                    <label>
                        <input type="checkbox" id="all_checked4" class="filled-in" name="all_checked4" value="1" <?php if (isset($all_checked4) && $all_checked4 == '1') {
                                                                                                                        echo "checked";
                                                                                                                    } ?> />
                        <span>All</span>
                    </label>
                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col m12 s12">
                                <table id="page-length-option" class="display ">
                                    <thead>
                                        <tr>
                                            <?php
                                            $headings = '	<th class="sno_width_60">S.No</th>
                                                            <th>Tracking#</th>
                                                            <th>Courier</th>
                                                            <th>Status</th>
                                                            <th>Arrived Date</th>
                                                            <th>Location</th>
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

                                                $sql_pd1                = "	SELECT a.*
                                                                            FROM purchase_order_detail_receive a
                                                                            WHERE a.logistic_id = '" . $detail_id2 . "' ";
                                                $result_pd1             = $db->query($conn, $sql_pd1);
                                                $is_logistic_received   = $db->counter($result_pd1);

                                                $sql_pd1                = "	SELECT a.*
                                                                            FROM purchase_order_detail_receive a
                                                                            INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
                                                                            WHERE b.po_id = '" . $id . "' ";
                                                $result_pd1             = $db->query($conn, $sql_pd1);
                                                $is_po_item_received    = $db->counter($result_pd1); ?>
                                                <tr>
                                                    <td style="<?= $td_padding; ?>"><?php echo $i + 1; ?>
                                                        <?php
                                                        if ($is_po_item_received == 0) { ?>
                                                            <label style="margin-left: 25px;">
                                                                <input type="checkbox" name="logistics_ids_2[]" id="logistics_ids_2[]" value="<?= $detail_id2; ?>" <?php
                                                                                                                                                                    if (isset($logistics_ids_2) && in_array($detail_id2, $logistics_ids_2)) {
                                                                                                                                                                        echo "checked";
                                                                                                                                                                    } ?> class="checkbox4 filled-in" />
                                                                <span></span>
                                                            </label>
                                                        <?php } ?>
                                                    </td>
                                                    <td style="<?= $td_padding; ?>">
                                                        <a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=" . $cmd . "&cmd3=" . $cmd3 . "&active_tab=tab3&id=" . $id . "&detail_id=" . $data['tracking_no']) ?>">
                                                            <?php echo $data['tracking_no']; ?>
                                                        </a>
                                                    </td>
                                                    <td style="<?= $td_padding; ?>"><?php echo $data['courier_name']; ?></td>
                                                    <td style="<?= $td_padding; ?>"><?php echo $data['status_name']; ?></td>
                                                    <td style="<?= $td_padding; ?>"><?php echo dateformat2($data['arrived_date']); ?></td>
                                                    <td style="<?= $td_padding; ?>">
                                                        <?php
                                                        echo $data['sub_location_name'];
                                                        $sub_location_type = $data['sub_location_type'];
                                                        if (isset($sub_location_type) && $sub_location_type != "") {
                                                            echo " ( " . $sub_location_type . " )";
                                                        } ?>
                                                    </td>
                                                    <td style="<?= $td_padding; ?>">
                                                        <?php
                                                        if (access("delete_perm") == 1 && $data['arrived_date'] != "" && $data['arrived_date'] != null) {
                                                            if ($is_logistic_received == 0) { ?>
                                                                <a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=" . $cmd . "&cmd3=delete&active_tab=tab3&id=" . $id . "&detail_id=" . $detail_id2) ?>">
                                                                    <i class="material-icons dp48">delete</i>
                                                                </a>
                                                            <?php
                                                            }
                                                        }
                                                        $bill_of_landing = $data['bill_of_landing'];
                                                        if (isset($bill_of_landing) && $bill_of_landing != "") { ?>
                                                            &nbsp;
                                                            <a target="_blank" href="app-assets/bills_of_landing/<?php echo ($bill_of_landing); ?>">
                                                                <i class="material-icons dp48">folder</i>
                                                            </a>
                                                        <?php
                                                        }
                                                        if ($data['arrived_date'] != NULL && $data['arrived_date'] != '') {  ?>
                                                            &nbsp;&nbsp;
                                                            <a href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/printlabels_pdf.php?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&id=" . $id . "&detail_id=" . $detail_id2) ?>" target="_blank">
                                                                <i class="material-icons dp48">print</i>
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
                </div>

                <div class="card-panel">
                    <br>
                    <div class="row">
                        <div class="input-field col m4 s12">
                            <?php
                            $field_name     = "sub_location_id";
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
                        <div class="input-field col m4 s12">
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
                    </div>
                    <div class="row">
                        <?php
                        $field_name     = "bill_of_landing";
                        $field_label     = "Bill of Landing";
                        ?>
                        <div class="input-field col m4 s12">
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
                        <div class="input-field col m12 s12"></div>
                    </div>
                    <div class="row">
                        <div class="input-field col m4 s12"></div>
                        <div class="input-field col m4 s12">
                            <?php if (isset($id) && $id > 0 && (($cmd3 == 'add' || $cmd3 == '') && access("add_perm") == 1)  || ($cmd3 == 'edit' && access("edit_perm") == 1) || ($cmd3 == 'delete' && access("delete_perm") == 1)) { ?>
                                <button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col m12 s12" type="submit" name="add">Update</button>
                            <?php } ?>
                        </div>
                        <div class="input-field col m4 s12"></div>
                    </div>
                    <div class="row">
                        <div class="input-field col m12 s12"></div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="card-panel">
                    <div class="row">
                        <div class="col 24 s12"><br>
                            <div class="card-alert card red lighten-5">
                                <div class="card-content red-text">
                                    <p>No logistics information is available. </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </form>
    <?php
    } ?>
</div>