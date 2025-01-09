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