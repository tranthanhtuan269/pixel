<?php
echo $this->element('top_page', array(
    'page_title' => 'Sửa thông tin cá nhân'
));
?>
<div id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/"><?php echo __('Trang chủ'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li class="active">
            <?php echo __('Sửa thông tin cá nhân'); ?>
        </li>
    </ul>
</div>
<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i><?php echo __('Sửa thông tin'); ?></h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php echo $this->Form->create('User', array('action' => 'profile', 'class' => 'form-horizontal validation-form', 'type' => 'file')); ?>
                <div class="row-fluid">
                    <div class="span6 ">
                        <div class="col-md-4">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><?php echo __('Thông tin cá nhân:'); ?></h4>
                                </div>
                                <div class="panel-body">
                                    <div class="control-group">
                                        <label class="control-label" for="name"><?php echo __('Họ tên'); ?>: </label>

                                        <div class="controls">
                                            <div class="span12">
                                                <?php echo $this->Form->input('name', array('value' => $user_info['User']['name'], 'class' => 'input-xlarge', 'label' => false, 'data-rule-required' => 'true', 'data-rule-minlength' => '3')) ?>
                                                <?php echo $this->Form->input('id', array('value' => $user_info['User']['id'], 'type' => 'hidden', 'label' => false)) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="name"><?php echo __('Số CMT'); ?>:</label>

                                        <div class="controls">
                                            <div class="span12">
                                                <?php echo $this->Form->input('id_number', array('value' => $user_info['User']['id_number'], 'type' => 'text', 'class' => 'input-xlarge', 'label' => false)) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="name"><?php echo __('Email'); ?>: </label>

                                        <div class="controls">
                                            <div class="span12">
                                                <?php echo $this->Form->input('email', array('value' => $user_info['User']['email'], 'class' => 'input-xlarge', 'label' => false, 'data-rule-required' => 'false', 'data-rule-email' => 'true')) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="name"><?php echo __('Số điện thoại'); ?>
                                            :</label>

                                        <div class="controls">
                                            <div class="span12">
                                                <?php echo $this->Form->input('phone', array('value' => $user_info['User']['phone'], 'type' => 'text', 'class' => 'input-xlarge', 'label' => false)) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="name"><?php echo __('Địa chỉ'); ?>:</label>

                                        <div class="controls">
                                            <div class="span12">
                                                <?php echo $this->Form->input('address', array('value' => $user_info['User']['address'], 'type' => 'text', 'class' => 'input-xlarge', 'label' => false)) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label"
                                               for="name"><?php echo __('Tài khoản skype:'); ?></label>

                                        <div class="controls">
                                            <div class="span12">
                                                <?php echo $this->Form->input('skype', array('value' => $user_info['User']['skype'], 'class' => 'input-xlarge', 'label' => false)) ?>
                                            </div>
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
                                            <label class="control"
                                                   for="name"><?php echo __('Tên đăng nhập'); ?> </label>

                                            <div class="username_user">
                                                <?php echo $user_info['User']['username']; ?>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label for="password" class="control"><?php echo __('Nhập mật khẩu'); ?>
                                                : </label>
                                            <?php echo $this->Form->input('password', array('type' => 'password', 'placeholder' => '', 'class' => 'input-medium', 'label' => false, 'data-rule-required' => 'false', 'data-rule-minlength' => '6')) ?>
                                        </div>
                                        <div class="control-group">
                                            <label for="password2"
                                                   class="control"><?php echo __('Nhập lại mật khẩu'); ?>:</label>
                                            <?php echo $this->Form->input('password2', array('type' => 'password', 'placeholder' => '', 'class' => 'input-medium password', 'label' => false, 'data-rule-minlength' => '6', 'data-rule-equalto' => '#UserPassword')) ?>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control"><?php echo __('Ảnh đại diện:'); ?></label>
                                            <div data-provides="fileupload" class="fileupload fileupload-new">
                                                <div style="width: 200px; height: 150px;" class="fileupload-new thumbnail">
                                                    <img style="width: 200px; height: 150px; alt=""
                                                    src="<?php if ($user_info['User']['avatar'] != '') {
                                                        echo $domain.'medias/avatar_employee/' . $user_info['User']['avatar'];
                                                    } else {
                                                        echo 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image';
                                                    } ?>">
                                                </div>
                                                <div style="max-width: 200px; max-height: 150px; line-height: 20px;"
                                                     class="fileupload-preview fileupload-exists thumbnail">
                                                </div>
                                                <div>
                                               <span class="btn btn-file"><span
                                                       class="fileupload-new"><?php echo __('Chọn ảnh'); ?></span>
                                               <span class="fileupload-exists"><?php echo __('Sửa'); ?></span>
                                               <input type="file" class="default"
                                                      value="<?php echo $user_info['User']['avatar']; ?>"
                                                      name="data[User][avatar]"></span>
                                                    <a data-dismiss="fileupload" class="btn fileupload-exists"
                                                       href="#"><?php echo __('Hủy'); ?></a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                </div>
                            </div>

                            <hr>
                            <!-- END Right Side -->
                        </div>
                    </div>
                    <div style="clear: both"></div>
                    <div class="form-actions text-center">
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

</script>