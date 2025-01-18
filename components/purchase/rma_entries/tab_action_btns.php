<?php 
if(isset($id) && $id>0){?>
    <a class="btn cyan waves-effect waves-light custom_btn_size" href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/print_invoice.php?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&id=" . $id) ?>" target="_blank">
        Print
    </a>
<?php }?>
<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=profile&cmd=add&active_tab=tab1") ?>">
    New
</a>
<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=listing") ?>" data-target="dropdown1">
    List
</a>