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
                if (isset($rma_no) && isset($id)) { 
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
                        <h5 class="media-heading"><span class=""><?php echo "<b>RMA Order No: </b>" . $so_no; ?></span></h5>
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
                            $field_name     = "po_id";
                            $field_label    = "Purchase Order No";
                            $sql            = " SELECT a.id,a.po_no 
                                                FROM purchase_orders a 
                                                WHERE a.enabled = 1 
                                                GROUP BY a.po_no 
                                                ORDER BY a.po_no DESC";
                            // echo $sql; 
                            $result_log2    = $db->query($conn, $sql);
                            $count_r2       = $db->counter($result_log2); ?>
                            <i class="material-icons prefix">question_answer</i>
                            <div class="select2div">
                                <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                                                                } ?>">
                                    <?php
                                    if ($count_r2 > 1) { ?>
                                        <option value="">Select</option>
                                        <?php
                                    }
                                    if ($count_r2 > 0) {
                                        $row_r2    = $db->fetch($result_log2);
                                        foreach ($row_r2 as $data_r2) {

                                            $detail_id_r1       = $data_r2['id'];
                                            $product_uniqueid_r1  = $data_r2['product_uniqueid'];  ?>

                                            <option value="<?php echo $data_r2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data_r2['id']) { ?> selected="selected" <?php } ?>>
                                                <?php
                                                echo $data_r2['po_no'];
                                                ?>
                                            </option>
                                    <?php
                                        }
                                    } ?>
                                </select>
                                <label for="<?= $field_name; ?>">
                                    <?= $field_label; ?>
                                    <span class="color-red">* <?php
                                                                if (isset($error7[$field_name])) {
                                                                    echo $error7[$field_name];
                                                                } ?>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <?php
                        $field_name     = "rma_date";
                        $field_label     = "RMA Order Date (d/m/Y)";
                        ?>
                        <div class="input-field col m4 s12 custom_margin_bottom_col">
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
                    </div><br>
                    
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
                                        RMA Product &nbsp;
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
                                    <th style="width: 200px;">Status</th>
                                    <th style="width: 150px;">Actions</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(isset($id) && $id>0){

                                    unset($received_ids);
                                    unset($rma_status);
                                    
                                    $sum_value      = 0;
                                    $sql_ee1		= "SELECT a.* FROM rma_order_detail a WHERE a.rma_id = '" . $id . "' ";  //echo $sql_ee1;
                                    $result_ee1		= $db->query($conn, $sql_ee1);
                                    $count_ee1  	= $db->counter($result_ee1);
                                    if($count_ee1 > 0){
                                        $row_ee1	= $db->fetch($result_ee1);
                                        foreach($row_ee1 as $data2){
                                            $received_ids[]	= $data2['received_id'];
                                            $rma_status[]	= $data2['rma_status'];
                                            
                                        }
                                    }
                                }?>
                                <input type="hidden" id="total_products_in_po" value="<?php if (!isset($received_ids) || (isset($received_ids) && sizeof($received_ids) == 0)) {
                                                                                            echo "1";
                                                                                        } else {
                                                                                            echo sizeof($received_ids);
                                                                                        } ?>">
                                <?php
                                $disabled = $readonly = "";
                                if((isset($order_status) && $order_status != 1 )){
                                    $disabled = "disabled='disabled'";
                                    $readonly = "readonly='readonly'";
                                }
                                for($i = 1; $i <= 50; $i++) {
                                    $field_name     = "received_ids";
                                    $field_id       = "productids_".$i;
                                    $field_label    = "Product";
                                    $style_btn = '';
                                    $style = ""; 
                                   
                                    if(!isset(${$field_name}[$i-1]) || (isset(${$field_name}[$i-1]) && ${$field_name}[$i-1] == "" || ${$field_name}[$i-1] == 0)){  
                                        if($i > 1){
                                            if(isset($received_ids) && sizeof($received_ids) >0){
                                                $style = 'style="display:none;"'; 
                                            }else{  
                                                $style = $i === 1 ? '' : 'style="display:none;"';
                                            }
                                        } 
                                    }
                                    else{
                                        if(isset($received_ids) && is_array($received_ids) && sizeof($received_ids)>1){ 
                                            $style = $i <= sizeof($received_ids) ? '' : 'style="display:none;"';
                                            $style_btn = $i <= sizeof($received_ids) ? 'style="display:none;"' : '';
                                        }
                                        else{
                                            $style = $i === 1 ? '' : 'style="display:none;"';
                                            $style_btn = $i === 1 ? 'style="display:none;"' : '';
                                        }
                                    }
                                
                                    $sql1       = " SELECT * FROM(
                                                        SELECT  a.id, a.po_detail_id, a.edit_lock, a.serial_no_barcode, a.base_product_id ,j.id as product_stock_id, 
                                                                a.sub_product_id, c.product_desc, d.category_name,  c.product_uniqueid, a.is_rma_processed, a.price
                                                        FROM purchase_order_detail_receive a
                                                        INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
                                                        INNER JOIN products c ON c.id = b.product_id
                                                        LEFT JOIN product_categories d ON d.id =c.product_category
                                                        LEFT JOIN inventory_status h ON h.id = a.inventory_status
                                                        LEFT JOIN warehouse_sub_locations i ON i.id = a.sub_location_id_after_diagnostic
                                                        INNER JOIN product_stock j ON j.receive_id = a.id
                                                        WHERE a.enabled = 1 
                                                        -- AND b.po_id = '" . $po_id . "'
                                                        AND a.inventory_status != '" . $tested_or_graded_status . "'  
                                                    ) AS t1
                                                    ORDER BY  is_rma_processed, base_product_id, serial_no_barcode DESC ";
                                    $result1    = $db->query($conn, $sql1);
                                    $count1     = $db->counter($result1);
                                    
                                    ?>
                                    <tr class="dynamic-row" id="row_<?=$i;?>" <?php echo $style; ?>>
                                        <td>
                                            <select <?php echo $disabled; echo $readonly; ?> name="<?=$field_name?>[]" id="<?=$field_id?>" class="select2-theme browser-default select2-hidden-accessible products <?=$field_name?>_<?=$i?>">
                                                <option value="">Select a product</option>
                                                <?php
                                                if ($count1 > 0) {
                                                    $row1    = $db->fetch($result1);
                                                    foreach ($row1 as $data2) { ?>
                                                        <option value="<?php echo $data2['id']; ?>" <?php if (isset($received_ids[$i-1]) && $received_ids[$i-1] == $data2['id']) { echo 'selected="selected"'; } ?>><?php echo $data2['product_desc']; ?> (<?php echo $data2['category_name']; ?>) - <?php echo $data2['product_uniqueid']; ?>, Serial#: - <?php echo $data2['serial_no_barcode']; ?>, Purchase Price: - <?php echo $data2['price']; ?> </option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <?php
                                            $field_name     = "rma_status";
                                            $field_id       = "rmastatus_" . $i;
                                            $field_label    = "Status";
                                            $sql_status     = "SELECT * FROM inventory_status WHERE enabled = 1 AND id IN(" . $rma_process_status . ") ORDER BY status_name ";
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
                                <!-- <tr>
                                    <td></td>
                                    <td class="text_align_right"><b>Total: </b></td>
                                    <td class="text_align_right">
                                        <b></b><span id="total_value"><?php //echo number_format($sum_value, 2); ?></b></span>
                                    </td>
                                    <td></td>
                                </tr> -->
                               
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
                                $field_name     = "rma_desc";
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
                                $field_name     = "rma_desc_public";
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
                            <div class="col m11 s12">
                                <h5 class="h5">Category Wise</h5>
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
                                                $headings = '	<th class="sno_width_100">S.No</th>
                                                                <th>Product Category</th>
                                                                <th>Qty</th> ';
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
                                                    <td style="text-align: center;"><?php echo $i + 1; ?></td>
                                                    <td><?php echo "" . $data['category_name'] . ""; ?></td>
                                                    <td><?php echo $data['total_qty']; ?></td>
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
                            <div class="col m11 s12">
                                <h5 class="h5">Product Wise</h5>
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
                                                $headings = '	<th class="sno_width_100">S.No</th>
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
                                            $row_cl = $db->fetch($result_cl);
                                            foreach ($row_cl as $data) {
                                                $total_qty      = $data['total_qty'];  ?>
                                                <tr>
                                                    <td style="text-align: center;"><?php echo $i + 1; ?></td>
                                                    <td><?php echo "" . $data['product_uniqueid']; ?><br></td>
                                                    <td>
                                                        <?php echo ucwords(strtolower($data['product_desc'])); ?>
                                                        <?php
                                                        if ($data['category_name'] != "") {
                                                            echo  " (" . $data['category_name'] . ")";
                                                        } ?>
                                                    </td>
                                                    <td><?php echo $total_qty; ?></td>
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
