<?php
/**
 * Created by PhpStorm.
 * User: MSc. Hoang Dung
 * Date: 10/22/14
 * Time: 8:10 PM
 */
class Producttype extends AppModel{
    public $useTable = 'product_types';
    public $belongsTo = array(
        'Productcategory' => array(
            'className' => 'Productcategory',
            'foreignKey' => 'product_category_id'
        )
    );
}