<div id="tab1_html" class="active" style="display: <?php if (isset($active_tab) && $active_tab == 'tab1') {
                                                        echo "block";
                                                    } else {
                                                        echo "none";
                                                    } ?>;">
    <div class="card-panel">
        <form class="infovalidate" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="is_Submit" value="Y" />
            <input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
            <input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
            <input type="hidden" name="po_no" id="po_no" value="<?php if (isset($po_no)) echo $po_no; ?>" />
            <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                echo encrypt($_SESSION['csrf_session']);
                                                            } ?>">
            <input type="hidden" name="active_tab" value="tab1" />

            <div class="row">
                <div class="col s10 m12 l8">
                    <h5 class="breadcrumbs mt-0 mb-0"><span>Purchase Order Detail</span></h5>
                </div>
            </div>
            <div class="row">
                <div class="col m12 s12"> &nbsp;</div>
            </div>
            <div class="row">
                <?php
                if (isset($po_no)) { ?>
                    <div class="input-field col m4 s12">
                        <i class="material-icons prefix">question_answer</i>
                        <input id="po_nodddd" readonly disabled type="text" value="<?php if (isset($po_no)) {
                                                                                        echo $po_no;
                                                                                    } ?>">
                        <label for="po_nodddd">PO No</label>
                    </div>
                <?php } ?>
                <?php
                $field_name     = "po_date";
                $field_label     = "Order Date (d/m/Y)";
                ?>
                <div class="input-field col m4 s12">
                    <i class="material-icons prefix">date_range</i>
                    <input id="<?= $field_name; ?>" type="text" readonly disabled value="<?php if (isset(${$field_name})) {
                                                                                                echo ${$field_name};
                                                                                            } ?>" class="  validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                    } ?>">
                    <label for="<?= $field_name; ?>">
                        <?= $field_label; ?>
                        <span class="color-red"><?php
                                                if (isset($error[$field_name])) {
                                                    echo $error[$field_name];
                                                } ?>
                        </span>
                    </label>
                </div>
                <?php
                $field_name     = "estimated_receive_date";
                $field_label     = "Estimated receive Date (d/m/Y)";
                ?>
                <div class="input-field col m4 s12">
                    <i class="material-icons prefix">date_range</i>
                    <input id="<?= $field_name; ?>" type="text" readonly disabled value="<?php if (isset(${$field_name})) {
                                                                                                echo ${$field_name};
                                                                                            } ?>" class="  validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                    } ?>">
                    <label for="<?= $field_name; ?>">
                        <?= $field_label; ?>
                        <span class="color-red"><?php
                                                if (isset($error[$field_name])) {
                                                    echo $error[$field_name];
                                                } ?>
                        </span>
                    </label>
                </div>

                <?php
                $field_name     = "vender_name";
                $field_label     = "Vender";

                $table              = "venders";
                $columns            = array("vender_name");
                $get_col_from_table = get_col_from_table($db, $conn, $selected_db_name, $table, $vender_id, $columns);
                foreach ($get_col_from_table as $array_key1 => $array_data1) {
                    ${$array_key1} = $array_data1;
                }

                ?>
                <div class="input-field col m4 s12">
                    <i class="material-icons prefix">date_range</i>
                    <input id="<?= $field_name; ?>" type="text" readonly disabled value="<?php if (isset(${$field_name})) {
                                                                                                echo ${$field_name};
                                                                                            } ?>" class="  validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                    } ?>">
                    <label for="<?= $field_name; ?>">
                        <?= $field_label; ?>
                        <span class="color-red"><?php
                                                if (isset($error[$field_name])) {
                                                    echo $error[$field_name];
                                                } ?>
                        </span>
                    </label>
                </div>
                <?php
                $field_name     = "vender_invoice_no";
                $field_label     = "Vender Invoice #";
                ?>
                <div class="input-field col m4 s12">
                    <i class="material-icons prefix">description</i>
                    <input id="<?= $field_name; ?>" type="text" readonly disabled value="<?php if (isset(${$field_name})) {
                                                                                                echo ${$field_name};
                                                                                            } ?>" class="  validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                    } ?>">
                    <label for="<?= $field_name; ?>">
                        <?= $field_label; ?>
                        <span class="color-red"><?php
                                                if (isset($error[$field_name])) {
                                                    echo $error[$field_name];
                                                } ?>
                        </span>
                    </label>
                </div>

                <?php
                $field_name         = "status_name";
                $field_label        = "Status";
                $table              = "inventory_status";
                $columns            = array("status_name");
                $get_col_from_table = get_col_from_table($db, $conn, $selected_db_name, $table, $order_status, $columns);
                foreach ($get_col_from_table as $array_key1 => $array_data1) {
                    ${$array_key1} = $array_data1;
                } ?>
                <div class="input-field col m4 s12">
                    <i class="material-icons prefix">date_range</i>
                    <input id="<?= $field_name; ?>" type="text" readonly disabled value="<?php if (isset(${$field_name})) {
                                                                                                echo ${$field_name};
                                                                                            } ?>" class="  validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                    } ?>">
                    <label for="<?= $field_name; ?>">
                        <?= $field_label; ?>
                        <span class="color-red"><?php
                                                if (isset($error[$field_name])) {
                                                    echo $error[$field_name];
                                                } ?>
                        </span>
                    </label>
                </div>

            </div>
            <div class="row"> &nbsp;</div>
            <div class="row">
                <div class="input-field col m8 s12">
                    <?php
                    $field_name     = "po_desc";
                    $field_label     = "Description";
                    ?>
                    <i class="material-icons prefix">description</i>
                    <textarea id="<?= $field_name; ?>" readonly disabled class="materialize-textarea validate "><?php if (isset(${$field_name})) {
                                                                                                                    echo ${$field_name};
                                                                                                                } ?></textarea>
                    <label for="<?= $field_name; ?>">
                        <?= $field_label; ?>
                        <span class="color-red"> <?php
                                                    if (isset($error[$field_name])) {
                                                        echo $error[$field_name];
                                                    } ?>
                        </span>
                    </label>
                </div>

                <div class="input-field col m4 s12">
                    <?php
                    $field_name         = "sub_user_id";
                    $field_label        = "Assign PO to User";
                    $sql1               = " SELECT * 
                                            FROM users 
                                            WHERE user_type         = 'Sub Users' 
                                            AND enabled             = 1 
                                            AND subscriber_users_id = '" . $subscriber_users_id . "' ";
                    $result1            = $db->query($conn, $sql1);
                    $count1             = $db->counter($result1);
                    ?>
                    <i class="material-icons prefix">question_answer</i>
                    <div class="select2div">
                        <select id="<?= $field_name; ?>" <?php if (po_permisions("Pkg_PO_Detail") != '1') {
                                                                echo "disabled";
                                                            } ?> name="<?= $field_name; ?>" class="<?php if (po_permisions("Pkg_PO_Detail") == '1') {
                                                                                                        echo "select2 browser-default select2-hidden-accessible ";
                                                                                                    } ?>  validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                    } ?>">
                            <option value="">Select</option>
                            <?php
                            if ($count1 > 0) {
                                $row1    = $db->fetch($result1);
                                foreach ($row1 as $data2) { ?>
                                    <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['first_name']; ?> <?php echo $data2['middle_name']; ?> <?php echo $data2['last_name']; ?></option>
                            <?php }
                            } ?>
                        </select>
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
            </div>
            <div class="row">
                <div class="input-field col m4 s12"></div>
                <div class="input-field col m2 s12">
                    <?php
                    if (isset($id) && $id > 0 && ($cmd == 'edit' && access("edit_perm") == 1) && po_permisions("Pkg_PO_Detail") == 1) { ?>
                        <button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col m12 s12" type="submit" name="update_logistics">Update</button>
                    <?php } ?>
                </div>
            </div>
            <div class="row"> &nbsp;</div>
        </form>
    </div>
    <div class="card-panel">

        <?php
        $sql_cl        = "	SELECT a.*, d.category_name, c.package_name
                            FROM package_materials_order_detail a 
                            INNER JOIN package_materials_orders b ON b.id = a.po_id
                            INNER JOIN packages c ON c.id = a.package_id
                            INNER JOIN product_categories d ON d.id = c.product_category
                            WHERE 1=1 
                            AND a.po_id = '" . $id . "' 
                            ORDER BY c.package_name, d.category_name "; // echo $sql_cl;
        $result_cl    = $db->query($conn, $sql_cl);
        $count_cl    = $db->counter($result_cl);
        ?>
        <div class="section section-data-tables">
            <div class="row">
                <div class="col s12">
                    <table id="page-length-option1" class=" bordered">
                        <thead>
                            <tr>
                                <?php
                                $headings = '	<th class="sno_width_60">S.No</th>
                                                <th>Item Name / Category</th>
                                                 <th>Order Qty</th>
                                                <th>Unit Price</th> ';
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
                                    $order_qty      = $data['order_qty'];
                                    $order_price    = $data['order_price']; ?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo $i + 1; ?></td>
                                        <td>
                                            <?php echo $data['package_name']; ?>
                                            <?php
                                            if ($data['category_name'] != "'") {
                                                echo "(" . $data['category_name'] . ")";
                                            } ?>
                                        </td>
                                        <td><?php echo $order_qty; ?></td>
                                        <td><?php echo $order_price; ?></td>
                                    </tr>
                            <?php $i++;
                                }
                            } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>