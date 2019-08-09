<?php
App::import('Controller', 'Timelogins');
App::import('Controller', 'Projects');
App::import('Vendor', 'PHPExcel');

class ReportsController extends AppController
{
    public $uses = array('Department', 'Customergroup', 'Country', 'Processtype', 'Producttype', 'Vacation', 'Productcategory', 'Product', 'Status', 'Project', 'Customer', 'Working', 'User', 'Com', 'GroupCom', 'ProjectCom', 'CheckerProduct', 'Reject', 'Ratepoint', 'Pointtime', 'TimePoint', 'ProjectAction', 'Action');
    public $components = array('PhpExcel');
    public $helpers = array(
        'PHPExcel'
    );

    public function index()
    {
        ini_set('memory_limit', '-1');
        error_reporting(0);
        ini_set('display_errors', 0);


        $projects = new ProjectsController;
        $projects->SelectUsers();
        $this->set(compact('dp', 'departments'));

        $statuses = $this->Status->find('list', array('fields' => array('Status.ID', 'Status.Name')));
        $departments = $this->Department->find('list', array('fields' => array('Department.id', 'Department.name')));
        $countries = $this->Country->find('list', array('fields' => array('Country.id', 'Country.name')));
//        $processTypes = $this->Processtype->find('list', array('fields' => array('Processtype.id', 'Processtype.name')));
        $processTypes = $this->Processtype->find('list', array('fields' => array('Processtype.id', 'Processtype.name')));
        $productCategories = $this->Productcategory->find('list', array('fields' => array('Productcategory.id', 'Productcategory.name')));
        $GroupCom = $this->GroupCom->find('list', array('fields' => array('GroupCom.id', 'GroupCom.name')));

        $this->set(compact('GroupCom', 'departments', 'countries', 'projectTypes', 'users', 'customers', 'processTypes', 'customergroups', 'productCategories', 'statuses'));

    }

    public function everyDay()
    {
        ini_set('memory_limit', '-1');
        error_reporting(0);
        ini_set('display_errors', 0);


//        return true;
        $this->layout = false;

        $report_input = $this->request->data;

//        die(json_encode($report_input));
//        $nothing = '{"Report":{"Departments_id":"2","from_date":"01\/06\/2017","to_date":"11\/07\/2017","Country_id":"","Customer_id":"empty","CustomerGroup_id":"empty","ProcessType_id":""},"user_id":"8"}';

        $show_customer = '';
        $show_customer_code = '';
        $show_customer_group = '';
        $show_country = '';

        if (isset($report_input['CustomerGroup_id']) && $report_input['CustomerGroup_id']) {
            //Lấy theo nhóm khách hàng
            $conditions['Project.CustomerGroup_id'] = $report_input['CustomerGroup_id'];
            $this->Customergroup->recursive = 0;
            $cg = $this->Customergroup->find('first', array('conditions' => array('Customergroup.id' => $report_input['CustomerGroup_id'])));

            $this->Customer->recursive = 0;
            $cu = $this->Customer->find('first', array('conditions' => array('Customer.id' => $report_input['Customer_id'])));

            $this->Country->recursive = 0;
            $ct = $this->Country->find('first', array('conditions' => array('Country.id' => $report_input['Report']['Country_id'])));

            $show_customer = $cu['Customer']['name'];
            $show_customer_code = $cu['Customer']['customer_code'];
            $show_customer_group = $cg['Customergroup']['name'];
            $show_country = $ct['Country']['name'];


        } else if (isset($report_input['Customer_id']) && $report_input['Customer_id']) {
            //Lấy theo khách hàng
            $conditions['Project.Customer_id'] = $report_input['Customer_id'];

            $this->Customer->recursive = 0;
            $cu = $this->Customer->find('first', array('conditions' => array('Customer.id' => $report_input['Customer_id'])));

            $this->Country->recursive = 0;
            $ct = $this->Country->find('first', array('conditions' => array('Country.id' => $report_input['Report']['Country_id'])));

            $show_customer = $cu['Customer']['name'];
            $show_customer_code = $cu['Customer']['customer_code'];
            $show_country = $ct['Country']['name'];

        } else if (isset($report_input['Report']['Country_id']) && $report_input['Report']['Country_id']) {
            $this->Country->recursive = 0;
            $ct = $this->Country->find('first', array('conditions' => array('Country.id' => $report_input['Report']['Country_id'])));
            $show_country = $ct['Country']['name'];
            //Lấy theo quóc gia
            $customer = $this->Customer->find('list', array(
                'fields' => array('Customer.id'),
                'conditions' => array('Customer.Country_id' => $report_input['Report']['Country_id'])
            ));
            if (count($customer) > 0) {
                $conditions['Project.Customer_id'] = $customer;
            }
        }


        if (isset($report_input['user_id']) && $report_input['user_id'] && isset($report_input['Report']['from_date']) && $report_input['Report']['from_date'] && isset($report_input['Report']['to_date']) && $report_input['Report']['to_date']) {

            $fromdate = str_replace("/", "-", $report_input['Report']['from_date']);
            $this->set('from_date', $fromdate);

            $fromdate = new DateTime($fromdate . " 00:00:00");
            $show_fromdate = $fromdate->format('d-m-Y');
            $fromdate = $fromdate->format('Y-m-d H:i:s');
            $todate = str_replace("/", "-", $report_input['Report']['to_date']);
            $this->set('to_date', $todate);
            $todate = new DateTime($todate . " 23:59:59");
            $show_todate = $todate->format('d-m-Y');
            $todate = $todate->format('Y-m-d H:i:s');

            $conditions['Project.CompTime >='] = $fromdate;
            $conditions['Project.CompTime <='] = $todate;

            $project_ids = $this->Project->find('list', array(
                'recursive' => -1, //int
                'fields' => array('ID'),
                'conditions' => $conditions
            ));

//            debug($project_ids);die;

            $employee = $this->User->findById($this->request->data['user_id']);
            $dep = $this->Department->findById($employee['User']['department_id']);

            $filename = "StaffDailyReport_" . $show_country . "_" . $show_customer . "_" . $show_customer_group . "_" . $show_fromdate . "_" . $show_todate . "_" . $employee['User']['username'] . ".xlsx";
            ob_end_clean();
            if (!isset($_GET['debug'])) {

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header("Content-Transfer-Encoding: binary ");
                header('Cache-Control: max-age=0');
            }

            $file = 'ExcelSource/V5_StaffDailyReport.xlsx';
            $excel = $this->PhpExcel->loadWorksheet($file);

            $excel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Times New Roman');
            $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(25);

            $excel->getActiveSheet()->setCellValue('B3', $employee['User']['username']);
            $excel->getActiveSheet()->setCellValue('B4', $dep['Department']['name']);

            //Lấy bảng actions để tạo mảng thang điểm cho từng loại action
            $action_points = $this->Action->find('all', array(
                'recursive' => -1, //int
            ));
            $nghiepvuArr = array();
            foreach ($action_points as $a_point) {
                $nghiepvuArr[$a_point['Action']['ID']] = $a_point['Action']['Point'];
            }

            //Danh mục loại xử lý
            $processtypes = $this->Processtype->find('all', array(
                'recursive' => -1, //int
            ));
            $processArr = array();

            foreach ($processtypes as $pt) {
                $processArr[$pt['Processtype']['id']]['point'] = $pt['Processtype']['point'];
                $processArr[$pt['Processtype']['id']]['number'] = $pt['Processtype']['number']; //Hệ số
                $processArr[$pt['Processtype']['id']]['timecheck'] = $pt['Processtype']['time_checkbox']; //Tính theo thời gian

            }

            //Danh mục thang điểm thời gian
            $timepoints = $this->TimePoint->find('all', array(
                'recursive' => -1, //int
                'order' => array('TimePoint.time DESC'),
            ));

            $begin_row = 7;

            $this->Product->Behaviors->load('Containable');
            $products = $this->Product->find('all', array(
                'conditions' => array(
                    'OR' => array(
                        'Product.perform_user_id' => $report_input['user_id'],
                        'Product.deliver_user_id' => $report_input['user_id'],
                    ),

                    'Product.done_round' => 2,
                    'Project.ID' => $project_ids
                ),
                'contain' => array(
                    'Project' => array(
                        'fields' => array('CompTime', 'Customer_id', 'Name'),
                        'Customer' => array(
                            'Country'
                        ),

                    ),
                    'Processtype',
                    'Working',
                    'Reject' => array(
                        'fields' => array('product_id', 'rate_point_id', 'percent', 'action_id'),
                        'Ratepoint'
                    )
                )
            ));

            //Những sản phẩm đã làm
            $total_time_process = 0;
            $total_process_files = 0;


            $total_err = 0;
            $total_point_err = 0;
            //Số lượng file và thời gian theo từng loại xử lý
            $count_process = array();
            foreach ($products as $product) {

                if ($product['Product']['perform_user_id'] == $report_input['user_id']) {

                    $begin_row++;
                    $total_process_files++;

                    $excel->getActiveSheet()->setCellValue('A' . $begin_row, $product['Product']['date_of_completion']);
                    $excel->getActiveSheet()->setCellValue('B' . $begin_row, $product['Project']['Customer']['Country']['name']);
                    $excel->getActiveSheet()->setCellValue('C' . $begin_row, $product['Project']['Customer']['name']);
                    $excel->getActiveSheet()->setCellValue('D' . $begin_row, $begin_row - 7);
                    $excel->getActiveSheet()->setCellValue('E' . $begin_row, $product['Project']['Name']);
                    $excel->getActiveSheet()->setCellValue('F' . $begin_row, $product['Product']['name_file_product']);
                    $excel->getActiveSheet()->setCellValue('G' . $begin_row, $product['Processtype']['name']);
                    $excel->getActiveSheet()->setCellValue('H' . $begin_row, 1);
                    $excel->getActiveSheet()->setCellValue('I' . $begin_row, $this->show_time_from_second($product['Working'][0]['process_time']));
                    $total_time_process += $product['Working'][0]['process_time'];

                    $count_process[$product['Product']['process_type_id']]['name'] = $product['Processtype']['name'];

                    if (!isset($count_process[$product['Product']['process_type_id']]['number'])) {
                        $count_process[$product['Product']['process_type_id']]['number'] = 0;
                    }

                    $count_process[$product['Product']['process_type_id']]['number'] += 1;

                    if (!isset($count_process[$product['Product']['process_type_id']]['time'])) {
                        $count_process[$product['Product']['process_type_id']]['time'] = 0;
                    }

                    $count_process[$product['Product']['process_type_id']]['time'] += $product['Working'][0]['process_time'];


                    ////xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx//////////
                    //Tính điểm xử lý của từng file
                    //$total_process_points = $this->get_process_point($product, $processArr, $timepoints);
                    $total_process_points = $this->getPointByProductAndAction($product['Product']['id']);

                    /////////////
                    $excel->getActiveSheet()->setCellValue('J' . $begin_row, $total_process_points);

                    $company_reject_point = 0;
                    $custom_reject_point = 0;
                    $extreme_reject_point = 0;
                    $company_reject_percent = "";
                    $custom_reject_percent = "";
                    $extreme_reject_percent = "";

                    if (isset($product['Reject']) && count($product['Reject'])) {
                        foreach ($product['Reject'] as $rej) {
                            //Chỉ lấy các reject làm hàng có Action_id = 100
                            if ($rej['action_id'] == 100) {
                                if ($rej['rate_point_id'] == 1) {
                                    $company_reject_percent .= ($rej['percent'] * 10) . "% ";
                                    $company_reject_point += $rej['Ratepoint']['value'] * $rej['percent'] * 0.1 * $total_process_points;
                                } elseif ($rej['rate_point_id'] == 2) {
                                    $custom_reject_percent .= ($rej['percent'] * 10) . "% ";
                                    $custom_reject_point += $rej['Ratepoint']['value'] * $rej['percent'] * 0.1 * $total_process_points;
                                } else {
                                    $extreme_reject_percent .= ($rej['percent'] * 10) . "% ";
                                    $extreme_reject_point += $rej['Ratepoint']['value'] * $rej['percent'] * 0.1 * $total_process_points;
                                }
                                $total_err++;
                                $total_point_err += $rej['Ratepoint']['value'] * $rej['percent'] * 0.1 * $total_process_points;
                            }
                        }
                    }

                    if ($company_reject_point != 0) {
                        $excel->getActiveSheet()->setCellValue('K' . $begin_row, $company_reject_percent);
                        $excel->getActiveSheet()->setCellValue('L' . $begin_row, $company_reject_point);
                        $excel->getActiveSheet()->getStyle('L' . $begin_row)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'FF0000')
                                )
                            )
                        );
                    }

                    if ($custom_reject_point != 0) {
                        $excel->getActiveSheet()->setCellValue('M' . $begin_row, $custom_reject_percent);
                        $excel->getActiveSheet()->setCellValue('N' . $begin_row, $custom_reject_point);
                        $excel->getActiveSheet()->getStyle('L' . $begin_row)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'FF0000')
                                )
                            )
                        );
                    }
                    if ($extreme_reject_point != 0) {
                        $excel->getActiveSheet()->setCellValue('O' . $begin_row, $extreme_reject_percent);
                        $excel->getActiveSheet()->setCellValue('P' . $begin_row, $extreme_reject_point);
                        $excel->getActiveSheet()->getStyle('M' . $begin_row)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'FF0000')
                                )
                            )
                        );
                    }

                    $excel->getActiveSheet()->setCellValue('Q' . $begin_row, "=(J" . $begin_row . "+L" . $begin_row . "+N" . $begin_row . "+P" . $begin_row . ")");
                }
            }

