<?php
/**
 * Created by PhpStorm.
 * User: MSc. Hoang Dung
 * Date: 10/22/14
 * Time: 3:32 PM
 */
class Processtype extends AppModel{
    public $useTable = 'process_types';
    public $belongsTo = array(
        'Processtypegroup' => array(
            'className' => 'Processtypegroup',
            'foreignKey' => 'process_type_group_id'
        )
    );
}