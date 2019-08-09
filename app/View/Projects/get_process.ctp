<?php if($number == 0){?>
    <label class="control-label span4" >
        <?php echo __('Loại xử lý'); ?>:</label>
    <div class="controls span8">
        <?php echo $this->Form->input('Project][process_type_id', array('class' => 'span11','options'=>$process_type,'empty'=>'--Chọn loại xử lý--','default'=>$selected,'div'=>false,'label'=>false)); ?>
    </div>
<?php }else{?>
    <div class="row-fluid">
        <label class="span4">
            <?php echo __('Loại xử lý'); ?>:</label>
        <div class="span8">
            <?php echo $this->Form->input('Product.'.$number.'.process_type_id', array('options'=>$process_type,'empty'=>'--Chọn loại xử lý--','default'=>$selected,'class' => 'datetimepicker span11','div'=>false,'label'=>false)); ?>
        </div>
    </div>
<?php }?>