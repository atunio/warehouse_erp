<?php 
if ($battery != "" && $battery < '60') {
    $defectsCode .= ' - Battery Health is less then 60%';
}
if ($battery > '60' && ($lcd_grade == 'D' || $digitizer_grade == 'D' || $body_grade == 'D')) {
    $overall_grade = "D";
} else if ($battery != "" && $battery >= '70') {
    if ($lcd_grade == 'A' && $digitizer_grade == 'A' && $body_grade == 'A') {
        $overall_grade = "A";
    }
    if ($lcd_grade == 'A' && $digitizer_grade == 'A' && $body_grade == 'B') {
        $overall_grade = "B";
    }
    if ($lcd_grade == 'A' && $digitizer_grade == 'A' && $body_grade == 'C') {
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

    if ($lcd_grade == 'C' && $digitizer_grade == 'A' && $body_grade == 'A') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'C' && $digitizer_grade == 'A' && $body_grade == 'B') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'C' && $digitizer_grade == 'A' && $body_grade == 'C') {
        $overall_grade = "C";
    }

    if ($lcd_grade == 'A' && $digitizer_grade == 'B' && $body_grade == 'A') {
        $overall_grade = "B";
    }
    if ($lcd_grade == 'B' && $digitizer_grade == 'B' && $body_grade == 'B') {
        $overall_grade = "B";
    }
    if ($lcd_grade == 'C' && $digitizer_grade == 'B' && $body_grade == 'C') {
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
} else if ($battery < '70' && $battery >= '60') {
    if ($lcd_grade == 'A' && $digitizer_grade == 'A' && $body_grade == 'A') {
        $overall_grade = "B";
    }
    if ($lcd_grade == 'B' && $digitizer_grade == 'A' && $body_grade == 'B') {
        $overall_grade = "B";
    }
    if ($lcd_grade == 'C' && $digitizer_grade == 'A' && $body_grade == 'C') {
        $overall_grade = "C";
    }

    if ($lcd_grade == 'A' && $digitizer_grade == 'B' && $body_grade == 'A') {
        $overall_grade = "B";
    }
    if ($lcd_grade == 'B' && $digitizer_grade == 'B' && $body_grade == 'B') {
        $overall_grade = "B";
    }
    if ($lcd_grade == 'C' && $digitizer_grade == 'B' && $body_grade == 'C') {
        $overall_grade = "C";
    }

    if ($lcd_grade == 'A' && $digitizer_grade == 'C' && $body_grade == 'A') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'B' && $digitizer_grade == 'C' && $body_grade == 'B') {
        $overall_grade = "C";
    }
    if ($lcd_grade == 'C' && $digitizer_grade == 'C' && $body_grade == 'C') {
        $overall_grade = "C";
    }
}
?>