<?php
echo $this->element('top_page', array(
    'page_title' => 'Quản lý nhóm làm việc',
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
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><?php echo __('Quản lý nhóm làm việc'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li class="active">
            <?php echo __('Thêm nhóm làm việc'); ?>
        </li>
    </ul>
</div>
<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i><?php echo __('Thêm mới nhóm'); ?></h3>
                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php echo $this->Form->create('WorkingGroup', array('class' => 'form-horizontal validation-form')); ?>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Tên nhóm'); ?>:<i class="icon-star red icon_fix"></i></label>

                    <div class="controls">
                        <div class="span12">
                            <?php echo $this->Form->input('name', array('placeholder' => '', 'class' => 'input-xlarge', 'label' => false, 'data-rule-required' => 'true', 'data-rule-minlength' => '3')) ?>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo __('Nhân viên'); ?>:</label>

                    <div class="controls">
                        <div class="span8">
                            <textarea name="NV_ten_1" id="NV_ten_1" readonly="true" class="span8" rows="2"></textarea>
                            <input type="hidden" value="" name="NV_ID_1" id="NV_ID_1"/>
                        </div>
                        <div class="span12">
                            <a data-toggle="modal" class="btn select-checkbox-user" onclick="getNVS(1)"
                               role="button" href="#modal-1">Chọn nhân viên</a>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Mô tả'); ?>:</label>

                    <div class="controls">
                        <div class="span12">
                            <?php echo $this->Form->textarea('desc', array('class' => 'ckeditor span12')); ?>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo __('Nhóm nghiệp vụ:'); ?></label>

                    <div class="controls">
                        <?php
                        echo $this->Form->input('large_group_id', array(
                                'type' => 'select',
                                'options' => $large_group,
                                'label' => false
                            )
                        );
                        ?>

                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo __('Trạng thái'); ?></label>

                    <div class="controls">
                        <?php
                        $status = array('1' => 'Có hiệu lực', '0' => 'Không hiệu lực');
                        echo $this->Form->input('status', array(
                                'type' => 'select',
                                'options' => $status,
                                'label' => false
                            )
                        );
                        ?>

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
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="modal-1"
     style="display: none;">

</div>
<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
<script>
    function getNVS(i) {
        $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'SelectUsers'),true)?>"+"/"+i, function (data) {
            $("#modal-1").html(data);
        });
    }
</script>