<div name="store_add_modal" id="store_add_modal" role="dialog" aria-hidden="true" class="modal fade modal" data-focus="false" style=" max-height: 70%;  height: 100%; padding: 1px 30px;">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Add Store</h4>
        </div>
    </div>
    <div class="row">
        <div class="input-field col m8 s4">
            <?php
            $field_name     = "store_name";
            $field_label     = "Store Name";
            ?>
            <i class="material-icons prefix ">person_outline</i>
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
        <div class="input-field col m2 s12">
            <a href="javascript:void(0)" name="add_store_btn" id="add_store_btn" class="btn modal-close cyan waves-effect waves-light right">
                Add<i class="material-icons right">send</i>
            </a>
        </div>
        <div class="input-field col m2 s12">
            <a href="javascript:void(0)" name="close_store_btn" class="btn modal-close waves-red" />Close</a>
        </div>
    </div>
    <br><br>
</div>