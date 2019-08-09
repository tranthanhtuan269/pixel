<?php
$objReader = new PHPExcel_Reader_Excel5();
$objPHPExcel = $objReader->load(WWW_ROOT . 'ExcelSource' . DS . "Collection_Staff_year.xls");
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
$objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");
$arrchar = array("A", "B", 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Thống kê báo cáo');
/////////////BODY/////////////////////////

////////////////////////////////////Add content//////////////////////////////////////////////
/////////////BODY/////////////////////////
//debug($employ);die;

$objPHPExcel->getActiveSheet()->SetCellValue('B' . 5, $currentUser['User']['name']);
$objPHPExcel->getActiveSheet()->SetCellValue('B' . 6, date('d/m/Y g:i a'));
$objPHPExcel->getActiveSheet()->SetCellValue('B' . 7, $currentUser['User']['date_of_birth']);
$objPHPExcel->getActiveSheet()->SetCellValue('B' . 8, $currentUser['User']['address']);
$objPHPExcel->getActiveSheet()->SetCellValue('B' . 9, $currentUser['User']['start_work_day']);
if (isset($employ)) {

    $row = 11;
    $ceil = 2;
    foreach ($employ['FILE'] as $item) {
        $objPHPExcel->getActiveSheet()->SetCellValue($arrchar[$ceil] . $row, $item);
        $ceil++;
    }
    $row++;
    $ceil = 2;
    foreach ($employ['TIME'] as $item1) {
        $objPHPExcel->getActiveSheet()->SetCellValue($arrchar[$ceil] . $row, $item1);
        $ceil++;
    }
}


////////////////////////////////////////////////////
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
$ob_content = ob_get_contents();
ob_end_clean();
$name = "BaoCao_NhanVienNam_" . '_' . date('Ymdhis') . '.xls';
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $name . '"');
header('Cache-Control: max-age=0');
echo trim($ob_content);
exit;
?>