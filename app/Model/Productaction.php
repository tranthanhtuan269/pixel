<?php
/**
 * Created by PhpStorm.
 * User: MSc. Hoang Dung
 * Date: 12/4/14
 * Time: 9:01 PM
 */
class Productaction extends AppModel{
    public $useTable = 'product_actions';

    public $belongsTo = array(
      'Action' => array(
            'className' => 'Action',
            'foreignKey' => 'action_id',
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
        ),
        'Product' => array(
            'className' => 'Product',
            'foreignKey' => 'Product_id'
        ),
        'Note' => array(
            'className' => 'Note',
            'foreignKey' => 'Note_id'
        ),
        'Processtype' => array(
            'className' => 'Processtype',
            'foreignKey' => 'process_type_id'
        ),
        'Extension' => array(
            'className' => 'Productextension',
            'foreignKey' => 'product_extension_id'
        )
    );
}