<?php
echo $this->element('top_page', array(
    'page_title' => 'Quản lý người dùng'
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
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><?php echo __('Quản lý người dùng'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li class="active">
            <?php echo __('Thêm mới người dùng'); ?>
        </li>
    </ul>
</div>
<!-- BEGIN Main Content -->
<div class="row-fluid">
<div class="span12">
<div class="box">
<div class="box-title">
    <h3><i class="icon-reorder"></i><?php echo __('Thêm mới người dùng'); ?></h3>

    <div class="box-tool">
        <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
        <a data-action="close" href="#"><i class="icon-remove"></i></a>
    </div>
</div>
<div class="box-content">
<?php echo $this->Form->create('User', array('class' => 'form-horizontal validation-form','type'=>'file')); ?>
<div class="row-fluid">
<div class="span6 ">
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo __('Thông tin cá nhân:'); ?></h4>
            </div>
            <div class="panel-body">
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Mã nhân viên'); ?>:<i
                            class="icon-star red icon_fix"></i></label>

                    <div class="controls">
                        <div class="span12">
                            <?php echo $this->Form->input('code_staff', array('placeholder' => '', 'class' => 'input-xlarge', 'label' => false, 'data-rule-required' => 'true', 'data-rule-minlength' => '3')) ?>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Họ tên'); ?>:<i
                            class="icon-star red icon_fix"></i></label>

                    <div class="controls">
                        <div class="span12">
                            <?php echo $this->Form->input('name', array('placeholder' => '', 'class' => 'input-xlarge', 'label' => false, 'data-rule-required' => 'true', 'data-rule-minlength' => '3')) ?>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Ngày sinh'); ?>:</label>

                    <div class="controls">
                        <div class="input-append date date-picker">
                            <?php echo $this->Form->input('date_of_birth', array('id' => 'datePick', 'type' => 'text', 'class' => 'datepicker', 'label' => false, 'div' => false)) ?>
                            <span class="add-on"><i class="icon-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Ngày vào làm'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('start_work_day', array('placeholder' => '', 'type' => 'text', 'class' => 'datepicker', 'label' => false, 'div' => false)) ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Ngày làm việc chính thức'); ?>:</label>

                    <div class="controls">

                        <?php echo $this->Form->input('day_work_official', array('placeholder' => '', 'type' => 'text', 'class' => 'datepicker', 'label' => false, 'div' => false)) ?>

                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Số CMT'); ?>:</label>

                    <div class="controls">
                        <div class="span12">
                            <?php echo $this->Form->input('id_number', array('placeholder' => '', 'type' => 'text', 'class' => 'input-xlarge', 'label' => false)) ?>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Email'); ?>:<i
                            class="icon-star red icon_fix"></i></label>

                    <div class="controls">
                        <div class="span12">
                            <?php echo $this->Form->input('email', array('placeholder' => '', 'class' => 'input-xlarge', 'label' => false, 'data-rule-required' => 'true', 'data-rule-email' => 'true')) ?>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('FTP'); ?>:</label>

                    <div class="controls">
                        <div class="span12">
                            <?php echo $this->Form->input('ftp', array('type' => 'text', 'class' => 'input-xlarge', 'label' => false)) ?>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Tên đăng nhập FTP'); ?>:</label>

                    <div class="controls">
                        <div class="span12">
                            <?php echo $this->Form->input('ftp_username', array('placeholder' => '', 'class' => 'input-xlarge', 'label' => false)) ?>
                        </div>
                    </div>
                </div> <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Mật khẩu FTP'); ?>:</label>

                    <div class="controls">
                        <div class="span12">
                            <?php echo $this->Form->input('ftp_password', array('type' => 'text', 'class' => 'input-xlarge', 'label' => false )) ?>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Số điện thoại'); ?>:</label>

                    <div class="controls">
                        <div class="span12">
                            <?php echo $this->Form->input('phone', array('placeholder' => '', 'type' => 'text', 'class' => 'input-xlarge', 'label' => false)) ?>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Địa chỉ'); ?>:</label>

                    <div class="controls">
                        <div class="span12">
                            <?php echo $this->Form->input('address', array('placeholder' => '', 'type' => 'text', 'class' => 'input-xlarge', 'label' => false)) ?>
                        </div>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Giới tính'); ?>:</label>

                    <div class="controls">
                        <label class="radio inline">
                            <input type="radio" value="0" name="data[User][gender]"> <?php echo __('Nữ'); ?>
                        </label>
                        <label class="radio inline">
                            <input type="radio" checked="" value="1"
                                   name="data[User][gender]"> <?php echo __('Nam'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo __('Phòng ban:'); ?><i
                            class="icon-star red icon_fix"></i></label>

                    <div class="controls">
                        <?php
                        echo $this->Form->input('department_id', array(
                            'type' => 'select',
                            'label' => false,
                            'class' => 'select2-choice select2-default',
                            'options' => $department,
                            'empty' => 'Chọn phòng ban',
                            'data-rule-required' => 'true'
                        ));
                        ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo __('Nhóm nhân viên:'); ?><i
                            class="icon-star red icon_fix"></i></label>

                    <div class="controls">
                        <?php
                        echo $this->Form->input('group_id', array(
                            'type' => 'select',
                            'label' => false,
                            'class' => 'select2-choice select2-default',
                            'options' => $groups,
                            'empty' => 'Chọn nhóm',
                            'data-rule-required' => 'true'
                        ));
                        ?>

                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Tài khoản skype:'); ?></label>

                    <div class="controls">
                        <div class="span12">
                            <?php echo $this->Form->input('skype', array('placeholder' => '', 'class' => 'input-xlarge', 'label' => false)) ?>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Lương cơ bản:'); ?></label>

                    <div class="controls">
                        <div class="span12">
                            <?php echo $this->Form->input('basic_salary', array('type' => 'text', 'class' => 'input-xlarge', 'label' => false)) ?>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Lương 1 ngày công:'); ?></label>

                    <div class="controls">
                        <div class="span12">
                            <?php echo $this->Form->input('daily_wage', array('type' => 'text','placeholder' => '', 'class' => 'input-xlarge', 'label' => false)) ?>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Ghi chú:'); ?></label>

                    <div class="controls">
                        <div class="span12">
                            <?php echo $this->Form->input('Note', array('placeholder' => '', 'class' => 'input-xlarge ckeditor', 'label' => false)) ?>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo __('Trạng thái:'); ?></label>

                    <div class="controls">
                        <?php
                        $status = array('1' => 'Có hiệu lực', '0' => 'Không hiệu lực');
                        echo $this->Form->input('status', array(
                            'type' => 'select',
                            'options' => $status,
                            'label' => false
                        ));
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BEGIN Left Side -->

    <!-- END Left Side -->
</div>
<div class="span6 ">
    <!-- BEGIN Right Side -->
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo __('Thông tin tài khoản:'); ?></h4>
            </div>
            <div class="panel-body">
                <div class="span6">
                    <div class="control-group">
                        <label class="control" for="name"><?php echo __('Tên đăng nhập'); ?>:<i
                                class="icon-star red icon_fix"></i></label>

                        <!--                        <div class="controls">-->
                        <!--                            <div class="span12">-->
                        <?php echo $this->Form->input('username', array('class' => 'input-medium', 'label' => false, 'data-rule-required' => 'true', 'data-rule-minlength' => '3')) ?>
                        <!--                            </div>-->
                        <!--                        </div>-->
                    </div>
                    <div class="control-group">
                        <label for="password" class="control"><?php echo __('Nhập mật khẩu'); ?>:<i
                                class="icon-star red icon_fix"></i></label>

                        <!--                        <div class="controls">-->
                        <!--                            <div class="span12">-->
                        <?php echo $this->Form->input('password', array('type' => 'password', 'placeholder' => '', 'class' => 'input-medium', 'label' => false, 'data-rule-required' => 'true', 'data-rule-minlength' => '6')) ?>
                        <!--                            </div>-->
                        <!--                        </div>-->
                    </div>
                    <div class="control-group">
                        <label for="password2" class="control"><?php echo __('Nhập lại mật khẩu'); ?>:<i
                                class="icon-star red icon_fix"></i></label>

                        <!--                        <div class="controls">-->
                        <!--                            <div class="span12">-->
                        <?php echo $this->Form->input('password2', array('type' => 'password', 'placeholder' => '', 'class' => 'input-medium password required', 'label' => false, 'data-rule-required' => 'true', 'data-rule-minlength' => '6', 'data-rule-equalto' => '#UserPassword')) ?>
                        <!--                            </div>-->
                        <!--                        </div>-->
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group">
                        <label class="control"><?php echo __('Ảnh đại diện:'); ?></label>

                        <!--                        <div class="controls">-->
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
                                               <input type="file" class="default" name="data[User][avatar]"></span>
                                <a data-dismiss="fileupload" class="btn fileupload-exists"
                                   href="#"><?php echo __('Hủy'); ?></a>
                            </div>
                            <!--                            </div>-->
                        </div>
                    </div>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>

        <hr>
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="panel-title"><?php echo __('Thông tin tài khoản ngân hàng và BHXH:'); ?></h4>
                </div>
                <div class="panel-body">
                    <div class="control-group">
                        <label class="control-label" for="name"><?php echo __('Tên ngân hàng'); ?>:</label>

                        <div class="controls">
                            <div class="span12">
                                <?php echo $this->Form->input('name_bank', array('placeholder' => '', 'class' => 'input-xlarge', 'label' => false)) ?>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="name"><?php echo __('Tên chủ tài khoản'); ?>:</label>

                        <div class="controls">
                            <div class="span12">
                                <?php echo $this->Form->input('username_bank', array('placeholder' => '', 'class' => 'input-xlarge', 'label' => false)) ?>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="name"><?php echo __('Số tài khoản'); ?>:</label>

                        <div class="controls">
                            <div class="span12">
                                <?php echo $this->Form->input('number_bank', array('placeholder' => '', 'type' => 'text', 'class' => 'input-xlarge', 'label' => false)) ?>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="name"><?php echo __('BHXH tự nguyện'); ?>:</label>

                        <div class="controls">
                            <div class="span12">
                                <?php echo $this->Form->input('BHXH_voluntary', array('type' => 'text','placeholder' => '', 'type' => 'text', 'class' => 'input-xlarge', 'label' => false)) ?>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="name"><?php echo __('BHXH nhân viên đóng'); ?>:</label>

                        <div class="controls">
                            <div class="span12">
                                <?php echo $this->Form->input('BHXH_staff', array('type' => 'text','placeholder' => '', 'type' => 'text', 'class' => 'input-xlarge', 'label' => false)) ?>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="name"><?php echo __('BHXH công ty đóng'); ?>:</label>

                        <div class="controls">
                            <div class="span12">
                                <?php echo $this->Form->input('BHXH_company', array('type' => 'text','placeholder' => '', 'type' => 'text', 'class' => 'input-xlarge', 'label' => false)) ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="panel-title"><?php echo __('Thông tin các khoản phụ cấp:'); ?></h4>
                </div>
                <div class="panel-body">
                    <div class="control-group">
                        <label class="control-label" for="name"><?php echo __('Phụ cấp ăn trưa'); ?>:</label>

                        <div class="controls">
                            <div class="span12">
                                <?php echo $this->Form->input('lunch_allowance', array('type' => 'text','placeholder' => '', 'class' => 'input-xlarge', 'label' => false)) ?>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="name"><?php echo __('Phụ cấp gửi xe'); ?>:</label>

                        <div class="controls">
                            <div class="span12">
                                <?php echo $this->Form->input('parking_allowance', array('type' => 'text','placeholder' => '', 'class' => 'input-xlarge', 'label' => false)) ?>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="name"><?php echo __('Phụ cấp tăng ca'); ?>:</label>

                        <div class="controls">
                            <div class="span12">
                                <?php echo $this->Form->input('overtime_allowance', array('type' => 'text','placeholder' => '', 'type' => 'text', 'class' => 'input-xlarge', 'label' => false)) ?>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="name"><?php echo __('Phụ cấp ca đêm'); ?>:</label>

                        <div class="controls">
                            <div class="span12">
                                <?php echo $this->Form->input('night_shift_allowance', array('type' => 'text','placeholder' => '', 'type' => 'text', 'class' => 'input-xlarge', 'label' => false)) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="panel-title"><?php echo __('Thông tin nhóm khách hàng:'); ?></h4>
                </div>
                <div class="panel-body" style="height: 300px; overflow: scroll">
                    <!--<div class="control-group">
                        <label class="control-label" for="name"><?php /*echo __('Quốc gia'); */?>:</label>

                        <div class="controls">
                            <div class="span12">
                                <?php /*echo $this->Form->input('Country_id', array('class' => 'span12', 'options' => $countries, 'onchange' => 'getCustomers(this.value)', 'empty' => '--Chọn quốc gia--', 'div' => false, 'label' => false, 'data-rule-required' => 'true')); */?>
                            </div>
                        </div>
                    </div>
                    <div class="control-group" id="customer">
                        <label class="control-label" for="name"><?php /*echo __('Khách hàng'); */?>:</label>

                        <div class="controls">
                            <div class="span12">
                                <?php /*echo $this->Form->input('Customer_id', array('class' => 'span12', 'empty' => '--Chọn khách hàng--', 'div' => false, 'label' => false, 'data-rule-required' => 'true')); */?>
                            </div>
                        </div>
                    </div>
                    <div class="control-group" id="customer_group">
                        <label class="control-label" for="name"><?php /*echo __('Nhóm khách hàng'); */?>:</label>

                        <div class="controls">
                            <div class="span12">
                            </div>
                        </div>-->
                    <div id="tree3"></div>
                    <div>Selected keys: <span id="echoSelection3">-</span></div>
                </div>

                <!--                    <input type="hidden" id="customer_group_id" name="customer_group_id">-->
                    <?php echo $this->Form->input('customer_group_id', array('type' => 'hidden','label' => false)) ?>
<!--                    <div>-->
<!--                        <div class="span3"></div>-->
<!--                        <input type="button" class="btn btn-primary" value="Thêm" onclick="addCustomer_Groups()">-->
<!--                    </div>-->
                </div>
            </div>

        </div>
        <!-- END Right Side -->
    </div>
</div>
<div style="clear: both"></div>
<div class="form-actions">
    <input type="submit" class="btn btn-primary" value="<?php echo __('Lưu') ?>">
    <button type="button" class="btn"><?php echo __('Hủy') ?></button>
</div>
</div>
<?php echo $this->Form->end(); ?>
</div>
</div>
</div>
</div>
<!-- END Main Content -->

<footer>
    <?php
    echo $this->element('footer');
    ?>
</footer>

<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
<script>
    var treeData = [
        <?php
        foreach ($countries as $country){
        ?>
        {title: "<?php echo $country['Country']['name']?>", folder: true,key: "country_<?php echo $country['Country']['id']?>",
            children: [
        <?php
            foreach($country['children'] as $customer){
        ?>

                {title: "<?php echo $customer['Customer']['name']?>", key: "cus_<?php echo $customer['Customer']['id']?>",
                    children: [
                        <?php
                            foreach($customer['children'] as $cus_gr){
                        ?>
                        {title: "<?php echo $cus_gr['CustomerGroup']['name']?>", key: "cusgr_<?php echo $cus_gr['CustomerGroup']['id']?>" },
                        <?php
                            }
                        ?>
                    ]
                },
        <?php
            }
            ?>
            ]},
        <?php
        }
        ?>
    ];
    $(function(){
        $("#tree3").fancytree({
//			extensions: ["select"],
            checkbox: true,
            selectMode: 3,
            source: treeData,
            loadChildren: function(event, ctx) {
                ctx.node.fixSelection3AfterClick();
            },
            select: function(event, data) {
                // Get a list of all selected nodes, and convert to a key array:
                var selKeys = $.map(data.tree.getSelectedNodes(), function(node){
                    return node.key;
                });
                $("#echoSelection3").text(selKeys.join(", "));
                $("#UserCustomerGroupId").val(selKeys.join(",")+',');

                // Get a list of all selected TOP nodes
                var selRootNodes = data.tree.getSelectedNodes(true);
                // ... and convert to a key array:
                var selRootKeys = $.map(selRootNodes, function(node){
                    return node.key;
                });
                $("#echoSelectionRootKeys3").text(selRootKeys.join(", "));
                $("#echoSelectionRoots3").text(selRootNodes.join(", "));
            },
            dblclick: function(event, data) {
                data.node.toggleSelected();
            },
            keydown: function(event, data) {
                if( event.which === 32 ) {
                    data.node.toggleSelected();
                    return false;
                }
            },
            // The following options are only required, if we have more than one tree on one page:
//				initId: "treeData",
            cookieId: "fancytree-Cb3",
            idPrefix: "fancytree-Cb3-"
        });
    });
</script>