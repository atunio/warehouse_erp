<div id="tab1_html" class="active" style="display: <?php if (isset($active_tab) && $active_tab == 'tab1') {
                                                        echo "block";
                                                    } else {
                                                        echo "none";
                                                    } ?>;">


    <div class="card-panel">
        <div class="row">
            <div class="col s10 m12 l8">
                <h5 class="breadcrumbs mt-0 mb-0"><span>Summary</span></h5>
            </div>
        </div>
        <div class="row">
            <div class="col m12 s12"> &nbsp;</div>
        </div>
        <div class="row">
            <?php
            if (isset($po_no)) { ?>
                <div class="input-field col m4 s12">
                    <i class="material-icons prefix">question_answer</i>
                    <input id="po_nodddd" readonly disabled type="text" value="<?php if (isset($po_no)) {
                                                                                    echo $po_no;
                                                                                } ?>">
                    <label for="po_nodddd">PO No</label>
                </div>
            <?php } ?>
            <?php
            $field_name     = "po_date";
            $field_label     = "Order Date (d/m/Y)";
            ?>
            <div class="input-field col m4 s12">
                <i class="material-icons prefix">date_range</i>
                <input id="<?= $field_name; ?>" type="text" readonly disabled value="<?php if (isset(${$field_name})) {
                                                                                            echo ${$field_name};
                                                                                        } ?>" class="  validate <?php if (isset(${$field_name . "_valid"})) {
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
            <?php
            $field_name     = "vender_name";
            $field_label     = "Vender";

            $table              = "venders";
            $columns            = array("vender_name");
            $get_col_from_table = get_col_from_table($db, $conn, $selected_db_name, $table, $vender_id, $columns);
            foreach ($get_col_from_table as $array_key1 => $array_data1) {
                ${$array_key1} = $array_data1;
            }

            ?>
            <div class="input-field col m4 s12">
                <i class="material-icons prefix">date_range</i>
                <input id="<?= $field_name; ?>" type="text" readonly disabled value="<?php if (isset(${$field_name})) {
                                                                                            echo ${$field_name};
                                                                                        } ?>" class="  validate <?php if (isset(${$field_name . "_valid"})) {
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
            <?php
            $field_name     = "vender_invoice_no";
            $field_label     = "Vendor Invoice #";
            ?>
            <div class="input-field col m4 s12">
                <i class="material-icons prefix">description</i>
                <input id="<?= $field_name; ?>" type="text" readonly disabled value="<?php if (isset(${$field_name})) {
                                                                                            echo ${$field_name};
                                                                                        } ?>" class="  validate <?php if (isset(${$field_name . "_valid"})) {
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
        <div class="row"> &nbsp;</div>
    </div>
    <div class="card-panel">
        <?php
        $sql_cl        = "	SELECT b.*, d.po_no, d.po_date, e.vender_name, d.vender_invoice_no, f.status_name, 
                                g.product_desc, g.product_uniqueid, h.category_name, b.overall_grade, b.price, 
                                i.sub_location_name, i.sub_location_type
                            FROM purchase_order_detail_receive_rma a
                            INNER JOIN purchase_order_detail_receive b ON b.id = a.receive_id
                            INNER JOIN purchase_order_detail c ON c.id = b.po_detail_id
                            INNER JOIN purchase_orders d ON d.id = c.po_id
                            LEFT JOIN venders e ON e.id = d.vender_id
                            INNER JOIN inventory_status f ON f.id = a.status_id
                            LEFT JOIN products g ON g.id = c.product_id
                            LEFT JOIN product_categories h ON h.id = g.product_category
                            LEFT JOIN warehouse_sub_locations i ON i.id = b.sub_location_id_after_diagnostic 
                            WHERE c.po_id = '" . $id . "'
					        AND a.status_id != 19
                            ORDER BY a.id DESC"; // echo $sql_cl;
        $result_cl    = $db->query($conn, $sql_cl);
        $count_cl    = $db->counter($result_cl);
        ?>
        <div class="section section-data-tables">
            <div class="row">
                <div class="col s12">
                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col m12 s12">
                                <table id="page-length-option" class="display ">
                                    <thead>
                                        <tr>
                                            <?php
                                            $headings = '	<th class="sno_width_60">S.No</th>
                                                            <th>Product ID</th>
                                                            <th>SeriaL#</th>
                                                            <th>Original Grade</th> 
                                                            <th>Location</th> 
                                                            <th>RMA Status</th> ';
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
                                                    <td style="text-align: center;"><?php echo $i + 1; ?></td>
                                                    <td style="<?= $td_padding; ?>">
                                                        <?php echo $data['product_uniqueid']; ?>
                                                    </td>
                                                    <td><?php echo $data['serial_no_barcode']; ?></td>
                                                    <td><?php echo $data['overall_grade']; ?></td>
                                                    <td style="<?= $td_padding; ?>">
                                                        <?php
                                                        echo $data['sub_location_name'];
                                                        if ($data['sub_location_type'] != "") {
                                                            echo " (" . $data['sub_location_type'] . ")";
                                                        } ?>
                                                    </td>
                                                    <td>
                                                        <span class="chip green lighten-5">
                                                            <span class="green-text">
                                                                <?php echo $data['status_name'];  ?>
                                                            </span>
                                                        </span>
                                                    </td>
                                                </tr>
                                        <?php $i++;
                                            }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>