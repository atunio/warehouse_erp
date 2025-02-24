<div id="tab1_html" class="active" style="display: <?php if (isset($active_tab) && $active_tab == 'tab1') {
                                                        echo "block";
                                                    } else {
                                                        echo "none";
                                                    } ?>;">  
    <input type="hidden" id="module_id" value="<?= $module_id;?>" />
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
                    <?= $general_heading;?> --> Master Info 
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
                if (isset($so_no) && isset($id)) { 
                    if (access("edit_perm") == 1) { ?>
                        <button class="btn cyan waves-effect waves-light green custom_btn_size" type="submit" name="action">
                            Save changes
                        </button>
                <?php }
                } 
                include("tab_action_btns.php");?>
            </div>
        </div>
    </div> 
    <div class="row ">
        <div class="col s12 m12 l12">
            <div id="Form-advance" class="card card card-default scrollspy custom_margin_section">
                <div class="card-content custom_padding_section ">
                    <?php            
                    if (isset($so_no) && isset($id)) { ?>
                        <h5 class="media-heading"><span class=""><?php echo "<b>Sale Order No: </b>" . $so_no; ?></span></h5>
                    <?php } ?><br>
                    <?php  
                    if(isset($cmd) && $cmd == 'add'){?>
                    <form method="post" autocomplete="off" action="<?php echo "?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=profile&cmd=edit&active_tab=tab1&cmd=' . $cmd . '&id=' . $id); ?>">
                    <input type="hidden" name="is_Submit" value="Y" />
                    <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                        echo encrypt($_SESSION['csrf_session']);
                                                                    } ?>">
                    <input type="hidden" name="active_tab" value="tab1" />
                    <?php } ?>
                    <div class="row">
                        <div class="input-field col m4 s12 custom_margin_bottom_col">
                            <?php
                            $field_name     = "customer_id";
                            $field_label     = "Customer";
                            $sql1             = "SELECT * FROM customers WHERE enabled = 1 ORDER BY customer_name ";
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
                                            <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['customer_name']; ?> - Phone: <?php echo $data2['phone_primary']; ?></option>
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
                        <div class="input-field col m2 s12 custom_margin_bottom_col">
                            <br>
                            <a class="waves-effect waves-light btn modal-trigger custom_btn_size" href="#customer_add_modal">Add New Customer</a>
                        </div>
                        
                        <?php
                        $field_name     = "order_date";
                        $field_label     = "Order Date (d/m/Y)";
                        ?>
                        <div class="input-field col m3 s12 custom_margin_bottom_col">
                            <i class="material-icons prefix">date_range</i>
                            <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                echo ${$field_name};
                                                                                                            } ?>" class="custom_input_heigh datepicker validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                                                } ?>">
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
                        $field_name     = "customer_invoice_no";
                        $field_label     = "Customer Invoice #";
                        ?>
                        <div class="input-field col m3 s12 custom_margin_bottom_col">
                            <i class="material-icons prefix">question_answer</i>
                            <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                echo ${$field_name};
                                                                                                            } ?>" class="custom_input_heigh validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                                    } ?>">
                            <label for="<?= $field_name; ?>">
                                <?= $field_label; ?>
                                <span class="color-red">* <?php
                                                            if (isset($error[$field_name])) {
                                                                echo $error[$field_name];
                                                            } ?>
                                </span>
                            </label>
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="input-field col m3 s12 custom_margin_bottom_col">
                            <?php
                            $field_name     = "source_id";
                            $field_label     = "Sources";
                            $sql1             = "SELECT * FROM sources WHERE enabled = 1 ORDER BY source_name ";
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
                                        foreach ($row1 as $data2) {
                                            echo "$field_name == " . $data2['id']; ?>
                                            <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['source_name']; ?></option>
                                    <?php }
                                    } ?>
                                </select>
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
                        <div class="input-field col m3 s12 custom_margin_bottom_col">
                            <?php
                            $field_name     = "origin_id";
                            $field_label     = "Origins";
                            $sql1             = "SELECT * FROM origins WHERE enabled = 1 ORDER BY origin_name ";
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
                                            <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['origin_name']; ?></option>
                                    <?php }
                                    } ?>
                                </select>
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
                        <div class="input-field col m3 s12 custom_margin_bottom_col">
                            <?php
                            $field_name     = "fullfilment_id";
                            $field_label     = "Fullfilments";
                            $sql1             = "SELECT * FROM fullfilments WHERE enabled = 1 ORDER BY fullfilments_name ";
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
                                            <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['fullfilments_name']; ?></option>
                                    <?php }
                                    } ?>
                                </select>
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
                        <div class="input-field col m3 s12 custom_margin_bottom_col">
                            <?php
                            $field_name     = "terms_id";
                            $field_label     = "Terms";
                            $sql1             = "SELECT * FROM terms WHERE enabled = 1 ORDER BY term_name ";
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
                                            <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['term_name']; ?></option>
                                    <?php }
                                    } ?>
                                </select>
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
                    </div><br>
                    <div class="row">
                        <div class="input-field col m3 s12 custom_margin_bottom_col">
                            <?php
                            $field_name     = "requested_shipment_id";
                            $field_label     = "Requested Shipments";
                            $sql1             = "SELECT * FROM requested_shipments WHERE enabled = 1 ORDER BY requested_shipment_name ";
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
                                            <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['requested_shipment_name']; ?></option>
                                    <?php }
                                    } ?>
                                </select>
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
                        <div class="input-field col m3 s12 custom_margin_bottom_col">
                            <?php
                            $field_name     = "batch_id";
                            $field_label     = "Batchs";
                            $sql1             = "SELECT * FROM batchs WHERE enabled = 1 ORDER BY batch_name ";
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
                                            <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['batch_name']; ?></option>
                                    <?php }
                                    } ?>
                                </select>
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
                    
                    <?php if (($cmd == 'add' && access("add_perm") == 1)) { ?>
                        <div class="row">
                            <div class="input-field col m6 s12">
                                <button class="btn cyan waves-effect waves-light right custom_btn_size" type="submit" name="action"><?php echo $button_val; ?>
                                    <i class="material-icons right">send</i>
                                </button>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(isset($cmd) && $cmd == 'add'){?>
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
                            <thead>
                                <tr>
                                    
                                    <th style="width: %;">
                                        Sale Product &nbsp;
                                        <?php
                                        if(isset($order_status) && $order_status == 1){?>
                                            <a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=import_so_details&id=" . $id) ?>" class="btn gradient-45deg-amber-amber waves-effect waves-light custom_btn_size">
                                                Import
                                            </a> 
                                            &nbsp;&nbsp;
                                            <a class="add-more add-more-btn2 btn-sm btn-floating waves-effect waves-light cyan first_row" style="line-height: 32px; display: none;" id="add-more^0" href="javascript:void(0)" style="display: none;">
                                                <i class="material-icons  dp48 md-36">add_circle</i>
                                            </a> 
                                        <?php }?>
                                    </th>
                                    <th style="width: 400px;">Description</th>
                                    <th style="width: 100px;">Sale Price</th>
                                    <th style="width: 150px;">Actions</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(isset($id) && $id>0){

                                    unset($product_stock_ids);
                                    unset($order_price);
                                    unset($product_so_desc);

                                    $sum_value      = 0;
                                    $sql_ee1		= "SELECT a.* FROM sales_order_detail a WHERE a.sales_order_id = '" . $id . "' ";  //echo $sql_ee1;
                                    $result_ee1		= $db->query($conn, $sql_ee1);
                                    $count_ee1  	= $db->counter($result_ee1);
                                    if($count_ee1 > 0){
                                        $row_ee1	= $db->fetch($result_ee1);
                                        foreach($row_ee1 as $data2){
                                            $product_stock_ids[]	= $data2['product_stock_id'];
                                            $order_price[]			= $data2['order_price'];
                                            $product_so_desc[]      = $data2['product_so_desc'];
                                            $sum_value             += $data2['order_price'];
                                        }
                                    }
                                }?>
                                <input type="hidden" id="total_products_in_po" value="<?php if (!isset($product_stock_ids) || (isset($product_stock_ids) && sizeof($product_stock_ids) == 0)) {
                                                                                            echo "1";
                                                                                        } else {
                                                                                            echo sizeof($product_stock_ids);
                                                                                        } ?>">
                                <?php
                                $disabled = $readonly = "";
                                if((isset($order_status) && $order_status != 1 )){
                                    $disabled = "disabled='disabled'";
                                    $readonly = "readonly='readonly'";
                                }
                                for($i = 1; $i <= 50; $i++) {
                                    $field_name     = "product_stock_ids";
                                    $field_id       = "productstockids_".$i;
                                    $field_label    = "Product";
                                    $style_btn = '';
                                    $style = ""; 
                                   
                                    if(!isset(${$field_name}[$i-1]) || (isset(${$field_name}[$i-1]) && ${$field_name}[$i-1] == "" || ${$field_name}[$i-1] == 0)){  
                                        if($i > 1){
                                            if(isset($product_stock_ids) && sizeof($product_stock_ids) >0){
                                                $style = 'style="display:none;"'; 
                                            }else{  
                                                $style = $i === 1 ? '' : 'style="display:none;"';
                                            }
                                        } 
                                    }
                                    else{
                                        if(isset($product_stock_ids) && is_array($product_stock_ids) && sizeof($product_stock_ids)>1){ 
                                            $style = $i <= sizeof($product_stock_ids) ? '' : 'style="display:none;"';
                                            $style_btn = $i <= sizeof($product_stock_ids) ? 'style="display:none;"' : '';
                                        }
                                        else{
                                            $style = $i === 1 ? '' : 'style="display:none;"';
                                            $style_btn = $i === 1 ? 'style="display:none;"' : '';
                                        }
                                    }
                                
                                    $sql1       = " SELECT c.*, c1.category_name,b.serial_no, b.id As product_stock_id, b.price
                                                    FROM product_stock b 
                                                    INNER JOIN products c ON c.id = b.product_id
                                                    LEFT JOIN product_categories c1 ON c1.id = c.product_category
                                                    LEFT JOIN sales_order_detail d ON d.product_stock_id = b.id AND d.sales_order_id = '".$id."' 
                                                    WHERE b.enabled = 1 
                                                    AND (
                                                            (d.id IS NULL AND b.p_total_stock > 0 AND b.is_packed = 0)
                                                            OR (d.id > 0)
                                                        )
                                                    GROUP BY b.serial_no
                                                    ORDER BY b.serial_no ";
                                    $result1    = $db->query($conn, $sql1);
                                    $count1     = $db->counter($result1);
                                    
                                    ?>
                                    <tr class="dynamic-row" id="row_<?=$i;?>" <?php echo $style; ?>>
                                        <td>
                                            <select <?php echo $disabled; echo $readonly; ?> name="<?=$field_name?>[]" id="<?=$field_id?>" class="select2-theme browser-default select2-hidden-accessible product_stock <?=$field_name?>_<?=$i?>">
                                                <option value="">Select a product</option>
                                                <?php
                                                if ($count1 > 0) {
                                                    $row1    = $db->fetch($result1);
                                                    foreach ($row1 as $data2) { ?>
                                                        <option value="<?php echo $data2['product_stock_id']; ?>" <?php if (isset($product_stock_ids[$i-1]) && $product_stock_ids[$i-1] == $data2['product_stock_id']) { echo 'selected="selected"'; } ?>><?php echo $data2['product_desc']; ?> (<?php echo $data2['category_name']; ?>) - <?php echo $data2['product_uniqueid']; ?>, Serial#: - <?php echo $data2['serial_no']; ?>, Purchase Price: - <?php echo $data2['price']; ?> </option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <?php
                                            $field_name     = "product_so_desc";
                                            $field_id       = "productsodesc_".$i;
                                            $field_label     = "Product Desc";
                                            ?>
                                            <textarea <?php echo $disabled; echo $readonly; ?> id="<?= $field_name; ?>" name="<?= $field_name; ?>[]" class="materialize-textarea validate "><?php if (isset($product_so_desc[$i-1])) {
                                                                                                                                        echo $product_so_desc[$i-1];
                                                                                                                                    } ?></textarea>
                                            
                                        </td>
                                        <td>
                                            <?php
                                            $field_name     = "order_price";
                                            $field_id       = "orderprice_".$i;
                                            $field_label     = "Unit Price";
                                            ?>
                                            <input <?php echo $disabled; echo $readonly; ?> name="<?= $field_name; ?>[]" type="number"  id="<?= $field_id; ?>" value="<?php if (isset(${$field_name}[$i-1])) { echo ${$field_name}[$i-1];} ?>" class="validate custom_input order_price">
                                        </td>
                                        <td>
                                            <?php 
                                            if(isset($order_status) && $order_status == 1){ ?>
                                                <a  class="remove-row btn-sm btn-floating waves-effect waves-light red" style="line-height: 32px;" id="remove-row^<?=$i?>" href="javascript:void(0)">
                                                    <i class="material-icons dp48">cancel</i>
                                                </a> &nbsp;
                                                <a class="add-more add-more-btn btn-sm btn-floating waves-effect waves-light cyan" style="line-height: 32px; display:none;" id="add-more^<?=$i?>" href="javascript:void(0)">
                                                    <i class="material-icons dp48">add_circle</i>
                                                </a>&nbsp;&nbsp;
                                            <?php }?>
                                        </td>
                                    </tr>
                                <?php }  ?>
                                <tr>
                                    <td></td>
                                    <td class="text_align_right"><b>Total: </b></td>
                                    <td class="text_align_right">
                                        <b></b><span id="total_value"><?php echo number_format($sum_value, 2); ?></b></span>
                                    </td>
                                    <td></td>
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
                                <textarea <?php echo $disabled; echo $readonly; ?> id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="materialize-textarea validate "><?php if (isset(${$field_name})) {
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
                                <textarea <?php echo $disabled; echo $readonly; ?> id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="materialize-textarea validate "><?php if (isset(${$field_name})) {
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
            <?php 
            if (isset($cmd) && $cmd == 'edit') { ?>
                </form>
            <?php } 
            
            $sql_cl     = "	SELECT d.category_name, COUNT(a.id) AS total_qty
                            FROM sales_order_detail a  
                            INNER JOIN sales_orders b ON b.id = a.sales_order_id
                            INNER JOIN product_stock c ON c.id = a.product_stock_id
                            INNER JOIN products c1 ON c1.id = c.product_id
                            LEFT JOIN product_categories d ON d.id = c1.product_category
                            WHERE a.sales_order_id = '" . $id . "' 
                            AND a.enabled = 1
                            GROUP BY c1.product_category
                            ORDER BY c1.product_category  "; // echo $sql_cl;
            $result_cl  = $db->query($conn, $sql_cl);
            $count_cl   = $db->counter($result_cl);
            if ($count_cl > 0) {?>
                <!-- Summary -->
                <div class="col s12 m12 l12">
                    <div class="card-panel">
                        <div class="row">
                            <div class="col m2 s12">
                                <h5 class="h5">Category Wise</h5>
                            </div>
                            <div class="col m9 s12">
                                <div class="text_align_right">
                                    <?php 
                                    $table_columns	= array('SNo', 'Product Category', 'Qty');
                                    $k 				= 0;
                                    foreach($table_columns as $data_c1){?>
                                        <label>
                                            <input type="checkbox" value="<?= $k?>" name="table_columns[]" class="filled-in toggle-column" data-column="<?= set_table_headings($data_c1)?>" checked="checked">
                                            <span><?= $data_c1?></span>
                                        </label>&nbsp;&nbsp;
                                    <?php 
                                        $k++;
                                    }?> 
                                </div>
                            </div>
                            <div class="col m1 s12">
                                <a href="export/export_sales_order_summary.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" class="custom_btn_size mb-4 btn waves-effect waves-light gradient-45deg-green-teal">
                                    <i class="material-icons medium icon-demo">vertical_align_bottom</i>
                                </a>
                        
                                <a class="mb-4 btn waves-effect waves-light cyan custom_btn_size" href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/sales_order_summary_print.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" target="_blank">
                                    <i class="material-icons medium icon-demo">print</i>
                                </a>
                            </div>
                        </div>
                       
                        <div class="section section-data-tables">
                            <div class="row">
                                <div class="col s12">
                                    <table id="page-length-option" class="display pagelength50 dataTable dtr-inline ">
                                        <thead>
                                            <tr>
                                                <?php
                                                $headings = "";
                                                foreach($table_columns as $data_c){
                                                    if($data_c == 'SNo'){
                                                        $headings .= '<th class="sno_width_60 col-'.set_table_headings($data_c).'">'.$data_c.'</th>';
                                                    }
                                                    else{
                                                        $headings .= '<th class="col-'.set_table_headings($data_c).'">'.$data_c.'</th> ';
                                                    }
                                                } 
                                                echo $headings;
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 0; 
                                            $row_cl = $db->fetch($result_cl);
                                            foreach ($row_cl as $data) { ?>
                                                <tr>
                                                    <td style="text-align: center;" class="col-<?= set_table_headings($table_columns[0]);?>"><?php echo $i + 1; ?></td>
                                                    <td class="col-<?= set_table_headings($table_columns[1]);?>"><?php echo "" . $data['category_name'] . ""; ?></td>
                                                    <td class="col-<?= set_table_headings($table_columns[2]);?>"><?php echo $data['total_qty']; ?></td>
                                                </tr>
                                        <?php   
                                                $i++;
                                            }
                                        }?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>                       
                </div>
            <?php }
            $sql_cl     = "	SELECT c1.product_desc, d.category_name, c1.product_uniqueid, COUNT(a.id) AS total_qty
                            FROM sales_order_detail a  
                            INNER JOIN sales_orders b ON b.id = a.sales_order_id
                            INNER JOIN product_stock c ON c.id = a.product_stock_id
                            INNER JOIN products c1 ON c1.id = c.product_id
                            LEFT JOIN product_categories d ON d.id = c1.product_category 
                            WHERE a.sales_order_id = '" . $id . "' 
                            AND a.enabled = 1
                            GROUP BY c.product_id
                            ORDER BY c.product_id  "; // echo $sql_cl;
            $result_cl  = $db->query($conn, $sql_cl);
            $count_cl   = $db->counter($result_cl);
            if ($count_cl > 0) {?>
                <!-- Summary Details-->
                <div class="col s12 m12 l12">
                    <div class="card-panel">
                        <div class="row">
                            <div class="col m2 s12">
                                <h5 class="h5">Product Wise</h5>
                            </div>
                            <div class="col m9 s12">
                                <div class="text_align_right">
                                    <?php 
                                    $table_columns	= array('SNo', 'Product ID', 'Detail' ,'Qty');
                                    $k 				= 0;
                                    foreach($table_columns as $data_c1){?>
                                        <label>
                                            <input type="checkbox" value="<?= $k?>" name="table_columns[]" class="filled-in toggle-column" data-column="<?= set_table_headings($data_c1)?>" checked="checked">
                                            <span><?= $data_c1?></span>
                                        </label>&nbsp;&nbsp;
                                    <?php 
                                        $k++;
                                    }?> 
                                </div>
                            </div>
                            <div class="col m1 s12">
                                <a href="export/export_sales_order_summary_detail.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" class="mb-4 custom_btn_size btn waves-effect waves-light gradient-45deg-green-teal">
                                    <i class="material-icons medium icon-demo">vertical_align_bottom</i>
                                </a>
                            
                                <a class="mb-4 custom_btn_size btn waves-effect waves-light cyan" href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/sales_order_summary_detail_print.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" target="_blank">
                                    <i class="material-icons medium icon-demo">print</i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="section section-data-tables">
                            <div class="row">
                                <div class="col s12">
                                    <table id="page-length-option" class="display pagelength50_2 dataTable dtr-inline ">
                                        <thead>
                                            <tr>
                                            <?php
                                                $headings = "";
                                                foreach($table_columns as $data_c){
                                                    if($data_c == 'SNo'){
                                                        $headings .= '<th class="sno_width_60 col-'.set_table_headings($data_c).'">'.$data_c.'</th>';
                                                    }
                                                    else{
                                                        $headings .= '<th class="col-'.set_table_headings($data_c).'">'.$data_c.'</th> ';
                                                    }
                                                } 
                                                echo $headings;
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 0;
                                            $row_cl = $db->fetch($result_cl);
                                            foreach ($row_cl as $data) {
                                                $total_qty      = $data['total_qty'];  ?>
                                                <tr>
                                                    <td style="text-align: center;" class="col-<?= set_table_headings($table_columns[0]);?>"><?php echo $i + 1; ?></td>
                                                    <td class="col-<?= set_table_headings($table_columns[1]);?>"><?php echo "" . $data['product_uniqueid']; ?><br></td>
                                                    <td class="col-<?= set_table_headings($table_columns[2]);?>">
                                                        <?php echo ucwords(strtolower($data['product_desc'])); ?>
                                                        <?php
                                                        if ($data['category_name'] != "") {
                                                            echo  " (" . $data['category_name'] . ")";
                                                        } ?>
                                                    </td>
                                                    <td class="col-<?= set_table_headings($table_columns[3]);?>"><?php echo $total_qty; ?></td>
                                                </tr>
                                        <?php
                                                $i++;
                                            }?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>                        
                </div>
            <?php } ?> 
    </div>
</div>
