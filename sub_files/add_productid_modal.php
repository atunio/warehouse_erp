<div name="productid_add_modal" id="productid_add_modal" role="dialog" aria-hidden="true" class="modal fade modal modal_95_perc" data-focus="false" style="padding: 1px 30px;">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Add Product ID</h4>
        </div>
    </div>
    <div class="row">
        <div class="input-field col m12 s12">
            <input type="hidden" name="module_id" id="module_id" value="<?= $module_id; ?>">
            <?php
            $field_name     = "product_id";
            $field_id       = "product_id_modal";
            $field_label    = "Product ID";
            ?>
            <i class="material-icons prefix">description</i>
            <input id="<?= $field_id; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                echo ${$field_name};
                                                                                            } ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                    } ?>">
            <label for="<?= $field_id; ?>">
                <?= $field_label; ?>
                <span class="color-red"> * <?php
                                            if (isset($error[$field_name])) {
                                                echo $error[$field_name];
                                            } ?>
                </span>
            </label>
        </div> 
    </div>  
    <div class="row">
        <div class="input-field col m6 s12">
            <a href="javascript:void(0)" name="add_productid_btn" id="add_productid_btn" class="btn modal-close cyan waves-effect waves-light right">
                Add<i class="material-icons right">send</i>
            </a>
        </div>
        <div class="input-field col m6 s12">
            <a href="javascript:void(0)" name="close_productid_btn" class="btn modal-close waves-red" />Close</a>
        </div>
    </div>
</div>