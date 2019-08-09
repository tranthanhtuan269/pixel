<style type="text/css">
    .com-group-name {
        font-weight: bold;
        padding: 10px 0 10px 10px;
        margin: 10px 0;
        border-bottom: 1px solid black;
    }

    .com-group-name .collapsed i.icon-minus:before {
        content: "\f067";
    }
</style>
<?php
echo $this->element('top_page', array(
    'page_title' => 'Quản lý đơn hàng'
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
            <?php echo __('Thêm đơn hàng'); ?>
        </li>
    </ul>
</div>
<!-- BEGIN Main Content -->
<div class="row-fluid">
<div class="span12">
<div class="box">
<div class="box-title">
    <h3><i class="icon-reorder"></i><?php echo 'Thêm mới đơn hàng'; ?></h3>

    <div class="box-tool">
        <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
        <a data-action="close" href="#"><i class="icon-remove"></i></a>
    </div>
</div>
<div class="box-content">
<?php echo $this->Form->create('Project', array('class' => 'form-horizontal validation-form', 'type' => 'file', 'onsubmit' => 'return filldata()')); ?>
<div class="row-fluid">
<div class="span9">
<div class="panel panel-info">
<div class="panel-heading">
    <h4 class="panel-title"><?php echo __('Thông tin đơn hàng:'); ?></h4>
</div>
<div class="panel-body">
    <a data-number="1" class="btn btn-success addproject" href="#" style="float: right"><i
            class="icon-plus"></i> Thêm đơn hàng</a>

    <div class="span6">
        <div class="control-group" id="project1">
            <label class="control-label" for="name"><?php echo __('Tên đơn hàng'); ?>:</label>
            <div class="controls">
                <div class="span12">
                    <div class="error_name" style="color: red; display: none;">Tên đơn hàng đã tồn tại, hãy chọn tên khác</div>

                    <input type="text" class="input-xlarge span12" id="project_name" name="data[AllProject][0][Name]"
                           placeholder="Tên đơn hàng" data-rule-required="true" data-rule-minlength="3" onchange="check_name()"/>
                    <?php echo $this->Form->input('ProjectType_id', array('type' => 'hidden', 'value' => 1, 'class' => 'input-xlarge', 'class' => 'span12', 'label' => false)) ?>
                    <input type="hidden" id="number_project" name="data[Project][number_project]" value="0">
                </div>
            </div>
        </div>
    </div>
    <div style="clear: both;"></div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">
                <?php echo __('Quốc gia'); ?>:</label>

            <div class="controls">
                <?php echo $this->Form->input('Country_id', array('class' => 'span12', 'options' => $countries, 'onchange' => 'getCustomers(this.value)', 'empty' => '--Chọn quốc gia--', 'div' => false, 'label' => false, 'data-rule-required' => 'true')); ?>
            </div>
        </div>
        <div class="control-group" id="customer">
            <label class="control-label">
                <?php echo __('Khách hàng'); ?>:</label>

            <div class="controls">
                <?php echo $this->Form->input('Customer_id', array('class' => 'span12', 'empty' => '--Chọn khách hàng--', 'div' => false, 'label' => false, 'data-rule-required' => 'true')); ?>
            </div>
        </div>
        <div class="control-group" id="customer_group">
            <label class="control-label">
                <?php echo __('Nhóm khách hàng'); ?>:</label>

            <div class="controls">
                <?php echo $this->Form->input('CustomerGroup_id', array('class' => 'span12', 'empty' => '--Chọn nhóm khách hàng--', 'div' => false, 'label' => false, 'data-rule-required' => 'true')); ?>
            </div>
        </div>
    </div>
    <div id="new_project" class="span12">
        <div data-width="1500" style="width: 1500px" id="content_project">

        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">
                <?php echo __('Ngày nhận'); ?>:</label>

            <div class="controls">
                <?php echo $this->Form->input('InputDate', array('type' => 'text', 'class' => 'datetimepicker span12', 'value' => date('d/m/Y H:i:s'), 'div' => false, 'label' => false)); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">
                <?php echo __('Dự kiến trả hàng'); ?>:</label>

            <div class="controls">
                <?php echo $this->Form->input('returnTime', array('type' => 'text', 'class' => 'datetimepicker span12', 'div' => false, 'label' => false)); ?>
<!--           Sẽ được cập nhật khi kích hoạt dự án-->
            </div>
        </div>
        <div class="row-fluid">
        </div>

    </div>
    <div style="clear: both;"></div>
    <div class="span12">
        <div class="span4">
            <div class="control-group">
                <label class="control-label">
                    <?php echo __('Cho phép trùng'); ?>:</label>

                <div class="controls">
                    <?php echo $this->Form->input('duplicate', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?>
                </div>
            </div>
        </div>
        <div class="span4">
            <div class="control-group">
                <label class="control-label">
                    <?php echo __('Không cần kiểm tra'); ?>:</label>

                <div class="controls">
                    <?php echo $this->Form->input('HasCheck', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?>
                </div>
            </div>
        </div>
        <div class="span4">
            <div class="control-group">
                <label class="control-label">
                    <?php echo __('Cho chia hàng tự động'); ?>:</label>

                <div class="controls">
                    <?php echo $this->Form->input('auto', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?>
                </div>
            </div>
        </div>
        <div class="span4">
            <div class="control-group">
                <label class="control-label">
                    <?php echo __('Đơn hàng nặng'); ?>:</label>

                <div class="controls">
                    <?php echo $this->Form->input('project_weight', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label"><?php echo __('Nhân viên review'); ?>:</label>

            <div class="controls">
                <div class="span12">
                    <textarea name="NV_ten_1" id="NV_ten_1" readonly="true" class="span12" rows="2"></textarea>
                    <input type="hidden" value="" name="NV_ID_1" id="NV_ID_1"/>
                </div>
                <div class="span12">
                    <a data-toggle="modal" class="btn select-checkbox-user" onclick="getNVS(1)"
                       role="button" href="#modal-1">Chọn nhân viên</a>
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="control-group">
            <label class="control-label"><?php echo __('Nhân viên download'); ?>:</label>

            <div class="controls">
                <div class="span12">
                    <textarea name="NV_ten_2" id="NV_ten_2" readonly="true" class="span12" rows="2"></textarea>
                    <input type="hidden" value="" name="NV_ID_2" id="NV_ID_2"/>
                </div>
                <div class="span12">
                    <a data-toggle="modal" class="btn select-checkbox-user" onclick="getNVS(2)"
                       role="button" href="#modal-1">Chọn nhân viên</a>
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="control-group">
            <label class="control-label"><?php echo __('Nhân viên khác'); ?>:</label>

            <div class="controls">
                <div class="span12">
                    <textarea name="NV_ten_3" id="NV_ten_3" readonly="true" class="span12" rows="2"></textarea>
                    <input type="hidden" value="" name="NV_ID_3" id="NV_ID_3"/>
                </div>
                <div class="span12">
                    <a data-toggle="modal" class="btn select-checkbox-user" onclick="getNVS(3)"
                       role="button" href="#modal-1">Chọn nhân viên</a>
                </div>
            </div>
        </div>
    </div>
    <div class="span11">
        <?php echo __('Yêu cầu khởi tạo'); ?>:
        <div class="control-group">

            <?php echo $this->Form->textarea('Note', array('class' => 'span12 ckeditor', 'div' => false, 'label' => false)); ?>
        </div>
        <?php echo __('Lưu ý ca trưởng/VP'); ?>:
        <div class="control-group">
            <?php echo $this->Form->textarea('RequireDevide', array('class' => ' span12 ckeditor', 'div' => false, 'label' => false)); ?>
        </div>
        <?php echo __('Lưu ý đơn hàng'); ?>:
        <div class="control-group">
            <?php echo $this->Form->textarea('Require', array('id' => 'Require', 'class' => ' span12 ckeditor', 'div' => false, 'label' => false)); ?>
        </div>
        <div class="control-group">
            <label class="control-label"><?php echo __('File đính kèm'); ?>:</label>

            <div class="controls">
                <?php echo $this->Form->input('File', array('type' => 'file', 'class' => 'span12', 'div' => false, 'label' => false)); ?>
                <div id="cusgroup_file">

                </div>
            </div>

        </div>

    </div>
    <div class="span10">
        <div class="control-group">
            <label class="control-label">
                <?php echo __('Gửi theo email1'); ?>:</label>

            <div class="controls">
                <?php echo $this->Form->input('email_1', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><?php echo __('Email khách hàng'); ?>:</label>

            <div class="controls">
                <?php
                echo $this->Form->input('Email', array('class' => 'span12', 'div' => false, 'label' => false)); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><?php echo __('Cc'); ?>:</label>

            <div class="controls">
                <?php
                echo $this->Form->input('EmailCc', array('class' => 'span12', 'div' => false, 'label' => false)); ?>
                <div>Lưu ý: Mỗi email ngăn cách nhau bằng dấu phẩy!</div>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><?php echo __('Nhóm loại xử lý'); ?>:</label>

            <div class="controls">
                <?php
                echo $this->Form->input('process_type_group_id', array('options' => $process_type_group, 'empty' => '--Chọn nhóm loại xử lý--', 'onchange' => 'getProcess(this.value)', 'class' => 'span12', 'div' => false, 'label' => false)); ?>
            </div>
        </div>
        <div class="control-group" id="process">
            <label class="control-label"><?php echo __('Loại xử lý'); ?>:</label>

            <div class="controls">
                <?php echo $this->Form->input('ProcessType_id', array('class' => 'span12', 'empty' => '--Chọn loại xử lý--', 'div' => false, 'label' => false)); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><?php echo __('Định dạng trở về'); ?>:</label>

            <div class="controls">
                <?php echo $this->Form->input('product_extension_id', array('options' => $productextension, 'value' => '11', 'class' => 'span12', 'div' => false, 'label' => false)); ?>
            </div>
        </div>
    </div>
</div>
<div style="clear: both"></div>
</div>
</div>
<div class="span3">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h4 class="panel-title"><?php echo __('Thông tin com:'); ?></h4>
        </div>
        <div class="panel-body" id="show-coms">

            <div class="control-group">
                <div class="span5 text-center">Tên COM</div>
                <div class="span3 text-center">SL</div>
                <div class="span4 text-center">Thời gian</div>
            </div>
            <div class="row-fluid nice-scroll" style="" tabindex="5000">
                <div>
                    <?php if (isset($coms)) {
                    $default = '';
                    $name_com = '';
                    $group = '';
                    $tmp = array();
                    foreach ($coms as $k => $com) {
                    $default    .= $com['Com']['id'] . ",";
                    $group      .= $com['GroupCom']['name'].",";
                    $name_com   .= $com['Com']['name'] . ",";

                    ?>
                    <?php if (!array_key_exists($com['Com']['group_com_id'], $tmp)){ ?>
                </div>
                <div class="com-group-name" onclick="toogle">
                    <a data-toggle="collapse" href="#com-group-<?php echo $com['Com']['group_com_id'] ?>"
                       aria-expanded="false" aria-controls="com-group-<?php echo $com['Com']['group_com_id'] ?>">
                        <i class="icon-minus"></i> <?php echo $com['GroupCom']['name'] ? $com['GroupCom']['name'] : "Chưa chọn nhóm"; ?>
                    </a>
                </div>

                <div id="com-group-<?php echo $com['Com']['group_com_id'] ?>" class="collapse in">
                    <?php
                    }
                    $tmp[$com['Com']['group_com_id']] = 1;
                    ?>
                    <div class="control-group com-item" id="com-<?php echo $com['Com']['id'] ?>">
                        <div class="span5"><?php echo $com['Com']['name']; ?>:</label></div>
                        <div class="span3 text-center ">
                            <?php echo $this->Form->input('Com', array('name' => 'data[AllProject][0][Com-' . $com['Com']['id'] . ']', 'type' => 'text', 'class' => 'coms span12 text-center ', 'id' => 'Com-' . $com['Com']['id'], 'data' => $com['Com']['time'], 'div' => false, 'label' => false, 'value' => 0)); ?></div>
                        <div class="time-coms span4 text-center "
                             id="<?php echo 'Time-Com-' . $com['Com']['id'] ?>">0 Phút
                        </div>
                    </div>
                    <?php
                    }
                    } ?>
                </div>
                <input type="hidden" value="<?php echo isset($coms) ? count($coms) : 0 ?>" id="countcoms">
            </div>
            <hr/>
            <div class="control-group">
                <div class="span5">&nbsp;</div>
                <div class="total-coms span3 text-center ">0</div>
                <div class="total-time-coms span4 text-center ">0 Phút</div>
                <input type="hidden" class="input-total-time-coms" name="data[Project][SpentTime]" value=""
                       id="countcoms">
                <input type="hidden" class="input-total-coms" name="data[Project][Quantity]" value="" id="countcoms">
            </div>
        </div>

    </div>
    <div style="clear: both"></div>
</div>
<div style="clear: both"></div>
<div class="span12" style="margin: 0 auto">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h4 style="float: left" class="panel-title"><?php echo __('Thêm file đặc biệt:'); ?></h4>
            <a style="float: right" href="#" class="btn btn-success add-row" data-number="1"><i
                    class="icon-plus"></i></a>

            <div style="clear: both"></div>
        </div>
        <div class="panel-body">
            <div class="row-fluid" id="item1">
                <div class="span3">
                    <div class="row-fluid">
                        <label class="span4" for="name"><?php echo __('Tên file  (Cách nhau dấu , )'); ?>:</label>

                        <div class="span8">
                            <?php echo $this->Form->input('Product.1.name', array('placeholder' => 'Tên file', 'rows' => 1, 'class' => 'input-xlarge span12', 'label' => false, 'data-rule-minlength' => '3')) ?>
                        </div>
                    </div>
                </div>
                <div class="span3">
                    <div class="row-fluid">
                        <label class="span4"><?php echo __('Nhóm loại xử lý'); ?>:</label>

                        <div class="span8">
                            <?php echo $this->Form->input('Product.1.process_type_group_id', array('options' => $process_type_group, 'empty' => '--Chọn nhóm loại xử lý--', 'onchange' => 'getProcessSpecial(this.value,1)', 'class' => 'span12', 'div' => false, 'label' => false)); ?>
                        </div>
                    </div>
                </div>
                <div class="span3 processSpecial-1">
                    <div class="row-fluid">
                        <label class="span4"><?php echo __('Loại xử lý'); ?>:</label>

                        <div class="span8">
                            <?php echo $this->Form->input('Product.1.process_type_id', array('class' => 'span12', 'empty' => 'Chọn loại xử lý', 'div' => false, 'label' => false)); ?>
                        </div>
                    </div>
                </div>
                <div class="span3">
                    <div class="row-fluid">
                        <label class="span5"><?php echo __('Định dạng trả về'); ?>:</label>

                        <div class="span7">
                            <?php echo $this->Form->input('Product.1.product_extension_id', array('options' => $productextension, 'value' => '11', 'class' => 'span12', 'div' => false, 'label' => false)); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php echo $this->Form->input('Country_name', array('class' => 'span12', 'value' => '', 'type' => 'hidden', 'div' => false, 'label' => false)); ?>
<?php echo $this->Form->input('CustomerGroup_name', array('class' => 'span12', 'value' => '', 'type' => 'hidden', 'div' => false, 'label' => false)); ?>
<?php echo $this->Form->input('Customer_name', array('class' => 'span12', 'value' => '', 'type' => 'hidden', 'div' => false, 'label' => false)); ?>
<div class="form-actions clr">
<!--    <input type="submit" onclick="check()" class="btn btn-primary" value="--><?php //echo __('Lưu') ?><!--">-->
    <a class="btn btn-primary " onclick="check()"><?php echo __('Lưu') ?></a>
    <button type="reset" class="btn"><?php echo __('Hủy') ?></button>
</div>
<?php echo $this->Form->end(); ?>
</div>
</div>
</div>
<footer>
    <?php echo $this->element('footer'); ?>
</footer>
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="modal-1"
     style="display: none;">

</div>
<!-- END Main Content -->
<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
<script>
    $('#ProjectProcessTypeGroupId').val(2);
    $('#ProjectProcessTypeId').val(3);
    var count_project = 0;
    var default_coms = '<?php echo $default;?>';
    var name_coms = '<?php echo $name_com;?>';
    function add_com(com_number) {
        var com = default_coms.split(",");
        var name_com = name_coms.split(",");
        var str = '';

        for (i = 0; i < com.length; i++) {
            if (com[i] != '' && name_com[i] != '') {

                str += '<div class ="control-group com-item com-' + com[i] + '">';
                str += '   <div class="span5">' + name_com[i] + '</label></div>';
                str += '    <div class="span3 text-center ">';
                str += '       <span> <input name="data[AllProject][' + count_project + '][Com-' + com[i] + ']" type="text" class="coms span12 text-center " value="0"/> </span>';
                str += '   </div>';
                str += '</div>';

            }
        }
        $('#com-list-' + com_number).html(str);
    }
    function total() {
        var sum = 0;
        $('.time-coms').each(function () {
            sum += parseFloat($(this).text());
        });
        $('.total-time-coms').text(sum + " Phút");
        $('.input-total-time-coms').val(sum);
        var count = 0;
        $('.coms').each(function () {
            count += parseFloat($(this).val());
        });
        $('.total-coms').text(count);
        $('.input-total-coms').val(count);
    }

    $(function () {
//        $('.com-item').hide();
        $('.coms').change(function () {
            if ($(this).val() == '') {
                $(this).val(0);
            }
            var name = $(this).attr('id');
            var value = $(this).val() * $(this).attr('data');
            $('#Time-' + name).text(value + " Phút");
            total();
        });
        $('.add-row').click(function () {
            var number = $(this).attr('data-number');
            $('#item' + number).after("<div id='item" + (number * 1 + 1) + "' class='row-fluid'></div>");
            setInterval(number = number * 1 + 1);
            $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'rowHtml'),true)?>/" + number, function (data) {
                $('#item' + number).html(data);
            });
            $(this).attr('data-number', number);
        });
        $('.addproject').click(function () {
            $('#new_project').show();
            count_project++;
            var width = $('#content_project').attr('data-width');
            $('#content_project').html($('#content_project').html() +
                '<div id="name_project-' + count_project + '" class="span5 new_project" style="width: 330px" >' +
                '<div class="control-group" id="project">' +
                '<label class="control-label" for="name">Tên đơn hàng:</label>' +
                '   <div class="controls">' +
                '       <div style="position: relative;" class="span12">' +
                '           <input type="text" maxlength="150" data-rule-minlength="3" data-rule-required="true" class="span12" placeholder="Tên đơn hàng" name="data[AllProject][' + count_project + '][Name]"><a  data="' + count_project + '" class="cancel" onclick="cancel_click(' + count_project + ')" ><i class="icon-trash red"></i></a>' +
                '       </div>' +
                '   </div>' +
                '</div>' +
                '<div style="  margin-left: 140px;" id="com-list-' + count_project + '">' +
                '</div>' +
                '</div>');
            var width_new = parseInt(width) + (parseInt(count_project) * 330);
            $('#content_project').css('width', width_new + 'px');
            $('#number_project').val(count_project);
            add_com(count_project);
        });

    });

    function cancel_click(i) {
        $('#name_project-' + i).remove();
    }
    function filldata() {
        $("#ProjectCountryName").val($('#ProjectCountryId').find(":selected").text());
        $("#ProjectCustomerGroupName").val($('#CustomerGroup_id').find(":selected").text());
        $("#ProjectCustomerName").val($('#Customer_id').find(":selected").text());

//        $("#ProjectProcessTypeGroupId").val($('#Customer_id').find(":selected").text());
    }

    function deleterow(number) {
        $('#item' + number).remove();
        $('.add-row').attr('data-number', (number - 1));
    }

    function getNVS(i) {
        $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'SelectUsers'),true)?>" + "/" + i, function (data) {
            $("#modal-1").html(data);
        });
    }


    function getCustomers(country_id) {
        $('.fixed_bottom').show();
        $.get("<?php echo $this->html->url(array('controller'=>'Customers','action'=>'getCustomers'),true)?>/" + country_id, function (data) {
            $("#customer").html(data);
            $('.fixed_bottom').hide();
        });
    }

    function getProcess(val) {
        $('.fixed_bottom').show();
        var number = 0;
        $.get("<?php echo $this->html->url(array('controller'=>'Processtypes','action'=>'getProcess'),true)?>/" + val + "/" + number, function (data) {
            $("#process").html(data);
            $('.fixed_bottom').hide();
        });
    }
    function getProcessSpecial(val, number) {
        $('.fixed_bottom').show();
        $.get("<?php echo $this->html->url(array('controller'=>'Processtypes','action'=>'getProcess'),true)?>/" + val + "/" + number, function (data) {
            $(".processSpecial-" + number).html(data);
            $('.fixed_bottom').hide();
        });
    }

    function getCustomer_Groups(country_id) {
        $('.fixed_bottom').show();
        $.get("<?php echo $this->html->url(array('controller'=>'Customergroups','action'=>'getCustomerGroups'),true)?>/" + country_id, function (data) {
            $("#customer_group").html(data);
            $('.fixed_bottom').hide();

           // getEmail(country_id);
        });
    }

    function hideComs(ctgroup_id) {
        $('.fixed_bottom').show();
        $('.com-item').hide();
        if (ctgroup_id == '') {
            $('.com-item').show();
        } else {
            $.get("<?php echo $this->html->url(array('controller'=>'Customergroups','action'=>'getCustomergroupComs'),true)?>/" + ctgroup_id, function (data) {
                if (data.returnCode == 1) {
                    var str = data.com_ids;
                    default_coms = data.com_ids;
                    name_coms = "";
                    //                alert(str);
                    var show = str.split(",");
                    for (var i = 0; i < show.length; i++) {
                        $('#com-' + show[i]).show();
                        $('.com-' + show[i]).show();

                        name_coms += $('#com-' + show[i] + ' .span5').html() + ",";
                    }
                    $('#ProjectProcessTypeId').val(data.process_type_id);
                    $('#ProjectRequireDevide').text(data.sharing_note);
                    $('#ProjectRequire').text(data.doing_note);
                    $('#ProjectNote').text(data.init_note);
                    $('.fixed_bottom').hide();
                    //                $('#ProjectProductTypeId').val(data.product_extension_id);
                }
            });
        }
        getData(ctgroup_id);
    }

    function getData(cusgroup) {
        $('.fixed_bottom').show();
        if (cusgroup != '') {
            $.get("<?php echo $this->html->url(array('controller'=>'Customergroups','action'=>'getCustomergroupData'),true)?>/" + cusgroup, function (data) {
                if (data.returnCode == 1) {
                    var today = new Date();
                    if(data.deliver_time !="" && data.deliver_time > 0){
                        today.setHours(today.getHours() + data.deliver_time*1);
                    }else{
                        today.setHours(today.getHours() + 12);
                    }
//                    alert(data.help_file);
                    var help_file = data.help_file;
                    help_file = help_file.replace(/ /g,'_');
                    $('#cusgroup_file').html('<a href="<?php echo $domain?>medias/Customergroup_files/'+help_file+'" download>'+help_file+'</a>');
                    $('#ProjectProcessTypeGroupId').val(data.process_type_group_id);
                    $('#ProjectProcessTypeId').val(data.process_type_id);
                    //$('#ProjectHasCheck').val(data.done_test);
                    $('#ProjectProductExtensionId').val(data.product_extension_id);
                    $('#ProjectReturnTime').val(today.getDate() + "/" + (today.getMonth()+1) + "/" + today.getFullYear() + " " + today.getHours()+ ":" + today.getMinutes());
//                    alert(data.init_note);
                    CKEDITOR.instances.Require.setData(data.doing_note);
                    CKEDITOR.instances.ProjectRequireDevide.setData(data.sharing_note);
                    CKEDITOR.instances.ProjectNote.setData(data.init_note);
                    //                $('#ProjectProductTypeId').val(data.product_extension_id);
                }
            });
        }
        $('.fixed_bottom').hide();
    }


    $(document).ready(function(){
        $('input.coms').focusin(function(){
            if($(this).val() == 0){
                $(this).val("");
            }
        });
        $('input.coms').focusout(function(){
            if($(this).val() == ""){
                $(this).val(0);
            }
        });
    });
