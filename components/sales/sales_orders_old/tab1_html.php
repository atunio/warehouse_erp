<div id="tab1_html" class="active" style="display: <?php if (isset($active_tab) && $active_tab == 'tab1') {
                                                        echo "block";
                                                    } else {
                                                        echo "none";
                                                    } ?>;">

    <?php
    if (isset($id) && isset($so_no) && (isset($cmd) && $cmd == "edit")) { ?>
        <div class="card-panel" style="padding-top: 5px; padding-bottom: 5px;">
            <div class="row">
                <div class="input-field col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Sale Order No: </b>" . $so_no; ?></span></h6>
                </div>
                <div class="input-field col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Order Date: </b>" . $order_date_disp; ?></span></h6>
                </div>
                <div class="input-field col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Customer Invoice No: </b>" . $customer_po_no; ?></span></h6>
                </div>
            </div>
        </div>
    <?php }
    if (!isset($cmd2) || (isset($cmd2) && $cmd2 != "edit")) { ?>
        <div class="card-panel">
            <div class="card-content">
                <?php
                if (isset($error['msg'])) { ?>
                    <div class="card-alert card red lighten-5">
                        <div class="card-content red-text">
                            <p><?php echo $error['msg']; ?></p>
                        </div>
                        <button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                <?php } else if (isset($msg['msg_success'])) { ?>
                    <div class="card-alert card green lighten-5">
                        <div class="card-content green-text">
                            <p><?php echo $msg['msg_success']; ?></p>
                        </div>
                        <button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                <?php } ?>
                <h4 class="card-title">Sales Order Master Info</h4><br>
                <?php
                if (!isset($cmd2)) {
                    $cmd2_2 = "add";
                } else {
                    $cmd2_2 = $cmd2;
                } ?>
                <form method="post" autocomplete="off" action="<?php echo "?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=profile&cmd=edit&active_tab=tab1&cmd=' . $cmd . '&id=' . $id); ?>">
                    <input type="hidden" name="is_Submit" value="Y" />
                    <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                        echo encrypt($_SESSION['csrf_session']);
                                                                    } ?>">
                    <input type="hidden" name="active_tab" value="tab1" />

                    <div class="row">
                        <div class="input-field col m4 s12">
                            <?php
                            $field_name     = "customer_id";
                            $field_label     = "Customer";
                            $sql1             = "SELECT * FROM customers WHERE enabled = 1 ORDER BY customer_name ";
                            $result1         = $db->query($conn, $sql1);
                            $count1         = $db->counter($result1);
                            ?>
                            <i class="material-icons prefix">question_answer</i>
                            <div class="select2div">
                                <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                                                                } ?>">
                                    <option value="">Select</option>
                                    <?php
                                    if ($count1 > 0) {
                                        $row1    = $db->fetch($result1);
                                        foreach ($row1 as $data2) { ?>
                                            <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['customer_name']; ?> - Phone: <?php echo $data2['phone_primary']; ?></option>
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
                            <a class="waves-effect waves-light btn modal-trigger mb-2 mr-1" href="#customer_add_modal">Add New Customer</a>
                        </div>
                        <div class="input-field col m3 s12">
                            <?php
                            $field_name     = "source_id";
                            $field_label     = "Sources";
                            $sql1             = "SELECT * FROM sources WHERE enabled = 1 ORDER BY source_name ";
                            $result1         = $db->query($conn, $sql1);
                            $count1         = $db->counter($result1);
                            ?>
                            <i class="material-icons prefix">question_answer</i>
                            <div class="select2div">
                                <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                                                                } ?>">
                                    <option value="">Select</option>
                                    <?php
                                    if ($count1 > 0) {
                                        $row1    = $db->fetch($result1);
                                        foreach ($row1 as $data2) {
                                            echo "$field_name == " . $data2['id']; ?>
                                            <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['source_name']; ?></option>
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
                        <?php
                        $field_name     = "order_date";
                        $field_label     = "Order Date (d/m/Y)";
                        ?>
                        <div class="input-field col m3 s12">
                            <i class="material-icons prefix">date_range</i>
                            <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                echo ${$field_name};
                                                                                                            } ?>" class=" datepicker validate <?php if (isset(${$field_name . "_valid"})) {
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
                        <div class="input-field col m3 s12">
                            <?php
                            $field_name     = "origin_id";
                            $field_label     = "Origins";
                            $sql1             = "SELECT * FROM origins WHERE enabled = 1 ORDER BY origin_name ";
                            $result1         = $db->query($conn, $sql1);
                            $count1         = $db->counter($result1);
                            ?>
                            <i class="material-icons prefix">question_answer</i>
                            <div class="select2div">
                                <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                                                                } ?>">
                                    <option value="">Select</option>
                                    <?php
                                    if ($count1 > 0) {
                                        $row1    = $db->fetch($result1);
                                        foreach ($row1 as $data2) { ?>
                                            <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['origin_name']; ?></option>
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
                        <?php
                        $field_name     = "estimated_ship_date";
                        $field_label     = "Expected Ship Date (d/m/Y)";
                        ?>
                        <div class="input-field col m3 s12">
                            <i class="material-icons prefix">date_range</i>
                            <input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                echo ${$field_name};
                                                                                                            } ?>" class=" datepicker validate <?php if (isset(${$field_name . "_valid"})) {
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
                        <?php
                        $field_name     = "customer_po_no";
                        $field_label     = "Customer Invoice #";
                        ?>
                        <div class="input-field col m3 s12">
                            <i class="material-icons prefix">question_answer</i>
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
                        <div class="input-field col m3 s12">
                            <?php
                            $field_name     = "fullfilment_id";
                            $field_label     = "Fullfilments";
                            $sql1             = "SELECT * FROM fullfilments WHERE enabled = 1 ORDER BY fullfilments_name ";
                            $result1         = $db->query($conn, $sql1);
                            $count1         = $db->counter($result1);
                            ?>
                            <i class="material-icons prefix">question_answer</i>
                            <div class="select2div">
                                <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                                                                } ?>">
                                    <option value="">Select</option>
                                    <?php
                                    if ($count1 > 0) {
                                        $row1    = $db->fetch($result1);
                                        foreach ($row1 as $data2) { ?>
                                            <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['fullfilments_name']; ?></option>
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
                    </div><br>
                    <div class="row">
                        <div class="input-field col m3 s12">
                            <?php
                            $field_name     = "terms_id";
                            $field_label     = "Terms";
                            $sql1             = "SELECT * FROM terms WHERE enabled = 1 ORDER BY term_name ";
                            $result1         = $db->query($conn, $sql1);
                            $count1         = $db->counter($result1);
                            ?>
                            <i class="material-icons prefix">question_answer</i>
                            <div class="select2div">
                                <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                                                                } ?>">
                                    <option value="">Select</option>
                                    <?php
                                    if ($count1 > 0) {
                                        $row1    = $db->fetch($result1);
                                        foreach ($row1 as $data2) { ?>
                                            <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['term_name']; ?></option>
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
                        <div class="input-field col m3 s12">
                            <?php
                            $field_name     = "requested_shipment_id";
                            $field_label     = "Requested Shipments";
                            $sql1             = "SELECT * FROM requested_shipments WHERE enabled = 1 ORDER BY requested_shipment_name ";
                            $result1         = $db->query($conn, $sql1);
                            $count1         = $db->counter($result1);
                            ?>
                            <i class="material-icons prefix">question_answer</i>
                            <div class="select2div">
                                <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                                                                } ?>">
                                    <option value="">Select</option>
                                    <?php
                                    if ($count1 > 0) {
                                        $row1    = $db->fetch($result1);
                                        foreach ($row1 as $data2) { ?>
                                            <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['requested_shipment_name']; ?></option>
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
                        <div class="input-field col m3 s12">
                            <?php
                            $field_name     = "batch_id";
                            $field_label     = "Batchs";
                            $sql1             = "SELECT * FROM batchs WHERE enabled = 1 ORDER BY batch_name ";
                            $result1         = $db->query($conn, $sql1);
                            $count1         = $db->counter($result1);
                            ?>
                            <i class="material-icons prefix">question_answer</i>
                            <div class="select2div">
                                <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                                    echo ${$field_name . "_valid"};
                                                                                                                                                                } ?>">
                                    <option value="">Select</option>
                                    <?php
                                    if ($count1 > 0) {
                                        $row1    = $db->fetch($result1);
                                        foreach ($row1 as $data2) { ?>
                                            <option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['batch_name']; ?></option>
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
                    </div><br>
                    <div class="row">
                        <div class="input-field col m6 s12">
                            <?php
                            $field_name     = "public_note";
                            $field_label     = "Public Note";
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
                        <div class="input-field col m6 s12">
                            <?php
                            $field_name     = "internal_note";
                            $field_label     = "Internal Note";
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
                    </div><br>
                    <div class="row">
                        <div class="input-field col m6 s12">
                            <?php if (($cmd == 'add' && access("add_perm") == 1)  || ($cmd == 'edit' && access("edit_perm") == 1)) { ?>
                                <button class="btn cyan waves-effect waves-light right" type="submit" name="action"><?php echo $button_val; ?>
                                    <i class="material-icons right">send</i>
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php }
    if (isset($cmd) && $cmd == 'edit') { ?>
        <div class="card-panel">
            <div class="row">
                <div class="col m4 s12">
                    <a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=import_so_details&id=" . $id) ?>" class="waves-effect waves-light  btn gradient-45deg-amber-amber box-shadow-none border-round mr-1 mb-1">Import Sale Order Details</a>
                </div>
            </div>
        </div>
        <div class="card-panel">
            <div class="card-content">
                <?php
                if (isset($error2['msg'])) { ?>
                    <div class="card-alert card red lighten-5">
                        <div class="card-content red-text">
                            <p><?php echo $error2['msg']; ?></p>
                        </div>
                        <button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                <?php } else if (isset($msg2['msg_success'])) { ?>
                    <div class="card-alert card green lighten-5">
                        <div class="card-content green-text">
                            <p><?php echo $msg2['msg_success']; ?></p>
                        </div>
                        <button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                <?php } ?>
                <h4 class="card-title"><?php echo $title_heading2; ?></h4><br>
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
                <form method="post" autocomplete="off" action="<?php echo "?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=profile&active_tab=tab1&cmd=' . $cmd . '&cmd2=' . $cmd2_2 . '&id=' . $id . '&detail_id=' . $detail_id1); ?>">
                    <input type="hidden" name="is_Submit2" value="Y" />
                    <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                        echo encrypt($_SESSION['csrf_session']);
                                                                    } ?>">
                    <div class="row">
                        <div class="input-field col m10 s12">
                            <?php
                            $field_name     = "product_stock_id";
                            $field_label    = "Product";

                            $sql1             = "   SELECT c.*, c1.category_name,b.serial_no, b.id As product_stock_id, b.price
                                                    FROM product_stock b 
                                                    INNER JOIN products c ON c.id = b.product_id
                                                    LEFT JOIN product_categories c1 ON c1.id = c.product_category
                                                    WHERE b.enabled = 1 
                                                    AND b.p_total_stock > 0
                                                    AND b.is_packed = 0 
                                                    GROUP BY b.serial_no
                                                    ORDER BY b.serial_no ";
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
                                            <option value="<?php echo $data2['product_stock_id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['product_stock_id']) { ?> selected="selected" <?php } ?>> <?php echo $data2['product_desc']; ?> (<?php echo $data2['category_name']; ?>) - <?php echo $data2['product_uniqueid']; ?>, Serial#: - <?php echo $data2['serial_no']; ?>, Purchase Price: - <?php echo $data2['price']; ?> </option>
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
                            <?php
                            $field_name     = "order_price";
                            $field_label     = "Sale Price";
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
                            <?php
                            $field_name     = "order_qty";
                            $field_label     = "Quantity";
                            ?>
                            <input id="<?= $field_name; ?>" type="hidden" name="<?= $field_name; ?>" readonly value="1">
                        </div>
                        <div class="input-field col m12 s12">
                            <?php
                            $field_name     = "product_so_desc";
                            $field_label     = "Product Desc";
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
                        <div class="input-field col m4 s12"></div>
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
                </form>
            </div>
        </div>
        <div class="card-panel">
            <div class="row">
                <div class="col m8 s12">
                    <h5>Sale Order Details</h5>
                </div>
                <div class="col m1 s12">
                    <a href="export/export_sales_order_details.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" class="mb-6 btn waves-effect waves-light gradient-45deg-green-teal">
                        <i class="material-icons medium icon-demo">vertical_align_bottom</i>
                    </a>
                </div>
                <div class="col m1 s12">
                    <a class="mb-6 btn waves-effect waves-light cyan" href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/sales_order_details_print.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" target="_blank">
                        <i class="material-icons medium icon-demo">print</i>
                    </a>
                </div>
            </div>
            <?php
            $sql_cl        = "	SELECT a.*, c1.product_desc, c.serial_no,d.category_name,b.order_status, 
                                        c1.product_uniqueid,  g.category_name as package_material_category_name, f.package_name, c.is_packed,
                                        h.sub_location_name, h.sub_location_type
                                FROM sales_order_detail a  
                                INNER JOIN sales_orders b ON b.id = a.sales_order_id
                                INNER JOIN product_stock c ON c.id = a.product_stock_id
                                INNER JOIN products c1 ON c1.id = c.product_id
                                LEFT JOIN product_categories d ON d.id = c1.product_category
                                LEFT JOIN packages f ON f.id = a.package_id
                                LEFT JOIN product_categories g ON g.id = f.product_category
                                LEFT JOIN warehouse_sub_locations h ON h.id = c.sub_location
                                WHERE a.sales_order_id = '" . $id . "' 
                                ORDER BY c.serial_no "; // echo $sql_cl;
            $result_cl    = $db->query($conn, $sql_cl);
            $count_cl    = $db->counter($result_cl);
            ?>
            <div class="section section-data-tables">

                <div class="row">
                    <div class="col s12">
                        <table id="page-length-option" class="display pagelength100 dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <?php
                                    $headings = '	<th class="sno_width_60">S.No</th>
                                                    <th>Product ID</th>
                                                    <th>Product Detail</th>
                                                    <th>Serial#</th> 
                                                    <th>Location</th> 
                                                    <th>Sale Price</th> 
                                                    <th>Action</th> ';
                                    echo $headings;
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                if ($count_cl > 0) {
                                    $row_cl = $db->fetch($result_cl);
                                    foreach ($row_cl as $data) {
                                        $detail_id1     = $data['id'];  ?>
                                        <tr>
                                            <td style="text-align: center;">
                                                <?php echo $i + 1; ?>
                                            </td>
                                            <td><?php echo "" . $data['product_uniqueid']; ?></td>
                                            <td>
                                                <?php echo ucwords(strtolower($data['product_desc'])); ?>
                                                <?php
                                                if ($data['category_name'] != "") {
                                                    echo  " (" . $data['category_name'] . ")";
                                                } ?>
                                                <br>
                                                <?php
                                                echo desc_length($data['product_so_desc']);  ?>
                                            </td>
                                            <td><?php echo "" . $data['serial_no']; ?></td>
                                            <td>
                                                <?php echo $data['sub_location_name'];
                                                if ($data['sub_location_type'] != "") {
                                                    echo " (" . ucwords(strtolower($data['sub_location_type'])) . ")";
                                                } ?>
                                            </td>
                                            <td><?php echo $data['order_price']; ?></td>
                                            <td>
                                                <?php
                                                if (access("edit_perm") == 1 && $data['is_packed'] == 0) { // 1 = In Process, 4 = Logistics, 12 = Arrived  
                                                ?>
                                                    <a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&active_tab=tab1&cmd=edit&cmd2=edit&id=" . $id . "&detail_id=" . $detail_id1) ?>">
                                                        <i class="material-icons dp48">edit</i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                <?php $i++;
                                    }
                                } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-panel">
            <div class="row">
                <div class="col m8 s12">
                    <h5>Summary</h5>
                </div>
                <div class="col m1 s12">
                    <a href="export/export_sales_order_summary.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" class="mb-6 btn waves-effect waves-light gradient-45deg-green-teal">
                        <i class="material-icons medium icon-demo">vertical_align_bottom</i>
                    </a>
                </div>
                <div class="col m1 s12">
                    <a class="mb-6 btn waves-effect waves-light cyan" href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/sales_order_summary_print.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" target="_blank">
                        <i class="material-icons medium icon-demo">print</i>
                    </a>
                </div>
            </div>
            <?php
            $sql_cl     = "	SELECT d.category_name, COUNT(a.id) AS total_qty
                            FROM sales_order_detail a  
                            INNER JOIN sales_orders b ON b.id = a.sales_order_id
                            INNER JOIN product_stock c ON c.id = a.product_stock_id
                            INNER JOIN products c1 ON c1.id = c.product_id
                            LEFT JOIN product_categories d ON d.id = c1.product_category
                            WHERE a.sales_order_id = '" . $id . "' 
                            AND a.enabled = 1
                            GROUP BY c1.product_category
                            ORDER BY c1.product_category  "; // echo $sql_cl;
            $result_cl  = $db->query($conn, $sql_cl);
            $count_cl   = $db->counter($result_cl);
            ?>
            <div class="section section-data-tables">
                <div class="row">
                    <div class="col s12">
                        <table id="page-length-option" class="display pagelength50 dataTable dtr-inline ">
                            <thead>
                                <tr>
                                    <?php
                                    $headings = '	<th class="sno_width_100">S.No</th>
                                                    <th>Product Category</th>
                                                     <th>Qty</th> ';
                                    echo $headings;
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                if ($count_cl > 0) {
                                    $row_cl = $db->fetch($result_cl);
                                    foreach ($row_cl as $data) { ?>
                                        <tr>
                                            <td style="text-align: center;"><?php echo $i + 1; ?></td>
                                            <td><?php echo "" . $data['category_name'] . ""; ?></td>
                                            <td><?php echo $data['total_qty']; ?></td>
                                        </tr>
                                <?php $i++;
                                    }
                                } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-panel">
            <div class="row">
                <div class="col m8 s12">
                    <h5>Summary Detail</h5>
                </div>
                <div class="col m1 s12">
                    <a href="export/export_sales_order_summary_detail.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" class="mb-6 btn waves-effect waves-light gradient-45deg-green-teal">
                        <i class="material-icons medium icon-demo">vertical_align_bottom</i>
                    </a>
                </div>
                <div class="col m1 s12">
                    <a class="mb-6 btn waves-effect waves-light cyan" href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/sales_order_summary_detail_print.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" target="_blank">
                        <i class="material-icons medium icon-demo">print</i>
                    </a>
                </div>
            </div>
            <?php
            $sql_cl     = "	SELECT c1.product_desc, d.category_name, c1.product_uniqueid, COUNT(a.id) AS total_qty
                            FROM sales_order_detail a  
                            INNER JOIN sales_orders b ON b.id = a.sales_order_id
                            INNER JOIN product_stock c ON c.id = a.product_stock_id
                            INNER JOIN products c1 ON c1.id = c.product_id
                            LEFT JOIN product_categories d ON d.id = c1.product_category
                            LEFT JOIN packages f ON f.id = a.package_id
                            LEFT JOIN product_categories g ON g.id = f.product_category
                            WHERE a.sales_order_id = '" . $id . "' 
                            AND a.enabled = 1
                            GROUP BY c.product_id
                            ORDER BY c.product_id  "; // echo $sql_cl;
            $result_cl  = $db->query($conn, $sql_cl);
            $count_cl   = $db->counter($result_cl);
            ?>
            <div class="section section-data-tables">

                <div class="row">
                    <div class="col s12">
                        <table id="page-length-option" class="display pagelength50_2 dataTable dtr-inline ">
                            <thead>
                                <tr>
                                    <?php
                                    $headings = '	<th class="sno_width_100">S.No</th>
                                                    <th>Product ID</th>
                                                    <th>Detail</th>
                                                    <th>Qty</th> ';
                                    echo $headings;
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                if ($count_cl > 0) {
                                    $row_cl = $db->fetch($result_cl);
                                    foreach ($row_cl as $data) {
                                        $total_qty      = $data['total_qty'];  ?>
                                        <tr>
                                            <td style="text-align: center;"><?php echo $i + 1; ?></td>
                                            <td><?php echo "" . $data['product_uniqueid']; ?><br></td>
                                            <td>
                                                <?php echo ucwords(strtolower($data['product_desc'])); ?>
                                                <?php
                                                if ($data['category_name'] != "") {
                                                    echo  " (" . $data['category_name'] . ")";
                                                } ?>
                                            </td>
                                            <td><?php echo $total_qty; ?></td>
                                        </tr>
                                <?php $i++;
                                    }
                                } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <?php } ?>

</div>