//            debug($products);die;
            $pos_rej = array(1 => 'L', 2 => 'N', 3 => 'P');
            $pos_rej_percent = array(1 => 'K', 2 => 'M', 3 => 'O');

            //Danh sách chia hàng
            foreach ($products as $product) {
                if ($product['Product']['deliver_user_id'] == $report_input['user_id']) {
                    $begin_row++;
                    $excel->getActiveSheet()->setCellValue('A' . $begin_row, $product['Product']['deliver_date']);
                    $excel->getActiveSheet()->setCellValue('B' . $begin_row, $product['Project']['Customer']['Country']['name']);
                    $excel->getActiveSheet()->setCellValue('C' . $begin_row, $product['Project']['Customer']['name']);
                    $excel->getActiveSheet()->setCellValue('D' . $begin_row, $begin_row - 7);
                    $excel->getActiveSheet()->setCellValue('E' . $begin_row, $product['Project']['Name']);
                    $excel->getActiveSheet()->setCellValue('F' . $begin_row, $product['Product']['name_file_product']);
                    $excel->getActiveSheet()->setCellValue('G' . $begin_row, "Chia hàng");
                    $excel->getActiveSheet()->setCellValue('H' . $begin_row, 1);
                    //$excel->getActiveSheet()->setCellValue('I' . $begin_row, $this->show_time_from_second($product['Working'][0]['process_time']));
                    $excel->getActiveSheet()->setCellValue('J' . $begin_row, $this->getPointByProductAndAction($product['Product']['id'], 3));

                    //Lấy toàn bộ các reject liên quan đến dự án của nghiệp vụ này
                    $reject_NVs = $this->Reject->find('all', array(
                        'conditions' => array(
                            'product_id' => $product['Product']['id'],
                            'action_id' => 3
                        ),
                        'contain' => array(
                            'Ratepoint'
                        )

                    ));
                    $rejVal = 0;
                    $rejPer = "";
                    foreach ($reject_NVs as $projRej) {
                        $total_err++;
                        $rejVal += $projRej['Reject']['percent'] * 0.1 * $projRej['Ratepoint']['value'] * $nghiepvuArr[3];
                        $rejPer .= ($projRej['Reject']['percent'] * 10) . "% ";
                        $excel->getActiveSheet()->setCellValue($pos_rej_percent[$projRej['Reject']['rate_point_id']] . $begin_row, $rejPer);
                        $excel->getActiveSheet()->setCellValue($pos_rej[$projRej['Reject']['rate_point_id']] . $begin_row, $rejVal);
                        if ($rejVal) {
                            $excel->getActiveSheet()->getStyle($pos_rej[$projRej['Reject']['rate_point_id']] . $begin_row)->applyFromArray(
                                array(
                                    'fill' => array(
                                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                        'color' => array('rgb' => 'FF0000')
                                    )
                                )
                            );
                        }
                    }
                    $excel->getActiveSheet()->setCellValue('Q' . $begin_row, "=(J" . $begin_row . "+L" . $begin_row . "+N" . $begin_row . "+P" . $begin_row . ")");
                }
            }


            //Danh sách check hang
            $this->CheckerProduct->Behaviors->load('Containable');
            $checkingFiles = $this->CheckerProduct->find('all', array(
                'contain' => array(
                    'Product' => array(
                        'fields' => array('name_file_product'),
                        'Processtype',
                        'Working',
                    ),
                    'Project' => array(
                        'fields' => array('CompTime', 'Customer_id', 'Name'),
                        'Customer' => array(
                            'Country'
                        ),
                    ),
                ),
                'conditions' => array(
                    'OR' => array(
                        'checker_id' => $report_input['user_id'],
                        'doner_id' => $report_input['user_id'],
                    ),
                    'CheckerProduct.project_id' => $project_ids
                ),

            ));


            $total_checking_time = 0;
            $total_checking_files = 0;
            foreach ($checkingFiles as $checking) {
                if ($checking['CheckerProduct']['checker_id'] == $report_input['user_id']) {
                    $begin_row++;
                    $excel->getActiveSheet()->setCellValue('A' . $begin_row, $checking['CheckerProduct']['end_time']);
                    $excel->getActiveSheet()->setCellValue('B' . $begin_row, $checking['Project']['Customer']['Country']['name']);
                    $excel->getActiveSheet()->setCellValue('C' . $begin_row, $checking['Project']['Customer']['name']);
                    $excel->getActiveSheet()->setCellValue('D' . $begin_row, $begin_row - 7);
                    $excel->getActiveSheet()->setCellValue('E' . $begin_row, $checking['Project']['Name']);
                    $excel->getActiveSheet()->setCellValue('F' . $begin_row, $checking['Product']['name_file_product']);
                    $excel->getActiveSheet()->setCellValue('G' . $begin_row, "Kiểm tra");
                    $excel->getActiveSheet()->setCellValue('H' . $begin_row, 1);
                    $date_a = $checking['CheckerProduct']['start_time'];
                    $date_b = $checking['CheckerProduct']['end_time'];
                    $total_checking_files++;
                    if ($checking['CheckerProduct']['end_time'] && $checking['CheckerProduct']['end_time'] != "0000-00-00 00:00:00") {
                        $total_checking_time += strtotime($date_b) - strtotime($date_a);
                    }
                    //$excel->getActiveSheet()->setCellValue('I' . $begin_row, $this->show_time_from_second($product['Working'][0]['process_time']));
                    $excel->getActiveSheet()->setCellValue('J' . $begin_row, $this->getPointByProductAndAction($checking['Product']['id'], 6));

                    //Lấy toàn bộ các reject liên quan đến dự án của nghiệp vụ này
                    $reject_NVs = $this->Reject->find('all', array(
                        'conditions' => array(
                            'product_id' => $checking['Product']['id'],
                            'action_id' => 6
                        ),
                        'contain' => array(
                            'Ratepoint'
                        )

                    ));
                    $rejVal = 0;
                    $rejPer = "";
                    foreach ($reject_NVs as $projRej) {
                        $total_err++;
                        $rejVal += $projRej['Reject']['percent'] * 0.1 * $projRej['Ratepoint']['value'] * $nghiepvuArr[6] * $this->get_process_point($checking, $processArr, $timepoints);
                        $rejPer .= ($projRej['Reject']['percent'] * 10) . "% ";
                        $excel->getActiveSheet()->setCellValue($pos_rej_percent[$projRej['Reject']['rate_point_id']] . $begin_row, $rejPer);
                        $excel->getActiveSheet()->setCellValue($pos_rej[$projRej['Reject']['rate_point_id']] . $begin_row, $rejVal);
                        if ($rejVal) {
                            $excel->getActiveSheet()->getStyle($pos_rej[$projRej['Reject']['rate_point_id']] . $begin_row)->applyFromArray(
                                array(
                                    'fill' => array(
                                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                        'color' => array('rgb' => 'FF0000')
                                    )
                                )
                            );
                        }
                    }
                    $excel->getActiveSheet()->setCellValue('Q' . $begin_row, "=(J" . $begin_row . "+L" . $begin_row . "+N" . $begin_row . "+P" . $begin_row . ")");
                }

            }
            $total_done_files = 0;
            $total_done_time = 0;

            foreach ($checkingFiles as $checking) {
                if ($checking['CheckerProduct']['doner_id'] == $report_input['user_id']) {
                    $begin_row++;
                    $excel->getActiveSheet()->setCellValue('A' . $begin_row, $checking['CheckerProduct']['end_time']);
                    $excel->getActiveSheet()->setCellValue('B' . $begin_row, $checking['Project']['Customer']['Country']['name']);
                    $excel->getActiveSheet()->setCellValue('C' . $begin_row, $checking['Project']['Customer']['name']);
                    $excel->getActiveSheet()->setCellValue('D' . $begin_row, $begin_row - 7);
                    $excel->getActiveSheet()->setCellValue('E' . $begin_row, $checking['Project']['Name']);
                    $excel->getActiveSheet()->setCellValue('F' . $begin_row, $checking['Product']['name_file_product']);
                    $excel->getActiveSheet()->setCellValue('G' . $begin_row, "Done");
                    $excel->getActiveSheet()->setCellValue('H' . $begin_row, 1);
                    $date_a = $checking['CheckerProduct']['start_time'];
                    $date_b = $checking['CheckerProduct']['end_time'];
                    $total_done_files++;
                    if ($checking['CheckerProduct']['end_time'] && $checking['CheckerProduct']['end_time'] != "0000-00-00 00:00:00" && ($date_b > $date_a)) {
                        $total_done_time += strtotime($date_b) - strtotime($date_a);
                    }
                    //$excel->getActiveSheet()->setCellValue('I' . $begin_row, $this->show_time_from_second($product['Working'][0]['process_time']));
                    $excel->getActiveSheet()->setCellValue('J' . $begin_row, $nghiepvuArr[7]);
                    //Lấy toàn bộ các reject liên quan đến dự án của nghiệp vụ này
                    $reject_NVs = $this->Reject->find('all', array(
                        'conditions' => array(
                            'product_id' => $checking['Product']['id'],
                            'action_id' => 7
                        ),
                        'contain' => array(
                            'Ratepoint'
                        )

                    ));
                    $rejVal = 0;
                    $rejPer = "";

                    foreach ($reject_NVs as $projRej) {
                        $total_err++;
                        $rejVal += $projRej['Reject']['percent'] * 0.1 * $projRej['Ratepoint']['value'] * $nghiepvuArr[7];
                        $rejPer .= ($projRej['Reject']['percent'] * 10) . "% ";
                        $excel->getActiveSheet()->setCellValue($pos_rej_percent[$projRej['Reject']['rate_point_id']] . $begin_row, $rejPer);
                        $excel->getActiveSheet()->setCellValue($pos_rej[$projRej['Reject']['rate_point_id']] . $begin_row, $rejVal);
                        if ($rejVal) {
                            $excel->getActiveSheet()->getStyle($pos_rej[$projRej['Reject']['rate_point_id']] . $begin_row)->applyFromArray(
                                array(
                                    'fill' => array(
                                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                        'color' => array('rgb' => 'FF0000')
                                    )
                                )
                            );
                        }
                    }
                    $excel->getActiveSheet()->setCellValue('Q' . $begin_row, "=(J" . $begin_row . "+L" . $begin_row . "+N" . $begin_row . "+P" . $begin_row . ")");
                }

            }

            //Danh sách các nghiệp vụ khác
            //Lấy toàn bộ Project action của dự án để tính điểm
            $project_actions = $this->ProjectAction->find('all', array(
                'recursive' => 1, //int

                'conditions' => array(
                    'ProjectAction.Project_id' => $project_ids,
                    'ProjectAction.User_id' => $report_input['user_id'],
                    'NOT' => array('ProjectAction.Action_id' => array(6, 7, 3))
                ),
                'contain' => array(
                    'Project' => array(
                        'Customer' => array(
                            'Country'
                        ),
                    ),
                    'Action'
                )

            ));
//            debug($project_actions);
//    die;
            //Tạo mảng chứa số lượng nghiệp vụ của từng user
//            $user_nv_Arr = array();
            $this->Reject->Behaviors->load('Containable');

            foreach ($project_actions as $action) {
                //Nhân viên bổ sung (Action id = 13) chỉ dùng cho reject ko hiện ở đây
//                if ($action['Action']['ID'] <> 13) {
                $begin_row++;
                $excel->getActiveSheet()->setCellValue('A' . $begin_row, $action['Project']['InputDate']);
                $excel->getActiveSheet()->setCellValue('B' . $begin_row, $action['Project']['Customer']['Country']['name']);
                $excel->getActiveSheet()->setCellValue('C' . $begin_row, $action['Project']['Customer']['name']);
                $excel->getActiveSheet()->setCellValue('D' . $begin_row, $begin_row - 7);
                $excel->getActiveSheet()->setCellValue('E' . $begin_row, $action['Project']['Name']);
                $excel->getActiveSheet()->setCellValue('G' . $begin_row, $action['Action']['Name']);
                $value = 1;
                if ($action['Action']['ID'] <> 13) {
                    if (get($action['ProjectAction'], 'value'))
                        $value = $action['ProjectAction']['value'];
                    $excel->getActiveSheet()->setCellValue('H' . $begin_row, $value); //Number
                    $excel->getActiveSheet()->setCellValue('J' . $begin_row, $nghiepvuArr[$action['Action']['ID']] * $value);
                }
                //Tính reject cho từng nghiệp vụ nếu có


                //Lấy toàn bộ các reject liên quan đến dự án của nghiệp vụ này
                $reject_NVs = $this->Reject->find('all', array(
                    'conditions' => array(
                        'project_id' => $action['Project']['ID'],
                        'action_id' => $action['Action']['ID'],
                        'user_id_reject' => $report_input['user_id']
                    ),
                    'contain' => array(
                        'Ratepoint'
                    )

                ));
                $rejVal = 0;
                $rejPer = "";
                foreach ($reject_NVs as $projRej) {
                    $total_err++;
//                    if ($action['Action']['ID'] == 13) {
//                        $rejVal = $projRej['Reject']['percent'] * 0.1 * $projRej['Ratepoint']['value'] * $nghiepvuArr[$action['Action']['ID']] * $value * (-1);
//                    } else {
//                        $rejVal = $projRej['Reject']['percent'] * 0.1 * $projRej['Ratepoint']['value'] * $nghiepvuArr[$action['Action']['ID']] * $value;
//                    }
                    $rejVal = $this->getRejectPointByProductAndAction($projRej['Reject']['product_id'], $action['Action']['ID'], $projRej['Reject']['id']);
                    $rejPer .= ($projRej['Reject']['percent'] * 10) . "% ";
                    $excel->getActiveSheet()->setCellValue($pos_rej_percent[$projRej['Reject']['rate_point_id']] . $begin_row, $rejPer);

                    $excel->getActiveSheet()->setCellValue($pos_rej[$projRej['Reject']['rate_point_id']] . $begin_row, $rejVal);
                    if ($rejVal) {
                        $excel->getActiveSheet()->getStyle($pos_rej[$projRej['Reject']['rate_point_id']] . $begin_row)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'FF0000')
                                )
                            )
                        );
                    }
                }

                $excel->getActiveSheet()->setCellValue('Q' . $begin_row, "=(J" . $begin_row . "+L" . $begin_row . "+N" . $begin_row . "+P" . $begin_row . ")");
//                }
                $total_point_err += $rejVal;
            }


            //////////////////

            //Danh sách thực hiện reject
            //Lấy toàn bộ các reject liên quan do người này thực hiện
            $rejecters = $this->Reject->find('all', array(
                'conditions' => array(
                    'rejecter' => $report_input['user_id'],
                    'datetime >= ' => $fromdate,
                    'datetime <= ' => $todate,
                ),
                'contain' => array(
                    'Ratepoint',
                    'Project' => array(
                        'fields' => array('InputDate', 'Name'),
                        'Customer'
                    ),
                    'Action',
                    'User' => array(
                        'fields' => 'username'
                    )
                )
            ));
            foreach ($rejecters as $rejecter) {

                $begin_row++;
                if ($rejecter['Reject']['action_id'] == 100) {
                    $rejecter['Action']['Name'] = "làm hàng";
                }

                $excel->getActiveSheet()->setCellValue('A' . $begin_row, $rejecter['Reject']['datetime']);
                $excel->getActiveSheet()->setCellValue('B' . $begin_row, get(get($rejecter['Project']['Customer'], 'Country'), 'name'));
                $excel->getActiveSheet()->setCellValue('C' . $begin_row, $rejecter['Project']['Customer']['name']);
                $excel->getActiveSheet()->setCellValue('D' . $begin_row, $begin_row - 7);
                $excel->getActiveSheet()->setCellValue('E' . $begin_row, $rejecter['Project']['Name']);
                $excel->getActiveSheet()->setCellValue('G' . $begin_row, "Thực hiện reject cho " . $rejecter['User']['username'] . ", nghiệp vụ " . $rejecter['Action']['Name']);
                $excel->getActiveSheet()->setCellValue('H' . $begin_row, 1);
                $excel->getActiveSheet()->setCellValue('J' . $begin_row, "=(0.5*" . $this->getRejectPointByProductAndAction($rejecter['Reject']['product_id'], $rejecter['Reject']['action_id'], $rejecter['Reject']['id']) * (-1) . ")");
                $excel->getActiveSheet()->setCellValue('Q' . $begin_row, "=(J" . $begin_row . "+L" . $begin_row . "+N" . $begin_row . "+P" . $begin_row . ")");

            }

            ///////

