<div class="row-fluid related" id="item<?php echo $number?>" >
    <div class="span3">
        <div class="row-fluid">
            <label class="span4" for="name"><?php echo __('Tên file (Cách nhau dấu , )'); ?>:</label>
            <div class="span8">
                <?php echo $this->Form->input('Product.'.$number.'.name', array('placeholder' => 'Tên file','rows'=>1, 'class' => 'input-xlarge span12','label' => false)) ?>
            </div>
        </div>
    </div>
    <div class="span3">
        <div class="row-fluid">
            <label class="span4">
                <?php echo __('Nhóm loại xử lý'); ?>:</label>
            <div class="span8">
                <?php echo $this->Form->input('Product.'.$number.'.process_type_group_id', array('options' => $process_type_group, 'empty' => '--Chọn nhóm loại xử lý--','onchange'=>'getProcessSpecial(this.value,'.$number.')',  'class' => 'span12', 'div' => false, 'label' => false)); ?>
            </div>
        </div>
    </div>
    <div class="span3 processSpecial-<?php echo $number;?>">
        <div class="row-fluid">
            <label class="span4">
                <?php echo __('Loại xử lý'); ?>:</label>
            <div class="span8">
                <?php echo $this->Form->input('Product.'.$number.'.process_type_id', array('empty' => '--Chọn loại xử lý--','class' => 'datetimepicker span12','div'=>false,'label'=>false)); ?>
            </div>
        </div>
    </div>
    <div class="span3">
        <div class="row-fluid ">
            <label class="span5">
                <?php echo __('Định dạng trả về'); ?>:</label>
            <div class="span7">
                <?php echo $this->Form->input('Product.'.$number.'.product_extension_id', array('options'=>$productextension,'value'=> '11','class' => 'datetimepicker span12','div'=>false,'label'=>false)); ?>
            </div>
        </div>
    </div>
    <a class="delete-row" onclick="deleterow(<?php echo $number?>)" title="Xóa" >
        <i class="icon-remove-circle"></i>
    </a>

    </div>
</div>

<style>
    .delete-row {
        position: absolute;
        right: -50px;
        cursor: pointer;
        font-size: 25px;
    }
    .related {
        position: relative;
    }
</style>