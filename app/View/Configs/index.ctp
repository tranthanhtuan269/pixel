<?php
echo $this->element('top_page', array(
    'page_title' => 'Quản lý danh mục cấu hình',
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
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><b><?php echo __('Quản lý danh mục cấu hình'); ?></b></a>
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
                               href="<?php echo Router::url(array('controller'=>'configs','action' => 'add')); ?>">
                                <button class="btn btn-primary">
                                    <i class="icon-plus"></i><?php echo __(' Thêm'); ?>
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
                    <div class="span6">
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
                    <div class="span6">
                        <?php echo $this->Form->create('Config', array('type' => 'get'));
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
                    <th><?php echo __('Tên biến'); ?></th>
                    <th class="text-center"><?php echo __('Giá trị'); ?></th>
                    <th class="text-center"><?php echo __('Mô tả'); ?></th>
                    <th style="width:100px"><?php echo __('Hành động'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($configs as $item):
                    ?>
                    <tr class="table-flag">
                        <td><input value="<?php echo $item['Config']['id']; ?>" type="checkbox" name="ck[]"/></td>
                        <td><?php echo $this->Html->link($item['Config']['code'], array('action' => 'edit', $item['Config']['id'])); ?></td>
                        <td class="text-center"><?php echo $item['Config']['value'] ?></td>
                        <td class="text-center"><?php echo $item['Config']['description'] ?></td>
                        <td>
                            <div class="btn-group">
                                <a class="btn btn-small show-tooltip" title="Sửa"
                                   href="<?php echo Router::url(array('controller'=>'configs','action' => 'edit')); ?>/<?php echo $item['Config']['id']; ?>"><i
                                        class="icon-edit"></i></a>
                                <?php
                                echo $this->Form->postLink(
                                    $this->Html->tag('i', '', array('class' => 'icon-trash')),
                                    array('action' => 'delete',$item['Config']['id']),
                                    array('class' => 'btn btn-small btn-danger show-tooltip','title'=>'Xóa' ,'escape' => false, 'confirm' => __('Are you sure?'))
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
        location.href = "/configs/index/" + $('#pagination').val();
    }

    $(document).ready(function () {

        $('#del').click(function () {
            var values = new Array();
            $.each($("input[name='ck[]']:checked"), function () {
                values.push($(this).val());
            });
            if(values == ''){
                alert('Bạn chưa chọn dòng để xóa!')
            }
            else{
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
</script>