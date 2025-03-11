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
                    echo '<option value="' . $product_id . '" selected="selected">' . $product_desc . ' (' . $category_name . ') - ' . $product_uniqueid . '</option>';
                } else {
                    echo "Fail";
                }
            } else {
                $row                = $db->fetch($result);
                $product_id         = $row[0]['id'];
                $product_desc       = $row[0]['product_desc'];
                $product_uniqueid   = $row[0]['product_uniqueid'];
                echo '<option value="' . $product_id . '" selected="selected">' . $product_desc . ' (' . $category_name . ') - ' . $product_uniqueid . '</option>';
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
            $sql = "    SELECT a.* FROM venders a 
                        WHERE a.vender_name	= '" . $vender_name . "'
                        AND a.phone_no		= '" . $phone_no . "'  ";
            $result = $db->query($conn, $sql);
            $count  = $db->counter($result);
            if ($count == 0) {
                $sql    = "INSERT INTO venders(subscriber_users_id, vender_name, phone_no, `address`, note_about_vender,warranty_period_in_days, add_date, add_by, add_ip)
                            VALUES('" . $subscriber_users_id . "', '" . $vender_name . "', '" . $phone_no . "', '" . $address . "', '" . $note_about_vender . "', '" . $warranty_period_in_days . "','" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
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
                    echo '<option value="' . $package_id . '" selected="selected">' . $package_name . ' (' . $category_name . ')</option>';
                } else {
                    echo "Fail";
                }
            } else {
                $row            = $db->fetch($result);
                $package_id     = $row[0]['id'];
                $package_name   = $row[0]['package_name'];
                $category_name  = $row[0]['category_name'];
                echo '<option value="' . $package_id . '" selected="selected">' . $package_name . ' (' . $category_name . ')</option>';
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
}