//            die;
            //Summary
            $begin_row++;
            $total_points_f1 = "";
            $xxi = "I";
            while ($xxi != "R") {
                if ($xxi == "K" || $xxi == "M" || $xxi == "O") {
                    $xxi++;
                    continue;
                }
                $excel->getActiveSheet()->getStyle($xxi . $begin_row)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'FF0000')
                        )
                    )
                );
                //
                $total_points_f1 = "=SUM(" . $xxi . "7:" . $xxi . ($begin_row - 1) . ")";
                $excel->getActiveSheet()->setCellValue($xxi . $begin_row, "=SUM(" . $xxi . "7:" . $xxi . ($begin_row - 1) . ")");
                $xxi++;
            }
            $begin_row++;
            $begin_row++;
            $begin_row++;
            $excel->getActiveSheet()->setCellValue('F' . $begin_row, "Total Time");
            $excel->getActiveSheet()->setCellValue('G' . $begin_row, $this->show_time_from_second($total_time_process));

            foreach ($count_process as $pc) {
                $begin_row++;
                $excel->getActiveSheet()->setCellValue('F' . $begin_row, "Total " . $pc['name']);
                $excel->getActiveSheet()->setCellValue('G' . $begin_row, $pc['number']);
                $begin_row++;
                $excel->getActiveSheet()->setCellValue('F' . $begin_row, "Average time" . $pc['name'] . " / Total " . $pc['name']);
                $excel->getActiveSheet()->setCellValue('G' . $begin_row, $this->show_time_from_second(ceil($pc['time'] / $pc['number'])));
            }


            $begin_row++;
            $excel->getActiveSheet()->setCellValue('F' . $begin_row, "Total Files");
            $excel->getActiveSheet()->setCellValue('G' . $begin_row, $total_process_files);

            if ($total_process_files) {
                $begin_row++;
                $excel->getActiveSheet()->setCellValue('F' . $begin_row, "Average time/ a file");
                $excel->getActiveSheet()->setCellValue('G' . $begin_row, $this->show_time_from_second(ceil($total_time_process / $total_process_files)));

            }

            $begin_row++;
            $excel->getActiveSheet()->setCellValue('F' . $begin_row, "Total time check");
            $excel->getActiveSheet()->setCellValue('G' . $begin_row, $this->show_time_from_second($total_checking_time));

            if ($total_checking_files) {
                $begin_row++;
                $excel->getActiveSheet()->setCellValue('F' . $begin_row, "Average time check/ file");
                $excel->getActiveSheet()->setCellValue('G' . $begin_row, $this->show_time_from_second(ceil($total_checking_time / $total_checking_files)));
            }
            $begin_row++;
            $excel->getActiveSheet()->setCellValue('F' . $begin_row, "Total Error");
            $excel->getActiveSheet()->setCellValue('G' . $begin_row, $total_point_err); //$total_err
            $begin_row++;
            $excel->getActiveSheet()->setCellValue('F' . $begin_row, "Total Point rest");
            $excel->getActiveSheet()->setCellValue('G' . $begin_row, $total_points_f1);
            $begin_row++;

//    debug($checkingFiles);die;

            if (isset($_GET['debug'])) {
                // return true;
                // die('xxx');
            }
            $this->PhpExcel->output($filename);
            die;
        } else {
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Bạn chưa nhập đủ thông tin để xuất báo cáo.</div>'));
            $this->redirect(array('action' => 'index'));
        }
    }

    public function employee()
    {
        ini_set('memory_limit', '-1');
        error_reporting(0);
        ini_set('display_errors', 0);

//        debug($this->request->data);
//        die;
        if ($this->request->is('post')) {
            $report_input = $this->request->data;
            if (isset($report_input['Report']['from_date']) && $report_input['Report']['from_date'] && isset($report_input['Report']['to_date']) && $report_input['Report']['to_date']) {

                $fromdate = str_replace("/", "-", $report_input['Report']['from_date']);
                $this->set('from_date', $fromdate);

                $fromdate = new DateTime($fromdate . " 00:00:00");
                $show_fromdate = $fromdate->format('d-m-Y');
                $fromdate = $fromdate->format('Y-m-d H:i:s');
                $todate = str_replace("/", "-", $report_input['Report']['to_date']);
                $this->set('to_date', $todate);
                $todate = new DateTime($todate . " 23:59:59");
                $show_todate = $todate->format('d-m-Y');
                $todate = $todate->format('Y-m-d H:i:s');
                $conditions['OR'] = array(
                    array(
                        'Project.CompTime >=' => $fromdate,
                        'Project.CompTime <=' => $todate
                    )
                ,
//                    array(
//                        'Project.InputDate >=' => $fromdate,
//                        'Project.InputDate <=' => $todate
//                    )
                );

                $project_ids = $this->Project->find('list', array(
                    'recursive' => -1, //int
                    'fields' => array('ID'),
                    'conditions' => $conditions
                ));

//                debug($project_ids);die;
                if (isset($report_input["NV_ID"]) && $report_input["NV_ID"]) {
                    $user_ids = explode(",", $report_input["NV_ID"]);
                    $user_ids = $this->User->find('all', array(
                        'fields' => array('name', 'username', 'id'),
                        'conditions' => array('status' => 1, 'id' => $user_ids)
                    ));

                } else {
                    $user_ids = $this->User->find('all', array(
                        'fields' => array('name', 'username', 'id'),
                        'conditions' => array('status' => 1)
                    ));
                }

                if (!isset($_GET['debug'])) {
                    $filename = "CollectionStaffReport_" . $show_fromdate . "_" . $show_todate . ".xlsx";
                    ob_end_clean();
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
                    header("Content-Type: application/force-download");
                    header("Content-Type: application/octet-stream");
                    header("Content-Type: application/download");
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                    header("Content-Transfer-Encoding: binary ");
                    header('Cache-Control: max-age=0');

                }
                $file = 'ExcelSource/V5_CollectionStaffReport.xlsx';
                $excel = $this->PhpExcel->loadWorksheet($file);

                $excel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Times New Roman');
                $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(25);


                $begin = 9;
                //Setup Reject column
                $reject_types = $this->Ratepoint->find('all', array(
                    'recursive' => -1, //int
                ));
                $col = "I";
                $total_rejects = array();
                //Vi tri cua tung loai reject
                $pos_reject = array();
                $points = array();
                foreach ($reject_types as $rtype) {
                    $excel->getActiveSheet()->setCellValue($col . $begin, $rtype['Ratepoint']['name'] . " Times");
                    $total_rejects[$rtype['Ratepoint']['id']] = "";
                    $pos_reject[$rtype['Ratepoint']['id']] = $col;
                    $col++;
                    $excel->getActiveSheet()->setCellValue($col . $begin, $rtype['Ratepoint']['name'] . " Point");
                    $col++;
                    $points[$rtype['Ratepoint']['id']] = $rtype['Ratepoint']['value'];
                }
                $excel->getActiveSheet()->mergeCells("I" . ($begin - 1) . ':' . $col . ($begin - 1));
                $excel->getActiveSheet()->setCellValue("I" . ($begin - 1), "Các loại reject");
                $excel->getActiveSheet()->setCellValue($col . "9", "Total processing point");
                $i = 1;
                $begin++;
                $row = 0;


                ///////////////
                //Biến vị trí: $col, $nv_col,
                //Thứ tự header: A->H -> $col++ -> $nv_col (điểm nghiệp vụ)


                //Danh mục thang điểm thời gian
                $timepoints = $this->TimePoint->find('all', array(
                    'recursive' => -1, //int
                    'order' => array('TimePoint.time DESC'),
                ));


                //Lấy bảng actions để tạo mảng thang điểm cho từng loại action
                $action_points = $this->Action->find('all', array(
                    'recursive' => -1, //int
                ));
                $actionsArr = array();
                $nghiepvuArr = array();
                $pos_nghiepvuArr = array();
                $nv_col = $col;
                $pos_nghiepvu = array();
                $nv_col++;
                foreach ($action_points as $a_point) {
                    $nghiepvuArr[$a_point['Action']['ID']] = $a_point['Action']['Point'];
                    //Hiển thị danh sách các loại nghiệp vụ
                    $excel->getActiveSheet()->setCellValue($nv_col . ($begin - 1), $a_point['Action']['Name']);
                    $pos_nghiepvu[$a_point['Action']['ID']] = $nv_col;
                    $nv_col++;
                }

                $tmp = $nv_col;
                $excel->getActiveSheet()->setCellValue(($tmp) . "9", "Điểm reject");
                $excel->getActiveSheet()->setCellValue((++$tmp) . "9", "Total operative point");
                $excel->getActiveSheet()->setCellValue((++$tmp) . "9", "TOTAL POINT");

                //Lấy toàn bộ Project action của dự án để tính điểm
                $project_actions = $this->ProjectAction->find('all', array(
                    'recursive' => -1, //int

                    'conditions' => array(
                        'project_id' => $project_ids,
                        'NOT' => array('ProjectAction.Action_id' => array(6, 7, 3))

                    ),
                ));

                //Tạo mảng chứa số lượng nghiệp vụ của từng user
                $user_nv_Arr = array();
                foreach ($project_actions as $action) {

                    if (!isset($user_nv_Arr[$action['ProjectAction']['User_id']][$action['ProjectAction']['Action_id']]['value'])) $user_nv_Arr[$action['ProjectAction']['User_id']][$action['ProjectAction']['Action_id']]['value'] = 0;
                    if (!isset($user_nv_Arr[$action['ProjectAction']['User_id']][$action['ProjectAction']['Action_id']]['number'])) $user_nv_Arr[$action['ProjectAction']['User_id']][$action['ProjectAction']['Action_id']]['number'] = 0;
                    $user_nv_Arr[$action['ProjectAction']['User_id']][$action['ProjectAction']['Action_id']]['ID'] = $action['ProjectAction']['Action_id'];
                    $user_nv_Arr[$action['ProjectAction']['User_id']][$action['ProjectAction']['Action_id']]['number'] += 1;
                    $value = 1;
                    if (get(get($action,'ProjectAction'),'value'))
                        $value = $action['ProjectAction']['value'];
                    $user_nv_Arr[$action['ProjectAction']['User_id']][$action['ProjectAction']['Action_id']]['value'] += $value;
                }

                //yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy
                ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //Code here
                ///////////////

                //Danh mục loại xử lý
                $processtypes = $this->Processtype->find('all', array(
                    'recursive' => -1, //int
                ));
                $processArr = array();

                //Mảng tổng số lượng các loại xử lý của từng người
                $total_process_type = array();
                $start_col_xuly = $nv_col; //Cột nghiệp vụ
                $start_col_xuly++; //Cột tổng nghiệp vụ
                $start_col_xuly++; // Cột tổng điểm
                $start_col_xuly++; //Cột loại xử lý
                $pos_process = array();
                foreach ($processtypes as $pt) {
                    $processArr[$pt['Processtype']['id']]['point'] = $pt['Processtype']['point'];
                    $processArr[$pt['Processtype']['id']]['number'] = $pt['Processtype']['number']; //Hệ số
                    $processArr[$pt['Processtype']['id']]['timecheck'] = $pt['Processtype']['time_checkbox']; //Tính theo thời gian

                    //Hiển thị danh sách các loại xử lý
                    $excel->getActiveSheet()->setCellValue($start_col_xuly . "9", $pt['Processtype']['name']);
                    $excel->getActiveSheet()->getStyle($start_col_xuly . "9")->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'FF0000')
                            )
                        )
                    );
                    $pos_process[$pt['Processtype']['id']] = $start_col_xuly;
                    $start_col_xuly++;
                }
                $total_rej = $total_rejects;

                foreach ($user_ids as $uid) {
                    $row++;
                    $begin++;
                    $excel->getActiveSheet()->setCellValue('A' . $begin, $row);
                    $excel->getActiveSheet()->setCellValue('B' . $begin, $uid['User']['name']);

                    //Get total file processing
                    /*
                    $this->Working->Behaviors->load('Containable');
                    $processingFiles = $this->Working->find('all', array(
//                        'fields'=>array('id', 'product_id', 'user_id'),
                        'recursive' => -1, //int

                        'conditions' => array(
                            'user_id' => $uid['User']['id'],
                            'Product.project_id' => $project_ids
                        ),
                        'contain' => array(
                            'Product' => array(
                                'fields' => array('id', 'process_type_id'),
                            )
                        ),
                    ));
                    */

                    $this->Product->Behaviors->load('Containable');

                    $processingFiles = $this->Product->find('all', array(
                        'conditions' => array(
                            'Product.perform_user_id' => $uid['User']['id'],
                            'Product.done_round' => 2,
                            'Product.project_id' => $project_ids
                        ),
                        'contain' => array(
                            'Working',
                        )
                    ));

//                    debug(($processingFiles));die;


                    //Tong thoi gian processign cua tung nguoi
                    $total_process_time = 0;
                    $total_process_points = 0;

                    $total_process_count = array();
//                    debug(count($processingFiles));
                    foreach ($processingFiles as $process) {
                        $process['Working'] = $process['Working'][0];
                        $total_process_time += $process['Working']['process_time'];
                        /*****Tính điểm dựa theo số lượng process*****/

                        //Thời gian làm file thực tế (Tính theo phút)
                        $workingTime = $process['Working']['process_time'] / 60;

                        //Kiểm tra xem loại xử lý của file hiện tại
                        $currProcess = $process['Product']['process_type_id'];

                        //Kiểm tra xem có phải là tính điểm theo thời gian hay không?
                        //- Nếu tính theo thời gian
                        if (get(get($processArr,$currProcess),'timecheck')) {
                            //Lấy theo thang thời gian
                            //1. Hệ số
                            $heso = $processArr[$currProcess]['number'];
                            if (!$heso) {
                                $heso = 1;
                            }
                            //2. Số điểm
                            $processArr[$currProcess]['point'];
                            $timepoints;

                            $tmp_point = $processArr[$currProcess]['point'];
                            //15,10,5,2,1
                            //8
                            foreach ($timepoints as $timepoint) {
                                if ($workingTime <= $timepoint['TimePoint']['time']) {
                                    $tmp_point = $timepoint['TimePoint']['point'];
                                }
                            }
                            $total_process_points += $tmp_point * $heso;

                        } else {
                            //-Nếu không tính theo thời gian thì điểm số = số điểm X số xử lý
                            $soxuly = 1;
                            $total_process_points += $processArr[$currProcess]['point'] * $soxuly;
                        }


                        //Tính số loại xử lý của mỗi loại
                        if (!isset($total_process_count[$currProcess])) {
                            $total_process_count[$currProcess] = 0;
                        }
                        $total_process_count[$currProcess] += 1;
                        $excel->getActiveSheet()->setCellValue($pos_process[$currProcess] . $begin, $total_process_count[$currProcess]);
                    }


                    $excel->getActiveSheet()->setCellValue('C' . $begin, count($processingFiles));
                    $excel->getActiveSheet()->setCellValue('E' . $begin, $this->show_time_from_second($total_process_time));
                    if (count($processingFiles)) {
                        $excel->getActiveSheet()->setCellValue('G' . $begin, $this->show_time_from_second($total_process_time / count($processingFiles)));
                    }

                    $pos = $col;
                    $excel->getActiveSheet()->setCellValue($pos . $begin, $total_process_points);

                    //Tính điểm nghiệp vụ
                    //-Review

                    //Nghiệp vụ chia hàng
                    $this->Product->Behaviors->load('Containable');
                    $products = $this->Product->find('all', array(
                        'conditions' => array(
                            'Product.deliver_user_id' => $uid['User']['id'],
                            'Product.done_round' => 2,
                            'Project.ID' => $project_ids
                        ),
                        'contain' => array(
                            'Project' => array(
                                'fields' => array('CompTime', 'Customer_id', 'Name'),
                                'Customer' => array(
                                    'Country'
                                ),

                            ),
                            'Processtype',
                            'Working',
                            'Reject' => array(
                                'fields' => array('product_id', 'rate_point_id', 'percent', 'action_id'),
                                'Ratepoint'
                            )
                        )
                    ));

                    foreach ($products as $product) {
                        if (!isset($user_nv_Arr[$uid['User']['id']][3]['value'])) $user_nv_Arr[$uid['User']['id']][3]['value'] = 0;
                        if (!isset($user_nv_Arr[$uid['User']['id']][3]['number'])) $user_nv_Arr[$uid['User']['id']][3]['number'] = 0;
                        $user_nv_Arr[$uid['User']['id']][3]['ID'] = 3;
                        $user_nv_Arr[$uid['User']['id']][3]['number'] += 1;
                        $user_nv_Arr[$uid['User']['id']][3]['value'] += 1;
                    }

                    //Get Total checking files
                    $this->CheckerProduct->Behaviors->load('Containable');
                    $checkingFiles = $this->CheckerProduct->find('all', array(
                        'contain' => array(
                            'Product' => array(
                                'fields' => array('name_file_product'),
                                'Processtype',
                                'Working',
                            ),
                            'Project' => array(
                                'fields' => array('CompTime', 'Customer_id', 'Name'),
                                'Customer' => array(
                                    'Country'
                                ),
                            ),
                        ),
                        'conditions' => array(
                            'OR' => array(
                                'checker_id' => $uid['User']['id'],
                                'doner_id' => $uid['User']['id'],

                            ),
                            'CheckerProduct.project_id' => $project_ids
                        ),

                    ));
                    $total_checking_time = 0;
                    $total_checking_points = 0;
                    $user_nv_Arr[$uid['User']['id']][6]['ID'] = 6;
                    $user_nv_Arr[$uid['User']['id']][6]['number'] = 0;
                    $user_nv_Arr[$uid['User']['id']][6]['value'] = 0;
                    foreach ($checkingFiles as $checking) {
                        if ($checking['CheckerProduct']['checker_id'] == $uid['User']['id']) {
                            $date_a = $checking['CheckerProduct']['start_time'];
                            $date_b = $checking['CheckerProduct']['end_time'];

                            if ($checking['CheckerProduct']['end_time'] && $checking['CheckerProduct']['end_time'] != "0000-00-00 00:00:00" && (strtotime($date_b) - strtotime($date_a))) {
                                $total_checking_time += strtotime($date_b) - strtotime($date_a);
                            }

                            $total_checking_points += $this->getPointByProductAndAction($checking['CheckerProduct']['products'], 6);
                            $user_nv_Arr[$uid['User']['id']][6]['ID'] = 6;
                            $user_nv_Arr[$uid['User']['id']][6]['number'] += 1;
                            $user_nv_Arr[$uid['User']['id']][6]['value'] += 1;
                        }
                        if ($checking['CheckerProduct']['doner_id'] == $uid['User']['id']) {
                            if (!isset($user_nv_Arr[$uid['User']['id']][7]['value'])) $user_nv_Arr[$uid['User']['id']][7]['value'] = 0;
                            if (!isset($user_nv_Arr[$uid['User']['id']][7]['number'])) $user_nv_Arr[$uid['User']['id']][7]['number'] = 0;
                            $user_nv_Arr[$uid['User']['id']][7]['ID'] = 7;
                            $user_nv_Arr[$uid['User']['id']][7]['number'] += 1;
                            $user_nv_Arr[$uid['User']['id']][7]['value'] += 1;
                        }

                    }
                    $excel->getActiveSheet()->setCellValue('D' . $begin, count($checkingFiles));
                    $excel->getActiveSheet()->setCellValue('F' . $begin, $this->show_time_from_second($total_checking_time));
                    if (count($checkingFiles)) {
                        $excel->getActiveSheet()->setCellValue('H' . $begin, $this->show_time_from_second($total_checking_time / count($checkingFiles)));
                    }


                    //Get all rejects

                    $rejects = $this->Reject->find('all', array(
//                        'recursive' => -1, //int

                        'conditions' => array(
                            'OR' => array(
                                'rejecter' => $uid['User']['id'],
                                'user_id_reject' => $uid['User']['id'],

                            ),
                            'datetime >=' => $fromdate,
                            'datetime <=' => $todate,
                        ),
                        'contain' => array(
                            'Product' => array(
                                'Processtype',
                                'Working',
                            ),
                            'Action'
                        )
                    ));


                    //Tính tổng số reject của từng loại
                    $count_reject = $total_rejects;
                    $point_reject = $total_rejects;
                    $rejecter = 0;
                    foreach ($rejects as $reject) {

                        //TInhs ddier nguoi reject
                        if ($reject['Reject']['rejecter'] == $uid['User']['id']) {
                            $rejecter += 0.5 * (-1) * $this->getRejectPointByProductAndAction($reject['Reject']['product_id'], $reject['Reject']['action_id'], $reject['Reject']['id']);
                        } else {
                            $count_reject[$reject['Reject']['rate_point_id']] += (10 * $reject['Reject']['percent']);
                            $total_rej[$reject['Reject']['rate_point_id']] += 10 * $reject['Reject']['percent'];
                            $point_reject[$reject['Reject']['rate_point_id']] += $this->getRejectPointByProductAndAction($reject['Reject']['product_id'], $reject['Reject']['action_id'], $reject['Reject']['id']);

                        }
                    }

                    //Hiển thị số lượng reject theo từng cột
                    foreach ($reject_types as $type) {
                        $p = $pos_reject[$type['Ratepoint']['id']];
                        $p++;
                        $excel->getActiveSheet()->setCellValue($pos_reject[$type['Ratepoint']['id']] . $begin, $count_reject[$type['Ratepoint']['id']] ? $count_reject[$type['Ratepoint']['id']] . "% " : "");
                        if ($point_reject[$type['Ratepoint']['id']]) {
                            $excel->getActiveSheet()->setCellValue($p . $begin, $point_reject[$type['Ratepoint']['id']]);
                        }
                    }


                    //Hiển thị số điểm nghiệp vụ của từng loại

                    if (isset($user_nv_Arr[$uid['User']['id']])) {
                        foreach ($user_nv_Arr[$uid['User']['id']] as $nghiepvu_point) {
                            if ($nghiepvu_point['ID'] == 6) {
                                $excel->getActiveSheet()->setCellValue($pos_nghiepvu[$nghiepvu_point['ID']] . $begin, "=(" . $total_checking_points . ")");
                            } elseif ($nghiepvu_point['ID'] != 13) {
                                $excel->getActiveSheet()->setCellValue($pos_nghiepvu[$nghiepvu_point['ID']] . $begin, "=(" . $nghiepvu_point['value'] . "*" . $nghiepvuArr[$nghiepvu_point['ID']] . ")");
                            }
                        }
                    }

                    $excel->getActiveSheet()->setCellValue($nv_col . $begin, $rejecter);
                    //
                    //Hiển thị tổng điểm nghiệp vụ (THeo công thức)
                    $col_total_active = $nv_col;
                    $tmp = $nv_col;
                    $start_col = $col;
                    $excel->getActiveSheet()->setCellValue((++$col_total_active) . $begin, "=SUM(" . (++$start_col) . $begin . ":" . $tmp . $begin . ")");

                    $tmp = $col_total_active;
                    //Hiển thị tổng Total point
//                    $excel->getActiveSheet()->setCellValue(++$col_total_active . $begin, "=SUM(" . $pos . $begin .  ":" . $tmp . $begin.", J" . $begin . ",L" . $begin . ",N". $begin . ")");
                    $excel->getActiveSheet()->setCellValue(++$col_total_active . $begin, "=SUM(AH" . $begin . ",O" . $begin . ",J" . $begin . ",L" . $begin . ",N" . $begin . ")");


//                    echo "<pre>";
//                    print_r($rejects);
//                    echo "</pre>";


                }
                $begin++;
                //Tỉnh tổng theo từng cột(Sử dụng công thức)
                $i = "I";

                while ($i != $start_col_xuly) {
                    $excel->getActiveSheet()->getStyle($i . $begin)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'FF0000')
                            )
                        )
                    );
                    $excel->getActiveSheet()->setCellValue($i . $begin, "=SUM(" . $i . "10:" . $i . ($begin - 1) . ")");

                    $i++;
                }
                $excel->getActiveSheet()->setCellValue("I" . $begin, $total_rej[1] . "%");
                $excel->getActiveSheet()->setCellValue("K" . $begin, $total_rej[2] . "%");
                $excel->getActiveSheet()->setCellValue("M" . $begin, $total_rej[3] . "%");

                $excel->getActiveSheet()->setTitle('Employee General report');
                $excel->setActiveSheetIndex(0);
                //$excel = new PHPExcel();

                //debug($excel);
                if (isset($_GET['debug'])) return;

