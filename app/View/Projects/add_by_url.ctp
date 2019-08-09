<?php
//    echo $this->Form->create('Project');
//    echo $this->Form->input('Url');
//    echo $this->Form->end('a');
echo $this->element('top_page', array(
    'page_title' => 'Danh sách đơn hàng cập nhật tự động',
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
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><?php echo __('Quản lý đơn hàng'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li class="active">
            <?php echo __('Danh sách các đơn hàng tự động '); ?>
        </li>
    </ul>
</div>
<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i><?php echo __('Danh sách các đơn hàng tự động'); ?></h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">
                                <?php echo __('Quốc gia'); ?>:</label>

                            <div class="controls">
                                <?php echo $this->Form->input('Country_id', array('class' => 'span12', 'options' => $country, 'onchange' => 'getCustomers(this.value)', 'empty' => '--Chọn quốc gia--', 'div' => false, 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="control-group" id="customer">
                            <label class="control-label">
                                <?php echo __('Khách hàng'); ?>:</label>

                            <div class="controls">
                                <?php echo $this->Form->input('Customer_id', array('class' => 'span12', 'options' => array('empty' => '--Chọn khách hàng--'), 'div' => false, 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="control-group" id="customer_group">
                            <label class="control-label">
                                <?php echo __('Nhóm khách hàng'); ?>:</label>

                            <div class="controls">
                                <?php echo $this->Form->input('CustomerGroup_id', array('class' => 'span12', 'options' => array('' => '--Chọn nhóm khách hàng--'), 'div' => false, 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="control-group" id="customer_group">
                            <label class="control-label">
                                <?php echo __('Ngày tạo dự án'); ?>:</label>

                            <div class="controls">
                                <?php echo $this->Form->input('InputDate', array('type' => 'text', 'class' => 'datepicker span12', 'div' => false, 'label' => false)); ?>
                            </div>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div class="span6">
                        <div class="form-actions">
                            <input type="button" class="btn btn-primary" id="update_project"
                                   value="<?php echo __('Cập nhật danh sách') ?>">

                            <div><br>
                                Chú ý:<br>
                                - Tạo đơn hàng cần có thư mục <b>< Tên đơn hàng ></b><br>
                                - Copy file vào thư mục <b>< Tên đơn hàng > </b> để hệ thống tự cập nhật.
                                <h1 style="color:red; font-weight:bold; background:yellow; line-height:normal; text-align:center;">Tên thư mục không được chứa ký tự đặc biệt hoặc dấu cách</h1> (Hỗ trợ các ký tự A-Z, a-z, 0-9, _, -)
                            </div>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div style="clear: both;"></div>
                    <!--                    abc-->
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->Form->create('Project'); ?>
    <div class="span6" style="margin: 0">
        <div class="control-group">
            <label class="control-label">
                <?php echo __('Dự kiến trả hàng'); ?>:</label>

            <div class="controls">
<!--                --><?php //echo $this->Form->input('returnTime', array('type' => 'text', 'class' => 'datetimepicker span12', 'div' => false, 'label' => false, 'data-rule-required' => true)); ?>
                Sẽ được cập nhật khi kích hoạt dự án
            </div>
        </div>
    </div>
    <div class="span12" style="margin: 0;">
        <div class="span3">
            <div class="control-group">
                <label class="control-label" style="float: left; margin-right: 10px">
                    <?php echo __('Không cần kiểm tra'); ?>:</label>

                <div class="controls">
                    <?php echo $this->Form->input('HasCheck', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?>
                </div>
            </div>
        </div>
        <div class="span3">
            <div class="control-group">
                <label class="control-label" style="float: left; margin-right: 10px">
                    <?php echo __('Cho chia hàng tự động'); ?>:</label>

                <div class="controls">
                    <?php echo $this->Form->input('auto', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?>
                </div>
            </div>
        </div>
        <div class="span3">
            <div class="control-group">
                <label class="control-label" style="float: left; margin-right: 10px">
                    <?php echo __('Đơn hàng nặng'); ?>:</label>

                <div class="controls">
                    <?php echo $this->Form->input('project_weight', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?>
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
    <div style="clear: both;"></div>
    <div class="span12" style="margin: 0">
        <div class="span4">
            <div class="control-group">
                <label class="control-label"><?php echo __('Nhân viên review'); ?>:</label>

                <div class="controls">
                    <div class="span12">
                        <textarea name="NV_ten_1" id="NV_ten_1" readonly="true" class="span12"
                                  rows="2"></textarea>
                        <input type="hidden" value="" name="NV_ID_1" id="NV_ID_1"/>
                    </div>
                    <div class="span12">
                        <a data-toggle="modal" class="btn select-checkbox-user" onclick="getNVS(1)"
                           role="button" href="#modal-1">Chọn nhân viên</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="span4">
            <div class="control-group">
                <label class="control-label"><?php echo __('Nhân viên download'); ?>:</label>

                <div class="controls">
                    <div class="span12">
                        <textarea name="NV_ten_2" id="NV_ten_2" readonly="true" class="span12"
                                  rows="2"></textarea>
                        <input type="hidden" value="" name="NV_ID_2" id="NV_ID_2"/>
                    </div>
                    <div class="span12">
                        <a data-toggle="modal" class="btn select-checkbox-user" onclick="getNVS(2)"
                           role="button" href="#modal-1">Chọn nhân viên</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="span4">
            <div class="control-group">
                <label class="control-label"><?php echo __('Nhân viên khác'); ?>:</label>

                <div class="controls">
                    <div class="span12">
                        <textarea name="NV_ten_3" id="NV_ten_3" readonly="true" class="span12"
                                  rows="2"></textarea>
                        <input type="hidden" value="" name="NV_ID_3" id="NV_ID_3"/>
                    </div>
                    <div class="span12">
                        <a data-toggle="modal" class="btn select-checkbox-user" onclick="getNVS(3)"
                           role="button" href="#modal-1">Chọn nhân viên</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="clear: both;"></div>
    <table class="table table-advance" id="table1">
        <thead>
        <tr>
            <th class="text-center" style="width:18px"><input type="checkbox"/></th>
            <th class="text-center"><?php echo __('Tên đơn hàng'); ?></th>
            <th class="text-center"><?php echo __('Loại Com'); ?></th>
            <th class="text-center"><?php echo __('Số lượng file'); ?></th>
            <th class="text-center"><?php echo __('Ngày tạo'); ?></th>
            <th class="text-center"><?php echo __('Yêu cầu giao việc'); ?></th>
            <th class="text-center"><?php echo __('Yêu cầu nhân viên'); ?></th>
            <th class="text-center"><?php echo __('Đường dẫn'); ?></th>
        </tr>
        </thead>
        <tbody id="content_list_project">

        </tbody>
    </table>
    <div class="text-center">
        <input type="submit" class="btn btn-primary text-center" id="save_list_project"
               value="<?php echo __('Cập nhật các đơn hàng đã chọn') ?>">
    </div>
    <?php echo $this->Form->end(); ?>
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="modal-1"
     style="display: none;">

</div>
<footer>
    <?php
    echo $this->element('footer');
    ?>
</footer>
</div>
<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
<script>
    $('#save_list_project').click(function () {
        var values = new Array();
        $.each($("input[name='ck[]']:checked"), function () {
            values.push($(this).val());
        });
        if (values.length == 0) {
            alert('Bạn chưa chọn đơn hàng!');
            $('.fixed_bottom').hide();
            return false;
        } else {
            return true;
            $('.fixed_bottom').hide();
        }
    });
    $('#update_project').click(function () {
        $('.fixed_bottom').show();
        var country_id = $('#Country_id').val();
        var customer_id = $('#Customer_id').val();
        var customer_group_id = $('#CustomerGroup_id').val();
        var date = $('#InputDate').val();
        if (country_id != '' && customer_id != '' && customer_group_id != '' && date != '') {
            $.post("<?php echo Router::url(array('controller' => 'projects','action' => 'infoProjectAuto')); ?>", { 'country_id': country_id, 'customer_id': customer_id, 'customer_group_id': customer_group_id, 'date': date })
                .done(function (data) {
                    if (data) {
                        $('#content_list_project').html(data);
                        $('.fixed_bottom').hide();
                    }
                });
            ;
            $('.fixed_bottom').hide();
        } else {
            $('.fixed_bottom').hide();
            alert('Bạn phải chọn đầy đủ các thông tin để cập nhật danh sách chính xác!')
        }
    });
    function getCustomers(country_id) {
        $.get("<?php echo $this->html->url(array('controller'=>'Customers','action'=>'getCustomers'),true)?>/" + country_id, function (data) {
            $("#customer").html(data);
        });
    }

    function getCustomer_Groups(country_id) {
        $.get("<?php echo $this->html->url(array('controller'=>'CustomerGroups','action'=>'getCustomerGroups'),true)?>/" + country_id, function (data) {
            $("#customer_group").html(data);
        });
    }
    function getNVS(i) {
        $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'SelectUsers'),true)?>" + "/" + i, function (data) {
            $("#modal-1").html(data);
        });
    }

</script>