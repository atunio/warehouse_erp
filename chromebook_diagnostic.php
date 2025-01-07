<?php
die;
include("conf/session_start.php");
include('path.php');
include($directory_path . "conf/connection.php");
include($directory_path . "conf/functions.php");
$db = new mySqlDB;
if (!isset($_SESSION['csrf_session'])) {
    $_SESSION['csrf_session'] = session_id();
}

// session_unset();
// session_destroy();

extract($_POST);
if (isset($is_submit) && $is_submit == "Y") {
    foreach ($_POST as $key => $value) {
        if (!is_array($value)) {
            $data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
            $$key = $data[$key];
        }
    }
    if ($user_access_token == "") {
        $error['user_access_token'] = "Required";
    }
    if (decrypt($csrf_token) != $_SESSION["csrf_session"]) {
        echo redirect_to_page("signout");
        exit();
    }
    if (empty($error)) {
        $sql        = " SELECT a.* FROM users a 
                        WHERE a.user_access_token	= '" . $user_access_token . "'
                        AND a.user_access_token >0  "; //echo $sql; die;
        $result     = $db->query($conn, $sql);
        $count      = $db->counter($result);
        if ($count > 0) {

            $row_pd01   = $db->fetch($result);
            $user_id    = $row_pd01[0]['id'];
            $username   = $row_pd01[0]['username'];

            $_SESSION['is_submit']              = "Y";
            $_SESSION['user_access_token']      = $user_access_token;
            $_SESSION['diagnose_by_user_id']    = $user_id;
            $_SESSION['diagnose_by_user']       = $username;
        } else {
            $error['user_access_token'] = "Invalid";
        }
    } else {
        $error['msg'] = "Please check errors in form";
    }
}

