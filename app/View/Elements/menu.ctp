<!--// class = "nav-collapse sidebar-collapsed"-->
<?php if($group_id_user == 4){?>
    <div id="sidebar" class="nav-collapse collapse">
        <!-- BEGIN Navlist -->
        <ul class="nav nav-list" id="menu">
            <!--        <ul class="nav nav-list nice-scroll" style="height: 600px; overflow: hidden; outline: none;" tabindex="5000" id="menu">-->
            <li>
                <a href="/">
                    <i class="icon-home"></i>
                    <span><?php echo __('Trang chủ'); ?></span>
                </a>
            </li>
            <li class="<?php if ($this->params['controller'] == 'vacations' && $this->params['action'] == 'add') {
                echo 'active';
            } ?>"><a
                    href="<?php echo Router::url(array('plugin' => false, 'controller' => 'vacations', 'action' => 'add')); ?>"><i class="icon-vk"></i><?php echo __('Xin nghỉ phép'); ?></a>
            </li>
            <li class="<?php if ($this->params['controller'] == 'workings' && $this->params['action'] == 'index') {
                echo 'active';
            } ?>"><a
                    href="<?php echo Router::url(array('plugin' => false, 'controller' => 'workings', 'action' => 'index')); ?>"><i class="icon-suitcase"></i><?php echo __('Công việc'); ?></a>
            </li>
        </ul>
        <!-- END Sidebar Collapse Button -->

    </div>
