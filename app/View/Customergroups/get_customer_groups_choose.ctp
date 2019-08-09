<label class="control-label">
    <?php echo __('Nhóm khách hàng'); ?>:</label>
<div class="controls">
<!--    --><?php //debug($Customergroups)?>
    <?php foreach($Customergroups as $k => $Customergroup){?>
        <label class="checkbox inline">
            <input type="checkbox" name="data[Customergroup][]" class="Customergroup id_<?php echo $k?>" value="<?php echo $k?>" ><?php echo $Customergroup?>
        </label>
    <?php }?>
</div>