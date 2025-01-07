<ul class="tabs">

    <li class="tab">
        <a href="#tab1_html" class="<?php if (isset($active_tab) && $active_tab == 'tab1') {
                                        echo "active";
                                    } ?>">
            <i class="material-icons">receipt</i>
            <span>Summary</span>
        </a>
    </li>
    <?php
    if (po_permisions("RMA Repair") == 1) {  ?>
        <li class="tab">
            <a href="#tab2_html" class="<?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab2')) {
                                            echo "active";
                                        } ?>">
                <i class="material-icons">perm_data_setting</i>
                <span>Repair</span>
            </a>
        </li>
    <?php } ?>
    <li class="indicator" style="left: 0px; right: 0px;"></li>
</ul>