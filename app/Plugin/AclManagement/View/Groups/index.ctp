<?php //debug($category);die;?><!-- -->
<?php
echo $this->element('top_page', array(
    'page_title' => 'Quản lý nhóm người dùng'
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
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><?php echo __('Quản lý nhóm người dùng'); ?></a>
        </li>
    </ul>
</div>
<?php echo $this->Session->flash(); ?>
<div id="alert"></div>
<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-content">
                <div class="btn-toolbar pull-right clearfix">
                    <div class="btn-group">
                        <div class="box-content">
<!--                            <a style="margin-right: 5px" class="btn btn-small btn-primary show-tooltip" title="Thêm mới"-->
<!--                               href="/admin/groups/add">-->
<!--                                <button class="btn btn-primary">-->
<!--                                    <i class="icon-plus"></i> Thêm-->
<!--                                </button>-->
<!--                            </a>-->
<!--                            <a id="del" style="margin-right: 5px" class="btn btn-small btn-danger show-tooltip"-->
<!--                               title="Xóa"-->
<!--                               href="#">-->
<!--                                <button class="btn btn-danger">-->
<!--                                    <i class="icon-trash"></i></i> Xóa-->
<!--                                </button>-->
<!--                            </a>-->
                            <a style="" class="btn btn-small btn-success show-tooltip" title="Quay lại"
                               href="/">
                                <button class="btn btn-success">
                                    <i class="icon-repeat"></i> Quay lại
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <table class="table table-advance" id="table1">
                    <thead>
                    <tr>
                        <th style="width:18px"><input type="checkbox"/></th>
                        <th class="text-center"><?php echo $this->Paginator->sort('id'); ?></th>
                        <th class="text-center"><?php echo $this->Paginator->sort('name'); ?></th>
                        <th class="text-center"><?php echo $this->Paginator->sort('created'); ?></th>
                        <th class="text-center"><?php echo $this->Paginator->sort('modified'); ?></th>
                        <th style="width:200px" class="text-center"><?php echo __('Hành động'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($groups as $group):
                        ?>
                        <tr class="table-flag">
                            <td><input value="<?php echo $group['Group']['id']; ?>" type="checkbox" name="ck[]"/></td>
                            <td class="text-center"><?php echo h($group['Group']['id']); ?>&nbsp;</td>
                            <td class="text-center"><?php echo h($group['Group']['name']); ?>&nbsp;</td>
                            <td class="text-center"><?php echo h($group['Group']['created']); ?>&nbsp;</td>
                            <td class="text-center"><?php echo h($group['Group']['modified']); ?>&nbsp;</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a class="btn btn-small show-tooltip" title="Edit"
                                       href="/admin/groups/edit/<?php echo $group['Group']['id']; ?>"><i
                                            class="icon-edit"></i></a>
                                    <?php
                                    echo $this->Form->postLink(
                                        $this->Html->tag('i', '', array('class' => 'icon-trash')),
                                        array('action' => 'delete', $group['Group']['id']),
                                        array('class' => 'btn btn-small btn-danger show-tooltip', 'escape' => false, 'confirm' => __('Are you sure?'))
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
                            //'Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}'
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
</div>
<!-- END Main Content -->

<footer>
    <p>2013 © FLATY Admin Template.</p>
</footer>
<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
<script>
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