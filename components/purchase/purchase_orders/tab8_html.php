<div id="tab8_html" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab8')) {
                                        echo "block";
                                    } else {
                                        echo "none";
                                    } ?>;">
    <div class="card-panel" style="padding-top: 5px; padding-bottom: 5px; margin-top: 0px; margin-bottom: 5px;">
        <div class="row">
            <div class="input-field col m6 s12" style="margin-top: 3px; margin-bottom: 3px;">
                <h6 class="media-heading">
                    <?= $general_heading; ?> => Price Setup
                </h6>
            </div>
            <div class="input-field col m6 s12" style="text-align: right; margin-top: 3px; margin-bottom: 3px;">
                <?php include("tab_action_btns.php"); ?>
            </div>
        </div>
        <?php
        if (isset($id) && isset($po_no)) {  ?>
            <div class="row">
                <div class="input-field col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>PO#: </b>" . $po_no; ?></span></h6>
                </div>
                <div class="input-field col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Vendor Invoice#: </b>" . $vender_invoice_no; ?></span></h6>
                </div>
                <div class="input-field col m4 s12">
                    <span class="chip green lighten-5">
                        <span class="green-text">
                            <?php echo $disp_status_name; ?>
                        </span>
                    </span>
                </div>
            </div>
        <?php }  ?>
    </div>
    <?php
    if (!isset($id) || (isset($id) && $id == '')) { ?>
        <div class="card-panel">
            <div class="row">
                <!-- Search for small screen-->
                <div class="container">
                    <div class="card-alert card red">
                        <div class="card-content white-text">
                            <p>Please add master record first</p>
                        </div>
                        <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else if (isset($active_tab) && $active_tab == 'tab8') {
        $td_padding     = "padding:5px 15px !important;";
        $sql            = " SELECT a.id
                            FROM purchase_order_detail_receive a 
                            INNER JOIN product_stock c ON a.id = c.receive_id
                            WHERE a.enabled = 1 
                            AND a.is_diagnost = 1
                            AND a.po_id = '" . $id . "' ";
        // echo $sql; 
        $result_log     = $db->query($conn, $sql);
        $count_log      = $db->counter($result_log);
        if ($count_log > 0) {
            $total_pending_rma = 0;
            if (isset($_SESSION['total_pending_rma'])) {
                $total_pending_rma = $_SESSION['total_pending_rma'];
            }
            if ($total_pending_rma == 0) { ?>
                <?php //*/ 
                $td_padding = "padding:5px 10px !important;";
                $sql        = " WITH cte AS (
                                    SELECT * FROM (
                                        SELECT  a.po_detail_id, a.product_id, c.product_uniqueid, 
                                            c2.stock_grade,
                                            ROUND((SUM(a.price)/COUNT(c2.id)), 2) AS order_price, 
                                            COUNT(c2.id) AS total_qty, 
                                            ROUND(SUM(a.logistic_cost), 2) AS logistic_cost, 
                                            ROUND(SUM(a.receiving_labor), 2) AS receiving_labor, 
                                            ROUND(SUM(a.diagnostic_labor), 2) AS diagnostic_labor, 
                                            ROUND(SUM(c2.distributed_amount), 2) AS distributed_amount, 
                                            ROUND(SUM(c2.distributed_amount)+SUM(a.logistic_cost)+ SUM(a.receiving_labor)+SUM(a.diagnostic_labor), 2) AS other_cost,
                                            ROUND(SUM(c2.distributed_amount)+SUM(a.logistic_cost)+ SUM(a.receiving_labor)+SUM(a.diagnostic_labor)+(SUM(a.price)), 2) total_price
                                        FROM purchase_order_detail_receive a 
                                        INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
                                        INNER JOIN products c ON c.id = b.product_id
                                        INNER JOIN product_stock c2 ON c2.receive_id = a.id
                                        WHERE a.enabled     = 1 AND c2.enabled = 1 AND b.enabled = 1
                                        AND a.is_diagnost   = 1
                                        AND b.enabled       = 1
                                        AND b.po_id         = '" . $id . "'
                                        AND c2.p_inventory_status = 5
                                        AND a.received_during != 'BarCodeReceive'
                                        AND c2.stock_grade IN('A', 'B', 'C')
                                        GROUP BY c.product_uniqueid, c2.stock_grade
                                        
                                        UNION ALL 
                                        
                                        SELECT  a.po_detail_id, a.product_id, c.product_uniqueid, 
                                            c2.stock_grade,
                                            ROUND((SUM(a.price)/COUNT(c2.id)), 2) AS order_price, 
                                            COUNT(c2.id) AS total_qty, 
                                            ROUND(SUM(a.logistic_cost), 2) AS logistic_cost, 
                                            ROUND(SUM(a.receiving_labor), 2) AS receiving_labor, 
                                            ROUND(SUM(a.diagnostic_labor), 2) AS diagnostic_labor, 
                                            ROUND(SUM(c2.distributed_amount), 2) AS distributed_amount, 
                                            ROUND(SUM(c2.distributed_amount)+SUM(a.logistic_cost)+ SUM(a.receiving_labor)+SUM(a.diagnostic_labor), 2) AS other_cost,
                                            ROUND(SUM(c2.distributed_amount)+SUM(a.logistic_cost)+ SUM(a.receiving_labor)+SUM(a.diagnostic_labor)+(SUM(a.price)), 2) total_price
                                        FROM purchase_order_detail_receive a 
                                        INNER JOIN products c ON c.id = a.product_id
                                        INNER JOIN product_stock c2 ON c2.receive_id = a.id
                                        WHERE a.enabled     = 1 AND c2.enabled = 1  
                                        AND a.is_diagnost   = 1
                                        AND a.po_id         = '" . $id . "'
                                        AND c2.p_inventory_status = 5
                                        AND a.received_during != 'BarCodeReceive'
                                        AND c2.stock_grade IN('A', 'B', 'C')
                                        GROUP BY c.product_uniqueid, c2.stock_grade
                                    ) AS t1
                                    ORDER BY product_uniqueid, stock_grade
                                ),
                                product_totals AS (
                                    SELECT product_uniqueid,
                                        SUM(total_price) AS total_price_sum,
                                        SUM(total_qty) AS total_qty_sum,
                                        COUNT(*) AS record_count
                                    FROM cte
                                    GROUP BY product_uniqueid
                                )
                                SELECT cte.*, 
                                    pt.total_price_sum AS product_total_price,
                                    pt.total_qty_sum AS product_total_qty,
                                    pt.record_count AS no_of_grades_in_product
                                FROM cte
                                LEFT JOIN product_totals pt ON cte.product_uniqueid = pt.product_uniqueid
                                ORDER BY product_uniqueid, stock_grade ";
                // echo $sql;
                $result_log     = $db->query($conn, $sql);
                $result_log2    = $db->query($conn, $sql);
                $count_log      = $db->counter($result_log);
                if ($count_log > 0) { ?>
                    <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&active_tab=tab8") ?>" method="post">
                        <input type="hidden" name="is_Submit_tab8" value="Y" />
                        <input type="hidden" name="cmd8" value="<?php if (isset($cmd8)) echo $cmd8; ?>" />
                        <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                            echo encrypt($_SESSION['csrf_session']);
                                                                        } ?>">
                        <input type="hidden" name="duplication_check_token" value="<?php echo (time() . session_id()); ?>">
                        <input type="hidden" name="active_tab" value="tab8" />
                        <div class="card-panel">
                            <div class="section section-data-tables">
                                <div class="row">
                                    <div class="text_align_right">
                                        <?php
                                        $table_columns    = array('Product ID / Product Detail', 'Grade', 'Qty', 'Suggested Price', 'Total');
                                        $k                = 0;
                                        foreach ($table_columns as $data_c1) { ?>
                                            <label>
                                                <input type="checkbox" value="<?= $k ?>" name="table_columns[]" class="filled-in toggle-column" data-column="<?= set_table_headings($data_c1) ?>" checked="checked">
                                                <span><?= $data_c1 ?></span>
                                            </label>&nbsp;&nbsp;
                                        <?php
                                            $k++;
                                        } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col m12 s12">
                                        <table id="page-length-option" class=" display pagelength50_4 dataTable dtr-inline">
                                            <thead>
                                                <tr>
                                                    <?php
                                                    $headings = "";
                                                    foreach ($table_columns as $data_c) {
                                                        if ($data_c == 'SNo') {
                                                            $headings .= '<th class="sno_width_60 col-' . set_table_headings($data_c) . '">' . $data_c . '</th>';
                                                        } else {
                                                            $headings .= '<th class="col-' . set_table_headings($data_c) . '">' . $data_c . '</th> ';
                                                        }
                                                    }
                                                    echo $headings;
                                                    $headings2 = ' '; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = $k = 0;
                                                $array_pricing  = array();
                                                if ($count_log > 0) {
                                                    $row_cl1 = $db->fetch($result_log);
                                                    $row_cl2 = $db->fetch($result_log2);
                                                    foreach ($row_cl2 as $data2) {
                                                        $product_total_price        = $data2['product_total_price'];
                                                        $total_qty                  = $data2['total_qty'];
                                                        $logistic_cost              = $data2['logistic_cost'];
                                                        $receiving_labor            = $data2['receiving_labor'];
                                                        $diagnostic_labor           = $data2['diagnostic_labor'];
                                                        $distributed_amount         = $data2['distributed_amount'];

                                                        if ($logistic_cost > 0) {
                                                            $logistic_cost = $logistic_cost / $data2['total_qty'];
                                                        }
                                                        if ($receiving_labor > 0) {
                                                            $receiving_labor = $receiving_labor / $data2['total_qty'];
                                                        }
                                                        if ($diagnostic_labor > 0) {
                                                            $diagnostic_labor = $diagnostic_labor / $data2['total_qty'];
                                                        }
                                                        if ($distributed_amount > 0) {
                                                            $distributed_amount = $distributed_amount / $data2['total_qty'];
                                                        }

                                                        $array_pricing[$data2['product_uniqueid']][] = [
                                                            "stock_grade"           => $data2['stock_grade'],
                                                            "product_total_price"   => $product_total_price,
                                                            "total_qty"             => $total_qty,
                                                            "logistic_cost"         => $logistic_cost,
                                                            "receiving_labor"       => $receiving_labor,
                                                            "diagnostic_labor"      => $diagnostic_labor,
                                                            "distributed_amount"    => $distributed_amount
                                                        ];
                                                    }
                                                    foreach ($row_cl1 as $data) {
                                                        $po_detail_id_prc       = $data['po_detail_id'];
                                                        $product_id_prc         = $data['product_id'];
                                                        $product_uniqueid       = $data['product_uniqueid'];
                                                        $product_total_price    = $data['product_total_price'];
                                                        $total_cost             = ($data['total_price']);
                                                        $total_qty              = $data['total_qty'];
                                                        $single_item_price      = round(($total_cost / $total_qty), 2);

                                                        $logistic_cost          = $data['logistic_cost'];
                                                        $receiving_labor        = $data['receiving_labor'];
                                                        $diagnostic_labor       = $data['diagnostic_labor'];
                                                        $distributed_amount     = $data['distributed_amount'];

                                                        $logistic_percentage_per_item = $receiving_percentage_per_item = $diagnostic_percentage_per_item = $distributed_percentage_per_item = 0;
                                                        if ($logistic_cost > 0) {
                                                            $logistic_percentage_per_item = ((($logistic_cost / $total_qty) / $single_item_price) * 100);
                                                        }
                                                        if ($receiving_labor > 0) {
                                                            $receiving_percentage_per_item = ((($receiving_labor / $total_qty) / $single_item_price) * 100);
                                                        }
                                                        if ($diagnostic_labor > 0) {
                                                            $diagnostic_percentage_per_item = ((($diagnostic_labor / $total_qty) / $single_item_price) * 100);
                                                        }
                                                        if ($distributed_amount > 0) {
                                                            $distributed_percentage_per_item = ((($distributed_amount / $total_qty) / $single_item_price) * 100);
                                                        }

                                                        if (!isset($array_pricing[$product_uniqueid]) || !array_filter($array_pricing[$product_uniqueid], function ($entry) {
                                                            return $entry['stock_grade'] === "A";
                                                        })) {
                                                            // If not present, add the new entry for stock grade "A"
                                                            array_unshift($array_pricing[$product_uniqueid], [
                                                                "stock_grade" => "A",
                                                                "product_total_price" => $product_total_price,
                                                                "total_qty" => 0, // Assuming you want to set this to 0
                                                                "logistic_cost" => 0,
                                                            ]);
                                                        }
                                                        if (!isset($array_pricing[$product_uniqueid]) || !array_filter($array_pricing[$product_uniqueid], function ($entry) {
                                                            return $entry['stock_grade'] === "B";
                                                        })) {
                                                            $new_item = [
                                                                "stock_grade" => "B",
                                                                "product_total_price" => $product_total_price,
                                                                "total_qty" => 0, // Assuming you want to set this to 0
                                                                "logistic_cost" => 0,
                                                            ];
                                                            // Insert at index 1 and shift the rest
                                                            array_splice($array_pricing[$product_uniqueid], 1, 0, [$new_item]);
                                                        }
                                                        if (!isset($array_pricing[$product_uniqueid]) || !array_filter($array_pricing[$product_uniqueid], function ($entry) {
                                                            return $entry['stock_grade'] === "C";
                                                        })) {
                                                            // If not present, add the new entry for stock grade "C"
                                                            $array_pricing[$product_uniqueid][] = [
                                                                "stock_grade" => "C",
                                                                "product_total_price" => $product_total_price,
                                                                "total_qty" => 0, // Assuming you want to set this to 0
                                                                "logistic_cost" => 0,
                                                            ];
                                                        }
                                                        $pricing_table_trs = "";
                                                ?>
                                                        <tr>
                                                            <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[1]); ?>">
                                                                <?php echo $product_uniqueid; ?>
                                                            </td>
                                                            <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[2]); ?>">
                                                                <?php echo $data['stock_grade']; ?>
                                                            </td>
                                                            <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[3]); ?>">
                                                                <?php echo $total_qty; ?>
                                                            </td>
                                                            <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[4]); ?>">
                                                                <a class="modal-trigger" href="#<?= $product_uniqueid; ?>_<?= $data['stock_grade']; ?>">
                                                                    <?php
                                                                    $suggested_price = 0;
                                                                    if ($data['no_of_grades_in_product'] > 1) {
                                                                        // echo "<pre>";
                                                                        foreach ($array_pricing[$product_uniqueid] as $record) {

                                                                            $total_qty_from_array1  = $array_pricing[$product_uniqueid][0]['total_qty'];
                                                                            $total_qty_from_array2  = $array_pricing[$product_uniqueid][1]['total_qty'];
                                                                            $total_qty_from_array3  = $array_pricing[$product_uniqueid][2]['total_qty'];

                                                                            $b_grade_calculation    = ($product_total_price / (($total_qty_from_array1 * 1.1) + $total_qty_from_array2 + ($total_qty_from_array3 * 0.9)));
                                                                            //echo " $grade_calculation    = ($product_total_price / (($total_qty_from_array1 * 1.1) + $total_qty_from_array2 + ($total_qty_from_array3 * 0.9)))";

                                                                            if ($data["stock_grade"] == 'A' && $record["stock_grade"] == 'A') {
                                                                                $suggested_price = ($b_grade_calculation * 1.1);
                                                                                echo round(($suggested_price), 2);
                                                                            } else if ($data["stock_grade"] == 'B' && $record["stock_grade"] == 'B') {
                                                                                $suggested_price = $b_grade_calculation;
                                                                                echo round(($suggested_price), 2);
                                                                            } else if ($data["stock_grade"] == 'C' && $record["stock_grade"] == 'C') {
                                                                                $suggested_price =  ($b_grade_calculation * 0.9);
                                                                                echo round(($suggested_price), 2);
                                                                            }
                                                                        }
                                                                    } else {
                                                                        $suggested_price = $single_item_price;
                                                                        echo $suggested_price;
                                                                    }
                                                                    $single_product_price = $suggested_price;
                                                                    if ($data['stock_grade'] == 'A') {
                                                                        $index = '0';
                                                                    } else if ($data['stock_grade'] == 'B') {
                                                                        $index = '1';
                                                                    } else if ($data['stock_grade'] == 'C') {
                                                                        $index = '2';
                                                                    }
                                                                    $logistic_after_pricing = "0";
                                                                    if (isset($array_pricing[$product_uniqueid][$index]['logistic_cost']) && $array_pricing[$product_uniqueid][$index]['logistic_cost'] > 0) {
                                                                        $logistic_after_pricing =  $array_pricing[$product_uniqueid][$index]['logistic_cost'];
                                                                        $pricing_table_trs .= "  <tr>
                                                                                                    <td>Logistics</td>
                                                                                                    <td>" . $logistic_after_pricing . "</td>
                                                                                                </tr>";
                                                                    }
                                                                    $single_product_price = $single_product_price - $logistic_after_pricing;


                                                                    $receiving_after_pricing = "0";
                                                                    if (isset($array_pricing[$product_uniqueid][$index]['receiving_labor']) && $array_pricing[$product_uniqueid][$index]['receiving_labor'] > 0) {
                                                                        $receiving_after_pricing =  $array_pricing[$product_uniqueid][$index]['receiving_labor'];
                                                                        $pricing_table_trs .= "  <tr>
                                                                                                    <td>Receiving</td>
                                                                                                    <td>" . $receiving_after_pricing . "</td>
                                                                                                </tr>";
                                                                    }
                                                                    $single_product_price = $single_product_price - $receiving_after_pricing;


                                                                    $diagnostic_after_pricing = "0";
                                                                    if (isset($array_pricing[$product_uniqueid][$index]['diagnostic_labor']) && $array_pricing[$product_uniqueid][$index]['diagnostic_labor'] > 0) {
                                                                        $diagnostic_after_pricing =  $array_pricing[$product_uniqueid][$index]['diagnostic_labor'];
                                                                        $pricing_table_trs .= "  <tr>
                                                                                                    <td>Diagnostic</td>
                                                                                                    <td>" . $diagnostic_after_pricing . "</td>
                                                                                                </tr>";
                                                                    }
                                                                    $single_product_price = $single_product_price - $diagnostic_after_pricing;


                                                                    $distributed_after_pricing = "0";
                                                                    if (isset($array_pricing[$product_uniqueid][$index]['distributed_amount']) && $array_pricing[$product_uniqueid][$index]['distributed_amount'] > 0) {
                                                                        $distributed_after_pricing =  $array_pricing[$product_uniqueid][$index]['distributed_amount'];
                                                                        $pricing_table_trs .= "  <tr>
                                                                                                    <td>Other Defective Distribution</td>
                                                                                                    <td>" . $distributed_after_pricing . "</td>
                                                                                                </tr>";
                                                                    }
                                                                    $single_product_price = $single_product_price - $distributed_after_pricing;

                                                                    $pricing_table_trs .= "  <tr>
                                                                                                <td>Product Price</td>
                                                                                                <td>" . round($single_product_price, 2) . "</td>
                                                                                            </tr>";
                                                                    $pricing_table_trs .= "  <tr>
                                                                                                                        <td>Final Price</td>
                                                                                                                        <td>" . round($suggested_price, 2) . "</td>
                                                                                                                    </tr>";

                                                                    $price_grade    = $data["stock_grade"];
                                                                    if (!isset($is_Submit_tab8)) {
                                                                        $sql = "INSERT INTO temp_po_pricing (uniq_session_id, po_id, po_detail_id, product_id, po_product_uniq_id, price_grade, suggested_price,
                                                                                                        logistic_percentage_per_item, receiving_percentage_per_item, diagnostic_percentage_per_item, distributed_percentage_per_item, 
                                                                                                        add_by_user_id, add_date,  add_by, add_ip, add_timezone, added_from_module_id) 
                                                                                VALUES( '" . $uniq_session_id . "', '" . $id . "', '" . $po_detail_id_prc . "', '" . $product_id_prc . "', '" . $product_uniqueid . "', '" . $price_grade . "', '" . round($suggested_price, 2) . "',
                                                                                    '" . $logistic_percentage_per_item . "', '" . $receiving_percentage_per_item . "', '" . $diagnostic_percentage_per_item . "', '" . $distributed_percentage_per_item . "',
                                                                                    '" . $_SESSION['user_id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
                                                                        $db->query($conn, $sql);
                                                                    } ?>
                                                                </a>
                                                                <div id="<?= $product_uniqueid; ?>_<?= $data['stock_grade']; ?>" class="modal">
                                                                    <div class="modal-content">
                                                                        <h4>Pricing Details</h4>
                                                                        <table>
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Head</th>
                                                                                    <th>Amount</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?= $pricing_table_trs; ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td style="<?= $td_padding; ?>" class="col-<?= set_table_headings($table_columns[5]); ?>">
                                                                <?php echo round($suggested_price * $data['total_qty'], 2); ?>
                                                            </td>
                                                        </tr>
                                                <?php
                                                        $i++;
                                                    }
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php
                                if (po_permisions("PriceSetup") == 1) {  ?>
                                    <div class="row">
                                        <div class="input-field col m12 s12 text_align_center">
                                            <?php if (isset($id) && $id > 0) { ?>
                                                <button class="mb-6 btn waves-effect waves-light gradient-45deg-purple-deep-orange" type="submit" name="add">Finalize Pricing</button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m12 s12"></div>
                                    </div>
                                <?php
                                } ?>
                            </div>
                        </div>
                    </form>
                <?php
                }
            } else { ?>
                <div class="card-panel">
                    <div class="row">
                        <div class="col 24 s12"><br>
                            <div class="card-alert card red lighten-5">
                                <div class="card-content red-text">
                                    <p>RMA Data is in Pending, Please complete RMA Process First. </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
        } else { ?>
            <div class="card-panel">
                <div class="row">
                    <div class="col 24 s12"><br>
                        <div class="card-alert card red lighten-5">
                            <div class="card-content red-text">
                                <p>Diagnostic not processed yet. </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php }
    } ?>
</div>