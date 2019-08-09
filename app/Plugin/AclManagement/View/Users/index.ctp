<?php //debug($category);die;?><!-- -->
<?php
echo $this->element('top_page', array(
    'page_title' => 'Quản lý người dùng'
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
        </li>
    </ul>
</div>
<!--<div class="alert alert-success">--><?php //echo $this->Session->flash(); ?><!--</div>-->
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
                            <a style="margin-right: 5px" class="btn btn-small btn-primary show-tooltip" title="Thêm mới"
                               href="<?php echo $this->webroot;?>admin/users/add">
                                <button class="btn btn-primary">
                                    <i class="icon-plus"></i> Thêm
                                </button>
                            </a>
                            <a id="del" style="margin-right: 5px" class="btn btn-small btn-danger show-tooltip"
                               title="Xóa"
                               href="#">
                                <button class="btn btn-danger">
                                    <i class="icon-trash"></i></i> Xóa
                                </button>
                            </a>
                            <a id="search" style="margin-right: 5px" class="btn btn-small btn-danger show-tooltip"
                               title="Tìm kiếm"
                               href="#">
                                <button class="btn btn-danger">
                                    <i class="icon-search"></i><?php echo __(' Tìm'); ?>
                                </button>
                            </a>
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
                <table class="table table-advance" id="table1">
                    <thead>
                    <tr>
                        <th style="width:18px"><input type="checkbox" name="ck[]"/></th>
                        <th class="text-center"><?php echo $this->Paginator->sort('name');?></th>
                        <th class="text-center"><?php echo $this->Paginator->sort('email');?></th>
                        <th class="text-center"><?php echo $this->Paginator->sort('group_id');?></th>
                        <th class="text-center"><?php echo $this->Paginator->sort('department_id');?></th>
                        <th class="text-center"><?php echo $this->Paginator->sort('status');?></th>
                        <th style="width:120px"><?php echo __('Hành động'); ?></th>
                    </tr>
                    <tr id="form_search" style="<?php if($name != '' || $email != '' || $group_id > 0 || $department_id >0){echo '';}else{echo 'display: none';}?>">
                        <?php echo $this->Form->create('User', array('type' => 'get'));
                        ?>
                        <th>

                        </th>
                        <th class="text-center">
                            <input id="name" type="text" class="input-small input_fix" name="name" value="<?php if ($name) {
                                echo $name;
                            } ?>">
                        </th>
                        <th class="text-center">
                            <input id="email" type="text" class="input-small input_fix" name="email" value="<?php if ($email) {
                                echo $email;
                            } ?>">
                        </th>
                        <th class="text-center">
                            <?php
                            echo $this->Form->input('group_id', array(
                                'type' => 'select',
                                'id' => 'group',
                                'value' => $group_id,
                                'label' => false,
                                'class' => 'select2-choice select2-default input-medium',
                                'options' => $group,
                                'empty' => 'Chọn nhóm'
                            ));
                            ?>
                        </th>
                        <th class="text-center">
                            <?php
                            echo $this->Form->input('department_id', array(
                                'type' => 'select',
                                'label' => false,
                                'id' => 'department',
                                'value' => $department_id,
                                'class' => 'select2-choice select2-default input-medium',
                                'options' => $department,
                                'empty' => 'Chọn phòng ban'
                            ));
                            ?>
                        </th>
                        <th></th>
                        <th>
                            <button type="submit" class="btn btn-primary"><?php echo __('Tìm'); ?></button>
                            <button type="reset" class="btn"><?php echo __('Reset'); ?></button>
                        </th>

                        <?php echo $this->Form->end();
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($users as $user):
                        ?>
                        <tr class="table-flag">
                            <td><input value="<?php echo $user['User']['id']; ?>" type="checkbox" name="ck[]"/></td>
                            <td class="text-center"><?php echo h($user['User']['name']); ?>&nbsp;</td>
                            <td class="text-center"> <?php echo h($user['User']['email']); ?>&nbsp;</td>
                            <td class="text-center"><?php echo h($user['Group']['name']); ?>&nbsp;</td>
                            <td class="text-center"><?php echo h($user['Department']['name']); ?>&nbsp;</td>
                            <td class="text-center">
                                <?php
                                $adminRoleName = array('admin', 'administrator');
                                if(in_array(strtolower($user['Group']['name']), $adminRoleName)){//Admin
                                    echo $this->Html->image('http://pixel-files.pixelvn.com/acl/img/icons/tick_disabled.png');
                                }else{
                                    echo '<span style="cursor: pointer">';
                                    echo $this->Html->image('http://pixel-files.pixelvn.com/acl/img/icons/allow-' . intval($user['User']['status']) . '.png',
                                        array('onclick' => 'published.toggle("status-'.$user['User']['id'].'", "'.$this->Html->url('/acl_management/users/toggle/').$user['User']['id'].'/'.intval($user['User']['status']).'");',
                                            'id' => 'status-'.$user['User']['id']
                                        ));
                                    echo '</span>&nbsp;';
                                }
                                ?>
                            </td>
                            <td>
                                <a data-toggle="modal" class="btn btn-small show-tooltip" role="button"
                                   href="#modal-<?php echo $user['User']['id']; ?>" data-original-title="Xem"><i
                                        class="icon-zoom-in"></i></a>
                                    <a class="btn btn-small show-tooltip" title="Edit"
                                       href="<?php echo Router::url(array('action' => 'edit')); ?>/<?php echo $user['User']['id']; ?>"><i
                                            class="icon-edit"></i></a>
                                    <?php
                                    echo $this->Form->postLink(
                                        $this->Html->tag('i', '', array('class' => 'icon-trash')),
                                        array('action' => 'delete', $user['User']['id']),
                                        array('class' => 'btn btn-small btn-danger show-tooltip', 'escape' => false, 'confirm' => __('Are you sure?'))
                                    );
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <div id="modal-<?php echo $user['User']['id']; ?>" class="modal hide fade" tabindex="-1"
                             role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="myModalLabel3"><?php echo __('Thông tin cá nhân'); ?></h3>
                            </div>
                            <div class="modal-body">
                                <div class="employee">
                                    <?php if ($user['User']['avatar'] != '') { ?>
                                        <img src="<?php echo $this->webroot;?>medias/avatar_employee/<?php echo $user['User']['avatar']; ?>">
                                    <?php } else { ?>
                                        <img src="<?php echo $this->webroot;?>img/no_images.jpg">
                                    <?php } ?>
                                    <div>
                                        <div>
                                            <lable
                                                class="name"><?php echo __('Mã nhân viên: '); ?></lable><?php echo $user['User']['code_staff']; ?>
                                            <div style="clear: both"></div>
                                        </div>
                                        <div>
                                            <lable
                                                class="name"><?php echo __('Tên nhân viên: '); ?></lable><?php echo $user['User']['name']; ?>
                                            <div style="clear: both"></div>
                                        </div>
                                        <div>
                                            <lable
                                                class="name"><?php echo __('Số điện thoại: '); ?></lable><?php echo $user['User']['phone']; ?>
                                            <div style="clear: both"></div>
                                        </div>
                                        <div>
                                            <lable
                                                class="name"><?php echo __('Email: '); ?></lable><?php echo $user['User']['email']; ?>
                                            <div style="clear: both"></div>
                                        </div>
                                        <div>
                                            <lable
                                                class="name"><?php echo __('Ngày sinh: '); ?></lable><?php echo $user['User']['date_of_birth']; ?>
                                            <div style="clear: both"></div>
                                        </div>
                                        <div>
                                            <lable
                                                class="name"><?php echo __('Số CMT: '); ?></lable><?php echo $user['User']['id_number']; ?>
                                            <div style="clear: both"></div>
                                        </div>
                                        <div>
                                            <lable
                                                class="name"><?php echo __('Giới tính: '); ?></lable><?php if ($user['User']['gender'] == 1) {
                                                echo 'Nam';
                                            } else {
                                                echo 'Nữ';
                                            }; ?>
                                            <div style="clear: both"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal">Ok</button>
                            </div>
                        </div>
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
   <?php echo $this->element('footer');?>
</footer>

<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
<script type="text/javascript">
    var published = {
        toggle : function(id, url){
            obj = $('#'+id).parent();
            $.ajax({
                url: url,
                type: "POST",
                success: function(response){
                    obj.html(response);
                    window.location.reload();
                }
            });
        }
    };

    function Pagination() {
        location.href = "<?php echo Router::url(array('plugin' => 'acl_management', 'controller' => 'users', 'action'=>'index')); ?>/" + $('#pagination').val() + "?<?php echo $_SERVER['QUERY_STRING'];  ?>";
    }
    $(document).ready(function () {
        $('#search').click(function(){
            $('#form_search').show();
        });
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
                    $.post("<?php echo Router::url(array('plugin' => 'acl_management', 'controller' => 'users', 'action'=>'multi_del')); ?>", { 'items[]': values })
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
