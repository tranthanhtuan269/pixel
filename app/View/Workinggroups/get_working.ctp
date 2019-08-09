<div class="span12">
    <?php
    echo $this->Form->input('working_group_id', array(
            'type' => 'select',
            'empty' => '--Chọn nhóm làm việc--',
            'options'=>$working,
            'div' => false,
            'label' => false
        )
    );

    ?>
</div>