//                $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
                $this->PhpExcel->output($filename);
            }
        }
        die;
    }
    
    
    public function getIds($data, $modelName, $idField = 'id') {
        $res = array();
        foreach ($data as $aData)
        {
            if (isset($aData[$modelName][$idField]))
                $res[] = $aData[$modelName][$idField];
            else
                foreach ($aData[$modelName] as $item){
                    $res[] = $item[$idField];
                }
        }
        
        return array_unique($res);
    }
    
    public function createIndex($data, $modelName, $field)
    {
        $res = array();
        foreach ($data as $item)
        {
            $item = $item[$modelName];
            $res[$item[$field]] = $item;
        }
        
        return $res;
    }
    
    
    
    public function createArrayIndex($data, $modelName, $field)
    {
        $res = array();
        foreach ($data as $item)
        {
            $item = $item[$modelName];
            if (!isset($res[$item[$field]]))
                $res[$item[$field]] = array($item);
            else
                $res[$item[$field]][] = $item;
        }
        
        return $res;
    }
    
    public function fetchMoreData($report_data)
    {
        $ProductIds = $this->getIds($report_data, 'Product');
        $CountryIds = $this->getIds($report_data, 'Customer', 'country_id');
        
        $Workings = $this->Working->find(
            'all', 
            array(
                'fields' => array('process_time', 'id', 'product_id'),
                'conditions' => array(
                    'product_id' => $ProductIds,
                )
            )
        );
        $Workings = $this->createArrayIndex($Workings, 'Working', 'product_id');
        
        $CheckerProducts = $this->CheckerProduct->find(
            'all', 
            array(
                'fields' => array('start_time', 'end_time', 'id', 'products'),
                'conditions' => array(
                    'products' => $ProductIds,
                )
            )
        );
        $CheckerProducts = $this->createArrayIndex($CheckerProducts, 'CheckerProduct', 'products');
        
        $Countrys = $this->Country->find(
            'all', 
            array(
                'fields' => array('id', 'name'),
                'conditions' => array(
                    'id' => $CountryIds,
                )
            )
        );
        $Countrys = $this->createIndex($Countrys, 'Country', 'id');
        
        foreach ($report_data as &$data) {
            $data['Customer']['Country'] = $Countrys[$data['Customer']['country_id']];
            foreach ($data['Product'] as &$product){
                $product['Working'] = isset($Workings[$product['id']]) ? $Workings[$product['id']] : array();
                $product['CheckerProduct'] = isset($CheckerProducts[$product['id']]) ? $CheckerProducts[$product['id']] : array();
            }
        }
        
        return $report_data;
    }

    public function project_summary()
    {
        ini_set('memory_limit', '-1');
        error_reporting(-1);
        ini_set('display_errors', -1);

        if ($this->request->is('post')) {
            $report_input = $this->request->data;
            

            if (isset($report_input['Report']['from_date']) && $report_input['Report']['from_date'] && isset($report_input['Report']['to_date']) && $report_input['Report']['to_date']) {

                $conditions = array();

                $coms = $this->Com->find('list', array(
                    'fields' => array('Com.id', 'Com.name'),
                    'recursive' => 0
                ));
                $this->set('coms', $coms);

                $cus = $this->Customer->find('list', array(
                    'fields' => array('Customer.id', 'Customer.name'),
                    'recursive' => 0
                ));
                $this->set('cus', $cus);

                $cusgro = $this->Customergroup->find('list', array(
                    'fields' => array('Customergroup.id', 'Customergroup.name'),
                    'recursive' => 0
                ));
                $this->set('cusgro', $cusgro);

                $coun = $this->Country->find('list', array(
                    'fields' => array('Country.id', 'Country.name'),
                    'recursive' => 0
                ));
                $this->set('coun', $coun);

                $show_customer = '';
                $show_customer_code = '';
                $show_customer_group = '';
                $show_country = '';
                if (isset($report_input['CustomerGroup_id']) && $report_input['CustomerGroup_id']) {
                    //Lấy theo nhóm khách hàng
                    $conditions['Project.CustomerGroup_id'] = $report_input['CustomerGroup_id'];
                    $this->Customergroup->recursive = 0;
                    $cg = $this->Customergroup->find('first', array('conditions' => array('Customergroup.id' => $report_input['CustomerGroup_id'])));

                    $this->Customer->recursive = 0;
                    $cu = $this->Customer->find('first', array('conditions' => array('Customer.id' => $report_input['Customer_id'])));

                    $this->Country->recursive = 0;
                    $ct = $this->Country->find('first', array('conditions' => array('Country.id' => $report_input['Report']['Country_id'])));

                    $show_customer = $cu['Customer']['name'];
                    $show_customer_code = $cu['Customer']['customer_code'];
                    $show_customer_group = $cg['Customergroup']['name'];
                    $show_country = $ct['Country']['name'];


                } else if (isset($report_input['Customer_id']) && $report_input['Customer_id']) {
                    //Lấy theo khách hàng
                    $conditions['Project.Customer_id'] = $report_input['Customer_id'];

                    $this->Customer->recursive = 0;
                    $cu = $this->Customer->find('first', array('conditions' => array('Customer.id' => $report_input['Customer_id'])));

                    $this->Country->recursive = 0;
                    $ct = $this->Country->find('first', array('conditions' => array('Country.id' => $report_input['Report']['Country_id'])));

                    $show_customer = $cu['Customer']['name'];
                    $show_customer_code = $cu['Customer']['customer_code'];
                    $show_country = $ct['Country']['name'];

                } else if (isset($report_input['Report']['Country_id']) && $report_input['Report']['Country_id']) {
                    $this->Country->recursive = 0;
                    $ct = $this->Country->find('first', array('conditions' => array('Country.id' => $report_input['Report']['Country_id'])));
                    $show_country = $ct['Country']['name'];
                    //Lấy theo quóc gia
                    $customer = $this->Customer->find('list', array(
                        'fields' => array('Customer.id'),
                        'conditions' => array('Customer.Country_id' => $report_input['Report']['Country_id'])
                    ));
                    if (count($customer) > 0) {
                        $conditions['Project.Customer_id'] = $customer;
                    }
                }

                if (isset($report_input['Report']['Status_id']) && $report_input['Report']['Status_id']) {
                    $conditions['Project.Status_id'] = (int)$report_input['Report']['Status_id'];
                }

                if (isset($report_input['Report']['from_date']) && $report_input['Report']['from_date'] && isset($report_input['Report']['to_date']) && $report_input['Report']['to_date']) {

                    $fromdate = str_replace("/", "-", $report_input['Report']['from_date']);
                    $this->set('from_date', $fromdate);

                    $fromdate = new DateTime($fromdate . " 00:00:00");
                    $show_fromdate = $fromdate->format('d-m-Y');
                    $fromdate = $fromdate->format('Y-m-d H:i:s');
                    $todate = str_replace("/", "-", $report_input['Report']['to_date']);
                    $this->set('to_date', $todate);
                    $todate = new DateTime($todate . " 23:59:59");
                    $show_todate = $todate->format('d-m-Y');
                    $todate = $todate->format('Y-m-d H:i:s');


                    if (isset($report_input['Report']['Status_id']) && $report_input['Report']['Status_id']) {
                        if ($report_input['Report']['Status_id'] == 6) {
                            $conditions['Project.CompTime >='] = $fromdate;
                            $conditions['Project.CompTime <='] = $todate;

                        } elseif ($report_input['Report']['Status_id'] == 1) {
                            $conditions['Project.InputDate >='] = $fromdate;
                            $conditions['Project.InputDate <='] = $todate;
                        } else {
                            $conditions['OR'] = array(
                                array(
                                    'Project.CompTime >=' => $fromdate,
                                    'Project.CompTime <=' => $todate
                                ),
                                array(
                                    'Project.InputDate >=' => $fromdate,
                                    'Project.InputDate <=' => $todate
                                )
                            );
                        }
                    } else {
                        $conditions['OR'] = array(
                            array(
                                'Project.CompTime >=' => $fromdate,
                                'Project.CompTime <=' => $todate
                            ),
                            array(
                                'Project.InputDate >=' => $fromdate,
                                'Project.InputDate <=' => $todate
                            )
                        );
                    }

                }

                if (isset($report_input['Com_id']) && $report_input['Com_id']) {
                    $procom = $this->ProjectCom->find('list', array(
                            'fields' => array('Project_id'),
                            'conditions' => array('Com_id' => $report_input['Com_id']), 'recursive' => 0
                        )
                    );

                    if (!empty($procom) && is_array($procom)) {
                        $conditions['Project.ID'] = $procom;
                    } else {
                        $conditions['Project.ID'] = array('0');
                    }
                }

                $filename = "Pixelvn_MonthlyCustomerWorkflow_" . $show_country . "_" . $show_customer . "_" . $show_customer_group . "_" . $show_fromdate . "_" . $show_todate . ".xlsx";
                ob_end_clean();
                if (!isset($_GET['debug'])) {
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
                    header("Content-Type: application/force-download");
                    header("Content-Type: application/octet-stream");
                    header("Content-Type: application/download");
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                    header("Content-Transfer-Encoding: binary ");
                    header('Cache-Control: max-age=0');
                }
                /** report data */
                $report_data = $this->Project->find('all', array(
                        'fields' => array('ID', 'Name', 'InputDate', 'CompTime', 'Quantity', 'Status_id', 'IsActivated', 'RealQuantity', 'ActiveTime', 'InitSize', 'CompSize'),
                        'contain' => array(
                            'ProjectCom',
                            'Product' => array(
                                'fields' => array('id'),
                                /*'Working' => array(
                                    'fields' => array('process_time')
                                ),
                                'CheckerProduct' => array(
                                    'fields' => array('start_time', 'end_time'),
                                ),*/
                            ),
                            'CustomerGroup' => array(
                                'fields' => array('id', 'name')
                            ),
                            'Customer' => array(
                                'fields' => array('id', 'name', 'country_id'),
                                /*'Country' => array(
                                    'fields' => array('id', 'name')
                                )*/
                            ),


                        ),
                        'conditions' => $conditions
                    )
                );
                
                
                // $log = $this->Project->getDataSource()->getLog(false, false); die(json_encode($log));
//        debug($report_data);
//        die;
                $report_data = $this->fetchMoreData($report_data);
                
                // die(json_encode($report_data));

                if (!$report_input['Report']['type']) {
                    $report_input['Report']['type'] = 3;
                }
                $this->set('report_data', $report_data);
                $file = 'ExcelSource/V5_MonthlyCustomerWorkflow.xlsx';
                $excel = $this->PhpExcel->loadWorksheet($file);

                $excel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Times New Roman');
                $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(25);

                $i = 1;
                $begin = 7;

                $excel->getActiveSheet()->setCellValue('B3', $fromdate);
                $excel->getActiveSheet()->setCellValue('D3', $todate);
                $excel->getActiveSheet()->setCellValue('B4', ($show_customer) ? $show_customer : "All");
                $excel->getActiveSheet()->setCellValue('B5', ($show_customer) ? $show_customer_code : "All");
                $excel->getActiveSheet()->setCellValue('B6', ($show_customer_group) ? $show_customer_group : "All");

                if (count($report_data)) {
                    $row = 0;
                    $sttCom = "Q"; // Bắt đầu
                    $showCom = array();
                    $posCom = array();
                    $totalCom = array();

                    $total_init_size = 0;
                    $total_comp_size = 0;
                    $total_delevery_time = 0;
                    $total_process_time = 0;
                    $total_checking_time = 0;
                    $total_delevery_time_avg = 0;
                    $total_quantity = 0;
                    $total_quantity_real = 0;
                    $total_time = 0;
                    foreach ($report_data as $data) {
                        $row++;
                        $begin++;
                        $excel->getActiveSheet()->setCellValue('A' . $begin, $row);
                        $excel->getActiveSheet()->setCellValue('B' . $begin, $data['Project']['Name']);
                        $excel->getActiveSheet()->setCellValue('C' . $begin, $cus[$data['Customer']['id']]);
                        if (isset($data['CustomerGroup']['id']) && $data['CustomerGroup']['id'] && isset($cusgro[$data['CustomerGroup']['id']]) && $cusgro[$data['CustomerGroup']['id']]) {
                            $excel->getActiveSheet()->setCellValue('D' . $begin, $cusgro[$data['CustomerGroup']['id']]);
                        }


                        $excel->getActiveSheet()->setCellValue('E' . $begin, $data['Customer']['Country']['name']);
                        $excel->getActiveSheet()->setCellValue('F' . $begin, $data['Project']['InputDate']);
                        $excel->getActiveSheet()->setCellValue('G' . $begin, $data['Project']['CompTime']);
                        $excel->getActiveSheet()->setCellValue('H' . $begin, $data['Project']['Quantity']);
                        $excel->getActiveSheet()->setCellValue('I' . $begin, $data['Project']['RealQuantity']);
                        $total_quantity += $data['Project']['Quantity'];
                        $total_quantity_real += $data['Project']['RealQuantity'];
                        $deliver_time = 0;
                        $t_a = 0;
                        $t_b = 0;


                        if ($data['Project']['CompTime']) {
                            if ($data['Project']['ActiveTime'] && $data['Project']['ActiveTime'] != "0000-00-00 00:00:00") {
                                $date_a = new DateTime($data['Project']['ActiveTime']);
                                $t_a = $data['Project']['ActiveTime'];
                                $t_b = $data['Project']['CompTime'];
                            } else {
                                $date_a = new DateTime($data['Project']['InputDate']);
                                $date_b = new DateTime($data['Project']['CompTime']);
                                $t_a = $data['Project']['InputDate'];
                                $t_b = $data['Project']['CompTime'];

                            }


                            if ($t_b && $t_a) {
                                $days = floor($deliver_time / 86400);
                                $hours = floor(($deliver_time - ($days * 86400)) / 3600);
                                $minutes = floor(($deliver_time - ($days * 86400) - ($hours * 3600)) / 60);
                                $seconds = floor(($deliver_time - ($days * 86400) - ($hours * 3600) - ($minutes * 60)));
                                $total_delevery_time += $deliver_time;
                                $deliver_time = $hours . ":" . $minutes . ":" . $seconds;

                            }
                            //Report type
                            if ($report_input['Report']['type'] == 1) {
                                $excel->getActiveSheet()->setCellValue('J' . $begin, $deliver_time);
                            } else {
                                $excel->getActiveSheet()->getColumnDimension('J')->setVisible(false);
                                $excel->getActiveSheet()->getColumnDimension('O')->setVisible(false);
                                $excel->getActiveSheet()->getColumnDimension('P')->setVisible(false);
                            }
                        }


                        $processTime = $checkingTime = 0;
                        $avg_process_time = 0;
                        $avg_checking_time = 0;
                        foreach ($data['Product'] as $prod) {
                            if (isset($prod['CheckerProduct'][count($prod['CheckerProduct']) - 1]['start_time']) && isset($prod['CheckerProduct'][count($prod['CheckerProduct']) - 1]['end_time']) && ($prod['CheckerProduct'][count($prod['CheckerProduct']) - 1]['end_time'] != "0000-00-00 00:00:00")) {
                                $date_a = $prod['CheckerProduct'][count($prod['CheckerProduct']) - 1]['start_time'];
                                $date_b = $prod['CheckerProduct'][count($prod['CheckerProduct']) - 1]['end_time'];
                                if ((strtotime($date_b) - strtotime($date_a)) > 0) {
                                    $checkingTime += strtotime($date_b) - strtotime($date_a);
                                }
                            }
                            if (count($prod['Working'])) {
                                $processTime += $prod['Working'][count($prod['Working']) - 1]['process_time'];
                            }
                        }
                        $total_process_time += $processTime;
                        $total_checking_time += $checkingTime;
                        if ($data['Project']['RealQuantity']) {
                            $avg_process_time = ceil($processTime / ($data['Project']['RealQuantity']));
                            $avg_checking_time = ceil($checkingTime / ($data['Project']['RealQuantity']));
                        }

                        $days = 0;
                        $hours = floor(($processTime - ($days * 86400)) / 3600);
                        $minutes = floor(($processTime - ($days * 86400) - ($hours * 3600)) / 60);
                        $seconds = floor(($processTime - ($days * 86400) - ($hours * 3600) - ($minutes * 60)));
                        $processTime = $hours . ":" . $minutes . ":" . $seconds;
                        if ($report_input['Report']['type'] < 3) {
                            $excel->getActiveSheet()->setCellValue('K' . $begin, $processTime);
                        } else {
                            $excel->getActiveSheet()->getColumnDimension('K')->setVisible(false);
                        }
                        $hours = floor(($avg_process_time - ($days * 86400)) / 3600);
                        $minutes = floor(($avg_process_time - ($days * 86400) - ($hours * 3600)) / 60);
                        $seconds = floor(($avg_process_time - ($days * 86400) - ($hours * 3600) - ($minutes * 60)));
                        $avg_process_time = $hours . ":" . $minutes . ":" . $seconds;
                        if ($report_input['Report']['type'] < 3) {
                            $excel->getActiveSheet()->setCellValue('L' . $begin, $avg_process_time);
                        } else {
                            $excel->getActiveSheet()->getColumnDimension('L')->setVisible(false);
                        }
                        $hours = floor(($checkingTime - ($days * 86400)) / 3600);
                        $minutes = floor(($checkingTime - ($days * 86400) - ($hours * 3600)) / 60);
                        $seconds = floor(($checkingTime - ($days * 86400) - ($hours * 3600) - ($minutes * 60)));
                        $checkingTime = $hours . ":" . $minutes . ":" . $seconds;
                        if ($report_input['Report']['type'] < 3) {
                            $excel->getActiveSheet()->setCellValue('M' . $begin, $checkingTime);
                        } else {
                            $excel->getActiveSheet()->getColumnDimension('M')->setVisible(false);
                        }
                        $hours = floor(($avg_checking_time - ($days * 86400)) / 3600);
                        $minutes = floor(($avg_checking_time - ($days * 86400) - ($hours * 3600)) / 60);
                        $seconds = floor(($avg_checking_time - ($days * 86400) - ($hours * 3600) - ($minutes * 60)));
                        $avg_checking_time = $hours . ":" . $minutes . ":" . $seconds;
                        if ($report_input['Report']['type'] < 3) {
                            $excel->getActiveSheet()->setCellValue('N' . $begin, $avg_checking_time);
                        } else {
                            $excel->getActiveSheet()->getColumnDimension('N')->setVisible(false);

                        }
                        //Hiển thị các cột comp

                        foreach ($data['ProjectCom'] as $com) {
//                            if(!isset($totalCom[$com['Com_id']])){
//                                $totalCom[$com['Com_id']] = 0;
//                            }
                            if ($com['Quantity'] > 0 && !in_array($com['Com_id'], $showCom)) {
                                $excel->getActiveSheet()->setCellValue($sttCom . '7', $coms[$com['Com_id']]);
                                $excel->getActiveSheet()->setCellValue($sttCom . $begin, $com['Quantity']);

                                $showCom[] = $com['Com_id'];
                                $posCom[$com['Com_id']] = $sttCom;
                                $sttCom++;
                            } else {
                                if ($com['Quantity'] > 0) {
                                    $excel->getActiveSheet()->setCellValue($posCom[$com['Com_id']] . $begin, $com['Quantity']);
                                }
                            }

//                            $totalCom[$com['Com_id']] += $com['Quantity'];
                        }

                        $total_init_size += $data['Project']['InitSize'];
                        $total_comp_size += $data['Project']['CompSize'];
//                         $excel->getActiveSheet()->setCellValue( $posCom[$com['Com_id']]. $begin+1, $totalCom[$com['Com_id']]);

                    }

                    $begin++;
                    $excel->getActiveSheet()->setCellValue('G' . $begin, 'Total');
                    $excel->getActiveSheet()->setCellValue('H' . $begin, $total_quantity);
                    $excel->getActiveSheet()->setCellValue('I' . $begin, $total_quantity_real);

                    $begin++;
                    $begin++;
                    $begin++;
                    $excel->getActiveSheet()->setCellValue('E' . $begin, 'Sumary');
                    $total_delevery_time_avg = ($total_delevery_time / $row);


                    if ($report_input['Report']['type'] == 1) {
                        $begin++;
                        $excel->getActiveSheet()->setCellValue('E' . $begin, 'Total Size input');
                        if ($total_init_size < 1073741824) {
                            $excel->getActiveSheet()->setCellValue('F' . $begin, round($total_init_size / 1024 / 1024, 2) . " MB");
                        } else {
                            $excel->getActiveSheet()->setCellValue('F' . $begin, round($total_init_size / 1024 / 1024 / 1024, 2) . " GB");

                        }

                        $begin++;
                        $excel->getActiveSheet()->setCellValue('E' . $begin, 'Total Size Output');
                        if ($total_comp_size < 1073741824) {
                            $excel->getActiveSheet()->setCellValue('F' . $begin, round($total_comp_size / 1024 / 1024, 2) . " MB");
                        } else {
                            $excel->getActiveSheet()->setCellValue('F' . $begin, round($total_comp_size / 1024 / 1024 / 1024, 2) . " GB");
                        }
                        $begin++;
                        $hours = floor(($total_delevery_time) / 3600);
                        $minutes = floor(($total_delevery_time - ($hours * 3600)) / 60);
                        $seconds = floor(($total_delevery_time - ($hours * 3600) - ($minutes * 60)));
                        $total_delevery_time = $hours . ":" . $minutes . ":" . $seconds;
                        $excel->getActiveSheet()->setCellValue('E' . $begin, 'Delivery time');
                        $excel->getActiveSheet()->setCellValue('F' . $begin, $total_delevery_time);


                        $begin++;
                        $hours = floor(($total_delevery_time_avg) / 3600);
                        $minutes = floor(($total_delevery_time_avg - ($hours * 3600)) / 60);
                        $seconds = floor(($total_delevery_time_avg - ($hours * 3600) - ($minutes * 60)));
                        $total_delevery_time_avg = $hours . ":" . $minutes . ":" . $seconds;
                        $excel->getActiveSheet()->setCellValue('E' . $begin, 'Averager Delivery time');
                        $excel->getActiveSheet()->setCellValue('F' . $begin, $total_delevery_time_avg);

                    }
                    $begin++;
                    $hours = floor(($total_process_time) / 3600);
                    $minutes = floor(($total_process_time - ($hours * 3600)) / 60);
                    $seconds = floor(($total_process_time - ($hours * 3600) - ($minutes * 60)));
                    $stotal_process_time = $hours . ":" . $minutes . ":" . $seconds;
                    $excel->getActiveSheet()->setCellValue('E' . $begin, 'Total. Processing time');
                    $excel->getActiveSheet()->setCellValue('F' . $begin, $stotal_process_time);

                    $total_process_time_avg = $total_process_time / $total_quantity_real;
                    $begin++;
                    $hours = floor(($total_process_time_avg) / 3600);
                    $minutes = floor(($total_process_time_avg - ($hours * 3600)) / 60);
                    $seconds = floor(($total_process_time_avg - ($hours * 3600) - ($minutes * 60)));
                    $total_process_time_avg = $hours . ":" . $minutes . ":" . $seconds;
                    $excel->getActiveSheet()->setCellValue('E' . $begin, 'Pro.File.Average');
                    $excel->getActiveSheet()->setCellValue('F' . $begin, $total_process_time_avg);


                    $total_process_time_project = $total_process_time / $row;
                    $begin++;
                    $hours = floor(($total_process_time_project) / 3600);
                    $minutes = floor(($total_process_time_project - ($hours * 3600)) / 60);
                    $seconds = floor(($total_process_time_project - ($hours * 3600) - ($minutes * 60)));
                    $total_process_time_project = $hours . ":" . $minutes . ":" . $seconds;
                    $excel->getActiveSheet()->setCellValue('E' . $begin, 'Pro.Order Average');
                    $excel->getActiveSheet()->setCellValue('F' . $begin, $total_process_time_project);


                    $begin++;
                    $total_checking_time_avg = ceil($total_checking_time / $total_quantity_real);
                    $total_checking_time_project = ceil($total_checking_time / $row);
                    $hours = floor(($total_checking_time) / 3600);
                    $minutes = floor(($total_checking_time - ($hours * 3600)) / 60);
                    $seconds = floor(($total_checking_time - ($hours * 3600) - ($minutes * 60)));
                    $stotal_checking_time = $hours . ":" . $minutes . ":" . $seconds;
                    $excel->getActiveSheet()->setCellValue('E' . $begin, 'Total Checking time');
                    $excel->getActiveSheet()->setCellValue('F' . $begin, $stotal_checking_time);

                    $begin++;
                    $hours = floor(($total_checking_time_avg) / 3600);
                    $minutes = floor(($total_checking_time_avg - ($hours * 3600)) / 60);
                    $seconds = floor(($total_checking_time_avg - ($hours * 3600) - ($minutes * 60)));
                    $total_checking_time_avg = $hours . ":" . $minutes . ":" . $seconds;
                    $excel->getActiveSheet()->setCellValue('E' . $begin, 'Check.File.Average');
                    $excel->getActiveSheet()->setCellValue('F' . $begin, $total_checking_time_avg);

                    $begin++;
                    $hours = floor(($total_checking_time_project) / 3600);
                    $minutes = floor(($total_checking_time_project - ($hours * 3600)) / 60);
                    $seconds = floor(($total_checking_time_project - ($hours * 3600) - ($minutes * 60)));
                    $stotal_checking_time_project = $hours . ":" . $minutes . ":" . $seconds;
                    $excel->getActiveSheet()->setCellValue('E' . $begin, 'Chec.Order.Average');
                    $excel->getActiveSheet()->setCellValue('F' . $begin, $stotal_checking_time_project);

                    $begin++;
                    $total_time = $total_checking_time + $total_process_time;
                    $total_time_avg = ceil($total_time / $total_quantity_real);
                    $total_time_project = ceil($total_time / $row);

                    $hours = floor(($total_time) / 3600);
                    $minutes = floor(($total_time - ($hours * 3600)) / 60);
                    $seconds = floor(($total_time - ($hours * 3600) - ($minutes * 60)));
                    $total_time = $hours . ":" . $minutes . ":" . $seconds;

                    $excel->getActiveSheet()->setCellValue('E' . $begin, 'Total Time');
                    $excel->getActiveSheet()->setCellValue('F' . $begin, $total_time);

                    $begin++;
                    $hours = floor(($total_time_avg) / 3600);
                    $minutes = floor(($total_time_avg - ($hours * 3600)) / 60);
                    $seconds = floor(($total_time_avg - ($hours * 3600) - ($minutes * 60)));
                    $total_time_avg = $hours . ":" . $minutes . ":" . $seconds;
                    $excel->getActiveSheet()->setCellValue('E' . $begin, 'Time.File.Average');
                    $excel->getActiveSheet()->setCellValue('F' . $begin, $total_time_avg);

                    $begin++;
                    $hours = floor(($total_time_project) / 3600);
                    $minutes = floor(($total_time_project - ($hours * 3600)) / 60);
                    $seconds = floor(($total_time_project - ($hours * 3600) - ($minutes * 60)));
                    $total_time_project = $hours . ":" . $minutes . ":" . $seconds;
                    $excel->getActiveSheet()->setCellValue('E' . $begin, 'Time.Order.Average');
                    $excel->getActiveSheet()->setCellValue('F' . $begin, $total_time_project);

                    $begin++;

                }


                $excel->getActiveSheet()->setTitle('Workflow report');
                $excel->setActiveSheetIndex(0);
                //$excel = new PHPExcel();

                //debug($excel);


                //$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
                if (isset($_GET['debug']) && isset($_GET['debug']) == 2) return;
                if (isset($_GET['debug'])) die;
                $this->PhpExcel->output($filename);
                die('xxx');
            } else {
                $this->redirect('/reports/');
            }
        } else {
            $this->redirect('/reports/');
        }
    }

    public function products()
    {
        ini_set('memory_limit', '-1');
        error_reporting(0);
        ini_set('display_errors', 0);

        if ($this->request->is('post')) {
            $report_input = $this->request->data;

            if (isset($report_input['Report']['from_date']) && $report_input['Report']['from_date'] && isset($report_input['Report']['to_date']) && $report_input['Report']['to_date']) {

                $conditions = array();

                $coms = $this->Com->find('list', array(
                    'fields' => array('Com.id', 'Com.name'),
                    'recursive' => 0
                ));
                $this->set('coms', $coms);

                $cus = $this->Customer->find('list', array(
                    'fields' => array('Customer.id', 'Customer.name'),
                    'recursive' => 0
                ));
                $this->set('cus', $cus);

                $cusgro = $this->Customergroup->find('list', array(
                    'fields' => array('Customergroup.id', 'Customergroup.name'),
                    'recursive' => 0
                ));
                $this->set('cusgro', $cusgro);

                $coun = $this->Country->find('list', array(
                    'fields' => array('Country.id', 'Country.name'),
                    'recursive' => 0
                ));
                $this->set('coun', $coun);

                $show_customer = '';
                $show_customer_code = '';
                $show_customer_group = '';
                $show_country = '';
                if (isset($report_input['CustomerGroup_id']) && $report_input['CustomerGroup_id']) {
                    //Lấy theo nhóm khách hàng
                    $conditions['Project.CustomerGroup_id'] = $report_input['CustomerGroup_id'];
                    $this->Customergroup->recursive = 0;
                    $cg = $this->Customergroup->find('first', array('conditions' => array('Customergroup.id' => $report_input['CustomerGroup_id'])));

                    $this->Customer->recursive = 0;
                    $cu = $this->Customer->find('first', array('conditions' => array('Customer.id' => $report_input['Customer_id'])));

                    $this->Country->recursive = 0;
                    $ct = $this->Country->find('first', array('conditions' => array('Country.id' => $report_input['Report']['Country_id'])));

                    $show_customer = $cu['Customer']['name'];
                    $show_customer_code = $cu['Customer']['customer_code'];
                    $show_customer_group = $cg['Customergroup']['name'];
                    $show_country = $ct['Country']['name'];


                } else if (isset($report_input['Customer_id']) && $report_input['Customer_id']) {
                    //Lấy theo khách hàng
                    $conditions['Project.Customer_id'] = $report_input['Customer_id'];

                    $this->Customer->recursive = 0;
                    $cu = $this->Customer->find('first', array('conditions' => array('Customer.id' => $report_input['Customer_id'])));

                    $this->Country->recursive = 0;
                    $ct = $this->Country->find('first', array('conditions' => array('Country.id' => $report_input['Report']['Country_id'])));

                    $show_customer = $cu['Customer']['name'];
                    $show_customer_code = $cu['Customer']['customer_code'];
                    $show_country = $ct['Country']['name'];

                } else if (isset($report_input['Report']['Country_id']) && $report_input['Report']['Country_id']) {
                    $this->Country->recursive = 0;
                    $ct = $this->Country->find('first', array('conditions' => array('Country.id' => $report_input['Report']['Country_id'])));
                    $show_country = $ct['Country']['name'];
                    //Lấy theo quóc gia
                    $customer = $this->Customer->find('list', array(
                        'fields' => array('Customer.id'),
                        'conditions' => array('Customer.Country_id' => $report_input['Report']['Country_id'])
                    ));
                    if (count($customer) > 0) {
                        $conditions['Project.Customer_id'] = $customer;
                    }
                }

                if (isset($report_input['Report']['Status_id']) && $report_input['Report']['Status_id']) {
                    $conditions['Project.Status_id'] = (int)$report_input['Report']['Status_id'];
                }

                if (isset($report_input['Report']['from_date']) && $report_input['Report']['from_date'] && isset($report_input['Report']['to_date']) && $report_input['Report']['to_date']) {

                    $fromdate = str_replace("/", "-", $report_input['Report']['from_date']);
                    $this->set('from_date', $fromdate);

                    $fromdate = new DateTime($fromdate . " 00:00:00");
                    $show_fromdate = $fromdate->format('d-m-Y');
                    $fromdate = $fromdate->format('Y-m-d H:i:s');
                    $todate = str_replace("/", "-", $report_input['Report']['to_date']);
                    $this->set('to_date', $todate);
                    $todate = new DateTime($todate . " 23:59:59");
                    $show_todate = $todate->format('d-m-Y');
                    $todate = $todate->format('Y-m-d H:i:s');


                    if (isset($report_input['Report']['Status_id']) && $report_input['Report']['Status_id']) {
                        if ($report_input['Report']['Status_id'] == 6) {
                            $conditions['Project.CompTime >='] = $fromdate;
                            $conditions['Project.CompTime <='] = $todate;

                        } elseif ($report_input['Report']['Status_id'] == 1) {
                            $conditions['Project.InputDate >='] = $fromdate;
                            $conditions['Project.InputDate <='] = $todate;
                        } else {
                            $conditions['OR'] = array(
                                array(
                                    'Project.CompTime >=' => $fromdate,
                                    'Project.CompTime <=' => $todate
                                ),
                                array(
                                    'Project.InputDate >=' => $fromdate,
                                    'Project.InputDate <=' => $todate
                                )
                            );
                        }
                    } else {
                        $conditions['OR'] = array(
                            array(
                                'Project.CompTime >=' => $fromdate,
                                'Project.CompTime <=' => $todate
                            ),
                            array(
                                'Project.InputDate >=' => $fromdate,
                                'Project.InputDate <=' => $todate
                            )
                        );
                    }

                }

                if (isset($report_input['Com_id']) && $report_input['Com_id']) {
                    $procom = $this->ProjectCom->find('list', array(
                            'fields' => array('Project_id'),
                            'conditions' => array('Com_id' => $report_input['Com_id']), 'recursive' => 0
                        )
                    );

                    if (!empty($procom) && is_array($procom)) {
                        $conditions['Project.ID'] = $procom;
                    } else {
                        $conditions['Project.ID'] = array('0');
                    }
                }
                $filename = "Pixelvn_Product_general" . $show_country . "_" . $show_customer . "_" . $show_customer_group . "_" . $show_fromdate . "_" . $show_todate . ".xlsx";
                ob_end_clean();
                if (!isset($_GET['debug'])) {
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
                    header("Content-Type: application/force-download");
                    header("Content-Type: application/octet-stream");
                    header("Content-Type: application/download");
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                    header("Content-Transfer-Encoding: binary ");
                    header('Cache-Control: max-age=0');

                }
                $file = 'ExcelSource/V5_Product_Report.xlsx';
                $excel = $this->PhpExcel->loadWorksheet($file);
                $excel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Times New Roman');
                $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(25);

                $row = 0;
                $begin = 7;

                $excel->getActiveSheet()->setCellValue('B3', $fromdate);
                $excel->getActiveSheet()->setCellValue('D3', $todate);
                $excel->getActiveSheet()->setCellValue('B4', ($show_customer) ? $show_customer : "All");
                $excel->getActiveSheet()->setCellValue('B5', ($show_customer) ? $show_customer_code : "All");
                $excel->getActiveSheet()->setCellValue('B6', ($show_customer_group) ? $show_customer_group : "All");

                $report_data = $this->Project->find('all', array(
                        'recursive' => -1,
                        'fields' => array('ID', 'Name', 'InputDate', 'CompTime', 'Quantity', 'Status_id', 'IsActivated', 'RealQuantity', 'ActiveTime', 'InitSize', 'CompSize'),
                        'conditions' => $conditions
                    )
                );
                $projectIDs = array();
                foreach ($report_data as $data) {
                    $projectIDs[] = $data['Project']['ID'];
                }


                //Lấy toàn bộ sản phẩm
                $products = $this->Product->find('all', array(
                    'conditions' => array(
                        'project_id' => $projectIDs
                    ),
                    'contain' => array(
                        'Project' => array(
                            'CustomerGroup' => array(
                                'fields' => array('id', 'name')
                            ),
                            'Customer' => array(
                                'fields' => array('id', 'name', 'customer_code'),
                                'Country' => array(
                                    'fields' => array('id', 'name')
                                )
                            ),
                        ),
                        'Processtype',
                        'Performer' => array(
                            'Department'
                        ),
                        'Working',
                        'CheckerProduct',
                        'Reject'

                    )
                ));
                $total_time_process = 0;
                $total_checking_time = 0;
                $total_err = 0;
                $total_files = 0;
                $company_reject_time_total = $customer_reject_time_total = $extreme_reject_time_total = 0;
                foreach ($products as $product) {
                    $row++;
                    $total_files++;
                    $begin++;
                    $excel->getActiveSheet()->setCellValue('A' . $begin, $row);
                    $excel->getActiveSheet()->setCellValue('B' . $begin, $product['Project']['Name']);
                    $excel->getActiveSheet()->setCellValue('C' . $begin, str_replace("@", "", $product['Product']['sub_folder']));
                    $excel->getActiveSheet()->setCellValue('D' . $begin, $product['Product']['name_file_product']);
                    $excel->getActiveSheet()->setCellValue('E' . $begin, $product['Project']['Customer']['customer_code']);
                    $excel->getActiveSheet()->setCellValue('F' . $begin, $product['Project']['CustomerGroup']['name']);
                    $excel->getActiveSheet()->setCellValue('G' . $begin, $product['Product']['deliver_date']);
                    $excel->getActiveSheet()->setCellValue('H' . $begin, $product['Product']['date_of_completion']);
                    $excel->getActiveSheet()->setCellValue('J' . $begin, $product['Processtype']['name']);

                    $excel->getActiveSheet()->setCellValue('L' . $begin, $product['Performer']['Department']['name']);
                    $excel->getActiveSheet()->setCellValue('M' . $begin, $product['Performer']['username']);
                    $total_time_process += $product['Working'][0]['process_time'];
                    $excel->getActiveSheet()->setCellValue('N' . $begin, $this->show_time_from_second($product['Working'][0]['process_time']));
                    $excel->getActiveSheet()->setCellValue('O' . $begin, ceil($product['Working'][0]['process_time'] / 60));
                    if (isset($product['CheckerProduct'][0])) {
                        $date_a = $product['CheckerProduct'][0]['start_time'];
                        $date_b = $product['CheckerProduct'][0]['end_time'];
                        if (strtotime($date_b) > strtotime($date_a)) {
                            $total_checking_time += strtotime($date_b) - strtotime($date_a);
                            $excel->getActiveSheet()->setCellValue('P' . $begin, $this->show_time_from_second(strtotime($date_b) - strtotime($date_a)));
                        }
                    }
                    $company_reject_time = $customer_reject_time = $extreme_reject_time = 0;
                    if (isset($product['Reject']) && count($product['Reject'])) {
                        foreach ($product['Reject'] as $rej) {
                            //Chỉ lấy các reject làm hàng có Action_id = 100
                            if ($rej['rate_point_id'] == 1) {
                                $company_reject_time++;
                            } elseif ($rej['rate_point_id'] == 2) {
                                $customer_reject_time++;
                            } else {
                                $extreme_reject_time++;
                            }
                            $total_err++;
                            $company_reject_time_total++;
                            $customer_reject_time_total++;
                            $extreme_reject_time_total++;
                        }
                    }

                    $excel->getActiveSheet()->setCellValue('Q' . $begin, $company_reject_time);
                    $excel->getActiveSheet()->setCellValue('R' . $begin, $customer_reject_time);
                    $excel->getActiveSheet()->setCellValue('S' . $begin, $extreme_reject_time);


                }

                $begin++;
                $begin++;
                $begin++;
                $begin++;
                $excel->getActiveSheet()->setCellValue('C' . $begin, "No of order");
                $excel->getActiveSheet()->setCellValue('D' . $begin, count($projectIDs));
                $begin++;
                $excel->getActiveSheet()->setCellValue('C' . $begin, "No of file");
                $excel->getActiveSheet()->setCellValue('D' . $begin, $total_files);
                $begin++;
                $excel->getActiveSheet()->setCellValue('C' . $begin, "Processing time");
                $excel->getActiveSheet()->setCellValue('D' . $begin, $this->show_time_from_second($total_time_process));
                $begin++;
                $excel->getActiveSheet()->setCellValue('C' . $begin, "Checking time");
                $excel->getActiveSheet()->setCellValue('D' . $begin, $this->show_time_from_second($total_checking_time));
                $begin++;
                $excel->getActiveSheet()->setCellValue('C' . $begin, "Total time");
                $excel->getActiveSheet()->setCellValue('D' . $begin, $this->show_time_from_second($total_checking_time + $total_time_process));
                $begin++;
                $excel->getActiveSheet()->setCellValue('C' . $begin, "Processing time Average");
                $excel->getActiveSheet()->setCellValue('D' . $begin, $this->show_time_from_second(ceil($total_time_process / $total_files)));
                $begin++;
                $excel->getActiveSheet()->setCellValue('C' . $begin, "Checking time Average");
                $excel->getActiveSheet()->setCellValue('D' . $begin, $this->show_time_from_second(ceil($total_checking_time / $total_files)));
                $begin++;
                $excel->getActiveSheet()->setCellValue('C' . $begin, "Time Average");
                $excel->getActiveSheet()->setCellValue('D' . $begin, $this->show_time_from_second(ceil(($total_checking_time + $total_time_process) / $total_files)));
                $begin++;
                $excel->getActiveSheet()->setCellValue('C' . $begin, "No of Admin rejected");
                $excel->getActiveSheet()->setCellValue('D' . $begin, $company_reject_time_total);
                $begin++;
                $excel->getActiveSheet()->setCellValue('C' . $begin, "No of Customer rejected");
                $excel->getActiveSheet()->setCellValue('D' . $begin, $customer_reject_time_total);
                $begin++;
                $excel->getActiveSheet()->setCellValue('C' . $begin, "No of Extreme rejected");
                $excel->getActiveSheet()->setCellValue('D' . $begin, $extreme_reject_time_total);
                $begin++;
                $excel->getActiveSheet()->setCellValue('C' . $begin, "Avere Adm. Rejected");
                $excel->getActiveSheet()->setCellValue('D' . $begin, round($company_reject_time_total / $total_files, 2));
                $begin++;
                $excel->getActiveSheet()->setCellValue('C' . $begin, "Avere Cust. Rejected");
                $excel->getActiveSheet()->setCellValue('D' . $begin, round($customer_reject_time_total / $total_files, 2));
                $begin++;
                $excel->getActiveSheet()->setCellValue('C' . $begin, "Avere Extr. Rejected");
                $excel->getActiveSheet()->setCellValue('D' . $begin, round($extreme_reject_time_total / $total_files, 2));
                $begin++;


                if (isset($_GET['debug'])) die;
                $this->PhpExcel->output($filename);

            } else {
                $this->redirect('/reports/');
            }
        } else {
            $this->redirect('/reports/');
        }
        $this->set('products', $products);
    }

    public function vacation()
    {
        ini_set('memory_limit', '-1');
        error_reporting(0);
        ini_set('display_errors', 0);


        // Tinh toan ngay nghi cua mot nhan vien trong mot nam
        $ngaynghi = array();
        $vacation = new TimeloginsController;
        $flag = false;
        if ($this->request->data['Report']['datetime']['month'] == '') {
            $vacations = $this->Vacation->find('all', array('conditions' => array(
                'Vacation.user_id' => $this->request->data['user_id'],
                'YEAR(Vacation.to_date)' => $this->request->data['Report']['datetime']['year']),
                'recursive' => 0,
            ));
//            debug($vacations);die;
            if ($vacations != '') {
                foreach ($vacations as $vc) {
                    $ngaynghi[$vc['Vacationtype']['id']][0] = $vc['Vacationtype']['name'];
                    for ($i = 1; $i <= 12; $i++) {
                        $day = $vacation->get_vacation($this->request->data['user_id'], $i, $this->request->data['Report']['datetime']['year']);
                        $tmp = explode("/", $day);
                        @$ngaynghi[$vc['Vacationtype']['id']][$i] += $tmp[0] + $tmp[1];
                    }
                }
                $name = $this->User->find('all', array('conditions' => array(
                    'User.id' => $this->request->data['user_id']
                )));
//                debug($name[0]['User']['name']);die;

                $this->set('nameNV', $name[0]['User']['name']);
            }
        } else {
            // Tinh toan ngay nghi cua tat ca nhan vien
            $vacations = $this->Vacation->find('all', array('conditions' => array(
                'YEAR(Vacation.to_date)' => $this->request->data['Report']['datetime']['year']),
                'recursive' => 2,
            ));
            foreach ($vacations as $vc) {
                $ngaynghi[$vc['User']['id']][0] = $vc['User']['name'];
                for ($i = 1; $i <= 12; $i++) {
                    $day = $vacation->get_vacation($vc['User']['id'], $i, $this->request->data['Report']['datetime']['year']);
                    $tmp = explode("/", $day);
                    $allDay = $tmp[0] + $tmp[1];
                    @$ngaynghi[$vc['User']['id']][1] += $allDay;
                }
                $ngaynghi[$vc['User']['id']][2] = ($vc['User']['start_work_day'] == null) ? "N/A" : $vc['User']['start_work_day'];
            }
            $flag = true;
        }
//        debug($vacations[0]['User']['name']);
//        debug($ngaynghi);
//        die;
        $this->set(compact('ngaynghi', 'flag'));


    }

    public function projects()
    {
        ini_set('memory_limit', '-1');
        error_reporting(0);
        ini_set('display_errors', 0);

        debug($this->request->data);
        $projects = $this->Project->find('all', array('conditions' => array('Project.Customer_id' => $this->request->data['Customer_id'], 'Project.returnTime LIKE ' => '%' . $this->request->data['Report']['datetime']['year'] . '%', 'MONTH(Project.returnTime)' => $this->request->data['Report']['datetime']['month'], 'Project.CustomerGroup_id' => $this->request->data['CustomerGroup_id'])));
        debug($projects);
        die;
    }


    public function activity()
    {

    }

    public function files()
    {
        debug($this->request->data);
        die;
    }

