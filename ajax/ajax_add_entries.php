<?php
include('../conf/session_start.php');
include("../conf/connection.php");
include("../conf/functions.php");
$db      = new mySqlDB;
foreach ($_POST as $key => $value) {
    if (!is_array($value)) {
        $data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
        $$key = $data[$key];
    }
}
extract($_POST);
$subscriber_users_id    = $_SESSION["subscriber_users_id"];
$selected_db_name       = $_SESSION["db_name"];

switch ($type) {
    case 'add_store':
        if ($store_name != "") {
            $store_name = ucwords(strtolower($store_name));
            $count  = 0;
            $sql    = " SELECT a.* FROM stores a 
                        WHERE a.store_name	= '" . $store_name . "' ";
            $result = $db->query($conn, $sql);
            $count  = $db->counter($result);
            if ($count == 0) {
                $sql    = "INSERT INTO stores(subscriber_users_id, store_name, add_date, add_by, add_ip)
                            VALUES('" . $subscriber_users_id . "', '" . $store_name . "','" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
                $ok     = $db->query($conn, $sql);
                if ($ok) {
                    $id = mysqli_insert_id($conn);
                    echo '<option value="' . $id . '" selected="selected">' . $store_name . '</option>';
                } else {
                    echo "Fail";
                }
            } else {
                $row            = $db->fetch($result);
                $id             = $row[0]['id'];
                $store_name     = $row[0]['store_name'];
                echo '<option value="' . $id . '" selected="selected">' . $store_name . '</option>';
            }
        } else {
            echo 'Select';
        }
        break;
    case 'add_product':
        if ($product_uniqueid != "undefined" && $product_category != "undefined" && $product_uniqueid != "" && $product_category != "") {
            $count = 0;

            $table      = "product_categories";
            $columns    = array("category_name");
            $get_col_from_table = get_col_from_table($db, $conn, $selected_db_name, $table, $product_category, $columns);
            foreach ($get_col_from_table as $array_key1 => $array_data1) {
                ${$array_key1} = $array_data1;
            }

            $sql    = " SELECT a.* 
                        FROM products a 
                        WHERE a.product_uniqueid  = '" . $product_uniqueid . "' ";
            $result = $db->query($conn, $sql);
            $count  = $db->counter($result);
            if ($count == 0) {
                $sql = "INSERT INTO products(subscriber_users_id, product_desc, product_uniqueid, product_category, product_model_no, add_date, add_by, add_ip)
                        VALUES('" . $subscriber_users_id . "', '" . $product_desc . "', '" . $product_uniqueid . "', '" . $product_category . "', '" . $product_model_no . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
                $ok = $db->query($conn, $sql);
                if ($ok) {
                    $product_id = mysqli_insert_id($conn);
                    echo '<option value="' . $product_id . '" selected="selected">' . $product_uniqueid . ' (' . $category_name . ')</option>';
                } else {
                    echo "Fail";
                }
            } else {
                $row                = $db->fetch($result);
                $product_id         = $row[0]['id'];
                $product_desc       = $row[0]['product_desc'];
                $product_uniqueid   = $row[0]['product_uniqueid'];

                $text_return        = $product_uniqueid;
                $text_return        .= isset($category_name) ? " (" . $category_name . ") " : '';

                echo '<option value="' . $product_id . '" selected="selected">' . $text_return . '</option>';
            }
        } else {
            echo 'Select';
        }
        break;
    case 'add_category':
        if ($category_name != "") {
            $count  = 0;
            $sql    = " SELECT a.* FROM product_categories a  WHERE a.category_name    = '" . $category_name . "' ";
            $result = $db->query($conn, $sql);
            $count  = $db->counter($result);
            if ($count == 0) {
                $sql6 = "INSERT INTO " . $selected_db_name . ".product_categories(subscriber_users_id, category_name, category_type, add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id )
                             VALUES('" . $subscriber_users_id . "',  '" . $category_name  . "',  'Device',  '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
                $ok = $db->query($conn, $sql6);
                if ($ok) {
                    $id = mysqli_insert_id($conn);
                    echo '<option value="' . $id . '" selected="selected">' . $category_name . ' </option>';
                } else {
                    echo "Fail";
                }
            } else {
                $row    = $db->fetch($result);
                $id     = $row[0]['id'];
                $category_name  = $row[0]['category_name'];
                echo '<option value="' . $id . '" selected="selected">' . $category_name . '</option>';
            }
        } else {
            echo 'Select';
        }
        break;
    case 'add_productid':
        if ($product_id != "") {
            $count  = 0;
            $sql    = " SELECT a.* FROM product_ids a  WHERE a.product_id    = '" . $product_id . "' ";
            $result = $db->query($conn, $sql);
            $count  = $db->counter($result);
            if ($count == 0) {
                $sql6 = "INSERT INTO " . $selected_db_name . ".product_ids(subscriber_users_id, product_id, add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id )
                                VALUES('" . $subscriber_users_id . "',  '" . $product_id  . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
                $ok = $db->query($conn, $sql6);
                if ($ok) {
                    echo '<option value="' . $product_id . '" selected="selected">' . $product_id . ' </option>';
                } else {
                    echo "Fail";
                }
            } else {
                $row            = $db->fetch($result);
                $product_id     = $row[0]['product_id'];
                echo '<option value="' . $product_id . '" selected="selected">' . $product_id . '</option>';
            }
        } else {
            echo 'Select';
        }
        break;
    case 'add_customer':
        if ($customer_name != "" && $phone_primary != "") {
            $count  = 0;
            $sql    = " SELECT a.* FROM customers a WHERE a.customer_name  = '" . $customer_name . "' AND a.phone_primary  = '" . $phone_primary . "' ";
            $result = $db->query($conn, $sql);
            $count  = $db->counter($result);
            if ($count == 0) {
                $sql = "INSERT INTO customers(subscriber_users_id, customer_name, phone_primary, email_primary, 
                                            address_primary, address_primary_city, address_primary_state, address_primary_country, 
                                            add_date, add_by, add_ip, add_timezone, added_from_module_id)
                            VALUES('" . $subscriber_users_id . "', '" . $customer_name . "', '" . $phone_primary . "', '" . $email_primary . "', 
                            '" . $address_primary . "',  '" . $address_primary_city . "',  '" . $address_primary_state . "',  '" . $address_primary_country . "', 
                            '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
                $ok = $db->query($conn, $sql);
                if ($ok) {
                    $product_id = mysqli_insert_id($conn);
                    echo '<option value="' . $product_id . '" selected="selected">' . $customer_name . ' - Phone: ' . $phone_primary . '</option>';
                } else {
                    echo "Fail";
                }
            } else {
                $row                = $db->fetch($result);
                $product_id         = $row[0]['id'];
                $customer_name      = $row[0]['customer_name'];
                $phone_primary      = $row[0]['phone_primary'];
                echo '<option value="' . $product_id . '" selected="selected">' . $customer_name . ' - Phone: ' . $phone_primary . '</option>';
            }
        } else {
            echo 'Select';
        }
        break;
    case 'add_product2':
        if ($product_uniqueid != "" && $product_desc != "" && $product_category != "") {
            $product_desc = ucwords(strtolower($product_desc));
            $count = 0;

            $table              = "product_categories";
            $columns            = array("category_name");
            $get_col_from_table = get_col_from_table($db, $conn, $selected_db_name, $table, $product_category, $columns);
            foreach ($get_col_from_table as $array_key1 => $array_data1) {
                ${$array_key1} = $array_data1;
            }

            $sql    = " SELECT a.* 
                            FROM products a 
                            WHERE a.product_uniqueid  = '" . $product_uniqueid . "' ";
            $result = $db->query($conn, $sql);
            $count  = $db->counter($result);
            if ($count == 0) {
                $sql = "INSERT INTO products(subscriber_users_id, product_desc, product_uniqueid, product_category, detail_desc, add_date, add_by, add_ip)
                            VALUES('" . $subscriber_users_id . "', '" . $product_desc . "', '" . $product_uniqueid . "', '" . $product_category . "', '" . $detail_desc . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
                $ok = $db->query($conn, $sql);
                if ($ok) {
                    $product_id = mysqli_insert_id($conn);
                    echo '<option value="' . $product_id . '" selected="selected">' . $product_uniqueid . '</option>';
                } else {
                    echo "Fail";
                }
            } else {
                $row                = $db->fetch($result);
                $product_id         = $row[0]['id'];
                $product_desc       = $row[0]['product_desc'];
                $product_uniqueid   = $row[0]['product_uniqueid'];
                echo '<option value="' . $product_id . '" selected="selected">' . $product_uniqueid . '</option>';
            }
        } else {
            echo 'Select';
        }
        break;
    case 'add_vender':
        if ($vender_name != "" && $phone_no != "") {
            $vender_name = ucwords(strtolower($vender_name));
            $count  = 0;
            $sql    = " SELECT a.* FROM venders a 
                        WHERE a.vender_name	= '" . $vender_name . "'
                        AND a.phone_no		= '" . $phone_no . "'  ";
            $result = $db->query($conn, $sql);
            $count  = $db->counter($result);
            if ($count == 0) {
                $sql    = "INSERT INTO venders(subscriber_users_id, purchasing_agent_id, vender_name, phone_no,  `address`, vender_type, note_about_vender, warranty_period_in_days, add_date, add_by, add_ip)
                            VALUES('" . $subscriber_users_id . "', '" . $purchasing_agent_id . "', '" . $vender_name . "', '" . $phone_no . "', '" . $address . "', '" . $vender_type . "', '" . $note_about_vender . "', '" . $warranty_period_in_days . "','" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
                $ok     = $db->query($conn, $sql);
                if ($ok) {
                    $vender_id          = mysqli_insert_id($conn);
                    $vender_no          = "V" . $vender_id;
                    $sql6               = "UPDATE venders SET vender_no = '" . $vender_no . "' WHERE id = '" . $vender_id . "' ";
                    $db->query($conn, $sql6);
                    if ($cmd == 'edit') {
                        $sql6 = "UPDATE offers SET vender_id = '" . $vender_id . "' WHERE id = '" . $id . "' ";
                        $db->query($conn, $sql6);
                    }
                    echo '<option value="' . $vender_id . '" selected="selected">' . $vender_name . '</option>';
                } else {
                    echo "Fail";
                }
            } else {
                $row            = $db->fetch($result);
                $vender_id      = $row[0]['id'];
                $vender_name    = $row[0]['vender_name'];
                echo '<option value="' . $vender_id . '" selected="selected">' . $vender_name . '</option>';
            }
        } else {
            echo 'Select';
        }
        break;
    case 'add_agent':
        if ($agent_name != "" && $phone_no != "") {
            $agent_name = ucwords(strtolower($agent_name));
            $count  = 0;
            $sql    = " SELECT a.* FROM purchasing_agents a 
                        WHERE a.agent_name	= '" . $agent_name . "'
                        AND a.phone_no		= '" . $phone_no . "'  ";
            $result = $db->query($conn, $sql);
            $count  = $db->counter($result);
            if ($count == 0) {
                $sql    = "INSERT INTO purchasing_agents(subscriber_users_id, agent_name, phone_no, `address`, note_about_agent, add_date, add_by, add_ip)
                            VALUES('" . $subscriber_users_id . "', '" . $agent_name . "', '" . $phone_no . "', '" . $address . "', '" . $note_about_agent . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
                $ok     = $db->query($conn, $sql);
                if ($ok) {
                    $agent_id   = mysqli_insert_id($conn);
                    $agent_no   = "AG" . $agent_id;
                    $sql6       = "UPDATE purchasing_agents SET agent_no = '" . $agent_no . "' WHERE id = '" . $agent_id . "' ";
                    $db->query($conn, $sql6);
                    echo '<option value="' . $agent_id . '" selected="selected">' . $agent_name . '</option>';
                } else {
                    echo "Fail";
                }
            } else {
                $row            = $db->fetch($result);
                $agent_id      = $row[0]['id'];
                $agent_name    = $row[0]['agent_name'];
                echo '<option value="' . $agent_id . '" selected="selected">' . $agent_name . '</option>';
            }
        } else {
            echo 'Select';
        }
        break;
    case 'add_repair_type':
        if ($repair_type_name != "") {
            $repair_type_name = ucwords(strtolower($repair_type_name));
            $count  = 0;
            $sql    = " SELECT a.* FROM repair_types a WHERE a.repair_type_name	= '" . $repair_type_name . "'  ";
            $result = $db->query($conn, $sql);
            $count  = $db->counter($result);
            if ($count == 0) {
                $sql    = " INSERT INTO repair_types(subscriber_users_id, repair_type_name, add_date, add_by, add_ip)
                            VALUES('" . $subscriber_users_id . "', '" . $repair_type_name . "','" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
                $ok     = $db->query($conn, $sql);
                if ($ok) {
                    $id = mysqli_insert_id($conn);
                    echo '<option value="' . $id . '" selected="selected">' . $repair_type_name . '</option>';
                } else {
                    echo "Fail";
                }
            } else {
                $row                = $db->fetch($result);
                $id                 = $row[0]['id'];
                $repair_type_name   = $row[0]['repair_type_name'];
                echo '<option value="' . $id . '" selected="selected">' . $repair_type_name . '</option>';
            }
        } else {
            echo 'Select';
        }
        break;
    case 'add_package':

        if ($package_name != "" && $product_category != "" && $sku_code != "") {
            if (!isset($case_pack)) {
                $case_pack = "0";
            }
            if (!isset($pack_desc)) {
                $pack_desc = "";
            }
            $count = 0;

            $table              = "product_categories";
            $columns            = array("category_name");
            $get_col_from_table = get_col_from_table($db, $conn, $selected_db_name, $table, $product_category, $columns);
            foreach ($get_col_from_table as $array_key1 => $array_data1) {
                ${$array_key1} = $array_data1;
            }
            $sql    = " SELECT a.*, b.category_name
                        FROM packages a 
                        LEFT JOIN product_categories b ON b.id = a.product_category
                        WHERE a.sku_code    = '" . $sku_code . "'  ";
            $result = $db->query($conn, $sql);
            $count  = $db->counter($result);
            if ($count == 0) {
                $sql6 = "INSERT INTO " . $selected_db_name . ".packages(subscriber_users_id, product_ids,  package_name, sku_code, case_pack, pack_desc, product_category, add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id )
                         VALUES('" . $subscriber_users_id . "', '" . $product_id . "',  '" . $package_name  . "', '" . $sku_code  . "', '" . $case_pack  . "', '" . $pack_desc  . "', '" . $product_category . "',  '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
                $ok = $db->query($conn, $sql6);
                if ($ok) {
                    $package_id         = mysqli_insert_id($conn);
                    $package_no         = "Pkg" . $package_id;
                    $sql6               = "UPDATE packages SET package_no = '" . $package_no . "' WHERE id = '" . $package_id . "' ";
                    $db->query($conn, $sql6);
                    echo '<option value="' . $package_id . '" selected="selected">' . $package_name . ' (' . $category_name . ') - SKU Code: ' . $sku_code . '</option>';
                } else {
                    echo "Fail";
                }
            } else {
                $row            = $db->fetch($result);
                $package_id     = $row[0]['id'];
                $package_name   = $row[0]['package_name'];
                $category_name  = $row[0]['category_name'];
                echo '<option value="' . $package_id . '" selected="selected">' . $package_name . ' (' . $category_name . ') - SKU Code: ' . $sku_code . '</option>';
            }
        } else {
            echo 'Select';
        }
        break;
    case 'assign_bin':
        if ($bin_id != "" && $bin_id != "0") {
            $order_by       = 1;
            $sql_order      = " SELECT MAX(a.order_by) as max_order_by
                                FROM users_bin_for_processing a  
                                WHERE  a.is_processing_done	= '0'  ";
            $result_order = $db->query($conn, $sql_order);
            $count_order  = $db->counter($result_order);
            if ($count_order > 0) {
                $row_order = $db->fetch($result_order);
                $order_by = $row_order[0]['max_order_by'] + 1;
            }
            $sql     = "SELECT a.*
                        FROM users_bin_for_processing a 
                        WHERE a.location_id	= '" . $bin_id . "' 
                        AND a.is_processing_done	= '0'";
            $result = $db->query($conn, $sql);
            $count  = $db->counter($result);
            if ($count == 0) {
                $sql6 = "INSERT INTO " . $selected_db_name . ".users_bin_for_processing(subscriber_users_id, bin_user_id, location_id,order_by, add_date, add_by, add_by_user_id, add_ip, added_from_module_id )
                                VALUES('" . $subscriber_users_id . "', '" . $bin_user_id . "', '" . $bin_id . "',  '" . $order_by . "' ,'" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $module_id . "')";
                $ok = $db->query($conn, $sql6);
                if ($ok) {
                    include('../components/processing/process_manager_view/display_users_bins.php');
                } else {
                    echo "Fail";
                }
            } else {
                if ($bin_user_id == "" || $bin_user_id == "0") {
                    $sql6 = "DELETE FROM " . $selected_db_name . ".users_bin_for_processing 
                             WHERE location_id = '$bin_id '
                             AND is_processing_done = 0 ";
                    $ok = $db->query($conn, $sql6);
                    if ($ok) {
                        echo '<span id="removedBin">Removed</span>';
                        include('../components/processing/process_manager_view/display_users_bins.php');
                    }
                } else {
                    $sql6 = "UPDATE " . $selected_db_name . ".users_bin_for_processing SET  bin_user_id             = '" . $bin_user_id . "', 
                                                                                            update_date             = '" . $add_date . "', 
                                                                                            update_by               = '" . $_SESSION['username'] . "', 
                                                                                            update_by_user_id       = '" . $_SESSION['user_id'] . "', 
                                                                                            update_ip               = '" . $add_ip . "',
                                                                                            update_from_module_id   = '" . $module_id . "'
                            WHERE location_id = '$bin_id '
                            AND is_processing_done = 0 ";
                    $ok = $db->query($conn, $sql6);
                    if ($ok) {
                        include('../components/processing/process_manager_view/display_users_bins.php');
                    } else {
                        echo "Fail";
                    }
                }
            }
        } else {
            if ($bin_id != "" && $bin_id != "0" && $bin_user_id == "0" && $bin_user_id == "") {
                $sql6 = "UPDATE " . $selected_db_name . ".users_bin_for_processing SET  bin_user_id             = '" . $bin_user_id . "',
                                                                                        bin_has_assigned        = '0', 
                                                                                        update_date             = '" . $add_date . "', 
                                                                                        update_by               = '" . $_SESSION['username'] . "', 
                                                                                        update_by_user_id       = '" . $_SESSION['user_id'] . "', 
                                                                                        update_ip               = '" . $add_ip . "',
                                                                                        update_from_module_id   = '" . $module_id . "'
                        WHERE location_id = '$bin_id '
                        AND is_processing_done = 0 ";
                $ok = $db->query($conn, $sql6);
                if ($ok) {
                    echo "unassigned";
                }
            }
            echo 'Select';
        }
        break;
    case 'update_order':
        $user_ids = explode(",", $user_ids);
        if (isset($user_ids)) {
            foreach ($user_ids as $order => $id) {
                $order = ($order + 1);
                $sql6 = "UPDATE users_bin_for_processing SET order_by = $order WHERE id = '$id' AND is_processing_done = 0";
                $ok = $db->query($conn, $sql6);
            }
            if ($ok) {
                echo "Success";
            }
        }
        break;
    case 'assign_bin_repair':
        if ($bin_id != "" && $bin_id != "0") {
            $order_by       = 1;
            $sql_order      = " SELECT MAX(a.order_by) as max_order_by
                                FROM users_bin_for_repair a  
                                WHERE  a.is_processing_done	= '0'  ";
            $result_order = $db->query($conn, $sql_order);
            $count_order  = $db->counter($result_order);
            if ($count_order > 0) {
                $row_order = $db->fetch($result_order);
                $order_by = $row_order[0]['max_order_by'] + 1;
            }
            $sql     = "SELECT a.*
                        FROM users_bin_for_repair a 
                        WHERE a.location_id	= '" . $bin_id . "' 
                        AND a.is_processing_done	= '0'";
            $result = $db->query($conn, $sql);
            $count  = $db->counter($result);
            if ($count == 0) {
                $sql6 = "INSERT INTO " . $selected_db_name . ".users_bin_for_repair(subscriber_users_id, bin_user_id, location_id,order_by, add_date, add_by, add_by_user_id, add_ip, added_from_module_id )
                                VALUES('" . $subscriber_users_id . "', '" . $bin_user_id . "', '" . $bin_id . "',  '" . $order_by . "' ,'" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $module_id . "')";
                $ok = $db->query($conn, $sql6);
                if ($ok) {
                    include('../components/repair/repaire_manager_view/display_users_bins.php');
                } else {
                    echo "Fail";
                }
            } else {
                if ($bin_user_id == "" || $bin_user_id == "0") {
                    $sql6 = "DELETE FROM " . $selected_db_name . ".users_bin_for_repair 
                             WHERE location_id = '$bin_id '
                             AND is_processing_done = 0 ";
                    $ok = $db->query($conn, $sql6);
                    if ($ok) {
                        echo '<span id="removedBin">Removed</span>';
                        include('../components/repair/repaire_manager_view/display_users_bins.php');
                    }
                } else {
                    $sql6 = "UPDATE " . $selected_db_name . ".users_bin_for_repair SET  bin_user_id             = '" . $bin_user_id . "', 
                                                                                            update_date             = '" . $add_date . "', 
                                                                                            update_by               = '" . $_SESSION['username'] . "', 
                                                                                            update_by_user_id       = '" . $_SESSION['user_id'] . "', 
                                                                                            update_ip               = '" . $add_ip . "',
                                                                                            update_from_module_id   = '" . $module_id . "'
                            WHERE location_id = '$bin_id '
                            AND is_processing_done = 0 ";
                    $ok = $db->query($conn, $sql6);
                    if ($ok) {
                        include('../components/repair/repaire_manager_view/display_users_bins.php');
                    } else {
                        echo "Fail";
                    }
                }
            }
        } else {
            if ($bin_id != "" && $bin_id != "0" && $bin_user_id == "0" && $bin_user_id == "") {
                $sql6 = "UPDATE " . $selected_db_name . ".users_bin_for_repair SET  bin_user_id             = '" . $bin_user_id . "',
                                                                                        bin_has_assigned        = '0', 
                                                                                        update_date             = '" . $add_date . "', 
                                                                                        update_by               = '" . $_SESSION['username'] . "', 
                                                                                        update_by_user_id       = '" . $_SESSION['user_id'] . "', 
                                                                                        update_ip               = '" . $add_ip . "',
                                                                                        update_from_module_id   = '" . $module_id . "'
                        WHERE location_id = '$bin_id '
                        AND is_processing_done = 0 ";
                $ok = $db->query($conn, $sql6);
                if ($ok) {
                    echo "unassigned";
                }
            }
            echo 'Select';
        }
        break;
    case 'update_order_repair':
        $user_ids = explode(",", $user_ids);
        if (isset($user_ids)) {
            foreach ($user_ids as $order => $id) {
                $order = ($order + 1);
                $sql6 = "UPDATE users_bin_for_repair SET order_by = $order WHERE id = '$id' AND is_processing_done = 0";
                $ok = $db->query($conn, $sql6);
            }
            if ($ok) {
                echo "Success";
            }
        }
        break;
    case 'vendor_get_warranty_period_days':
        if (isset($vender_id)) {
            $sql_v = "SELECT * FROM venders WHERE id = '" . $vender_id . "' ";
            $result_v    = $db->query($conn, $sql_v);
            $count_v  = $db->counter($result_v);
            if ($count_v > 0) {
                $row_v        = $db->fetch($result_v);
                $warranty_period_in_days = $row_v[0]['warranty_period_in_days'];
            }
            echo  $warranty_period_in_days;
        } else {
            echo  "0";
        }
        break;
    case 'assign_bin_diagnostic':
        if ($bin_id != "" && $bin_id != "0") {
            $order_by       = 1;
            $sql_order      = " SELECT MAX(a.order_by) as max_order_by
                                FROM users_bin_for_diagnostic a  
                                WHERE  a.is_processing_done	= '0'  ";
            $result_order = $db->query($conn, $sql_order);
            $count_order  = $db->counter($result_order);
            if ($count_order > 0) {
                $row_order = $db->fetch($result_order);
                $order_by = $row_order[0]['max_order_by'] + 1;
            }
            $sql     = "SELECT a.*
                        FROM users_bin_for_diagnostic a 
                        WHERE a.location_id	= '" . $bin_id . "' 
                        AND a.is_processing_done	= '0'";
            $result = $db->query($conn, $sql);
            $count  = $db->counter($result);
            if ($count == 0) {
                $sql6 = "INSERT INTO " . $selected_db_name . ".users_bin_for_diagnostic(subscriber_users_id, bin_user_id, location_id,order_by, add_date, add_by, add_by_user_id, add_ip, added_from_module_id )
                                VALUES('" . $subscriber_users_id . "', '" . $bin_user_id . "', '" . $bin_id . "',  '" . $order_by . "' ,'" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $module_id . "')";
                $ok = $db->query($conn, $sql6);
                if ($ok) {
                    $id                = mysqli_insert_id($conn);
                    $assignment_no  = "A" . $id;

                    $sql_dup    = " SELECT a.* FROM users_bin_for_diagnostic a WHERE  assignment_no	= '" . $assignment_no . "' ";
                    $result_dup    = $db->query($conn, $sql_dup);
                    $count_dup    = $db->counter($result_dup);
                    if ($count_dup > 0) {
                        $assignment_no  = "A" . $assignment_no;
                        $sql_dup    = " SELECT a.* FROM users_bin_for_diagnostic a WHERE  assignment_no	= '" . $assignment_no . "' ";
                        $result_dup    = $db->query($conn, $sql_dup);
                        $count_dup    = $db->counter($result_dup);
                        if ($count_dup > 0) {
                            $assignment_no  = "AA" . $assignment_no;
                        }
                    }
                    $sql6 = " UPDATE users_bin_for_diagnostic SET assignment_no = '" . $assignment_no . "' WHERE id = '" . $id . "' ";
                    $db->query($conn, $sql6);
                    include('../components/diagnostic/diagnostic_manager_view/display_users_bins.php');
                } else {
                    echo "Fail";
                }
            } else {
                if ($bin_user_id == "" || $bin_user_id == "0") {
                    $sql6 = "DELETE FROM " . $selected_db_name . ".users_bin_for_diagnostic 
                             WHERE location_id = '$bin_id '
                             AND is_processing_done = 0 ";
                    $ok = $db->query($conn, $sql6);
                    if ($ok) {
                        echo '<span id="removedBin">Removed</span>';
                        include('../components/diagnostic/diagnostic_manager_view/display_users_bins.php');
                    }
                } else {
                    $sql6 = "UPDATE " . $selected_db_name . ".users_bin_for_diagnostic SET  bin_user_id             = '" . $bin_user_id . "', 
                                                                                            update_date             = '" . $add_date . "', 
                                                                                            update_by               = '" . $_SESSION['username'] . "', 
                                                                                            update_by_user_id       = '" . $_SESSION['user_id'] . "', 
                                                                                            update_ip               = '" . $add_ip . "',
                                                                                            update_from_module_id   = '" . $module_id . "'
                            WHERE location_id = '$bin_id '
                            AND is_processing_done = 0 ";
                    $ok = $db->query($conn, $sql6);
                    if ($ok) {
                        include('../components/diagnostic/diagnostic_manager_view/display_users_bins.php');
                    } else {
                        echo "Fail";
                    }
                }
            }
        } else {
            if ($bin_id != "" && $bin_id != "0" && $bin_user_id == "0" && $bin_user_id == "") {
                $sql6 = "UPDATE " . $selected_db_name . ".users_bin_for_processing SET  bin_user_id             = '" . $bin_user_id . "',
                                                                                        bin_has_assigned        = '0', 
                                                                                        update_date             = '" . $add_date . "', 
                                                                                        update_by               = '" . $_SESSION['username'] . "', 
                                                                                        update_by_user_id       = '" . $_SESSION['user_id'] . "', 
                                                                                        update_ip               = '" . $add_ip . "',
                                                                                        update_from_module_id   = '" . $module_id . "'
                        WHERE location_id = '$bin_id '
                        AND is_processing_done = 0 ";
                $ok = $db->query($conn, $sql6);
                if ($ok) {
                    echo "unassigned";
                }
            }
            echo 'Select';
        }
        break;
    case 'update_order_diagnostic':
        $user_ids = explode(",", $user_ids);
        if (isset($user_ids)) {
            foreach ($user_ids as $order => $id) {
                $order = ($order + 1);
                $sql6 = "UPDATE users_bin_for_diagnostic SET order_by = $order WHERE id = '$id' AND is_processing_done = 0";
                $ok = $db->query($conn, $sql6);
            }
            if ($ok) {
                echo "Success";
            }
        }
        break;
    case 'get_case_pack':
        if (isset($package_id) && $package_id != "" && $package_id != "0") {
            $sql_order      = " SELECT case_pack
                                FROM packages   
                                WHERE  id	= '" . $package_id . "'  ";
            $result_order = $db->query($conn, $sql_order);
            $count_order  = $db->counter($result_order);
            if ($count_order > 0) {
                $row_order = $db->fetch($result_order);
                echo $row_order[0]['case_pack'];
            }
        } else {
            echo "";
        }
    case 'get_pkg_stock_of_product':
        if (isset($product_id) && $product_id != "" && $product_id != "0") {
            $sql_order      = " SELECT IFNULL(SUM(stock_in_hand), 0) AS stock_in_hand
                                FROM packages   
                                WHERE FIND_IN_SET('" . $product_id . "', product_ids) ";
            $result_order = $db->query($conn, $sql_order);
            $count_order  = $db->counter($result_order);
            if ($count_order > 0) {
                $row_order = $db->fetch($result_order);
                echo $row_order[0]['stock_in_hand'];
            } else {
                echo "0";
            }
        } else {
            echo "";
        }
        break;
    case 'update_po_stage_status':
        if (isset($stage_status) && $stage_status != "" && $stage_status != "0" && isset($id) && $id != "" && $id != "0") {
            $sql6 = "UPDATE " . $selected_db_name . ".purchase_orders SET  stage_status             = '" . $stage_status . "',
                                                                                    
                                                                            update_date             = '" . $add_date . "', 
                                                                            update_by               = '" . $_SESSION['username'] . "', 
                                                                            update_by_user_id       = '" . $_SESSION['user_id'] . "', 
                                                                            update_ip               = '" . $add_ip . "',
                                                                            update_from_module_id   = '" . $module_id . "'
            WHERE id = '$id ' ";
            $ok = $db->query($conn, $sql6);
            if ($ok) {
                echo "Success";
            }
        } else {
            echo "Fail";
        }
        break;
    case 'update_so_stage_status':
        if (isset($stage_status) && $stage_status != "" && $stage_status != "0" && isset($id) && $id != "" && $id != "0") {
            $sql6 = "UPDATE " . $selected_db_name . ".sales_orders SET  stage_status             = '" . $stage_status . "',
                                                                                    
                                                                            update_date             = '" . $add_date . "', 
                                                                            update_by               = '" . $_SESSION['username'] . "', 
                                                                            update_by_user_id       = '" . $_SESSION['user_id'] . "', 
                                                                            update_ip               = '" . $add_ip . "',
                                                                            update_from_module_id   = '" . $module_id . "'
            WHERE id = '$id ' ";
            $ok = $db->query($conn, $sql6);
            if ($ok) {
                echo "Success";
            }
        } else {
            echo "Fail";
        }
        break;
    case 'update_ro_stage_status':
        if (isset($stage_status) && $stage_status != "" && $stage_status != "0" && isset($id) && $id != "" && $id != "0") {
            $sql6 = "UPDATE " . $selected_db_name . ".returns SET  stage_status             = '" . $stage_status . "',
                                                                                    
                                                                            update_date             = '" . $add_date . "', 
                                                                            update_by               = '" . $_SESSION['username'] . "', 
                                                                            update_by_user_id       = '" . $_SESSION['user_id'] . "', 
                                                                            update_ip               = '" . $add_ip . "',
                                                                            update_from_module_id   = '" . $module_id . "'
            WHERE id = '$id ' ";
            $ok = $db->query($conn, $sql6);
            if ($ok) {
                echo "Success";
            }
        } else {
            echo "Fail";
        }
        break;
    case 'update_ppo_stage_status':
        if (isset($stage_status) && $stage_status != "" && $stage_status != "0" && isset($id) && $id != "" && $id != "0") {
            $sql6 = "UPDATE " . $selected_db_name . ".package_materials_orders SET  stage_status             = '" . $stage_status . "',
                                                                                    
                                                                            update_date             = '" . $add_date . "', 
                                                                            update_by               = '" . $_SESSION['username'] . "', 
                                                                            update_by_user_id       = '" . $_SESSION['user_id'] . "', 
                                                                            update_ip               = '" . $add_ip . "',
                                                                            update_from_module_id   = '" . $module_id . "'
            WHERE id = '$id ' ";
            $ok = $db->query($conn, $sql6);
            if ($ok) {
                echo "Success";
            }
        } else {
            echo "Fail";
        }
        break;
    case 'update_product_modelno':
        if ((isset($modalNo) && $modalNo != "" && $modalNo != "0") && (isset($product_id) && $product_id != "" && $product_id != "0")) {
            $sql6 = "UPDATE     products SET    product_model_no            = '" . $modalNo . "',
                                                    update_date             = '" . $add_date . "', 
                                                    update_by               = '" . $_SESSION['username'] . "', 
                                                    update_by_user_id       = '" . $_SESSION['user_id'] . "', 
                                                    update_ip               = '" . $add_ip . "',
                                                    update_from_module_id   = '" . $module_id . "'
                    WHERE product_uniqueid = '" . $product_id . "' ";
            $ok = $db->query($conn, $sql6);
            if ($ok) {
                echo "Success";
            }
        } else {
            echo "Fail";
        }
        break;

    case 'recevie_using_barcode':
        $error = 0;
        if (!isset($sub_location_id_barcode) || (isset($sub_location_id_barcode)  && ($sub_location_id_barcode == "0" || $sub_location_id_barcode == ""))) {
            echo " Location is Required";
            $error = 1;
        } else {
            $sql_rc2            = " SELECT a.* 
                                    FROM purchase_order_detail_receive a 
                                    WHERE a.enabled 		= 1
                                    AND ((a.received_during != 'BarCodeReceive' AND a.is_diagnost = '0') || (a.received_during = 'BarCodeReceive'))
                                    AND a.sub_location_id 	= '" . $sub_location_id_barcode . "' ";
            $result_rc2         = $db->query($conn, $sql_rc2);
            $total_received2    = $db->counter($result_rc2);
            $bin_capacity_rc1     = bin_item_count($db, $conn, $sub_location_id_barcode);
            if (($total_received2 + 1) > $bin_capacity_rc1) {
                echo " More than Capacity " . $bin_capacity_rc1;
                $error = 1;
            }
        }
        if (!isset($serial_no_barcode) || (isset($serial_no_barcode)  && ($serial_no_barcode == "0" || $serial_no_barcode == ""))) {
            echo " Serial# is Required";
            $error = 1;
        }
        if ($error == 0) {
            $vd_defects_or_notes = "";
            $sql_pd01_4        = "	SELECT  c.id, a.overall_grade, a.status, a.defects_or_notes
                                    FROM vender_po_data a 
                                    INNER JOIN products b ON b.product_uniqueid = a.product_uniqueid
                                    LEFT JOIN inventory_status d ON d.status_name = a.status AND d.enabled = 1
                                    INNER JOIN purchase_order_detail c ON 	c.product_id 			= b.id 
                                                                            AND IFNULL(a.overall_grade, '') = IFNULL(c.product_condition, '')  
                                                                            AND IFNULL(a.price, '0') = IFNULL(c.order_price, '0')  
                                                                            AND c.expected_status	= IFNULL(d.id, 0)
                                    WHERE a.enabled = 1 
                                    AND c.enabled	= 1
                                    AND c.po_id = '" . $id . "'
                                    AND a.serial_no = '" . $serial_no_barcode . "'
                                    ORDER BY a.id DESC LIMIT 1 ";
            $result_pd01_4    = $db->query($conn, $sql_pd01_4);
            $count_pd01_4    = $db->counter($result_pd01_4);
            if ($count_pd01_4 > 0) {
                $row_pd01_4                 = $db->fetch($result_pd01_4);
                $product_id_barcode         = $row_pd01_4[0]['id'];
                $c_product_condition1       = $row_pd01_4[0]['overall_grade'];
                $c_product_status1          = $row_pd01_4[0]['status'];
                $vd_defects_or_notes        = $row_pd01_4[0]['defects_or_notes'];

                $vender_d_status            = $c_product_status1;
                $vender_d_overall_grade     = $c_product_condition1;

                if ($vender_d_overall_grade == 'D' || $vender_d_status == 'Defective') {
                    $sql_pd01_4        = "	SELECT  a.id
                                            FROM purchase_order_detail_receive a 
                                            WHERE a.enabled = 1 
                                            AND a.po_id = '" . $id . "'
                                            AND a.inventory_status = '5' 
                                            AND a.sub_location_id = '" . $sub_location_id_barcode . "'  ";
                    $result_pd01_4    = $db->query($conn, $sql_pd01_4);
                    $count_pd01_4    = $db->counter($result_pd01_4);
                    if ($count_pd01_4 > 0) {
                        echo "The Serial# is D, Select another Bin";
                        $error = 1;
                    }
                } else {
                    $sql_pd01_4        = "	SELECT  a.id
                                            FROM purchase_order_detail_receive a 
                                            WHERE a.enabled = 1 
                                            AND a.po_id = '" . $id . "'
                                            AND a.inventory_status = '6' 
                                            AND a.sub_location_id = '" . $sub_location_id_barcode . "'  ";
                    $result_pd01_4    = $db->query($conn, $sql_pd01_4);
                    $count_pd01_4    = $db->counter($result_pd01_4);
                    if ($count_pd01_4 > 0) {
                        echo "The Serial# is " . $vender_d_status . ", Select another Bin.";
                        $error = 1;
                    }
                }
            } else if ($count_pd01_4 == 0) {
                echo " Serial#  does not match in vendor data";
                $error = 1;
            }
        }
        if ($error == 0) {

            $k = 0;
            $sql_ee1 = "SELECT a.* FROM purchase_order_detail_receive a 
                        INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id AND b.enabled	= 1
                        WHERE a.enabled = 1 
                        AND ( 
                            b.po_id = '" . $id . "'
                            AND a.serial_no_barcode = '" . $serial_no_barcode . "'
                        ) ";
            // echo $sql_ee1;
            $result_ee1     = $db->query($conn, $sql_ee1);
            $counter_ee1    = $db->counter($result_ee1);
            if ($counter_ee1 == 0) {

                $product_uniqueid_main1 = "";
                $package_id1 = $package_material_qty1 = $package_material_qty_received1 = 0;

                $sql_pd3        = "	SELECT  a.product_id, a.product_condition, c.product_uniqueid, a.order_price,a.expected_status,
                                            a2.logistics_cost, a2.is_tested_po, a2.is_wiped_po, a2.is_imaged_po, c.product_category
                                    FROM purchase_order_detail a 
                                    INNER JOIN products c ON c.id = a.product_id
                                    INNER JOIN purchase_orders a2 ON a2.id = a.po_id
                                    WHERE 1 = 1
                                    AND a.enabled	= 1
                                    AND a.id 	= '" . $product_id_barcode . "'";
                // echo "<br><br>".$sql_pd3;
                $result_pd3        = $db->query($conn, $sql_pd3);
                $count_pd3        = $db->counter($result_pd3);
                if ($count_pd3 > 0) {
                    $row_pd3                            = $db->fetch($result_pd3);
                    $order_price                        = $row_pd3[0]['order_price'];
                    $product_category_brc               = $row_pd3[0]['product_category'];
                    $po_logistic_cost1                  = $row_pd3[0]['logistics_cost'];
                    $product_uniqueid_main1             = $row_pd3[0]['product_uniqueid'];
                    $c_product_id2                      = $row_pd3[0]['product_id'];
                    $c_product_condition2               = $row_pd3[0]['product_condition'];
                    $c_expected_status2                 = $row_pd3[0]['expected_status'];

                    if (isset($c_product_condition1) && ($c_product_condition1 == "A" || $c_product_condition1 == "B" || $c_product_condition1 == "C" || $c_product_condition1 == "D")) {
                        $c_product_condition2 = $c_product_condition1;
                    }
                    if (isset($c_product_status1) && $c_product_status1 != "") {
                        $sql1        = "SELECT * FROM inventory_status WHERE status_name = '" . $c_product_status1 . "' ";
                        $result_st1    = $db->query($conn, $sql1);
                        $count_st1        = $db->counter($result_st1);
                        if ($count_st1 > 0) {
                            $row_st1                 = $db->fetch($result_st1);
                            $c_expected_status2     = $row_st1[0]['id'];
                        }
                    }
                    // Only if Diagnostic bypass
                    // edit_lock, is_import_diagnostic_data, is_diagnostic_bypass

                    $item_logistic_cost         = round(po_logistic_cost_product_added($db, $conn, $id, $po_logistic_cost1), 2);
                    $item_receive_labor_cost    = round(signle_device_receive_labor_cost($db, $conn, $_SESSION['user_id'], $product_category_brc), 2);
                    $new_order_price            = round(($order_price + $item_logistic_cost + $item_receive_labor_cost), 2);

                    $sql6 = "INSERT INTO purchase_order_detail_receive(po_id, po_detail_id, serial_no_barcode, price, logistic_cost, receiving_labor, 
                                                                        inventory_status, overall_grade, received_during, edit_lock, is_import_diagnostic_data, is_diagnostic_bypass, 
                                                                        defects_or_notes, sub_location_id, is_diagnost,  
                                                                        add_by_user_id, add_date,  add_by, add_ip, add_timezone)
                                VALUES('" . $id . "', '" . $product_id_barcode . "',  '" . $serial_no_barcode . "', '" . $new_order_price . "', '" . $item_logistic_cost . "', '" . $item_receive_labor_cost . "', 
                                            '" . $c_expected_status2 . "', '" . $c_product_condition2 . "', 'BarCodeReceive', 1, 1, 1, 
                                        '" . $vd_defects_or_notes . "', '" . $sub_location_id_barcode . "', '1',  
                                        '" . $_SESSION['user_id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
                    $ok = $db->query($conn, $sql6);
                    if ($ok) {

                        $receive_id = mysqli_insert_id($conn);
                        /////////////////////////// Create Stock  START /////////////////////////////
                        $sql6 = "INSERT INTO product_stock( subscriber_users_id, receive_id, serial_no, is_final_pricing, price,
                                                            product_id, p_total_stock, stock_grade,  p_inventory_status, sub_location,  
                                                            add_by_user_id, add_date, add_by, add_ip, add_timezone)
                                    VALUES('" . $subscriber_users_id . "', '" . $receive_id . "', '" . $serial_no_barcode . "', 1, '" . $new_order_price . "',
                                    '" . $c_product_id2 . "', 1, '" . $c_product_condition2 . "', '" . $c_expected_status2 . "', '" . $sub_location_id_barcode . "',
                                    '" . $_SESSION['user_id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
                        $db->query($conn, $sql6);

                        $status_not_in = "5";
                        update_po_detail_status($db, $conn, $product_id_barcode, $receive_status_dynamic, $status_not_in);
                        update_po_status($db, $conn, $id, $receive_status_dynamic, $status_not_in);

                        $disp_status_name = get_status_name($db, $conn, $receive_status_dynamic);
                        echo  "1";
                    }
                }
            } else {
                echo  "The record is already exist";
            }
        }
        break;

    case 'process_fetch_data_barcode':
        $error      = 0;
        $field_name = "diagnostic_fetch_id";
        if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
            echo " Product is Required";
            $error = 1;
        }
        $field_name = "sub_location_id_fetched";
        if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
            echo " Location is Required";
            $error = 1;
        } else {
            $sql_rc2            = " SELECT a.* 
                                    FROM purchase_order_detail_receive a 
                                    WHERE a.enabled 						= 1
                                    AND a.po_id 							= '" . $id . "'
                                    AND a.sub_location_id_after_diagnostic 	= '" . ${$field_name} . "' ";
            $result_rc2         = $db->query($conn, $sql_rc2);
            $total_received2    = $db->counter($result_rc2);
            $bin_capacity_rc1     = bin_item_count($db, $conn, ${$field_name});
            if (($total_received2 + 1) > $bin_capacity_rc1) {
                echo " More than Capacity " . $bin_capacity_rc1;
                $error = 1;
            }
        }
        if ($error == 0) {
            $sql_pd01       = "	SELECT a.* 
                                FROM purchase_order_detail_receive_diagnostic_fetch a 
                                WHERE a.enabled = 1  
                                AND a.id	= '" . $diagnostic_fetch_id . "' ";
            $result_pd01    = $db->query($conn, $sql_pd01);
            $count_pd01     = $db->counter($result_pd01);
            if ($count_pd01 > 0) {
                $row_pd01                           = $db->fetch($result_pd01);
                $po_detail_id1                      = $row_pd01[0]['po_detail_id'];
                $product_id_not_in_po               = $row_pd01[0]['product_id_not_in_po'];
                $product_category_diagn             = $row_pd01[0]['product_category'];
                $data                               = $row_pd01[0]['serial_no'];
                $model_no                           = $row_pd01[0]['model_no'];
                $product_assignment_id              = $row_pd01[0]['assignment_id'];

                $sql_pd01_4         = "	SELECT  a.*
                                        FROM phone_check_api_data a 
                                        WHERE a.enabled = 1 
                                        AND a.imei_no = '" . $data . "'
                                        ORDER BY a.id DESC LIMIT 1";
                $result_pd01_4      = $db->query($conn, $sql_pd01_4);
                $count_pd01_4       = $db->counter($result_pd01_4);
                if ($count_pd01_4 > 0) {
                    $row_pd01_4 = $db->fetch($result_pd01_4);

                    include("../components/purchase/purchase_orders/db_phone_check_api_data.php");
                    include("../components/purchase/purchase_orders/overall_grade_calculation.php");

                    if ($overall_grade == 'D') {
                        $inventory_status = '6';
                    }

                    $sql_pd01       = "	SELECT a.*
                                        FROM purchase_order_detail_receive a 
                                        WHERE a.enabled = 1  
                                        AND a.serial_no_barcode	= '" . $data . "' ";
                    $result_pd01    = $db->query($conn, $sql_pd01);
                    $count_pd01     = $db->counter($result_pd01);
                    if ($count_pd01 == 0) {

                        $item_diagnostic_labor_cost = round(signle_device_diagnostic_labor_cost($db, $conn, $_SESSION['user_id'], $product_category_diagn), 2);

                        $sql_pd01       = "	SELECT a.* 
                                            FROM purchase_order_detail_receive a 
                                            INNER JOIN users_bin_for_diagnostic b ON b.location_id = a.sub_location_id
                                            WHERE a.enabled 				= 1
                                            AND a.po_id 					= '" . $id . "'
                                            AND b.id 						= '" . $product_assignment_id . "'
                                            AND a.recevied_product_category = '" . $product_category_diagn . "'
                                            AND (a.serial_no_barcode IS NULL OR a.serial_no_barcode = '')
                                            LIMIT 1 "; // echo "<br><br>" . $sql_pd01;
                        $result_pd01    = $db->query($conn, $sql_pd01);
                        $count_pd01     = $db->counter($result_pd01);
                        if ($count_pd01 > 0) {
                            $row_pd01        = $db->fetch($result_pd01);
                            $receive_id_2     = $row_pd01[0]['id'];
                        } else {
                            $sql = "INSERT INTO purchase_order_detail_receive(po_id, assignment_id, recevied_product_category, product_id, receive_type, diagnostic_labor, sub_location_id, sub_location_id_after_diagnostic, add_by_user_id, add_date, add_by, add_ip, add_timezone, added_from_module_id)
                                    VALUES('" . $id . "' , '" . $product_assignment_id . "' , '" . $product_category_diagn . "' , '" . $product_id_not_in_po . "' , 'CateogryReceived' , '" . $item_diagnostic_labor_cost . "' , '" . $sub_location_id_fetched . "',  '" . $sub_location_id_fetched . "',  '" . $_SESSION['user_id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
                            $db->query($conn, $sql);
                            // echo "<br><br>" . $sql;
                            $receive_id_2 = mysqli_insert_id($conn);
                        }

                        $update_product_id = "";
                        if ($po_detail_id1 == '0' && $product_id_not_in_po > 0) {
                            $update_product_id = " product_id = " . $product_id_not_in_po . ", ";
                        }

                        $sql_c_up = "UPDATE  purchase_order_detail_receive SET 	
                                                                                po_detail_id						= '" . $po_detail_id1 . "', 
                                                                                assignment_id						= '" . $product_assignment_id . "', 
                                                                                serial_no_barcode					= '" . $data . "', 
                                                                                diagnostic_labor					= '" . $item_diagnostic_labor_cost . "', 
                                                                                " . $update_product_id . "

                                                                                phone_check_api_data				= '" . $jsonData2 . "',
                                                                                model_name							= '" . $model_name . "',
                                                                                make_name							= '" . $make_name . "',
                                                                                model_no							= '" . $model_no . "',
                                                                                carrier_name						= '" . $carrier_name . "',
                                                                                color_name							= '" . $color_name . "',
                                                                                battery								= '" . $battery . "',
                                                                                body_grade	           	 			= '" . $body_grade . "',
                                                                                lcd_grade							= '" . $lcd_grade . "',
                                                                                digitizer_grade	        			= '" . $digitizer_grade . "',
                                                                                ram									= '" . $ram . "',
                                                                                storage								= '" . $memory . "',
                                                                                defects_or_notes					= '" . $defectsCode . "',
                                                                                overall_grade		    			= '" . $overall_grade . "', 
                                                                                mdm		                			= '" . $mdm . "',
                                                                                failed		            			= '" . $failed . "',
                                                                                inventory_status					= '" . $inventory_status . "', 
                                                                                sku_code							= '" . $sku_code . "',

                                                                                sub_location_id_after_diagnostic	= '" . $sub_location_id_fetched . "',
                                                                                is_diagnost							= '1',
                                                                                is_import_diagnostic_data			= '1',

                                                                                diagnose_by_user					= '" . $_SESSION['username'] . "',
                                                                                diagnose_by_user_id					= '" . $_SESSION['user_id'] . "',
                                                                                diagnose_timezone					= '" . $timezone . "',
                                                                                diagnose_date						= '" . $add_date . "',
                                                                                diagnose_ip							= '" . $add_ip . "',

                                                                                update_timezone		   	 			= '" . $timezone . "',
                                                                                update_date			    			= '" . $add_date . "',
                                                                                update_by_user_id	   	 			= '" . $_SESSION['user_id'] . "',
                                                                                update_by			    			= '" . $_SESSION['username'] . "',
                                                                                update_ip			    			= '" . $add_ip . "',
                                                                                update_from_module_id				= '" . $module_id . "'
                                WHERE id = '" . $receive_id_2 . "' ";
                        $ok = $db->query($conn, $sql_c_up);
                        if ($ok) {
                            $sql_c_up = "UPDATE  purchase_order_detail_receive_diagnostic_fetch SET 	
                                                    is_processed			= '1',
                                                    update_timezone			= '" . $timezone . "',
                                                    update_date				= '" . $add_date . "',
                                                    update_by				= '" . $_SESSION['username'] . "',
                                                    update_ip 				= '" . $add_ip . "',
                                                    update_from_module_id	= '" . $module_id . "'
                                    WHERE id = '" . $diagnostic_fetch_id . "' ";
                            $db->query($conn, $sql_c_up);

                            update_po_detail_status($db, $conn, $po_detail_id1, $diagnost_status_dynamic, "");
                            update_po_status($db, $conn, $id, $diagnost_status_dynamic, "");
                            $disp_status_name = get_status_name($db, $conn, $diagnost_status_dynamic);
                        }
                        echo "1";
                    } else {
                        echo  "The record already processed";
                    }
                }
            } else {
                echo  "The record does not exist";
            }
        }
        break;
}
