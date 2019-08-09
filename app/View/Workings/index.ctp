<?php
echo $this->element('top_page', array(
    'page_title' => 'Danh sách sản phẩm được giao'
));
?>
<div class="modal fade" id="modal-2" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" id="content-popup">

    </div>
</div>
<div id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/"><?php echo __('Trang chủ'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li>
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><b><?php echo __('Danh sách sản phẩm được giao'); ?></b></a>
        </li>
    </ul>
</div>
<?php echo $this->Session->flash(); ?>
<!-- BEGIN Main Content -->
<div class="row-fluid">
<div class="span12">
<div class="box">
<div class="box-content">
<div class="btn-toolbar pull-right clearfix">
    <div class="btn-group">
        <div class="box-content">
            <?php
            if (count($product_deliver) == 0) {
                ?>
                <a id="product_please" style="margin-right: 5px;" href="#" class="btn btn-gray" data-number="1"><i
                        class="icon-check"></i> Xin hàng</a>
            <?php
            }
            ?>
            <?php
            if ((substr($_SERVER['HTTP_HOST'], 0, 8) == "192.168.") && ($_SERVER['HTTP_HOST'] != "117.0.32.133:2015") && count($product_deliver) == 0 && $CF['AUTOPRODUCT'] == 1 && ($auto_project > 0)) {
                    ?>
                    <a id="auto_product" style="margin-right: 5px;" href="#" class="btn btn-success" data-number="1"><i
                            class="icon-plus"></i> Xin chia hàng tự động</a>
                <?php
            }
            ?>
            <a style="margin-right: 5px;" id="feedback" data-toggle="modal" role="button"
               class="btn btn-primary show-tooltip"
               title="Feedback"
               href="#modal-feedback"><i class="icon-mail-reply"></i>
            </a>
            <a id="return" data-toggle="modal" role="button" class="btn btn-danger show-tooltip"
               title="Trả lại sản phẩm" href="#modal-return"
                ><i class="icon-refresh"></i>
            </a>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="row-fluid">
    <div class="span9">
        <div><i class="icon-stop" style="color: #00BFDD"></i><?php echo __('<b> : Chuyển hàng</b>'); ?>
        </div>
    </div>
</div>
<table class="table table-advance " id="table_product">
<thead>
<tr>
    <th style="width:18px"></th>
    <th class="text-center" style="width:18px"><?php echo __('STT'); ?></th>
    <th><?php echo __('Tên sản phẩm'); ?></th>
    <th class="text-center"><?php echo __('Mã khách hàng'); ?></th>
    <th class="text-center"><?php echo __('Loại xử lý'); ?></th>
    <th class="text-center"><?php echo __('Thời gian xử lý tiêu chuẩn'); ?></th>
    <th class="text-center" style="width:200px"><?php echo __('Thời gian làm'); ?></th>
    <th class="text-center" style="width:135px"><?php echo __('Hành động'); ?></th>
</tr>
</thead>
<tbody>
<tr style="background: green;color: #ffffff">
    <td colspan="3">Sản phẩm check</td>
    <td></td>
    <?php
    $pd = "";
    foreach ($check_product as $product) {
        $pd .= $product['Product']['id'].',';
    } ?>
    <?php
//    debug($check);die;
    $stat_check = false;
    foreach ($check_product as $cpd){
        if($cpd['CheckerProduct']['check'] == 0 && $cpd['CheckerProduct']['start_time'] == null){
            $stat_check = true;
        }
    }?>
    <td>
    <?php if($stat_check==true){?>
    <button class="btn btn-primary start_check" onclick="product_check()">Bắt đầu kiểm tra</button>
    <?php }?>
    </td>
    <td>
        <?php if($stat_check==false && !empty($cpd)){?>
            <span>Đang kiểm tra</span> &nbsp;
        <button class="btn btn-warning stop_check" onclick="product_done()">Hoàn tất kiểm tra</button>
        <?php }?>
    </td>
    <td></td>
    <td></td>

