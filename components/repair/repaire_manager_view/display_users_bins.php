<div class="user-list-container">
    <div class="user-list" id="user-list">
        <?php
        $module_status = 19;
        $sql1 ="SELECT b1.order_by,b.id, b.sub_location_name, b.sub_location_type, COUNT(a.id) AS qty
                FROM product_stock a 
                INNER JOIN products a2 ON a2.id = a.product_id
                INNER JOIN warehouse_sub_locations b ON b.id = a.sub_location
                INNER JOIN users_bin_for_repair b1 ON a.sub_location = b1.location_id AND b1.is_processing_done = '0'
                WHERE a.p_total_stock > 0
                AND a.p_inventory_status = '$module_status'
                GROUP BY b.id,b1.id
                ORDER BY b1.order_by ASC";
        $result1 = $db->query($conn, $sql1);
        $count1 = $db->counter($result1);
        if ($count1 > 0 ) {
            $locations 	= array();
            $row 		= $db->fetch($result1);
            $locations 	= $row;
            foreach ($locations as $location_data) { ?>
                <?php
                $sql1 ="SELECT b.id, CONCAT(a.first_name, ' ', a.last_name) AS user_full_name, a.profile_pic,
                                b.location_id, b.bin_user_id, b2.sub_location_name, b2.sub_location_type 
                        FROM users a
                        INNER JOIN users_bin_for_repair b ON a.id = b.bin_user_id AND b.is_processing_done = '0'
                        INNER JOIN warehouse_sub_locations b2 ON b2.id = b.location_id
                        WHERE b.location_id = '".$location_data['id']."' 
                        ";
                $result2 = $db->query($conn, $sql1);
                $count2	= $db->counter($result2);
                if ($count2 > 0) {
                    $row2 	= $db->fetch($result2); 
                    foreach($row2 as $data_2){

                        $detail_id2 			= $data_2['id']; 
                        $bin_user_id 			= $data_2['bin_user_id'];
                        $location_id 			= $data_2['location_id'];
                        $sub_location_name		= $data_2['sub_location_name'];
                        $sub_location_type		= $data_2['sub_location_type'];
                        $total_estimated_time 	= 0;

                        $sql_time = "SELECT IFNULL((COUNT(a.id) / e.devices_per_user_per_day), 0) AS estimated_time
                                    FROM product_stock a 
                                    INNER JOIN  products a2 ON a2.id = a.product_id
                                    INNER JOIN product_categories a3 ON a3.id = a2.product_category
                                    INNER JOIN warehouse_sub_locations b ON b.id = a.sub_location
                                    INNER JOIN `users_bin_for_repair` d ON d.`location_id` = a.`sub_location` AND d.`is_processing_done` = 0
                                    LEFT JOIN `formula_category` e ON e.product_category = a2.product_category AND e.formula_type = 'Processing' AND e.enabled = 1
                                    WHERE 1=1
                                    AND a.p_total_stock > 0
                                    AND a.p_inventory_status = '$module_status' 
                                    AND d.bin_user_id = '$bin_user_id' 
                                    AND d.location_id = '$location_id' 
                                    GROUP BY a.sub_location, a3.category_name";
                        $result_time	= $db->query($conn, $sql_time);
                        $count_time	= $db->counter($result_time);
                        if ($count_time > 0) { 
                            $row_time = $db->fetch($result_time);	
                            foreach ($row_time as $data_time) {	
                                $total_estimated_time += $data_time['estimated_time'];?>
                        <?php }
                        }?>
                        <div style="text-align:center;" class="user" draggable="true" data-id="<?php echo $data_2['id']; ?>">
                            <img src="app-assets/images/logo/<?php echo $data_2['profile_pic']; ?>" alt="images" style="height:100px !important;" class="circle z-depth-2 responsive-img" />
                            <h6 class=" lighten-4"><?php echo $data_2['user_full_name']; ?></h6>
                            <h5 class=" lighten-4">
                                <?php echo $sub_location_name; 
                                if($sub_location_type != ""){
                                    echo "(".$sub_location_type.")";
                                }?>
                            </h5>
                            <h6 class=" lighten-4"><?php echo round($total_estimated_time, 2); ?> Days</h6>
                        </div> 
                    <?php }
                }
            } 
        }?>
    </div>
</div>