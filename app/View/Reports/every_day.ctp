<?php
/**
 * Created by PhpStorm.
 * User: DungHM
 * Date: 10/9/15
 * Time: 01:39
 */

App::import('Vendor', 'PHPExcel');
$excel = new PHPExcel();
$file = 'template.xls';
$excel = PHPExcel_IOFactory::load($file);
$excel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Times New Roman');
$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);


$excel->getActiveSheet()->setCellValue('A2',"Danh sách VTVL");
$excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
$excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(15);
$excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->mergeCells("A2:D2");

//agregamos los datos
$i=1;
$begin = 4 ;
$excel->getActiveSheet()->getStyle('A'.$begin.':C'.$begin)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$excel->getActiveSheet()->setCellValue('A'.$begin, 'STT');
$excel->getActiveSheet()->getStyle('A'.$begin)->getFont()->setBold(true);
$excel->getActiveSheet()->getStyle('A'.$begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$begin)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$excel->getActiveSheet()->setCellValue('B'.$begin, 'Nhiệm vụ');
$excel->getActiveSheet()->getStyle('B'.$begin)->getFont()->setBold(true);
$excel->getActiveSheet()->getStyle('B'.$begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('B'.$begin)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$excel->getActiveSheet()->setCellValue('C'.$begin, 'Danh sách công việc');
$excel->getActiveSheet()->getStyle('C'.$begin)->getFont()->setBold(true);
$excel->getActiveSheet()->getStyle('C'.$begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('C'.$begin)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$begin++;

$objRichText = new PHPExcel_RichText();

//if (count($jobs) > 0) {
//    $row = 1;
//    foreach ($listmg as $list) {
//        $excel->getActiveSheet()->setCellValue('A'.$begin,$list['name']);
//        $excel->getActiveSheet()->mergeCells("A".$begin.":D".$begin);
//        $begin++;
//        foreach ($jobs as $ele) {
//            $cnt = count($ele['Job']);
//            $temp = 0;
//            if ($ele['Jobgroup']['misssiongroup_id'] == $list['id']) {
//                $end = ($begin + $cnt) -1;
//                foreach ($ele['Job'] as $item) {
//                    $temp++;
//                    if ($temp == 1) {
//                        $excel->getActiveSheet()->setCellValue('A'.$begin, $row++);
//                        $excel->getActiveSheet()->mergeCells("A".$begin.":A".$end);
//                        $excel->getActiveSheet()->setCellValue('B'.$begin,$ele['Jobgroup']['name']);
//                        $excel->getActiveSheet()->mergeCells("B".$begin.":B".$end);
//                    }
//                    $excel->getActiveSheet()->setCellValue('C'.$begin,$item['Job']['name']);
//                    $begin++;
//                }
//                $begin = $end+1;
//            }
//        }
//    }
//}

$excel->getActiveSheet()->setTitle('VTVL');
$excel->setActiveSheetIndex(0);
$filename = "out.xls";
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
ob_end_clean();
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');


$objWriter->save('php://output');
//

exit;