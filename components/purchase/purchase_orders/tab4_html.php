<div id="tab4_html" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab4')) {
                                        echo "block";
                                    } else {
                                        echo "none";
                                    } ?>;">
    <div class="card-panel" style="padding-top: 5px; padding-bottom: 5px; margin-top: 0px; margin-bottom: 5px;">
        <div class="row">
            <div class="input-field col m6 s12" style="margin-top: 3px; margin-bottom: 3px;">
                <h6 class="media-heading">
                    <?= $general_heading; ?> => Vendor's Data
                </h6>
            </div>
            <div class="input-field col m6 s12" style="text-align: right; margin-top: 3px; margin-bottom: 3px;">
                <?php if (access("add_perm") == 1  && $stage_status == 'Draft') { ?>
                    <a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&id=" . $id . "&page=importvender_data") ?>">
                        Import Vendor Data
                    </a>
                <?php } ?>
                <?php include("tab_action_btns.php"); ?>
            </div>
        </div>
        <?php
        if (isset($id) && isset($po_no)) {  ?>
            <div class="row">
                <div class="input-field col m4 s12">
                    <h6 class="media-heading"><span class=""><?php echo "<b>PO#:</b>" . $po_no; ?></span></h6>
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
    }
    $td_padding = "padding:5px 15px !important;";
    if (isset($id) && $id > 0) {
        $orderby    = " ORDER BY a.id ";
        $sql        = " SELECT a.*  FROM vender_po_data a WHERE a.enabled = 1 AND a.po_id = '" . $id . "'";
        $sql       .= $orderby;  ?>
        <?php
        $result_log         = $db->query($conn, $sql);
        $count_log          = $db->counter($result_log);
        $total_vender_date  = $count_log;
        if ($count_log > 0) { ?>
            <div class="card-panel">
                <div class="row">
                    <div class="col m4 s12">
                        <h5>Vendor's Imported Data</h5>
                    </div>
                    <?php
                    if (isset($cmd4) &&  $cmd4 == "add" && isset($detail_id) && $detail_id != "") {  ?>
                        <div class="col m4 s12"><br><br>
                            <a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=" . $cmd . "&cmd4=" . $cmd4 . "&active_tab=tab4&id=" . $id) ?>">All Tracking / Pro #</a>
                        </div> <br>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col m12 s12">
                        <table class="display nowrap bordered">
                            <thead>
                                <tr>
                                    <?php
                                    $headings = '	<th class="sno_width_60">S.No</th>
                                                    <th>Product ID</th>
                                                    <th>Serial#</th>
                                                    <th>Overall Grade</th>
                                                    <th>Defects Or Notes</th> 
                                                    <th>Status</th>
                                                    <th>Price</th>';
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
                                        $detail_id2 = $data['id']; ?>
                                        <tr>
                                            <td style="<?= $td_padding; ?>"><?php echo $i + 1; ?> </td>
                                            <td style="<?= $td_padding; ?>"><?php echo $data['product_uniqueid']; ?></td>
                                            <td style="<?= $td_padding; ?>"><?php echo $data['serial_no']; ?></td>
                                            <td style="<?= $td_padding; ?>"><?php echo $data['overall_grade']; ?></td>
                                            <td style="<?= $td_padding; ?>"><?php echo $data['defects_or_notes']; ?></td>
                                            <td style="<?= $td_padding; ?>"><?php echo $data['status']; ?></td>
                                            <td style="<?= $td_padding; ?>"><?php echo $data['price']; ?></td>
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
        <?php } else { ?>
            <div class="card-panel">
                <div class="row">
                    <div class="col 24 s12"><br>
                        <div class="card-alert card red lighten-5">
                            <div class="card-content red-text">
                                <p>No data available. </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php
    } ?>
</div>