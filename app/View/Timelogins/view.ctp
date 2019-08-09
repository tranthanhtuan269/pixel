<?php
echo $this->element('top_page', array(
    'page_title' => 'Danh sách chấm công',
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
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><b><?php echo __('Danh sách chấm công '); ?></b></a>
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
                        <h3><i class="icon-search"></i> Tìm kiếm</h3>

                        <div class="box-tool">
                            <a href="#" data-action="collapse"><i class="icon-chevron-down"></i></a>
                        </div>
                    </div>
                    <div class="box-content" style="display: none;">
                        <?php echo $this->Form->create('Timelogin', array('action' => 'view')); ?>
                        <div class="row-fluid">
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label" for="name"><?php echo __('Ngày chấm công'); ?>
                                        :</label>

                                    <div class="controls">
                                        <div>
                                            <?php echo $this->Form->input('date_work', array('id' => 'datePick', 'type' => 'text', 'class' => 'datepicker', 'label' => false, 'div' => false)) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div style="float: left; margin-right: 20px" class="control-group">
                                    <label class="control-label" for="name"><?php echo __('Tháng'); ?>:</label>
                                    <?php echo $this->Form->input('month', array('placeholder' => '', 'class' => 'input-small', 'label' => false)) ?>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="name"><?php echo __('Năm'); ?>:</label>
                                    <?php echo $this->Form->input('year', array('placeholder' => '', 'class' => 'input-small', 'label' => false, 'div' => false)) ?>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Phòng ban:'); ?></label>

                                    <div class="controls">
                                        <?php
                                        echo $this->Form->input('department_id', array(
                                            'type' => 'select',
                                            'label' => false,
                                            'class' => 'select2-choice select2-default',
                                            'options' => $department,
                                            'empty' => 'Chọn phòng ban'
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions clearfix text-center">
                            <input type="submit" class="btn btn-primary " value="Tìm kiếm">
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
            <div style="clear:both;"></div>
            <table class="table table-advance" id="table1">
                <thead>
                <tr>
                    <th style="width:18px"><?php echo __('STT'); ?></th>
                    <th class="text-center"><?php echo __('Tên nhân viên'); ?></th>
                    <th class="text-center"><?php echo __('Tên đăng nhập'); ?></th>
                    <th class="text-center"><?php echo __('Phòng ban'); ?></th>
                    <th class="text-center"><?php echo __('Thời gian làm'); ?></th>
                    <th class="text-center" style="width:125px"><?php echo __('Ngày nghỉ<br>(Không phép/Phép)'); ?></th>
                    <th class="text-center" style="width:100px"><?php echo __('Tháng/Năm'); ?></th>
                    <th  class="text-center" style="width:100px"><?php echo __('Công'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $stt = 1;
                foreach ($new_list as $key => $item):
                    $user_info = $this->requestAction(Router::url(array('controller' => 'timelogins', 'action' => 'get_user', $item['department_id'])));
                    ?>
                    <tr class="table-flag">
                        <td class="text-center"><?php echo $stt; ?></td>
                        <td class="text-center"><a data-toggle="modal" role="button" href="#modal" data-department="<?php echo $user_info['Department']['name'];?>"  data="<?php echo $key; ?>" data-name="<?php echo $item['name']; ?>" data-month=" <?php echo $month;?>"
                                                   class="user"><?php echo $item['name']; ?></a></td>
                        <td class="text-center"><?php echo $item['username']; ?></td>
                        <td class="text-center"><?php echo $user_info['Department']['name']; ?></td>
                        <td class="text-center">
                            <?php echo round($item['time']/3600,0).' giờ '.round(($item['time']-(round($item['time']/3600,0)*3600))/60,0).' phút'; ?>
                        </td>
                        <td class="text-center">
                            <?php echo $vacation = $this->requestAction(Router::url(array('controller'=>'timelogins','action'=>'get_vacation',$key,$month,$year)));
                            ?>
                        </td>
                        <td class="text-center">
                        <?php echo $month.'/'.$year;?>
                        </td>
                        <td class="text-center">
                            <?php echo round($item['time']/(60*$time_day),1); ?>
                        </td>
                    </tr>
                    <div id="modal" class="modal hide fade" tabindex="-1"
                         role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">

                    </div>
                    <?php $stt++; endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<footer>
    <?php
    echo $this->element('footer');
    ?>
</footer>
</div>
<script>
    $(document).ready(function () {
        $('.user').click(function () {
            $('.fixed_bottom').show();
            var user_id = $(this).attr('data');
            var name = $(this).attr('data-name');
            var month = $(this).attr('data-month');
            var department = $(this).attr('data-department');
            $.post("<?php echo Router::url(array('controller'=>'timelogins','action' => 'user_time')); ?>", { 'user_id': user_id, name: name,month:month,department:department  })
                .done(function (data) {
                    if (data) {
                        $('#modal').html(data);
                        $('.fixed_bottom').hide();
                    } else {
                        alert('Không thể lưu!')
                    }
                });
        });
    });
</script>
<!-- END Main Content -->
<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>