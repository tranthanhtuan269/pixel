<?php
if($export==1){
    if($this->Session->check('dataexport')){
        $dataexport = $this->Session->read('dataexport');
    }else{
        echo 0;die;
    }
//    pr($dataexport);die;
$objReader = new PHPExcel_Reader_Excel5();
$objPHPExcel = $objReader->load(WWW_ROOT."export-excel.xls");
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
$objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");
// Add some data

$arrchar = array("A","B",'C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Báo cáo công việc');
/////////////BODY/////////////////////////
    $row = 3;
    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$row, $dataexport['project']['Project']['Name'] );
    $row++;
    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$row, $dataexport['project']['Project']['Name'] );
    $row++;
    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$row, $dataexport['project']['User']['name'] );
    $row++;
    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$row, $dataexport['project']['User']['id'] );
    $row++;
    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$row, date("d/m/Y H:i:s",strtotime($dataexport['project']['Project']['InputDate'])));
    $row++;
    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$row, $current['name']);
    $row++;
    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$row, $dataexport['project']['ProcessType']['name']);
    $row++;
    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$row, $dataexport['project']['ProductType']['name']);

    $row = 14;$ceil = 0;$i=1;
    foreach($dataexport['products'] as $product){
        $ceil = 0;
        $objPHPExcel->getActiveSheet()->SetCellValue($arrchar[$ceil].$row, $i);
        $ceil++;
        $objPHPExcel->getActiveSheet()->SetCellValue($arrchar[$ceil].$row, $product['Product']['id'] );
        $ceil++;
        $objPHPExcel->getActiveSheet()->SetCellValue($arrchar[$ceil].$row, $product['Product']['name_file_product']);
        $ceil++;
        $ceil++;
        $objPHPExcel->getActiveSheet()->SetCellValue($arrchar[$ceil].$row,$product['Product']['note_file'] );
        $row++;$i++;
    }
////////////////////////////////////////////////////
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//    pr(str_replace('@', '\\', $dir.$dataexport['project']['Project']['UrlFolder'].'@').$username.'.xls');die;
$i=1;
do {
    $a = WWW_ROOT.$username.'_'.$i;
    $a .= '.xls';
    $i++;
}while(file_exists($a));

$objPHPExcel->getActiveSheet()->SetCellValue('E9', $i);
//    echo $a;die;
$objWriter->save($a);
    echo $a;
}else{
    echo 0;
}
?>