<style>
    .tabs .tab a:hover,
    .tabs .tab a.active {
        color: rgb(5, 103, 183);
        background-color: rgba(17, 116, 245, 0.15);
    }

    .tabs .tab a.active {
        color: rgb(5, 103, 183);
        background-color: rgba(17, 116, 245, 0.15);
    }
</style>
<div class="row">
    <ul class="tabs tabs-fixed-width tab-demo z-depth-1">
        <?php
        if (po_permisions("PO Detail") == 1) { ?>
            <li class="tab" id="show_tab1">
                <a href="#tab1_html" class="<?php if (isset($active_tab) && $active_tab == 'tab1') {
                                                echo "active";
                                            } ?>">
                    <i class="material-icons dp48">receipt</i>
                    <span>Purchase</span>
                </a>
            </li>
        <?php }
        if (po_permisions("Vendor Data") == 1) { ?>
            <li class="tab">
                <a href="#tab4_html" class="<?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab4')) {
                                                echo "active";
                                            } ?>">
                    <i class="material-icons dp48">person_outline</i>
                    <span>Vendor Data</span>
                </a>
            </li>
        <?php }
        if (po_permisions("Logistics") == 1) {  ?>
            <li class="tab">
                <a href="#tab2_html" class="<?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab2')) {
                                                echo "active";
                                            } ?>">
                    <i class="material-icons dp48">add_shopping_cart</i>
                    <span>Logistics</span>
                </a>
            </li>
        <?php }
        if (po_permisions("Arrival") == 1) { ?>
            <li class="tab">
                <a href="#tab3_html" class="<?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab3')) {
                                                echo "active";
                                            } ?>">
                    <i class="material-icons dp48">add_shopping_cart</i>
                    <span> Arrival
                        <?php
                        $total_logistics = 0;
                        $sql2               = " SELECT ifnull(sum(a.no_of_boxes), 0) as total_no_of_boxes
                                                FROM purchase_order_detail_logistics a
                                                WHERE a.po_id = '" . $id . "'";
                        $result_logistics1  = $db->query($conn, $sql2);
                        $ct_logistics       = $db->counter($result_logistics1);
                        if($ct_logistics >0){
                            $row_logistics1     = $db->fetch($result_logistics1);
                            $total_logistics    = $row_logistics1[0]['total_no_of_boxes'];
                        }

                        $total_arrived = 0;
                        $sql2               = " SELECT ifnull(sum(a.no_of_box_arried), 0) as total_no_of_box_arried
                                                FROM purchase_order_detail_logistics_receiving a
                                                WHERE a.po_id = '" . $id . "'";
                        $result_logistics1  = $db->query($conn, $sql2);
                        $ct_logistics       = $db->counter($result_logistics1);
                        if($ct_logistics >0){
                            $row_logistics1     = $db->fetch($result_logistics1);
                            $total_arrived      = $row_logistics1[0]['total_no_of_box_arried'];
                        }

                        if ($total_logistics > 0 && $total_arrived > 0) {
                            $total_arrival_percentage = ($total_arrived / $total_logistics) * 100;
                            if ($total_arrival_percentage > 0) {
                                if($total_arrival_percentage == 100){
                                    echo " <span class='color-green'>(" . round(($total_arrival_percentage)) . "%)</span>";
                                }else if($total_arrival_percentage < 100){
                                    echo " <span class='color-yellow'>(" . round(($total_arrival_percentage)) . "%)</span>";
                                    ?>
                                    <i class="material-icons dp48">warning</i>
                                    <?php
                                }else if($total_arrival_percentage > 100){
                                    echo " <span class='color-red'>(" . round(($total_arrival_percentage)) . "%)</span>";
                                }
                            }
                        }
                        ?>
                    </span>
                </a>
            </li>
        <?php }
        if (po_permisions("Receive") == 1) { ?>
            <li class="tab">
                <a href="#tab5_html" class="<?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab5')) {
                                                echo "active";
                                            } ?>">
                    <i class="material-icons dp48">assistant</i>
                    <span>Receive
                        <?php
                        $total_items_ordered = 0;
                        $sql2       = " SELECT sum(a.order_qty) as order_qty
                                        FROM purchase_order_detail a
                                        WHERE a.po_id = '" . $id . "'
                                        AND a.enabled = 1 ";
                        $result_r2    = $db->query($conn, $sql2);
                        $count2     = $db->counter($result_r2);
                        if ($count2 > 0) {
                            $row_lg2                = $db->fetch($result_r2);
                            $total_items_ordered    = $row_lg2[0]['order_qty'];
                        }

                        $j = 0;
                        $sql3               = " SELECT a.id
                                                FROM purchase_order_detail_receive a
                                                WHERE a.po_id = '" . $id . "'
                                                AND a.enabled = 1 ";
                        $result3            = $db->query($conn, $sql3);
                        $total_received     = $db->counter($result3);
                        if ($total_items_ordered > 0 && $total_received > 0) {
                            $total_received_percentage = ($total_received / $total_items_ordered) * 100;
                            if ($total_received_percentage > 0) {
                                if($total_received_percentage == '100'){
                                    echo " <span class='color-green'>(" . round(($total_received_percentage)) . "%)</span>";
                                }
                                else if ($total_received_percentage < '100') { 
                                    echo " <span class='color-yellow'>(" . round(($total_received_percentage)) . "%)</span>";
                                    ?>
                                    <i class="material-icons dp48 color-yellow">warning</i>
                                    <?php
                                }else if($total_received_percentage > '100'){
                                    echo " <span class='color-red'>(" . round(($total_received_percentage)) . "%)</span>";
                                }
                            }
                        }
                        ?>
                    </span>
                </a>
            </li>
        <?php }
        if (po_permisions("Diagnostic") == 1) { ?>
            <li class="tab">
                <a href="#tab6_html" class="<?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab6')) {
                                                echo "active";
                                            } ?>">
                    <i class="material-icons dp48">list</i>
                    <span>Diagnostic
                        <?php
                        $j = 0;
                        $sql3               = " SELECT a.id
                                                FROM purchase_order_detail_receive a
                                                 WHERE a.po_id = '" . $id . "'
                                                AND a.serial_no_barcode IS NOT NULL
                                                AND a.serial_no_barcode !=''
                                                AND a.is_diagnost = 1
                                                AND a.is_diagnostic_bypass = 0
                                                AND a.enabled = 1 ";
                        $result3            = $db->query($conn, $sql3);
                        $total_diagnosed    = $db->counter($result3);
                        if ($total_received > 0) {
                            if ($total_diagnosed > 0) {
                                $total_diagnosed_percentage = ($total_diagnosed / $total_received) * 100;
                                if ($total_received > 0) {
                                    if($total_diagnosed_percentage == '100'){
                                        echo " <span class='color-green'>(" . round(($total_diagnosed_percentage)) . "%)</span>";
                                    }
                                    else if ($total_diagnosed_percentage < '100') { 
                                        echo " <span class='color-yellow'>(" . round(($total_diagnosed_percentage)) . "%)</span>";
                                        ?>
                                        <i class="material-icons dp48 color-yellow">warning</i>
                                        <?php
                                    }else if($total_diagnosed_percentage > '100'){
                                        echo " <span class='color-red'>(" . round(($total_diagnosed_percentage)) . "%)</span>";
                                    }
                                }
                            }
                        }
                        ?>
                    </span>
                </a>
            </li>
        <?php }
        if (po_permisions("RMA") == 1) { ?>
            <li class="tab">
                <a href="#tab7_html" class="<?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab7')) {
                                                echo "active";
                                            } ?>">
                    <i class="material-icons dp48">access_time</i>
                    <span>RMA</span>
                </a>
            </li>
        <?php }
        if (po_permisions("PriceSetup") == 1) { ?>
            <li class="tab">
                <a href="#tab8_html" class="<?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab8')) {
                                                echo "active";
                                            } ?>">
                    <i class="material-icons dp48">attach_money</i>
                    <span>Price Setup</span>
                </a>
            </li>
        <?php } ?>
        <li class="indicator" style="left: 0px; right: auto"></li>
    </ul>
</div>