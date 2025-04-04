<div name="product_add_modal" id="product_add_modal" role="dialog" aria-hidden="true" class="modal fade modal modal_95_perc" data-focus="false" style=" max-height: 70%;  height: 100%; padding: 1px 30px;">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Add Product</h4>
        </div>
    </div>
    <div class="row">
        <input type="hidden" value="0" name="selected_product_id" id="selected_product_id" />
        <div class="input-field col m6 s12">
            <?php
            $field_name     = "product_uniqueid_modal";
            $field_label    = "Product ID";
            $sql1           = "SELECT * FROM product_ids WHERE enabled = 1 ORDER BY product_id ";
            $result1        = $db->query($conn, $sql1);
            $count1         = $db->counter($result1);
            ?>
            <i class="material-icons prefix">question_answer</i>
            <div class="select2div">
                <select2 id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" validate  <?php if (isset(${$field_name . "_valid"})) {
                                                                                                    echo ${$field_name . "_valid"};
                                                                                                } ?>">
                    <option value="">Select</option>
                    <?php
                    if ($count1 > 0) {
                        $row1    = $db->fetch($result1);
                        foreach ($row1 as $data2) { ?>
                            <option value="<?php echo $data2['product_id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['product_id']) { ?> selected="selected" <?php } ?>><?php echo $data2['product_id']; ?></option>
                    <?php }
                    } ?>
                </select2>
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
        <div class="input-field col m6 s12">
            <?php
            $field_name     = "product_category_modal";
            $field_label    = "Category";
            $sql1           = "SELECT * FROM product_categories WHERE enabled = 1 AND category_type = 'Device' ORDER BY category_name ";
            $result1        = $db->query($conn, $sql1);
            $count1         = $db->counter($result1);
            ?>
            <i class="material-icons prefix">question_answer</i>
            <div class="select2div">
                <select2 id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="category_id_modal validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                } ?>">
                    <option value="">Select</option>
                    <?php
                    if ($count1 > 0) {
                        $row1    = $db->fetch($result1);
                        foreach ($row1 as $data2) { ?>
                            <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>> <?php echo $data2['category_name']; ?></option>
                    <?php }
                    } ?>
                </select2>
                <label for="<?= $field_name; ?>">
                    <?= $field_label; ?>
                    <span class="color-red"> * <?php
                                                if (isset($error[$field_name])) {
                                                    echo $error[$field_name];
                                                } ?>
                    </span>
                </label>
            </div>
        </div>
        <div class="input-field col m6 s12">
            <?php
            $field_name     = "product_desc";
            $field_label     = "Product Descripton";
            ?>
            <i class="material-icons prefix">description</i>
            <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                echo ${$field_name};
                                                                                            } else if (isset($test_on_local) && $test_on_local == 1) {
                                                                                                echo "Desc " . date('YmdHis');
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
        <div class="input-field col m6 s12">
            <?php
            $field_name     = "product_model_no";
            $field_label     = "Model#";
            ?>
            <i class="material-icons prefix">description</i>
            <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                echo ${$field_name};
                                                                                            } else if (isset($test_on_local) && $test_on_local == 1) {
                                                                                                echo "MD" . date('YmdHis');
                                                                                            } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
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
    <div class="row">
        <div class="input-field col m6 s12">
            <a href="javascript:void(0)" name="add_product_btn" id="add_product_btn" class="btn modal-close cyan waves-effect waves-light right">
                Add<i class="material-icons right">send</i>
            </a>
        </div>
        <div class="input-field col m6 s12">
            <a href="javascript:void(0)" name="close_product_btn" class="btn modal-close waves-red" />Close</a>
        </div>
    </div>
    <br><br>
</div>