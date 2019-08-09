<?php
/**
 * Created by PhpStorm.
 * User: MSc. Hoang Dung
 * Date: 10/22/14
 * Time: 3:32 PM
 */
class Vacation extends AppModel
{
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'Vacationtype' => array(
            'className' => 'Vacationtype',
            'foreignKey' => 'vacation_type_id'
        )
    );
}