</tr>
<?php
//debug($check);die;
$stt =1;
foreach ($check as $key => $check_pj): ?>
    <tr>
        <th style="width:18px"></th>
        <th style="width:18px"></th>
        <th>
            <?php $name_project = $this->requestAction(Router::url(array('controller' => 'workings', 'action' => 'project_info', $key)));
            echo $name_project['Project']['name'] ;
            if ($name_project['Project']['Require'] != '') {
                echo '<a data-toggle="modal" class=" show-tooltip" role="button" href="#modal-' . $key . '" data-original-title="Lưu ý"><i class="icon-pencil"></i></a>';
            };
            if ($name_project['Project']['File']!=''){
                $_tmp = explode("/",$name_project['Project']['File'])
                ?>
                <span class="download-file" style="cursor: pointer;" data-name="<?php $_tmp[count($_tmp)-1] ?>" data="<?php echo $domain . (str_replace('@', '/',  $name_project['Project']['File'])) ?>"><i
                        class="icon-download"></i> </span>

            <?php }
            ?>
            <!--             <span style="color: blue; font-weight: normal">--><?php //echo str_replace("@", "\\", $dir.$name_project['Project']['UrlFolder']) ?><!--</span>-->
        </th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th class="text-center">
        </th>
        <div id="modal-<?php echo $key; ?>" class="modal hide fade" tabindex="-1"
             role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel3"><?php echo __('Lưu ý dự án:'); ?></h3>
            </div>
            <div class="modal-body">
                <?php echo $name_project['Project']['Require']; ?>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </tr>
<?php
//debug($check_product);die;

foreach ($check_product as $cpd):?>
    <?php
    if($cpd['Project']['ID'] == $key) {
        $done_pd = $this->requestAction(Router::url(array('controller' => 'products', 'action' => 'get_done_product', $cpd['Product']['id'])));
        ?>
        <tr>
            <td style="width:18px"></td>
            <td class="text-center" style="width:18px"><?php echo $stt?></td>
            <td><?php echo $cpd['Product']['name_file_product']?></td>
            <td class="text-center"><?php echo get(get($name_project,'Customer'), 'customer_code'); ?></td>
            <td></td>
            <td>
                <?php $performer = $this->requestAction(Router::url(array('controller' => 'projects', 'action' => 'user_info', $cpd['Product']['perform_user_id'])));?>
                <span
                    data="<?php echo str_replace('@', '/', $domain . $dir . $cpd['Product']['url'] .$cpd['Product']['sub_folder'] .'@' . $cpd['Product']['name_file_product'])?>"
                    class="btn btn-primary btn-sm download-file"><i class="fa fa-download"></i> Download file gốc</span>
                <span
                    data="<?php echo str_replace('@', '/', $domain . $dir . $cpd['Project']['UrlFolder'] . '@DONE@' . $cpd['Project']['Code'] . '-' . $performer['User']['username'] . $cpd['Product']['sub_folder'] . '@' . rawurlencode($done_pd['DoneProduct']['name_file_done']))?>"
                    class="btn btn-primary btn-sm download-file"><i
                        class="fa fa-download"></i> Download file đã làm</span>
                <a data-toggle="modal" class="btn btn-primary btn-sm "
                   onclick="changefile(<?php echo $cpd['Product']['id']?>)" role="button" href="#modal-2"><i
                        class="fa fa-mail-forward"></i> Thay thế</a>
                <a data-toggle="modal" id="bt-reject<?php echo $cpd['Product']['id'] ?>"
                   class="btn btn-danger reject-product reject_product" data-rate="7"
                   data-project-id="<?php echo $cpd['Project']['ID'];?>"
                   data-id-product="<?php echo $cpd['Product']['id'] ?>"
                   data-file="<?php echo $cpd['Product']['name_file_product'] ?>"
                   role="button" href="#reject">Reject</a>
            </td>
            <td>

            </td>
            <td></td>
        </tr>
        <?php
        $stt++;
    }
endforeach;
endforeach;?>
<tr>
    <td colspan="8"  style="background: blue;color: #ffffff">Công việc</td>
</tr>
<?php
$stt = 1;
foreach ($product_deliver as $key => $product):
    ?>
    <tr>
        <th style="width:18px"></th>
        <th style="width:18px"></th>
        <th>
            <?php $name_project = $this->requestAction(Router::url(array('controller' => 'workings', 'action' => 'project_info', $key)));
            echo get(get($name_project,'Project'),'name');
            if (get(get($name_project,'Project'),'Require') != '') {
                echo '<a data-toggle="modal" class=" show-tooltip" role="button" href="#modal-' . $key . '" data-original-title="Lưu ý"><i class="icon-pencil"></i></a>';
            };

            $agent = "";
            if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $agent = $_SERVER['HTTP_USER_AGENT'];
            };

            if (get(get($name_project,'Project'),'File')!=''){
                $_tmp = explode("/",$name_project['Project']['File'])

                ?>


                <?php


                if (strlen(strstr($agent, 'Firefox')) > 0) {
                    ?>

                    <a target="'_blank" href="<?php echo $domain . (str_replace('@', '/',  $name_project['Project']['File'])) ?>">
                        ⬇️
                    </a>

                    <?php
                }

                else {
                    ?>

                    <?php
                }

                ?>


                <span class="download-file" style="cursor: pointer;" data-name="<?php $_tmp[count($_tmp)-1] ?>" data="<?php echo $domain . (str_replace('@', '/',  $name_project['Project']['File'])) ?>" ><i
                        class="icon-download"></i> </span>

            <?php }
            ?>
