<?php if($number == 0){?>
    <label class="control-label" >
        <?php echo __('Loại xử lý'); ?>:</label>
    <div class="controls">
        <?php echo $this->Form->input('ProcessType_id', array('class' => 'span12','options'=>$process_type,'empty'=>'--Chọn loại xử lý--','default'=>$selected,'div'=>false,'label'=>false)); ?>
    </div>
<?php }else{?>
    <div class="row-fluid">
        <label class="span4">
            <?php echo __('Loại xử lý'); ?>:</label>
        <div class="span8">
            <?php echo $this->Form->input('Product.'.$number.'.process_type_id', array('options'=>$process_type,'empty'=>'--Chọn loại xử lý--','default'=>$selected,'class' => 'datetimepicker span12','div'=>false,'label'=>false)); ?>
        </div>
    </div>
<?php }?>