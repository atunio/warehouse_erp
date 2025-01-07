<div name="product_add_modal" id="product_add_modal" role="dialog" aria-hidden="true" class="modal fade modal" data-focus="false" style=" max-height: 70%;  height: 100%; padding: 1px 30px;">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Add Product</h4>
        </div>
    </div>
    <div class="row">
        <div class="input-field col m6 s12">
            <?php
            $field_name     = "product_uniqueid";
            $field_label     = "Product ID";
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
                                            if (isset($error[$field_name])) {
                                                echo $error[$field_name];
                                            } ?>
                </span>
            </label>
        </div>

        <div class="input-field col m6 s12">
            <?php
            $field_name     = "product_category";
            $field_label     = "Category";
            $sql1             = "SELECT * FROM product_categories WHERE enabled = 1 AND category_type = 'Device' ORDER BY category_name ";
            $result1         = $db->query($conn, $sql1);
            $count1         = $db->counter($result1);
            ?>
            <i class="material-icons prefix">question_answer</i>
            <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" validate  <?php if (isset(${$field_name . "_valid"})) {
                                                                                                echo ${$field_name . "_valid"};
                                                                                            } ?>">
                <option value="">Select</option>
                <?php
                if ($count1 > 0) {
                    $row1    = $db->fetch($result1);
                    foreach ($row1 as $data2) { ?>
                        <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['category_name']; ?></option>
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
        <div class="input-field col m12 s12">
            <?php
            $field_name     = "product_desc";
            $field_label     = "Item Descripton";
            ?>
            <i class="material-icons prefix">description</i>
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
    </div><br>
    <div class="row">
        <div class="input-field col m12 s12">
            <?php
            $field_name     = "detail_desc";
            $field_id         = "detail_desc2";
            $field_label     = "Detail Description";
            ?>
            <i class="material-icons prefix">description</i>
            <textarea id="<?= $field_id; ?>" name="<?= $field_name; ?>" class="materialize-textarea validate "></textarea>
            <label for="<?= $field_id; ?>">
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
            <a href="#" name="add_product_btn" id="add_product_btn" class="btn modal-close cyan waves-effect waves-light right">
                Add<i class="material-icons right">send</i>
            </a>
        </div>
        <div class="input-field col m6 s12">
            <a href="#" name="close_product_btn" class="btn modal-close waves-red" />Close</a>
        </div>
    </div>
    <br><br>
</div>