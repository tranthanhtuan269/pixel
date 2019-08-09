<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiemtd
 * Date: 3/20/15
 * Time: 10:36 PM
 * To change this template use File | Settings | File Templates.
 */
class ProductReturn extends AppModel{
    public $belongsTo = array(
        'Product' => array(
            'className' => 'Product',
            'foreignKey' => 'Product_id'
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
        )
    );
    
    const STATUS_ACCEPTED = 0;
    const STATUS_REJECTED = 1;
    const STATUS_WANT_RETURN = 2;
}