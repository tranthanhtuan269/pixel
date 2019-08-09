<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel3"><?php echo __('Bảng chi tiết chấm công của nhân viên trong tháng '.$month); ?></h3>
</div>
<div class="modal-body">
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="info-user">
                    <div>Tên nhân viên: <?php echo $name;?></div>
                    <div>Phòng ban: <?php echo $department;?></div>
                </div>
                <br>
                 <table class="table table-advance" id="table1">
                    <thead>
                    <tr>
                        <th style="width:18px"><?php echo __('STT'); ?></th>
                        <th class="text-center"><?php echo __('Ngày làm'); ?></th>
                        <th style="width:100px;" class="text-center"><?php echo __('Giờ đăng nhập'); ?></th>
                        <th style="width:100px;"><?php echo __('Giờ đăng xuất'); ?></th>
                        <th style="width:100px"><?php echo __('Thời gian làm '); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $stt = 1;
                    foreach ($new_list as $item) {
                        ?>
                        <tr class="table-flag">
                            <td class="text-center"><?php echo $stt; ?></td>
                            <td class="text-center"><?php echo $item['date']?></td>
                            <td class="text-center"><?php echo $item['time_login']?></td>
                            <td class="text-center"><?php echo ($item['time_logout'] != '')?$item['time_logout']:'Chưa đăng xuất';?></td>
                            <td class="text-center"><?php echo round($item['time_work']/3600,0).' giờ '.round(($item['time_work']-(round($item['time_work']/3600,0)*3600))/60,0).' phút'; ?></td>
                        </tr>
                        <?php
                        $stt++;
                    }
                    ?>
                    <tr class="table-flag">
                        <td class="text-center"></td>
                        <td class="text-center"><?php echo __('<b>Tổng thời gian</b>')?></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"><?php echo $h.' giờ '.$i.' phút';?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" data-dismiss="modal">Ok</button>
</div>