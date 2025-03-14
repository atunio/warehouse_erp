<?php
if (isset($id) && $id > 0) {
    if (access("print_perm") == 1) { ?>
        <a class="btn cyan waves-effect waves-light custom_btn_size" href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/print_po.php?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&id=" . $id) ?>" target="_blank">
            Print
        </a>
    <?php
    }
}
if (access("add_perm") == 1) { ?>
    <a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=profile&cmd=add&active_tab=tab1") ?>">
        New
    </a>
<?php } ?>
<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=listing") ?>" data-target="dropdown1">
    List
</a>