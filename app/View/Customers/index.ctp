<?php
echo $this->element('top_page', array(
    'page_title' => 'Quản lý danh mục khách hàng'
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
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><b><?php echo __('Quản lý danh mục khách hàng'); ?></b></a>
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
                            <a style="margin-right: 5px" class="btn btn-small btn-primary show-tooltip" title="Thêm mới"
                               href="<?php echo Router::url(array('controller' => 'customers', 'action' => 'add')); ?>">
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
                    <div class="span9">
                        <?php echo $this->Form->create('Customers', array('type' => 'get'));
                        ?>
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">
                                    <?php echo __('Quốc gia'); ?>:</label>

                                <div class="controls">
                                    <?php echo $this->Form->input('Country_id', array('class' => 'span12', 'value' => $country_id, 'options' => $countries, 'onchange' => 'getCustomers(this.value)', 'empty' => '--Chọn quốc gia--', 'div' => false, 'label' => false)); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">
                                    <?php echo __('Tên khách hàng'); ?>:</label>

                                <div class="controls">
                                    <?php echo $this->Form->input('name', array('class' => 'span12','value'=> $name, 'type' => 'text', 'div' => false, 'label' => false, 'style' => 'margin-bottom:0', 'placeholder' => 'Tên khách hàng')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span1">
                            <label class="control-label">
                                <?php echo __(''); ?></label>
                            <div class="form-actions clearfix text-center">
                                <input type="submit" class="btn btn-primary " value="<?php echo __('Tìm kiếm') ?>">
                            </div>
                        </div>

                        <?php echo $this->Form->end();
                        ?>
                    </div>
                </div>
            </div>
            <table class="table table-advance" id="table1">
                <thead>
                <tr>
                    <th style="width:18px"><input type="checkbox"/></th>
                    <th><?php echo __('Tên khách hàng'); ?></th>
                    <th><?php echo __('Địa chỉ'); ?></th>
                    <th><?php echo __('Email'); ?></th>
                    <th class="text-center"><?php echo __('Thứ tự'); ?></th>
                    <th class="text-center"><?php echo __('Trạng thái'); ?></th>
                    <th style="width:100px"><?php echo __('Hành động'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($customer as $item):
                    ?>
                    <tr class="table-flag">
                        <td><input value="<?php echo $item['Customer']['id']; ?>" type="checkbox" name="ck[]"/>
                        </td>
                        <td><?php echo $this->Html->link($item['Customer']['name'], array('action' => 'edit', $item['Customer']['id'])); ?></td>
                        <td><?php echo $item['Customer']['address'] ?></td>
                        <td><?php echo $item['Customer']['email'] ?></td>
                        <td class="text-center"><?php echo $item['Customer']['order'] ?></td>
                        <td class="text-center"
                            id="ajax<?php echo $item['Customer']['id'] ?>"><a class="btn btn-circle show-tooltip"
                                                                              onclick="status(<?php echo $item['Customer']['id']; ?>,<?php echo $item['Customer']['status']; ?>)"><?php if ($item['Customer']['status'] == 1) {
                                    echo '<i class="icon-circle green"></i>';
                                } else {
                                    echo '<i class="icon-circle-blank red"></i>';
                                } ?></a>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a class="btn btn-small show-tooltip" title="Sửa"
                                   href="<?php echo Router::url(array('controller' => 'customers', 'action' => 'edit')); ?>/<?php echo $item['Customer']['id']; ?>"><i
                                        class="icon-edit"></i></a>
                                <?php
                                echo $this->Form->postLink(
                                    $this->Html->tag('i', '', array('class' => 'icon-trash')),
                                    array('action' => 'delete', $item['Customer']['id']),
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
<footer>
    <?php
    echo $this->element('footer');
    ?>
</footer>
</div>
<!-- END Main Content -->

<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
<script>
    function status(id, status) {
        if (status == 1) {
            status = 0;
        }
        else {
            status = 1;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'customers','action' => 'status')); ?>/" + id + "/" + status,
            success: function (data) {

                window.location.reload();
            }
        });
    }

    function Pagination() {
        location.href = "/customers/index/" + $('#pagination').val();
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
</script>