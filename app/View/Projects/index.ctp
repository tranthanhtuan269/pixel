<?php
//echo $_SERVER['HTTP_HOST'];die;
echo $this->element('top_page', array(
    'page_title' => 'Quản lý đơn hàng',
));
$status_color = array();
$status_color[1] = '#0000FF';
$status_color[2] = '#CD3333';
$status_color[3] = '#FF1493';
$status_color[4] = '#FF0099';
$status_color[5] = '#FF0000';
$status_color[6] = '#FF6633';
$status_color[7] = '#FF3333';
$status_color[8] = '#FF0066';
$status_color[9] = '#FF6600';
$status_color[10] = '#6699FF';
?>
<?php
//$file = explode("/", $this->requestAction(array('controller' => 'Projects', 'action' => 'all_file_project')));
//$dissevered = $file[0];  $un_dissevered = $file[1]; $processed = $file[2];$unprocessed = $file[3];$total = $file[4];
//pr($file);die;
?>
<?php echo $this->Session->flash(); ?>
<!-- BEGIN Main Content -->
<div class="row-fluid">
<div class="box">
<div class="span12">
    <div class="box  table-bordered">
        <div class="box-title">
            <h3><i class="icon-search"></i> Tìm kiếm</h3>

            <div class="box-tool">
                <a href="#" data-modal="setting-modal-1" data-action="config"><i class="icon-gear"></i></a>
                <a href="#" data-action="collapse"><i
                        class="icon-chevron-<?php if ($status_id > 0 || $name != '' || $to_date != '' || $from_date != '') {
                            echo 'up';
                        } else {
                            echo 'down';
                        } ?>"></i></a>
            </div>
        </div>
        <div class="box-content" <?php if(!isset($_GET['search'])){ ?> style="display: none;" <?php } ?>>
            <?php echo $this->Form->create('Project', array('type' => 'get')) ?>
            <div class="row-fluid">
                <div class="span4">
                    <div class="control-group">
                        <label class="control-label">
                            <?php echo __('Trạng thái'); ?>:</label>

                        <div class="controls">
                            <?php echo $this->Form->input('Status_id', array('class' => 'span12', 'value' => $status_id, 'options' => $statuses, 'empty' => '--Chọn trạng thái--', 'onchange' => 'getCustomers(this.value)', 'div' => false, 'label' => false)); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">
                            <?php echo __('Chọn nhóm com'); ?>:</label>

                        <div class="controls">
                            <?php echo $this->Form->input('GroupCom', array('class' => 'span12', 'options' => $GroupCom, 'empty' => '--Chọn nhóm Comp--', 'onchange' => 'getCom(this.value)', 'div' => false, 'label' => false)); ?>
                        </div>
                        <label class="control-label">
                            <?php echo __('Tìm theo Comp'); ?>:</label>

                        <div class="controls" id="com">
                            <?php echo $this->Form->input('Com_id', array('class' => 'span12', 'empty' => '--Chọn Comp--', 'div' => false, 'label' => false)); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">
                            <?php echo __('Tên đơn hàng'); ?>:</label>

                        <div class="controls">
                            <?php echo $this->Form->input('Name', array('class' => 'span12', 'value' => $name, 'type' => 'text', 'div' => false, 'label' => false, 'style' => 'margin-bottom:0', 'placeholder' => 'Tên đơn hàng')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">
                            <?php echo __('Ngày Khởi tạo'); ?>:</label>

                        <div class="controls">
                            <?php echo $this->Form->input('FromDate', array('class' => 'span6 datepicker', 'value' => $from_date, 'type' => 'text', 'div' => false, 'label' => false, 'placeholder' => 'Từ ngày')); ?>
                            <?php echo $this->Form->input('ToDate', array('class' => 'span6 datepicker', 'value' => $to_date, 'type' => 'text', 'div' => false, 'label' => false, 'placeholder' => 'Đến ngày')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">
                            <?php echo __('Ngày hoàn thành'); ?>:</label>

                        <div class="controls">
                            <?php echo $this->Form->input('FromCompDate', array('class' => 'span6 datepicker', 'value' => $from_comp_date, 'type' => 'text', 'div' => false, 'label' => false, 'placeholder' => 'Từ ngày')); ?>
                            <?php echo $this->Form->input('ToCompDate', array('class' => 'span6 datepicker', 'value' => $to_comp_date, 'type' => 'text', 'div' => false, 'label' => false, 'placeholder' => 'Đến ngày')); ?>
                        </div>
                    </div>
                </div>
                <div class="span4">
                    <div class="control-group">
                        <label class="control-label">
                            <?php echo __('Quốc gia'); ?>:</label>

                        <div class="controls">
                            <?php echo $this->Form->input('Country_id', array('class' => 'span12', 'value' => $country_id, 'options' => $countries, 'onchange' => 'getCustomers(this.value)', 'empty' => '--Chọn quốc gia--', 'div' => false, 'label' => false)); ?>
                        </div>
                    </div>
                    <div class="control-group" id="customer">
                        <label class="control-label">
                            <?php echo __('Khách hàng'); ?>:</label>

                        <div class="controls">
                            <?php echo $this->Form->input('Customer_id', array('class' => 'span12', 'options' => array('' => '--Chọn khách hàng--'), 'div' => false, 'label' => false)); ?>
                        </div>
                    </div>
                    <div class="control-group" id="customer_group">
                        <label class="control-label">
                            <?php echo __('Nhóm khách hàng'); ?>:</label>

                        <div class="controls">
                            <?php echo $this->Form->input('CustomerGroup_id', array('class' => 'span12', 'options' => array('' => '--Chọn nhóm khách hàng--'), 'div' => false, 'label' => false)); ?>
                        </div>
                    </div>
                </div>
                <div class="span4">
                    <div id="container" style="min-width: 300px; height: 300px; margin: 0 auto"></div>
                </div>
            </div>
            <div class="form-actions clearfix text-center">
                <input type="submit" class="btn btn-primary " name="search" value="<?php echo __('Tìm kiếm') ?>">
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<div style="clear: both"></div>
<div class="">
    <div class="box  table-bordered">
        <div class="box-title">
            <h3><i class="icon-puzzle-piece"></i> Thống kê</h3>

            <div class="box-tool">
                <a href="#" data-modal="setting-modal-1" data-action="config"><i class="icon-gear"></i></a>
                <a href="#" data-action="collapse"><i class="icon-chevron-down"></i></a>
            </div>
        </div>
        <div class="box-content" id="statistic" style="display: none;">

        </div>
    </div>
</div>
<br>
<div class="row-fluid">
    <div class="span4">
        <div id="table1_length" class="dataTables_length"><label>
                <select name="DataTables_Table_0_length" id="pagination" aria-controls="table1" size="1"
                        onchange="Pagination()">
                    <option value="10" <?php if ($row == 10){ ?>selected="selected" <?php } ?>> 10</option>
                    <option value="25" <?php if ($row == 25){ ?>selected="selected" <?php } ?>> 25</option>
                    <option value="50" <?php if ($row == 50){ ?>selected="selected" <?php } ?>> 50</option>
                    <option value="100" <?php if ($row == 100){ ?>selected="selected" <?php } ?>> 100</option>
                </select>
                <?php echo __('Dòng/trang'); ?></label>
        </div>
    </div>
    <div class="span8" style="text-align: right;">
        <a style="margin-right: 5px" class="btn btn-small btn-primary show-tooltip" title="Thêm mới"
           href="<?php echo Router::url(array('controller' => 'Projects', 'action' => 'add')); ?>">
            <button class="btn btn-primary">
                <i class="icon-plus"></i><?php echo __(' Thêm mới'); ?>
            </button>
        </a>
        <a style="margin-right: 5px" class="btn btn-small btn-primary show-tooltip" title="Cập nhật đơn hàng tự động"
           href="<?php echo Router::url(array('controller' => 'projects', 'action' => 'addByUrl')); ?>">
            <button class="btn btn-primary">
                <?php echo __(' Cập nhật đơn hàng tự động'); ?>
            </button>
        </a>
        <?php if (array_key_exists('AUTOPRODUCT', $CF) && ($CF["AUTOPRODUCT"] == 1)) { ?>
            <a id="auto_project" style="margin-right: 5px" class="btn btn-small btn-gray show-tooltip"
               title="Dự án chia tự động"
                >
                <button class="btn btn-gray">
                    <i class="icon-check"></i><?php echo __(' Dự án chia tự động'); ?>
                </button>
            </a>
        <?php } ?>
        <!--        <a id="del" style="margin-right: 5px" class="btn btn-small btn-danger show-tooltip"-->
        <!--           title="Xóa"-->
        <!--           href="#">-->
        <!--            <button class="btn btn-danger">-->
        <!--                <i class="icon-trash" con></i>--><?php //echo __(' Xóa'); ?>
        <!--            </button>-->
        <!--        </a>-->
        <a id="refresh" style="" class="btn btn-small btn-success show-tooltip" title="Refresh"
           href="#">
            <button class="btn btn-success">
                <i class="icon-repeat"></i> <?php echo __(' Refresh'); ?>
            </button>
        </a>
    </div>
</div>
<br>
<div class="row-fluid">
    <div class="span6">
<!--        <div class="dataTables_info"-->
<!--             id="table1_info">--><?php //echo $this->Paginator->counter('Tổng số bản ghi {:current}/{:count}'); ?><!--</div>-->
    </div>
    <div class="span6">
        <div class="dataTables_paginate paging_bootstrap pagination">
            <ul>
                <?php
                echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
                echo $this->Paginator->numbers(array('separator' => '', 'currentTag' => 'a', 'currentClass' => 'active', 'tag' => 'li', 'first' => 1));
                echo $this->Paginator->next(__('next'), array('tag' => 'li', 'currentClass' => 'disabled'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
                ?>
            </ul>
        </div>
    </div>
</div>
<table class="table table-advance" id="table1">
    <thead>
    <tr>
        <th class="text-center" style="width:18px"><input type="checkbox"/></th>
        <th class="text-center"><?php echo __('Tên đơn hàng'); ?></th>
        <th class="text-center">
            <?php echo __('Mã khách hàng'); ?>
        </th>
        <th class="text-center"><?php echo __('Số lượng'); ?></th>
        <th class="text-center"><?php if (isset($searchStatus) && $searchStatus == 6) {
                echo __('Hoàn thành');
            } else {
                echo __('Trả hàng');
            } ?></th>
        <th class="text-center"><?php echo __('Yêu cầu'); ?></th>
        <th class="text-center"><?php echo __('Trạng thái'); ?></th>
        <th style="width:100px"><?php echo __('Hành động'); ?></th>
    </tr>
    </thead>
    <tbody>

    <?php
    $ut = 0;
    if(isset($prio_projects)){
    foreach ($prio_projects as $daut){
    $ut++;
        ?>

        <?php if($ut==1){ ?>
        <tr>
            <td colspan="8" style="background-color: firebrick; color: #ffffff"> Các đơn hàng ưu tiên</td>
        </tr>
            <?php } ?>
        <tr class="table-flag " >
            <td class="text-center"><input value="<?php echo $daut['Project']['ID']; ?>" type="checkbox" name="ck[]"/>
            </td>
            <td style="color: firebrick; font-weight: bold">
                <?php echo $this->Html->link($daut['Project']['Name'], array('action' => 'detail', $daut['Project']['ID']), array("style"=>"color: firebrick" )); ?>
                &nbsp;
                <?php if (trim($daut['Project']['Require'])) { ?>
                    <i style="position: absolute;" class="icon-pencil light-blue show-popover" data-html="true" data-content="<?php echo __($daut['Project']['Require']); ?>" data-trigger="hover"
                       data-original-title="Lưu ý đơn hàng"></i>
                <?php } ?>
                <?php if($daut['Project']['File'] != null){?>
                    <a class="download-file" style="cursor: pointer;margin-left: 20px;" data="<?php echo $domain . (str_replace('@', '/', $daut['Project']['File'])); ?>"><i
                            class="icon-file" downloaded></i>
                    </a>

                <?php }?>
            </td>
            <td>
                <?php echo $daut['Customer']['customer_code']; ?>
            </td>
            <td class="text-center">
                <?php //echo $this->requestAction(array('controller' => 'Projects', 'action' => 'getQuantity', $daut['Project']['ID'])); ?>
            </td>
            <td class="text-center">
                <p id="comp-time-<?php echo 'a' . $daut['Project']['ID'] ?>" class="show-input"
                   data="<?php echo 'a' . $daut['Project']['ID'] ?>">
                    <?php if ($daut['Project']['Status_id'] == 6) {
                        echo isset($daut['Project']['CompTime']) ? date('d/m/Y H:i:s', strtotime($daut['Project']['CompTime'])) : '';
                    } else {
                        echo isset($daut['Project']['returnTime']) ? date('d/m/Y H:i:s', strtotime($daut['Project']['returnTime'])) : '';
                    } ?>
                </p>
                <?php echo $this->Form->input('returnTime', array('div' => false, 'label' => false, 'style' => 'margin:0', 'value' => isset($daut['Project']['returnTime']) ? date('d/m/Y H:i:s', strtotime($daut['Project']['returnTime'])) : '', 'id' => 'a' . $daut['Project']['ID'], 'class' => 'input-medium order datetimepicker')) ?>
                <a class="submit-order <?php echo $daut['Project']['ID'] ?>" id="in<?php echo $daut['Project']['ID'] ?>"
                   name="10" data="<?php echo 'a' . $daut['Project']['ID'] ?>">Lưu | </a>
                <a id="cancel-<?php echo 'a' . $daut['Project']['ID'] ?>" class="submit-cancel"
                   data="<?php echo 'a' . $daut['Project']['ID'] ?>">Hủy</a>
                <?php echo $this->html->image('loading.gif', array('class' => 'loading', 'id' => 'l' . $daut['Project']['returnTime'])) ?>
            </td>
            <td><?php echo $daut['Project']['RequireDevide'] ?></td>
            <td class="text-center"><span
                    style="color: <?php echo $status_color[$daut['Status']['ID']]; ?>"><?php echo $daut['Status']['Name'] ?></span>
<!--                --><?php //if($daut['Project']['Status_id']==6){
//                    echo('<br><span>'.$daut['Project']['CompTime'].'</span>');
//                }?>
            </td>
            <td>

                <div class="btn-group">
                    <?php if(count($daut['Product'])< $daut['Project']['Quantity']) {?>
                        <a class="btn btn-small show-tooltip" title="Kích hoạt"
                           onclick="status(<?php echo $daut['Project']['ID'] ?>)"><i class="icon-exchange"></i>
                        </a>

                    <?php
                    }
                    if ($daut['Project']['Status_id'] >= 3 && $daut['Project']['Status_id'] < 6) {
                        ?>
                        <a class="btn btn-small show-tooltip" title="Gom hàng - giao hàng"
                           href="<?php echo Router::url(array('controller' => 'Projects', 'action' => 'bring')); ?>/<?php echo $daut['Project']['ID']; ?>">
                            <i class="icon-ok"></i>
                        </a>
                    <?php } ?>
                    <a class="btn btn-small btn-primary show-tooltip" title="Sửa"
                       href="<?php echo Router::url(array('controller' => 'Projects', 'action' => 'edit')); ?>/<?php echo $daut['Project']['ID']; ?>">
                        <i class="icon-edit"></i>
                    </a>
                    <a data-toggle="modal" data-rate="4" data-project-id="<?php echo $daut['Project']['ID'] ?>"
                       data-projectName="<?php echo $daut['Project']['Name'] ?>" role="button" href="#reject"
                       class="btn btn-small btn-success show-tooltip reject_project" title="Reject"
                       href="#reject">
                        <i class="icon-reply"></i>
                    </a>
                    <?php
                    $deliver = 0;
                    foreach($daut['Product'] as $deliver_check){
                        if ($deliver_check['deliver_user_id'] == 0){
                        }else{
                            $deliver = 1;
                        }
                    }
                    if ($daut['Project']['Status_id'] == 1|| $deliver==0) {
                        echo $this->Form->postLink(
                            $this->Html->tag('i', '', array('class' => 'icon-trash')),
                            array('action' => 'delete', $daut['Project']['ID']),
                            array('class' => 'btn btn-small btn-danger show-tooltip', 'title' => 'Xóa', 'escape' => false, 'confirm' => __('Are you sure?'))
                        );
                    }?>
                </div>
            </td>
        </tr>
    <?php }} ?>
    <tr>
        <td colspan="8" style="background-color: blue; color: #ffffff"> Các đơn hàng thông thường</td>
    </tr>
    <?php
    foreach ($projects as $item) {
        $flag = '';
        if (strtotime($item['Project']['CompTime']) - time() < 0) {
            $flag = "table-flag-black";
        } elseif (strtotime($item['Project']['CompTime']) - time() < 86400) {
            $flag = "table-flag-red";
        } elseif ($item['Project']['Quantity'] == 1) {
            $flag = "table-flag-orange";
        }
        ?>
        <tr class="table-flag <?php echo $flag ?>">
            <td class="text-center"><input value="<?php echo $item['Project']['ID']; ?>" type="checkbox" name="ck[]"/>
            </td>
            <td>
                <?php echo $this->Html->link($item['Project']['Name'], array('action' => 'detail', $item['Project']['ID'])); ?>
                &nbsp;
                <?php if (trim($item['Project']['Require'])) { ?>
                    <i style="position: absolute;" class="icon-pencil light-blue show-popover" data-html="true" data-content="<?php echo __($item['Project']['Require']); ?>" data-trigger="hover"
                       data-original-title="Lưu ý đơn hàng"></i>
                <?php } ?>
                <?php if($item['Project']['File'] != null){?>
<!--                    <a class="download-file" style="cursor: pointer;margin-left: 20px;"-->
<!--                       data="--><?php //echo $domain . (str_replace('@', '/', $item['Project']['File'])); ?><!--"><i-->
<!--                            class="icon-file"></i>-->
<!--                    </a>-->
                    <a class="download-file" style="cursor: pointer;margin-left: 20px;" data-name="<?php echo $domain . (str_replace('@', '/', $item['Project']['File'])); ?>" data="<?php echo $domain . (str_replace('@', '/', $item['Project']['File'])); ?>"><i
                            class="icon-file" ></i>
                    </a>
                <?php }?>
            </td>
            <td>
                <?php echo $item['Customer']['customer_code']; ?>
            </td>
            <td class="text-center">
                <?php echo $this->requestAction(array('controller' => 'Projects', 'action' => 'getQuantity', $item['Project']['ID'])); ?>
            </td>
            <td class="text-center">
                <p id="comp-time-<?php echo 'a' . $item['Project']['ID'] ?>" class="show-input"
                   data="<?php echo 'a' . $item['Project']['ID'] ?>">
                    <?php if ($item['Project']['Status_id'] == 6) {
                        echo isset($item['Project']['CompTime']) ? date('d/m/Y H:i:s', strtotime($item['Project']['CompTime'])) : '';
                    } else {
                        echo isset($item['Project']['returnTime']) ? date('d/m/Y H:i:s', strtotime($item['Project']['returnTime'])) : '';
                    } ?>
                </p>
                <?php echo $this->Form->input('returnTime', array('div' => false, 'label' => false, 'style' => 'margin:0', 'value' => isset($item['Project']['returnTime']) ? date('d/m/Y H:i:s', strtotime($item['Project']['returnTime'])) : '', 'id' => 'a' . $item['Project']['ID'], 'class' => 'input-medium order datetimepicker')) ?>
                <a class="submit-order <?php echo $item['Project']['ID'] ?>" id="in<?php echo $item['Project']['ID'] ?>"
                   name="10" data="<?php echo 'a' . $item['Project']['ID'] ?>">Lưu | </a>
                <a id="cancel-<?php echo 'a' . $item['Project']['ID'] ?>" class="submit-cancel"
                   data="<?php echo 'a' . $item['Project']['ID'] ?>">Hủy</a>
                <?php echo $this->html->image('loading.gif', array('class' => 'loading', 'id' => 'l' . $item['Project']['returnTime'])) ?>
            </td>
            <td><?php echo $item['Project']['RequireDevide'] ?></td>
            <td class="text-center"><span
                    style="color: <?php echo $status_color[$item['Status']['ID']]; ?>"><?php echo $item['Status']['Name'] ?></span>
<!--                --><?php //if($item['Project']['Status_id']==6){
//                    echo('<br><span>'.$item['Project']['CompTime'].'</span>');
//                }?>
            </td>
            <td>
                <div class="btn-group">
                    <?php if(count($item['Product'])< $item['Project']['Quantity']) { ?>
                        <a class="btn btn-small show-tooltip" title="Kích hoạt"
                           onclick="status(<?php echo $item['Project']['ID'] ?>)"><i class="icon-exchange"></i>
                        </a>

                    <?php
                    }
                    if ($item['Project']['Status_id'] >= 3 && $item['Project']['Status_id'] < 6) {
                        ?>
                        <a class="btn btn-small show-tooltip" title="Gom hàng - giao hàng"
                           href="<?php echo Router::url(array('controller' => 'Projects', 'action' => 'bring')); ?>/<?php echo $item['Project']['ID']; ?>">
                            <i class="icon-ok"></i>
                        </a>
                    <?php }
                    if ($item['Project']['Status_id'] <= 6){?>
                    <a class="btn btn-small btn-primary show-tooltip" title="Sửa"
                       href="<?php echo Router::url(array('controller' => 'Projects', 'action' => 'edit')); ?>/<?php echo $item['Project']['ID']; ?>">
                        <i class="icon-edit"></i>
                    </a>
                    <?php }?>
                    <a data-toggle="modal" data-rate="4" data-project-id="<?php echo $item['Project']['ID'] ?>"
                       data-projectName="<?php echo $item['Project']['Name'] ?>" role="button" href="#reject"
                       class="btn btn-small btn-success show-tooltip reject_project" title="Reject"
                       href="#reject">
                        <i class="icon-reply"></i>
                    </a>
                    <?php
                    $deliver = 0;
                    foreach($item['Product'] as $deliver_check){
                        if ($deliver_check['deliver_user_id'] == 0){
                        }else{
                            $deliver = 1;
                        }
                    }
                    // Nut xoa project tai day :
                    if ($item['Project']['Status_id'] == 1 || $deliver==0) {
                        echo $this->Form->postLink(
                            $this->Html->tag('i', '', array('class' => 'icon-trash')),
                            array('action' => 'delete', $item['Project']['ID']),
                            array('class' => 'btn btn-small btn-danger show-tooltip', 'title' => 'Xóa', 'escape' => false, 'confirm' => __('Are you sure?'))
                        );
                    }
                    
                    ?>
                </div>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<div class="row-fluid">
    <div class="span6">
        <div class="dataTables_info"
             id="table1_info"><?php echo $this->Paginator->counter('Tổng số bản ghi {:current}/{:count}'); ?></div>
    </div>
    <div class="span6">
        <div class="dataTables_paginate paging_bootstrap pagination">
            <ul>
                <?php
                echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
                echo $this->Paginator->numbers(array('separator' => '', 'currentTag' => 'a', 'currentClass' => 'active', 'tag' => 'li', 'first' => 1));
                echo $this->Paginator->next(__('next'), array('tag' => 'li', 'currentClass' => 'disabled'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
                ?>
            </ul>
        </div>
    </div>
</div>
</div>
</div>
<footer>
    <?php echo $this->element('footer'); ?>
</footer>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="reject"
     style="display: none;">
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="modal-1"
     style="display: none;">

</div>
<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
<script>
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''//Tiêu đề của biểu đồ
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: ''//text ben trai bieu do
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: '<b>{point.y:.1f} file</b>'
        },
        series: [
            {
                name: 'Population',
                data: [
                    ['Tổng file', <?php echo $total?>],
                    ['File chưa chia', <?php echo $un_dissevered?>],
                    ['File đã chia chưa done', <?php echo $unprocessed?>],
                    ['File đã done', <?php echo $processed?>],
                ],
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    x: 4,
                    y: 10,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 3px black'
                    }
                }
            }
        ]
    });
});