//Báo cáo tổng hợp D.Mục Sản Phẩm(năm)
    public function productCategories()
    {
        ini_set('memory_limit', '-1');
        error_reporting(0);
        ini_set('display_errors', 0);

        $danhmuc = array();
//        debug($this->request->data);
        $products = $this->Product->find('all', array('conditions' => array('Product.product_category_id' => $this->request->data['Report']['product_category_id'], 'YEAR(Product.date_of_completion)' => $this->request->data['Report']['datetime']['year'])));
        debug($products);
        foreach ($products as $sp) {
            $danhmuc[$sp['Producttype']['id']][0] = $sp['Productcategory']['name'];
            $danhmuc[$sp['Producttype']['id']][13] = $sp['Producttype']['name'];
            for ($i = 1; $i <= 12; $i++) {
                $month = explode('-', $sp['Product']['date_of_completion']);
                $count = $this->Product->find('count', array('conditions' => array(
                    'Product.product_category_id' => $this->request->data['Report']['product_category_id'],
                    'YEAR(Product.date_of_completion)' => $this->request->data['Report']['datetime']['year'],
                    'MONTH(Product.date_of_completion)' => $i,
                )));
                if ($month[1] == $i) {
                    @$danhmuc[$sp['Producttype']['id']][$i] += $count;
                } else {
                    @$danhmuc[$sp['Producttype']['id']][$i] = 0;
                }

            }
        }
        debug($danhmuc);
        die;
        $this->set('products', $products);
    }

    public function customers()
    {
        ini_set('memory_limit', '-1');
        error_reporting(0);
        ini_set('display_errors', 0);

        debug($this->request->data);
        $customers = $this->Product->find('all', array('conditions' => array(
            'Project.customer_id' => $this->request->data['Customer_id'],
            'YEAR(Project.returnTime)' => $this->request->data['Report']['datetime']['year']
        )));
//        debug($customers);die;
        $khachhang = array();
        foreach ($customers as $ct) {
            for ($i = 1; $i <= 12; $i++) {
                $sp = $this->Project->find('count', array('conditions' => array(
                    'CustomerGroup.customer_id' => $this->request->data['Customer_id'],
                    'YEAR(Project.returnTime)' => $this->request->data['Report']['datetime']['year'],
                    'MONTH(Project.returnTime)' => $i
                )));
                $khachhang['Data'][$i] = $sp;
            }
        }
        debug($khachhang);
        die;
//        $customers = $this->Product->find('all');
//        debug($customers);die;

    }

