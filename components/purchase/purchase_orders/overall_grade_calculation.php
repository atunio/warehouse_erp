<?php
if ($battery == "" || ($battery < 0) || !is_numeric($battery)) {
    $overall_grade = "Grade Not Calculated due to battery";
} else if ($lcd_grade == '' || $digitizer_grade == '' || $body_grade == '') {
    $overall_grade = "Grade Not Calculated due to lcd, digitilzer and body blank";
} else if (is_numeric($battery) && $battery < '60') {
    $defectsCode .= ' - Battery Health is less then 60%';
    $overall_grade = "D";
} else if (is_numeric($battery) && $battery > '60' && ($lcd_grade == 'D' || $digitizer_grade == 'D' || $body_grade == 'D')) {
    $overall_grade = "D";
} else if (is_numeric($battery)) {
    if ($lcd_grade == 'A' && $digitizer_grade == 'A' && $body_grade == 'A') {
        $overall_grade = "A";
    }
    if ($lcd_grade == 'A' && $digitizer_grade == 'A' && $body_grade == 'B') {
        $overall_grade = "B";
    }
    if ($lcd_grade == 'A' && $digitizer_grade == 'A' && $body_grade == 'C') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'A' && $digitizer_grade == 'B' && $body_grade == 'A') {
        $overall_grade = "B";
    }
    if ($lcd_grade == 'A' && $digitizer_grade == 'B' && $body_grade == 'B') {
        $overall_grade = "B";
    }
    if ($lcd_grade == 'A' && $digitizer_grade == 'B' && $body_grade == 'C') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'A' && $digitizer_grade == 'C' && $body_grade == 'A') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'A' && $digitizer_grade == 'C' && $body_grade == 'B') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'A' && $digitizer_grade == 'C' && $body_grade == 'C') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'B' && $digitizer_grade == 'A' && $body_grade == 'A') {
        $overall_grade = "B";
    }
    if ($lcd_grade == 'B' && $digitizer_grade == 'A' && $body_grade == 'B') {
        $overall_grade = "B";
    }
    if ($lcd_grade == 'B' && $digitizer_grade == 'A' && $body_grade == 'C') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'B' && $digitizer_grade == 'B' && $body_grade == 'A') {
        $overall_grade = "B";
    }
    if ($lcd_grade == 'B' && $digitizer_grade == 'B' && $body_grade == 'B') {
        $overall_grade = "B";
    }
    if ($lcd_grade == 'B' && $digitizer_grade == 'B' && $body_grade == 'C') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'B' && $digitizer_grade == 'C' && $body_grade == 'A') {
        $overall_grade = "B";
    }
    if ($lcd_grade == 'B' && $digitizer_grade == 'C' && $body_grade == 'B') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'B' && $digitizer_grade == 'C' && $body_grade == 'C') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'C' && $digitizer_grade == 'A' && $body_grade == 'A') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'C' && $digitizer_grade == 'A' && $body_grade == 'B') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'C' && $digitizer_grade == 'A' && $body_grade == 'C') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'C' && $digitizer_grade == 'B' && $body_grade == 'A') {
        $overall_grade = "B";
    }
    if ($lcd_grade == 'C' && $digitizer_grade == 'B' && $body_grade == 'B') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'C' && $digitizer_grade == 'B' && $body_grade == 'C') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'C' && $digitizer_grade == 'C' && $body_grade == 'A') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'C' && $digitizer_grade == 'C' && $body_grade == 'B') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'C' && $digitizer_grade == 'C' && $body_grade == 'C') {
        $overall_grade = "C";
    }
} else {
    $overall_grade = "Grade Not Calculated due to battery";
}
