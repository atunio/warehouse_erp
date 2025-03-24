<div name="agent_add_modal" id="agent_add_modal" role="dialog" aria-hidden="true" class="modal fade modal modal_95_perc" data-focus="false" style=" max-height: 70%;  height: 100%; padding: 1px 30px;">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Add Agent</h4>
        </div>
    </div>
    <div class="row">
        <div class="input-field col m6 s12">
            <?php
            $field_name     = "agent_name";
            $field_label     = "Agent Name";
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
        <div class="input-field col m6 s12">
            <?php
            $field_name     = "agent_phone_no";
            $field_label     = "Agent Phone";
            ?>
            <i class="material-icons prefix ">phone</i>
            <input type="text" id="<?= $field_name; ?>" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
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
        <div class="input-field col m12 s12">
            <?php
            $field_name     = "agent_address";
            $field_label     = "Address";
            ?>
            <i class="material-icons prefix">add_location</i>
            <input type="text" id="<?= $field_name; ?>" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                            echo ${$field_name};
                                                                                                        } ?>">
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
        <div class="input-field col m12 s12">
            <?php
            $field_name     = "note_about_agent";
            $field_label     = "Note About Agent";
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
            <a href="javascript:void(0)" name="add_agent_btn" id="add_agent_btn" class="btn modal-close cyan waves-effect waves-light right">
                Add<i class="material-icons right">send</i>
            </a>
        </div>
        <div class="input-field col m6 s12">
            <a href="javascript:void(0)" name="close_agent_btn" class="btn modal-close waves-red" />Close</a>
        </div>
    </div>
    <br><br>
</div>