<!--             <span style="color: blue; font-weight: normal">--><?php //echo str_replace("@", "\\", $dir.$name_project['Project']['UrlFolder']) ?><!--</span>-->
        </th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th class="text-center">
            <?php
            $dem = 0;
            $i = 0;
            $id_product = array();
            foreach ($product as $product_item) {
                $dem += $product_item['Product']['done_round'];
                $id_product[$i] = $product_item['Product']['id'];
                $i++;
            }
            $id_product = implode(',', $id_product);
            if ($dem == count($product)) {
                ?>
                <a class="btn btn-small btn-primary show-tooltip doneAll" id="doneAll-<?php echo $key; ?>"
                   data-product="<?php echo $id_product; ?>" title="Done all">
                    <i class="icon-check-sign"></i>
                    <?php echo __('Done all'); ?>
                </a>
            <?php
            }
            ?>
        </th>
        <div id="modal-<?php echo $key; ?>" class="modal hide fade" tabindex="-1"
             role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel3"><?php echo __('Lưu ý dự án:'); ?></h3>
            </div>
            <div class="modal-body">
                <?php echo $name_project['Project']['Require']; ?>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </tr>
<?php
//        debug($product);die;
    foreach ($product as $item) {
        ?>
        <tr class="table-flag-<?php if ($item['Product']['receive_user_id'] != null) {
            echo 'blue';
        } else {
            echo 'red';
        } ?>">
        <td><input value="<?php echo $item['Product']['id']; ?> " type="checkbox" name="ck[]"/></td>
        <td><?php echo $stt;
            ?></td>
        <td>
            <?php
            if ((substr($_SERVER['REMOTE_ADDR'], 0, 8) == "192.1688.") || ($_SERVER['REMOTE_ADDR'] == "127.0.0.11")) {
                ?>
                <a class="download-file" data="<?php echo $domain . (str_replace('@', '/', $dir . $item['Product']['url'] . $item['Product']['sub_folder'].'@' . rawurlencode($item['Product']['name_file_product']))) ?>" data-name="<?php echo $item['Product']['name_file_product'];?>"><i
                        class="icon-download"></i><?php echo ($item['Project']['duplicate'] == '1' ? trim($item['Product']['sub_folder'], '@') . '/' : '') . $item['Product']['name_file_product'];
                    echo $this->Form->input('image-' . $item['Product']['id'], array('type' => 'hidden', 'value' => str_replace('@', '/', $dir . $item['Product']['url'] . $item['Product']['sub_folder']. '@' . $item['Product']['name_file_product']), 'id' => 'image-' . $item['Product']['id'])); ?>
                </a>
                <?php if (isset($item['Product']['note_product'])) { ?>
                    <i style="position: absolute;" class="icon-pencil light-blue show-popover" data-content="<?php echo __($item['Product']['note_product']); ?>" data-trigger="hover"
                       data-original-title="Lưu ý người chuyển"></i>
                <?php } ?>
<!--                --><?php //if($item['Product']['note_file']) { ?>
<!--                    <a class="download-file" style="cursor: pointer;margin-left: 20px;"-->
<!--                       href="--><?php //echo $domain . (str_replace('@', '/',$dir . $item['Project']['UrlFolder'] . '@SUB@' .$item['Product']['note_file'])); ?><!--"><i-->
<!--                            class="icon-file"></i>-->
<!--                    </a>-->
                <?php
//                }
            } else {
                ?>
                <!-- <a><?php echo $item['Product']['name_file_product'];
                        echo $this->Form->input('image-' . $item['Product']['id'], array('type' => 'hidden', 'value' => str_replace('@', '/', $dir . $item['Product']['url'] . '@' . $item['Product']['name_file_product']), 'id' => 'image-' . $item['Product']['id'])); ?>
                    </a> -->

                <?php


                if (strlen(strstr($agent, 'Firefox')) > 0) {
                    ?>

                    <a target="'_blank" href="<?php echo $domain . (str_replace('@', '/', $dir . $item['Product']['url']  . $item['Product']['sub_folder']. '@' . rawurlencode($item['Product']['name_file_product']))) ?>">
                        ⬇️
                    </a>

                    <?php
                }

                else {
                    ?>

                    <?php
                }

                ?>


                <span class="download-file" style="cursor: pointer;" data="<?php echo $domain . (str_replace('@', '/', $dir . $item['Product']['url']  . $item['Product']['sub_folder']. '@' . rawurlencode($item['Product']['name_file_product']))) ?>"><i
                        class="icon-download"></i><?php echo ($item['Project']['duplicate'] == '1' ? trim($item['Product']['sub_folder'], '@') . '/' : '') . $item['Product']['name_file_product'];
                    echo $this->Form->input('image-' . $item['Product']['id'], array('type' => 'hidden', 'value' => str_replace('@', '/', $dir . $item['Product']['url'] . $item['Product']['sub_folder']. '@' . $item['Product']['name_file_product']), 'id' => 'image-' . $item['Product']['id'])); ?>
                </span>
            <?php
            }
            if($item['Product']['note_file']) { ?>
                <a class="download-file" style="cursor: pointer;margin-left: 20px;"
                   data="<?php echo $domain . (str_replace('@', '/',$dir . $item['Project']['UrlFolder'] . '@SUB@' .$item['Product']['note_file'])); ?>"><i
                        class="icon-file"></i>
                </a>
            <?php
            }
            ?>
        </td>
        <td class="text-center"><?php echo get(get($name_project,'Customer'),'customer_code'); ?></td>
        <td class="text-center"><?php echo get(get($item,'Processtype'),'name'); ?></td>
        <td class="text-center"><?php echo get(get($item,'Processtype'),'time_counting') . ' (phút)'; ?></td>
        <td id="time-<?php echo $item['Product']['id']; ?>" class="text-center time"
            data="<?php echo $item['Product']['id']; ?>">
            <?php
            $get_status = $this->requestAction(Router::url(array('action' => 'get_status'), true) . '/' . $item['Product']['id']);

            ?>
            <div style="display: none;" class="clock-<?php echo $stt; ?>" data-status="<?php echo $get_status; ?>"
                 id="clock-<?php echo $item['Product']['id']; ?>" data="<?php echo $item['Product']['id']; ?>">

            </div>
            <input type="hidden" id="get_id-<?php echo $stt; ?>"
                   value="<?php echo $this->requestAction(Router::url(array('controller' => 'workings', 'action' => 'time_work', $item['Product']['id']))) ?>">
        </td>
        <?php if ($item['feedback'] > 0) {
            ?>
            <td class="text-center"><?php echo __('Sản phẩm đang Feedback!'); ?></td>
        <?php
        } else {
            ?>
            <td class="text-center status">
            <?php
            //  $xxx= true;
            // if($edit_profile['User']['id']==38){
            //     $xxx = false;
            // }
            //Check toàn bộ sản phẩm của nhân viên xem có file nào đang start hay không
            //Nếu có
            if (count($check_status) > 0 ) {

                //Xem file hiện tại đã làm và đang start (status=1) hay không? check_status > 0
                if ($this->requestAction(Router::url(array('controller' => 'workings', 'action' => 'check_status', $item['Product']['id']))) > 0) {


                        ?>
                        <a id="pause-<?php echo $item['Product']['id']; ?>"
                           class="btn btn-small btn-pink show-tooltip pause" title="Pause"
                           href="#" data="<?php echo $item['Product']['id']; ?>"
                           data-action="<?php echo $stt; ?>"><i
                                class="icon-pause"></i>
                        </a>
                        <a style="display: none;" id="continue-<?php echo $item['Product']['id']; ?>"
                           class="btn btn-small btn-success show-tooltip continue" title="Continue"
                           href="#" data="<?php echo $item['Product']['id']; ?>"
                           data-action="<?php echo $stt; ?>"><i
                                class="icon-play"></i>
                        </a>
                        <a style="display: none;" id="done1-<?php echo $item['Product']['id']; ?>"
                           class="btn btn-small btn-primary show-tooltip done1" title="Done 1"
                           href="<?php echo Router::url(array('action' => 'done')) . '/' . $item['Product']['id'] . '/1'; ?>"
                           data="<?php echo $item['Product']['id']; ?>"><i
                                class="icon-check"></i>
                        </a>
                        <a style="display: none;" id="change-<?php echo $item['Product']['id']; ?>"
                           class="btn btn-small btn-pink show-tooltip change" title="Transfer"
                           href="<?php echo Router::url(array('action' => 'change')) . '/' . $item['Product']['id']; ?>"
                           data="<?php echo $item['Product']['id']; ?>"><i
                                class="icon-share-sign"></i>
                        </a>
                    <?php

                } else {
                    /** luckymancvp cho working bi loi */
                    /*if ($check_status[0]['Working']['user_id'] == '76') {
                        var_dump($this->requestAction(Router::url(array('controller' => 'workings', 'action' => 'check_status', $item['Product']['id']))));
                        echo ($item['Product']['id']) . '|';
                    }
                    echo(var_dump($check_status));die();*/
                    // echo 'Working 1...';
                }
            } else {
                if ($item['Product']['status'] == Product::STATUS_DANG_REJECT) { 
                    echo 'Returning';
                }
                else if ($get_status == false) {
                    ?>
                    <a id="start-<?php echo $item['Product']['id']; ?>"
                       class="btn btn-small btn-success show-tooltip start" title="Start"
                       href="#" data="<?php echo $item['Product']['id']; ?>"
                       data-action="<?php echo $stt; ?>"><i
                            class="icon-play"></i>
                    </a>
                    <a style="display: none;" id="pause-<?php echo $item['Product']['id']; ?>"
                       class="btn btn-small btn-pink show-tooltip pause" title="Pause"
                       href="#" data="<?php echo $item['Product']['id']; ?>"
                       data-action="<?php echo $stt; ?>"><i
                            class="icon-pause"></i>
                    </a>
                    <a style="display: none;" id="continue-<?php echo $item['Product']['id']; ?>"
                       class="btn btn-small btn-success show-tooltip continue" title="Continue"
                       href="#" data="<?php echo $item['Product']['id']; ?>"
                       data-action="<?php echo $stt; ?>"><i
                            class="icon-play"></i>
                    </a>
                    <a style="display: none;" id="done1-<?php echo $item['Product']['id']; ?>"
                       class="btn btn-small btn-primary show-tooltip done1" title="Done 1"
                       href="<?php echo Router::url(array('action' => 'done')) . '/' . $item['Product']['id'] . '/1'; ?>"
                       data="<?php echo $item['Product']['id']; ?>"><i
                            class="icon-check"></i>
                    </a>
                    <a style="display: none;" id="done2-<?php echo $item['Product']['id']; ?>"
                       class="btn btn-small btn-success show-tooltip done2" title="Done 2"
                       href="#" data="<?php echo $item['Product']['id'] . '/2'; ?>"
                       data-action="<?php echo $stt; ?>"><i
                            class="icon-check-sign"></i>
                    </a>
                    <a id="change-<?php echo $item['Product']['id']; ?>"
                       class="btn btn-small btn-pink show-tooltip change" title="Transfer"
                       href="<?php echo Router::url(array('action' => 'change')) . '/' . $item['Product']['id']; ?>"
                       data="<?php echo $item['Product']['id']; ?>" data-action="<?php echo $stt; ?>"><i
                            class="icon-share-sign"></i>
                    </a>
                    <span style="display: none;" id="text-<?php echo $item['Product']['id']; ?>"
                          data="<?php echo $item['Product']['id']; ?>"><?php echo __('Working...'); ?></span>
                <?php
                }
                if ($get_status == 1) {
                    ?>
                    <a id="pause-<?php echo $item['Product']['id']; ?>"
                       class="btn btn-small btn-pink show-tooltip pause" title="Pause"
                       href="#" data="<?php echo $item['Product']['id']; ?>"
                       data-action="<?php echo $stt; ?>"><i
                            class="icon-pause"></i>
                    </a>
                    <a style="display: none;" id="continue-<?php echo $item['Product']['id']; ?>"
                       class="btn btn-small btn-success show-tooltip continue" title="Continue"
                       href="#" data="<?php echo $item['Product']['id']; ?>"
                       data-action="<?php echo $stt; ?>"><i
                            class="icon-play"></i>
                    </a>
                    <a style="display: none;" id="done1-<?php echo $item['Product']['id']; ?>"
                       class="btn btn-small btn-primary show-tooltip done1" title="Done 1"
                       href="<?php echo Router::url(array('action' => 'done')) . '/' . $item['Product']['id'] . '/1'; ?>"
                       data="<?php echo $item['Product']['id']; ?>"><i
                            class="icon-check"></i>
                    </a>
                    <a style="display: none;" id="change-<?php echo $item['Product']['id']; ?>"
                       class="btn btn-small btn-pink show-tooltip change" title="Transfer"
                       href="<?php echo Router::url(array('action' => 'change')) . '/' . $item['Product']['id']; ?>"
                       data="<?php echo $item['Product']['id']; ?>"><i
                            class="icon-share-sign"></i>
                    </a>
                <?php
                }
                if ($get_status == 2) {
                    ?>
                    <a style="display: none;" id="pause-<?php echo $item['Product']['id']; ?>"
                       class="btn btn-small btn-pink show-tooltip pause" title="Pause"
                       href="#" data="<?php echo $item['Product']['id']; ?>"
                       data-action="<?php echo $stt; ?>"><i
                            class="icon-pause"></i>
                    </a>
                    <a id="continue-<?php echo $item['Product']['id']; ?>"
                       class="btn btn-small btn-success show-tooltip continue" title="Continue"
                       href="#" data="<?php echo $item['Product']['id']; ?>"
                       data-action="<?php echo $stt; ?>"><i
                            class="icon-play"></i>
                    </a>
                    <a id="done1-<?php echo $item['Product']['id']; ?>"
                       class="btn btn-small btn-primary show-tooltip done1" title="Done 1"
                       href="<?php echo Router::url(array('action' => 'done')) . '/' . $item['Product']['id'] . '/1'; ?>"
                       data="<?php echo $item['Product']['id']; ?>"><i
                            class="icon-check"></i>
                    </a>
                    </a> <a id="change-<?php echo $item['Product']['id']; ?>"
                            class="btn btn-small btn-pink show-tooltip change" title="Transfer"
                            href="<?php echo Router::url(array('action' => 'change')) . '/' . $item['Product']['id']; ?>"
                            data="<?php echo $item['Product']['id']; ?>"><i
                            class="icon-share-sign"></i>
                    </a>
                <?php
                }
                if ($get_status == 3) {
                    ?>
                    <a id="done2-<?php echo $item['Product']['id']; ?>"
                       class="btn btn-small btn-success show-tooltip done2" title="Done 2"
                       href="<?php echo Router::url(array('action' => 'done')) . '/' . $item['Product']['id'] . '/2 '; ?>"
                       data="<?php echo $item['Product']['id']; ?>"><i
                            class="icon-check-sign"></i>
                    </a>
                <?php
                }
            }
            ?>

            </td>
        <?php
        }
        $stt++; ?>
        </tr>
    <?php
    }
