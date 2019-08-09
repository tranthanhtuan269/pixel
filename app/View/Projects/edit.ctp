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
            <?php echo __('Sửa đơn hàng'); ?>
        </li>
    </ul>
</div>
<!-- BEGIN Main Content -->
<div class="row-fluid">
<div class="span12">
<div class="box">
<div class="box-title">
    <h3><i class="icon-reorder"></i><?php echo 'Sửa đơn hàng'; ?></h3>

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
<div class="span6">
    <div class="control-group">
        <label class="control-label" for="name"><?php echo __('Tên đơn hàng'); ?>
            :</label>

        <div class="controls">
            <div class="span12">

                <?php echo $this->Form->input('Name', array('placeholder' => 'Tên đơn hàng', 'class' => 'input-xlarge span12', 'label' => false, 'disabled', 'data-rule-required' => 'true', 'data-rule-minlength' => '3')) ?>
                <?php echo $this->Form->input('ID', array('type' => 'hidden', 'class' => 'span12', 'label' => false)) ?>
            </div>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">
            <?php echo __('Quốc gia'); ?>:</label>

        <div class="controls">
            <?php echo $this->Form->input('Country_id', array('class' => 'span12', 'disabled', 'options' => $countries, 'onchange' => 'getCustomers(this.value)', 'empty' => '--Chọn quốc giá--', 'default' => $this->request->data['Customer']['country_id'], 'div' => false, 'label' => false)); ?>
        </div>
    </div>
    <div class="control-group" id="customer">
        <label class="control-label">
            <?php echo __('Khách hàng'); ?>:</label>
        <div class="controls">
            <?php echo $this->Form->input('Customer_id', array('class' => 'span12', 'disabled', 'options' => array('empty' => '--Chọn khách hàng--'), 'div' => false, 'label' => false)); ?>
        </div>
    </div>
    <div class="control-group" id="customer_group">
        <label class="control-label">
            <?php echo __('Nhóm khách hàng'); ?>:</label>

        <div class="controls">
            <?php echo $this->Form->input('CustomerGroup_id', array('class' => 'span12', 'disabled', 'options' => array('empty' => '--Chọn nhóm khách hàng--'), 'div' => false, 'label' => false)); ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">
            <?php echo __('Ngày nhận'); ?>:</label>

        <div class="controls">
            <?php echo $this->Form->input('InputDate', array('type' => 'text', 'class' => 'datepicker span12', 'readonly' => true, 'div' => false, 'label' => false)); ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">
            <?php echo __('Dự kiến trả hàng'); ?>:</label>

        <div class="controls">
            <?php echo $this->Form->input('returnTime', array('type' => 'text', 'class' => 'datetimepicker span12', 'div' => false, 'label' => false, 'data-rule-required' => false)); ?>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
