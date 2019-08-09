
<label class="control-label">
    <?php echo __('Nhóm khách hàng'); ?>:</label>
<div class="controls">
    <?php

    if(isset($_REQUEST['search']) && $_REQUEST['search']){
        echo $this->Form->input('CustomerGroup_id', array('class' => 'span12','options'=>$Customergroups,'empty'=>'--Chọn nhóm khách hàng--','name'=>'CustomerGroup_id','default'=>$selected,'div'=>false,'label'=>false,'onchange'=>'hideComs(this.value)'));
    }else{
        echo $this->Form->input('CustomerGroup_id', array('class' => 'span12','options'=>$Customergroups,'empty'=>'--Chọn nhóm khách hàng--','default'=>$selected,'div'=>false,'label'=>false,'onchange'=>'hideComs(this.value)'));
    }  ?>

</div>