<?php
if ($data_project != null) {
    $stt = 1;
    foreach ($data_project as $files) {
        ?>
        <tr class="table-flag">
            <td class="text-center"><input value="<?php echo $stt; ?>" type="checkbox" name="ck[]"/>
            </td>
            <td> <?php echo $files['name']; ?></td>
            <td> <?php echo $com['Com']['name']; ?></td>
            <td class="text-center"> <?php echo $files['file']; ?> </td>
            <td class="text-center"> <?php echo $date_create; ?></td>
            <td>  <?php echo $customer_group['Customergroup']['sharing_note'];
                echo $this->Form->input('sharing_note', array('type' => 'hidden', 'value' => $customer_group['Customergroup']['sharing_note'], 'div' => false, 'label' => false));
                echo $this->Form->input('doing_note', array('type' => 'hidden', 'value' => $customer_group['Customergroup']['doing_note'], 'div' => false, 'label' => false));
                echo $this->Form->input('init_note', array('type' => 'hidden', 'value' => $customer_group['Customergroup']['init_note'], 'div' => false, 'label' => false));
                echo $this->Form->input('customer_group_id', array('type' => 'hidden', 'value' => $customer_group['Customergroup']['id'], 'div' => false, 'label' => false));
                echo $this->Form->input('time', array('type' => 'hidden', 'value' => isset($com) ? (($com['Com']['time'])*$files['file'] ) : 0, 'div' => false, 'label' => false));
                ?> </td>
            <td>  <?php echo $customer_group['Customergroup']['doing_note']; ?> </td>
            <td class="text-center"> <?php
                echo str_replace('/','\\',$link . '/' . $files['name']);
                echo $this->Form->input('Url'.$stt, array('type' => 'hidden', 'value' => str_replace('/','\\',$link . '/' . $files['name']), 'div' => false, 'label' => false));
                ?></td>
        </tr>
        <?php
        $stt++;
    }
}else{
    echo '<div style="width: 150px;"><b>Không tồn tại dự án!</b></div>';
}
?>