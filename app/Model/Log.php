<?php
/**
 * Created by PhpStorm.
 * User: MSc. Hoang Dung
 * Date: 10/22/14
 * Time: 3:32 PM
 */
class Log extends AppModel{

    public $name = 'Log';
    public $primaryKey = 'ID';
    public $actsAs = array('Containable');

    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'User_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterScope' => '',
            'counterQuery' => '',
            'counterCache' => '',
        )
    );
}