<!--    function getEmail(customer_id) {-->
<!--        if (customer_id != '') {-->
<!--            $.get("--><?php //echo $this->html->url(array('controller'=>'Customers','action'=>'getEmail'),true)?><!--/" + customer_id, function (data) {-->
<!--                if (data.returnCode == 1) {-->
<!--                    $('#ProjectEmail').val(data.email);-->
<!--                    for(var instanceName in CKEDITOR.instances) {-->
<!--                        console.log( CKEDITOR.instances[instanceName] );-->
<!--                    }-->
<!--                    alert(data.sharing_note);-->
<!--                   CKEDITOR.instances.Require.setData(data.sharing_note);-->
<!--                   CKEDITOR.instances['data[Project][RequireDevide]'].setData(data.sharing_note);-->
<!--                   CKEDITOR.instances['data[Project][Note]'].setData(data.doing_note);-->
<!--                                   $('#ProjectProductTypeId').val(data.product_extension_id);-->
<!--                }-->
<!--            });-->
<!--        }-->
<!--    }-->

    function check() {
        filldata();
        var rs = 1;
        var j;
        var number = $('.add-row').attr('data-number');
        for (j=1;j<=number;j++) {
            if ($('#Product'+j+'Name').val() !== '' && $('#Product'+j+'ProcessTypeId').val() == '') {
                rs = 0;
                alert('Bạn cần chọn loại xử lý cho file đặc biệt');
            }
        }
        if ($('.input-total-coms').val()==0){
            alert('Hãy nhập số Com khởi tạo');
            rs = 0;
        }
        if($('#ProjectProcessTypeId').val()=='') {
            rs = 0;
            alert('Bạn cần chọn loại xử lý');
        }
        if(rs==1) {
            $('#ProjectAddForm').submit();
        }
    }

    function check_name(){

        $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'check_project_name'),true)?>/" + $('#project_name').val(), function (data) {
            if (data == "OK") {
             }else{
                alert('Tên đơn hàng đã tồn tại, hãy chọn tên khác');
            }

        });
    }
</script>