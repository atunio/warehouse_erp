<style>
.tabs .tab a:hover, .tabs .tab a.active {
    color:rgb(7, 123, 218);
    background-color: rgba(17, 116, 245, 0.15);
}
.tabs .tab a.active {
    color:rgb(7, 123, 218);
    background-color: rgba(17, 116, 245, 0.15);
}
</style>
<div class="row"> 
    <ul class="tabs tabs-fixed-width tab-demo z-depth-1">
        <li class="tab">
            <a href="#tab1_html" class="<?php if (isset($active_tab) && $active_tab == 'tab1') { echo "active"; } ?>">
                <i class="material-icons">receipt</i>
                <span>Purchase</span>
            </a>
        </li>
        <?php
        if (po_permisions("Vender Data") == 1) { ?>
            <li class="tab">
                <a href="#tab4_html" class="<?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab4')) { echo "active"; } ?>">
                    <i class="material-icons">person_outline</i>
                    <span>Vender Data</span>
                </a>
            </li>
        <?php }
        if (po_permisions("Logistics") == 1) {  ?>
            <li class="tab">
                <a href="#tab2_html" class="<?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab2')) { echo "active"; } ?>">
                    <i class="material-icons">add_shopping_cart</i>
                    <span>Logistics</span>
                </a>
            </li>
        <?php }
        if (po_permisions("Arrival") == 1) { ?>
            <li class="tab">
                <a href="#tab3_html" class="<?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab3')) { echo "active"; } ?>">
                    <i class="material-icons">add_shopping_cart</i>
                    <span> Arrival
                        <?php
                        $sql2                = "SELECT a.*
                                                FROM purchase_order_detail_logistics a
                                                WHERE a.po_id = '" . $id . "'";
                        $result2            = $db->query($conn, $sql2);
                        $total_logistics    = $db->counter($result2);

                        $j              = 0;
                        $sql3           = " SELECT a.*
                                            FROM purchase_order_detail_logistics a
                                            WHERE a.po_id = '" . $id . "'
                                            AND arrived_date IS NOT NULL ";
                        $result3        = $db->query($conn, $sql3);
                        $total_arrived  = $db->counter($result3);
                        if ($total_logistics > 0 && $total_arrived > 0) {
                            $total_arrival_percentage = ($total_arrived / $total_logistics) * 100;
                            if ($total_arrival_percentage > 0) {
                                echo " <span class='color-green'>(" . round(($total_arrival_percentage)) . "%)</span>";
                                if ($total_arrival_percentage < '100') { ?>
                                    <i class="material-icons dp48">warning</i>
                        <?php
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
                <a href="#tab5_html" class="<?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab5')) { echo "active"; } ?>">
                    <i class="material-icons">assistant</i>
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
                        $sql3               = "SELECT a.id
                                                FROM purchase_order_detail_receive a
                                                INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id 
                                                WHERE b.po_id = '" . $id . "'
                                                AND a.enabled = 1 ";
                        $result3            = $db->query($conn, $sql3);
                        $total_received     = $db->counter($result3);
                        if ($total_items_ordered > 0 && $total_received > 0) {
                            $total_received_percentage = ($total_received / $total_items_ordered) * 100;
                            if ($total_received_percentage > 0) {
                                echo " <span class='color-green'>(" . round(($total_received_percentage)) . "%)</span>";
                                if ($total_received_percentage < '100') { ?>
                                    <i class="material-icons dp48">warning</i>
                        <?php
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
                <a href="#tab6_html" class="<?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab6')) { echo "active";  } ?>">
                    <i class="material-icons">list</i>
                    <span>Diagnostic
                        <?php
                        $j = 0;
                        $sql3               = "SELECT a.id
                                                FROM purchase_order_detail_receive a
                                                INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id 
                                                WHERE b.po_id = '" . $id . "'
                                                AND a.serial_no_barcode IS NOT NULL
                                                AND a.serial_no_barcode !=''
                                                AND a.is_diagnost = 1
                                                AND a.enabled = 1 ";
                        $result3            = $db->query($conn, $sql3);
                        $total_diagnosed    = $db->counter($result3);
                        if ($total_received > 0) {
                            if ($total_diagnosed > 0) {
                                $total_diagnosed_percentage = ($total_diagnosed / $total_received) * 100;
                                if ($total_received > 0) {
                                    echo " <span class='color-green'>(" . round(($total_diagnosed_percentage)) . "%)</span>";
                                    if ($total_diagnosed_percentage < '100') { ?>
                                        <i class="material-icons dp48">warning</i>
                        <?php
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
                <a href="#tab7_html" class="<?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab7')) { echo "active";} ?>">
                    <i class="material-icons">access_time</i>
                    <span>RMA</span>
                </a>
            </li>
        <?php }
        if (po_permisions("PriceSetup") == 1) { ?>
            <li class="tab">
                <a href="#tab8_html" class="<?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab8')) { echo "active";} ?>">
                    <i class="material-icons">attach_money</i>
                    <span>Price Setup</span>
                </a>
            </li>
        <?php } ?>
        <li class="indicator" style="left: 0px; right: auto"></li>
    </ul> 
</div>
  