<?php
$stt = 0;
foreach ($userpoints as $k => $userpoint) {
    ?>
    <tr>
        <td><input type="checkbox"
                   name="user[<?php echo $stt; ?>]"
                   value="<?php echo $userpoint['User']['id']; ?>"/></td>
        <td class="text-center"><?php echo $userpoint['User']['name'] ?></td>
        <td class="text-center"><?php echo $userpoint['Action']['Name'] ?></td>
        <td class="text-center"><?php
            $percent = array('-1' => '-10', '-2' => '-20', '-3' => '-30', '-4' => '-40', '-5' => '-50', '-6' => '-60', '-7' => '-70', '-8' => '-80', '-9' => '-90', '-10' => '-100', '1' => '10', '2' => '20', '3' => '30', '4' => '40', '5' => '50', '6' => '60', '7' => '70', '8' => '80', '9' => '90', '10' => '100');
            echo $this->Form->input('percent', array(
                    'type' => 'select',
                    'options' => $percent,
                    'name' => 'percent[' . $stt . ']',
                    'label' => false,
                    'class' => 'span1',
                    'empty' => '-Chọn-'
                )
            );
            ?></td>
        <td class="text-center">
            <?php
            if(count($new_reject) > 0){
                foreach ($new_reject as $key => $item) {
                    if ($key == $userpoint['User']['id']) {
                        echo count($item['percent']) . ' lần';
                        ?>
                        <?php foreach ($item['percent'] as $a => $j) { ?>
                            <div>Lần <?php echo ($a + 1) . ':  ' . ($j * 10).'% - '.$item['datetime'][$a]; ?></div>
                        <?php
                        }
                    }
                }
            }else{
                echo 'Chưa bị reject lần nào!';
            }
            ?>

        </td>
    </tr>
    <?php $stt++;
} ?>