if (isset($is_submit2) && $is_submit2 == "Y") {
    foreach ($_POST as $key => $value) {
        if (!is_array($value)) {
            $data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
            $$key = $data[$key];
        }
    }
    if ($product_id == "" || $product_id == "0") {
        $error['product_id'] = "Required";
    }
    if ($po_no == "") {
        $error['po_no'] = "Required";
    }
    if (decrypt($csrf_token) != $_SESSION["csrf_session"]) {
        echo redirect_to_page("signout");
        exit();
    }
    if (empty($error)) {
        $_SESSION['po_no']      = $po_no;
        $_SESSION['product_id'] = $product_id;
        $_SESSION['is_submit2'] = "Y";
    } else {
        $error['msg'] = "Please check errors in form";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Chromebook Testing</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/chromebooktesting_page.css">

    <link rel="apple-touch-icon" href="<?php echo $directory_path; ?>app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $directory_path; ?>app-assets/images/favicon/favicon-32x32.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $directory_path; ?>app-assets/vendors/select2/select2.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo $directory_path; ?>app-assets/vendors/select2/select2-materialize.css" type="text/css">
    <!-- BEGIN: VENDOR CSS-->
    <?php
    if (isset($_SESSION['is_submit']) && isset($_SESSION['is_submit2'])) { ?>
    <?php
    } else { ?>
        <!-- END: VENDOR CSS-->
        <!-- BEGIN: Page Level CSS-->
        <link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/themes/vertical-modern-menu-template/materialize.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/themes/vertical-modern-menu-template/style.css">
        <!-- END: Page Level CSS-->
    <?php } ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/pages/form-select2.css">
</head>

<body>
    <div class="container">
        <div>
            <?php
            /*
            $url = "http://192.168.18.4/cti_system_api/index.php?method=device_info";
            $response = file_get_contents($url);
            if ($response === FALSE) {
                echo "Error fetching the API response.";
            } else {
                echo $response;
            }
            echo "<br>";
            echo date('YmdHis');
            */
            ?>
        </div>
        <h1>Chromebook Device Testing</h1>
        <?php
        if (!isset($_SESSION['is_submit'])) { ?>
            <form method="post" action="">
                <input type="hidden" name="is_submit" id="is_submit" value="Y">
                <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                    echo encrypt($_SESSION['csrf_session']);
                                                                } ?>">
                <div class="row">
                    <div class="col3">
                        <?php
                        $field_name     = "user_access_token";
                        $field_label    = "User Access Code"; ?>
                        <label>
                            <?= $field_label; ?>:

                            <span class="color-red"> * <?php
                                                        if (isset($error[$field_name])) {
                                                            echo $error[$field_name];
                                                        } ?>
                            </span>
                        </label>
                        <input type="number" class="input_text" name="<?= $field_name; ?>" id="<?= $field_name; ?>" placeholder="<?= $field_label; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                                                                    echo ${$field_name};
                                                                                                                                                                } ?>" />
                    </div>
                    <div class="col3">
                        <button class="submitbtn" type="submit">Access</button>
                    </div>
                    <div class="col4"></div>
                </div>
            </form>
        <?php
        } else if (!isset($_SESSION['is_submit2'])) { ?>
            <form method="post" action="">
                <input type="hidden" name="is_submit2" id="is_submit2" value="Y">
                <input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
                                                                    echo encrypt($_SESSION['csrf_session']);
                                                                } ?>">
                <div class="row">
                    <div class="col3">
                        <?php
                        $field_name     = "po_no";
                        $field_label    = "PO No"; ?>
                        <label>
                            <?= $field_label; ?>:

                            <span class="color-red"> * <?php
                                                        if (isset($error[$field_name])) {
                                                            echo $error[$field_name];
                                                        } ?>
                            </span>
                        </label>
                        <input type="text" class="input_text" name="<?= $field_name; ?>" id="<?= $field_name; ?>" placeholder="<?= $field_label; ?>" value="<?php if (isset(${$field_name})) {
                                                                                                                                                                echo ${$field_name};
                                                                                                                                                            } ?>" />
                        <input type="hidden" id="po_no_for_product_ids" name="po_no_for_product_ids" />
                    </div>
                    <div class="col4">
                        <?php
                        $field_name     = "product_id";
                        $field_label    = "Product ID"; ?>
                        <label>
                            <?= $field_label; ?>:

                            <span class="color-red"> * <?php
                                                        if (isset($error[$field_name])) {
                                                            echo $error[$field_name];
                                                        } ?>
                            </span>
                        </label>
                        <?php
                        ?>
                        <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                            echo ${$field_name . "_valid"};
                                                                                                                                                        } ?>">
                            <?php
                            if (isset($po_no) && $po_no != '') {
                                $sql            = " SELECT DISTINCT a.*, c.product_desc, d.category_name, c.product_uniqueid
                                                    FROM purchase_order_detail_receive a1 
                                                    INNER JOIN purchase_order_detail a ON a1.po_detail_id = a.id
                                                    INNER JOIN purchase_orders b ON b.id = a.po_id
                                                    INNER JOIN products c ON c.id = a.product_id
                                                    INNER JOIN product_categories d ON d.id = c.product_category
                                                    WHERE 1=1 
                                                    AND a.enabled = 1 
                                                    AND b.po_no = '" . $po_no . "'
                                                    ORDER BY c.product_uniqueid, a.product_condition ";
                                // echo $sql; 
                                $result_log2    = $db->query($conn, $sql);
                                $count_r2       = $db->counter($result_log2);
                                if ($count_r2 > 1) { ?>
                                    <option value="">Select</option>
                                    <?php
                                }
                                if ($count_r2 > 0) {
                                    $row_r2    = $db->fetch($result_log2);
                                    foreach ($row_r2 as $data_r2) {  ?>
                                        <option value="<?php echo $data_r2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data_r2['id']) { ?> selected="selected" <?php } ?>>
                                            <?php
                                            echo " Product ID: " . $data_r2['product_uniqueid'];
                                            echo " -  Product: " . $data_r2['product_desc'];
                                            if ($data_r2['category_name'] != "") {
                                                echo " (" . $data_r2['category_name'] . ") ";
                                            } ?>
                                        </option>
                                    <?php
                                    }
                                } else { ?>
                                    <option value="">No Product Available</option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="col3">
                        <button class="submitbtn" type="submit">Process To Test</button>
                    </div>
                    <div class="col4"></div>
                    <br><br>
                </div>
            </form>
        <?php
        } else if (isset($_SESSION['is_submit']) && isset($_SESSION['is_submit2'])) { ?>
            <input type="hidden" name="user_access_token" id="user_access_token" value="<?php
                                                                                        if (isset($_SESSION['user_access_token'])) {
                                                                                            echo encrypt($_SESSION['user_access_token']);
                                                                                        }; ?>">
            <input type="hidden" name="product_id" id="product_id" value="<?php
                                                                            if (isset($_SESSION['product_id'])) {
                                                                                echo encrypt($_SESSION['product_id']);
                                                                            }; ?>">
            <div class="row">
                <div class="col3">
                    <!-- Speaker Test -->
                    <div id="speaker">
                        <h2>Speaker Test</h2>
                        <audio id="audioTest" controls>
                            <source src="mov_bbb.mp4" type="video/mp4">
                            Your browser does not support the audio tag.
                        </audio>
                        <p id="speakerStatus">Not Tested</p>
                        <div style="width: 100%; display: flex;">
                            <button id="SpeakerWorking" class="speaker_btn">Speaker Working</button>
                            <button id="SpeakerNotWorking" class="speaker_btn bg_red">Speaker Not Working</button>
                        </div>
                    </div>
                    <br><br><br><br>
                </div>
                <div class="col3">
                    <!-- Microphone Test -->
                    <div id="mic">
                        <h2>Microphone Test</h2>
                        <div id="micVolumeBar">
                            <div id="micVolume" style="width: 0%; height: 20px; background-color: green;"></div>
                        </div>
                        <p id="micStatus">Not Tested</p>
                    </div>
                    <br><br><br><br>
                </div>
                <div class="col3">
                    <!-- Battery Test -->
                    <div id="battery">
                        <h2>Battery Test</h2>
                        <p id="batteryStatus"></p>
                        <p id="batteryPercentage">Battery Percentage: </p>
                        <p id="batteryChargingTime">Charging Time: </p>
                    </div>
                    <br><br><br><br>
                </div>
                <div class="col3">
                    <!-- Camera Test -->
                    <div id="camera">
                        <h2>Camera Test</h2>
                        <video id="cameraFeed" width="500" height="450" autoplay></video>
                        <p id="cameraStatus">Not Tested</p>
                    </div>
                </div>
            </div>

            <!-- Keyboard Test -->
            <div class="keyboard">
                <h2>Keyboard Test</h2>
                <table id="keyboard">
                    <tbody>
                        <tr class="key_small">
                            <td>
                                <div class="key_row frstrow">
                                    <div id="key27" class="key_un">Esc</div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="2">
                                <div class="key_row scondrow">
                                    <div id="key192" class="key_un">~ <br> `</div>
                                    <div id="key49" class="key_un">!<br>1</div>
                                    <div id="key50" class="key_un">@<br>2</div>
                                    <div id="key51" class="key_un">#<br>3</div>
                                    <div id="key52" class="key_un">$<br>4</div>
                                    <div id="key53" class="key_un">%<br>5</div>
                                    <div id="key54" class="key_un">^<br>6</div>
                                    <div id="key55" class="key_un">&amp; <br>7</div>
                                    <div id="key56" class="key_un">*<br>8</div>
                                    <div id="key57" class="key_un">(<br>9</div>
                                    <div id="key48" class="key_un">)<br>0</div>
                                    <div id="key189" class="key_un">-<br>-</div>
                                    <div id="key187" class="key_un">+<br>=</div>
                                    <div id="key8" class="key_un">Backspace</div>
                                </div>
                                <div class="key_row twogigitrow">
                                    <div id="key9" class="key_un">Tab</div>
                                    <div id="key81" class="key_un">Q</div>
                                    <div id="key87" class="key_un">W</div>
                                    <div id="key69" class="key_un">E</div>
                                    <div id="key82" class="key_un">R</div>
                                    <div id="key84" class="key_un">T</div>
                                    <div id="key89" class="key_un">Y</div>
                                    <div id="key85" class="key_un">U</div>
                                    <div id="key73" class="key_un">I</div>
                                    <div id="key79" class="key_un">O</div>
                                    <div id="key80" class="key_un">P</div>
                                    <div id="key219" class="key_un twodg">{<br>[</div>
                                    <div id="key221" class="key_un twodg">}<br>]</div>
                                    <div id="key220" class="key_un twodg">|<br>\</div>
                                </div>
                                <div class="key_row twogigitrow">
                                    <div id="key91" class="key_un">Search</div>
                                    <div id="key65" class="key_un">A</div>
                                    <div id="key83" class="key_un">S</div>
                                    <div id="key68" class="key_un">D</div>
                                    <div id="key70" class="key_un">F</div>
                                    <div id="key71" class="key_un">G</div>
                                    <div id="key72" class="key_un">H</div>
                                    <div id="key74" class="key_un">J</div>
                                    <div id="key75" class="key_un">K</div>
                                    <div id="key76" class="key_un">L</div>
                                    <div id="key186" class="key_un twodg">:<br>;</div>
                                    <div id="key222" class="key_un twodg">"<br>'</div>
                                    <div id="key13" class="key_un">Enter</div>
                                </div>
                                <div class="key_row twogigitrow">
                                    <div id="key16a" class="key_un">Shift</div>
                                    <div id="key90" class="key_un">Z</div>
                                    <div id="key88" class="key_un">X</div>
                                    <div id="key67" class="key_un">C</div>
                                    <div id="key86" class="key_un">V</div>
                                    <div id="key66" class="key_un">B</div>
                                    <div id="key78" class="key_un">N</div>
                                    <div id="key77" class="key_un">M</div>
                                    <div id="key188" class="key_un twodg">&lt;<br>,</div>
                                    <div id="key190" class="key_un twodg">&gt;<br>.</div>
                                    <div id="key191" class="key_un twodg">?<br>/</div>
                                    <div id="key16b" class="key_un">Shift</div>
                                </div>
                                <div class="key_row">
                                    <div id="key17a" class="key_un">Ctrl</div>
                                    <div id="key18a" class="key_un">Alt</div>
                                    <div id="key32" class="key_un">Spacebar</div>
                                    <div id="key18b" class="key_un">Alt</div>
                                    <div id="key17b" class="key_un">Ctrl</div>
                                    <div id="key37" class="key_un"><i class="fa fa-angle-left"></i></div>
                                    <div class="noborderkey">
                                        <span id="key38" class="key_un"><i class="fa fa-angle-up"></i></span>
                                    </div>
                                    <div id="key39" class="key_un"><i class="fa fa-angle-right"></i></div>
                                </div>
                                <div class="key_row" style="flex-wrap: nowrap; display: inline-block">
                                    <span class="key_un2"> </span>
                                    <span class="key_un2"> </span>
                                    <span class="key_un2"> </span>
                                    <span class="key_un2"> </span>
                                    <span class="key_un2"> </span>
                                    <span class="key_un2"> </span>
                                    <span id="key40" class="key_un key_un2"><i class="fa fa-angle-down"></i></span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="statistics">
                    <div class="row">
                        <div class="col3">
                            <p>Keys Left: <span id="keysLeft">63</span></p>
                        </div>
                        <div class="col3">
                            <p>Keys Pressed: <span id="keysPressed">0</span></p>
                        </div>
                        <div class="col3">
                            <p>Test Progress: <span id="testProgress">0%</span></p>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div id="progress" class="progress">0%</div>
                    </div>
                </div>
            </div>
            <br><br>

            <div class="row">
                <div class="col2">
                    <?php
                    $field_name     = "body_grade";
                    $field_label    = "Body Grade"; ?>
                    <label>
                        <?= $field_label; ?>:

                        <span class="color-red"> * <?php
                                                    if (isset($error[$field_name])) {
                                                        echo $error[$field_name];
                                                    } ?>
                        </span>
                    </label>
                    <?php
                    ?>
                    <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                                                    } ?>">
                        <option value="">Select</option>
                        <option value="A" <?php if (isset(${$field_name}) && ${$field_name} == "A") { ?> selected="selected" <?php } ?>>A</option>
                        <option value="B" <?php if (isset(${$field_name}) && ${$field_name} == "B") { ?> selected="selected" <?php } ?>>B</option>
                        <option value="C" <?php if (isset(${$field_name}) && ${$field_name} == "C") { ?> selected="selected" <?php } ?>>C</option>
                        <option value="D" <?php if (isset(${$field_name}) && ${$field_name} == "D") { ?> selected="selected" <?php } ?>>D</option>
                    </select>
                </div>
                <div class="col2">
                    <?php
                    $field_name     = "lcd_grade";
                    $field_label    = "LCD Grade"; ?>
                    <label>
                        <?= $field_label; ?>:

                        <span class="color-red"> * <?php
                                                    if (isset($error[$field_name])) {
                                                        echo $error[$field_name];
                                                    } ?>
                        </span>
                    </label>
                    <?php
                    ?>
                    <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                                                    } ?>">
                        <option value="">Select</option>
                        <option value="A" <?php if (isset(${$field_name}) && ${$field_name} == "A") { ?> selected="selected" <?php } ?>>A</option>
                        <option value="B" <?php if (isset(${$field_name}) && ${$field_name} == "B") { ?> selected="selected" <?php } ?>>B</option>
                        <option value="C" <?php if (isset(${$field_name}) && ${$field_name} == "C") { ?> selected="selected" <?php } ?>>C</option>
                        <option value="D" <?php if (isset(${$field_name}) && ${$field_name} == "D") { ?> selected="selected" <?php } ?>>D</option>
                    </select>
                </div>
                <div class="col2">
                    <?php
                    $field_name     = "digitizer_grade";
                    $field_label    = "Digitizer Grade"; ?>
                    <label>
                        <?= $field_label; ?>:

                        <span class="color-red"> * <?php
                                                    if (isset($error[$field_name])) {
                                                        echo $error[$field_name];
                                                    } ?>
                        </span>
                    </label>
                    <?php
                    ?>
                    <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                                                    } ?>">
                        <option value="">Select</option>
                        <option value="A" <?php if (isset(${$field_name}) && ${$field_name} == "A") { ?> selected="selected" <?php } ?>>A</option>
                        <option value="B" <?php if (isset(${$field_name}) && ${$field_name} == "B") { ?> selected="selected" <?php } ?>>B</option>
                        <option value="C" <?php if (isset(${$field_name}) && ${$field_name} == "C") { ?> selected="selected" <?php } ?>>C</option>
                        <option value="D" <?php if (isset(${$field_name}) && ${$field_name} == "D") { ?> selected="selected" <?php } ?>>D</option>
                    </select>
                </div>
                <div class="col2">
                    <?php
                    $field_name     = "overall_grade";
                    $field_label    = "Over All Grade"; ?>
                    <label>
                        <?= $field_label; ?>:

                        <span class="color-red"> * <?php
                                                    if (isset($error[$field_name])) {
                                                        echo $error[$field_name];
                                                    } ?>
                        </span>
                    </label>
                    <?php
                    ?>
                    <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                                                    } ?>">
                        <option value="">Select</option>
                        <option value="A" <?php if (isset(${$field_name}) && ${$field_name} == "A") { ?> selected="selected" <?php } ?>>A</option>
                        <option value="B" <?php if (isset(${$field_name}) && ${$field_name} == "B") { ?> selected="selected" <?php } ?>>B</option>
                        <option value="C" <?php if (isset(${$field_name}) && ${$field_name} == "C") { ?> selected="selected" <?php } ?>>C</option>
                        <option value="D" <?php if (isset(${$field_name}) && ${$field_name} == "D") { ?> selected="selected" <?php } ?>>D</option>
                    </select>
                </div>

                <div class="col3">
                    <?php
                    $field_name     = "sub_location_id";
                    $field_label    = "Location"; ?>
                    <label>
                        <?= $field_label; ?>:

                        <span class="color-red"> * <?php
                                                    if (isset($error[$field_name])) {
                                                        echo $error[$field_name];
                                                    } ?>
                        </span>
                    </label>
                    <?php
                    ?>
                    <select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
                                                                                                                                                        echo ${$field_name . "_valid"};
                                                                                                                                                    } ?>">
                        <?php
                        $sql    = "SELECT * FROM warehouse_sub_locations a WHERE a.enabled = 1  ORDER BY sub_location_name ";
                        // echo $sql; 
                        $result_log2    = $db->query($conn, $sql);
                        $count_r2       = $db->counter($result_log2);
                        if ($count_r2 > 1) { ?>
                            <option value="">Select</option>
                            <?php
                        }
                        if ($count_r2 > 0) {
                            $row_r2    = $db->fetch($result_log2);
                            foreach ($row_r2 as $data_r2) {  ?>
                                <option value="<?php echo $data_r2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data_r2['id']) { ?> selected="selected" <?php } ?>>
                                    <?php echo $data_r2['sub_location_name'];
                                    if ($data_r2['sub_location_type'] != "") {
                                        echo " (" . ucwords(strtolower($data_r2['sub_location_type'])) . ")";
                                    } ?>
                                </option>
                            <?php
                            }
                        } else { ?>
                            <option value="">No Location</option>
                        <?php }
                        ?>
                    </select>
                </div>
            </div>
            <br><br>
            <div class="row">
                <div class="col4"></div>
                <div class="col3">
                    <button class="submitbtn" id="submitTest">Submit Results</button>
                </div>
                <div class="col4"></div>
                <br><br>
            </div>

        <?php } ?>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <?php
    if (isset($_SESSION['is_submit']) && isset($_SESSION['is_submit2'])) { ?>
        <script>
            // Camera Test
            let cameraFeed = document.getElementById('cameraFeed');
            let cameraStatus = document.getElementById('cameraStatus');

            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then((stream) => {
                    cameraFeed.srcObject = stream;
                    cameraStatus.textContent = "Working";
                })
                .catch(() => {
                    cameraStatus.textContent = "Not Working";
                });
            // Speaker Test
            const speakerStatus = document.getElementById("speakerStatus");
            const SpeakerWorking = document.getElementById("SpeakerWorking");
            const SpeakerNotWorking = document.getElementById("SpeakerNotWorking");

            SpeakerWorking.addEventListener("click", () => {
                speakerStatus.textContent = "Working";
            });
            SpeakerNotWorking.addEventListener("click", () => {
                speakerStatus.textContent = "Not Working";
            });

            // Microphone Test
            let micStatus = document.getElementById('micStatus');
            let micVolume = document.getElementById('micVolume');

            navigator.mediaDevices.getUserMedia({
                    audio: true
                })
                .then((stream) => {
                    let audioContext = new(window.AudioContext || window.webkitAudioContext)();
                    let analyser = audioContext.createAnalyser();
                    let source = audioContext.createMediaStreamSource(stream);
                    source.connect(analyser);

                    let bufferLength = analyser.frequencyBinCount;
                    let dataArray = new Uint8Array(bufferLength);

                    function updateMic() {
                        analyser.getByteFrequencyData(dataArray);
                        let average = dataArray.reduce((a, b) => a + b) / dataArray.length;
                        let volume = average / 255 * 100;
                        micVolume.style.width = volume + '%';

                        if (volume > 0) {
                            micStatus.textContent = "Working";
                        } else {
                            micStatus.textContent = "Not Working";
                        }

                        requestAnimationFrame(updateMic);
                    }

                    updateMic();
                })
                .catch(() => {
                    micStatus.textContent = "Not Working";
                });

            // Keyboard Test  
            let keysPressed = 0;
            let keysLeft = 0;
            let totalKeys = document.querySelectorAll('.key_un').length;
            let keyStatuses = {};
            document.querySelectorAll('.key_un').forEach(key => {
                keyStatuses[key.id] = 'unpressed';
            });

            // Listen for keydown events on the document
            document.addEventListener('keydown', (event) => {
                if (event.keyCode == '16') {
                    if (event.location == '1') {
                        var key = document.getElementById(`key${event.keyCode}a`);
                    } else {
                        var key = document.getElementById(`key${event.keyCode}b`);
                    }
                } else if (event.keyCode == '17') {
                    if (event.location == '1') {
                        var key = document.getElementById(`key${event.keyCode}a`);
                    } else {
                        var key = document.getElementById(`key${event.keyCode}b`);
                    }
                } else if (event.keyCode == '18') {
                    if (event.location == '1') {
                        var key = document.getElementById(`key${event.keyCode}a`);
                    } else {
                        var key = document.getElementById(`key${event.keyCode}b`);
                    }
                } else {
                    var key = document.getElementById(`key${event.keyCode}`);
                }
                if (key) {
                    key.classList.add('pressed');
                    keyStatuses[key.id] = 'pressed';
                    updateStats();
                }
            });

            function updateStats() {
                keysPressed = Object.values(keyStatuses).filter(status => status === 'pressed').length;
                keysLeft = totalKeys - keysPressed; // For now, assume all presses are correct
                const progress = (keysPressed / totalKeys) * 100;

                document.getElementById('keysLeft').textContent = keysLeft;
                document.getElementById('keysPressed').textContent = keysPressed;
                document.getElementById('testProgress').textContent = `${Math.round(progress)}%`;
                document.getElementById('progress').textContent = `${Math.round(progress)}%`;
                document.getElementById('progress').style.width = `${Math.round(progress)}%`;
            }

            // Battery Test
            let batteryStatus = document.getElementById('batteryStatus');
            let batteryPercentage = document.getElementById('batteryPercentage');
            let batteryChargingTime = document.getElementById('batteryChargingTime');

            navigator.getBattery().then((battery) => {
                batteryStatus.textContent = battery.charging ? "Charging" : "Not Charging";
                batteryPercentage.textContent = `Battery Percentage: ${battery.level * 100}%`;
                batteryChargingTime.textContent = `Charging Time: ${battery.chargingTime} seconds`;
            });

            // Submit Results
            document.getElementById('submitTest').addEventListener('click', () => {
                let cameraTestResult = cameraStatus.textContent;
                let speakerTestResult = speakerStatus.textContent;
                let micTestResult = micStatus.textContent;
                let keyboardTest_Perc = testProgress.textContent;

                let keyboardTest_LeftKeys = document.getElementById('keysLeft').textContent;

                let batteryTestResult = batteryStatus.textContent;
                let batteryPercent = parseInt(batteryPercentage.textContent.replace('Battery Percentage: ', '%'));
                let batteryTime = batteryChargingTime.textContent;

                let user_access_token = document.getElementById('user_access_token').value;
                let product_id = document.getElementById('product_id').value;
                let body_grade = document.getElementById('body_grade').value;
                let lcd_grade = document.getElementById('lcd_grade').value;
                let digitizer_grade = document.getElementById('digitizer_grade').value;
                let overall_grade = document.getElementById('overall_grade').value;
                let sub_location_id = document.getElementById('sub_location_id').value;

                fetch('submit_test.php', {
                        method: 'POST',
                        body: JSON.stringify({
                            user_access_token: user_access_token,
                            product_id: product_id,
                            camera_status: cameraTestResult,
                            speaker_status: speakerTestResult,
                            mic_status: micTestResult,
                            keyboard_status: keyboardTest_Perc,
                            keyboardTest_LeftKeys: keyboardTest_LeftKeys,
                            battery_status: batteryTestResult,
                            battery_percentage: batteryPercent,
                            charging_time: batteryTime,
                            body_grade: body_grade,
                            lcd_grade: lcd_grade,
                            digitizer_grade: digitizer_grade,
                            overall_grade: overall_grade,
                            sub_location_id: sub_location_id,
                            serial_no: 'Dv879112887'
                        }),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    }).then(
                        response => response.json()
                    )
                    .then(
                        data => {
                            if (data.status === 'success') {
                                alert('Results Submitted Successfully!');
                            } else {
                                alert('Failed to submit results. Please try again.');
                            }
                        }
                    )
                    .catch(
                        error => console.error('Error:', error)
                    );
            });
        </script>
    <?php
    } else { ?>
    <?php } ?>
    <!-- BEGIN VENDOR JS-->
    <script src="<?php echo $directory_path; ?>app-assets/js/vendors.min.js"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="<?php echo $directory_path; ?>app-assets/vendors/select2/select2.full.min.js"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN THEME  JS-->
    <script src="<?php echo $directory_path; ?>app-assets/js/plugins.js"></script>
    <script src="<?php echo $directory_path; ?>app-assets/js/search.js"></script>
    <script src="<?php echo $directory_path; ?>app-assets/js/custom/custom-script.js"></script>
    <script src="<?php echo $directory_path; ?>app-assets/js/scripts/customizer.js"></script>
    <!-- END THEME  JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <script src="<?php echo $directory_path; ?>app-assets/js/scripts/form-select2.js"></script>
    <!-- END PAGE LEVEL JS-->

    <script>
        $(document).ready(function() {
            $('#po_no').on('keyup', function() {
                $('#po_no_for_product_ids').val($(this).val());
                data = [];
                data[0] = po_no_for_product_ids; // source field name
                data[1] = 'product_id'; // target field
                data[2] = null;
                data[3] = null;
                data[4] = null;
                generate_combo_new(data);
            });
        });

        function generate_combo_new(data) {
            source_field = data[0];
            target_field = data[1];
            other_option = data[2];
            default_value = data[3];
            other_value = data[4];

            var dataString = '';
            dataString = dataString + "source_field=" + $(source_field).attr('name') + "&" + $(source_field).attr('name') + "=" + $(source_field).val() + "";
            dataString = dataString + "&target_field=" + target_field;
            if (other_option != null) {
                dataString = dataString + "&other_option=1";
            }
            if (other_value != null) {
                dataString = dataString + "&other_value=" + other_value;
            }

            //alert(dataString);
            // extra variables for query
            if (data[4] != null) {
                for (i = 4; i < data.length; i++) {
                    dataString = dataString + "&" + data[i] + "=" + $('#' + data[i] + '').val() + "";
                }
            }
            //alert(source_field);
            $.ajax({
                url: 'ajax/generate_combo.php',
                type: 'POST',
                dataType: 'json',
                data: dataString,

                success: function(result) {

                    $('#' + target_field).html(""); //clear old options
                    result = eval(result);
                    for (i = 0; i < result.length; i++) {
                        for (key in result[i]) {
                            $('#' + target_field).get(0).add(new Option(result[i][key], [key]), document.all ? i : null);
                        }
                    }
                    if (default_value != null) {
                        $('#' + target_field).val(default_value); //select default value
                    } else {
                        $("option:first", target_field).attr("selected", "selected"); //select first option
                    }

                    $('#' + target_field).css("display", "inline");

                }
            });
        }
    </script>
</body>

</html>