<?php
echo $this->element('top_page', array(
    'page_title' => 'Quản lý tin nhắn',
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
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><?php echo __('Tin nhắn'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li class="active">
            <?php echo __('Chi tiết tin nhắn'); ?>
        </li>
    </ul>
</div>
<!-- BEGIN Main Content -->

<div class="row-fluid">
    <div>
        <ul class="mail-toolbar">
            <li><a href="javascript:history.back()"><i class="icon-reply"></i> Trở về</a></li>
            <li><?php
                echo $this->Form->postLink(
                    $this->Html->tag('i', '', array('class' => 'icon-trash')).' Xóa tin nhắn',
                    array('action' => 'delete', $notification['Notification']['id']),
                    array( 'escape' => false, 'confirm' => __('Are you sure?'))
                );
                ?></li>
            <li><a href="<?php echo Router::url(array('controller' => 'notifications', 'action' => 'add')); ?>"><i class="icon-plus"></i> Gửi tin nhắn</a></li>

        </ul>
    </div>
    <div class="">
        <div class="box">
            <div class="box-content">
                <div class="control-group">
                    <?php echo '<h5>'.$notification['Notification']['title'].'</h5>';?>

                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Gửi từ: ');
                        if ( $notification['Notification']['user_id'] !=0){
                        foreach ($users as $user):
                            if ($user['User']['id'] == $notification['Notification']['user_id']) {
                                echo $user['User']['name'];
                            }
                        endforeach;
                        }  else {
                            echo 'Hệ thống';
                        }
                        ?>
                    </label>
                </div>
                <div class="control-group">
                    <hr/>

                    <div class="mail-msg-content">
                        <?php echo $notification['Notification']['content'];?>
                    </div>
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
<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>

<style>
   ul,li{
       list-style: none;
   }
   .mail-toolbar > li {
       display: inline-block;
       padding-right: 25px;
   }
   .mail-toolbar {
       background-color: #fff;
       margin-bottom: 1px;
       padding: 10px;
       position: relative;
   }
   .mail-msg-content{
       padding: 10px 0;
       text-align: justify;
       display: block;
       color: #1B1B1B;
   }
</style>