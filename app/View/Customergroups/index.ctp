<?php
echo $this->element('top_page', array(
    'page_title' => 'Quản lý nhóm khách hàng',
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
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><b><?php echo __('Quản lý nhóm khách hàng'); ?></b></a>
        </li>
    </ul>
</div>
<?php echo $this->Session->flash(); ?>
<div class="row-fluid">
<div class="span12">
<div class="box">
<div class="span12">
    <div class="box  table-bordered">
        <div class="box-title">
            <h3><i class="icon-search"></i> <?php echo __(' Tìm kiếm');?></h3>

            <div class="box-tool">
                <a href="#" data-modal="setting-modal-1" data-action="config"><i class="icon-gear"></i></a>
                <a href="#" data-action="collapse"><i
                        class="icon-chevron-<?php if($country_id > 0 ){echo 'up';}else{echo 'down';}?>"></i></a>
            </div>
        </div>
        <div class="box-content" style="<?php if($country_id > 0 ){echo 'display: block;';}else{echo 'display: none;';}?>">
            <?php echo $this->Form->create('Project', array('type' => 'get')) ?>
            <div class="row-fluid">
                <div class="span4">
                    <div class="control-group">
                        <label class="control-label">
                            <?php echo __('Quốc gia'); ?>:</label>

                        <div class="controls">
                            <?php echo $this->Form->input('Country_id', array('class' => 'span12','value' => $country_id, 'options' => $countries, 'onchange' => 'getCustomers(this.value)', 'empty' => '--Chọn quốc gia--', 'div' => false, 'label' => false)); ?>
                        </div>
                    </div>
                </div>
                <div class="span4">
                    <div class="control-group" id="customer">
                        <label class="control-label">
                            <?php echo __('Khách hàng'); ?>:</label>

                        <div class="controls">
                            <?php echo $this->Form->input('Customer_id', array('class' => 'span12' , 'options' => array('empty' => '--Chọn khách hàng--'), 'div' => false, 'label' => false)); ?>
                        </div>
                    </div>
                </div>
                <div class="span4">
                    <div class="control-group" id="customer_group">
                        <label class="control-label">
                            <?php echo __('Nhóm khách hàng'); ?>:</label>

                        <div class="controls">
                            <?php echo $this->Form->input('CustomerGroup_id', array('class' => 'span12' , 'options' => array('' => '--Chọn nhóm khách hàng--'), 'div' => false, 'label' => false)); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions clearfix text-center">
                <input type="submit" class="btn btn-primary " value="<?php echo __('Tìm kiếm') ?>">
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<div style="clear: both"></div>
<div class="">
    <div class="btn-toolbar pull-right clearfix">
        <div class="btn-group">
            <div class="">
                <a style="margin-right: 5px" class="btn btn-small btn-primary show-tooltip" title="Thêm mới"
                   href="<?php echo Router::url(array('controller' => 'customergroups', 'action' => 'add')); ?>">
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
    </div>
</div>
<table class="table table-advance" id="table1">
    <thead>
    <tr>
        <th style="width:18px"><input type="checkbox"/></th>
        <th><?php echo __('Tên nhóm khách hàng'); ?></th>
        <th><?php echo __('Tên khách hàng'); ?></th>
        <th><?php echo __('Quốc gia'); ?></th>
        <th class="text-center"><?php echo __('Thứ tự'); ?></th>
        <th class="text-center"><?php echo __('Trạng thái'); ?></th>
        <th style="width:100px"><?php echo __('Hành động'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($customerGroups as $item):
        ?>
        <tr class="table-flag">
            <td><input value="<?php echo $item['Customergroup']['id']; ?>" type="checkbox" name="ck[]"/>
            </td>
            <td><?php echo $this->Html->link($item['Customergroup']['name'], array('action' => 'edit', $item['Customergroup']['id'])); ?></td>
            <td class="text-center"><?php echo $item['Customer']['customer_code'] ?></td>
            <td class="text-center"><?php echo $item['Country']['name'] ?></td>
            <td class="text-center"><?php echo $item['Customergroup']['order'] ?></td>
            <td class="text-center"
                id="ajax<?php echo $item['Customergroup']['id'] ?>"><a class="btn btn-circle show-tooltip"
                                                                       onclick="status(<?php echo $item['Customergroup']['id'] . ',' . $item['Customergroup']['status']; ?>)"><?php if ($item['Customergroup']['status'] == 1) {
                        echo '<i class="icon-circle green"></i>';
                    } else {
                        echo '<i class="icon-circle-blank red"></i>';
                    } ?></a>
            </td>
            <td>
                <div class="btn-group">
                    <a class="btn btn-small show-tooltip" title="Sửa"
                       href="<?php echo Router::url(array('controller' => 'Customergroups', 'action' => 'edit')); ?>/<?php echo $item['Customergroup']['id']; ?>"><i
                            class="icon-edit"></i></a>
                    <?php
                    echo $this->Form->postLink(
                        $this->Html->tag('i', '', array('class' => 'icon-trash')),
                        array('action' => 'delete', $item['Customergroup']['id']),
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
    function status(id, status) {
        if (status == 1) {
            status = 0;
        }
        else {
            status = 1;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'customergroups','action' => 'status')); ?>/" + id + "/" + status,
            success: function (data) {
                window.location.reload();
            }
        });
    }


    function Pagination() {
        location.href = "/customergroups/index/" + $('#pagination').val();
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
    function getCustomers(country_id) {
        $.get("<?php echo $this->html->url(array('controller'=>'Customers','action'=>'getCustomers'),true)?>/" + country_id, function (data) {
            $("#customer").html(data);
        });
    }

    function getCustomer_Groups(country_id) {
        $.get("<?php echo $this->html->url(array('controller'=>'Customergroups','action'=>'getCustomerGroups'),true)?>/" + country_id, function (data) {
            $("#customer_group").html(data);
        });
    }
</script>