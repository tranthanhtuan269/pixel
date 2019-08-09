<?php

echo $this->element('top_page', array(
    'page_title' => 'Trang chủ',
));
?>
<?php echo $this->Session->flash(); ?>
<div class="row-fluid">
    <div class="span9">
        <div class="span6">
            <div class="span12">
                <div class="tile tile-green">
                    <div class="img top-fix">
                        <img style="width: 80px;height: 80px;" class="nav-user-photo cicle"
                             src="<?php echo $edit_profile['User']['avatar'] ? $domain . 'medias/avatar_employee/' . $edit_profile['User']['avatar'] : $this->webroot . 'img/no_images.jpg'; ?>"
                             alt="Penny's Photo">
                    </div>
                    <div class="content">
                        <p class="title"><?php echo __('Xin chào') ?></p>

                        <p class="big"><?php echo $edit_profile['User']['name']; ?></p>
                        <br>

                        <div class="clock"><?php echo __('Thời gian làm việc: '); ?></div>
                    </div>
                    <?php
                    if ($group_id == 1 || $group_id == 2 || $group_id == 3 || $group_id == 5 || $group_id == 7) {
                        ?>
                        <div class="img img-bottom-left">
                            <?php if ($dem_customer == 0) { ?>
                                <a id="start_work" data-original-title="Start thời gian làm việc của bạn">
                                    <button class="btn btn-warning btn-to-default"><i class="icon-play"></i> Start
                                    </button>
                                </a>
                            <?php } ?>
                            <?php if ($dem_customer == 1) { ?>
                                <a id="stop_work" data-toggle="modal" href="#modal-stop"
                                   data-original-title="Stop thời gian làm việc của bạn">
                                    <button class="btn btn-magenta btn-to-default"><i class="icon-stop"></i> Stop
                                    </button>
                                </a>
                            <?php } ?>
                        </div>
                        <div id="modal-stop" class="modal hide fade" tabindex="-1"
                             role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                                </button>
                                <h3 style="color: #000000"
                                    id="myModalLabel3"><?php echo __('Kết thúc thời gian làm việc'); ?></h3>
                            </div>
                            <div class="modal-body">
                                <div class="row-fluid">
                                    <div class="span4">
                                        <div class="control-group">
                                            <label style="color: #000000" class="control-label">
                                                <?php echo __('Quốc gia'); ?>:</label>

                                            <div class="controls">
                                                <?php echo $this->Form->input('Country_id', array('class' => 'span12', 'options' => $countries, 'onchange' => 'getCustomers(this.value)', 'empty' => '--Chọn quốc gia--', 'div' => false, 'label' => false)); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span4">
                                        <div class="control-group" id="customer">
                                            <label style="color: #000000" class="control-label">
                                                <?php echo __('Khách hàng'); ?>:</label>

                                            <div class="controls">
                                                <?php echo $this->Form->input('Customer_id', array('class' => 'span12', 'options' => array('empty' => '--Chọn khách hàng--'), 'div' => false, 'label' => false)); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span4">
                                        <div class="control-group" id="customer_group">
                                            <label style="color: #000000" class="control-label">
                                                <?php echo __('Nhóm khách hàng'); ?>:</label>

                                            <div class="controls">
                                                <?php echo $this->Form->input('CustomerGroup_id', array('class' => 'span12', 'options' => array('' => '--Chọn nhóm khách hàng--'), 'div' => false, 'label' => false)); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                         <div class="control-group" id="customer_group">
                                            <label style="color: #000000" class="control-label">
                                                <?php echo __('Nội dung'); ?>:</label>

                                            <div class="controls">
                                                <?php echo $this->Form->input('content', array('type'=>'textarea','class' => 'span12', 'div' => false, 'label' => false)); ?>
                                            </div>
                                        </div>
                                 </div>
                                <div style="color: red" id="error"></div>
                            </div>
                            <div class="modal-footer">
                                <button id="save-time-work" class="btn btn-primary">Lưu</button>
                                <button class="btn btn-gray" data-dismiss="modal">Hủy</button>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="img img-bottom">
                        <a href="<?php echo Router::url(array('controller' => 'vacations', 'action' => 'add')) ?>">
                            <button class="btn btn-circle btn-orange show-tooltip" title="Xin nghỉ"><i
                                    class="icon-edit"></i></button>
                        </a>
                        <a href="<?php echo Router::url('/admin/users/profile/' . $edit_profile['User']['id'], array('controller' => 'users', 'action' => 'profile', $edit_profile['User']['id'])); ?>">
                            <button class="btn btn-circle btn-magenta show-tooltip" title="Đổi mật khẩu"><i
                                    class="icon-cog"></i></button>
                        </a>
                        <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'logout')); ?>">
                            <button class="btn btn-circle btn-danger show-tooltip" title="Đăng xuất"><i
                                    class="icon-power-off"></i></button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="span12">
                <div class="tile">
                    <p class="title">Lịch sử đăng nhập <?php echo date("d/m/Y", strtotime($to_day)); ?></p>
                    <br>
                    <?php
                    $stt = 1;
                    if (isset($user_time)) {
                        foreach ($user_time as $item) {
                            ?>
                            <?php echo $stt ?>. Login: <?php echo date("H:i:s", strtotime($item['Timelogin']['time_login'])); ?> - Logout: <?php echo $item['Timelogin']['time_logout'] ? date("H:i:s", strtotime($item['Timelogin']['time_logout'])) : 'chưa thực hiện' ?>
                            <br/>
                            <?php $stt++;
                        }
                    }
                    ?>
                    <div class="img img-bottom">
                        <i class="icon-time"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="span3">
        <div class="span12">
            <div class="tile-active" style="height: 140px !important;">
                <div class="tile tile-magenta">
                    <p class="title"><?php echo __('Thông tin'); ?></p>

                    <div><?php echo __('1. Số lượng file hôm nay: ' . $file_of_date . ' file') ?></div>
                    <div><?php echo __('2. Số công trong tháng: ' . $this->requestAction(Router::url(array('action' => 'date_work'))) . ' công') ?></div>
                    <div><?php echo __('2. Số ngày nghỉ trong tháng: ' . $this->requestAction(Router::url(array('action' => 'vacation'))) . ' ngày') ?></div>
                    <div class="img img-bottom">
                        <i class="icon-warning-sign"></i>
                    </div>
                </div>

                <div class="tile tile-blue">
                    <div class="img img-center">
                        <i class="icon-envelope-alt"></i>
                    </div>
                    <p class="title text-center"><?php echo __('Tin nhắn mới'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<footer>
    <?php
    echo $this->element('footer');
    ?>
</footer>
<!-- END Main Content -->
<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
<script>
    $(document).ready(function () {
        $('#start_work').click(function () {
            $('.fixed_bottom').show();
            $.post("<?php echo Router::url(array('action' => 'start_work')); ?>", { 'status': 1 })
                .done(function (data) {
                    $.gritter.add({title: "Đã tính thời gian!", text: '<a href="#" style="color:#ccc"></a>', image: "<?php echo $this->webroot. 'img/notification.png';?>", sticky: false, time: ""});
                    window.location.reload();
                });
        });
        $('#save-time-work').click(function () {
            $('#error').html('');
            var country_id = $('#Country_id').val();
            var customer_id = $('#Customer_id').val();
            var customerGroup_id = $('#CustomerGroup_id').val();
            var content = $('#content').val();
             if (customer_id == 0) {
                $('#error').html('Bạn chưa chọn khách hàng!');
            }else{
                $.post("<?php echo Router::url(array('action' => 'stop_work')); ?>", { 'country_id': country_id,'customer_id':customer_id,'customerGroup_id':customerGroup_id,content: content })
                    .done(function (data) {
                        $.gritter.add({title: "Đã lưu!", text: '<a href="#" style="color:#ccc"></a>', image: "<?php echo $this->webroot. 'img/notification.png';?>", sticky: false, time: ""});
                        window.location.reload();
                    });
            }
        });
        $('#status_project').change(function () {
            var status = $(this).val();
            $.post("<?php echo Router::url(array('action' => 'list_project')); ?>", { 'status': status })
                .done(function (data) {
                    if (data) {
                        $('#list_project').html(data);
                    } else {
                        $.gritter.add({title: "Lỗi!", text: 'Không thực hiện được thao tác <a href="#" style="color:#ccc"></a>.', image: "<?php echo $this->webroot. 'img/notification.png';?>", sticky: false, time: ""});
                        return false
                    }
                });
        });
    });
    $(document).ready(function () {
        var clock = $('.clock').FlipClock(<?php echo $this->requestAction(Router::url(array('action' => 'time_work')));?>, {
            clockFace: 'HourlyCounter'
        });
    });
    function getCustomers(country_id) {
        $('.fixed_bottom').show();
        $.get("<?php echo $this->html->url(array('controller'=>'Customers','action'=>'getCustomers'),true)?>/" + country_id, function (data) {
            $("#customer").html(data);
            $('.fixed_bottom').hide();
        });
    }

    function getCustomer_Groups(country_id) {
        $('.fixed_bottom').show();
        $.get("<?php echo $this->html->url(array('controller'=>'Customergroups','action'=>'getCustomerGroups'),true)?>/" + country_id, function (data) {
            $("#customer_group").html(data);
            $('.fixed_bottom').hide();
        });
    }
</script>