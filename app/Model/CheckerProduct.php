<?php
/**
 * Created by PhpStorm.
 * User: MSc. Hoang Dung
 * Date: 12/4/14
 * Time: 9:01 PM
 */
class CheckerProduct extends AppModel{

    public $useTable = 'checker_products';

    public $belongsTo = array(
        'Checker' => array(
            'className' => 'User',
            'foreignKey' => 'checker_id',
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
        ),'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
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
        ),'Deliver' => array(
            'className' => 'User',
            'foreignKey' => 'deliver_id',
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
            'foreignKey' => 'products',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ),'Project' => array(
            'className' => 'Project',
            'foreignKey' => 'Project_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        )
    );
}