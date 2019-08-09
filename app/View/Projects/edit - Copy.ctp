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
                        <?php echo $this->Form->input('Name', array('placeholder' => 'Tên đơn hàng', 'class' => 'input-xlarge span12', 'label' => false,'readonly'=>true, 'data-rule-required' => 'true', 'data-rule-minlength' => '3')) ?>
                        <?php echo $this->Form->input('ID', array('type' => 'hidden', 'class' => 'span12', 'label' => false)) ?>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">
                    <?php echo __('Ngày nhận'); ?>:</label>

                <div class="controls">
                    <?php echo $this->Form->input('InputDate', array('type' => 'text', 'class' => 'datepicker span12','readonly'=>true, 'div' => false, 'label' => false)); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">
                    <?php echo __('Dự kiến trả hàng'); ?>:</label>

                <div class="controls">
                    <?php echo $this->Form->input('returnTime', array('type' => 'text', 'class' => 'datetimepicker span12', 'div' => false, 'label' => false)); ?>
                </div>
            </div>
            <div class="row-fluid">
                <div class="control-group  ">
                    <label class="control-label">
                        <?php echo __('Khẩn cấp'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('IsSpecial', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="control-group  ">
                    <label class="control-label">
                        <?php echo __('Không cần kiểm tra'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('HasCheck', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">
                        <?php echo __('Cho chia hàng tự động'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('auto', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><?php echo __('File đính kèm'); ?>:</label>

                <div class="controls">
                    <?php echo $this->Form->input('File', array('type' => 'file', 'class' => 'span12', 'div' => false, 'label' => false)); ?>
                    <?php echo $this->Form->input('OldFile', array('type' => 'hidden', 'class' => 'span12', 'div' => false, 'label' => false, 'value' => $this->request->data['Project']['File'])); ?>
                </div>
            </div>
            <!--                                <div class="control-group">-->
            <!--                                    <label class="control-label">&nbsp</label>-->
            <!---->
            <!--                                    <div class="controls">-->
            <!--                                        <a href="-->
            <?php //echo str_replace('/', '\\', $this->request->data['Project']['File']) ?><!--">-->
            <!--                                            --><?php //echo str_replace("\\", '/', $this->request->data['Project']['File']) ?>
            <!--                                        </a>-->
            <!--                                    </div>-->
            <!--                                </div>-->

        </div>
        <div class="span6">
            <div class="control-group">
                <label class="control-label"><?php echo __('Nhân viên review'); ?>:</label>

                <div class="controls">
                    <div class="span12">
                        <textarea name="NV_ten" id="NV_ten" readonly="true" class="span12"
                                  rows="2"><?php echo isset($usernames) ? $usernames : '' ?></textarea>
                        <input type="hidden" value="<?php echo $this->request->data['Project']['UserReview'] ?>"
                               name="NV_ID" id="NV_ID"/>
                    </div>
                    <div class="span12">
                        <a data-toggle="modal" class="btn select-checkbox-user"
                           onclick="getNVS()"
                           role="button" href="#modal-1">Chọn nhân viên</a>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><?php echo __('Loại xử lý'); ?>:</label>

                <div class="controls">
                    <?php echo $this->Form->input('ProcessType_id', array('options' => $processTypes, 'class' => 'span12', 'div' => false, 'label' => false, 'default' => $this->request->data['Project']['ProcessType_id'])); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><?php echo __('Định dạng trả về'); ?>:</label>

                <div class="controls">
                    <?php echo $this->Form->input('ProductType_id', array('options' => $productTypes, 'class' => 'span12', 'div' => false, 'label' => false, 'default' => $this->request->data['Project']['ProductType_id'])); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">
                    <?php echo __('Quốc gia'); ?>:</label>

                <div class="controls">
                    <?php echo $this->Form->input('Country_id', array('class' => 'span12','readonly'=>true, 'options' => $countries, 'onchange' => 'getCustomers(this.value)', 'empty' => '--Chọn quốc giá--', 'default' => $this->request->data['Customer']['country_id'], 'div' => false, 'label' => false)); ?>
                </div>
            </div>
            <div class="control-group" id="customer">
                <label class="control-label">
                    <?php echo __('Khách hàng'); ?>:</label>

                <div class="controls">
                    <?php echo $this->Form->input('Customer_id', array('class' => 'span12','readonly'=>true, 'options' => array('empty' => '--Chọn khách hàng--'), 'div' => false, 'label' => false)); ?>
                </div>
            </div>
            <div class="control-group" id="customer_group">
                <label class="control-label">
                    <?php echo __('Nhóm khách hàng'); ?>:</label>

                <div class="controls">
                    <?php echo $this->Form->input('CustomerGroup_id', array('class' => 'span12','readonly'=>true, 'options' => array('empty' => '--Chọn nhóm khách hàng--'), 'div' => false, 'label' => false)); ?>
                </div>
            </div>
        </div>
        <div class="span11">
            <?php echo __('Yêu cầu đơn hàng'); ?>:
            <div class="control-group">
                <?php echo $this->Form->textarea('Require', array('class' => ' span12 ckeditor' , 'div' => false, 'label' => false)); ?>
            </div>
            <?php echo __('Yêu cầu chia hàng'); ?>:
            <div class="control-group">
                <?php echo $this->Form->textarea('RequireDevide', array('class' => ' span12 ckeditor' , 'div' => false, 'label' => false)); ?>
            </div>
            <?php echo __('Chú ý đơn hàng'); ?>:
            <div class="control-group">
                <?php echo $this->Form->textarea('Note', array('class' => 'span12 ckeditor' , 'div' => false, 'label' => false)); ?>
            </div>
        </div>
    </div>
    <div style="clear: both"></div>
</div>
<div>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h4 style="float: left" class="panel-title"><?php echo __('Thêm file đặc biệt:'); ?></h4>
            <a style="float: right" href="#" class="btn btn-success add-row" data-number="1"><i class="icon-plus"></i></a>

            <div style="clear: both"></div>
        </div>
        <div class="panel-body">
            <?php if (count($profeats) > 0) {
                $i = 1;
                foreach ($profeats as $k => $pro) {
                    $a = explode('-', $k);
                    ?>
                    <div class="row-fluid" id="item<?php echo $i ?>">
                        <div class="span5">
                            <div class="row-fluid">
                                <label class="span4" for="name"><?php echo __('Tên file  (Cách nhau dấu , )'); ?>
                                    :</label>

                                <div class="span8">
                                    <?php echo $this->Form->input('Product.1.name', array('placeholder' => 'Tên file', 'value' => $pro, 'rows' => 1, 'class' => 'input-xlarge span12', 'label' => false, 'data-rule-minlength' => '3')) ?>
                                </div>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="row-fluid">
                                <label class="span4"><?php echo __('Loại xử lý'); ?>:</label>

                                <div class="span8">
                                    <?php echo $this->Form->input('Product.1.process_type_id', array('class' => 'span12', 'options' => $processTypes, 'default' => $a[0], 'div' => false, 'label' => false)); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="row-fluid">
                                <label class="span5"><?php echo __('Định dạng trả về'); ?>:</label>

                                <div class="span7">
                                    <?php echo $this->Form->input('Product.1.product_type_id', array('options' => $productTypes, 'class' => 'span12', 'default' => $a[1], 'div' => false, 'label' => false)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $i++;
                }
            } else { ?>
                <div class="row-fluid" id="item1">
                    <div class="span5">
                        <div class="row-fluid">
                            <label class="span4" for="name"><?php echo __('Tên file  (Cách nhau dấu , )'); ?>:</label>

                            <div class="span8">
                                <?php echo $this->Form->input('Product.1.name', array('placeholder' => 'Tên file', 'rows' => 1, 'class' => 'input-xlarge span12', 'label' => false)) ?>
                            </div>
                        </div>
                    </div>
                    <div class="span3">
                        <div class="row-fluid">
                            <label class="span4"><?php echo __('Loại xử lý'); ?>:</label>

                            <div class="span8">
                                <?php echo $this->Form->input('Product.1.process_type_id', array('class' => 'datetimepicker span12', 'options' => $processTypes, 'div' => false, 'label' => false)); ?>
                            </div>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="row-fluid">
                            <label class="span5"><?php echo __('Định dạng trả về'); ?>:</label>

                            <div class="span7">
                                <?php echo $this->Form->input('Product.1.product_type_id', array('options' => $productTypes, 'class' => 'datetimepicker span12', 'div' => false, 'label' => false)); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
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
            <div class="row-fluid nice-scroll" style="height: 365px; overflow: hidden;" tabindex="5000">
                <?php if (isset($coms)) {
                    foreach ($coms as $k => $com) { ?>
                        <div class="control-group com-item" id="com-<?php echo $com['Com']['id'] ?>">
                            <div class="span5"><?php echo $com['Com']['name']; ?>:</label></div>
                            <div class="span3 text-center ">
                                <?php echo $this->Form->input('Com-' . $com['Com']['id'], array('type' => 'text', 'class' => 'coms span12 text-center ', 'id' => 'Com-' . $com['Com']['id'], 'value' => (isset($datacom[$com['Com']['id']]) ? $datacom[$com['Com']['id']]['Quantity'] : '0'), 'data' => $com['Com']['time'], 'div' => false, 'label' => false)); ?></div>
                            <div class="time-coms span4 text-center "
                                 id="<?php echo 'Time-Com-' . $com['Com']['id'] ?>"> <?php echo isset($datacom[$com['Com']['id']]['Quantity']) ? ($datacom[$com['Com']['id']]['Quantity'] * $com['Com']['time']) : '0 ' ?>
                                Phút
                            </div>
                        </div>
                    <?php }
                } ?>
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
</div>
</div>
<?php echo $this->Form->input('Country_name', array('class' => 'span12', 'value' => '', 'type' => 'hidden', 'div' => false, 'label' => false)); ?>
<?php echo $this->Form->input('CustomerGroup_name', array('class' => 'span12', 'value' => '', 'type' => 'hidden', 'div' => false, 'label' => false)); ?>
<?php echo $this->Form->input('Customer_name', array('class' => 'span12', 'value' => '', 'type' => 'hidden', 'div' => false, 'label' => false)); ?>
<div class="form-actions clr">
    <input type="submit" class="btn btn-primary" value="<?php echo __('Lưu') ?>">
    <button type="reset" class="btn"><?php echo __('Hủy') ?></button>
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
//        $('.com-item').hide();
        var name = $(this).attr('id');
        var value = $(this).val() * $(this).attr('data');
        $('#Time-' + name).text(value + " Phút");
        total();
        $('.coms').change(function () {
            if($(this).val()==''){
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
        getCustomer_Groups(<?php echo $this->request->data['Customer']['id']?>, <?php echo $this->request->data['CustomerGroup']['id']?>);
    });

    function filldata() {
        $("#ProjectCountryName").val($('#ProjectCountryId').find(":selected").text());
        $("#ProjectCustomerGroupName").val($('#Customer_id').find(":selected").text());
        $("#ProjectCustomerName").val($('#CustomerGroup_id').find(":selected").text());
    }

    function getNVS() {
        $.get("<?php echo $this->html->url(array('controller'=>'Users','action'=>'SelectUsers'),true)?>", function (data) {
            $("#modal-1").html(data);
        });
    }
    function getCustomers(country_id, selected) {
        $.get("<?php echo $this->html->url(array('controller'=>'Customers','action'=>'getCustomers'),true)?>/" + country_id + '/' + selected, function (data) {
            $("#customer").html(data);
        });
    }

    function deleterow(number){
        $('#item'+number).remove();
        $('.add-row').attr('data-number',(number-1));
    }

    function getCustomer_Groups(country_id, selected) {
        $.get("<?php echo $this->html->url(array('controller'=>'Customergroups','action'=>'getCustomerGroups'),true)?>/" + country_id + '/' + selected, function (data) {
            $("#customer_group").html(data);
        });
        hideComs(selected);
    }
        function hideComs(ctgroup_id){
            $('.com-item').hide();
            $.get("<?php echo $this->html->url(array('controller'=>'Customergroups','action'=>'getCustomergroupComs'),true)?>/" + ctgroup_id, function (data) {
                if(data.returnCode==1){
                    var str = data.com_ids;
                    var show = str.split(",");
                    for (var i = 0; i < show.length; i++) {
                        $('#com-'+show[i]).show();
                    }


                    $('#ProjectProcessTypeId').val(data.process_type_id);
//                    $('#ProjectRequireDevide').text(data.sharing_note);
//                    $('#ProjectRequire').text(data.doing_note);
//                    $('#ProjectNote').text(data.init_note);
    //                $('#ProjectProductTypeId').val(data.product_extension_id);
                }
            });


        }


</script>