function status(id) {
    $('.fixed_bottom').show();
    $.ajax({
        type: "POST",
        url: "<?php echo Router::url(array('controller'=>'Projects','action' => 'ActiveProject')); ?>/" + id,
        success: function (data) {
            if (data.returnCode == 1) {
                $.gritter.add({title: "Kích hoạt thành công!", text: '<a href="#" style="color:#ccc">Bạn đã thay đổi trạng thái đơn hàng</a>.', image: "<?php echo $this->webroot. 'img/notification.png';?>", sticky: false, time: ""});
                window.location.reload();
            } else {
                alert(data.returnMsg);
            }
        }
    });
    $('.fixed_bottom').hide();

}

function Pagination() {
    location.href = "/Projects/index/" + $('#pagination').val();
}

$(document).ready(function () {
    $.post("<?php echo Router::url(array('controller'=>'Projects','action' => 'statistic')); ?>")
        .done(function (data) {
            $('#statistic').html(data);
        });


    $('.order').hide();
    $('.submit-cancel').hide();
    $('.submit-order').hide();
    $('.loading').hide();

    $('.order').click(function () {
        var id = $(this).attr('id');
        var nid = id.substring(1);
        $('#in' + nid).show();
    });

    $('.show-input').click(function () {
        $(this).hide();
        $('#' + $(this).attr('data')).show();
        $('#cancel-' + $(this).attr('data')).show();
    });
    $('.submit-cancel').click(function () {
        $(this).hide();
        $('#comp-time-' + $(this).attr('data')).show();
        $('#' + $(this).attr('data')).hide();
        $('.submit-order').hide();
    });
    $('.submit-order').click(function () {
        $('.submit-cancel').hide();
        var data_id = $(this).attr('data');
        var id = $(this).attr('id');
        var nid = id.substring(2);
        var order = $('#a' + nid).val();
        if (order == 0 || order == '' || order == null) {
            alert('Bạn phải nhập thứ tự!');
        } else {
//                $('#l' + nid).show();
            $('#a' + nid).hide();
            $('#in' + nid).hide();
            $.post("<?php echo Router::url(array('controller'=>'Projects','action' => 'editAField')); ?>", {ID: nid, returnTime: order})
                .done(function (data) {
                    $('#a' + nid).val(data.Order);
                    $('#in' + nid).hide();
                    $('#l' + nid).hide();
                    $('#comp-time-' + data_id).html(order).show();
                    $('#a' + nid).val(order);
                    $.gritter.add({title: "Thành công!", text: '<a href="#" style="color:#ccc">Bạn đã thay đổi ngày trả hàng</a>.', image: "<?php echo $this->webroot. 'img/notification.png';?>", sticky: false, time: ""});
//                        location.reload(true);
                });
        }
    });
    $('#del').click(function () {
        var values = new Array();
        $.each($("input[name='ck[]']:checked"), function () {
            values.push($(this).val());
        });
        $.post("<?php echo Router::url(array('action' => 'multi_del')); ?>", { 'items[]': values })
            .done(function (data) {
                if (data) {
                    window.location.reload();
                } else {

                }
            });
    });


    <?php if(isset($CF["AUTOPRODUCT"])&&($CF["AUTOPRODUCT"]==1)){ ?>
    $('#auto_project').click(function () {
        var values = new Array();
        $.each($("input[name='ck[]']:checked"), function () {
            values.push($(this).val());
        });
        if (values.length == 0) {
            alert('Bạn chưa chọn dự án!');
        } else {
            $.post("<?php echo Router::url(array('action' => 'auto_project')); ?>", { 'items[]': values })
                .done(function (data) {
                    if (data) {
                        alert(data + ' Dự án đã được chia tự động.');
                        window.location.reload();
                    } else {
                        alert('Lỗi! Không thể chia tự động.');
                    }
                });
        }
    });

    <?php }?>
});