endforeach;
?>
</tbody>
</table>
</div>
</div>
</div>
</div>
<footer>
    <?php
    echo $this->element('footer');
    ?>
</footer>
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide"
     id="modal-feedback" style="display: none">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel3"><?php echo __('Sản phẩm feedback'); ?></h3>
    </div>
    <?php echo $this->Form->create('Working', array('action' => 'feedback', 'class' => 'form-horizontal validation-form',)); ?>
    <div class="modal-body">
        <?php
        $note = $this->requestAction(Router::url(array('controller' => 'workings', 'action' => 'list_note')));
        $process = $this->requestAction(Router::url(array('controller' => 'workings', 'action' => 'list_process')));
        ?>
        <div class="span8">
            <div class="control-group">
                <label><?php echo __('Chọn lý do'); ?>:</label>
                <?php
                echo $this->Form->input('note_id', array(
                    'type' => 'select',
                    'label' => false,
                    'class' => 'select2-choice select2-default',
                    'options' => $note,
                    'data-rule-required' => 'true'
                ));
                ?>
            </div>
            <div class="control-group">
                <label><?php echo __('Chọn loại xử lý'); ?>:</label>
                <?php
                echo $this->Form->input('process_type_id', array(
                    'type' => 'select',
                    'label' => false,
                    'class' => 'select2-choice select2-default',
                    'options' => $process,
                    'data-rule-required' => 'true'
                ));
                ?>
            </div>
            <div class="control-group">
                <label><?php echo __('Chọn định dạng'); ?>:</label>
                <?php
                echo $this->Form->input('product_extension_id', array(
                    'type' => 'select',
                    'label' => false,
                    'class' => 'select2-choice select2-default',
                    'options' => $productextension,
                    'data-rule-required' => 'true'
                ));
                ?>
            </div>
        </div>
        <?php echo $this->Form->input('product_id', array('type' => 'hidden', 'id' => 'product_id'));
        ?>
        <div>
            <label><?php echo __('Các sản phẩm: '); ?>:</label>
            <ul class="gallery" id="feedback_product">

            </ul>
        </div>
    </div>
    <div class="modal-footer">
        <div class="form-actions">
            <input type="submit" class="btn btn-primary" value="<?php echo __('Lưu') ?>">
            <button type="button" class="btn" data-dismiss="modal" aria-hidden="true"><?php echo __('Hủy') ?></button>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide"
     id="modal-return" style="display: none">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel3"><?php echo __('Sản phẩm trả lại'); ?></h3>
    </div>
    <div class="modal-body">
        <div class="span8">
            <div class="control-group">
                <label><?php echo __('Lý do'); ?>:</label>

                <?php
                echo $this->Form->textarea('note_id', array(
                    'label' => false,
                    'id' => 'product_note',
                    'class' => 'select2-choice select2-default',
                    'style' => 'width:400px;height:150px',
                    'options' => $note,
                    'data-rule-required' => 'true'
                ));
                ?>
            </div>
        </div>
        <?php echo $this->Form->input('product_id', array('type' => 'hidden', 'id' => 'product_return'));
        ?>
        <div>
            <label><?php echo __('Các sản phẩm trả lại: '); ?>:</label>
            <ul class="gallery" id="return_product">

            </ul>
        </div>
    </div>
    <div class="modal-footer">
        <div class="form-actions">
            <input id="save_return" type="button" class="btn btn-primary" value="<?php echo __('Lưu') ?>">
            <button type="button" class="btn" data-dismiss="modal" aria-hidden="true"><?php echo __('Hủy') ?></button>
        </div>
    </div>
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="modal-1"
     style="display: none;">

