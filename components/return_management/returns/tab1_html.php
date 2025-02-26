<div id="tab1_html" class="active" style="display: <?php if (isset($active_tab) && $active_tab == 'tab1') {
                                                        echo "block";
                                                    } else {
                                                        echo "none";
                                                    } ?>;">
    <input type="hidden" id="module_id" value="<?= $module_id; ?>" />
    <input type="hidden" id="id" value="<?= $id; ?>" />
    <input type="hidden" id="previous_stage_status" value="<?= $stage_status; ?>" />
    <?php
    if (isset($cmd) && $cmd == 'edit') { ?>
        <form method="post" autocomplete="off" action="<?php echo "?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=profile&active_tab=tab1&cmd=edit&id=' . $id); ?>">
            <input type="hidden" name="is_Submit2" value="Y" />
        <?php
    } ?>
        <div class="card-panel" style="padding-top: 5px; padding-bottom: 5px; margin-top: 0px; margin-bottom: 5px;">
            <div class="row">
                <div class="input-field col m6 s12" style="margin-top: 3px; margin-bottom: 3px;">
                    <h6 class="media-heading">
                        <?= $general_heading; ?> => Master Info
                    </h6>
                </div>
                <div class="input-field col m1 s12" style="margin-top: 5px; margin-bottom: 5px;">
                    <?php
                    $field_name     = "stage_status";
                    $field_label     = "Stage Status";
                    ?>
                    <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="browser-default custom_condition_class">
                        <?php
                        $sql1 = "SELECT * FROM stages_status WHERE enabled = 1";
                        if(!isset($cmd) || (isset($cmd) && $cmd == 'add' )){
                            $sql1 .= " AND status_name = 'Draft' ";
                        }
                        echo  $sql1 .= " ORDER BY sort_by ";
                        $result1         = $db->query($conn, $sql1);
                        $count1         = $db->counter($result1); 
                        if ($count1 > 0) {
                            $row1    = $db->fetch($result1);
                            foreach ($row1 as $data2) { ?>
                                <option value="<?php echo $data2['status_name']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['status_name']) { ?> selected="selected" <?php } ?>><?php echo $data2['status_name']; ?></option>
                            <?php  
                            }
                        } ?> 
                    </select>
                </div>
                <div class="input-field col m5 s12" style="text-align: right; margin-top: 3px; margin-bottom: 3px;">
                    <?php /*?>
                    <a href="javascript:void(0)" class="btn cyan waves-effect waves-light ">
                        <i class="material-icons ">print</i>
                        Print
                    </a>  &nbsp;&nbsp;
                <?php */ ?>
                    <?php
                    if (isset($return_no) && isset($id)) {
                        if (access("edit_perm") == 1) { ?>
                            <button class="btn cyan waves-effect waves-light green custom_btn_size" type="submit" name="action">
                                Save changes
                            </button>
                    <?php
                        }
                    }
                    include("tab_action_btns.php"); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m12 l12">
                <div id="Form-advance" class="card card card-default scrollspy custom_margin_section">
                    <div class="card-content custom_padding_section">
                        <?php
                        if (isset($return_no) && isset($id)) { ?>
                            <h5 class="media-heading"><span class=""><?php echo "<b>Return#:</b>" . $return_no; ?></span></h5>
                        <?php } ?>
                        <?php
                        if (isset($cmd) && $cmd == 'add') { ?>
                            <form method="post" autocomplete="off" action="<?php echo "?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=profile&cmd=edit&active_tab=tab1&cmd=' . $cmd . '&id=' . $id); ?>">

                                <input type="hidden" name="is_Submit" value="Y" />
                            <?php } ?>
 
                            <div class="row" style="margin-top: 20px;">
                                <div class="input-field col m3 s12 custom_margin_bottom_col">
                                    <?php 
                                    $field_name     = "return_type"; 
                                    $field_label     = "Return Type";
                                    ?>
                                    <i class="material-icons prefix">question_answer</i>
                                    <div class="select2div">
                                        <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                            echo ${$field_name . "_valid"};
                                                                                                                                                                        } ?>">
                                            <option value="">Select</option>
                                            <option value="Shipstation" <?php if (isset(${$field_name}) && ${$field_name} == 'Shipstation') { ?> selected="selected" <?php } ?>>Shipstation</option>
                                            <option value="FBA" <?php if (isset(${$field_name}) && ${$field_name} == 'FBA') { ?> selected="selected" <?php } ?>>FBA</option>
                                            <option value="WFS" <?php if (isset(${$field_name}) && ${$field_name} == 'WFS') { ?> selected="selected" <?php } ?>>WFS</option>
                                            <option value="Wholesale" <?php if (isset(${$field_name}) && ${$field_name} == 'Wholesale') { ?> selected="selected" <?php } ?>>Wholesale</option>
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
                                <?php
                                $field_name     = "removal_order_id";
                                $field_label     = "Order / Removal ID";
                                ?>
                                <div class="input-field col m2 s12 custom_margin_bottom_col">
                                    <i class="material-icons prefix">question_answer</i>
                                    <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                        echo ${$field_name};
                                                                                                                    } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                            echo ${$field_name . "_valid"};
                                                                                                                                                        } ?> custom_input_heigh">
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red">* <?php
                                                                    if (isset($error[$field_name])) {
                                                                        echo $error[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div> 
                                <?php
                                $field_name     = "return_date";
                                $field_label     = "Return Date (d/m/Y)";
                                ?>
                                <div class="input-field col m2 s12 custom_margin_bottom_col">
                                    <i class="material-icons prefix">date_range</i>
                                    <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                        echo ${$field_name};
                                                                                                                    } else {
                                                                                                                        echo date("d/m/Y");
                                                                                                                    } ?>" class=" datepicker validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                            echo ${$field_name . "_valid"};
                                                                                                                                                        } ?> custom_input_heigh">
                                    <label for="<?= $field_name; ?>">
                                        <?= $field_label; ?>
                                        <span class="color-red">* <?php
                                                                    if (isset($error[$field_name])) {
                                                                        echo $error[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div> 
                                <div class="input-field col m3 s12 custom_margin_bottom_col">
                                    <?php
                                    $field_name     = "store_id";
                                    $field_label     = "Store";
                                    $sql1             = "SELECT * FROM stores WHERE enabled = 1 ORDER BY store_name ";
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
                                                    <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['store_name']; ?> </option>
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
                                <div class="input-field col m2 s12 custom_margin_bottom_col"><br>
                                    <a class="waves-effect waves-light btn modal-trigger mb-2 mr-1 custom_btn_size" href="#store_add_modal">Add New Store</a>
                                </div>
                            </div>
                         
                            <?php if (($cmd == 'add' &&  access("add_perm") == 1)) { ?>
                                <div class="row">
                                    <div class="input-field col m6 s12">
                                        <button class="btn cyan waves-effect waves-light right" type="submit" name="action"><?php echo $button_val; ?>
                                            <i class="material-icons right">send</i>
                                        </button>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (isset($cmd) && $cmd == 'add') { ?>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php if (isset($cmd) && $cmd == 'edit') { ?>
                <div class="col s12 m12 l12">
                    <div id="Form-advance2" class="card card card-default scrollspy custom_margin_section">
                        <div class="card-content custom_padding_section">
                            <table id="page-length-option1" class="bordered addproducttable" cellpadding="0" cellspacing="0">

                                <?php
                                if (isset($id) && $id > 0) {

                                    unset($product_ids);
                                    unset($return_qty);
                                    unset($expected_status);

                                    $sql_ee1    = "SELECT a.* FROM return_items_detail a WHERE a.return_id = '" . $id . "' AND a.enabled = 1 ";
                                    $result_ee1 = $db->query($conn, $sql_ee1);
                                    $count_ee1  = $db->counter($result_ee1);
                                    if ($count_ee1 > 0) {
                                        $row_ee1 = $db->fetch($result_ee1);
                                        foreach ($row_ee1 as $data2) {
                                            $product_ids[]          = $data2['product_id'];
                                            $return_qty[]           = $data2['return_qty'];
                                            $expected_status[]      = $data2['expected_status'];
                                        }
                                    }
                                    else{
                                        if (isset($test_on_local) && $test_on_local == 1) {
                                            $product_ids[]          = "2987";
                                            $product_ids[]          = "2989";
                                            $return_qty[]           = "2";
                                            $return_qty[]           = "2";
                                            $expected_status[]      = "6";
                                            $expected_status[]      = "6";
                                        }
                                    }
                                } ?>

                                
                                <thead>
                                    <tr>

                                        <th style="width: 250px;">
                                            Product &nbsp;
                                            <?php
                                            if (isset($stage_status) && $stage_status != "Committed") { ?>
                                                <a href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=import_po_details&id=" . $id) ?>" class="btn gradient-45deg-amber-amber waves-effect waves-light custom_btn_size">
                                                    Import
                                                </a> &nbsp;&nbsp;
                                                <?php
                                                if (!isset($package_ids) || (isset($package_ids) && sizeof($package_ids) == 0)) { ?>
                                                   <!--  <a class=" btn gradient-45deg-amber-amber waves-effect waves-light custom_btn_size package_material_parts" style="line-height: 32px;" id="add-more^0" href="javascript:void(0)" style="display: none;">
                                                        Add Packages / Parts
                                                    </a> -->
                                                    <?php } ?>&nbsp;&nbsp;
                                                    <a class="add-more add-more-btn2 btn-sm btn-floating waves-effect waves-light cyan first_row" style="line-height: 32px; display: none;" id="add-more^0" href="javascript:void(0)" style="display: none;">
                                                        <i class="material-icons  dp48 md-36">add_circle</i>
                                                    </a>
                                                <?php } ?>
                                        </th>
                                        <th style="width: 200px;">Status</th>
                                        <th style="width: 100px;">Qty</th>
                                         <th style="width: 150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                
                                    <input type="hidden" id="total_products_in_po" value="<?php if (!isset($product_ids) || (isset($product_ids) && sizeof($product_ids) == 0)) {
                                                                                                echo "1";
                                                                                            } else {
                                                                                                echo sizeof($product_ids);
                                                                                            } ?>">
                                    <?php
                                    $disabled = $readonly = "";
                                    if (isset($stage_status) && $stage_status == "Committed") {
                                        $disabled = "disabled='disabled'";
                                        $readonly = "readonly='readonly'";
                                    }
                                    $sum_value = $sum_qty =  $sum_price = 0;
                                    for ($i = 1; $i <= 50; $i++) {
                                        $field_name     = "product_ids";
                                        $field_id       = "productids_" . $i;
                                        $field_label    = "Product";
                                        $style_btn = '';
                                        $style = "";
                                        if (!isset(${$field_name}[$i - 1]) || (isset(${$field_name}[$i - 1]) && ${$field_name}[$i - 1] == "" || ${$field_name}[$i - 1] == 0)) {
                                            if ($i > 1) {
                                                if (isset($product_ids) && sizeof($product_ids) > 0) {
                                                    $style = 'style="display:none;"';
                                                } else {
                                                    $style = $i === 1 ? '' : 'style="display:none;"';
                                                }
                                            }
                                        } else {
                                            if (isset($product_ids) && is_array($product_ids) && sizeof($product_ids) > 1) {
                                                $style = $i <= sizeof($product_ids) ? '' : 'style="display:none;"';
                                                $style_btn = $i <= sizeof($product_ids) ? 'style="display:none;"' : '';
                                            } else {
                                                $style = $i === 1 ? '' : 'style="display:none;"';
                                                $style_btn = $i === 1 ? 'style="display:none;"' : '';
                                            }
                                        }
                                        $sql1       = " SELECT a.*, b.category_name
                                                        FROM products a
                                                        LEFT JOIN product_categories b ON b.id = a.product_category
                                                        WHERE a.enabled = 1 ";
                                        // if (isset($stage_status) && $stage_status == "Committed") {
                                        //     $sql1 .= " AND a.id = '" . ${$field_name}[$i - 1] . "' ";
                                        // }
                                        $sql1      .= " ORDER BY a.product_desc ";
                                        $result1    = $db->query($conn, $sql1);
                                        $count1     = $db->counter($result1);
                                        $pkg_stock_in_hand  = $pkg_stock_of_product_needed = $return_qty_val = 0;
                                        if (isset(${$field_name}[$i - 1])) {
                                            $sql_order      = " SELECT SUM(stock_in_hand) AS stock_in_hand
                                                                FROM packages   
                                                                WHERE FIND_IN_SET('" . ${$field_name}[$i - 1] . "', product_ids) ";
                                            $result_order   = $db->query($conn, $sql_order);
                                            $count_order    = $db->counter($result_order);
                                            if ($count_order > 0) {
                                                $row_order          = $db->fetch($result_order);
                                                $pkg_stock_in_hand  = $row_order[0]['stock_in_hand'];
                                            }
                                        }
                                        if (isset($return_qty[$i - 1])) {
                                            $return_qty_val = $return_qty[$i - 1];
                                        }
                                        $pkg_stock_of_product_needed = $return_qty_val - $pkg_stock_in_hand;
                                        if (isset($stage_status) && $stage_status == "Committed" || isset($stage_status) && $stage_status != "Committed") { ?>
                                            <tr class="dynamic-row" id="row_<?= $i; ?>" <?php echo $style; ?>>
                                            <td>
                                                    <select <?php echo $disabled;
                                                            echo $readonly; ?> name="<?= $field_name ?>[]" id="<?= $field_id ?>" class="select2-theme browser-default select2-hidden-accessible product-select <?= $field_name ?>_<?= $i ?>">
                                                        <option value="">Select a product</option>
                                                        <?php
                                                        if ($count1 > 0) {
                                                            $row1    = $db->fetch($result1);
                                                            foreach ($row1 as $data2) { ?>
                                                                <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}[$i - 1]) && ${$field_name}[$i - 1] == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['product_desc']; ?> (<?php echo $data2['category_name']; ?>) - <?php echo $data2['product_uniqueid']; ?></option>
                                                        <?php }
                                                        } ?>
                                                        <option value="product_add_modal">+Add New Product</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <?php
                                                    $field_name     = "expected_status";
                                                    $field_id       = "expectedstatus_" . $i;
                                                    $field_label    = "Status";
                                                    $sql_status     = "SELECT id, status_name
                                                                        FROM  inventory_status b 
                                                                        WHERE enabled = 1
                                                                        AND id IN(6, 8 ,9 ,16 ,17 , 18 , 19 ,20)";
                                                    $result_status  = $db->query($conn, $sql_status);
                                                    $count_status   = $db->counter($result_status);
                                                    ?>

                                                    <select <?php echo $disabled;
                                                            echo $readonly; ?> name="<?= $field_name ?>[]" id="<?= $field_id ?>" class="browser-default custom_condition_class">
                                                        <option value="">N/A</option>
                                                        <?php
                                                        if ($count_status > 0) {
                                                            $row_status    = $db->fetch($result_status);
                                                            foreach ($row_status as $data2) { ?>
                                                                <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}[$i - 1]) && ${$field_name}[$i - 1] == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['status_name']; ?></option>
                                                        <?php }
                                                        } ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <?php
                                                    $field_name     = "return_qty";
                                                    $field_id       = "orderqty_" . $i;
                                                    $field_label     = "Quantity";
                                                    ?>
                                                    <input <?php echo $disabled;
                                                            echo $readonly; ?> name="<?= $field_name; ?>[]" type="number" id="<?= $field_id; ?>" value="<?php if (isset($return_qty_val)) {
                                                                                                                                                            echo $return_qty_val;
                                                                                                                                                        } ?>" class="validate custom_input return_qty">
                                                </td>
                                              
                                           
                                                <td>
                                                    <?php
                                                    if (isset($stage_status) && $stage_status != "Committed") { ?>
                                                        <a class="remove-row btn-sm btn-floating waves-effect waves-light red" style="line-height: 32px;" id="remove-row^<?= $i ?>" href="javascript:void(0)">
                                                            <i class="material-icons dp48">cancel</i>
                                                        </a> &nbsp;
                                                        <a class="add-more add-more-btn btn-sm btn-floating waves-effect waves-light cyan" style="line-height: 32px; display:none;" id="add-more^<?= $i ?>" href="javascript:void(0)">
                                                            <i class="material-icons dp48">add_circle</i>
                                                        </a>&nbsp;&nbsp;
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                    <?php }
                                    } ?>
                                    <tr>
                                        <td class="text_align_left"></td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                </div>
                <div class="col s12 m12 l12">
                    <div id="Form-advance2" class="card card card-default scrollspy custom_margin_section">
                        <div class="card-content custom_padding_section">
                            <div class="row">
                                <div class="input-field col m6 s12">
                                    <?php
                                    $field_name     = "internal_note";
                                    $field_label     = "Private Note";
                                    ?>
                                    <i class="material-icons prefix">description</i>
                                    <textarea <?php echo $disabled;
                                                echo $readonly; ?> id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="materialize-textarea validate "><?php if (isset(${$field_name})) {
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
                                <div class="input-field col m6 s12">
                                    <?php
                                    $field_name     = "public_note";
                                    $field_label     = "Public Note";
                                    ?>
                                    <i class="material-icons prefix">description</i>
                                    <textarea <?php echo $disabled;
                                                echo $readonly; ?> id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="materialize-textarea validate "><?php if (isset(${$field_name})) {
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
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php
        if (isset($cmd) && $cmd == 'edit') { ?>
        </form>
    <?php } ?>
</div>