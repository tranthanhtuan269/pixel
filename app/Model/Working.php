<?php
/**
 * Created by PhpStorm.
 * User: MSc. Hoang Dung
 * Date: 11/6/14
 * Time: 5:24 PM
 */
class Working extends AppModel{
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
        ),'Product' => array(
            'className' => 'Product',
            'foreignKey' => 'Product_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
            'status' => '',
        )

    );
}