<div id="tab1_html" class="active" style="display: <?php if (isset($active_tab) && $active_tab == 'tab1') {
                                                        echo "block";
                                                    } else {
                                                        echo "none";
                                                    } ?>;">
    <?php
    if (isset($po_no) && isset($id)) { ?>
        <div class="card-panel" style="padding-top: 5px; padding-bottom: 5px;">
            <div class="row">
                <div class="col s10 m12 l8">
                    <h5 class="breadcrumbs mt-0 mb-0"><span>Purchase Order</span></h5>
                </div>
            </div>
            <div class="row">
                <div class="input-field col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Purchase Order No: </b>" . $po_no; ?></span></h6>
                </div>
                <div class="input-field col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Vender Invoice No: </b>" . $vender_invoice_no; ?></span></h6>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <?php
        if (!isset($cmd2) || (isset($cmd2) && $cmd2 != "edit")) { ?>
            <div class="col s12 m12 l12">
                <div id="Form-advance" class="card card card-default scrollspy">
                    <div class="card-content">
                        <h4 class="card-title">PO Master Info</h4><br>
                        <form method="post" autocomplete="off" action="<?php echo "?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=profile&cmd=edit&active_tab=tab1&cmd=' . $cmd . '&id=' . $id); ?>">

                            <input type="hidden" name="is_Submit" value="Y" />
                            <div class="row">
                                <?php
                                $field_name     = "po_date";
                                $field_label     = "Order Date (d/m/Y)";
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
                                        <span class="color-red">* <?php
                                                                    if (isset($error[$field_name])) {
                                                                        echo $error[$field_name];
                                                                    } ?>
                                        </span>
                                    </label>
                                </div>
                                <?php
                                $field_name     = "estimated_receive_date";
                                $field_label     = "Expected Arrival Date (d/m/Y)";
                                ?>
                                <div class="input-field col m3 s12">
                                    <i class="material-icons prefix">date_range</i>
                                    <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                        echo ${$field_name};
                                                                                                                    } ?>" class=" datepicker validate <?php if (isset(${$field_name . "_valid"})) {
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
                                $field_name     = "vender_invoice_no";
                                $field_label     = "Vendor Invoice #";
                                ?>
                                <div class="input-field col m2 s12">
                                    <i class="material-icons prefix">question_answer</i>
                                    <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                        echo ${$field_name};
                                                                                                                    } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
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

                                <?php /*?>
                                <div class="row">
                                    <div class="input-field col m6 s12">
                                        <?php
                                        $field_name 	= "product_id";
                                        $field_label	= "Product";
                                        $sql1 			= " SELECT a.*, b.category_name
                                                            FROM products a
                                                            INNER JOIN product_categories b ON b.id = a.product_category
                                                            WHERE a.enabled = 1 
                                                            ORDER BY a.product_desc ";
                                        $result1		= $db->query($conn, $sql1);
                                        $count1			= $db->counter($result1);
                                        ?>
                                        <i class="material-icons prefix">question_answer</i>
                                        <div class="select2div">
                                            <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                                echo ${$field_name . "_valid"};
                                                                                                                                                                            } ?>">
                                                <option value="">Select</option>
                                                <?php
                                                if ($count1 > 0) {
                                                    $row1	= $db->fetch($result1);
                                                    foreach ($row1 as $data2) { ?>
                                                        <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['product_desc']; ?> (<?php echo $data2['category_name']; ?>) - <?php echo $data2['product_uniqueid']; ?></option>
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
                                    <div class="input-field col m2 s12">
                                        <a class="waves-effect waves-light btn modal-trigger mb-2 mr-1" href="#vender_add_modal">Add New Product</a>
                                    </div>
                                </div>
                                <?php */ ?>
                                <div class="input-field col m3 s12">
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
                                <div class="input-field col m2 s12">
                                    <a class="waves-effect waves-light btn modal-trigger mb-2 mr-1" href="#vender_add_modal">Add New Vendor</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col m3 s12"> </div>
                                <div class="input-field col m3 s12">
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
                                <div class="input-field col m3 s12">
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
                                <div class="input-field col m3 s12">
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
                                <div class="input-field col m12 s12">
                                    <?php
                                    $field_name     = "po_desc";
                                    $field_label     = "Description";
                                    ?>
                                    <i class="material-icons prefix">description</i>
                                    <textarea id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="materialize-textarea validate "><?php if (isset(${$field_name})) {
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
                            <div class="row">
                                <div class="input-field col m6 s12">
                                    <?php if (($cmd == 'add' && access("add_perm") == 1)  || ($cmd == 'edit' && access("edit_perm") == 1)) { ?>
                                        <button class="btn cyan waves-effect waves-light right" type="submit" name="action"><?php echo $button_val; ?>
                                            <i class="material-icons right">send</i>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php }
        if (isset($cmd) && $cmd == 'edit') { ?>
            <div class="col s12 m12 l12">
                <div class="card-panel">
                    <div class="row">
                        <div class="col m4 s12">
                            <a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=import_po_details&id=" . $id) ?>" class="btn waves-effect waves-light border-round gradient-45deg-amber-amber col m12 s12">Import Purchase Order Details</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 l12">
                <div id="Form-advance2" class="card card card-default scrollspy">
                    <div class="card-content">
                        <h4 class="card-title"><?php echo $title_heading2; ?></h4><br>
                        <?php
                        if (!isset($cmd2)) {
                            $cmd2_2 = "add";
                        } else {
                            $cmd2_2 = $cmd2;
                        }
                        if (!isset($detail_id)) {
                            $detail_id1 = 0;
                        } else {
                            $detail_id1 = $detail_id;
                        } ?>
                        <form method="post" autocomplete="off" action="<?php echo "?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=profile&active_tab=tab1&cmd=' . $cmd . '&cmd2=' . $cmd2_2 . '&id=' . $id . '&detail_id=' . $detail_id1); ?>">

                            <input type="hidden" name="is_Submit2" value="Y" />
                            <div class="row">
                                <div class="input-field col m6 s12">
                                    <?php
                                    $field_name     = "product_id";
                                    $field_label    = "Product";

                                    $sql1             = " SELECT a.*, b.category_name
                                                            FROM products a
                                                            LEFT JOIN product_categories b ON b.id = a.product_category
                                                            WHERE a.enabled = 1 
                                                            ORDER BY a.product_desc";
                                    $result1         = $db->query($conn, $sql1);
                                    $count1         = $db->counter($result1);
                                    ?>
                                    <i class="material-icons prefix">add_shopping_cart</i>
                                    <div class="select2div">
                                        <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                            echo ${$field_name . "_valid"};
                                                                                                                                                                        } ?>">
                                            <option value="">Select</option>
                                            <?php
                                            if ($count1 > 0) {
                                                $row1    = $db->fetch($result1);
                                                foreach ($row1 as $data2) { ?>
                                                    <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['product_desc']; ?> (<?php echo $data2['category_name']; ?>) - <?php echo $data2['product_uniqueid']; ?></option>
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
                                <div class="input-field col m2 s12">
                                    <a class="waves-effect waves-light btn modal-trigger mb-2 mr-1" href="#product_add_modal">Add New Product</a>
                                </div>
                                <div class="input-field col m2 s12">
                                    <?php
                                    $field_name     = "order_qty";
                                    $field_label     = "Quantity";
                                    ?>
                                    <i class="material-icons prefix">description</i>
                                    <input id="<?= $field_name; ?>" type="number" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                                    echo ${$field_name};
                                                                                                                                } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
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
                                <div class="input-field col m2 s12">
                                    <?php
                                    $field_name     = "order_price";
                                    $field_label     = "Unit Price";
                                    ?>
                                    <i class="material-icons prefix">attach_money</i>
                                    <input id="<?= $field_name; ?>" type="text" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                                    echo ${$field_name};
                                                                                                                                } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
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
                            </div>
                            <div class="row">
                                <div class="input-field col m3 s12">
                                    <?php
                                    $field_name     = "product_condition";
                                    $field_label     = "Product Condition";
                                    ?>
                                    <i class="material-icons prefix">description</i>
                                    <input id="<?= $field_name; ?>" type="text" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                                    echo ${$field_name};
                                                                                                                                } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
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


                                <div class="input-field col m3 s12">
                                    <?php
                                    $field_name     = "is_tested";
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
                                <div class="input-field col m3 s12">
                                    <?php
                                    $field_name     = "is_wiped";
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
                                <div class="input-field col m3 s12">
                                    <?php
                                    $field_name     = "is_imaged";
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
                            <div class="row">
                                <br>
                                <div class="input-field col m8 s12">
                                    <?php
                                    $field_name     = "product_po_desc";
                                    $field_label     = "Description";
                                    ?>
                                    <i class="material-icons prefix">description</i>
                                    <textarea id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="materialize-textarea validate "><?php if (isset(${$field_name})) {
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
                                    $field_name     = "warranty_period_in_days";
                                    $field_label     = "Warranty Days from Date of Arrival";
                                    ?>
                                    <i class="material-icons prefix">description</i>
                                    <input id="<?= $field_name; ?>" type="number" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                                    echo ${$field_name};
                                                                                                                                } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
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
                            </div>
                            <div class="row">
                                <div class="input-field col m4 s12"></div>
                            </div>
                            <div class="row">
                                <div class="input-field col m4 s12">
                                    <?php
                                    $field_name     = "package_id";
                                    $field_label    = "Packaging Material / Part";
                                    ?>
                                    <i class="material-icons prefix">subtitles</i>
                                    <div class="select2div">
                                        <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {  //a.product_sku, a.case_pack,a.pack_desc, b.category_name, c.total_stock
                                                                                                                                                                            echo ${$field_name . "_valid"};
                                                                                                                                                                        } ?>">
                                            <?php
                                            if (isset($product_id) && $product_id > 0) {
                                                $sql1             = " SELECT  a.id, b.category_name, a.package_name
                                                                        FROM packages a
                                                                        INNER JOIN product_categories b ON b.id = a.product_category
                                                                        WHERE 1=1
                                                                        AND a.enabled = 1
                                                                        AND FIND_IN_SET(  " . $product_id . " , product_ids) > 0
                                                                        ORDER BY b.category_name, a.package_name ";
                                                $result1         = $db->query($conn, $sql1);
                                                $count1         = $db->counter($result1);
                                                if ($count1 > 0) {
                                                    $row1    = $db->fetch($result1); ?>
                                                    <option value="">Select</option>
                                                    <?php
                                                    foreach ($row1 as $data2) { ?>
                                                        <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>>
                                                            <?php echo $data2['package_name']; ?> (<?php echo $data2['category_name']; ?>)
                                                        </option>
                                                    <?php }
                                                } else { ?>
                                                    <option value="">No <?= $field_label; ?> Available</option>
                                                <?php }
                                            } else { ?>
                                                <option value="">Select</option>
                                            <?php } ?>
                                        </select>
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
                                    $field_name = "product_id_for_package_material"; ?>
                                    <input type="hidden" name="<?= $field_name ?>" id="<?= $field_name ?>" value="" />
                                </div>
                                <div class="input-field col m3 s12 package_material_qty" style="<?php if (!isset($package_id) || (isset($package_id) && ($package_id == "" || $package_id == "0"))) {
                                                                                                    echo "display:none;";
                                                                                                } ?>">
                                    <?php
                                    $field_name     = "package_material_qty";
                                    $field_label     = "Package Material Qty";
                                    ?>
                                    <i class="material-icons prefix">description</i>
                                    <input id="<?= $field_name; ?>" type="number" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                        echo ${$field_name};
                                                                                                                    } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
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
                            </div>
                            <div class="row">
                                <div class="input-field col m6 s12">
                                    <?php if (access("add_perm") == 1 || access("edit_perm") == 1) { ?>
                                        <button class="btn cyan waves-effect waves-light right" type="submit" name="action"><?php echo $button_val2; ?>
                                            <i class="material-icons right">send</i>
                                        </button>
                                    <?php } ?>
                                </div>
                                <div class="input-field col m2 s12">
                                    <?php if (access("add_perm") == 1 && isset($cmd2) && $cmd2 == 'edit') { ?>
                                        <a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=edit&cmd2=add&active_tab=tab1&id=" . $id) ?>">Add New Product</a>
                                    <?php } ?>
                                </div>
                                <div class="input-field col m2 s12">
                                    <?php if (isset($cmd2) && $cmd2 == 'edit') { ?>
                                        <a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=edit&active_tab=tab1&id=" . $id) ?>">Order Master Info</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php //include('sub_files/right_sidebar.php'); 
                    ?>
                </div>
            </div>

            <div class="col s12">
                <div class="container">
                    <div class="section section-data-tables">
                        <!-- Page Length Options -->
                        <h4 class="card-title">Purchase Order Details</h4>
                        <div class="row">
                            <div class="col s12">
                                <div class="card-panel">
                                    <div class="card-content">
                                        <?php
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
                                        <?php
                                        $sql_cl        = "	SELECT a.*, c.product_desc, d.category_name, c.product_uniqueid, f.product_sku, g.category_name as package_material_category_name, f.package_name
                                                            FROM purchase_order_detail a 
                                                            INNER JOIN purchase_orders b ON b.id = a.po_id
                                                            INNER JOIN products c ON c.id = a.product_id
                                                            LEFT JOIN product_categories d ON d.id = c.product_category
                                                            LEFT JOIN packages f ON f.id = a.package_id
                                                            LEFT JOIN product_categories g ON g.id = f.product_category
                                                            WHERE 1=1 
                                                            AND a.po_id = '" . $id . "' 
                                                            ORDER BY c.product_uniqueid, a.product_condition ";
                                        //echo $sql_cl;
                                        $result_cl    = $db->query($conn, $sql_cl);
                                        $count_cl    = $db->counter($result_cl);
                                        ?>
                                        <div class="row">
                                            <div class="col s12">
                                                <table id="page-length-option" class="display">
                                                    <thead>
                                                        <tr>
                                                            <?php
                                                            $headings = '<th class="sno_width_60">S.No</th>
                                                                            <th>Product ID / Details</th>
                                                                            <th>Package Material</th>
                                                                            <th>Condition</th>
                                                                            <th>Order Qty</th>
                                                                            <th>Unit Price</th>
                                                                            <th>Action</th>';
                                                            echo $headings; ?>
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
                                                                        <?php echo $data['product_uniqueid']; ?><br>
                                                                        <?php echo ucwords(strtolower($data['product_desc'])); ?>
                                                                        <?php
                                                                        if ($data['category_name'] != "") {
                                                                            echo  " (" . $data['category_name'] . ")";
                                                                        } ?>
                                                                        <br>
                                                                        <?php
                                                                        echo desc_length($data['product_po_desc']);
                                                                        echo  "<br>Warranty Days: " . $data['warranty_period_in_days'];
                                                                        ?>

                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        echo  $data['package_name'];
                                                                        if ($data['package_material_category_name'] != "") {
                                                                            echo  " (" . $data['package_material_category_name'] . ")";
                                                                        } ?>
                                                                        <br>
                                                                        <?php
                                                                        if ($data['package_material_qty'] > "0") {
                                                                            echo  "<b>Quantity: </b>" . $data['package_material_qty'] . "";
                                                                        } ?>
                                                                    </td>
                                                                    <td><?php echo $data['product_condition']; ?></td>
                                                                    <td><?php echo $order_qty; ?></td>
                                                                    <td><?php echo $order_price; ?></td>

                                                                    <td class="text-align-center">
                                                                        <?php
                                                                        if ($data['enabled'] == 1 && access("view_perm") == 1) { ?>
                                                                            <a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=edit&cmd2=edit&active_tab=tab1&id=" . $id . "&detail_id=" . $detail_id1) ?>">
                                                                                <i class="material-icons dp48">edit</i>
                                                                            </a> &nbsp;&nbsp;
                                                                        <?php }
                                                                        if ($data['enabled'] == 0 && access("edit_perm") == 1) { ?>
                                                                            <a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&cmd2=add&cmd3=enabled&id=" . $id . "&detail_id=" . $detail_id1) ?>">
                                                                                <i class="material-icons dp48">add</i>
                                                                            </a> &nbsp;&nbsp;
                                                                        <?php } else if ($data['enabled'] == 1 && access("delete_perm") == 1) { ?>
                                                                            <a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&cmd2=add&cmd3=disabled&id=" . $id . "&detail_id=" . $detail_id1) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
                                                                                <i class="material-icons dp48">delete</i>
                                                                            </a> &nbsp;&nbsp;
                                                                        <?php } ?>
                                                                    </td>
                                                                </tr>
                                                        <?php $i++;
                                                            }
                                                        } ?>
                                                    <tfoot>
                                                        <tr><?php echo $headings; ?></tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Multi Select -->
                    </div><!-- START RIGHT SIDEBAR NAV -->

                    <?php include('sub_files/right_sidebar.php'); ?>
                </div>

                <div class="content-overlay"></div>
            </div>

        <?php } ?>
    </div>

</div>