<div id="navbar" class="navbar">
    <div class="navbar-inner">
        <div class="container-fluid">
            <!-- BEGIN Brand -->
            <a href="<?php echo $this->webroot; ?>" class="brand">
                <small>
                    <i class="icon-desktop"></i>
                    WORKMAN
                </small>
            </a>
            <!-- END Brand -->

            <!-- BEGIN Responsive Sidebar Collapse -->
            <a href="#" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
                <i class="icon-reorder"></i>
            </a>
            <!-- END Responsive Sidebar Collapse -->

            <!-- BEGIN Navbar Buttons -->
            <ul class="nav flaty-nav pull-right">


                <!-- BEGIN Button User -->
                <li class="user-profile">
                    <a data-toggle="dropdown" href="#" class="user-menu dropdown-toggle">
                        <img class="nav-user-photo"
                             src="<?php echo $edit_profile['User']['avatar'] ? $domain . 'medias/avatar_employee/' . $edit_profile['User']['avatar'] : $this->webroot . 'img/no_images.jpg'; ?>"
                             alt="Penny's Photo"/>
                                <span class="hidden-phone" id="user_info">
                                    <?php echo $edit_profile['User']['name']; ?>
                                </span>
                        <i class="icon-caret-down"></i>
                    </a>
                    <!-- BEGIN User Dropdown -->
                    <ul class="dropdown-menu dropdown-navbar" id="user_menu">
                        <li class="nav-header">
                            <i class="icon-time"></i>
                            Logined: <?php echo $time_logout;?>
                        </li>

                        <li>
                            <a  href="<?php echo Router::url('/admin/users/profile/'.$edit_profile['User']['id'], array('controller' => 'users', 'action' => 'profile',$edit_profile['User']['id'])); ?>"> <i class="icon-user"></i>
                                Sửa thông tin
                            </a>
                        </li>
<!--                        <li class="divider visible-phone"></li>-->
<!---->
<!--                        <li class="visible-phone">-->
<!--                            <a href="#">-->
<!--                                <i class="icon-tasks"></i>-->
<!--                                Tasks-->
<!--                                <span class="badge badge-warning">4</span>-->
<!--                            </a>-->
<!--                        </li>-->
<!--                        <li class="visible-phone">-->
<!--                            <a href="#">-->
<!--                                <i class="icon-bell-alt"></i>-->
<!--                                Notifications-->
<!--                                <span class="badge badge-important">8</span>-->
<!--                            </a>-->
<!--                        </li>-->
<!--                        <li class="visible-phone">-->
<!--                            <a href="#">-->
<!--                                <i class="icon-envelope"></i>-->
<!--                                Messages-->
<!--                                <span class="badge badge-success">5</span>-->
<!--                            </a>-->
<!--                        </li>-->

                        <li class="divider"></li>

                        <li>
                            <a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'logout')); ?>">
                                <i class="icon-off"></i>
                                <?php echo __('Đăng xuất') ?>
                            </a>
                        </li>
                    </ul>
                    <!-- BEGIN User Dropdown -->
                </li>
                <!-- END Button User -->
            </ul>
            <!-- END Navbar Buttons -->
            <?php if($group_id_user == 4){?>
                <ul id="nav-horizontal" class="nav flaty-nav navbar-collapse collapse">
                    <li>
                        <a href="<?php echo Router::url(array('plugin' => false, 'controller' => 'workings', 'action' => 'index')); ?>">
                            <i class="icon-suitcase"></i>
                            <span><?php echo __('Công việc'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo Router::url(array('plugin' => false, 'controller' => 'notifications', 'action' => 'index')); ?>">
                            <i class="icon-envelope"></i>
                            <span class="badge badge-success"><?php echo $notRead; ?></span>
                            <span><?php echo __('Tin nhắn'); ?></span>
                        </a>
                    </li>

                </ul>

            <?php }else{?>
                <ul id="nav-horizontal" class="nav flaty-nav navbar-collapse collapse">
                    <li>
                        <a href="<?php echo Router::url(array('plugin' => false, 'controller' => 'workings', 'action' => 'index')); ?>">
                            <i class="icon-suitcase"></i>
                            <span><?php echo __('Công việc'); ?></span>
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo Router::url('/projects/index',array('plugin' => false, 'controller' => 'projects', 'action' => 'index')); ?>">
                            <i class="icon-picture"></i>
                            <span><?php echo __('Dự án'); ?></span>
                        </a>
                    </li>

                    <li>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <i class="icon-code-fork"></i>
                            <span><?php echo __('Hệ thống'); ?></span>
                            <b class="arrow icon-caret-down"></b>
                        </a>

                        <ul class="dropdown-menu dropdown-navbar">
                            <li><a
                                    href="<?php echo Router::url('/admin/users/index', array('controller' => 'users', 'action' => 'index')); ?>"><?php echo __('Quản lý người dùng'); ?></a>
                            </li>
                            <li><a
                                    href="<?php echo Router::url(array('plugin' => false, 'controller' => 'productcategories', 'action' => 'index')); ?>"><?php echo __('Quản lý danh mục'); ?></a>
                            </li>
                            <li><a
                                    href="<?php echo Router::url(array('plugin' => false, 'controller' => 'vacations', 'action' => 'index')); ?>"><?php echo __('Quản lý ngày nghỉ'); ?></a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="<?php echo Router::url(array('plugin' => false, 'controller' => 'reports', 'action' => 'index')); ?>">
                            <i class="icon-print"></i>
                            <span><?php echo __('Báo cáo'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo Router::url(array('plugin' => false, 'controller' => 'notifications', 'action' => 'index')); ?>">
                            <i class="icon-envelope"></i>
                            <span class="badge badge-success"><?php echo $notRead; ?></span>
                            <span><?php echo __('Tin nhắn'); ?></span>
                        </a>
                    </li>

                </ul>
            <?php }?>
        </div>
        <!--/.container-fluid-->
    </div>
    <!--/.navbar-inner-->
</div>
