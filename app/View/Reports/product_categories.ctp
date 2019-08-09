<?php
if($products != null){
foreach ($products as $product):
echo $product['Productcategory']['name'].'</br>';
echo $product['Product']['name_file_product'].'</br>';
echo $product['Product']['deliver_date'].'</br>';
echo $product['Product']['date_of_completion'].'</br>';

    endforeach;

} else {
    echo('<strong>Không có báo cáo</strong>');
}