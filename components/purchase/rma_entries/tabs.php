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
            <a href="#tab1_html" class="<?php if (isset($active_tab) && $active_tab == 'tab1') {
                                            echo "active";
                                        } ?>">
                <i class="material-icons">receipt</i>
                <span>Sale Order</span>
            </a>
        </li>
        <li class="tab">
            <a href="#tab3_html" class="<?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab3')) {
                                            echo "active";
                                        } ?>">
                <i class="material-icons">collections_bookmark</i>
                <span>Packing
                    <?php
                        $total_items_ordered = 0;
                        $sql2       = " SELECT COUNT(a.id) as order_qty
                                        FROM sales_order_detail a
                                        WHERE a.sales_order_id = '" . $id . "'
                                        AND a.enabled = 1 ";
                        $result_r2    = $db->query($conn, $sql2);
                        $count2     = $db->counter($result_r2);
                        if ($count2 > 0) {
                            $row_lg2                = $db->fetch($result_r2);
                            $total_items_ordered    = $row_lg2[0]['order_qty'];
                        }
                        
                        $sql3               = "SELECT a.id
                                                FROM sales_order_detail_packing a
                                                WHERE a.sale_order_id = '" . $id . "'
                                                AND a.enabled = 1 ";
                        $result3            = $db->query($conn, $sql3);
                        $total_packed     = $db->counter($result3);
                        
                        if ($total_items_ordered > 0 && $total_packed > 0) {
                            $total_packed_percentage = ($total_packed / $total_items_ordered) * 100;
                            if ($total_packed_percentage > 0) {
                                echo " (" . round(($total_packed_percentage)) . "%)";
                            }
                        }
                        
                        ?>
                </span>
            </a>
        </li>
        <li class="tab">
            <a href="#tab2_html" class="<?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab2')) {
                                            echo "active";
                                        } ?>">
                <i class="material-icons">event_note</i>
                <span>Shipments
                <?php    
                    $sql3       = "SELECT a.id
                                    FROM sales_order_detail_packing a
                                    WHERE a.sale_order_id = '" . $id . "'
                                    AND a.enabled = 1 AND a.is_shipped =1 ";
                    $result3    = $db->query($conn, $sql3);
                    $total_shipped     = $db->counter($result3);
                    if ($total_items_ordered > 0 && $total_shipped > 0) {
                        $total_shipped_percentage = ($total_shipped / $total_items_ordered) * 100;
                        if ($total_shipped_percentage > 0) {
                            echo " (" . round(($total_shipped_percentage)) . "%)";
                        }
                    } 
                ?>
                </span>
            </a>
        </li>
        <li class="indicator" style="left: 0px; right: auto;"></li>
    </ul>
</div>
  