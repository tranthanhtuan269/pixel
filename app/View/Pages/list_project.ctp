<?php if ($list_project == false) {
    echo '<b>Không dự án nào có trạng thái bạn chọn!</b>';
} else {
    foreach ($list_project as $item) {
        ?>
        <li>
            <p>
                <i class="icon-minus light-green"></i>
                <?php echo $item['Project']['Name'] ?>
            </p>
        </li>
    <?php
    }
} ?>