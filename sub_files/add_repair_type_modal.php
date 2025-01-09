<div name="repair_type_add_modal" id="repair_type_add_modal" role="dialog" aria-hidden="true" class="modal fade modal" data-focus="false" style=" max-height: 70%;  height: 100%; padding: 1px 30px; ">
    <br><br><br>
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Add Repair Type</h4>
        </div>
    </div>
    <div class="row">
        <div class="input-field col m12 s12">
            <?php
            $field_name     = "repair_type_name";
            $field_label     = "Repair Type";
            ?>
            <i class="material-icons prefix ">description</i>
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
    </div>
    <div class="row">
        <div class="input-field col m6 s12">
            <a href="javascript:void(0)" name="add_repair_type_btn" id="add_repair_type_btn" class="btn modal-close cyan waves-effect waves-light right">
                Add<i class="material-icons right">send</i>
            </a>
        </div>
        <div class="input-field col m6 s12">
            <a href="javascript:void(0)" name="close_repair_type_btn" class="btn modal-close waves-red" />Close</a>
        </div>
    </div>
    <br><br>
</div>