<?php
/**
 * Created by PhpStorm.
 * User: MSc. Hoang Dung
 * Date: 11/10/14
 * Time: 2:49 PM
 */
class Timelogin extends AppModel{
    public $useTable = 'time_logins';
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );
}