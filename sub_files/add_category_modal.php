<div name="category_add_modal" id="category_add_modal" role="dialog" aria-hidden="true" class="modal fade modal modal_95_perc" data-focus="false" style="padding: 1px 30px;">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Add Category</h4>
        </div>
    </div>
    <div class="row">
        <div class="input-field col m12 s12">
            <input type="hidden" name="module_id" id="module_id" value="<?= $module_id; ?>">
            <?php
            $field_name     = "category_name";
            $field_label    = "Category";
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
    </div>  
    <div class="row">
        <div class="input-field col m6 s12">
            <a href="javascript:void(0)" name="add_category_btn" id="add_category_btn" class="btn modal-close cyan waves-effect waves-light right">
                Add<i class="material-icons right">send</i>
            </a>
        </div>
        <div class="input-field col m6 s12">
            <a href="javascript:void(0)" name="close_category_btn" class="btn modal-close waves-red" />Close</a>
        </div>
    </div>
</div>