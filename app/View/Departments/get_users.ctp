<label class="control-label">
    <?php echo __('Nhân viên'); ?>:</label>

<div class="controls">
    <?php echo $this->Form->input('user_id', array('class' => 'span12','options'=>$users, 'empty' => '--Chọn nhân viên--','default'=>$selected, 'div' => false, 'label' => false)); ?>
</div>
