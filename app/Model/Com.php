<?php
/**
 * Created by PhpStorm.
 * User: MSc. Hoang Dung
 * Date: 10/22/14
 * Time: 3:32 PM
 */
class Com extends AppModel{
    public $belongsTo = array(
        'GroupCom' => array(
            'className' => 'GroupCom',
            'foreignKey' => 'group_com_id'
        )
    );
}