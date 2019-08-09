<?php
echo $this->element('top_page', array(
    'page_title' => 'Quản lý tin nhắn',
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
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><?php echo __('Tin nhắn'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li class="active">
            <?php echo __('Gửi tin nhắn'); ?>
        </li>
    </ul>
</div>
<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i><?php echo __('Gửi tin nhắn mới'); ?></h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php echo $this->Form->create('Notification', array('type' => 'file', 'class' => 'form-horizontal validation-form')); ?>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Tiêu đề'); ?>:<i
                            class="icon-star red icon_fix"></i></label>

                    <div class="controls">
                        <div class="span12">
                            <?php echo $this->Form->input('title', array('placeholder' => '', 'class' => 'input-xxlarge', 'label' => false, 'data-rule-required' => 'true', 'data-rule-minlength' => '3')) ?>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Người nhận'); ?>:<i
                            class="icon-star red icon_fix"></i></label>

                    <div class="controls">
                        <div class="span12">
                            <textarea name="NV_ten" id="NV_ten" readonly="true" class="col span_12_of_12"
                                      rows="2"></textarea>
                            <input type="hidden" name="NV_ID" id="NV_ID" value="49"/
                        </div>
                        <div class="span9">
                            <a data-toggle="modal" class="btn select-checkbox-user" onclick="getNVS()" role="button"
                               href="#modal-1">Chọn người gửi</a>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo __('Loại tin nhắn'); ?></label>

                    <div class="controls">
                        <div class="span12">
                            <?php
                            echo $this->Form->input('alert_id', array(
                                    'type' => 'select',
                                    'options' => $alerts,
                                    'label' => false
                                )
                            );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo __('Nội dung'); ?></label>

                    <div class="controls">
                        <div class="span9">
                            <?php echo $this->Form->input('content', array('div' => false, 'type' => 'textarea', 'class' => 'ckeditor form-control', 'label' => false)); ?>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?php echo __('Gửi') ?>">
                    <button type="button" class="btn"><?php echo __('Hủy') ?></button>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="modal-1"
         style="display: none;"></div>
</div>
<footer>
    <?php
    echo $this->element('footer');
    ?>
</footer>
<script>
    function getNVS() {
        $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'SelectUsers'),true)?>", function (data) {
            $("#modal-1").html(data);
        });
    }
</script>
<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>