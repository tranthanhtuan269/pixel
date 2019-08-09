<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiemtd
 * Date: 5/15/15
 * Time: 10:45 PM
 * To change this template use File | Settings | File Templates.
 */
class Reject extends AppModel{
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id_reject'
        ),
        'Ratepoint' => array(
            'className' => 'Ratepoint',
            'foreignKey' => 'rate_point_id'
        ),
        'Project' => array(
            'className' => 'Project',
            'foreignKey' => 'project_id'
        ),
        'Action' => array(
            'className' => 'Action',
            'foreignKey' => 'action_id'
        ),
        'Product' => array(
            'className' => 'Product',
            'foreignKey' => 'product_id'
        ),
    );
}