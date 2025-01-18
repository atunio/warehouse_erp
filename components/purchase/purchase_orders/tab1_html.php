<div id="tab1_html" class="active" style="display: <?php if (isset($active_tab) && $active_tab == 'tab1') {
                                                        echo "block";
                                                    } else {
                                                        echo "none";
                                                    } ?>;">
    <input type="hidden" id="module_id" value="<?= $module_id; ?>" />
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
                <div class="input-field col m6 s12" style="text-align: right; margin-top: 3px; margin-bottom: 3px;">
                    <?php /*?>
                    <a href="javascript:void(0)" class="btn cyan waves-effect waves-light ">
                        <i class="material-icons ">print</i>
                        Print
                    </a>  &nbsp;&nbsp;
                <?php */ ?>
                    <?php
                    if (isset($po_no) && isset($id)) {
                        if (access("edit_perm") == 1) { ?>
                            <button class="btn cyan waves-effect waves-light green custom_btn_size" type="submit" name="action">
                                Save changes
                            </button>
                            <?php
                            if (isset($order_status) && $order_status == 1) { ?>
                                <a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=import_po_details&id=" . $id) ?>">
                                    Import Products
                                </a>
                    <?php }
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
                        if (isset($po_no) && isset($id)) { ?>
                            <h5 class="media-heading"><span class=""><?php echo "<b>PO#:</b>" . $po_no; ?></span></h5>
                        <?php } ?>
                        <?php
                        if (isset($cmd) && $cmd == 'add') { ?>
                            <form method="post" autocomplete="off" action="<?php echo "?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=profile&cmd=edit&active_tab=tab1&cmd=' . $cmd . '&id=' . $id); ?>">

                                <input type="hidden" name="is_Submit" value="Y" />
                            <?php } ?>
                            <div class="row" style="margin-top: 20px;">
                                <?php
                                $field_name     = "po_date";
                                $field_label     = "Order Date (d/m/Y)";
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
                                <?php
                                $field_name     = "vender_invoice_no";
                                $field_label     = "Vendor Invoice #";
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
                                        <span class="color-red"><?php
                                                                if (isset($error[$field_name])) {
                                                                    echo $error[$field_name];
                                                                } ?>
                                        </span>
                                    </label>
                                </div>
                                <div class="input-field col m3 s12 custom_margin_bottom_col">
                                    <?php
                                    $field_name     = "vender_id";
                                    $field_label     = "Vendor";
                                    $sql1             = "SELECT * FROM venders WHERE enabled = 1 ORDER BY vender_name ";
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
                                                    <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['vender_name']; ?> - Phone: <?php echo $data2['phone_no']; ?></option>
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
                                    <a class="waves-effect waves-light btn modal-trigger mb-2 mr-1 custom_btn_size" href="#vender_add_modal">Add New Vendor</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col m3 s12 custom_margin_bottom_col">
                                    <?php
                                    $field_name     = "is_tested_po";
                                    $field_label     = "To Be Tested";
                                    ?>
                                    <div style="margin-top: -10px; margin-bottom: 10px;">
                                        <?= $field_label; ?>
                                        <span class="color-red">* <?php
                                                                    if (isset($error[$field_name])) {
                                                                        echo $error[$field_name];
                                                                    } ?>
                                        </span>
                                    </div>
                                    <p class="mb-1 custom_radio">
                                        <label>
                                            <input name="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="Yes" <?php
                                                                                                                                if (isset(${$field_name}) && ${$field_name} == 'Yes') {
                                                                                                                                    echo 'checked=""';
                                                                                                                                } ?>>
                                            <span>Yes</span>
                                        </label> &nbsp;&nbsp;
                                        <label>
                                            <input name="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="No" <?php
                                                                                                                                if (isset(${$field_name}) && ${$field_name} == 'No') {
                                                                                                                                    echo 'checked=""';
                                                                                                                                } ?>>
                                            <span>No</span>
                                        </label>
                                    </p>
                                </div>
                                <div class="input-field col m3 s12 custom_margin_bottom_col">
                                    <?php
                                    $field_name     = "is_wiped_po";
                                    $field_label     = "To Be Wiped";
                                    ?>
                                    <div style="margin-top: -10px; margin-bottom: 10px;">
                                        <?= $field_label; ?>
                                        <span class="color-red">* <?php
                                                                    if (isset($error[$field_name])) {
                                                                        echo $error[$field_name];
                                                                    } ?>
                                        </span>
                                    </div>
                                    <p class="mb-1 custom_radio">
                                        <label>
                                            <input name="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="Yes" <?php
                                                                                                                                if (isset(${$field_name}) && ${$field_name} == 'Yes') {
                                                                                                                                    echo 'checked=""';
                                                                                                                                } ?>>
                                            <span>Yes</span>
                                        </label> &nbsp;&nbsp;
                                        <label>
                                            <input name="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="No" <?php
                                                                                                                                if (isset(${$field_name}) && ${$field_name} == 'No') {
                                                                                                                                    echo 'checked=""';
                                                                                                                                } ?>>
                                            <span>No</span>
                                        </label>
                                    </p>
                                </div>
                                <div class="input-field col m3 s12 custom_margin_bottom_col">
                                    <?php
                                    $field_name     = "is_imaged_po";
                                    $field_label     = "To Be Imaged";
                                    ?>
                                    <div style="margin-top: -10px; margin-bottom: 10px;">
                                        <?= $field_label; ?>
                                        <span class="color-red">* <?php
                                                                    if (isset($error[$field_name])) {
                                                                        echo $error[$field_name];
                                                                    } ?>
                                        </span>
                                    </div>
                                    <p class="mb-1 custom_radio">
                                        <label>
                                            <input name="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="Yes" <?php
                                                                                                                                if (isset(${$field_name}) && ${$field_name} == 'Yes') {
                                                                                                                                    echo 'checked=""';
                                                                                                                                } ?>>
                                            <span>Yes</span>
                                        </label> &nbsp;&nbsp;
                                        <label>
                                            <input name="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="No" <?php
                                                                                                                                if (isset(${$field_name}) && ${$field_name} == 'No') {
                                                                                                                                    echo 'checked=""';
                                                                                                                                } ?>>
                                            <span>No</span>
                                        </label>
                                    </p>
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
                                    unset($product_condition);
                                    unset($order_price);
                                    unset($order_qty);
                                    unset($expected_status);

                                    $sql_ee1    = "SELECT a.* FROM purchase_order_detail a WHERE a.po_id = '" . $id . "' ";
                                    $result_ee1 = $db->query($conn, $sql_ee1);
                                    $count_ee1  = $db->counter($result_ee1);
                                    if ($count_ee1 > 0) {
                                        $row_ee1 = $db->fetch($result_ee1);
                                        foreach ($row_ee1 as $data2) {
                                            $product_ids[]          = $data2['product_id'];
                                            $product_condition[]    = $data2['product_condition'];
                                            $order_price[]          = $data2['order_price'];
                                            $order_qty[]            = $data2['order_qty'];
                                            $expected_status[]      = $data2['expected_status'];
                                        }
                                    }
                                }?>

                                <?php 
                                if(isset($id) && $id>0){
                                    unset($package_ids);
                                    unset($order_part_qty);
                                    unset($order_part_price);
                                    unset($case_pack);
                                    $sql_ee1		= " SELECT a.*, b.case_pack 
                                                        FROM purchase_order_packages_detail a 
                                                        INNER JOIN packages b ON b.id = a.package_id
                                                        WHERE a.po_id = '" . $id . "' ";  //echo $sql_ee1;
                                    $result_ee1		= $db->query($conn, $sql_ee1);
                                    $count_ee1  	= $db->counter($result_ee1);
                                    if($count_ee1 > 0){
                                        $row_ee1	= $db->fetch($result_ee1);
                                        foreach($row_ee1 as $data2){ 
                                            $package_ids[]	        = $data2['package_id'];
                                            $order_part_qty[]		= $data2['order_qty'];
                                            $order_part_price[]		= $data2['order_price'];
                                            $case_pack[]		    = $data2['case_pack'];
                                            
                                        }
                                    }
                                }?>
                                <thead>
                                    <tr>
                                        <th style="width: %;">
                                            Product &nbsp;
                                            <?php
                                            if (isset($order_status) && $order_status == 1) { ?>
                                                <a href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=import_po_details&id=" . $id) ?>" class="btn gradient-45deg-amber-amber waves-effect waves-light custom_btn_size">
                                                    Import
                                                </a> &nbsp;&nbsp;
                                                <?php
                                                if (!isset($package_ids) || (isset($package_ids) && sizeof($package_ids) ==0)) { ?>
                                                    <a class=" btn gradient-45deg-amber-amber waves-effect waves-light custom_btn_size package_material_parts" style="line-height: 32px;" id="add-more^0" href="javascript:void(0)" style="display: none;">
                                                        Add Packages / Parts 
                                                    </a>
                                                <?php } ?>
                                                <a class="add-more add-more-btn2 btn-sm btn-floating waves-effect waves-light cyan first_row" style="line-height: 32px; display: none;" id="add-more^0" href="javascript:void(0)" style="display: none;">
                                                    <i class="material-icons  dp48 md-36">add_circle</i>
                                                </a>
                                            <?php } ?>
                                        </th>
                                        
                                        <th style="width: 100px;">Qty</th>
                                        <th style="width: 100px;">Price</th>
                                        <th style="width: 100px;">Value</th>
                                        <th style="width: 120px;">Condition</th>
                                        <th style="width: 200px;">Status</th>
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
                                    if (isset($order_status) && $order_status != 1) {
                                        $disabled = "disabled='disabled'";
                                        $readonly = "readonly='readonly'";
                                    }
                                    $sum_value = $sum_qty =  $sum_price = 0 ;
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

                                        $sql1             = " SELECT a.*, b.category_name
                                                            FROM products a
                                                            LEFT JOIN product_categories b ON b.id = a.product_category
                                                            WHERE a.enabled = 1 
                                                            ORDER BY a.product_desc";
                                        $result1         = $db->query($conn, $sql1);
                                        $count1         = $db->counter($result1); ?>
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
                                                $field_name     = "order_qty";
                                                $field_id       = "orderqty_" . $i;
                                                $field_label     = "Quantity";  
                                                ?>
                                                <input <?php echo $disabled;
                                                        echo $readonly; ?> name="<?= $field_name; ?>[]" type="number" id="<?= $field_id; ?>" value="<?php if (isset(${$field_name}[$i - 1])) {
                                                                                                                                                        echo ${$field_name}[$i - 1];
                                                                                                                                                    } ?>" class="validate custom_input order_qty">
                                            </td>
                                            <td>
                                                <?php
                                                $field_name     = "order_price";
                                                $field_id       = "orderprice_" . $i;
                                                $field_label     = "Unit Price";
                                                ?>
                                                <input <?php echo $disabled;
                                                        echo $readonly; ?> name="<?= $field_name; ?>[]" type="number" id="<?= $field_id; ?>" value="<?php if (isset(${$field_name}[$i - 1])) {
                                                                                                                                                        echo ${$field_name}[$i - 1];
                                                                                                                                                    } ?>" class="validate custom_input order_price">
                                            </td>
                                            <td class="text_align_right">
                                                <span id="value_<?= $i; ?>">
                                                    <?php
                                                    $value = 0;
                                                    if (isset($order_qty[$i - 1]) && isset($order_price[$i - 1])) {
                                                        $value =  ($order_price[$i - 1] * $order_qty[$i - 1]);
                                                        $sum_price += $order_price[$i - 1];
                                                        $sum_qty += $order_qty[$i - 1];
                                                    }
                                                    echo number_format($value, 2);
                                                    $sum_value += $value; ?>
                                                </span>
                                            </td>
                                           
                                            <td>
                                                <?php
                                                $field_name     = "product_condition";
                                                $field_id       = "productcondition_" . $i;
                                                $field_label     = "Product Condition";
                                                ?>

                                                <select <?php echo $disabled;
                                                        echo $readonly; ?> name="<?= $field_name ?>[]" id="<?= $field_id ?>" class="browser-default custom_condition_class">
                                                    <option value="">N/A</option>
                                                    <option value="A" <?php if (isset(${$field_name}[$i - 1]) && ${$field_name}[$i - 1] == "A") { ?> selected="selected" <?php } ?>>A</option>
                                                    <option value="B" <?php if (isset(${$field_name}[$i - 1]) && ${$field_name}[$i - 1] == "B") { ?> selected="selected" <?php } ?>>B</option>
                                                    <option value="C" <?php if (isset(${$field_name}[$i - 1]) && ${$field_name}[$i - 1] == "C") { ?> selected="selected" <?php } ?>>C</option>
                                                    <option value="D" <?php if (isset(${$field_name}[$i - 1]) && ${$field_name}[$i - 1] == "D") { ?> selected="selected" <?php } ?>>D</option>
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
                                                                    AND id IN(5, 6, 13, 20, 27)";
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
                                                <?php if (isset($order_status) && $order_status == 1) { ?>
                                                    <a class="remove-row btn-sm btn-floating waves-effect waves-light red" style="line-height: 32px;" id="remove-row^<?= $i ?>" href="javascript:void(0)">
                                                        <i class="material-icons dp48">cancel</i>
                                                    </a> &nbsp;
                                                    <a class="add-more add-more-btn btn-sm btn-floating waves-effect waves-light cyan" style="line-height: 32px; display:none;" id="add-more^<?= $i ?>" href="javascript:void(0)">
                                                        <i class="material-icons dp48">add_circle</i>
                                                    </a>&nbsp;&nbsp;
                                                <?php }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        
                                        <td class="text_align_right"><b>Total: </b></td>
                                        <td class="text_align_left">
                                            <span id="total_qty"><?php echo ($sum_qty); ?></b></span>
                                        </td>
                                        <td class="text_align_left"></td>
                                        <td class="text_align_right"><b>
                                                <span id="total_value"><?php echo number_format($sum_value, 2); ?></b></span>
                                        </td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div id="package_parts" style="<?php if(isset($package_ids) && $package_ids>0){ ; } else{ echo "display:none;";} ?>">
                                <br>
                                <table id="page-length-option2" class="bordered addproducttable" cellpadding="0" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th style="width: %;">
                                                Package / Part &nbsp;&nbsp;
                                                <a class="add-more add-part-more-btn2 btn-sm btn-floating waves-effect waves-light cyan first_row_part" style="line-height: 32px; display: none;" id="add-more^0" href="javascript:void(0)" style="display: none;">
                                                    <i class="material-icons  dp48 md-36">add_circle</i>
                                                </a>
                                            </th>
                                            <th style="width: 100px;">Qty</th>
                                            <th style="width: 100px;">Price</th>
                                            <th style="width: 100px;">Value</th>
                                            <th style="width: 120px;">Case Pack</th>
                                            <th style="width: 200px;">Total Case Pack</th>
                                            <th style="width: 150px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <input type="hidden" id="total_products_part_in_po" value="<?php if (!isset($package_ids) || (isset($package_ids) && sizeof($package_ids) == 0)) {
                                            echo "1";
                                        } else {
                                            echo sizeof($package_ids);
                                        } ?>">
                                        <?php
                                        $disabled = $readonly = "";
                                        if((isset($order_status) && $order_status != 1 )){
                                            $disabled = "disabled='disabled'";
                                            $readonly = "readonly='readonly'";
                                        }
                                        $sum_part_value = $sum_part_qty =  $sum_part_price = 0 ;
                                        for($i = 1; $i <= 50; $i++) {
                                            $field_name     = "package_ids";
                                            $field_id       = "packageids_".$i;
                                            $style_btn = '';
                                            $style = ""; 
                                        
                                            if(!isset(${$field_name}[$i-1]) || (isset(${$field_name}[$i-1]) && ${$field_name}[$i-1] == "" || ${$field_name}[$i-1] == 0)){  
                                                if($i > 1){
                                                    if(isset($package_ids) && sizeof($package_ids) >0){
                                                        $style = 'style="display:none;"'; 
                                                    }else{  
                                                        $style = $i === 1 ? '' : 'style="display:none;"';
                                                    }
                                                } 
                                            }
                                            else{
                                                if(isset($package_ids) && is_array($package_ids) && sizeof($package_ids)>1){ 
                                                    $style = $i <= sizeof($package_ids) ? '' : 'style="display:none;"';
                                                    $style_btn = $i <= sizeof($package_ids) ? 'style="display:none;"' : '';
                                                }
                                                else{
                                                    $style = $i === 1 ? '' : 'style="display:none;"';
                                                    $style_btn = $i === 1 ? 'style="display:none;"' : '';
                                                }
                                            }
                                            $sql1             = "   SELECT a.*, b.category_name
                                                                    FROM packages a
                                                                    LEFT JOIN product_categories b ON b.id = a.product_category
                                                                    WHERE a.enabled = 1 
                                                                    ORDER BY a.package_name, b.category_name";
                                            $result1         = $db->query($conn, $sql1);
                                            $count1         = $db->counter($result1);
                                            ?>
                                            <tr class="dynamic-row-part" id="row_part_<?=$i;?>" <?php echo $style; ?>>
                                                <td>
                                                    <select <?php echo $disabled; echo $readonly; ?> name="<?=$field_name?>[]" id="<?=$field_id?>" class="select2-theme browser-default select2-hidden-accessible product_packages <?=$field_name?>_<?=$i?>">
                                                        <option value="">Select a product</option>
                                                        <?php
                                                        if ($count1 > 0) {
                                                            $row1    = $db->fetch($result1);
                                                            foreach ($row1 as $data2) { ?>
                                                                <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}[$i-1]) && ${$field_name}[$i-1] == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['package_name']; ?> (<?php echo $data2['category_name']; ?>)</option>
                                                        <?php }
                                                        } ?>
                                                        <option value="package_add_modal">+Add New Package/Part</option>
                                                    </select>
                                                </td>
                                                
                                                <td>
                                                    <?php 
                                                    $field_name     = "order_part_qty";
                                                    $field_id       = "orderpartqty_".$i; 
                                                    
                                                    if (isset($order_part_qty[$i - 1])) {
                                                        $sum_part_qty += $order_part_qty[$i - 1];
                                                    }
                                                    ?>
                                                    <input <?php echo $disabled; echo $readonly; ?> name="<?= $field_name; ?>[]" type="number"  id="<?= $field_id; ?>" value="<?php if (isset(${$field_name}[$i-1])) { echo ${$field_name}[$i-1];} ?>" class="validate custom_input order_part_qty">
                                                </td>
                                                <td>
                                                    <?php
                                                    $field_name     = "order_part_price";
                                                    $field_id       = "orderpartprice_".$i; 
                                                    ?>
                                                    <input <?php echo $disabled; echo $readonly; ?> name="<?= $field_name; ?>[]" type="number"  id="<?= $field_id; ?>" value="<?php if (isset(${$field_name}[$i-1])) { echo ${$field_name}[$i-1];} ?>" class="validate custom_input order_part_price">
                                                </td>
                                                <td class="text_align_right">
                                                    <span id="part_value_<?= $i; ?>">
                                                        <?php
                                                        $part_value = 0;
                                                        if (isset($order_part_qty[$i - 1]) && isset($order_part_price[$i - 1])) {
                                                            $part_value =  ($order_part_price[$i - 1] * $order_part_qty[$i - 1]);
                                                            $sum_part_price += $order_part_price[$i - 1];
                                                            $sum_part_qty   += $order_part_qty[$i - 1];
                                                        }
                                                        echo number_format($part_value, 2);
                                                        $sum_part_value += $part_value; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span id="case_pack_<?= $i; ?>"><?php if (isset($case_pack[$i-1])) { echo $case_pack[$i-1];} ?></span>
                                                </td>
                                                <td>
                                                    <span id="total_case_pack_<?= $i; ?>">
                                                        <?php 
                                                        if (isset($case_pack[$i-1]) && isset($order_part_qty[$i-1])) {
                                                            echo ceil($order_part_qty[$i-1] / $case_pack[$i-1]);
                                                        } ?>
                                                    </span>
                                                </td>
                                                <td colspan="3">
                                                    <?php if(isset($order_status) && $order_status == 1){ ?>
                                                            <a  class="remove-row-part btn-sm btn-floating waves-effect waves-light red" style="line-height: 32px;" id="removepartrow_<?=$i?>" href="javascript:void(0)">
                                                                <i class="material-icons dp48">cancel</i>
                                                            </a> &nbsp;
                                                            <a class="add-more add-more-part-btn btn-sm btn-floating waves-effect waves-light cyan" style="line-height: 32px; display:none;" id="addpartmore_<?=$i?>" href="javascript:void(0)">
                                                                <i class="material-icons dp48">add_circle</i>
                                                            </a>&nbsp;&nbsp;
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php }  
                                        $field_name     = "product_id_for_package_material"; 
                                        ?>
                                        <input name="<?= $field_name; ?>" type="hidden"  id="<?= $field_name; ?>" value="">
                                        <tr>
                                            <td class="text_align_right"><b>Total: </b></td>
                                            <td class="text_align_left">
                                                <span id="total_part_qty"><?php echo ($sum_part_qty); ?></b></span>
                                            </td>
                                            <td></td>
                                            <td class="text_align_right"><b>
                                                <span id="total_part_value"><?php echo number_format($sum_part_value, 2); ?></b></span>
                                            </td>
                                            <td colspan="3"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col s12 m12 l12">
                    <div id="Form-advance2" class="card card card-default scrollspy custom_margin_section">
                        <div class="card-content custom_padding_section">
                            <div class="row">
                                <div class="input-field col m6 s12">
                                    <?php
                                    $field_name     = "po_desc";
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
                                    $field_name     = "po_desc_public";
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