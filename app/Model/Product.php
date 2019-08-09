<?php

/**
 * Created by PhpStorm.
 * User: MSc. Hoang Dung
 * Date: 10/22/14
 * Time: 3:32 PM
 */
class Product extends AppModel
{

    public $name = 'Product';
    public $primaryKey = 'id';
    public $actsAs = array('Containable');
//    public $hasOne = array(
//        'ProductReturn' => array(
//            'className' => 'ProductReturn',
//            'dependent' => true
//        )
//    );
    public $hasMany = array(
        'Productaction' => array(
            'className' => 'Productaction',
            'foreignKey' => 'action_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ),
        'Working' => array(
            'className' => 'Working',
            'foreignKey' => 'product_id',
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
    ,'CheckerProduct' => array(
            'className' => 'CheckerProduct',
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
        )
    ,'Reject' => array(
            'className' => 'Reject',
            'foreignKey' => 'product_id',
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

    public $belongsTo = array(
        'Processtype' => array(
            'className' => 'Processtype',
            'foreignKey' => 'process_type_id'
        ),
        'Productcategory' => array(
            'className' => 'Productcategory',
            'foreignKey' => 'product_category_id'
        ),
        'Producttype' => array(
            'className' => 'Producttype',
            'foreignKey' => 'product_type_id'
        ),
        'Project' => array(
            'className' => 'Project',
            'foreignKey' => 'project_id'
        ),
        'Deliver' => array(
            'className' => 'User',
            'foreignKey' => 'deliver_user_id'
        ),
        'Performer' => array(
            'className' => 'User',
            'foreignKey' => 'perform_user_id'
        ),
        'Productextension' => array(
            'className' => 'Productextension',
            'foreignKey' => 'product_extension_id'
        ),
        'Processtype' => array(
            'className' => 'Processtype',
            'foreignKey' => 'process_type_id'
        )
    );


    const STATUS_DA_CHIA = 1;
    
    const STATUS_DANG_REJECT = 100;
}