</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="reject"
     style="display: none;">
</div>


<!-- END Main Content -->
<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
<script type="text/javascript">
$(document).ready(function () {
    $('#product_please').click(function () {
        $.post("<?php echo Router::url(array('action' => 'please_product')); ?>")
            .done(function (data) {
                if (data) {
                    alert('Bạn đã yêu cầu thành công!');
//                        window.location.reload();
                }
            });
    });
    $('.doneAll').click(function () {

        var i = $(this).attr('data-product');
        var x = confirm("Bạn có chắc chắn muốn done toàn bộ sản phẩm của dự án này không?");
        if (x) {
            $('.fixed_bottom').show();
            $.post("<?php echo Router::url(array('action' => 'done_all')); ?>", {'product_id': i})
                .done(function (data) {
                    if (data) {
                        alert('Bạn đã thay trả toàn bộ sản phẩm của dự án');
                       window.location.reload();
                    } else {
                        $('.fixed_bottom').hide();
                        return false;
                    }
                });
        } else {
            return false;
        }
    });
    $('.change').click(function () {
        var x = confirm("Bạn có chắc chắn muốn chuyển cho người khác không?");
        if (x) {
            return true;
        } else {
            return false;
        }
    });
    $('.start').click(function () {
        var i = $(this).attr('data');
        var stt = $(this).attr('data-action');

        clock[stt].start();
        $.post("<?php echo Router::url(array('action' => 'start')); ?>", {'product_id': i})
            .done(function (data) {
                if (data == 'OK') {
                    $('.start').hide();
                    $('#pause-' + i).show();
                    $('.change').hide();
                    $('#clock-' + i).show();
                    $.gritter.add({
                        title: "Bắt đầu công việc!",
                        text: '<a href="#" style="color:#ccc"></a>',
                        image: "<?php echo $this->webroot. 'img/notification.png';?>",
                        sticky: false,
                        time: ""
                    });
                    return false
                } else {
                    alert('Có lỗi, không thể lưu start, Refresh để thử lại!')
                }
            });
    });
    $('.pause').click(function () {
        var i = $(this).attr('data');
        var stt = $(this).attr('data-action');

        clock[stt].stop();
        $.post("<?php echo Router::url(array('action' => 'pause')); ?>", {'product_id': i})
            .done(function (data) {
                if (data == 'NO_OK') {
                    alert('Có lỗi, không thể Pause, Refresh để thử lại!');
                } else {
                    $('#pause-' + i).hide();
                    $('#continue-' + i).show();
                    $('#done1-' + i).show();
                    $('#change-' + i).show();
                    $.gritter.add({
                        title: "Tạm dừng!",
                        text: '<a href="#" style="color:#ccc"></a>',
                        image: "<?php echo $this->webroot. 'img/notification.png';?>",
                        sticky: false,
                        time: ""
                    });
                    return false
                }
            });
    });
    $('.continue').click(function () {

        var i = $(this).attr('data');
        var stt = $(this).attr('data-action');
        $.post("<?php echo Router::url(array('action' => 'continue_work')); ?>", {'product_id': i})
            .done(function (data) {
                if (data == 'OK') {

                    $('.start').hide();
                    $('.change').hide();
                    $('#pause-' + i).show();
                    $('#done1-' + i).hide();
                    $('#change-' + i).hide();
                    $('#continue-' + i).hide();
                    clock[stt].start();
                    $.gritter.add({
                        title: "Tiếp tục công việc!",
                        text: '<a href="#" style="color:#ccc"></a>',
                        image: "<?php echo $this->webroot. 'img/notification.png';?>",
                        sticky: false,
                        time: ""
                    });
                    return false
                } else {
                    alert('Không thể tiếp tục start sản phẩm này, kiểm tra lại!')
                }
            });
    });
    $('#feedback').click(function () {
        $('.fixed_bottom').show();
        var values = new Array();
        $.each($("input[name='ck[]']:checked"), function () {
            values.push($(this).val());
        });
        if (values.length == 0) {
            alert('Bạn chưa chọn sản phẩm để feedback!');
            $('.fixed_bottom').hide();
            return false;
        } else {
            $('#feedback_product').html('');
            for (var i = 0; i < values.length; i++) {
                $('#feedback_product').html($('#feedback_product').html() + '<li><a href="<?php echo $domain.'/';?>' + $('#image-' + values[i]).val() + '" rel="prettyPhoto" title="Description of image"><div> <img class="image" style="border: 1px solid #CCCCCC" src="<?php //echo $domain.'/';?>' + $('#image-' + values[i]).val() + '" alt=""></div></a></li>')
            }
            $('#product_id').val(values);
            $('.fixed_bottom').hide();
        }
    });
    $('#auto_product').click(function () {
        var x = confirm("Bạn có chắc chắn muốn xin hàng tự động?");
        if (x) {
            $.post("<?php echo Router::url(array('controller' => 'Workings','action' => 'auto_product')); ?>", { 'items': 0 })
                .done(function (data) {
                    if (data == 'NULL') {
                        alert('Đã hết hàng chia tự động!');
                        window.location.reload();
                    } else {
                        alert('Bạn đã được chia!' + data + ' file.');
                        window.location.reload();
                    }
                });
            ;
        } else {
            return false;
        }
    });

    $('#return').click(function () {
        $('.fixed_bottom').show();
        var values = new Array();
        $.each($("input[name='ck[]']:checked"), function () {
            values.push($(this).val());
        });
        if (values.length == 0) {
            alert('Bạn chưa chọn sản phẩm để trả lại!');
            $('.fixed_bottom').hide();
            return false;
        } else {
            $('#return_product').html('');
            for (var i = 0; i < values.length; i++) {
                $('#return_product').html($('#return_product').html() + '<li><a href="<?php echo $domain.'/';?>' + $('#image-' + values[i]).val() + '" rel="prettyPhoto" title="Description of image"><div> <img class="image" style="border: 1px solid #CCCCCC" src="<?php echo $domain.'/';?>' + $('#image-' + values[i]).val() + '" alt=""></div></a></li>')
            }
            $('#product_return').val(values);
            $('.fixed_bottom').hide();
        }
    });
    $('#save_return').click(function () {
        $('.fixed_bottom').show();
        var id = $('#product_return').val();
        var note = $('#product_note').val();
//            alert(id+'/'+note);
        $.post("<?php echo Router::url(array('action' => 'return_product')); ?>", {'product_id': id, note: note})
            .done(function (data) {
                if (data == 'OK') {
                    $('.fixed_bottom').hide();
                    alert('Bạn đã trả sản phẩm!');
                    window.location.reload();
                } else {
                    alert('Không thể lưu!')
                }
            });
    });
    var clock = new Array(<?php echo $stt ?>);
    <?php
   for ( $j = 1; $j < $stt;  $j++) {?>
    //get Total time working: wtime
    var time_work = $('#get_id-<?php echo $j;?>').val();
    var status = $('.clock-<?php echo $j ?>').attr('data-status');
    clock[<?php echo $j ?>] = $('.clock-<?php echo $j ?>').FlipClock(time_work, {
        autoStart: false,
        clockFace: 'HourlyCounter'
    });
    //Dung jquery de show nhung clock nao co wtime > 0
    if (time_work > 0 && status == 2) {
        $(".clock-<?php echo $j;?>").show();
    }
    if (status == 1) {
        $(".clock-<?php echo $j;?>").show();
        clock[<?php echo $j ?>].start();
    }
    <?php }?>

    $('.download-file').click(function(){
        var a = document.createElement('a');
        a.href = $(this).attr('data');
        a.download = $(this).attr('data-name');
        a.click();
    });

})
;
</script>
<script>
    function changefile(product_id){
        $.get( "<?php echo $this->html->url(array('controller'=>'Projects','action'=>'changefile'))?>/"+product_id, function( data ) {
            $('#content-popup').html(data);
        });
    }
</script>
<script>
    function product_check() {
        location.href = "<?php echo $this->html->url(array('controller'=>'Workings','action'=>'product_check'),true);?>";
    }
</script>
<script>
    function product_done() {
        location.href = "<?php echo $this->html->url(array('controller'=>'Projects','action'=>'okcheck',5,$pd),true);?>";
    }
    $('.reject_product').click(function () {
        var id = $(this).attr('data-project-id');
        var id_product = $(this).attr('data-id-product');
        var rate = $(this).attr('data-rate');
        $.post("<?php echo Router::url(array('controller' => 'projects','action' => 'form_reject')); ?>", { 'id': id,rate:rate,id_product: id_product})
            .done(function (data) {
                if (data) {
                    $('#reject').html(data);
                }
            });
    });
</script>