//Báo cáo tổng hợp nhân viên theo năm
    public function collectionEmployee()
    {
        ini_set('memory_limit', '-1');
        error_reporting(0);
        ini_set('display_errors', 0);

//        debug($this->request->data);
        $workings = $this->Working->find('all', array('conditions' => array(
            'Working.user_id' => $this->request->data['user_id'],
            'YEAR(Working.end_time)' => $this->request->data['Report']['datetime']['year']
        )));
        $employ = array();
        foreach ($workings as $ct) {
            for ($i = 1; $i <= 12; $i++) {
                $files = $this->Working->find('count', array('conditions' => array(
                    'Working.user_id' => $this->request->data['user_id'],
                    'YEAR(Working.end_time)' => $this->request->data['Report']['datetime']['year'],
                    'MONTH(Working.end_time)' => $i
                )));
                @$employ['FILE'][$i] = $files;
                $times = $this->Working->find('all', array(
                    'conditions' => array(
                        'Working.user_id' => $this->request->data['user_id'],
                        'YEAR(Working.end_time)' => $this->request->data['Report']['datetime']['year'],
                        'MONTH(Working.end_time)' => $i

                    ),
//                    'fields' => array('Working.id', 'Working.process_time')
                ));
                $sumTime = 0;
                foreach ($times as $time) {
                    $sumTime += $time['Working']['process_time'];
                }
                @$employ['TIME'][$i] = $sumTime;
            }

        }
        $currentUser = $this->User->find('first', array('conditions' => array(
            'User.id' => $this->request->data['user_id'],
        )));
//        debug($employ);
//        die;
        $this->set('employ', $employ);
        $this->set('currentUser', $currentUser);
    }


    public function daily_user_report()
    {
        ini_set('memory_limit', '-1');
        error_reporting(0);
        ini_set('display_errors', 0);

        $this->autoRender = false;

        if ($this->request->data) {
            $req = $this->request->data;

            $conditions = array();
            $hasUser = false;
            if (isset($req['user_id']) && $req['user_id'] != '') {
                $conditions['OR'] = array(
                    array(
                        'Product.perform_user_id' => $req['user_id']
                    ),
                    array(
                        'Product.deliver_user_id' => $req['user_id']
                    )
                );
                $hasUser = true;
            }
            $fromdate = str_replace("/", "-", $req['Report']['from_date']);
            $fromdate = new DateTime($fromdate . " 00:00:00");
            $fromdate = $fromdate->format('Y-m-d H:i:s');
            $todate = str_replace("/", "-", $req['Report']['to_date']);
            $todate = new DateTime($todate . " 23:59:59");
            $todate = $todate->format('Y-m-d H:i:s');

            if (isset($req['Report']['from_date']) && $req['Report']['from_date'] && isset($req['Report']['to_date']) && $req['Report']['to_date']) {
                $temp['OR'] = array(
                    array(
                        'Product.deliver_date >=' => $fromdate,
                        'Product.deliver_date <=' => $todate
                    ),
                    array(
                        'Product.date_of_completion >=' => $fromdate,
                        'Product.date_of_completion <=' => $todate
                    )
                );
                if (isset($conditions['OR']) && !empty($conditions['OR'])) {
                    $temp2 = $conditions;
                    unset($conditions['OR']);
                    $conditions['AND'] = array($temp2, $temp);
                } else {
                    $conditions['OR'] = $temp['OR'];
                }
            } else {
                $this->redirect(array('action' => 'index'));
            }

            $filename = "Pixelvn_DailyReport_td.xlsx";
            ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header("Content-Transfer-Encoding: binary ");
            header('Cache-Control: max-age=0');

            $file = 'ExcelSource/V5_DailyReport_user.xlsx';
            $excel = $this->PhpExcel->loadWorksheet($file);

            $data = $this->Product->find('all', array(
                'conditions' => $conditions,
                'fields' => array('Product.name_file_product', 'Product.id', 'Product.deliver_user_id', 'Product.perform_user_id'),
                'contain' => array(
                    'Processtype' => array(
                        'fields' => array('name')
                    ),
                    'Working' => array(
                        'fields' => array('process_time')
                    ),
                    'Project' => array(
                        'fields' => array('Name', 'InputDate'),
                        'CustomerGroup' => array(
                            'fields' => array('id', 'name')
                        ),
                        'Customer' => array(
                            'fields' => array('id', 'name'),
                            'Country' => array(
                                'fields' => array('id', 'name')
                            )
                        ),
                    )
                )
            ));
//            debug($req);
//            debug($data);
//            die;
            $excel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Times New Roman');
            $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(25);
            $begin = 8;
            $row = 0;
            $totalTime = 0;
            $totalRetouch = 0;
            $totalFile = 0;

            // create file
            foreach ($data as $element) {
                $totalFile++;
                $inputDate = date_format(date_create($element['Project']['InputDate']), 'd/m/Y');
                $excel->getActiveSheet()->setCellValue('A' . $begin, $inputDate);
                $excel->getActiveSheet()->setCellValue('B' . $begin, $element['Project']['Customer']['Country']['name']);
                $excel->getActiveSheet()->setCellValue('C' . $begin, $element['Project']['Customer']['name']);
                $excel->getActiveSheet()->setCellValue('D' . $begin, $row);
                $excel->getActiveSheet()->setCellValue('E' . $begin, $element['Project']['Name']);
                $excel->getActiveSheet()->setCellValue('F' . $begin, $element['Product']['name_file_product']);
                $excel->getActiveSheet()->setCellValue('H' . $begin, '1');
                if ($hasUser) {
                    if ($req['user_id'] == $element['Product']['deliver_user_id']) {
                        $excel->getActiveSheet()->setCellValue('G' . $begin, 'Chia hàng');
                    } elseif ($req['user_id'] == $element['Product']['perform_user_id']) {
                        $excel->getActiveSheet()->setCellValue('G' . $begin, 'retouch2');
                        $totalRetouch++;
                    }
                }
                $seconds = $element['Working'][0]['process_time'];
                $totalTime += $seconds;
                $excel->getActiveSheet()->setCellValue('I' . $begin, gmdate('H:i:s', $seconds));

                $begin++;
                $row++;
            }
            $begin++;
            $excel->getActiveSheet()->setCellValue('F' . $begin, 'Total Time');
            $excel->getActiveSheet()->setCellValue('G' . $begin++, gmdate('H:i:s', $totalTime));
            $excel->getActiveSheet()->setCellValue('F' . $begin, 'Total retouch2');
            $excel->getActiveSheet()->setCellValue('G' . $begin++, $totalRetouch);
            $excel->getActiveSheet()->setCellValue('F' . $begin, 'Average time / Total retouch2');
            $excel->getActiveSheet()->setCellValue('G' . $begin++, gmdate('H:i:s', ($totalTime / $totalRetouch)));
            $excel->getActiveSheet()->setCellValue('F' . $begin, 'Total Files');
            $excel->getActiveSheet()->setCellValue('G' . $begin++, $totalFile);
            $excel->getActiveSheet()->setCellValue('F' . $begin++, 'Total Point');
            $excel->getActiveSheet()->setCellValue('F' . $begin, 'Average time/ a file');
            $excel->getActiveSheet()->setCellValue('G' . $begin++, gmdate('H:i:s', ($totalTime / $totalFile)));
            $excel->getActiveSheet()->setCellValue('F' . $begin++, 'Total time check');
            $excel->getActiveSheet()->setCellValue('F' . $begin++, 'Average time check/ file');
            $excel->getActiveSheet()->setCellValue('F' . $begin++, 'Total Error');
            $excel->getActiveSheet()->setCellValue('F' . $begin++, 'Total Point res');

            $excel->getActiveSheet()->setTitle('Report');
            $excel->setActiveSheetIndex(0);
            $this->PhpExcel->output($filename);

            die;
        }
        die;
    }

    public function show_time_from_second($second = 0, $format = 0)
    {
        ini_set('memory_limit', '-1');
        error_reporting(0);
        ini_set('display_errors', 0);

        $hours = floor(($second) / 3600);
        $minutes = floor(($second - ($hours * 3600)) / 60);
        $seconds = floor(($second - ($hours * 3600) - ($minutes * 60)));
        $time = $hours . ":" . $minutes . ":" . $seconds;
        return $time;
    }

    public function get_process_point($product = null, $processArr = array(), $timepoints = array())
    {
        ini_set('memory_limit', '-1');
        error_reporting(0);
        ini_set('display_errors', 0);

        //Kiểm tra xem loại xử lý của file hiện tại
        $currProcess = $product['Product']['process_type_id'];
        $total_process_points = 0;
        //Kiểm tra xem có phải là tính điểm theo thời gian hay không?
        //- Nếu tính theo thời gian
        if ($processArr[$currProcess]['timecheck']) {

            //Thời gian làm file thực tế (Tính theo phút)
            if (isset($product['Working'])) {
                $workingTime = $product['Working'][0]['process_time'] / 60;
            } else {
                $workingTime = $product['Product']['Working'][0]['process_time'] / 60;
            }
            //Lấy theo thang thời gian
            //1. Hệ số
            $heso = $processArr[$currProcess]['number'];
            if (!$heso) {
                $heso = 1;
            }
            //2. Số điểm
            $processArr[$currProcess]['point'];
            $timepoints;

            $tmp_point = $processArr[$currProcess]['point'];
            //15,10,5,2,1
            //8
            foreach ($timepoints as $timepoint) {
                if ($workingTime <= $timepoint['TimePoint']['time']) {
                    $tmp_point = $timepoint['TimePoint']['point'];
                }
            }
            $total_process_points = $tmp_point * $heso;

        } else {
            //-Nếu không tính theo thời gian thì điểm số = số điểm X số xử lý
            $soxuly = 1;
            $total_process_points = $processArr[$currProcess]['point'] * $soxuly;
        }

        return $total_process_points;
    }


    function getPointByProductAndAction($productId, $actionId = 100)
    {

        ini_set('memory_limit', '-1');
        error_reporting(0);
        ini_set('display_errors', 0);

        $product = $this->Product->find('first', array(
            'conditions' => array(
                'Product.id' => $productId
            ),
            'contain' => array(
                'Working',
            )
        ));
        //Danh mục loại xử lý
        $processtypes = $this->Processtype->find('all', array(
            'recursive' => -1, //int
        ));
        $processArr = array();

        foreach ($processtypes as $pt) {
            $processArr[$pt['Processtype']['id']]['point'] = $pt['Processtype']['point'];
            $processArr[$pt['Processtype']['id']]['number'] = $pt['Processtype']['number']; //Hệ số
            $processArr[$pt['Processtype']['id']]['timecheck'] = $pt['Processtype']['time_checkbox']; //Tính theo thời gian

        }

        //Danh mục thang điểm thời gian
        $timepoints = $this->TimePoint->find('all', array(
            'recursive' => -1, //int
            'order' => array('TimePoint.time DESC'),
        ));

        //Lấy bảng actions để tạo mảng thang điểm cho từng loại action
        $action_points = $this->Action->find('all', array(
            'recursive' => -1, //int
        ));
        $nghiepvuArr = array();
        foreach ($action_points as $a_point) {
            $nghiepvuArr[$a_point['Action']['ID']] = $a_point['Action']['Point'];
        }


        if ($actionId == 100 || $actionId == 6) {
            //Kiểm tra xem loại xử lý của file hiện tại
            $currProcess = $product['Product']['process_type_id'];
            $total_process_points = 0;
            //Kiểm tra xem có phải là tính điểm theo thời gian hay không?
            //- Nếu tính theo thời gian
            if (get(get($processArr, $currProcess), 'timecheck')) {

                //Thời gian làm file thực tế (Tính theo phút)
                if (isset($product['Working'])) {
                    $workingTime = $product['Working'][0]['process_time'] / 60;
                } else {
                    $workingTime = $product['Product']['Working'][0]['process_time'] / 60;
                }
                //Lấy theo thang thời gian
                //1. Hệ số
                $heso = $processArr[$currProcess]['number'];
                if (!$heso) {
                    $heso = 1;
                }
                //2. Số điểm
                $processArr[$currProcess]['point'];
                $timepoints;

                $tmp_point = $processArr[$currProcess]['point'];
                //15,10,5,2,1
                //8
                foreach ($timepoints as $timepoint) {
                    if ($workingTime <= $timepoint['TimePoint']['time']) {
                        $tmp_point = $timepoint['TimePoint']['point'];
                    }
                }
                $total_process_points = $tmp_point * $heso;

            } else {
                //-Nếu không tính theo thời gian thì điểm số = số điểm X số xử lý
                $soxuly = 1;
                $total_process_points = get(get($processArr, $currProcess), 'point') * $soxuly;
            }
            if ($actionId == 100)
                return $total_process_points;
            elseif ($actionId == 6)
                return $total_process_points * $nghiepvuArr[$actionId];
        } else {
            return $nghiepvuArr[$actionId];
        }
    }

    function getRejectPointByProductAndAction($productId = 0, $actionId = 100, $rejectId = 0)
    {
        ini_set('memory_limit', '-1');
        error_reporting(0);
        ini_set('display_errors', 0);

        $soluong = 1;
        //Lấy số lượng file liên quan đến reject
        if ($productId) { // Nếu có id sản phẩm thì số lượng chính là sản phẩm đó ==1
            $soluong = 1;
        } else {
            $reject_info = $this->Reject->find('first', array(
                'recursive' => -1, //int
                'conditions' => array('id' => $rejectId)
            ));
            $rejectUser = $reject_info['Reject']['user_id_reject'];
            $projectId = $reject_info['Reject']['project_id'];
            if ($actionId == 6) {//Checking

                $soluong = $this->CheckerProduct->find('count', array(
                    'conditions' => array(
                        'checker_id' => $rejectUser,
                        'CheckerProduct.project_id' => $projectId
                    ),
                ));
            }
            if ($actionId == 7) {//Done
                $reject_info = $this->Reject->find('first', array(
                    'recursive' => -1, //int
                    'conditions' => array('id' => $rejectId)
                ));
                $rejectUser = $reject_info['Reject']['user_id_reject'];
                $projectId = $reject_info['Reject']['project_id'];
                $soluong = $this->CheckerProduct->find('count', array(
                    'conditions' => array(
                        'doner_id' => $rejectUser,
                        'CheckerProduct.project_id' => $projectId
                    ),
                ));
            }
            if ($actionId == 1) {//Khởi tạo
                $reject_info = $this->Reject->find('first', array(
                    'recursive' => -1, //int
                    'conditions' => array('id' => $rejectId)
                ));
                $rejectUser = $reject_info['Reject']['user_id_reject'];
                $projectId = $reject_info['Reject']['project_id'];
                $soluong = $this->Project->find('first', array(
                    'conditions' => array(
                        'Project.ID' => $projectId
                    ),
                ));
                $soluong = $soluong['Project']['Quantity'];
            }
            if ($actionId == 2) {//Kích hoạt
                $reject_info = $this->Reject->find('first', array(
                    'recursive' => -1, //int
                    'conditions' => array('id' => $rejectId)
                ));
                $rejectUser = $reject_info['Reject']['user_id_reject'];
                $projectId = $reject_info['Reject']['project_id'];
                $soluong = $this->ProjectAction->find('first', array(
                    'conditions' => array(
                        'ProjectAction.Project_id' => $projectId,
                        'ProjectAction.Action_id' => $actionId,
                        'ProjectAction.User_id' => $rejectUser
                    ),
                ));
                $soluong = $soluong['ProjectAction']['value'];
            }
        }
        //Lấy bảng actions để tạo mảng thang điểm cho từng loại action
        $action_points = $this->Action->find('all', array(
            'recursive' => -1, //int
        ));
        $nghiepvuArr = array();
        foreach ($action_points as $a_point) {
            $nghiepvuArr[$a_point['Action']['ID']] = $a_point['Action']['Point'];
        }

        //Lấy toàn bộ các reject liên quan đến dự án của nghiệp vụ này
        if ($productId) {
            $reject_NVs = $this->Reject->find('all', array(
                'conditions' => array(
                    'Reject.product_id' => $productId,
                    'Reject.action_id' => $actionId
                ),
                'contain' => array(
                    'Ratepoint'
                )

            ));
        } else {
            $reject_NVs = $this->Reject->find('all', array(
                'conditions' => array(
                    'Reject.project_id' => $projectId,
                    'Reject.action_id' => $actionId
                ),
                'contain' => array(
                    'Ratepoint'
                )

            ));
        }


        $rejVal = 0;

        if ($actionId == 100) {
            foreach ($reject_NVs as $projRej) {

                $rejVal = $projRej['Reject']['percent'] * 0.1 * $projRej['Ratepoint']['value'] * $this->getPointByProductAndAction($projRej['Reject']['product_id'], $projRej['Reject']['action_id']);

            }
        } elseif ($actionId == 6) {
            foreach ($reject_NVs as $projRej) {
                if ($productId) {
                    $rejVal = $projRej['Reject']['percent'] * 0.1 * $projRej['Ratepoint']['value'] * $nghiepvuArr[$actionId] * $this->getPointByProductAndAction($projRej['Reject']['product_id'], $projRej['Reject']['action_id']);
                } else {
                    $rejVal = $projRej['Reject']['percent'] * 0.1 * $projRej['Ratepoint']['value'] * $nghiepvuArr[$actionId];
                }
            }
        } elseif ($actionId == 13) {
            foreach ($reject_NVs as $projRej) {
                $rejVal = $projRej['Reject']['percent'] * 0.1 * $projRej['Ratepoint']['value'] * $nghiepvuArr[$actionId] * (-1);
            }
        } else {
            foreach ($reject_NVs as $projRej) {
                $rejVal = $projRej['Reject']['percent'] * 0.1 * $projRej['Ratepoint']['value'] * $nghiepvuArr[$actionId];
                $rejValccc = $projRej['Reject']['percent'] . "* 0.1 *" . $projRej['Ratepoint']['value'] . "*" . $nghiepvuArr[$actionId];


            }
        }

        return $rejVal * $soluong;

    }

//

}