<div class="span6">
    <div class="span8">
    <div>Dung lượng hoạt động và đã chia</div>
       <div class="progress progress-striped active clearfix">
           <div class="bar show-tooltip"
                style="width: <?php echo ($totalsize['0']['TotalSize']) ? round(($sizework['0']['TotalSize'] / $totalsize['0']['TotalSize'] * 100), 2) : 0 ?>%;"
                data-original-title="<?php echo ($totalsize['0']['TotalSize'] > 0) ? ($sizework['0']['TotalSize'] . '/' . $totalsize['0']['TotalSize']) : 0 ?>"></div>
       </div>
       <div>Dung lượng hoàn tất xử lý</div>
       <div class="progress progress-striped active clearfix">
           <div class="bar show-tooltip"
                style="width: <?php echo $totalsize['0']['TotalSize'] ? round(($sizeprocess['0']['TotalSize'] / $totalsize['0']['TotalSize'] * 100), 2) : 0 ?>%;"
                data-original-title="<?php echo ($totalsize['0']['TotalSize'] > 0) ? ($sizeprocess['0']['TotalSize'] . '/' . $totalsize['0']['TotalSize']) : 0 ?>"></div>
       </div>
       <div>Dung lượng hoàn tất kiểm tra</div>
       <div class="progress progress-striped active clearfix">
           <div class="bar show-tooltip"
                style="width: <?php echo $totalsize['0']['TotalSize'] ? round(($sizedone['0']['TotalSize'] / $totalsize['0']['TotalSize'] * 100), 2) : 0 ?>%;"
                data-original-title="<?php echo ($totalsize['0']['TotalSize'] > 0) ? ($sizedone['0']['TotalSize'] . '/' . $totalsize['0']['TotalSize']) : 0 ?>"></div>
       </div>
   </div>
    <div class="span3">
        <div style="margin-bottom: 10px;margin-top: 15px" class="span12">
            <p>
            <?php echo round($sizework['0']['TotalSize']/(1024*1024),2)?> MB
            </p>
        </div>
        <div  style="margin-bottom: 10px;margin-top: 12px"  class="span12">
            <p>
                <?php echo round($sizeprocess['0']['TotalSize']/(1024*1024),2)?> MB
            </p>
        </div>
        <div  style="margin-bottom: 10px;margin-top: 10px"  class="span12">
            <p>
                <?php echo round($sizedone['0']['TotalSize']/(1024*1024),2)?> MB
            </p>
        </div>
    </div>
</div>
<div class="span6">
   <div class="span8">
       <div>Thời gian cần làm</div>
       <div class="progress progress-striped active clearfix">
           <div class="bar show-tooltip"
                style="width: <?php echo $worktimes['0']['TotalTime']/($worktimes['0']['TotalTime'] + $usertimes*3600) * 100 ?>%;" ></div>
       </div>
       <div>Thời gian làm việc</div>
       <div class="progress progress-striped active clearfix">
           <div class="bar show-tooltip"
                style="width: <?php echo round(($usertimes*3600 / ($worktimes['0']['TotalTime'] + $usertimes*3600)), 2) * 100 ?>%;" ></div>
       </div>
   </div>
    <div class="span3">
        <div style="margin-bottom: 10px;margin-top: 15px" class="span12">
            <p>
                <?php echo gmdate("H:i:s",$worktimes['0']['TotalTime']) ?> h
            </p>
        </div>
        <div  style="margin-bottom: 10px;margin-top: 12px"  class="span12">
            <p>
                <?php echo $usertimes.':00:00' ?> h
            </p>
        </div>
    </div>
</div>
<div style="clear: both"></div>