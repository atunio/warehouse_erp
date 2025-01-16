<div id="tab2_html" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab2')) {
                                        echo "block";
                                    } else {
                                        echo "none";
                                    } ?>;">
    <div class="card-panel">
        <div class="row">
            <div class="col s10 m12 l8">
                <h5 class="breadcrumbs mt-0 mb-0"><span>PO Detail</span></h5>
            </div>
        </div>
        <?php
        if (isset($id)) {  ?>
            <div class="row">
                <div class="input-field col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Purchase Order No: </b>" . $po_no; ?></span></h6>
                </div>
                <div class="input-field col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Vendor Invoice No: </b>" . $vender_invoice_no; ?></span></h6>

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
    <?php } ?>
    <?php
    /*
    if (isset($cmd2_1) && $cmd2_1 == 'edit') { ?>
        <div class="card-panel">
            <h5>Update Single Record</h5>
            <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&cmd2=" . $cmd2 . "&cmd2_1=edit&detail_id=" . $detail_id . "&active_tab=tab2") ?>" method="post">
                <input type="hidden" name="is_Submit_tab2_1" value="Y" />
                <input type="hidden" name="po_id" value="<?php if (isset($po_id)) echo $po_id; ?>" />
                <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                    echo encrypt($_SESSION['csrf_session']);
                                                                } ?>">
                <div class="row">
                    <div class="input-field col m4 s12">
                        <?php
                        $field_name     = "courier_name_update";
                        $field_label    = "Courier Name";
                        ?>
                        <i class="material-icons prefix">description</i>
                        <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                            echo ${$field_name};
                                                                                                        } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                                } ?>">
                        <label for="<?= $field_name; ?>">
                            <?= $field_label; ?>
                            <span class="color-red"><?php
                                                    if (isset($error2[$field_name])) {
                                                        echo $error2[$field_name];
                                                    } ?>
                            </span>
                        </label>
                    </div>
                    <?php
                    $field_name     = "shipment_date_update";
                    $field_label     = "Shipment Date (d/m/Y)";
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
                                                        if (isset($error2[$field_name])) {
                                                            echo $error2[$field_name];
                                                        } ?>
                            </span>
                        </label>
                    </div>
                    <?php
                    $field_name     = "expected_receiving_date_update";
                    $field_label     = "Expected Arrival Date (d/m/Y)";
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
                                                        if (isset($error2[$field_name])) {
                                                            echo $error2[$field_name];
                                                        } ?>
                            </span>
                        </label>
                    </div>
                    <div class="input-field col m4 s12">
                        <?php
                        $field_name     = "tracking_no_update";
                        $field_label    = "Tracking No";
                        ?>
                        <i class="material-icons prefix">description</i>
                        <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                            echo ${$field_name};
                                                                                                        } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                                } ?>">
                        <label for="<?= $field_name; ?>">
                            <?= $field_label; ?>
                            <span class="color-red"> * <?php
                                                        if (isset($error2[$field_name])) {
                                                            echo $error2[$field_name];
                                                        } ?>
                            </span>
                        </label>
                    </div>
                    <div class="input-field col m4 s12">
                        <?php
                        $field_name     = "status_id_update";
                        $field_label     = "Status";
                        $sql1             = "SELECT * FROM inventory_status WHERE enabled = 1 AND id IN(" . $logistics_page_status . ") ORDER BY status_name ";
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
                                                            if (isset($error2[$field_name])) {
                                                                echo $error2[$field_name];
                                                            } ?>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="input-field col m4 s12">
                        <?php
                        $field_name     = "logistics_cost_update";
                        $field_label    = "Logistics Cost";
                        ?>
                        <i class="material-icons prefix">attach_money</i>
                        <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                            echo ${$field_name};
                                                                                                        } ?>" class="twoDecimalNumber validate  <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                                                } ?>">
                        <label for="<?= $field_name; ?>">
                            <?= $field_label; ?>
                            <span class="color-red"> * <?php
                                                        if (isset($error2[$field_name])) {
                                                            echo $error2[$field_name];
                                                        } ?>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col m4 s12"></div>
                    <div class="input-field col m4 s12">
                        <?php if (isset($id) && $id > 0 && ($cmd2 == 'add' && access("add_perm") == 1)  || ($cmd2 == 'edit' && access("edit_perm") == 1)) { ?>
                            <button class="waves-effect waves-light  btn gradient-45deg-purple-deep-orange box-shadow-none border-round mr-1 mb-1" type="submit" name="update_logistics">Update</button>
                        <?php } ?>
                    </div>
                    <div class="input-field col m4 s12">
                        <br>
                        <a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&cmd2=" . $cmd2 . "&active_tab=tab2") ?>">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
        <?php
    } 
    */

    $td_padding = "padding:5px 15px !important;";

    if (isset($id)) {
        $sql             = " SELECT a.*, d.po_no, d.po_date, e.vender_name, d.vender_invoice_no, f.status_name, 
                                g.product_desc, g.product_uniqueid, h.category_name, b.overall_grade, b.price, 
                                i.sub_location_name, i.sub_location_type, a.is_repaired, a.edit_lock,
                                b.base_product_id, b.sub_product_id, b.serial_no_barcode
                            FROM purchase_order_detail_receive_rma a 
                            INNER JOIN purchase_order_detail_receive b ON b.id = a.receive_id
                            INNER JOIN purchase_order_detail c ON c.id = b.po_detail_id
                            INNER JOIN purchase_orders d ON d.id = c.po_id
                            LEFT JOIN venders e ON e.id = d.vender_id
                            INNER JOIN inventory_status f ON f.id = a.status_id
                            LEFT JOIN products g ON g.id = c.product_id
                            LEFT JOIN product_categories h ON h.id = g.product_category
                            LEFT JOIN warehouse_sub_locations i ON i.id = b.sub_location_id_after_diagnostic 
                            WHERE c.po_id = '" . $id . "' 
                            AND a.status_id != 19   
                            ORDER BY a.id DESC"; // status_id => 19 =  Repair
        // echo $sql;
        $result_log     = $db->query($conn, $sql);
        $count_log      = $db->counter($result_log);
        if ($count_log > 0) { ?>
            <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&cmd2=" . $cmd2 . "&active_tab=tab2") ?>" method="post">
                <input type="hidden" name="is_Submit_tab2" value="Y" />
                <input type="hidden" name="po_id" value="<?php if (isset($po_id)) echo $po_id; ?>" />
                <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                    echo encrypt($_SESSION['csrf_session']);
                                                                } ?>">
                <div class="card-panel">
                    <div class="row">
                        <div class="col m12 s12">
                            <h5>Logistics Detail</h5>
                            <table class="bordered">
                                <thead>
                                    <tr>
                                        <?php
                                        $headings = '	<th class="sno_width_60">S.No</th>
                                                        <th class="sno_width_60">';
                                        if (po_permisions("Retrun Logistics") == 1) {
                                            $headings .= '
                                                            <label>
                                                                <input type="checkbox" id="all_checked2" class="filled-in" name="all_checked2" value="1"   ';
                                            if (isset($all_checked2) && $all_checked2 == '1') {
                                                $headings .= ' checked ';
                                            }
                                            $headings .= ' 			/>
                                                                <span>All</span>
                                                            </label>';
                                        }
                                        $headings .= '
                                                        </th>
                                                        <th>Product ID</th>
                                                        <th>SeriaL#</th>
                                                        <th>Original Grade</th> 
                                                        <th>Location</th> 
                                                        <th>RMA Status</th>
                                                        <th>Action</th>';
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
                                            $detail_id2     = $data['id'];
                                            $edit_lock      = $data['edit_lock'];
                                            $is_repaired    = $data['is_repaired']; ?>
                                            <tr>
                                                <td style="<?= $td_padding; ?>"><?php echo $i + 1; ?></td>
                                                <td style="text-align: center; <?= $td_padding; ?>">
                                                    <?php
                                                    if (po_permisions("Retrun Logistics") == 1 && $edit_lock == '0' && $is_repaired == '0') { ?>
                                                        <label style="margin-left: 25px;">
                                                            <input type="checkbox" name="logistic_rma_ids[]" id="logistic_rma_ids[]" value="<?= $detail_id2; ?>" <?php
                                                                                                                                                                    if (isset($logistic_rma_ids) && in_array($detail_id2, $logistic_rma_ids)) {
                                                                                                                                                                        echo "checked";
                                                                                                                                                                    } ?> class="checkbox2 filled-in" />
                                                            <span></span>
                                                        </label>
                                                    <?php } ?>
                                                </td>
                                                <td style="<?= $td_padding; ?>">
                                                    <?php echo $data['product_uniqueid']; ?>
                                                </td>
                                                <td><?php echo $data['serial_no_barcode']; ?></td>
                                                <td><?php echo $data['overall_grade']; ?></td>
                                                <td style="<?= $td_padding; ?>">
                                                    <?php
                                                    echo $data['sub_location_name'];
                                                    if ($data['sub_location_type'] != "") {
                                                        echo " (" . $data['sub_location_type'] . ")";
                                                    } ?>
                                                </td>
                                                <td>
                                                    <span class="chip green lighten-5">
                                                        <span class="green-text">
                                                            <?php echo $data['status_name'];  ?>
                                                        </span>
                                                    </span>
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

                    <div class="row">
                        <div class="input-field col m12 s12">
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col m4 s12">
                            <?php
                            $field_name     = "courier_name";
                            $field_label    = "Courier Name";
                            ?>
                            <i class="material-icons prefix">description</i>
                            <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                echo ${$field_name};
                                                                                                            } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                                    } ?>">
                            <label for="<?= $field_name; ?>">
                                <?= $field_label; ?>
                                <span class="color-red"> * <?php
                                                            if (isset($error2[$field_name])) {
                                                                echo $error2[$field_name];
                                                            } ?>
                                </span>
                            </label>
                        </div>
                        <?php
                        $field_name     = "shipment_date";
                        $field_label     = "Shipment Date (d/m/Y)";
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
                                                            if (isset($error2[$field_name])) {
                                                                echo $error2[$field_name];
                                                            } ?>
                                </span>
                            </label>
                        </div>
                        <?php
                        $field_name     = "expected_receiving_date";
                        $field_label     = "Expected Arrival Date (d/m/Y)";
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
                                                            if (isset($error2[$field_name])) {
                                                                echo $error2[$field_name];
                                                            } ?>
                                </span>
                            </label>
                        </div>
                        <div class="input-field col m4 s12">
                            <?php
                            $field_name     = "tracking_no";
                            $field_label    = "Tracking No";
                            ?>
                            <i class="material-icons prefix">description</i>
                            <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                echo ${$field_name};
                                                                                                            } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                                    } ?>">
                            <label for="<?= $field_name; ?>">
                                <?= $field_label; ?>
                                <span class="color-red"> * <?php
                                                            if (isset($error2[$field_name])) {
                                                                echo $error2[$field_name];
                                                            } ?>
                                </span>
                            </label>
                        </div>
                        <div class="input-field col m4 s12">
                            <?php
                            $field_name     = "status_id";
                            $field_label     = "Status";
                            $sql1             = "SELECT * FROM inventory_status WHERE enabled = 1 AND id IN(" . $logistics_page_status . ") ORDER BY status_name ";
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
                                                                if (isset($error2[$field_name])) {
                                                                    echo $error2[$field_name];
                                                                } ?>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="input-field col m4 s12">
                            <?php
                            $field_name     = "logistics_cost";
                            $field_label    = "Logistics Cost";
                            ?>
                            <i class="material-icons prefix">attach_money</i>
                            <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                echo ${$field_name};
                                                                                                            } ?>" class="twoDecimalNumber validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                                                    } ?>">
                            <label for="<?= $field_name; ?>">
                                <?= $field_label; ?>
                                <span class="color-red"><?php
                                                        if (isset($error2[$field_name])) {
                                                            echo $error2[$field_name];
                                                        } ?>
                                </span>
                            </label>
                        </div>
                    </div>


                    <div class="row">
                        <div class="input-field col m4 s12"></div>
                        <div class="input-field col m4 s12">
                            <?php if (isset($id) && $id > 0 && po_permisions("Retrun Logistics") == 1) { ?>
                                <button class="waves-effect waves-light  btn gradient-45deg-purple-deep-orange box-shadow-none border-round mr-1 mb-1" type="submit" name="update_logistics">Update</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </form>
    <?php }
    }
    /* ?>
    <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&cmd2=" . $cmd2 . "&active_tab=tab2") ?>" method="post">
        <div class="card-panel">
            <input type="hidden" name="is_Submit_tab2" value="Y" />
            <input type="hidden" name="po_id" value="<?php if (isset($po_id)) echo $po_id; ?>" />
            <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                echo encrypt($_SESSION['csrf_session']);
                                                            } ?>">
            <div class="row">
                <div class="input-field col m4 s12">
                    <?php
                    $field_name     = "courier_name";
                    $field_label    = "Courier Name";
                    ?>
                    <i class="material-icons prefix">description</i>
                    <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                        echo ${$field_name};
                                                                                                    } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                echo ${$field_name . "_valid"};
                                                                                                                            } ?>">
                    <label for="<?= $field_name; ?>">
                        <?= $field_label; ?>
                        <span class="color-red"><?php
                                                if (isset($error2[$field_name])) {
                                                    echo $error2[$field_name];
                                                } ?>
                        </span>
                    </label>
                </div>
                <?php
                $field_name     = "shipment_date";
                $field_label     = "Shipment Date (d/m/Y)";
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
                                                    if (isset($error2[$field_name])) {
                                                        echo $error2[$field_name];
                                                    } ?>
                        </span>
                    </label>
                </div>
                <?php
                $field_name     = "expected_receiving_date";
                $field_label     = "Expected Arrival Date (d/m/Y)";
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
                                                    if (isset($error2[$field_name])) {
                                                        echo $error2[$field_name];
                                                    } ?>
                        </span>
                    </label>
                </div>
                <div class="input-field col m4 s12">
                    <?php
                    $field_name     = "tracking_no";
                    $field_label    = "Tracking No";
                    ?>
                    <i class="material-icons prefix">description</i>
                    <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                        echo ${$field_name};
                                                                                                    } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                echo ${$field_name . "_valid"};
                                                                                                                            } ?>">
                    <label for="<?= $field_name; ?>">
                        <?= $field_label; ?>
                        <span class="color-red"> * <?php
                                                    if (isset($error2[$field_name])) {
                                                        echo $error2[$field_name];
                                                    } ?>
                        </span>
                    </label>
                </div>
                <div class="input-field col m4 s12">
                    <?php
                    $field_name     = "status_id";
                    $field_label     = "Status";
                    $sql1             = "SELECT * FROM inventory_status WHERE enabled = 1 AND id IN(" . $logistics_page_status . ") ORDER BY status_name ";
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
                                                        if (isset($error2[$field_name])) {
                                                            echo $error2[$field_name];
                                                        } ?>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="input-field col m4 s12">
                    <?php
                    $field_name     = "logistics_cost";
                    $field_label    = "PO Logistics Cost";
                    ?>
                    <i class="material-icons prefix">attach_money</i>
                    <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                        echo ${$field_name};
                                                                                                    } ?>" class="twoDecimalNumber validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                echo ${$field_name . "_valid"};
                                                                                                                                            } ?>">
                    <label for="<?= $field_name; ?>">
                        <?= $field_label; ?>
                        <span class="color-red"> * <?php
                                                    if (isset($error2[$field_name])) {
                                                        echo $error2[$field_name];
                                                    } ?>
                        </span>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col m4 s12"></div>
                <div class="input-field col m4 s12">
                    <?php if (isset($id) && $id > 0 && ($cmd2 == 'add' && access("add_perm") == 1)  || ($cmd2 == 'edit' && access("edit_perm") == 1)) { ?>
                        <button class="waves-effect waves-light  btn gradient-45deg-purple-deep-orange box-shadow-none border-round mr-1 mb-1" type="submit" name="add">Create</button>
                    <?php } ?>
                </div>
                <div class="input-field col m4 s12"></div>
            </div>
        </div>
    </form>
    <?php  */ ?>
</div>