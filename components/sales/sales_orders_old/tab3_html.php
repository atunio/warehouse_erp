<div id="tab3_html" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab3')) {
                                        echo "block";
                                    } else {
                                        echo "none";
                                    } ?>;">
    <?php
    if (isset($id) && isset($so_no)) { ?>
        <div class="card-panel" style="padding-top: 5px; padding-bottom: 5px;">
            <div class="row">
                <div class="input-field col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Sale Order No: </b>" . $so_no; ?></span></h6>
                </div>
                <div class="input-field col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Order Date: </b>" . $order_date_disp; ?></span></h6>
                </div>
                <div class="input-field col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Customer Invoice No: </b>" . $customer_po_no; ?></span></h6>

                </div>
            </div>
            <?php
            if (isset($error4['msg'])) { ?>
                <div class="card-alert card red lighten-5">
                    <div class="card-content red-text">
                        <p><?php echo $error4['msg']; ?></p>
                    </div>
                    <button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            <?php } else if (isset($msg4['msg_success'])) { ?>
                <div class="card-alert card green lighten-5">
                    <div class="card-content green-text">
                        <p><?php echo $msg4['msg_success']; ?></p>
                    </div>
                    <button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            <?php }
            if (isset($error3['msg'])) { ?>
                <div class="card-alert card red lighten-5">
                    <div class="card-content red-text">
                        <p><?php echo $error3['msg']; ?></p>
                    </div>
                    <button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            <?php } else if (isset($msg3['msg_success'])) { ?>
                <div class="card-alert card green lighten-5">
                    <div class="card-content green-text">
                        <p><?php echo $msg3['msg_success']; ?></p>
                    </div>
                    <button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            <?php } ?>
        </div>
        <?php
        $td_padding = "padding:5px 15px !important;";
        ?>
        <div class="card-panel">
            <div class="card-content">
                <form id="barcodeForm2" class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=edit&id=" . $id . "&active_tab=tab3") ?>" method="post">
                    <input type="hidden" name="is_Submit_tab3" value="Y" />
                    <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                        echo encrypt($_SESSION['csrf_session']);
                                                                    } ?>">

                    <h5>Single Packing</h5>
                    <div class="row">
                        <div class="input-field col m12 s12"> </div>
                    </div>
                    <div class="row">
                        <div class="input-field col m2 s12">
                            <?php

                            $field_name     = "packing_type";
                            $field_label    = "Packing Type";
                            $sql1           = "SELECT * FROM packing_types a WHERE a.enabled = 1  ORDER BY packing_type ";
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
                                                <?php echo $data2['packing_type']; ?>
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
                        <div class="input-field col m2 s12">
                            <?php
                            $field_name     = "box_no";
                            $field_label     = "Box #";
                            ?>
                            <i class="material-icons prefix">description</i>
                            <input id="<?= $field_name; ?>" type="number" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                            echo ${$field_name};
                                                                                                                        } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                                                            echo ${$field_name . "_valid"};
                                                                                                                                                                                                        } ?>">
                            <label for="<?= $field_name; ?>">
                                <?= $field_label; ?>
                                <span class="color-red"> * <?php
                                                            if (isset($error3[$field_name])) {
                                                                echo $error3[$field_name];
                                                            } ?>
                                </span>
                            </label>
                        </div>
                        <div class="input-field col m2 s12">
                            <?php
                            $field_name     = "pallet_no";
                            $field_label     = "Pallet #";
                            ?>
                            <i class="material-icons prefix">description</i>
                            <input id="<?= $field_name; ?>" type="number" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                echo ${$field_name};
                                                                                                            } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                                                echo ${$field_name . "_valid"};
                                                                                                                                                                                            } ?>">
                            <label for="<?= $field_name; ?>">
                                <?= $field_label; ?>
                                <span class="color-red"> <?php
                                                            if (isset($error3[$field_name])) {
                                                                echo $error3[$field_name];
                                                            } ?>
                                </span>
                            </label>
                        </div>
                        <div class="input-field col m6 s12">
                            <?php
                            $field_name     = "product_stock_id";
                            $field_label    = "Bar Code";
                            $sql1           = " SELECT c.*, c1.category_name,b.serial_no, b.id As product_stock_id
                                                FROM product_stock b 
                                                INNER JOIN sales_order_detail b1 ON b1.product_stock_id = b.id 
                                                INNER JOIN products c ON c.id = b.product_id
                                                LEFT JOIN product_categories c1 ON c1.id = c.product_category
                                                WHERE b.enabled = 1 
                                                AND b.p_total_stock > 0
                                                AND b.is_packed = 0
                                                GROUP BY b.serial_no
                                                ORDER BY b.serial_no ";
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
                                            <option value="<?php echo $data2['product_stock_id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['product_stock_id']) { ?> selected="selected" <?php } ?>> <?php echo $data2['product_desc']; ?> (<?php echo $data2['category_name']; ?>) - <?php echo $data2['product_uniqueid']; ?> Serial NO : - <?php echo $data2['serial_no']; ?> </option>
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
                    </div>
                    <div class="row">
                        <div class="input-field col m12 s12"></div>
                    </div>
                    <div class="row">
                        <div class="input-field col m4 s12"></div>
                        <div class="input-field col m4 s12">
                            <?php if (isset($id) && $id > 0 && (($cmd6 == 'add' || $cmd6 == '') && access("add_perm") == 1)  || ($cmd6 == 'edit' && access("edit_perm") == 1) || ($cmd6 == 'delete' && access("delete_perm") == 1)) { ?>
                                <button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col m12 s12" type="submit" name="add">Add Packing</button>
                            <?php } ?>
                        </div>
                        <div class="input-field col m4 s12"></div>
                    </div>
                    <div class="row">
                        <div class="input-field col m12 s12"></div>
                    </div>
                </form>
            </div>
        </div>
        <?php
        $sql1       = " SELECT DISTINCT b.packing_type, a.box_no 
                        FROM sales_order_detail_packing a 
                        INNER JOIN packing_types b ON b.id = a.packing_type
                        WHERE a.sale_order_id = '" . $id . "' 
                        AND a.enabled = 1
                        "; // AND a.is_shipped = 0
        $result_dm    = $db->query($conn, $sql1);
        $countdm     = $db->counter($result_dm);
        if ($countdm > 0) { ?>
            <form id="barcodeForm2" class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=edit&id=" . $id . "&active_tab=tab3") ?>" method="post">
                <input type="hidden" name="is_Submit_tab3_3" value="Y" />
                <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                    echo encrypt($_SESSION['csrf_session']);
                                                                } ?>">
                <div class="card-panel">
                    <div class="row">
                        <div class="col m6 s12">
                            <h5>Box Dimensions</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <table id="page-length-option1" class=" bordered">
                                <thead>
                                    <tr>
                                        <?php
                                        $headings = '<th>Box #</th>
                                                    <th>Weight</th>
                                                    <th>Height</th>
                                                    <th>Width</th> ';
                                        echo $headings;
                                        $total_received_qty = ""; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $row_dm = $db->fetch($result_dm);
                                    foreach ($row_dm as $datadm) {
                                        $detail_id_r1 = $datadm['box_no'];
                                        $sql_dm1    = " SELECT * 
                                                        FROM sale_order_box_dimensions
                                                        WHERE box_no      = '" . $detail_id_r1 . "'
                                                        AND sale_order_id = '" . $id . "'";
                                        $result_dm1    = $db->query($conn, $sql_dm1);
                                        $count_dm1    = $db->counter($result_dm1);
                                        if ($count_dm1 > 0) {
                                            $row_dm1 = $db->fetch($result_dm1);
                                            foreach ($row_dm1 as $datadm1) {
                                                $detail_id_r11 = $datadm1['box_no']
                                    ?>
                                                <tr>
                                                    <td style="width: 400px;"><?= $datadm['packing_type']; ?> <?= $datadm['box_no']; ?></td>
                                                    <td style="width: 150px;">
                                                        <input type="hidden" name="box_no_array[]" value="<?php echo $detail_id_r1; ?>" />
                                                        <?php
                                                        $field_name             = "box_weight";
                                                        $field_label            = "Box Weight";
                                                        $set_value    = "";

                                                        if (isset($datadm1['box_weight']) && $datadm1['box_weight'] > 0) {
                                                            $set_value = $datadm1['box_weight'];
                                                        }

                                                        if (isset($error5[$field_name])) { ?>
                                                            <span class="color-red"><?php
                                                                                    echo $error5[$field_name]; ?>
                                                            </span>
                                                        <?php
                                                        } ?>
                                                        <input type="number" placeholder="<?= $field_label; ?>" class="" name="<?= $field_name; ?>[<?= $detail_id_r1; ?>]" value="<?= $set_value; ?>" style=" text-align: center;" />
                                                    </td>
                                                    <td style="width: 150px;">
                                                        <?php

                                                        $field_name     = "box_height";
                                                        $field_label    = "Box Height";
                                                        $set_value      = "";

                                                        if (isset($datadm1['box_height']) && $datadm1['box_height'] > 0) {
                                                            $set_value = $datadm1['box_height'];
                                                        }

                                                        if (isset($error5[$field_name])) { ?>
                                                            <span class="color-red"><?php
                                                                                    echo $error5[$field_name]; ?>
                                                            </span>
                                                        <?php
                                                        } ?>

                                                        <input type="number" placeholder="<?= $field_label; ?>" class="" name="<?= $field_name; ?>[<?= $detail_id_r1; ?>]" value="<?= $set_value; ?>" style=" text-align: center;" />
                                                    </td>
                                                    <td style="width: 150px;">
                                                        <?php

                                                        $field_name     = "box_width";
                                                        $field_label    = "Box Width";
                                                        $set_value      = "";

                                                        if (isset($datadm1['box_width']) && $datadm1['box_width'] > 0) {
                                                            $set_value = $datadm1['box_width'];
                                                        }

                                                        if (isset($error5[$field_name])) { ?>
                                                            <span class="color-red"><?php
                                                                                    echo $error5[$field_name]; ?>
                                                            </span>
                                                        <?php
                                                        } ?>

                                                        <input type="number" placeholder="<?= $field_label; ?>" class="" name="<?= $field_name; ?>[<?= $detail_id_r1; ?>]" value="<?= $set_value; ?>" style=" text-align: center;" />
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td style="width: 400px;"><?= $datadm['packing_type']; ?> <?= $datadm['box_no']; ?></td>
                                                <td style="width: 150px;">
                                                    <input type="hidden" name="box_no_array[]" value="<?php echo $detail_id_r1; ?>" />
                                                    <?php
                                                    $field_name             = "box_weight";
                                                    $field_label            = "Box Weight";
                                                    $set_value    = "";
                                                    if (isset($error5[$field_name])) { ?>
                                                        <span class="color-red"><?php
                                                                                echo $error5[$field_name]; ?>
                                                        </span>
                                                    <?php
                                                    } ?>
                                                    <input type="number" placeholder="<?= $field_label; ?>" class="" name="<?= $field_name; ?>[<?= $detail_id_r1; ?>]" value="<?= $set_value; ?>" style=" text-align: center;" />
                                                </td>
                                                <td style="width: 150px;">
                                                    <?php

                                                    $field_name     = "box_height";
                                                    $field_label    = "Box Height";
                                                    $set_value      = "";
                                                    if (isset($error5[$field_name])) { ?>
                                                        <span class="color-red"><?php
                                                                                echo $error5[$field_name]; ?>
                                                        </span>
                                                    <?php
                                                    } ?>

                                                    <input type="number" placeholder="<?= $field_label; ?>" class="" name="<?= $field_name; ?>[<?= $detail_id_r1; ?>]" value="<?= $set_value; ?>" style=" text-align: center;" />
                                                </td>
                                                <td style="width: 150px;">
                                                    <?php

                                                    $field_name     = "box_width";
                                                    $field_label    = "Box Width";
                                                    $set_value      = "";
                                                    if (isset($error5[$field_name])) { ?>
                                                        <span class="color-red"><?php
                                                                                echo $error5[$field_name]; ?>
                                                        </span>
                                                    <?php
                                                    } ?>

                                                    <input type="number" placeholder="<?= $field_label; ?>" class="" name="<?= $field_name; ?>[<?= $detail_id_r1; ?>]" value="<?= $set_value; ?>" style=" text-align: center;" />
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } ?>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col m12 s12"></div>
                    </div>
                    <div class="row">
                        <div class="input-field col m4 s12"></div>
                        <div class="input-field col m4 s12">
                            <?php if ((access("add_perm") == 1)  || (access("edit_perm") == 1)) { ?>
                                <button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col m12 s12" type="submit" name="add">Update Box Dimentions</button>
                            <?php } ?>
                        </div>
                        <div class="input-field col m4 s12"></div>
                    </div>
                    <div class="row">
                        <div class="input-field col m12 s12"></div>
                    </div>
                </div>
            </form>
        <?php
        }
        $sql_cl     = "	SELECT c.*,c1.category_name,b.serial_no,b.id As product_stock_id,b.price,b.serial_no
                        FROM product_stock b 
                        INNER JOIN sales_order_detail b1 ON b1.product_stock_id = b.id 
                        INNER JOIN products c ON c.id = b.product_id
                        LEFT JOIN product_categories c1 ON c1.id = c.product_category
                        WHERE b.enabled = 1 
                        AND b.p_total_stock > 0
                        AND b.is_packed = 0
                        GROUP BY b.serial_no
                        ORDER BY b.serial_no "; // echo $sql_cl;
        $result_cl    = $db->query($conn, $sql_cl);
        $count_cl    = $db->counter($result_cl);
        if ($count_cl > 0) { ?>
            <div class="card-panel">
                <div class="card-content">
                    <form id="barcodeForm2" class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=edit&id=" . $id . "&active_tab=tab3") ?>" method="post">
                        <input type="hidden" name="is_Submit_tab3_1" value="Y" />
                        <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                            echo encrypt($_SESSION['csrf_session']);
                                                                        } ?>">
                        <div class="">
                            <h5>Bulk Packing</h5>
                            <div class="section section-data-tables">
                                <div class="row">
                                    <div class="col s12">
                                        <table id="page-length-option" class=" display pagelength50_3 dataTable dtr-inline ">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">
                                                        <label>
                                                            <input type="checkbox" id="all_checked" class="filled-in" name="all_checked" value="1" <?php if (isset($all_checked) && $all_checked == '1') {
                                                                                                                                                        echo "checked";
                                                                                                                                                    } ?> />
                                                            <span></span>
                                                        </label>
                                                    </th>
                                                    <?php
                                                    $headings  = '
                                                                        <th>Product ID</th>
                                                                        <th>Product Description</th>
                                                                        <th>Serial No</th> ';
                                                    echo $headings;
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 0;
                                                if ($count_cl > 0) {
                                                    $row_cl = $db->fetch($result_cl);
                                                    foreach ($row_cl as $data) {
                                                        $detail_id1 = $data['id'];
                                                        $detail_id2 = $data['product_stock_id'];
                                                        $serial_no  = $data['serial_no'];
                                                ?>
                                                        <tr>
                                                            <td style="text-align: center;">
                                                                <?php
                                                                if (access("delete_perm") == 1) { ?>
                                                                    <label style="margin-left: 25px;">
                                                                        <input type="checkbox" name="bulkpacked[]" id="bulkpacked[]" value="<?= $detail_id2 ?>" <?php if (isset($bulkpacked) && in_array($detail_id2, $bulkpacked)) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> class="checkbox filled-in" />
                                                                        <span></span>
                                                                    </label>
                                                                <?php } ?>
                                                            </td>
                                                            <td><?php echo "" . $data['product_uniqueid']; ?></td>
                                                            <td>
                                                                <?php echo ucwords(strtolower($data['product_desc'])); ?><br>
                                                                <?php
                                                                if ($data['category_name'] != "") {
                                                                    echo  " (" . $data['category_name'] . ")";
                                                                } ?>
                                                            </td>
                                                            <td><?php echo "" . $data['serial_no']; ?></td>
                                                        </tr>
                                                <?php $i++;
                                                    }
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m3 s12">
                                <?php
                                $field_name     = "packing_type_bulk";
                                $field_label    = "Packing Type";
                                $sql1           = "SELECT * FROM packing_types a WHERE a.enabled = 1  ORDER BY packing_type ";
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
                                                    <?php echo $data2['packing_type']; ?>
                                                </option>
                                        <?php }
                                        } ?>
                                    </select>
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red">* <?php
                                                                    if (isset($error4[$field_name])) {
                                                                        echo $error4[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="input-field col m3 s12">
                                <?php
                                $field_name     = "box_no_bulk";
                                $field_label     = "Box #";
                                ?>
                                <i class="material-icons prefix">description</i>
                                <input id="<?= $field_name; ?>" type="number" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                    echo ${$field_name};
                                                                                                                } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                                                                                                } ?>">
                                <label for="<?= $field_name; ?>">
                                    <?= $field_label; ?>
                                    <span class="color-red"> * <?php
                                                                if (isset($error4[$field_name])) {
                                                                    echo $error4[$field_name];
                                                                } ?>
                                    </span>
                                </label>
                            </div>
                            <div class="input-field col m3 s12">
                                <?php
                                $field_name     = "pallet_no_bulk";
                                $field_label     = "Pallet #";
                                ?>
                                <i class="material-icons prefix">description</i>
                                <input id="<?= $field_name; ?>" type="number" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                    echo ${$field_name};
                                                                                                                } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                                                                                                } ?>">
                                <label for="<?= $field_name; ?>">
                                    <?= $field_label; ?>
                                    <span class="color-red"> <?php
                                                                if (isset($error3[$field_name])) {
                                                                    echo $error3[$field_name];
                                                                } ?>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m4 s12"></div>
                            <div class="input-field col m4 s12">
                                <?php if (isset($id) && $id > 0 && (($cmd6 == 'add' || $cmd6 == '') && access("add_perm") == 1)  || ($cmd6 == 'edit' && access("edit_perm") == 1) || ($cmd6 == 'delete' && access("delete_perm") == 1)) { ?>
                                    <button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col m12 s12" type="submit" name="add">Add Bulk Packing</button>
                                <?php } ?>
                            </div>
                            <div class="input-field col m4 s12"></div>
                        </div>
                        <div class="row">
                            <div class="input-field col m12 s12"></div>
                        </div>
                    </form>
                </div>
            </div>
        <?php }
        $sql_cl        = "	SELECT a.*, c.product_desc,b2.packing_type, d.category_name,b.order_status, 
                                c.product_uniqueid, b1.order_qty,b1.order_price,b1.product_so_desc,
                                b1.product_stock_id, c1.serial_no
                            FROM `sales_order_detail_packing` a  
                            INNER JOIN product_stock c1 ON c1.id = a.product_stock_id 
                            INNER JOIN sales_orders b ON b.id = a.sale_order_id
                            INNER JOIN `sales_order_detail` b1 ON b1.sales_order_id = a.sale_order_id AND b1.product_stock_id = a.product_stock_id
                            INNER JOIN products c ON c.id = c1.product_id
                            INNER JOIN packing_types b2 ON b2.id = a.packing_type
                            LEFT JOIN product_categories d ON d.id = c.product_category
                            WHERE b1.sales_order_id ='" . $id . "'
                            ORDER BY a.is_shipped, c1.serial_no "; // echo $sql_cl;
        $result_cl    = $db->query($conn, $sql_cl);
        $count_cl    = $db->counter($result_cl);
        if ($count_cl > 0) { ?>
            <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=edit&id=" . $id . "&active_tab=tab3") ?>" method="post">
                <input type="hidden" name="is_Submit_tab3_2" value="Y" />
                <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                    echo encrypt($_SESSION['csrf_session']);
                                                                } ?>">
                <div class="card-panel">
                    <div class="row">
                        <div class="col m8 s12">
                            <h5>Packed Products</h5>
                        </div>
                        <div class="col m1 s12">
                            <a href="export/export_sales_order_product_packed.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" class="mb-6 btn waves-effect waves-light gradient-45deg-green-teal">
                                <i class="material-icons medium icon-demo">vertical_align_bottom</i>
                            </a>
                        </div>
                        <div class="col m1 s12">
                            <a class="mb-6 btn waves-effect waves-light cyan" href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/sales_order_product_packed_print.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" target="_blank">
                                <i class="material-icons medium icon-demo">print</i>
                            </a>
                        </div>
                    </div>

                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col s12">
                                <table id="page-length-option" class=" display pagelength50_4 dataTable dtr-inline ">
                                    <thead>
                                        <tr>
                                            <?php
                                            echo $headings = '	<th style="text-align: center;">S.No</th>'; ?>
                                            <th style="text-align: center;">
                                                <label>
                                                    <input type="checkbox" id="all_checked3" class="filled-in" name="all_checked3" value="1" <?php if (isset($all_checked3) && $all_checked3 == '1') {
                                                                                                                                                    echo "checked";
                                                                                                                                                } ?> />
                                                    <span></span>
                                                </label>
                                            </th>
                                            <?php
                                            $headings  = '
                                                            <th>Product Detail</th>
                                                            <th>Serial No</th>
                                                            <th>Packing Type</th>
                                                            <th>Box#</th>
                                                            <th>Pallet#</th> ';
                                            echo $headings;
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        if ($count_cl > 0) {
                                            $row_cl = $db->fetch($result_cl);
                                            foreach ($row_cl as $data) {
                                                $detail_id1     = $data['id'];
                                                $detail_id2     = $data['product_stock_id']; ?>
                                                <tr>
                                                    <td style="text-align: center;">
                                                        <?php echo $i + 1; ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <?php
                                                        if (access("delete_perm") == 1 && $data['is_shipped'] == 0) { ?>
                                                            <label style="margin-left: 25px;">
                                                                <input type="checkbox" name="packedItems[]" id="packedItems[]" value="<?= $detail_id1; ?>^<?= $detail_id2; ?>" class="checkbox3 filled-in" />
                                                                <span></span>
                                                            </label>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php echo "" . $data['product_uniqueid']; ?><br>

                                                        <?php echo ucwords(strtolower($data['product_desc'])); ?>
                                                        <?php
                                                        if ($data['category_name'] != "") {
                                                            echo  " (" . $data['category_name'] . ")";
                                                        } ?>
                                                    </td>
                                                    <td><?php echo "" . $data['serial_no']; ?></td>
                                                    <td><?php echo $data['packing_type']; ?></td>
                                                    <td><?php echo $data['box_no']; ?></td>
                                                    <td><?php if (isset($data['pallet_no']) && $data['pallet_no'] > 0) echo "Pallet " . $data['pallet_no']; ?></td>
                                                </tr>
                                        <?php $i++;
                                            }
                                        } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col m12 s12">&nbsp;</div>
                    </div>
                    <div class="row">
                        <div class="input-field col m4 s12">
                            <?php
                            $field_name     = "sub_location_pack";
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
                        <div class="input-field col m4 s12">
                            <?php if (isset($id) && $id > 0 &&  access("delete_perm") == 1) { ?>
                                <button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col m12 s12" type="submit" name="deletepserial">Unpack / Remove from Packing</button>
                            <?php } ?>
                        </div>
                        <div class="input-field col m4 s12"></div>
                    </div>
                </div>
            </form>
        <?php
        }
        $sql_cl        = "	SELECT b2.packing_type, a.box_no,a.pallet_no, COUNT(a.id) AS total_qty
                            FROM sales_order_detail_packing a  
                            INNER JOIN packing_types b2 ON b2.id = a.packing_type
                            WHERE a.sale_order_id =  '" . $id . "'
                            GROUP BY b2.packing_type, a.box_no
                            ORDER BY b2.packing_type, a.box_no  "; // echo $sql_cl;
        $result_cl    = $db->query($conn, $sql_cl);
        $count_cl    = $db->counter($result_cl);
        if ($count_cl > 0) { ?>
            <div class="card-panel">

                <div class="row">
                    <div class="col m8 s12">
                        <h5>Summary</h5>
                    </div>
                    <div class="col m1 s12">
                        <a href="export/export_sales_order_packed_summary.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" class="mb-6 btn waves-effect waves-light gradient-45deg-green-teal">
                            <i class="material-icons medium icon-demo">vertical_align_bottom</i>
                        </a>
                    </div>
                    <div class="col m1 s12">
                        <a class="mb-6 btn waves-effect waves-light cyan" href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/sales_order_packed_summary_print.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" target="_blank">
                            <i class="material-icons medium icon-demo">print</i>
                        </a>
                    </div>
                </div>
                <div class="section section-data-tables">

                    <div class="row">
                        <div class="col s12">

                            <table id="page-length-option" class=" display pagelength50_6 dataTable dtr-inline ">
                                <thead>
                                    <tr>
                                        <?php
                                        $headings = '	<th class="sno_width_60">S.No</th>
                                                        <th>Packing Type</th>
                                                        <th>Box#</th>
                                                        <th>Pallets</th>
                                                        <th>Qty</th> ';
                                        echo $headings;
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    if ($count_cl > 0) {
                                        $row_cl = $db->fetch($result_cl);
                                        foreach ($row_cl as $data) {
                                            $pallet_no = $data['pallet_no'];
                                    ?>
                                            <tr>
                                                <td style="text-align: center;"><?php echo $i + 1; ?></td>
                                                <td><?php echo $data['packing_type']; ?></td>
                                                <td><?php echo $data['box_no']; ?></td>
                                                <td><?php
                                                    if (isset($pallet_no) && $pallet_no > 0) {
                                                        echo "Pallet " . $pallet_no;
                                                    } ?>
                                                </td>
                                                <td><?php echo $data['total_qty']; ?></td>
                                            </tr>
                                    <?php $i++;
                                        }
                                    } ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        $sql_cl        = "	SELECT c.product_desc,b2.packing_type, d.category_name, c.product_uniqueid, COUNT(a.id) AS total_qty, a.box_no,a.pallet_no
                            FROM sales_order_detail_packing a  
                            INNER JOIN sales_orders b ON b.id = a.sale_order_id
                            INNER JOIN product_stock c1 ON c1.serial_no = a.serial_no_barcode 
                            INNER JOIN products c ON c.id = c1.product_id
                            INNER JOIN packing_types b2 ON b2.id = a.packing_type
                            LEFT JOIN product_categories d ON d.id = c.product_category
                            WHERE a.sale_order_id = '" . $id . "'
                            GROUP BY a.packing_type, a.box_no, c1.product_id
                            ORDER BY a.packing_type, a.box_no, c1.product_id "; // echo $sql_cl;
        $result_cl    = $db->query($conn, $sql_cl);
        $count_cl    = $db->counter($result_cl);
        if ($count_cl > 0) { ?>
            <div class="card-panel">
                <div class="row">
                    <div class="col m8 s12">
                        <h5>Summary Detail</h5>
                    </div>
                    <div class="col m1 s12">
                        <a href="export/export_sales_order_packed_summary_details.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" class="mb-6 btn waves-effect waves-light gradient-45deg-green-teal">
                            <i class="material-icons medium icon-demo">vertical_align_bottom</i>
                        </a>
                    </div>
                    <div class="col m1 s12">
                        <a class="mb-6 btn waves-effect waves-light cyan" href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/sales_order_packed_summary_details_print.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" target="_blank">
                            <i class="material-icons medium icon-demo">print</i>
                        </a>
                    </div>
                </div>
                <div class="section section-data-tables">
                    <div class="row">
                        <div class="col s12">
                            <table id="page-length-option" class=" display pagelength50_7 dataTable dtr-inline ">
                                <thead>
                                    <tr>
                                        <?php
                                        $headings = '	<th class="sno_width_60">S.No</th>
                                                        <th>Packing Type</th>
                                                        <th>Box#</th>
                                                        <th>Pallets</th>
                                                        <th>Product ID</th>
                                                        <th>Detail</th>
                                                        <th>Qty</th> ';
                                        echo $headings;
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    if ($count_cl > 0) {
                                        $row_cl = $db->fetch($result_cl);
                                        foreach ($row_cl as $data) {    ?>
                                            <tr>
                                                <td style="text-align: center;"><?php echo $i + 1; ?></td>
                                                <td><?php echo $data['packing_type']; ?></td>
                                                <td><?php echo $data['box_no']; ?></td>
                                                <td><?php if (isset($data['pallet_no']) && $data['pallet_no'] > 0) {
                                                        echo "Pallet " . $data['pallet_no'];
                                                    } ?></td>
                                                <td><?php echo "" . $data['product_uniqueid']; ?><br></td>
                                                <td>
                                                    <?php echo ucwords(strtolower($data['product_desc'])); ?>
                                                    <?php
                                                    if ($data['category_name'] != "") {
                                                        echo  " (" . $data['category_name'] . ")";
                                                    } ?>
                                                </td>
                                                <td><?php echo $data['total_qty']; ?></td>
                                            </tr>
                                    <?php $i++;
                                        }
                                    } ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
    }
    if (!isset($id) && !isset($so_no)) { ?>
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
</div>
<script>
    function autoSubmit2(event) {
        var keycode_value = event.keyCode;
        if (keycode_value === 8 || keycode_value === 37 || keycode_value === 38 || keycode_value === 39 || keycode_value === 40 || keycode_value === 46 || keycode_value === 17 || keycode_value === 16 || keycode_value === 18 || keycode_value === 20 || keycode_value === 110 || (event.ctrlKey && (keycode_value === 65 || keycode_value === 67 || keycode_value === 88 || keycode_value === 88))) {

        } else {
            document.getElementById('barcodeForm2').submit();
        }
    }
</script>