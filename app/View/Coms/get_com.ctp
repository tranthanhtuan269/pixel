<?php echo $this->Form->input('Com_id', array('options' => $com,'class' => 'span12', 'empty' => '--Chọn Comp--', 'onchange' => 'getCustomers(this.value)', 'div' => false, 'label' => false)); ?>
