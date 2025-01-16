<div id="tab2_html" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab2')) {
                                        echo "block";
                                    } else {
                                        echo "none";
                                    } ?>;">
    <div class="card-panel">
        <div class="row">
            <div class="col s10 m12 l8">
                <h5 class="breadcrumbs mt-0 mb-0"><span>PO Detail</span></h5>
            </div>
        </div>
        <?php
        if (isset($id)) {  ?>
            <div class="row">
                <div class="input-field col m3 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Purchase Order No: </b>" . $po_no; ?></span></h6>
                </div>
                <div class="input-field col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>Vendor Invoice No: </b>" . $vender_invoice_no; ?></span></h6>

                </div>
            </div>
        <?php }  ?>
    </div>
    <?php
    if (!isset($id)) { ?>
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
    <?php } ?>
    <?php
    $td_padding = "padding:5px 15px !important;";
    if (isset($id)) {
        $sql             = "    SELECT  a.*, 
                                        b.base_product_id, b.sub_product_id, b.serial_no_barcode, b.overall_grade,
                                        d.po_no, d.po_date, e.vender_name, d.vender_invoice_no, f.status_name, 
                                        g.product_desc, g.product_uniqueid, h.category_name,  b.price, a.total_repair_cost, 
                                        i.sub_location_name, i.sub_location_type, a.grade_after_repaire
                                FROM purchase_order_detail_receive_rma a 
                                INNER JOIN purchase_order_detail_receive b ON b.id = a.receive_id
                                INNER JOIN purchase_order_detail c ON c.id = b.po_detail_id
                                INNER JOIN purchase_orders d ON d.id = c.po_id
                                LEFT JOIN venders e ON e.id = d.vender_id
                                LEFT JOIN inventory_status f ON f.id = a.repaire_status_id
                                LEFT JOIN products g ON g.id = c.product_id
                                LEFT JOIN product_categories h ON h.id = g.product_category 
                                LEFT JOIN warehouse_sub_locations i ON i.id = b.sub_location_id_after_diagnostic 
                                WHERE c.po_id = '" . $id . "' 
                                AND (a.status_id = 19 || a.is_repaired = 1) 
                                ORDER BY a.is_repaired DESC, date_format(a.update_date, '%Y%m%d%H%i%s'), a.id DESC"; // status_id => 19 =  Repair
        $result_log     = $db->query($conn, $sql);
        $count_log      = $db->counter($result_log);
        if ($count_log > 0) { ?>
            <form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=edit&id=" . $id . "&cmd2=" . $cmd2 . "&active_tab=tab2") ?>" method="post">
                <input type="hidden" name="is_Submit_tab2_1" value="Y" />
                <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                    echo encrypt($_SESSION['csrf_session']);
                                                                } ?>">
                <input type="hidden" name="active_tab" value="tab2" />
                <div class="card-panel">
                    <div class="row">
                        <div class="col m12 s12">
                            <h5>Products for Repair</h5>
                            <div class="section section-data-tables">

                                <?php
                                if (po_permisions("Repair Process") == 1) { ?>
                                    <div class="row">
                                        <div class="col m12 s12">
                                            <label>
                                                <input type="checkbox" id="all_checked" class="filled-in" name="all_checked" value="1" <?php if (isset($all_checked) && $all_checked == '1') {
                                                                                                                                            echo "checked";
                                                                                                                                        } ?> />
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                <?php
                                }   ?>
                                <div class="row">
                                    <div class="col m12 s12">
                                        <table id="page-length-option" class="display2 ">
                                            <thead>
                                                <tr>
                                                    <?php
                                                    $headings = '	<th class="sno_width_60">S.No ';
                                                    $headings .= '
                                                                    </th>
                                                                    <th>Product ID</th>
                                                                    <th>SeriaL#</th>
                                                                    <th>Original Grade</th> 
                                                                    <th>Repair Grade</th> 
                                                                    <th>Location</th> 
                                                                     <th>Repair Cost</th> 
                                                                     <th>Parts Cost</th> 
                                                                    <th>Repair Status</th> 
                                                                    <th>Action</th>';
                                                    echo $headings;
                                                    $headings2 = ' '; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 0;
                                                if ($count_log > 0) {
                                                    $row_cl1 = $db->fetch($result_log);
                                                    foreach ($row_cl1 as $data) {
                                                        $detail_id2     = $data['id'];
                                                        $edit_lock      = $data['edit_lock'];
                                                        $is_repaired    = $data['is_repaired'];  ?>
                                                        <tr>
                                                            <td style="text-align: center; <?= $td_padding; ?>">
                                                                <?php echo $i + 1; ?> &nbsp;
                                                                <?php
                                                                if (po_permisions("Repair Process") == 1 && $edit_lock == '0' && $is_repaired == '1') { ?>
                                                                    <label style="margin-left: 25px;">
                                                                        <input type="checkbox" name="repaire_ids[]" id="repaire_ids[]" value="<?= $detail_id2; ?>" <?php
                                                                                                                                                                    if (isset($repaire_ids) && in_array($detail_id2, $repaire_ids)) {
                                                                                                                                                                        echo "checked";
                                                                                                                                                                    } ?> class="checkbox filled-in" />
                                                                        <span></span>
                                                                    </label>
                                                                <?php } ?>
                                                            </td>
                                                            <td style="<?= $td_padding; ?>">
                                                                <?php echo $data['product_uniqueid']; ?></br>
                                                                <?php
                                                                if ($data['overall_grade'] != $data['grade_after_repaire'] && $data['grade_after_repaire'] != "" && $data['grade_after_repaire'] != null) {
                                                                    echo "<br><b>New: </b>" . $data['base_product_id'] . "-" . $data['grade_after_repaire'];
                                                                } ?>
                                                            </td>
                                                            <td style="<?= $td_padding; ?>"><?php echo $data['serial_no_barcode']; ?></td>
                                                            <td style="<?= $td_padding; ?>"><?php echo $data['overall_grade']; ?></td>
                                                            <td style="<?= $td_padding; ?>"><?php echo $data['grade_after_repaire']; ?></td>
                                                            <td style="<?= $td_padding; ?>">
                                                                <?php
                                                                echo $data['sub_location_name'];
                                                                if ($data['sub_location_type'] != "") {
                                                                    echo " (" . $data['sub_location_type'] . ")";
                                                                } ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                if ($data['repair_cost'] != "") {
                                                                    echo number_format($data['repair_cost'], 2);
                                                                } ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                if ($data['parts_cost'] != "") {
                                                                    echo number_format($data['parts_cost'], 2);
                                                                } ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                if ($data['status_name'] != "") { ?>
                                                                    <span class="chip green lighten-5">
                                                                        <span class="green-text"><?php echo $data['status_name']; ?></span>
                                                                    </span>
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                if (po_permisions("RMA Repair") == 1 && $edit_lock == '0') { ?>
                                                                    <a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=repaire_process&cmd=edit&id=" . $id . "&detail_id=" . $detail_id2) ?>">
                                                                        <i class="material-icons dp48">edit</i>
                                                                    </a> &nbsp;&nbsp;
                                                                <?php } ?>
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
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col m4 s12"></div>
                        <div class="input-field col m4 s12">
                            <?php
                            if (isset($id) && $id > 0 && (access("add_perm") == 1 || access("edit_perm") == 1) && po_permisions("Repair Process") == 1) { ?>
                                <button class="waves-effect waves-light  btn gradient-45deg-purple-deep-orange box-shadow-none border-round mr-1 mb-1" type="submit" name="add">Process Repair (Move To Inventory)</button>
                            <?php } ?>
                        </div>
                        <div class="input-field col m4 s12"></div>
                    </div>
                </div>
            </form>
    <?php }
    } ?>

</div>