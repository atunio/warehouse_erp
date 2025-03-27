<?php
$jsonData2                      = $row_pd01_4[0]['phone_check_api_data'];
$phone_check_api_data_id        = $row_pd01_4[0]['id'];
$model_name                     = $row_pd01_4[0]['model_name'];
$sku_code                       = $row_pd01_4[0]['sku_code'];
$model_no                       = $row_pd01_4[0]['model_no'];
$make_name                      = $row_pd01_4[0]['make_name'];
$carrier_name                   = $row_pd01_4[0]['carrier_name'];
$color_name                     = $row_pd01_4[0]['color_name'];
$battery                        = $row_pd01_4[0]['battery'];
$body_grade                     = $row_pd01_4[0]['body_grade'];
$lcd_grade                      = $row_pd01_4[0]['lcd_grade'];
$digitizer_grade                = $row_pd01_4[0]['digitizer_grade'];
$etching                        = $row_pd01_4[0]['etching'];
$ram                            = $row_pd01_4[0]['ram'];
$memory                         = $row_pd01_4[0]['memory'];
$defectsCode                    = $row_pd01_4[0]['defectsCode'];
$overall_grade                  = $row_pd01_4[0]['overall_grade'];
$mdm                            = $row_pd01_4[0]['mdm'];
$failed                         = $row_pd01_4[0]['failed'];
$is_diagnost                    = 1;

$inventory_status = '6';
$status_name = "Defective";
if ($defectsCode == '' || $defectsCode == NULL) {
    $inventory_status = '5';
    $status_name = "Tested/Graded";
}
