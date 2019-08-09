<?php
function _substr($str, $length, $minword = 1)
{
    $sub = '';
    $len = 0;
    foreach (explode(' ', $str) as $word) {
        $part = (($sub != '') ? ' ' : '') . $word;
        $sub .= $part;
        $len += strlen($part);
        if (strlen($word) > $minword && strlen($sub) >= $length) {
            break;
        }
    }
    return $sub . (($len < strlen($str)) ? '...' : '');
}

    foreach ($notifications as $item):
        ?>
        <tr class="table-flag">
            <td><input value="<?php echo $item['Notification']['id']; ?>" type="checkbox" name="ck[]"/></td>
            <td class="text">
                <?php
                foreach ($alerts as $name):
                    if ($name['Alert']['id'] == $item['Notification']['alert_id'])
                        echo $name['Alert']['name'];
                endforeach
                ?>
            </td>
            <td>
                <?php
                echo $this->Html->link(_substr($item['Notification']['title'], 35), array('action' => 'view', $item['Notification']['id']));
                ?>
            </td>
            <td class="text-center"><?php echo _substr($item['Notification']['content'], 35); ?></td>
            <td class="text-center"><?php echo $item['Notification']['createdate'] ?></td>
            <td>
                <div class="btn-group">
                    <a class="btn btn-small show-tooltip" title="Sửa"
                       href="<?php echo Router::url(array('controller' => 'coms', 'action' => 'edit')); ?>/<?php echo $item['Notification']['id']; ?>"><i
                            class="icon-edit"></i></a>
                    <?php
                    echo $this->Form->postLink(
                        $this->Html->tag('i', '', array('class' => 'icon-trash')),
                        array('action' => 'delete', $item['Notification']['id']),
                        array('class' => 'btn btn-small btn-danger show-tooltip', 'title' => 'Xóa', 'escape' => false, 'confirm' => __('Are you sure?'))
                    );
                    ?>
                </div>
            </td>
        </tr>
    <?php endforeach;
?>