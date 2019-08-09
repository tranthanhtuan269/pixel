<?php
echo $this->element('top_page', array(
    'page_title' => 'Quản lý quốc gia'
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
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><?php echo __('Quản lý quốc gia'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li class="active">
            <?php echo __('Thêm quốc gia'); ?>
        </li>
    </ul>
</div>
<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i><?php echo __('Thêm quốc gia'); ?></h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php echo $this->Form->create('Country', array('class' => 'form-horizontal validation-form', 'type' => 'file')); ?>
                <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label" for="name"><?php echo __('Tên quốc gia'); ?>:</label>

                            <div class="controls">
                                <div class="span12">
                                    <?php echo $this->Form->input('name', array(
                                        'class' => 'input-xlarge',
                                        'data-rule-required' => true,
                                        'data-rule-minlength' => '3',
                                        'label' => false

                                    ));?>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="name"><?php echo __('Thủ đô'); ?>:</label>

                            <div class="controls">
                                <div class="span12">
                                    <?php echo $this->Form->input('capital', array(
                                        'class' => 'input-xlarge',
                                        'label' => false
                                    ));?>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="name"><?php echo __('Tiền tệ'); ?>:</label>

                            <div class="controls">
                                <div class="span12">
                                    <?php echo $this->Form->input('currency', array(
                                        'class' => 'input-xlarge',
                                        'label' => false
                                    ));?>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="name"><?php echo __('Mã bưu chính'); ?>:</label>

                            <div class="controls">
                                <div class="span12">
                                    <?php echo $this->Form->input('postcode', array(
                                        'class' => 'input-xlarge',
                                        'label' => false

                                    ));?>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="name"><?php echo __('Ngày quốc khánh'); ?>:</label>

                            <div class="controls">
                                <div class="input date">
                                    <?php echo $this->Form->input('independence_day', array(
                                        'class' => 'input-medium',
                                        'label' => false,
                                        'dateFormat' => 'D-M'
                                    ));?>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="name"><?php echo __('Thứ tự'); ?>:</label>

                            <div class="controls">
                                <div class="span12">
                                    <?php echo $this->Form->input('order', array('type' => 'text', 'class' => 'input-xlarge', 'label' => false, 'data-rule-required' => 'true', 'data-rule-number' => 'true')) ?>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="name"><?php echo __('Thời gian trễ'); ?>:</label>

                            <div class="controls">
                                <div class="span12">
                                    <?php
                                    echo $this->Form->input('time_zone', array(
                                            'type' => 'select',
                                            'style' => array('width: 355px;'),
                                            'options' => $time_zone,
                                            'label' => false,
                                            'class' => 'input-xlarge'
                                        )
                                    );?>
                                </div>
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
                                        'label' => false,
                                        'class' => 'input-xlarge'
                                    )
                                );
                                ?>

                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label" for="name"><?php echo __('Kỳ nghỉ'); ?>:</label>

                            <div class="controls">
                                <div class="span12">
                                    <?php echo $this->Form->input('vacation', array('placeholder' => '', 'type' => 'textarea', 'class' => 'input-xlarge', 'label' => false)) ?>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo __('Quốc kỳ'); ?></label>

                            <div class="controls">
                                <div data-provides="fileupload" class="fileupload fileupload-new">
                                    <div style="width: 200px;" class="fileupload-new thumbnail">
                                        <img alt=""
                                             src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image">
                                    </div>
                                    <div style="max-width: 200px; line-height: 20px;"
                                         class="fileupload-preview fileupload-exists thumbnail"></div>
                                    <div>
                                               <span class="btn btn-file"><span
                                                       class="fileupload-new"><?php echo __('Chọn ảnh'); ?></span>
                                               <span class="fileupload-exists"><?php echo __('Sửa'); ?></span>
                                               <input type="file" class="default"
                                                      name="data[Country][flag]"
                                                      id="CountryFlag">
                                               </span>
                                        <a data-dismiss="fileupload" class="btn fileupload-exists"
                                           href="#"><?php echo __('Hủy'); ?></a>
                                    </div>
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
<!-- END Main Content -->

<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>