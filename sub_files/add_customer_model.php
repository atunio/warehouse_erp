<div name="customer_add_modal" id="customer_add_modal" role="dialog" aria-hidden="true" class="modal fade modal" data-focus="false" style=" max-height: 70%;  height: 100%; padding: 1px 30px;">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Add Customer</h4>
        </div>
    </div>
    <div class="row">

        <div class="input-field col m4 s12">
            <?php
            $field_name     = "customer_name";
            $field_label     = "Customer Name";
            ?>
            <i class="material-icons prefix pt-2">person_outline</i>
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
        <div class="input-field col m4 s12">
            <?php
            $field_name     = "phone_primary";
            $field_label     = "Primary Phone No";
            ?>
            <i class="material-icons prefix pt-2">phone</i>
            <input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
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
        <div class="input-field col m4 s12">
            <?php
            $field_name     = "email_primary";
            $field_label     = "Email Primary";
            ?>
            <i class="material-icons prefix">mail</i>
            <input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
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
            $field_name     = "address_primary";
            $field_label     = "Address Primary";
            ?>
            <i class="material-icons prefix pt-1">add_location</i>
            <input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
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
        <div class="col m12 s12"><br></div>
    </div>
    <div class="row">
        <div class="input-field col m4 s12">
            <?php
            $field_name     = "address_primary_city";
            $field_label     = "Address Primary City";
            ?>
            <i class="material-icons prefix pt-2">add_location</i>
            <input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
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
        <div class="input-field col m4 s12">
            <?php
            $field_name     = "address_primary_state";
            $field_label     = "Address Primary State";
            ?>
            <i class="material-icons prefix pt-2">add_location</i>
            <input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
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
        <div class="input-field col m4 s12">
            <?php
            $field_name     = "address_primary_country";
            $field_label    = "Address Primary Country";
            $sql1           = "SELECT * FROM countries WHERE enabled = 1 ORDER BY country_name ";
            $result1        = $db->query($conn, $sql1);
            $count1         = $db->counter($result1); ?>
            <i class="material-icons prefix pt-2">add_location</i>
            <div class="select2div">
                <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                                                } ?>">
                    <option value="">Select</option>
                    <?php
                    if ($count1 > 0) {
                        $row1    = $db->fetch($result1);
                        foreach ($row1 as $data2) { ?>
                            <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['country_name']; ?></option>
                    <?php }
                    } ?>
                </select>
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
    </div> <br>
    <div class="row">
        <div class="input-field col m6 s12">
            <a href="javascript:void(0)" name="add_customer_btn" id="add_customer_btn" class="btn modal-close cyan waves-effect waves-light right">
                Add<i class="material-icons right">send</i>
            </a>
        </div>
        <div class="input-field col m6 s12">
            <a href="javascript:void(0)" name="close_customer_btn" class="btn modal-close waves-red" />Close</a>
        </div>
    </div>
    <br><br>
</div>