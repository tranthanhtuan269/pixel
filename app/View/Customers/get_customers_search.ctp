    <label class="control-label" >
        <?php echo __('Khách hàng'); ?>:</label>
    <div class="controls">
        <?php

        if(isset($_REQUEST['search']) && $_REQUEST['search']){
            echo $this->Form->input('Customer_id', array('class' => 'span12','options'=>$customers,'empty'=>'--Chọn khách hàng--','name'=>'Customer_id','default'=>$selected,'onchange'=>'getCustomer_Groups(this.value)','div'=>false,'label'=>false));
        }else{
            echo $this->Form->input('Customer_id', array('class' => 'span12','options'=>$customers,'empty'=>'--Chọn khách hàng--','default'=>$selected,'onchange'=>'getCustomer_Groups(this.value)','div'=>false,'label'=>false));
        }  ?>
    </div>