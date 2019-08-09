<?php //debug($category);die;?><!-- -->
<?php
echo $this->element('top_page', array(
    'page_title' => 'Thông tin chi tiết người dùng'
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
            <?php echo __('Chi tiết người dùng');?>
        </li>
    </ul>
</div>
<!--<div class="alert alert-success">--><?php //echo $this->Session->flash(); ?><!--</div>-->
<div id="alert"></div>
<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-content">
                <table class="table table-advance" id="table1">
                    <tbody>
                        <tr class="table-flag">
                            <th><?php echo __('Id'); ?></th>
                            <td><?php echo h($user['User']['id']); ?></td>
                        </tr>
                        <tr class="table-flag">
                            <th><?php echo __('Email'); ?></th>
                            <td><?php echo h($user['User']['email']); ?></td>
                        </tr>
                        <tr class="table-flag">
                            <th><?php echo __('Group'); ?></th>
                            <td><?php echo h($user['Group']['name']); ?></td>
                        </tr>
                        <tr class="table-flag>
                            <th><?php echo __('Created'); ?></th>
                            <td><?php echo h($user['User']['created']); ?></td>
                        </tr>
                        <tr class="table-flag>
                            <th><?php echo __('Modified'); ?></th>
                            <td><?php echo h($user['User']['modified']); ?></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- END Main Content -->

<footer>
    <p>2013 © FLATY Admin Template.</p>
</footer>

<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
