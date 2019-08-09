<?php

echo $this->element('top_page', array(
    'page_title' => 'Quản lý dự án',
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
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><b><?php echo __('Quản lý dự án'); ?></b></a>
        </li>
    </ul>
</div>
<?php echo $this->Session->flash(); ?>
<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="box">
        <div class="span12">
            <div class="box  table-bordered">
                <div class="box-title">
                    <h3><i class="icon-search"></i>Chia hàng cho nhân viên</h3>

                    <div class="box-tool">
                        <a href="#" data-modal="setting-modal-1" data-action="config"><i class="icon-gear"></i></a>
                        <a href="#" data-action="collapse"><i class="icon-chevron-down"></i></a>
                        <a href="#" data-action="close"><i class="icon-remove"></i></a>
                    </div>
                </div>
                <div class="box-content">
                    <div class="row-fluid">
                        <div class="span5">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Thông tin đơn hàng</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row-fluid">
                                        <label class="span4"><?php echo __('Tên đơn hàng'); ?>:</label>

                                        <div class="span8">
                                            <?php echo $project['Project']['Name']; ?>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <label class="span4"><?php echo __('Mã khách hàng'); ?>:</label>

                                        <div class="span8">
                                            <?php echo $project['Customer']['customer_code']; ?>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <label class="span4"><?php echo __('Nhóm khách hàng'); ?>:</label>

                                        <div class="span8">
                                            <?php echo $project['CustomerGroup']['name']; ?>
                                        </div>
                                    </div>
                                    <div>
                                        <?php echo __('Yêu cầu chia hàng:'); ?>
                                        <br/>
                                    </div>
                                    <div class="row-fluid">
                                        <?php echo $this->Form->input('HasCheck', array('rows' => 3, 'class' => 'span11 ckeditor', 'value' => $project['Project']['RequireDevide'], 'div' => false, 'label' => false)); ?>
                                    </div>
                                    <?php if($project['Project']['File'] != null){?>
                                        <div class="row-fluid">
                                            <label class="span4"><?php echo __('File đính kèm'); ?>:</label>

                                            <div class="span8">
                                                <a class="download-file" style="cursor: pointer;margin-left: 20px;" href="<?php echo $domain . (str_replace('@', '/', $project['Project']['File'])); ?>"><?php echo $domain . (str_replace('@', '/', $project['Project']['File'])); ?>
                                                </a>
                                            </div>
                                        </div>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                        <div class="span7">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Giao việc</h4>
                                </div>
                                <div class="panel-body">
                                    <?php echo $this->Form->create('Project') ?>
                                    <div class="row-fluid">
                                        <label class="span4"><?php echo __('Ngày giao việc'); ?>:</label>

                                        <div class="span8">
                                            <?php echo $this->Form->input('deliver_date', array('type' => 'text', 'class' => 'span11', 'div' => false, 'label' => false, 'value' => date('d/m/Y'))); ?>
                                            <?php echo $this->Form->input('Feat_products', array('type' => 'hidden', 'class' => 'span11', 'div' => false, 'label' => false, 'value' => $feat)); ?>
                                            <?php echo $this->Form->input('Normal_products', array('type' => 'hidden', 'class' => 'span11', 'div' => false, 'label' => false, 'value' => $normal)); ?>
                                            <?php echo $this->Form->input('Project_id', array('type' => 'hidden', 'class' => 'span11', 'div' => false, 'label' => false, 'value' => $this->request->data['Product']['Project_id'])); ?>
                                            <?php echo $this->Form->input('save', array('type' => 'hidden', 'class' => 'span11', 'div' => false, 'label' => false, 'value' => 1)); ?>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <label class="span4"><?php echo __('Nhân viên được giao'); ?>:</label>

                                        <div class="span8">
                                            <div class="span12">
                                                <input type="text" name="NV_ten" id="NV_ten" readonly="true"
                                                       class="span11" rows="2">
                                                <input type="hidden" value="" name="NV_ID" id="NV_ID"/>
                                                <input type="hidden" value="" name="addzip" id="addzip"/>
                                            </div>
                                            <div class="span12">
                                                <a data-toggle="modal" class="btn select-checkbox-user"
                                                   onclick="getNVS(<?php echo $project['Project']['ID']?>)"
                                                   role="button" href="#modal-1">Chọn nhân viên</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <label class="span4"><?php echo __('Nhóm loại xử lý'); ?>:</label>

                                        <div class="span8">
                                            <?php
                                            echo $this->Form->input('process_type_group_id', array('options' => $process_type_group, 'empty' => '--Chọn nhóm loại xử lý--', 'onchange' => 'getProcess(this.value)', 'class' => 'span11', 'div' => false, 'label' => false)); ?>
                                        </div>
                                    </div>
                                    <div class="row-fluid" id="process">
                                        <label class="span4"><?php echo __('Loại xử lý'); ?>:</label>

                                        <div class="span8">
                                            <?php echo $this->Form->input('process_type_id', array('div' => false, 'class' => 'span11', 'options' => $processTypes, 'default' => $project['Project']['ProcessType_id'], 'empty' => '--Chọn loại xử lý--', 'label' => false)); ?>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <label class="span4"><?php echo __('Đinh dạng trả về'); ?>:</label>

                                        <div class="span8">
                                            <?php echo $this->Form->input('product_extension_id', array('div' => false, 'options' => $productextension, 'empty' => '--Chọn định dạng--', 'default' => $project['Project']['product_extension_id'], 'class' => 'span11', 'label' => false)); ?>
                                        </div>
                                        <div class="controls"><p class="required text-center">Chỉ có tác dụng với các
                                                file không phải đặc biệt</p></div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="control-group">
                                            <label class="span4">
                                                <?php echo __('Ưu tiên'); ?>:</label>

                                            <div class="span8">
                                                <div class="controls">
                                                    <?php echo $this->Form->input('priority', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--                            <div class="row-fluid">-->
                                    <!--                                <label class="span4">&nbsp;</label>-->
                                    <!--                                <div class="span8">-->
                                    <!--                                    <label class="checkbox span6">-->
                                    <!--                                        <input type="checkbox" id="export" value="1"> Xuất excel-->
                                    <!--                                        <input type="hidden" id="excelurl" name="data[ExcelUrl]" value="">-->
                                    <!--                                        <input type="hidden" id="urlfolder" name="data[UrlFolder]" value="-->
                                    <?php //echo $project['Project']['UrlFolder']?><!--">-->
                                    <!--                                    </label>-->
                                    <!--                                    <label class="checkbox span6">-->
                                    <!--                                        <input type="checkbox" id="addzip" name="addzip" value="1"> Nén zip-->
                                    <!--                                    </label>-->
                                    <!--                                </div>-->
                                    <!--                            </div>-->
                                    <div class="form-actions clr">
                                        <a class="btn btn-primary " onclick="check()"><?php echo __('Xác nhận') ?></a>
                                        <button type="reset" class="btn"><?php echo __('Hủy') ?></button>
                                    </div>
                                    <?php echo $this->Form->end() ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .grid-product-img {
        max-height: 100px;
        overflow: hidden;
        border: 1px solid #d9d9d9;
    }

    .grid-product-img .checkbox {
        width: 100%;
        height: 100px;
        padding-left: 30px;
    }
</style>
<footer>
    <?php echo $this->element('footer'); ?>
</footer>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="modal-1"
     style="display: none;">

</div>
<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
<script>
    function check() {
        if ($('#NV_ten').val() == '') {
            alert('Bạn cần chọn nhân viên để giao việc!');
        } else if ($('#ProjectDeliverDate').val() == '') {
            alert('Bạn cần chọn ngày giao việc!');
        }else if($('#ProjectProcessTypeId').val()==''){
            alert('Bạn cần chọn loại xử lý');
        }else if ($('#export').is(':checked')) {
            $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'export'),true)?>/" + $('#NV_ten').val() + '/' + $('#NV_ID').val())
                .done(function (data) {
                    if (data != 0) {
                        $('#excelurl').val(data);
                        $('#ProjectSetUserProductsForm').submit();
                    } else {
                        alert('Xuất excel lỗi! Hãy thử lại.');
                    }
                });
        } else {
            $('#ProjectSetUserProductsForm').submit();
        }
    }
    function getProcess(val) {
        $('.fixed_bottom').show();
        var number = 0;
        $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'getProcess'),true)?>/" + val + "/" + number, function (data) {
            $("#process").html(data);
            $('.fixed_bottom').hide();
        });
    }
    function getNVS(id) {
        $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'SelectUsersSetpd'),true)?>/"+id, function (data) {
            $("#modal-1").html(data);
        });
    }
</script>
<script>

</script>