<!--        <div class="span3">-->
<!--            <div class="control-group">-->
<!--                <label class="control-label">-->
<!--                    --><?php //echo __('Khẩn cấp'); ?><!--:</label>-->
<!---->
<!--                <div class="controls">-->
<!--                    --><?php //echo $this->Form->input('IsSpecial', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?>
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
        <div class="span3">
            <div class="control-group">
                <label class="control-label">
                    <?php echo __('Không cần kiểm tra'); ?>:</label>

                <div class="controls">
                    <?php echo $this->Form->input('HasCheck', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?>
                </div>
            </div>
        </div>
        <div class="span3">
            <div class="control-group">
                <label class="control-label">
                    <?php echo __('Cho chia hàng tự động'); ?>:</label>

                <div class="controls">
                    <?php echo $this->Form->input('auto', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?>
                </div>
            </div>
        </div>
        <div class="span3">
            <div class="control-group">
                <label class="control-label">
                    <?php echo __('Đơn hàng nặng'); ?>:</label>

                <div class="controls">
                    <?php echo $this->Form->input('project_weight', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="span6">

    <div class="control-group">
        <label class="control-label"><?php echo __('Nhân viên review'); ?>:</label>

        <div class="controls">
            <div class="span12">
                <textarea name="NV_ten_1" id="NV_ten_1" readonly="true" class="span12"
                          rows="2"><?php echo isset($usernames1) ? $usernames1 : '' ?></textarea>
                <input type="hidden" value="<?php echo $this->request->data['Project']['UserReview'] ?>"
                       name="NV_ID_1" id="NV_ID_1"/>
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
                <textarea name="NV_ten_2" id="NV_ten_2" readonly="true" class="span12"
                          rows="2"><?php echo isset($usernames2) ? $usernames2 : '' ?></textarea>
                <input type="hidden" value="<?php echo get($this->request->data['Project'],'user_download') ?>"
                       name="NV_ID_2" id="NV_ID_2"/>
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
                <textarea name="NV_ten_3" id="NV_ten_3" readonly="true" class="span12"
                          rows="2"><?php echo isset($usernames3) ? $usernames3 : '' ?></textarea>
                <input type="hidden" value="<?php echo get($this->request->data['Project'], 'user_khac') ?>"
                       name="NV_ID_3" id="NV_ID_3"/>
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
        <?php echo $this->Form->textarea('Note', array('class' => ' span12 ckeditor', 'div' => false, 'label' => false)); ?>
    </div>
    <?php echo __('Lưu ý ca trưởng/VP'); ?>:
    <div class="control-group">
        <?php echo $this->Form->textarea('RequireDevide', array('class' => ' span12 ckeditor', 'div' => false, 'label' => false)); ?>
    </div>
    <?php echo __('Lưu ý đơn hàng'); ?>:
    <div class="control-group">
        <?php echo $this->Form->textarea('Require', array('class' => 'span12 ckeditor', 'div' => false, 'label' => false)); ?>
    </div>
    <div class="control-group">
        <label class="control-label"><?php echo __('File đính kèm'); ?>:</label>

        <div class="controls">
            <?php echo $this->Form->input('File', array('type' => 'file', 'class' => 'span12', 'div' => false, 'label' => false)); ?>
            <?php echo $this->Form->input('OldFile', array('type' => 'hidden', 'class' => 'span12', 'div' => false, 'label' => false, 'value' => $this->request->data['Project']['File'])); ?>
        </div>
    </div>
    <?php if($this->request->data['Project']['File'] && $this->request->data['Project']['File'] != ''){ ?>
        <div class="control-group">
            <label class="control-label">File cũ:</label>

            <div class="controls">
                <?php $name_file = explode('/',$this->request->data['Project']['File']);
                echo $name_file[count($name_file) - 1];
                ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">
                <?php echo __('Xóa file cũ'); ?>:</label>

            <div class="controls">
                <?php echo $this->Form->input('DelFile', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?>
            </div>
        </div>
    <?php }?>
</div>
<div class="span10">
    <div class="control-group">
        <label class="control-label"><?php echo __('Nhóm loại xử lý'); ?>:</label>

        <div class="controls">
            <?php
            echo $this->Form->input('process_type_group_id', array('options' => $process_type_group,'value'=>$process_type_group_id, 'empty' => '--Chọn nhóm loại xử lý--', 'onchange' => 'getProcess(this.value)', 'class' => 'span12', 'div' => false, 'label' => false)); ?>
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
            <?php echo $this->Form->input('product_extension_id', array('options' => $productextension, 'value' =>$this->request->data['Project']['product_extension_id'], 'class' => 'span12', 'div' => false, 'label' => false)); ?>
        </div>
    </div>
</div>
</div>
<div style="clear: both;"></div>
</div>
<div style="clear: both;"></div>
</div>
<div class="span3">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h4 class="panel-title"><?php echo __('Thông tin com:'); ?></h4>
        </div>
        <div class="panel-body">

            <div class="control-group">
                <div class="span5 text-center">Tên COM</div>
                <div class="span3 text-center">SL</div>
                <div class="span4 text-center">Thời gian</div>
            </div>
            <div class="row-fluid nice-scroll"  tabindex="5000">
                <div>
                <?php if (isset($coms)) {
                    $tmp = array();
                    foreach ($coms as $k => $com) {
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
                                <?php echo $this->Form->input('Com-' . $com['Com']['id'], array('type' => 'text', 'class' => 'coms span12 text-center ', 'id' => 'Com-' . $com['Com']['id'], 'value' => (isset($datacom[$com['Com']['id']]) ? $datacom[$com['Com']['id']]['Quantity'] : '0'), 'data' => $com['Com']['time'], 'div' => false, 'label' => false)); ?></div>
                            <div class="time-coms span4 text-center "
                                 id="<?php echo 'Time-Com-' . $com['Com']['id'] ?>"> <?php echo isset($datacom[$com['Com']['id']]['Quantity']) ? ($datacom[$com['Com']['id']]['Quantity'] * $com['Com']['time']) : '0 ' ?>
                                Phút
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
    <div style="clear: both;"></div>
</div>

<div style="clear: both"></div>
<div class="span12" style="margin: 0 auto">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h4 style="float: left" class="panel-title"><?php echo __('Thêm file đặc biệt:'); ?></h4>
            <a style="float: right" href="#" class="btn btn-success add-row" data-number=""><i
                    class="icon-plus"></i></a>

            <div style="clear: both"></div>
        </div>
        <div class="panel-body">

<!--            --><?php //pr($feature_products);die?>
            <?php
            $i=1;
            if (count($feature_products) > 0) {
//                $i = 1;
                foreach ($feature_products as $k => $pro) {
//                    debug($pro);
                    ?>
                    <div class="row-fluid" id="item<?php echo $i ?>">
                        <div class="span3">
                            <div class="row-fluid">
                                <label class="span4" for="name"><?php echo __('Tên file  (Cách nhau dấu , )'); ?>
                                    :</label>

                                <div class="span8">
                                    <?php echo $this->Form->input('Product.'.$i.'.name', array('placeholder' => 'Tên file', 'value' => $pro['name_file_product'], 'rows' => 1, 'class' => 'input-xlarge span12 sp_name', 'label' => false, 'data-rule-minlength' => '3')) ?>
                                </div>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="row-fluid">
                                <label class="span4"><?php echo __('Nhóm loại xử lý'); ?>:</label>

                                <div class="span8">
                                    <?php echo $this->Form->input('Product.'.$i.'.process_type_group_id', array('options' => $process_type_group,'value'=>$pro['process_type_group_id_special'], 'empty' => '--Chọn nhóm loại xử lý--', 'onchange' => 'getProcessSpecial(this.value,'.$i.')', 'class' => 'span12', 'div' => false, 'label' => false)); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span3 processSpecial-<?php echo $i?>">
                            <div class="row-fluid">
                                <label class="span4"><?php echo __('Loại xử lý'); ?>:</label>

                                <div class="span8">
                                    <?php echo $this->Form->input('Product.'.$i.'.process_type_id', array('class' => 'span12', 'options' => $pro['processTypes_special'], 'value' => $pro['process_type_id'], 'div' => false, 'label' => false)); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="row-fluid">
                                <label class="span5"><?php echo __('Định dạng trả về'); ?>:</label>
                                <div class="span7">
                                    <?php echo $this->Form->input('Product.'.$i.'.product_extension_id', array('options' => $productextension, 'class' => 'span12', 'value' => $pro['product_extension_id'], 'div' => false, 'label' => false)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $i++;
                }
            } else {
                ?>
                <div class="row-fluid" id="item1">
                    <div class="span3">
                        <div class="row-fluid">
                            <label class="span4" for="name"><?php echo __('Tên file  (Cách nhau dấu , )'); ?>:</label>

                            <div class="span8">
                                <?php echo $this->Form->input('Product.1.name', array('placeholder' => 'Tên file', 'rows' => 1, 'class' => 'input-xlarge span12', 'label' => false)) ?>
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
                                <?php echo $this->Form->input('Product.1.product_extension_id', array('options' => $productextension, 'class' => 'span12', 'div' => false, 'label' => false)); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            $i++;
            } ?>
        </div>
    </div>
</div>
</div>
<?php echo $this->Form->input('Country_name', array('class' => 'span12', 'value' => '', 'type' => 'hidden', 'div' => false, 'label' => false)); ?>
<?php echo $this->Form->input('CustomerGroup_name', array('class' => 'span12', 'value' => '', 'type' => 'hidden', 'div' => false, 'label' => false)); ?>
<?php echo $this->Form->input('Customer_name', array('class' => 'span12', 'value' => '', 'type' => 'hidden', 'div' => false, 'label' => false)); ?>
<div class="form-actions clr">
    <a class="btn btn-primary " onclick="check()"><?php echo __('Lưu') ?></a>
    <a href="<?php echo $this->html->url(array('controller'=>'Projects','action'=>'index'),true)?>" class="btn" ><?php echo __('Hủy') ?></a>
</div>
<?php echo $this->Form->end(); ?>
</div>
</div>
</div>
<footer> <?php echo $this->element('footer'); ?> </footer>
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="modal-1"
     style="display: none;">
</div>
<!-- END Main Content -->
<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
<script>
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
    var com_info = 0;

    $(function () {
        $('.add-row').attr('data-number', <?php echo $i-1 ?>);
//        $('.com-item').hide();
        var name = $(this).attr('id');
        var value = $(this).val() * $(this).attr('data');
        $('#Time-' + name).text(value + " Phút");
        total();
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
        getCustomers(<?php echo $this->request->data['Customer']['country_id']?>, <?php echo $this->request->data['Customer']['id']?>);
        getCustomer_Groups(<?php echo $this->request->data['Customer']['id']?>, <?php echo ($this->request->data['CustomerGroup']['id'])?$this->request->data['CustomerGroup']['id']:0;?>);
    });

    function filldata() {
        $("#ProjectCountryName").val($('#ProjectCountryId').find(":selected").text());
        $("#ProjectCustomerGroupName").val($('#Customer_id').find(":selected").text());
        $("#ProjectCustomerName").val($('#CustomerGroup_id').find(":selected").text());
    }

    function getNVS(i) {
        $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'SelectUsers'),true)?>" + "/" + i, function (data) {
            $("#modal-1").html(data);
        });
    }
    function getProcess(country_id) {
        $('.fixed_bottom').show();
        $.get("<?php echo $this->html->url(array('controller'=>'Processtypes','action'=>'getProcess'),true)?>/" + country_id, function (data) {
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
    function getCustomers(country_id, selected) {
        $.get("<?php echo $this->html->url(array('controller'=>'Customers','action'=>'getCustomers'),true)?>/" + country_id + '/' + selected, function (data) {
            $("#customer").html(data);
        });


    }

    function deleterow(number) {
        $('#item' + number).remove();
        $('.add-row').attr('data-number', (number - 1));
    }

    function getCustomer_Groups(country_id, selected) {
        $.get("<?php echo $this->html->url(array('controller'=>'Customergroups','action'=>'getCustomerGroups'),true)?>/" + country_id + '/' + selected, function (data) {
            $("#customer_group").html(data);
            $("#Customer_id").attr('disabled','disabled');

            $('#CustomerGroup_id').val(<?php echo  $this->request->data['Project']['CustomerGroup_id'] ?>);
            $('#CustomerGroup_id').attr('disabled','disabled');

        });
        <?php if($this->request->data['Project']['CustomerGroup_id']){ ?>
        if(selected != 0){
            hideComs(selected);
        }
        <?php } ?>
    }
    function check() {
        filldata();
        <?php
            if($status == 6){
        ?>
        $("input").prop("disabled", false);
        $("select").prop("disabled", false);
        $("textarea").prop("disabled", false);
        <?php }?>
        var rs = 1;
        var j;
        var number = $('.add-row').attr('data-number');
        for (j=1;j<=number;j++) {
            if ($('#Product'+j+'Name').val() !== '' && $('#Product'+j+'ProcessTypeId').val() == '') {
                rs = 0;
                alert('Bạn cần chọn loại xử lý cho file đặc biệt');
            }
        }
        if($('#ProjectProcessTypeId').val()==''||$('#ProcessType_id').val()=='') {
            rs = 0;
            alert('Bạn cần chọn loại xử lý');
        }
        if ($('.input-total-coms').val()==0){
            alert('Hãy nhập số Com khởi tạo');
            rs = 0;
        }
        if(rs==1) {
            $('#ProjectEditForm').submit();
        }
    }
    function hideComs(ctgroup_id) {
        $('.com-item').hide();
        $.get("<?php echo $this->html->url(array('controller'=>'Customergroups','action'=>'getCustomergroupComs'),true)?>/" + ctgroup_id, function (data) {
            if (data.returnCode == 1) {
                var str = data.com_ids;
                var show = str.split(",");
                for (var i = 0; i < show.length; i++) {
                    $('#com-' + show[i]).show();
                }


//                $('#ProjectProcessTypeId').val(data.process_type_id);
//                    $('#ProjectRequireDevide').text(data.sharing_note);
//                    $('#ProjectRequire').text(data.doing_note);
//                    $('#ProjectNote').text(data.init_note);
                //                $('#ProjectProductTypeId').val(data.product_extension_id);
            }
        });


    }

    $(document).ready(function(){
        <?php
            if($status == 6){
        ?>
        $("input").prop("disabled", true);
        $("select").prop("disabled", true);
        $("textarea").prop("disabled", true);
        $("#ProjectRequireDevide").prop("disabled", false);
        $(".add-row").hide();
        <?php
            }
        ?>
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

</script>