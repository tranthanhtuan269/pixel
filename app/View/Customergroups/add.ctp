<?php
echo $this->element('top_page', array(
    'page_title' => 'Quản lý nhóm khách hàng',
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
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><?php echo __('Quản lý nhóm khách hàng'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li class="active">
            <?php echo __('Thêm nhóm khách hàng'); ?>
        </li>
    </ul>
</div>
<!-- BEGIN Main Content -->
<div class="row-fluid">
<div class="span12">
    <div class="box">
        <div class="box-title">
            <h3><i class="icon-reorder"></i><?php echo __('Thêm mới nhóm khách hàng'); ?></h3>

            <div class="box-tool">
                <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                <a data-action="close" href="#"><i class="icon-remove"></i></a>
            </div>
        </div>
        <div class="box-content">
            <?php echo $this->Form->create('Customergroup', array('class' => 'form-horizontal validation-form', 'type' => 'file')); ?>
            <div class="row-fluid">
                <div class="span6">
                    <div class="control-group">
                        <label class="control-label" for="name"><?php echo __('Tên nhóm khách hàng'); ?>:</label>

                        <div class="controls">
                            <div class="span12">
                                <?php echo $this->Form->input('name', array('placeholder' => '', 'class' => 'input-xlarge', 'label' => false, 'data-rule-required' => 'true', 'data-rule-minlength' => '3')) ?>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"><?php echo __('Quốc gia'); ?></label>

                        <div class="controls">
                            <?php
                            echo $this->Form->input('country_id', array(
                                    'type' => 'select',
                                    'options' => $countries,
                                    'onchange' => 'getCustomers(this.value)',
                                    'empty' => '--Chọn quốc gia--',
                                    'label' => false,
                                    'class' => 'input-xlarge'
                                )
                            );
                            ?>

                        </div>
                    </div>
                    <div class="control-group" id="customer">
                        <label class="control-label"><?php echo __('Khách hàng'); ?></label>

                        <div class="controls" >
                            <?php
                            echo $this->Form->input('customer_id', array(
                                    'type' => 'select',
                                    'options' => $customers,
                                    'label' => false,
                                    'class' => 'input-xlarge'
                                )
                            );
                            ?>

                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="name"><?php echo __('Thời gian trả hàng'); ?>:</label>

                        <div class="controls">
                            <div class="span12">
                                <?php echo $this->Form->input('deliver_time', array('placeholder' => '', 'type' => 'text', 'class' => 'input-xlarge', 'label' => false, 'data-rule-required' => 'true')) ?>(h)
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="name"><?php echo __('Help file'); ?>:</label>

                        <div class="controls">
                            <div class="span12">
                                <?php echo $this->Form->input('help_file', array('placeholder' => '', 'type' => 'file', 'class' => 'input-xlarge', 'label' => false)) ?>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"><?php echo __('Ảnh đại diện:'); ?></label>

                        <div class="controls">
                            <div data-provides="fileupload" class="fileupload fileupload-new">
                                <div style="width: 200px; height: 150px;" class="fileupload-new thumbnail">
                                    <img alt="" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image">
                                </div>
                                <div style="max-width: 200px; max-height: 150px; line-height: 20px;"
                                     class="fileupload-preview fileupload-exists thumbnail"></div>
                                <div>
                                               <span class="btn btn-file"><span
                                                       class="fileupload-new"><?php echo __('Chọn ảnh'); ?></span>
                                               <span class="fileupload-exists"><?php echo __('Sửa'); ?></span>
                                               <input type="file" class="default"
                                                      name="data[Customergroup][upload_file]"
                                                      id="CustomergroupUploadFile">
                                               </span>
                                    <a data-dismiss="fileupload" class="btn fileupload-exists"
                                       href="#"><?php echo __('Hủy'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"><?php echo __('Định dạng ảnh'); ?></label>

                        <div class="controls">
                            <?php
                            echo $this->Form->input('product_extension_id', array(
                                    'type' => 'select',
                                    'options' => $productExtensions,
                                    'label' => false,
                                    'class' => 'input-xlarge',
                                    'value' => '11'
                                )
                            );
                            ?>

                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"><?php echo __('Loại xử lý'); ?></label>

                        <div class="controls">
                            <?php
                            echo $this->Form->input('process_type_id', array(
                                    'type' => 'select',
                                    'options' => $processTypes,
                                    'label' => false,
                                    'class' => 'input-xlarge'
                                )
                            );
                            ?>

                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Com</label>
                        <?php
                        $coms = $this->requestAction('coms/index');
                        ?>
                        <div class="controls">
                            <?php foreach ($coms as $item): ?>
                                <label class="checkbox inline">
                                    <input type="checkbox" name="data[Com][name][]" id="com"
                                           value="<?php echo $item['Com']['id']; ?>"
                                           data-rule-required="true"/><?php echo $item['Com']['name']; ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>


                </div>
                <div class="span6">
                    <div class="control-group">
                        <label class="control-label" for="name"><?php echo __('Yêu cầu khởi tạo'); ?>:</label>

                        <div class="controls">
                            <div class="span12">
                                <?php echo $this->Form->input('doing_note', array('placeholder' => '', 'type' => 'textarea', 'class' => 'input-xlarge', 'label' => false)) ?>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="name"><?php echo __('Lưu ý ca trưởng/VP'); ?>:</label>

                        <div class="controls">
                            <div class="span12">
                                <?php echo $this->Form->input('sharing_note', array('placeholder' => '', 'type' => 'textarea', 'class' => 'input-xlarge', 'label' => false)) ?>
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="name"><?php echo __('Lưu ý đơn hàng'); ?>
                            :</label>

                        <div class="controls">
                            <div class="span12">
                                <?php echo $this->Form->input('init_note', array('placeholder' => '', 'type' => 'textarea', 'class' => 'input-xlarge', 'label' => false)) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group">
                            <label class="control-label">
                                <?php echo __('Không cần kiểm tra'); ?>:</label>

                            <div class="controls">
                                <?php echo $this->Form->input('done_test', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?>
                            </div>
                        </div>
                    <div class="control-group">
                        <label class="control-label" for="name"><?php echo __('Thứ tự'); ?>:</label>

                        <div class="controls">
                            <div class="span12">
                                <?php echo $this->Form->input('order', array('type' => 'text', 'class' => 'input-xlarge', 'value'=>'1', 'label' => false, 'data-rule-required' => 'true', 'data-rule-number' => 'true')); ?>
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
    function getCustomers(country_id) {
        $('.fixed_bottom').show();
        $.get("<?php echo $this->html->url(array('controller'=>'Customers','action'=>'getCustomers'),true)?>/" + country_id, function (data) {
            $("#customer").html(data);
            $('#Customer_id').attr('name','data[Customergroup][customer_id]').attr('class','input-xlarge');
            $('.fixed_bottom').hide();
        });
    }
</script>