function getCom(val) {
    $.get("<?php echo $this->html->url(array('controller'=>'Coms','action'=>'getCom'),true)?>/" + val, function (data) {
        $("#com").html(data);
    });
}

function getCustomers(country_id) {
    $.get("<?php echo $this->html->url(array('controller'=>'Customers','action'=>'getCustomers'),true)?>/" + country_id + "?search=1", function (data) {
        $("#customer").html(data);
    });
}
$(document).ready(function () {
    $('.download-file').click(function(){
        var a = document.createElement('a');
        a.href = $(this).attr('data');
        a.download = $(this).attr('data-name');
        a.click();
    });
});

function getCustomer_Groups(country_id) {
    $.get("<?php echo $this->html->url(array('controller'=>'Customergroups','action'=>'getCustomerGroups'),true)?>/" + country_id + "?search=1", function (data) {
        $("#customer_group").html(data);
    });
}
$(document).ready(function () {
    <?php if(isset($_GET['Country_id']) && $_GET['Country_id']){ ?>
    $.get("<?php echo $this->html->url(array('controller'=>'Customers','action'=>'getCustomers'),true)?>/<?php echo $_GET['Country_id'] ?>?search=1", function (data) {
        $("#customer").html(data);
        <?php if(isset($_GET['Customer_id']) && $_GET['Customer_id']){ ?>
        $('#Customer_id').val(<?php echo $_GET['Customer_id'] ?>);

        $.get("<?php echo $this->html->url(array('controller'=>'Customergroups','action'=>'getCustomerGroups'),true)?>/<?php echo $_GET['Customer_id'] ?>?search=1", function (data) {
            $("#customer_group").html(data);
            <?php if(isset($_GET['CustomerGroup_id']) && $_GET['CustomerGroup_id']){ ?>
            $('#CustomerGroup_id').val(<?php echo $_GET['CustomerGroup_id'] ?>);
            <?php } ?>
        });

        <?php } ?>
    });


    <?php } ?>
});

function getLarge(id) {
    $.get("<?php echo $this->html->url(array('controller'=>'workinggroups','action'=>'getWorking'),true)?>/" + id, function (data) {
        $("#working_group").html(data);
    });
}
function getNVS(i) {
    $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'SelectUsers'),true)?>" + "/" + i, function (data) {
        $("#modal-1").html(data);
    });
}
$('.reject_project').click(function () {
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
