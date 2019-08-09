<?php
if ($products != null) {
    $stt = 0;
    foreach ($products as $product):
        $stt++;
    echo 'No: '.$stt;
    echo 'Date: '.$product['Project']['InputDate'];
    echo 'Time: '.$product['Project']['returnTime'];
    echo 'Total Time Process: '.$product['Project']['SpentTime'];
    echo 'Free Time: ';
    echo '<br>';

    endforeach;
} else {
    echo 'Không có báo cáo';
}