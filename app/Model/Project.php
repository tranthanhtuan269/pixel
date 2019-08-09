<?php
/**
 * Created by PhpStorm.
 * User: MSc. Hoang Dung
 * Date: 10/22/14
 * Time: 3:32 PM
 */
class Project extends AppModel{

    public $name = 'Project';
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
        ),'Status' => array(
            'className' => 'Status',
            'foreignKey' => 'Status_id',
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
        ),'Customer' => array(
            'className' => 'Customer',
            'foreignKey' => 'Customer_id',
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
        ),'CustomerGroup' => array(
            'className' => 'CustomerGroup',
            'foreignKey' => 'CustomerGroup_id',
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
        ),'ProjectType' => array(
            'className' => 'ProjectType',
            'foreignKey' => 'ProjectType_id',
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
        ),'ProcessType' => array(
            'className' => 'ProcessType',
            'foreignKey' => 'ProcessType_id',
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
        ),'ProductType' => array(
            'className' => 'ProductType',
            'foreignKey' => 'ProductType_id',
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
        ),'Productextension' => array(
            'className' => 'Productextension',
            'foreignKey' => 'product_extension_id',
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

    public $hasMany = array(
        'ProjectAction' => array(
            'className' => 'ProjectAction',
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
        ),'ProjectCom' => array(
            'className' => 'ProjectCom',
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
        ),'Product' => array(
            'className' => 'Product',
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
    
    public function __construct()
    {
        parent::__construct();
        if (strpos($_SERVER['REQUEST_URI'], "/reports/") !== false)
            $this->recursive = -1;
    }
}