<div name="vender_add_modal" id="vender_add_modal" role="dialog" aria-hidden="true" class="modal fade modal modal_95_perc" data-focus="false" style=" max-height: 70%;  height: 100%; padding: 1px 30px;">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Add Vendor</h4>
        </div>
    </div>
    <div class="row">
        <div class="input-field col m5 s12">
            <?php
            $field_name     = "vender_name";
            $field_label     = "Vendor Name";
            ?>
            <i class="material-icons prefix ">person_outline</i>
            <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                echo ${$field_name};
                                                                                            } else if (isset($test_on_local) && $test_on_local == 1) {
                                                                                                echo "" . date('YmdHis');
                                                                                            }  ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
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
            $field_name     = "phone_no";
            $field_label     = "Vendor Phone";
            ?>
            <i class="material-icons prefix ">phone</i>
            <input type="text" id="<?= $field_name; ?>" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                            echo ${$field_name};
                                                                                                        } else if (isset($test_on_local) && $test_on_local == 1) {
                                                                                                            echo "" . date('YmdHis');
                                                                                                        }  ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
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

        <div class="input-field col m2 s12">
            <?php
            $field_name     = "vender_type_id";
            $field_label    = "Vendor Type";
            $sql1           = "SELECT * FROM vender_types WHERE enabled = 1 ORDER BY type_name ";
            $result1        = $db->query($conn, $sql1);
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
                        <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['type_name']; ?></option>
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
        <div class="input-field col m3 s12">
            <?php
            $field_name         = "purchasing_agent_id_modal";
            $field_label        = "Purchasing Agent";
            $sql1               = "SELECT * FROM purchasing_agents WHERE enabled = 1 ORDER BY agent_name ";
            $result1            = $db->query($conn, $sql1);
            $count1             = $db->counter($result1);
            ?>
            <i class="material-icons prefix">question_answer</i>
            <div class="select2div">
                <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                                                } ?>">
                    <option value="0">Select</option>
                    <?php
                    if ($count1 > 0) {
                        $row1    = $db->fetch($result1);
                        foreach ($row1 as $data2) { ?>
                            <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['agent_name']; ?> - Phone: <?php echo $data2['phone_no']; ?></option>
                    <?php }
                    } ?>
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
        </div>
    </div><br>
    <div class="row">
        <div class="input-field col m5 s12">
            <?php
            $field_name     = "address";
            $field_label     = "Address";
            ?>
            <i class="material-icons prefix">add_location</i>
            <input type="text" id="<?= $field_name; ?>" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                            echo ${$field_name};
                                                                                                        } else if (isset($test_on_local) && $test_on_local == 1) {
                                                                                                            echo "" . date('YmdHis');
                                                                                                        }  ?>">
            <label for="<?= $field_name; ?>">
                <?= $field_label; ?>
                <span class="color-red"> <?php
                                            if (isset($error[$field_name])) {
                                                echo $error[$field_name];
                                            } ?>
                </span>
            </label>
        </div>
        <div class="input-field col m2 s12">
            <?php
            $field_name     = "warranty_period_in_days";
            $field_label     = "Warranty in Days";
            ?>
            <i class="material-icons prefix pt-2">question_answer</i>
            <input type="number" id="<?= $field_name; ?>" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                            echo ${$field_name};
                                                                                                        } else if (isset($test_on_local) && $test_on_local == 1) {
                                                                                                            echo "30";
                                                                                                        }  ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
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
        <div class="input-field col m5 s12">
            <?php
            $field_name     = "note_about_vender";
            $field_label     = "Note About Vendor";
            ?>
            <i class="material-icons prefix">description</i>
            <textarea id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="materialize-textarea validate "><?php if (isset(${$field_name})) {
                                                                                                                        echo ${$field_name};
                                                                                                                    } else if (isset($test_on_local) && $test_on_local == 1) {
                                                                                                                        echo "Desc " . date('YmdHis');
                                                                                                                    }  ?></textarea>
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
            <a href="javascript:void(0)" name="add_vender_btn" id="add_vender_btn" class="btn modal-close cyan waves-effect waves-light right">
                Add<i class="material-icons right">send</i>
            </a>
        </div>
        <div class="input-field col m6 s12">
            <a href="javascript:void(0)" name="close_vender_btn" class="btn modal-close waves-red" />Close</a>
        </div>
    </div>
    <br><br>
</div>