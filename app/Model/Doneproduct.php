<?php
class Doneproduct extends AppModel{
    public $useTable = 'done_products';
    public $belongsTo = array(
        'Product' => array(
            'className' => 'Product',
            'foreignKey' => 'product_id'
        )
    );
}