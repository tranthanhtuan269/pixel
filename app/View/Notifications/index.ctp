<?php
function _substr($str, $length, $minword = 1)
{
    $sub = '';
    $len = 0;
    foreach (explode(' ', $str) as $word) {
        $part = (($sub != '') ? ' ' : '') . $word;
        $sub .= $part;
        $len += strlen($part);
        if (strlen($word) > $minword && strlen($sub) >= $length) {
            break;
        }
    }
    return $sub . (($len < strlen($str)) ? '...' : '');
}

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
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><b><?php echo __('Quản lý tin nhắn'); ?></b></a>
        </li>
    </ul>
</div>
<?php echo $this->Session->flash(); ?>
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-content">
                <div class="btn-toolbar pull-right clearfix">
                    <div class="btn-group">
                        <div class="box-content">
                            <a style="margin-right: 5px" class="btn btn-small btn-primary show-tooltip" title="Thêm mới"
                               href="<?php echo Router::url(array('controller' => 'notifications', 'action' => 'add')); ?>">
                                <button class="btn btn-primary">
                                    <i class="icon-plus"></i><?php echo __(' Gửi tin nhắn'); ?>
                                </button>
                            </a>
                            <a id="del" style="margin-right: 5px" class="btn btn-small btn-danger show-tooltip"
                               title="Xóa"
                               href="#">
                                <button class="btn btn-danger">
                                    <i class="icon-trash" con></i><?php echo __(' Xóa'); ?>
                                </button>
                            </a>
                            <a id="refresh" style="" class="btn btn-small btn-success show-tooltip" title="Refresh"
                               href="#">
                                <button class="btn btn-success">
                                    <i class="icon-repeat"></i> <?php echo __(' Refresh'); ?>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="row-fluid">
                    <div class="span3">
                        <div id="table1_length" class="dataTables_length"><label>
                                <select name="DataTables_Table_0_length" id="pagination"
                                        aria-controls="table1" size="1"
                                        onchange="Pagination()">

                                    <option value="10"
                                            <?php if ($row == 10){ ?>selected="selected" <?php } ?>>
                                        10
                                    </option>
                                    <option value="25"
                                            <?php if ($row == 25){ ?>selected="selected" <?php } ?>>
                                        25
                                    </option>
                                    <option value="50"
                                            <?php if ($row == 50){ ?>selected="selected" <?php } ?>>
                                        50
                                    </option>
                                    <option value="100"
                                            <?php if ($row == 100){ ?>selected="selected" <?php } ?>>
                                        100
                                    </option>

                                </select>
                                <?php echo __('Dòng/trang'); ?></label></div>
                    </div>
                    <div class="span3">
                        <?php
                        echo $this->Form->input('status', array(
                            'type' => 'select',
                            'class' => 'input-medium',
                            'id' => 'alert_notification',
                            'label' => false,
                            'options' => $alert,
                            'empty' => '--Tất cả tin nhắn--'
                        ));
                        ?>
                    </div>
                    <div class="span6">
                        <?php echo $this->Form->create('Notification', array('type' => 'get'));
                        ?>
                        <div class="dataTables_filter" id="table1_filter"><label><?php echo __('Tìm kiếm'); ?>: <input
                                    type="text" class="text" name="keyword" aria-controls="table1"
                                    value="<?php if ($keyword) {
                                        echo $keyword;
                                    } ?>" placeholder="Tìm..."></label></div>
                        <?php echo $this->Form->end();
                        ?>
                    </div>
                </div>
            </div>
            <table class="table table-advance" id="table1">
                <thead>
                <tr>
                    <th style="width:18px"><input type="checkbox"/></th>
                    <th><?php echo __('Loại'); ?></th>
                    <th><?php echo __('Gửi từ'); ?></th>
                    <th><?php echo __('Tiêu đề'); ?></th>
                    <th class="text"><?php echo __('Nội dung'); ?></th>
                    <th class="text-center"><?php echo __('Thời gian'); ?></th>
                    <th style="width:100px"><?php echo __('Hành động'); ?></th>
                </tr>
                </thead>
                <tbody id="list_notification">
                <?php
                foreach ($notifications as $item):
                    $mystring = $item['Notification']['read_id'];
                    $pos = strpos($mystring, $id);
                    ?>
                    <tr class="table-flag">
                        <td><input value="<?php echo $item['Notification']['id']; ?>" type="checkbox" name="ck[]"/></td>
                        <td class="text">
                            <?php
                            foreach ($alerts as $name):
                                if ($name['Alert']['id'] == $item['Notification']['alert_id'])
                                    echo  $name['Alert']['name'];
                            endforeach
                            ?>
                        </td>
                        <td class="text">
                            <?php
                            if ($item['Notification']['user_id'] != 0){
                            foreach ($users as $user):
                                if ($user['User']['id'] == $item['Notification']['user_id']) {
                                    echo $user['User']['name'];
                                }
                            endforeach;
                            } else {
                                echo 'Hệ thống';
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                           // debug($id);die;

                            if($pos === false){
                                echo '<strong>'.$this->Html->link(_substr($item['Notification']['title'], 35), array('action' => 'view', $item['Notification']['id'])).'</strong>';
                            } else {

                                echo $this->Html->link(_substr($item['Notification']['title'], 35), array('action' => 'view', $item['Notification']['id']));
                            }


                            ?>
                        </td>
                        <td class="text"><?php
                            if($pos === false){
                                echo '<strong>'._substr($item['Notification']['content'], 35).'</strong>';

                            } else {
                                echo _substr($item['Notification']['content'], 35);

                            }
                            ?>
                        </td>
                        <td class="text-center"><?php echo $item['Notification']['createdate'] ?></td>
                        <td>
                            <div class="btn-group">
                                <a class="btn btn-small show-tooltip" title="Sửa"
                                   href="<?php echo Router::url(array('controller' => 'notifications', 'action' => 'view')); ?>/<?php echo $item['Notification']['id']; ?>"><i
                                        class="icon-zoom-in"></i></a>
                                <?php
                                echo $this->Form->postLink(
                                    $this->Html->tag('i', '', array('class' => 'icon-trash')),
                                    array('action' => 'delete', $item['Notification']['id']),
                                    array('class' => 'btn btn-small btn-danger show-tooltip', 'title' => 'Xóa', 'escape' => false, 'confirm' => __('Are you sure?'))
                                );
                                ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="row-fluid">
                <div class="span6">
                    <div class="dataTables_info" id="table1_info"><?php
                        echo $this->Paginator->counter('Tổng số bản ghi {:current}/{:count}');
                        ?></div>
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
</div>
<footer>
    <?php
    echo $this->element('footer');
    ?>
</footer>
</div>
<!-- END Main Content -->
<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
<script>
    function Pagination() {
        location.href = "/notifications/index/" + $('#pagination').val();
    }

    $(document).ready(function () {

        $('#del').click(function () {
            var values = new Array();
            $.each($("input[name='ck[]']:checked"), function () {
                values.push($(this).val());
            });
            if (values == '') {
                alert('Bạn chưa chọn dòng để xóa!')
            }
            else {
                var x = confirm("Bạn có chắc chắn muốn xóa không?");
                if (x) {
                    $.post("<?php echo Router::url(array('action' => 'multi_del')); ?>", { 'items[]': values })
                        .done(function (data) {
                            if (data) {
                                window.location.reload();
                            } else {

                            }
                        });
                    ;
                } else {
                    return false;
                }
            }

        });

    });
    $(document).ready(function () {
        $('#alert_notification').change(function () {
            var status = $(this).val();
            $.post("<?php echo Router::url(array('action' => 'alert_type')); ?>", { 'status': status })
                .done(function (data) {
                    if (data) {
                        $('#list_notification').html(data);
                    } else {
                        $.gritter.add({title: "Lỗi!", text: 'Không thực hiện được thao tác <a href="#" style="color:#ccc"></a>.', image: "<?php echo $this->webroot. 'img/notification.png';?>", sticky: false, time: ""});
                        return false
                    }
                });
        });
    });
</script>
