<?php
echo $this->element('top_page', array(
    'page_title' => 'Quản lý ngày nghỉ',
));
?>
<div id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/"><?php echo __('Trang chủ'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li>
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><?php echo __('Quản lý ngày nghỉ'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li class="active">
            <?php echo __('Thêm ngày nghỉ'); ?>
        </li>
    </ul>
</div>
<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i><?php echo __('Thêm mới ngày nghỉ'); ?></h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php echo $this->Form->create('Vacation', array('class' => 'form-horizontal validation-form')); ?>
                <div class="row-fluid">
                    <div class="span6">
                        <?php echo $this->Form->input('user_id',array('type'=>'hidden','value'=>$user_id))?>
                        <div class="control-group">
                            <label class="control-label" for="name"><?php echo __('Loại ngày nghỉ'); ?>:</label>

                            <div class="controls">
                                <?php
                                echo $this->Form->input('vacation_type_id', array(
                                        'type' => 'select',
                                        'options' => $vacationTypes,
                                        'label' => false,
                                        'class'=>'input-xlarge'
                                    )
                                );
                                ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="name"><?php echo __('Ngày nghỉ từ ngày'); ?>:</label>
                            <div class="controls">
                                <div class="span12">
                                    <?php echo $this->Form->input('from_date', array('placeholder' => '', 'type' => 'text', 'class' => 'input-small', 'label' => false,'div'=>false,'data-rule-required'=>'true')) ?>  Đến ngày :
                                    <?php echo $this->Form->input('to_date', array('placeholder' => '', 'type' => 'text', 'class' => 'input-small', 'label' => false, 'div'=>false,'data-rule-required'=>'true')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label" for="name"><?php echo __('Lý do'); ?>:</label>

                            <div class="controls">
                                <div class="span12">
                                    <?php echo $this->Form->input('reason', array('placeholder' => '', 'type' => 'textarea', 'class' => 'input-xlarge', 'label' => false)) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?php echo __('Lưu') ?>">
                    <button type="button" class="btn"><?php echo __('Hủy') ?></button>
                </div>
                <?php echo $this->Form->end(); ?>
                </form>
            </div>
        </div>
    </div>
</div>
<footer>
    <?php
    echo $this->element('footer');
    ?>
</footer>
</div>
<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>

<script>
    $(function() {
        $( "#VacationFromDate" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
            onClose: function( selectedDate ) {
                $( "#VacationToDate" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#VacationToDate" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
            onClose: function( selectedDate ) {
                $( "#VacationFromDate" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });
</script>