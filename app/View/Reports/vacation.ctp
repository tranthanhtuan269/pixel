<?php
$objReader = new PHPExcel_Reader_Excel5();
if ($flag == false) {
    $objPHPExcel = $objReader->load(WWW_ROOT . 'ExcelSource'.DS."Resign_Staff_year.xls");
} else {
    $objPHPExcel = $objReader->load(WWW_ROOT .  'ExcelSource'.DS."Ngaynghi_nam.xls");
}
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
$objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");

$arrchar = array("A", "B", 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

$objPHPExcel->setActiveSheetIndex(0);
if ($flag == false) {
    $objPHPExcel->getActiveSheet()->setTitle('Báo cáo ngày nghỉ nhân viên');

    $row = 7;
    $objPHPExcel->getActiveSheet()->SetCellValue('B' . 3, date('d/m/Y g:i a'));
    $objPHPExcel->getActiveSheet()->SetCellValue('B' . 4, $nameNV);
    if (isset($ngaynghi)) {
        $i = 1;
        foreach ($ngaynghi as $lst) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $i);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $lst[0]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $lst[1]);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $lst[2]);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $row, $lst[3]);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $row, $lst[4]);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $row, $lst[5]);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $row, $lst[6]);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $row, $lst[7]);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $row, $lst[8]);
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $row, $lst[9]);
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . $row, $lst[10]);
            $objPHPExcel->getActiveSheet()->SetCellValue('M' . $row, $lst[11]);
            $objPHPExcel->getActiveSheet()->SetCellValue('N' . $row, $lst[12]);
            $objPHPExcel->getActiveSheet()->SetCellValue('O' . $row, "=sum(C" . $row . ":N" . $row . ")");
            $i++;
            $row++;

        }
    } else {
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, 1);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, 0);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, 0);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, 0);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $row, 0);
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $row, 0);
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $row, 0);
        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $row, 0);
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $row, 0);
        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $row, 0);
        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $row,0);
        $objPHPExcel->getActiveSheet()->SetCellValue('L' . $row, 0);
        $objPHPExcel->getActiveSheet()->SetCellValue('M' . $row,0);
        $objPHPExcel->getActiveSheet()->SetCellValue('N' . $row,0);
    }


////////////////////////////////////////////////////
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    $ob_content = ob_get_contents();
    ob_end_clean();

    $name = "Bao Cao Ngay Nghi_".$nameNV. '_' . date('Ymdhis') . '.xls';
}
if ($flag == true) {
    $objPHPExcel->getActiveSheet()->setTitle('Báo cáo ngày nghỉ trong năm');
    $row = 4;
//    $objPHPExcel->getActiveSheet()->SetCellValue('B' . 3, date('d/m/Y g:i a'));
//    $objPHPExcel->getActiveSheet()->SetCellValue('B' . 4, $name);
    if (isset($ngaynghi)) {
        $i = 1;
        foreach ($ngaynghi as $lst) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $row, $i);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $row, $lst[0]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $row, $lst[2]);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $row, $lst[1]);
            $i++;
            $row++;

        }
    }


////////////////////////////////////////////////////
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    $ob_content = ob_get_contents();
    ob_end_clean();
    $name = "Bao Cao Ngay Nghi_" . '_' . date('Ymdhis') . '.xls';
}
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $name . '"');
header('Cache-Control: max-age=0');
echo trim($ob_content);
exit;