<?php }else{?>
    <div id="sidebar" class="nav-collapse collapse">
        <!-- BEGIN Navlist -->
        <ul class="nav nav-list" id="menu">
            <!--        <ul class="nav nav-list nice-scroll" style="height: 600px; overflow: hidden; outline: none;" tabindex="5000" id="menu">-->
            <li>
                <a href="/">
                    <i class="icon-home"></i>
                    <span><?php echo __('Trang chủ'); ?></span>
                </a>
            </li>
            <li class="<?php if ($this->params['controller'] == 'projects') {
                echo 'active';
            } ?>">
                <a href="<?php echo Router::url('/projects/index', array('plugin' => false, 'controller' => 'projects', 'action' => 'index')); ?>">
                    <i class="icon-picture"></i>
                    <span><?php echo __('Dự án'); ?></span>
                </a>
            </li>
            <li class="<?php if ($this->params['controller'] == 'timelogins') {
                echo 'active';
            } ?>">
                <a href="<?php echo Router::url(array('plugin' => false, 'controller' => 'timelogins', 'action' => 'view')); ?>">
                    <i class="icon-picture"></i>
                    <span><?php echo __('Chấm công'); ?></span>
                </a>
            </li>
            <li class="<?php if (( $this->params['controller'] == 'workinggroups' && $this->params['action'] == 'index' || $this->params['controller'] == 'largegroups' && $this->params['action'] == 'index' || $this->params['controller'] == 'workings' && $this->params['action'] == 'view_list_product') || $this->params['controller'] == 'notes' || $this->params['controller'] == 'configs' || $this->params['controller'] == 'vacationtypes' || $this->params['controller'] == 'vacations') {
                echo 'active';
            } ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="icon-desktop"></i>
                    <span><?php echo __('Hệ Thống'); ?></span>
                    <b class="arrow icon-angle-right"></b>
                </a>
                <!-- BEGIN Submenu -->
                <ul class="submenu">
                    <li class="<?php if ($this->params['controller'] == 'largegroups' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'controller' => 'largegroups', 'action' => 'index')); ?>"><?php echo __('Quản lý nhóm nghiệp vụ'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'workinggroups' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'controller' => 'workinggroups', 'action' => 'index')); ?>"><?php echo __('Quản lý nhóm làm việcs'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'workings' && $this->params['action'] == 'view_list_product') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'controller' => 'workings', 'action' => 'view_list_product')); ?>"><?php echo __('Quản lý danh sách feedback và trả lại'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'vacationtypes' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'controller' => 'vacationtypes', 'action' => 'index')); ?>"><?php echo __('Quản lý loại ngày nghỉ'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'vacations' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'controller' => 'vacations', 'action' => 'index')); ?>"><?php echo __('Quản lý ngày nghỉ'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'configs' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'controller' => 'configs', 'action' => 'index')); ?>"><?php echo __('Quản lý cấu hình'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'notes' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'controller' => 'notes', 'action' => 'index')); ?>"><?php echo __('Quản lý các loại feedback'); ?></a>
                    </li>
                </ul>
                <!-- END Submenu -->
            </li>
            <li class="<?php if ($this->params['controller'] == 'timepoints' || $this->params['controller'] == 'groupcoms' || $this->params['controller'] == 'processtypegroups' || $this->params['controller'] == 'coms' || $this->params['controller'] == 'productcategories' || $this->params['controller'] == 'departments' || $this->params['controller'] == 'producttypes' || $this->params['controller'] == 'countries' || $this->params['controller'] == 'pointtimes' || $this->params['controller'] == 'customergroups' || $this->params['controller'] == 'customers' || $this->params['controller'] == 'ratepoints' || $this->params['controller'] == 'processtypes' || $this->params['controller'] == 'productextensions') {
                echo 'active';
            } ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="icon-list"></i>
                    <span><?php echo __('Danh mục'); ?></span>
                    <b class="arrow icon-angle-right"></b>
                </a>
                <!-- BEGIN Submenu -->
                <ul class="submenu">

                    <li class="<?php if ($this->params['controller'] == 'coms' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'plugin' => false, 'controller' => 'coms', 'action' => 'index')); ?>"><?php echo __('Quản lý com'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'groupcoms' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'plugin' => false, 'controller' => 'groupcoms', 'action' => 'index')); ?>"><?php echo __('Quản lý nhóm com'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'countries' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'plugin' => false, 'controller' => 'countries', 'action' => 'index')); ?>"><?php echo __('Quản lý quốc gia'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'departments' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'plugin' => false, 'controller' => 'departments', 'action' => 'index')); ?>"><?php echo __('Quản lý phòng ban'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'customers' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'plugin' => false, 'controller' => 'customers', 'action' => 'index')); ?>"><?php echo __('Quản lý khách hàng'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'customergroups' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'plugin' => false, 'controller' => 'customergroups', 'action' => 'index')); ?>"><?php echo __('Quản lý nhóm khách hàng'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'ratepoints' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'controller' => 'ratepoints', 'action' => 'index')); ?>"><?php echo __('Quản lý Reject'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'processtypes' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'controller' => 'processtypes', 'action' => 'index')); ?>"><?php echo __('Quản lý loại xử lý'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'processtypegroups' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'controller' => 'processtypegroups', 'action' => 'index')); ?>"><?php echo __('Quản lý nhóm loại xử lý'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'productextensions' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'controller' => 'productextensions', 'action' => 'index')); ?>"><?php echo __('Quản lý định dạng sản phẩm'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'actions' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'controller' => 'actions', 'action' => 'index')); ?>"><?php echo __('Quản lý điểm số nghiệp vụ'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'timepoints' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'controller' => 'timepoints', 'action' => 'index')); ?>"><?php echo __('Quản lý thang điểm thời gian'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'productcategories' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'plugin' => false, 'controller' => 'productcategories', 'action' => 'index')); ?>"><?php echo __('Quản lý sản phẩm'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'producttypes' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url(array('plugin' => false, 'controller' => 'producttypes', 'action' => 'index')); ?>">
                            <?php echo __('Quản lý loại sản phẩm '); ?>
                        </a></li>
                </ul>
                <!-- END Submenu -->
            </li>
            <li class="<?php if ($this->params['controller'] == 'users' || $this->params['controller'] == 'groups' || $this->params['controller'] == 'user_permissions') {
                echo 'active';
            } ?>">
                <a href="#" class="dropdown-toggle">
                    <i class="icon-user"></i>
                    <span><?php echo __('Quản lý người'); ?></span>
                    <b class="arrow icon-angle-right"></b>
                </a>
                <!-- BEGIN Submenu -->
                <ul class="submenu">
                    <li class="<?php if ($this->params['controller'] == 'users' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url('/admin/users/index', array('controller' => 'users', 'action' => 'index')); ?>"><?php echo __('Quản lý người dùng'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'groups' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url('/admin/groups', array('controller' => 'groups', 'action' => 'index')); ?>"><?php echo __('Quản lý nhóm người dùng'); ?></a>
                    </li>
                    <li class="<?php if ($this->params['controller'] == 'user_permissions' && $this->params['action'] == 'index') {
                        echo 'active';
                    } ?>"><a
                            href="<?php echo Router::url('/admin/user_permissions', array('controller' => 'user_permissions', 'action' => 'index')); ?>"><?php echo __('Quản lý phân quyền'); ?></a>
                    </li>
                </ul>
                <!-- END Submenu -->
            </li>
        </ul>
        <div id="sidebar-collapse" class="visible-desktop">
            <!--         class = "icon-double-angle-right"-->
            <i class="icon-double-angle-left"></i>
        </div>
        <?php if ($dem_customer == 1) { ?>
            <div class="clock-work"></div>
        <?php } ?>
        <?php if ($dem_customer == 0) { ?>

        <?php } ?>
        <!-- END Sidebar Collapse Button -->

    </div>
    <script>
        $(document).ready(function () {
            $('.clock-work').FlipClock(<?php echo $this->requestAction(Router::url(array('plugin'=>'','controller'=>'pages','action' => 'time_work_customer')));?>, {
                clockFace: 'HourlyCounter'
            });
        });
    </script>
<?php }?>

