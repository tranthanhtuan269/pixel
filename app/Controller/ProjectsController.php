<?php
App::uses('AppController', 'Controller');

class ProjectsController extends AppController
{

    public $uses = array('DoneProduct', 'ProductExtension', 'CustomerGroup', 'LargeGroup', 'Reject', 'Ratepoint', 'Working', 'Email', 'Processtypegroup', 'GroupCom', 'Department', 'Project', 'CheckerProduct', 'Productaction', 'AclManagement.User', 'Timelogin', 'Customer', 'Com', 'User', 'Group', 'Customergroup', 'Country', 'ProjectCom', 'ProjectAction', 'Action', 'Product', 'Status', 'Processtype', 'Producttype', 'Productextension','Status');

    public $current_user;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->current_user = $this->Auth->user();
    }
    
    public function xuly()
    {
        die('2');
    }

    public function index($row = 10)
    {
        $LargeGroup = $this->LargeGroup->find('list');
        $this->set('LargeGroup', $LargeGroup);
        $project_com = "";//$this->Com->find('list');
        $this->set('project_com', $project_com);
        $status_id = $this->request->query('Status_id');
        $name = $this->request->query('Name');
        $from_date = $this->request->query('FromDate');
        $from_comp_date = $this->request->query('FromCompDate');
        $to_comp_date = $this->request->query('ToCompDate');
        $to_date = $this->request->query('ToDate');
        $country_id = $this->request->query('Country_id');
        $customer_id = $this->request->query('Customer_id');
        $customer_group_id = $this->request->query('CustomerGroup_id');
        $search = $this->request->query('search');
        $conditions = array();
        if ($country_id && $country_id != '' && $customer_id == '' && $customer_group_id == '') {
            $customer = $this->Customer->find('list', array(
                'recursive' => -1, //int
                'fields' => array('Customer.id'),
                'conditions' => array('Customer.Country_id' => $country_id)
            ));
            if (count($customer) > 0) {
                $conditions['Project.Customer_id'] = $customer;
            } else {
                $conditions['Project.Customer_id'] = 0;
            }
//           debug($customer);die;
        }

        $com = $this->request->query('data');
//        debug($com);die;

        if ($com && isset($com['Com_id'])) {

            $procom = $this->ProjectCom->find('list', array(
                    'recursive' => -1, //int
                    'fields' => array('Project_id'),
                    'conditions' => array('Com_id' => $com['Com_id'])
                )
            );

            if (!empty($procom) && is_array($procom)) {
                $conditions['Project.ID'] = $procom;
            } else {
                $conditions['Project.ID'] = array('0');
            }


        }
        if ($status_id && $status_id != '') {
            $conditions['Project.Status_id'] = (int)$status_id;
            $this->set('searchStatus', $status_id);
        }
        if ($customer_id && $customer_id != '') {
            $conditions['Project.Customer_id'] = $customer_id;
        }
        if ($customer_group_id && $customer_group_id != '') {
            $conditions['Project.CustomerGroup_id'] = $customer_group_id;
        }
        if ($name && $name != '') {
            $conditions['Project.Name LIKE'] = '%' . $name . '%';
        }
        if ($from_date && $from_date != '' && $to_date && $to_date != '') {
            $conditions['Project.InputDate >='] = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $from_date)));
            $conditions['Project.InputDate <='] = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $to_date)));
        }
        if ($from_comp_date && $from_comp_date != '' && $to_comp_date && $to_comp_date != '') {
            $conditions['Project.CompTime >='] = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $from_comp_date)));
            $conditions['Project.CompTime <='] = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $to_comp_date)));
        }
        // echo "<div style='display:none'>";
        // pr($conditions); 
        // echo "</div>";
//        $priority = $this->Project->find('all',
//            array(
//                'recursive' => -1, //int
//                'conditions' => array(
//                    'OR' => array(
//                        'Project.project_weight' => 1,
//                        'Project.InitSize > ' => 1000000000
//                    ),
//                    'Project.status_id <>' => '6',
//                    $conditions
//                ),
//                'contain' =>array(
//                    'Product' => array(
//                        'fields' => array('id')
//                    ),
//                    'Status',
//                    'Customer' => array(
//                        'fields' => array('customer_code')
//                    )
//                ),
//                'order' => array('Status.oder' => 'ASC', 'Project.returnTime' => 'ASC')
//            )
//        );
        $priority = array();

        $processed = 0; //Tong so san pham da hoan thanh
        $dissevered = 0; //Tong so san pham da duoc giao
        $total = 0;
        if (isset($search) && $search) {
            $s_cond = $conditions;
            //Thống kê khi tìm kiếm
            $s_cond['Project.Status_id <>'] = 1;
            $s_cond['Project.Status_id <>'] = 6;


            $static = $this->Project->find('all', array(
                'conditions' => $s_cond));

            if (!empty($static)) {
                foreach ($static as $item) {
                    if ($item['Project']['Status_id'] != 1) {
                        foreach ($item['Product'] as $product) {
                            // Truong hop san pham da duoc giao (user_id !=0) va da hoan thanh (done > 0) <=> Da duoc xu ly
                            if ($product['perform_user_id'] != 0 && $product['done_round'] > 0) {
                                $processed++;
                            }

                            // Truong hop san pham da duoc giao (user_id !=0) co the hoan thanh hoac chua hoan thanh
                            if ($product['perform_user_id'] != 0) {
                                $dissevered++;
                            }
                        }

                        // Tong so luong san pham cua du an
                        $total = $total + $item['Project']['Quantity'];
                    }
                }
            }


            $paged = 1;
            if (isset($this->request->params['named']['page'])) {
                $paged = $this->request->params['named']['page'];
            }
            if (isset($static) && count($static) <= $row * $paged) {
                $this->request->params['named']['page'] = 1;
            }
        }

        $pri_ids = array();
        foreach ($priority as $pr) {
            $pri_ids[] = $pr['Project']['ID'];
        }

        if (count($pri_ids)) {
//            $conditions["NOT"]['Project.IxD'] = $pri_ids;
            $conditions['NOT'] = array(
                array('Project.ID' => $pri_ids)
            );
        }
//        $conditions['Project.Status_id <>'] = 6;
        $this->paginate = array(
            // 'recursive' => -1, //int
            'limit' => $row,
            'conditions' => $conditions,
            'contain' =>array(
                'Product' => array(
                    'fields' => array('id','deliver_user_id')
                ),
                'Status',
                'Customer' => array(
                    'fields' => array('customer_code')
                )
            ),
            'order' => array('Status.oder' => 'ASC', 'Project.returnTime' => 'ASC')
        );
//        $this->Project->recursive = 1;
        $this->Paginator->settings = $this->paginate;
        $data = $this->Paginator->paginate('Project');
        $this->set('projects', $data);
        $this->set('row', $row);
        $this->set('status_id', $status_id);
        $this->set('name', $name);
        $this->set('from_date', $from_date);
        $this->set('from_comp_date', $from_comp_date);
        $this->set('to_comp_date', $to_comp_date);
        $this->set('to_date', $to_date);
        $this->set('country_id', $country_id);
//        $this->set('customer_id',$customer_id);
//        $this->set('customer_group_id',$customer_group_id);
//        $coms = $this->Com->find('all');
        $this->set('dir', $this->dir);
        $statuses = $this->Status->find('list', array('fields' => array('Status.ID', 'Status.Name')));
        $countries = $this->Country->find('list', array('fields' => array('Country.id', 'Country.name')));
        $GroupCom = $this->GroupCom->find('list', array('fields' => array('GroupCom.id', 'GroupCom.name')));
        $this->set(compact(array('coms', 'countries', 'statuses', 'GroupCom')));
        if (!empty($data)) {


            if (@$_GET['show']) {
                debug($countries);die;
            }

//            foreach ($data as $item) {
//
//                if ($item['Project']['Status_id'] != 1) {
//                    foreach ($item['Product'] as $product) {
//                        // Truong hop san pham da duoc giao (user_id !=0) va da hoan thanh (done > 0) <=> Da duoc xu ly
//                        if ($product['perform_user_id'] != 0 && $product['done_round'] > 0) {
//                            $processed++;
//                        }
//
//                        // Truong hop san pham da duoc giao (user_id !=0) co the hoan thanh hoac chua hoan thanh
//                        if ($product['perform_user_id'] != 0) {
//                            $dissevered++;
//                        }
//                    }
//
//
//                    // Tong so luong san pham cua du an
//                    //$total = $total + $item['Project']['Quantity'];
//                }
//            }
            $un_dissevered = $total - $dissevered;
            $unprocessed = $dissevered - $processed;
            $this->set('total', $total);
            $this->set('unprocessed', $unprocessed);
            $this->set('processed', $processed);
            $this->set('un_dissevered', $un_dissevered);
            $this->set('dissevered', $dissevered);
            $this->set('prio_projects', $priority);
            return $dissevered . '/' . $un_dissevered . '/' . $processed . '/' . $unprocessed . '/' . $total;

        } else {
            $this->set('total', 0);
            $this->set('unprocessed', 0);
            $this->set('processed', 0);
            $this->set('un_dissevered', 0);
            $this->set('dissevered', 0);
            return '0/0/0/0/0';
        }
    }

    public function action()
    {
        $this->layout = 'ajax';
        $userpoints = $this->ProjectAction->find('all', array('conditions' => array('ProjectAction.Project_id' => $this->request->data['project_id'])));
        $this->set('userpoints', $userpoints);
        $reject = $this->Reject->find('all', array('conditions' => array('Reject.project_id' => $this->request->data['project_id'])));
        $new_reject = array();
        foreach ($reject as $item) {
            if ($item['Reject']['user_id_reject'] != null) {
                $new_reject[$item['Reject']['user_id_reject']]['percent'][] = $item['Reject']['percent'];
                $new_reject[$item['Reject']['user_id_reject']]['datetime'][] = date('d/m/Y H:i:s', strtotime($item['Reject']['datetime']));
            }
//            if($item['Reject']['product_id'] != null && $item['Reject']['user_id_reject'] != null){
//                $new_reject[$item['Reject']['user_id_reject']][$stt]['percent'] =  $item['Reject']['percent'];
//                $new_reject[$item['Reject']['user_id_reject']][$stt]['datetime'] =  $item['Reject']['datetime'];
//            }
        }
        $this->set('new_reject', $new_reject);
    }

    public function view($id = null)
    {
        if (!$this->Project->exists($id)) {
            throw new NotFoundException(__('Invalid project'));
        }
        $options = array('conditions' => array('Project.' . $this->Project->primaryKey => $id));
        $this->set('project', $this->Project->find('first', $options));
    }

    public function getQuantity($project_id = null)
    {
        $project = $this->Project->find('first', array(
            'recursive' => -1, //int
            'fields'=>array('Quantity','ID'),
            'conditions' => array(
                'Project.ID' => $project_id
            ),
            'contain'=> array(
                'Product' => array(
                    'fields' => array('id','perform_user_id','done_round')
                )
            )
        ));
        if (!empty($project)) {
            $processed = 0; //Tong so san pham da hoan thanh
            $dissevered = 0; //Tong so san pham da duoc giao
            foreach ($project['Product'] as $product) {
                // Truong hop san pham da duoc giao (user_id !=0) va da hoan thanh (done > 0) <=> Da duoc xu ly
                if ($product['perform_user_id'] != 0 && $product['done_round'] > 0) {
                    $processed++;
                }
                // Truong hop san pham da duoc giao (user_id !=0) co the hoan thanh hoac chua hoan thanh
                if ($product['perform_user_id'] != 0) {
                    $dissevered++;
                }
            }
            $total = $project['Project']['Quantity'];
            return $dissevered . '/' . $processed . '/' . $total;
        }
    }

    public function all_file_project()
    {
        $project = $this->Project->find('all');
        if (!empty($project)) {
            $processed = 0; //Tong so san pham da hoan thanh
            $dissevered = 0; //Tong so san pham da duoc giao
            $total = 0;
            foreach ($project as $item) {
                foreach ($item['Product'] as $product) {
                    // Truong hop san pham da duoc giao (user_id !=0) va da hoan thanh (done > 0) <=> Da duoc xu ly
                    if ($product['perform_user_id'] != 0 && $product['done_round'] > 0) {
                        $processed++;
                    }

                    // Truong hop san pham da duoc giao (user_id !=0) co the hoan thanh hoac chua hoan thanh
                    if ($product['perform_user_id'] != 0) {
                        $dissevered++;
                    }
                }


                // Tong so luong san pham cua du an
                $total = $total + $item['Project']['Quantity'];
            }
            $un_dissevered = $total - $dissevered;
            $unprocessed = $dissevered - $processed;
            return $dissevered . '/' . $un_dissevered . '/' . $processed . '/' . $unprocessed . '/' . $total;
//            echo $dissevered . '/' . $processed . '/' . $total;
//            die;
        } else {
            return '0/0/0/0/0';
        }
    }

    public function rowHtml($number = 1)
    {
        $process_type_group = $this->Processtypegroup->find('list', array('fields' => array('Processtypegroup.id', 'Processtypegroup.name')));
        $productextension = $this->Productextension->find('list', array('fields' => array('Productextension.id', 'Productextension.name')));
        $productTypes = $this->Producttype->find('list', array('fields' => array('Producttype.id', 'Producttype.name')));
        $this->set('number', $number);
        $this->set('process_type_group', $process_type_group);
        $this->set('productextension', $productextension);
        $this->set('productTypes', $productTypes);
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $ftp = new FTPUploader($this->host, $this->user, $this->pass);
            $number_project = get($this->request->data['Project'], 'number_project');
            for ($item_com = 0; $item_com <= $number_project; $item_com++) {
                if (isset($this->request->data['AllProject'][$item_com])) {
//                    debug($this->request->data);die;
                    $pro = trim($this->request->data['AllProject'][$item_com]['Name']);
                    $stt = $item_com;
                    $this->request->data['Project']['User_id'] = $this->current_user['id'];
                    $this->request->data['Project']['UserReview'] = $this->request->data['NV_ID_1'] . ',';
                    $this->request->data['Project']['user_download'] = $this->request->data['NV_ID_2'] . ',';
                    $this->request->data['Project']['user_khac'] = $this->request->data['NV_ID_3'] . ',';
                    $this->request->data['Project']['CustomerGroup_id'] = $this->request->data['CustomerGroup_id'];
                    $this->request->data['Project']['Customer_id'] = $this->request->data['Customer_id'];
                    $this->request->data['Project']['Status_id'] = 1;
                    $this->request->data['Project']['Code'] = str_replace(' ', '_', trim($this->stripVietName($pro)));
                    $this->request->data['Project']['UrlFolder'] = str_replace(' ', '_', $this->stripVietName($this->request->data['Project']['Country_name'] . '@' . $this->request->data['Project']['Customer_name'] . '@' . date('Y-m-d', strtotime(str_replace('/', '-', $this->request->data['Project']['InputDate']))) . '@' . $this->request->data['Project']['Code']));
                    // debug($this->request->data);die;
                    $this->request->data['Project']['Name'] = $pro;
                    $this->request->data['Project']['product_extension'] = $this->request->data['Project']['product_extension_id'];
                    if (isset($this->request->data['ProcessType_id'])) {
                        $this->request->data['Project']['ProcessType_id'] = $this->request->data['ProcessType_id'];
                    }
                    //////// Tính tổng số file ////////////////
                    $tong_com = 0;
                    foreach ($this->request->data['AllProject'][$item_com] as $key => $val) {
                        if (strpos($key, 'Com') !== false) {
                            $tong_com += $val;
                        }
                    }
                    $this->request->data['Project']['Quantity'] = $tong_com;
                    if ($this->request->data['Project']['InputDate'] != '') {
                        $this->request->data['Project']['InputDate'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $this->request->data['Project']['InputDate'])));
                    }
//                if ($this->request->data['Project']['CompTime'] != '') {
//                    $this->request->data['Project']['CompTime'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $this->request->data['Project']['CompTime'])));
//                }
                    if ($this->request->data['Project']['returnTime'] != '') {
                        $this->request->data['Project']['returnTime'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $this->request->data['Project']['returnTime'])));
                    }

                    $check_project = $this->Project->find('first', array('conditions' => array('or' => array('Project.UrlFolder' => $this->request->data['Project']['UrlFolder'], 'Project.Code' => $this->request->data['Project']['Code']))));
                    if (empty($check_project)) {
//                    if(1){
                        ////////// Tạo ra các thư mục cần thiết của đơn hàng ////////////
//                    $dir = new Folder(str_replace('@', '\\', $this->dir . $this->request->data['Project']['UrlFolder'] . '@SUB'), true, 0755);
//                    $dir = new Folder(str_replace('@', '\\', $this->dir . $this->request->data['Project']['UrlFolder'] . '@Chua_giao'), true, 0755);
//                    $dir = new Folder(str_replace('@', '\\', $this->dir . $this->request->data['Project']['UrlFolder'] . '@DONE'), true, 0755);


                        $ftp->make_directory(str_replace('@', '/', $this->dir . $this->request->data['Project']['UrlFolder'] . '@SUB'));
                        $ftp->make_directory(str_replace('@', '/', $this->dir . $this->request->data['Project']['UrlFolder'] . '@' . $this->request->data['Project']['Code'] . '_Chua_giao'));
                        $ftp->make_directory(str_replace('@', '/', $this->dir . $this->request->data['Project']['UrlFolder'] . '@DONE'));
                        ////////////////////////////////////


                        if (isset($this->request->data['Project']['File']['name']) && $this->request->data['Project']['File']['name'] != '') {
                            $this->request->data['Project']['File']['name'] = $this->char($this->request->data['Project']['File']['name']);

                            $fileupload = $this->_uploadFiles(str_replace('@', '/', $this->dir . $this->request->data['Project']['UrlFolder'] . '@SUB'), $this->request->data['Project']['File'], null);
                            echo json_encode($fileupload);
//                            die('aabccb' . time());

                            if (array_key_exists('urls', $fileupload)) {
                                $this->request->data['Project']['File']['name'] = $fileupload['urls'][0];
                                $this->request->data['Project']['File'] = str_replace("\\", '/', $this->request->data['Project']['File']['name']);
                            }
                        } else {
                            $cmg = $this->CustomerGroup->findById($this->request->data['CustomerGroup_id']);
//                            debug($cmg);die;
                            if ($cmg != "") {
                                if ($cmg['CustomerGroup']['help_file'] != null && $cmg['CustomerGroup']['help_file'] != 'no') {
                                    //copy(str_replace("\\", '/',$this->projectFolder.'medias/Customergroup_files' . DS .$cmg['CustomerGroup']['help_file']),str_replace('@', '/', $this->projectFolder.$this->dir . $this->request->data['Project']['UrlFolder'] . '@SUB@'.$cmg['CustomerGroup']['help_file']));
                                    $this->request->data['Project']['File'] = str_replace("@", '/', $this->dir . $this->request->data['Project']['UrlFolder'] . '@SUB@' . $cmg['CustomerGroup']['help_file']);
                                    $ftp->copy($this->domain . 'medias/Customergroup_files/' . str_replace(' ', '_', $cmg['CustomerGroup']['help_file']), 'medias/Customergroup_files/' . str_replace(' ', '_', $cmg['CustomerGroup']['help_file']), $this->request->data['Project']['File']);
//                                debug($this->request->data['Project']['File']);
//                                debug($this->domain .'medias/Customergroup_files/'.str_replace(' ','_',$cmg['CustomerGroup']['help_file']));die;
                                } else {
//
                                    $this->request->data['Project']['File'] = '';
                                }
                            }
                        }
//                                    pr($this->request->data);die;
                        $this->Project->create();
                        if ($this->Project->save($this->request->data['Project'])) {
//                          if(1){
                            ////////Cập nhật file đặc biệt nếu có/////
//                            debug($this->request->data['Product']);die;

                            if ($this->request->data['Product'][1]['name'] != '') {
                                $product = array();
                                $i = 0;
                                foreach ($this->request->data['Product'] as $k => $value) {
                                    if ($value['name'] != '') {
                                        $getp = explode(',', $value['name']);
                                        if (count($getp) > 1) {
                                            foreach ($getp as $v) {
                                                $expname = explode('.', $v);
                                                $product['Product'][$i]['name'] = $expname[0];
                                                $product['Product'][$i]['name_file_product'] = $v;
                                                $product['Product'][$i]['project_id'] = $this->Project->getInsertID();
                                                $product['Product'][$i]['product_special'] = 1;
                                                $product['Product'][$i]['process_type_id'] = $this->request->data['Product'][$k]['process_type_id'];
                                                $product['Product'][$i]['product_extension_id'] = $this->request->data['Product'][$k]['product_extension_id'];
                                                $i++;
                                            }
                                        } else {
                                            $expname = explode('.', $value['name']);
                                            $product['Product'][$i]['name'] = $expname[0];
                                            $product['Product'][$i]['name_file_product'] = $value['name'];
                                            $product['Product'][$i]['project_id'] = $this->Project->getInsertID();
                                            $product['Product'][$i]['product_special'] = 1;
                                            $product['Product'][$i]['process_type_id'] = $this->request->data['Product'][$k]['process_type_id'];
                                            $product['Product'][$i]['product_extension_id'] = $this->request->data['Product'][$k]['product_extension_id'];
                                            $i++;
                                        }
                                    } else {
                                        unset($product['Product'][$i]);
                                    }
                                }
                                if (isset($product['Product']) || count($product['Product']) > 0) {
                                    $this->Product->saveMany($product['Product'], array('deep' => true));
                                }
                            }
                            //////////Cập nhật vào bảng ProjectCom ///////////////

                            $data = null;
                            $data['ProjectCom'] = null;

                            foreach ($this->request->data['AllProject'][$item_com] as $key => $val) {
                                if (strpos($key, 'Com') !== false) {
                                    $id = explode('-', $key);
                                    if (count($id) > 1 && $val > 0) {
                                        $data['ProjectCom'][] = array(
                                            'Com_id' => $id['1'],
                                            'Quantity' => $val,
                                            'ProductType_id' => 1,
                                            'Project_id' => $this->Project->getInsertID()
                                        );
                                    }
                                }
                            }

                            /////////////////////////////
                            /*
                             * Lấy hệ số điểm từ bảng Action ứng với Action đã tác động lên đơn hàng
                             * Tính điểm cho người tạo và lưu vào ProjectAction
                            */
//                            debug($this->request->data); die;
                            $review = explode(',', $this->request->data['Project']['UserReview']);
                            if (count($review) > 0) {
                                $db_review = array();
                                $db_review['ProjectAction'] = array();
                                foreach ($review as $re) {
                                    if ($re != '') {
                                        $this->ProjectAction->create();
                                        $db_review['ProjectAction'][] = array(
                                            'Project_id' => $this->Project->getInsertID(),
                                            'Action_id' => 9,
                                            'User_id' => $re,
                                            'Point' => 1,
                                            'value' => 1
                                        );
//                                        debug($db_review['ProjectAction']);

                                    }
                                }
                                $this->ProjectAction->saveMany($db_review['ProjectAction'], array('deep' => true));
                            }
                            $download = explode(',', $this->request->data['Project']['user_download']);
                            if (count($download) > 0) {
                                $db_review2 = array();
                                $db_review2['ProjectAction'] = array();

                                foreach ($download as $re) {
                                    if ($re != '') {
                                        $this->ProjectAction->create();
                                        $db_review2['ProjectAction'][] = array(
                                            'Project_id' => $this->Project->getInsertID(),
                                            'Action_id' => 14,
                                            'User_id' => $re,
                                            'Point' => 1,
                                            'value' => 1
                                        );
//                                        debug($db_review2['ProjectAction']);
                                    }
                                }
                                $this->ProjectAction->saveMany($db_review2['ProjectAction'], array('deep' => true));

                            }
                            $khac = explode(',', $this->request->data['Project']['user_khac']);

                            if (count($khac) > 0) {
                                $db_review3 = array();
                                $db_review3['ProjectAction'] = array();

                                foreach ($khac as $re) {
                                    if ($re != '') {
                                        $this->ProjectAction->create();
                                        $db_review3['ProjectAction'][] = array(
                                            'Project_id' => $this->Project->getInsertID(),
                                            'Action_id' => 15,
                                            'User_id' => $re,
                                            'Point' => 1,
                                            'value' => 1
                                        );
//                                        debug($db_review3['ProjectAction']);
                                    }
                                }
                                $this->ProjectAction->saveMany($db_review3['ProjectAction'], array('deep' => true));

                            }
//                            debug($review);
//                            debug($download);
//                            debug($khac);
//                            die;
                            $gpoint = $this->Action->find('first', array('conditions' => array('Action.ID' => 1)));
                            $point = 0;
                            if (!empty($gpoint)) {
                                $point = $this->request->data['Project']['Quantity'] * $gpoint['Action']['Point'];
                            }
                            $this->ProjectAction->create();
                            $data['ProjectAction'][] = array(
                                'Project_id' => $this->Project->getInsertID(),
                                'Action_id' => 1,
                                'User_id' => $this->current_user['id'],
                                'Point' => $point,
                                'value' => $this->request->data['Project']['Quantity']
                            );
                            ///////////////////////////////////////
                            if (count($data['ProjectCom']) > 0) {
                                if ($this->ProjectCom->saveMany($data['ProjectCom'], array('deep' => true)) && $this->ProjectAction->saveMany($data['ProjectAction'], array('deep' => true))) {
                                    $alert[$stt] = (__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Dự án mới đã được tạo.</div>'));
                                } else {
                                    $this->Project->delete($this->Project->getInsertID());
                                    $alert[$stt] = (__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm dự án mới.</div>'));
                                }
                            } else {
                                if ($this->ProjectAction->save($data['ProjectAction'])) {
                                    $alert[$stt] = (__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Dự án mới đã được tạo.</div>'));
//                                $this->redirect(Router::url(array('controller' => 'Projects', 'action' => 'index'), true));
                                } else {
                                    $this->Project->delete($this->Project->getInsertID());
                                    $alert[$stt] = (__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm dự án mới.</div>'));
                                }
                            }

                        } else {
                            $alert[$stt] = (__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm dự án mới.</div>'));
                        }
                    } else {
                        $alert[$stt] = (__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Dự án đã tồn tại trong hệ thống.</div>'));
                    }
                }
            }
            $alert = isset($alert) && is_array($alert) ? $alert : [];
            $alert = implode('<br />', $alert);
            $this->Session->setFlash($alert);
            $this->redirect(Router::url(array('controller' => 'Projects', 'action' => 'index'), true));
            $ftp->close();
        }
        $process_type_group = $this->Processtypegroup->find('list', array('fields' => array('Processtypegroup.id', 'Processtypegroup.name')));
        $coms = $this->Com->find('all', array('order' => array('Com.group_com_id')));
        $countries = $this->Country->find('list', array('fields' => array('Country.id', 'Country.name')));
        $processTypes = $this->Processtype->find('list', array('fields' => array('Processtype.id', 'Processtype.name')));
        $productextension = $this->Productextension->find('list', array('fields' => array('Productextension.id', 'Productextension.name')));
        $productTypes = $this->Producttype->find('list', array('fields' => array('Producttype.id', 'Producttype.name')));
        $this->set(compact('customergroups', 'countries', 'projectTypes', 'users', 'process_type_group', 'coms', 'customers', 'projectTypes', 'processTypes', 'productextension', 'productTypes'));
    }

    public function infoProjectAuto()
    {
        $this->layout = 'ajax';
        if ($this->request->is('post')) {
            $ftp = new FTPUploader($this->host, $this->user, $this->pass);
            $country = $this->Country->findById($this->request->data['country_id']);
            $customer = $this->Customer->findById($this->request->data['customer_id']);
            $customer_group = $this->Customergroup->findById($this->request->data['customer_group_id']);
            $com_id = explode(',', $customer_group['Customergroup']['com_ids']);
            if (count($com_id) > 0) {
                $com = $this->Com->findById($com_id[0]);
            } else {
                $com = $this->Com->findById(30);
            }
            $this->set('com', $com);
            $date = explode('/', $this->request->data['date']);
            $new_date = $date[2] . '-' . $date[1] . '-' . $date[0];
            $dir = str_replace('@', '/', $this->dir . str_replace(' ', '_', $this->stripVietName($country['Country']['name'])) . '/' . str_replace(' ', '_', $this->stripVietName($customer['Customer']['name'])) . '/' . $new_date);
            $files = Manager::listFolder($dir);
            $in_files = array();
            $data = array();
            if ($files != false) {
                foreach ($files as $item) {
                    $name_project = explode('/', $item);
                    $number = (count($name_project) - 1);
                    $project_data = $this->Project->find('all', array(
                        'conditions' => array(
                            'Project.Code' => $name_project[$number],
                        )
                    ));

                    if (count($project_data) == 0 && (str_replace(' ', '_', $this->stripVietName($name_project[$number])) == $name_project[$number])) {
                        //$in_files[$name_project[$number]] = Manager::self::listFolder($item);
                        $files = Manager::listFolder($item);
                        $tam = array();
                        $tam_folders = array();
                        foreach ($files as $f) {
                            if ($ftp->file_size($f) > 0) {
                                $exp_filename = explode('/', $f);
                                if ($exp_filename[count($exp_filename) - 1] != 'Thumbs.db' && $exp_filename[count($exp_filename) - 1] != '._.DS_Store' && $exp_filename[count($exp_filename) - 1] != '._.DS_Store') {
                                    $tam[] = $f;
                                    $tam_folders[] = '';
                                }
                            } else {
//                    $dir = str_replace('@', '/', $this->dir . $project['Project']['UrlFolder'] . '@' . $project['Project']['Code'] . '_Chua_giao@'.$f);
                                $files_tam = Manager::listFolder($f);
//                    debug($files_tam);die;
                                foreach ($files_tam as $ft) {
                                    if ($ftp->file_size($ft) > 0) {
                                        $exp_folder = explode('/', $f);
                                        $exp_filename = explode('/', $ft);
                                        if ($exp_folder[count($exp_folder) - 1] != '__MACOSX' && $exp_filename[count($exp_filename) - 1] != 'Thumbs.db' && $exp_filename[count($exp_filename) - 1] != '._.DS_Store' && $exp_filename[count($exp_filename) - 1] != '._.DS_Store') {
                                            $tam[] = $ft;
                                            $tam_folders[] = '@' . $exp_folder[count($exp_folder) - 1];
                                        }
                                    }
                                }
                            }
                        }
                        $in_files[$name_project[$number]] = $tam;
                        foreach ($in_files as $key => $s) {
                            $dem = 0;
                            foreach ($s as $a) {
                                if (is_file($this->projectFolder . '/' . $a)) {
                                    $dem++;
                                }
                            }
                            if ($dem > 0) {
                                $data[$key]['name'] = $key;
                                $data[$key]['file'] = $dem;
                            }
                        }
                    }
                }
            } else {
                $data = null;
            }
            $this->set('link', $dir);
            $this->set('date_create', $this->request->data['date']);
            $this->set('data_project', $data);
            $this->set('customer_group', $customer_group);
        }
    }

    public function addByUrl()
    {
        $countries = $this->Country->find('list', array('fields' => array('Country.id', 'Country.name')));
        $this->set('country', $countries);
        if ($this->request->is('post')) {
            // debug($this->request->data);die;
            $number_project = count($this->request->data['ck']);
            if ($number_project > 0) {
                $ftp = new FTPUploader($this->host, $this->user, $this->pass);
                for ($t = 0; $t < $number_project; $t++) {

                    //D:\Xampp\htdocs\workman-2.0\app\webroot\Viet nam\Customer_group11\b\Don_hang_1
                    $this->request->data['Url' . $this->request->data['ck'][$t]] = str_replace('\\', '@', $this->request->data['Url' . $this->request->data['ck'][$t]]);
                    if ($this->dir != '') {
                        if ((strpos($this->request->data['Url' . $this->request->data['ck'][$t]], str_replace('\\', '@', $this->dir))) !== false) {
                            $this->request->data['Url' . $this->request->data['ck'][$t]] = substr($this->request->data['Url' . $this->request->data['ck'][$t]], strlen($this->dir), (strlen($this->request->data['Url' . $this->request->data['ck'][$t]]) - strlen($this->dir)));
                        }
                    } else {
                        if ((strpos($this->request->data['Url' . $this->request->data['ck'][$t]], str_replace('\\', '@', WWW_ROOT))) !== false) {
                            $this->request->data['Url' . $this->request->data['ck'][$t]] = substr($this->request->data['Url' . $this->request->data['ck'][$t]], strlen(WWW_ROOT), (strlen($this->request->data['Url' . $this->request->data['ck'][$t]]) - strlen(WWW_ROOT)));
                        }
                    }
                    // kiểm tra url của dự án xem có đúng cấu trúc ko : Quốc gia/Khách hàng/ngày/dự án
                    $arrobj = explode('@', $this->request->data['Url' . $this->request->data['ck'][$t]]);
                    if (count($arrobj) == 4) {
                        $this->request->data['Project']['User_id'] = $this->current_user['id'];
                        $this->request->data['Project']['product_extension_id'] = 11;
                        $this->request->data['Project']['Status_id'] = 1;
                        $this->request->data['Project']['RequireDevide'] = $this->request->data['sharing_note'];
                        $this->request->data['Project']['CustomerGroup_id'] = $this->request->data['customer_group_id'];
                        $this->request->data['Project']['Require'] = $this->request->data['doing_note'];
                        $this->request->data['Project']['Note'] = $this->request->data['init_note'];
                        $this->request->data['Project']['CompTime'] = $this->request->data['time'];
                        $this->request->data['Project']['UserReview'] = $this->request->data['NV_ID_1'];
                        $this->request->data['Project']['user_download'] = $this->request->data['NV_ID_2'];
                        $this->request->data['Project']['user_khac'] = $this->request->data['NV_ID_3'];
                        $this->request->data['Project']['CompTime'] = $this->request->data['time'];
                        $this->request->data['Project']['Name'] = $arrobj[3];
                        $this->request->data['Project']['Code'] = str_replace(' ', '_', $this->stripVietName($this->request->data['Project']['Name']));
                        $this->request->data['Project']['UrlFolder'] = $this->request->data['Url' . $this->request->data['ck'][$t]];
                        $ctmg = $this->CustomerGroup->findById($this->request->data['customer_group_id']);
                        $this->request->data['Project']['ProcessType_id'] = $ctmg['CustomerGroup']['process_type_id'];
                        $this->request->data['Project']['product_extension_id'] = $ctmg['CustomerGroup']['product_extension_id'];
                        $this->request->data['Project']['Require'] = $ctmg['CustomerGroup']['doing_note'];


//                        if ($this->request->data['Project']['returnTime'] != '') {
//                            $this->request->data['Project']['returnTime'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $this->request->data['Project']['returnTime'])));
//                        }
                        $this->request->data['Project']['InputDate'] = date('Y-m-d H:i:s', strtotime($arrobj[2]));
                        $check_project = $this->Project->find('first', array('conditions' => array('Project.UrlFolder' => $this->request->data['Project']['UrlFolder'])));
                        if (empty($check_project)) {
                            $check_country = $this->Country->find('first', array('conditions' => array('Country.name' => $arrobj[0])));
                            if (!empty($check_country)) {
                                $check_customer = $this->Customer->find('first', array('conditions' => array('Customer.name' => $arrobj[1], 'Customer.country_id' => $check_country['Country']['id'])));
                                if (!empty($check_customer)) {
                                    ////////// Tạo ra các thư mục cần thiết của đơn hàng ////////////

                                    $dir = str_replace('@', '/', $this->dir . $this->request->data['Project']['UrlFolder']);
                                    $files = Manager::listFolder($dir);
                                    $dem = 0;
                                    $tam = array();
                                    $tam_folders = array();
                                    foreach ($files as $f) {
                                        if ($ftp->file_size($f) > 0) {
                                            $exp_filename = explode('/', $f);
                                            if ($exp_filename[count($exp_filename) - 1] != 'Thumbs.db' && $exp_filename[count($exp_filename) - 1] != '._.DS_Store' && $exp_filename[count($exp_filename) - 1] != '._.DS_Store') {
                                                $tam[] = $f;
                                                $tam_folders[] = '';
                                            }
                                        } else {
//                    $dir = str_replace('@', '/', $this->dir . $project['Project']['UrlFolder'] . '@' . $project['Project']['Code'] . '_Chua_giao@'.$f);
                                            $files_tam = Manager::listFolder($f);
//                    debug($files_tam);die;
                                            foreach ($files_tam as $ft) {
                                                if ($ftp->file_size($ft) > 0) {
                                                    $exp_folder = explode('/', $f);
                                                    $exp_filename = explode('/', $ft);
                                                    if ($exp_folder[count($exp_folder) - 1] != '__MACOSX' && $exp_filename[count($exp_filename) - 1] != 'Thumbs.db' && $exp_filename[count($exp_filename) - 1] != '._.DS_Store' && $exp_filename[count($exp_filename) - 1] != '._.DS_Store') {
                                                        $tam[] = $ft;
                                                        $tam_folders[] = '@' . $exp_folder[count($exp_folder) - 1];
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    $files = $tam;
//                                    debug($tam_folders);die;
                                    $ftp->make_directory(str_replace('@', '/', $this->dir . $this->request->data['Project']['UrlFolder'] . '@SUB'));
                                    $ftp->make_directory(str_replace('@', '/', $this->dir . $this->request->data['Project']['UrlFolder'] . '@' . $this->request->data['Project']['Code'] . '_Chua_giao'));
                                    $ftp->make_directory(str_replace('@', '/', $this->dir . $this->request->data['Project']['UrlFolder'] . '@DONE'));

                                    foreach ($files as $k => $item) {
                                        if (is_file($this->projectFolder . '/' . $item)) {
                                            if ($tam_folders[$k] != '') {
                                                $ftp->make_directory(str_replace('@', '/', $this->dir . $this->request->data['Project']['UrlFolder'] . '@' . $this->request->data['Project']['Code'] . '_Chua_giao' . $tam_folders[$k]));
                                            }
                                            $link_files = explode('/', $item);
//                                            debug($tam_folders[$k]);
//                                            debug($item);
                                            $number = (count($link_files) - 1);
                                            $ftp->change($this->host, $this->user, $this->pass);
                                            $rsmove = $ftp->move('', str_replace('@', '/', $dir . $tam_folders[$k] . '/' . $link_files[$number]), $dir . '/' . str_replace('@', '/', $this->request->data['Project']['Code'] . '_Chua_giao' . $tam_folders[$k] . '/' . $link_files[$number]));
                                            $dem = $dem + 1;
                                        }
                                    }
                                    $deleted = array();
                                    foreach ($tam_folders as $tp) {
                                        if ($tp != '') {
                                            if (!in_array($tp, $deleted)) {
                                                ftp_rmdir($ftp->ftp_stream, str_replace('@', '/', $dir . $tp));
                                                $deleted[] = $tp;
                                            }
                                        }
                                    }
                                    $this->request->data['Project']['Quantity'] = count($files);
                                    $this->request->data['Project']['Customer_id'] = $check_customer['Customer']['id'];
                                    ////////////////////////////////////
                                    $this->Project->create();
                                    if ($ctmg['CustomerGroup']['help_file'] != null && $ctmg['CustomerGroup']['help_file'] != 'no') {
                                        $this->request->data['Project']['File'] = str_replace("@", '/', $this->dir . $this->request->data['Project']['UrlFolder'] . '@SUB@' . $ctmg['CustomerGroup']['help_file']);
                                        $ftp->copy($this->domain . 'medias/Customergroup_files/' . str_replace(' ', '_', $ctmg['CustomerGroup']['help_file']), 'medias/Customergroup_files/' . str_replace(' ', '_', $ctmg['CustomerGroup']['help_file']), $this->request->data['Project']['File']);
                                    } else {
//
                                        $this->request->data['Project']['File'] = '';
                                    }
                                    if ($this->Project->save($this->request->data['Project'])) {
                                        $com = null;
//                                        $com_id = array();
                                        $com_id = explode(',', $ctmg['CustomerGroup']['com_ids']);
                                        $com = $com_id[1];
                                        $this->ProjectCom->create();
                                        $pj_com = array(
                                            'Project_id' => $this->Project->getInsertID(),
                                            'Com_id' => $com,
                                            'Quantity' => $this->request->data['Project']['Quantity'],
                                            'ProductType_id' => 1
                                        );
                                        $this->ProjectCom->save($pj_com);
                                        /////////////////////////////
                                        /*
                                         * Lấy hệ số điểm từ bảng Action ứng với Action đã tác động lên đơn hàng
                                         * Tính điểm cho người tạo và lưu vào ProjectAction
                                        */
                                        $id_project = $this->Project->getLastInsertId();
                                        if ($files > 0) {
                                            $this->ActiveProject($id_project);
                                        }
                                        $gpoint = $this->Action->find('first', array('conditions' => array('Action.ID' => 1)));
                                        $point = 0;
                                        if (!empty($gpoint)) {
                                            $point = $this->request->data['Project']['Quantity'] * $gpoint['Action']['Point'];
                                        }
                                        $review = explode(',', $this->request->data['Project']['UserReview']);
                                        if (count($review) > 0) {
                                            $db_review = array();
                                            $db_review['ProjectAction'] = array();
                                            foreach ($review as $re) {
                                                if ($re != '') {
                                                    $this->ProjectAction->create();
                                                    $db_review['ProjectAction'][] = array(
                                                        'Project_id' => $this->Project->getInsertID(),
                                                        'Action_id' => 9,
                                                        'User_id' => $re,
                                                        'Point' => 1,
                                                        'value' => 1
                                                    );
//                                        debug($db_review['ProjectAction']);

                                                }
                                            }
                                            $this->ProjectAction->saveMany($db_review['ProjectAction'], array('deep' => true));
                                        }
                                        $download = explode(',', $this->request->data['Project']['user_download']);
                                        if (count($download) > 0) {
                                            $db_review2 = array();
                                            $db_review2['ProjectAction'] = array();

                                            foreach ($download as $re) {
                                                if ($re != '') {
                                                    $this->ProjectAction->create();
                                                    $db_review2['ProjectAction'][] = array(
                                                        'Project_id' => $this->Project->getInsertID(),
                                                        'Action_id' => 14,
                                                        'User_id' => $re,
                                                        'Point' => 1,
                                                        'value' => 1
                                                    );
//                                        debug($db_review2['ProjectAction']);
                                                }
                                            }
                                            $this->ProjectAction->saveMany($db_review2['ProjectAction'], array('deep' => true));

                                        }
                                        $khac = explode(',', $this->request->data['Project']['user_khac']);

                                        if (count($khac) > 0) {
                                            $db_review3 = array();
                                            $db_review3['ProjectAction'] = array();

                                            foreach ($khac as $re) {
                                                if ($re != '') {
                                                    $this->ProjectAction->create();
                                                    $db_review3['ProjectAction'][] = array(
                                                        'Project_id' => $this->Project->getInsertID(),
                                                        'Action_id' => 15,
                                                        'User_id' => $re,
                                                        'Point' => 1,
                                                        'value' => 1
                                                    );
//                                        debug($db_review3['ProjectAction']);
                                                }
                                            }
                                            $this->ProjectAction->saveMany($db_review3['ProjectAction'], array('deep' => true));

                                        }
                                        $data['ProjectAction'][] = array(
                                            'Project_id' => $this->Project->getInsertID(),
                                            'Action_id' => 1,
                                            'User_id' => $this->current_user['id'],
                                            'Point' => $point,
                                            'value' => $this->request->data['Project']['Quantity']
                                        );
                                        ///////////////////////////////////////
                                        if ($this->ProjectAction->saveMany($data['ProjectAction'], array('deep' => true))) {
                                            $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> ' . $number_project . ' Dự án mới đã được tạo.</div>'));
                                        } else {
                                            $this->Project->delete($this->Project->getInsertID());
                                            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm dự án mới.</div>'));
                                        }
                                    } else {
                                        $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm dự án mới.</div>'));
                                    }
                                } else {
                                    $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Đường dẫn gặp lỗi ở folder khách hàng.</div>'));
                                }
                            } else {
                                $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Đường dẫn gặp lỗi ở folder quốc gia.</div>'));
                            }
                        } else {
                            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Đường dẫn đã tồn tại trong hệ thống.</div>'));
                        }
                    } else {
                        $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Đường dẫn gặp lỗi vì thiếu subforlder.</div>'));
                    }
                }
            }
            $this->redirect(Router::url(array('controller' => 'Projects', 'action' => 'index'), true));
        }
    }

    public function statistic()
    {
        $this->layout = "ajax";
        $worktimes = $this->Project->find('first', array('conditions' => array('Project.Status_id' => array(2, 3)), 'fields' => array('sum(Project.SpentTime) as TotalTime')));
        $today = date("Y-m-d");
        $users = $this->Timelogin->find('count', array(
            'fields' => 'DISTINCT Timelogin.user_id',
            'conditions' => array('Timelogin.time_login LIKE ' => '%' . $today . '%')));
        $usertimes = $users * ($this->CF['TIME_WORK'] / 60);
        $totalsize = $this->Project->find('first', array('fields' => array('sum(Project.InitSize) as TotalSize')));
        $sizework = $this->Project->find('first', array('conditions' => array('Project.Status_id' => array(2, 3)), 'fields' => array('sum(Project.InitSize) as TotalSize')));
        $sizeprocess = $this->Project->find('first', array('conditions' => array('Project.Status_id' => 4), 'fields' => array('sum(Project.InitSize) as TotalSize')));
//        $sizeprocess1 = $this->Project->find('first', array('conditions' => array('Project.Status_id' => 4),'recursive'=>0));
//        pr($sizeprocess1);die;
        $sizedone = $this->Project->find('first', array('conditions' => array('Project.Status_id' => 5), 'fields' => array('sum(Project.InitSize) as TotalSize')));
        $this->set(compact('sizework', 'sizeprocess', 'sizedone', 'worktimes', 'totalsize', 'usertimes'));
    }

    public function movefiles($products = array(), $project_id = 0, $status = 0, $user_id = 0, $ftp = 0)
    {
        //Khai báo sử dụng FTPUPloader
        $uptoftp = new FTPUploader($this->host, $this->user, $this->pass);
        $done = array();
        $project = $this->Project->find('first', array('conditions' => array('Project.ID' => $project_id), 'contain' => false));
        $user = $this->User->find('first', array('conditions' => array('User.id' => $user_id), 'contain' => false));
        $products = $this->Product->find('all', array('conditions' => array('Product.id' => $products), 'contain' => false));

        if (!empty($products)) {
            /////////Status = 0 ==> move file trong khi giao việc/////////////////////
            $dir = '';
            if ($status == 0) {
//                $uptoftp->change('localhost','media','123456');
//                $dir = new Folder(str_replace('@', '\\', $this->dir . $project['Project']['UrlFolder'] . '@' . $project['Project']['Code'].'-'.$user['User']['username']), true, 0755);
                foreach ($products as $pd) {
                    $uptoftp->make_directory(str_replace('@', '/', $this->dir . $project['Project']['UrlFolder'] . '@' . $project['Project']['Code'] . '-' . $user['User']['username'] . $pd['Product']['sub_folder']));
                }
                $dir = $project['Project']['UrlFolder'] . '@' . $project['Project']['Code'] . '-' . $user['User']['username'];

            }

            $dir = str_replace('@', '/', $this->dir . $dir);


            //////////////Xử lý copy file sang folder mới và delete file cũ //////////
            foreach ($products as $product) {

                $uptoftp->change($this->host, $this->user, $this->pass);
                $urlfile = str_replace('@', '/', $this->domain . $this->dir . $product['Product']['url'] . '@' . $product['Product']['name_file_product']);
                $localfile = str_replace('@', '/', $this->dir . $product['Product']['url'] . $product['Product']['sub_folder'] . '@' . $product['Product']['name_file_product']);


                $rsmove = $uptoftp->move($urlfile, $localfile, str_replace('@', '/', $dir . $product['Product']['sub_folder'] . '/' . $product['Product']['name_file_product']));
                if (strlen($product['Product']['sub_folder']) > 2) {
                    $folderChuaGiao = (str_replace('@', '/', $this->dir . $product['Product']['url']));
                    $command = 'find "'. FTPUploader::$realFolder . $folderChuaGiao .'" -mindepth 1 -type d -empty -delete';
                    exec($command);
                }
                // debug($rs);
                if ($rsmove['resultCode'] == 1) {
                    //NẾu upfile  nên ftp và k nén
//                    if($ftp==1){
//                        $uptoftp->change("demo.laurus.vn",'demo','demo');
//                        $result = $uptoftp->upload($this->domain.$dir .'/'. $product['Product']['name_file_product'],$product['Product']['name_file_product'],"http://demo.laurus.vn/".$product['Product']['name_file_product']);
//                        $done['uploadFTP'] = $result;
//                    }
                    //////////Update url product///////////////
                    $this->Product->id = $product['Product']['id'];
                    $this->Product->saveField('url', $project['Project']['UrlFolder'] . '@' . $project['Project']['Code'] . '-' . $user['User']['username']);
                    $done['done'][$product['Product']['name_file_product']] = str_replace('@', '/', $this->domain . $dir . $project['Project']['UrlFolder'] . '@' . $project['Project']['Code'] . '-' . $user['User']['username'] . $product['Product']['sub_folder'] . '@' . $product['Product']['name_file_product']);
                } else {
                    $done['reject'][$product['Product']['name_file_product']] = 1;
                }
            }

            //NẾu upfile nên ftp và nén
            if ($ftp == 2) {
                if ($this->create_zip_from_url($done['done'], str_replace('\\', '/', WWW_ROOT . $project['Project']['UrlFolder'] . '@' . $project['Project']['Code'] . '-' . $user['User']['username'] . '.zip'), true)) {
//                    $uptoftp->change("demo.laurus.vn",'demo','demo');
                    $result = $uptoftp->upload(str_replace('@', '/', $this->webroot . $project['Project']['UrlFolder'] . '@' . $project['Project']['Code'] . '-' . $user['User']['username'] . '.zip'), str_replace('@', '/', $dir . $project['Project']['url'] . '@' . $project['Project']['Code'] . '-' . $user['User']['username'] . '.zip'));
                    $done['uploadFTP'] = $result;
                }
            }
            $uptoftp->close();
            return $done;
        }
        $uptoftp->close();
        return 0;
    }

    public function copyfiles($basefolder = null, $folder_target = null, $folder_copy = null)
    {
        $ftp = new FTPUploader($this->host, $this->user, $this->pass);
        $done = array();
        $err = 0;
        $dir = str_replace('@', '/', $this->dir . $basefolder . '@' . $folder_target);
        $ftp->make_directory($dir);
        $dircopy = str_replace('@', '/', $this->dir . $basefolder . '@' . $folder_copy);
        //////////////Xử lý copy file sang folder mới và delete file cũ //////////
        $files = Manager::listFolder($dircopy);
        if (count($files) > 0) {
            foreach ($files as $file) {

//                pr(str_replace('@', '\\', $this->dir .$basefolder.'@'. $folder_copy.'@'.$file));die;
                if ($ftp->file_size($file) > 0) {

                    $file = explode('/', $file);
                    $check = str_replace('@', '/', $this->dir . $basefolder . '@' . $folder_copy . '@' . $file[count($file) - 1]);
//                pr($dir.'/'.$file[count($file)-1]);die;
                    $copy_status = $ftp->copy($this->domain . $check, $check, $dir . '/' . $file[count($file) - 1]);
                    if ($copy_status['resultCode'] == 1) {
//                    $check->copy($dir->path . DS . $check->name);
                        $done[$file[count($file) - 1]] = 1;
                    } else {
                        $done[$file[count($file) - 1]] = 0;
                        $err++;
                    }
                } else {
                    $exp = explode('/', $file);
                    $subfolder = $exp[count($exp) - 1];
                    $ftp->make_directory($dir . '/' . $subfolder);
                    $sub_file = Manager::listFolder($file);
                    foreach ($sub_file as $sf) {
                        $sf = explode('/', $sf);
                        $check = str_replace('@', '/', $this->dir . $basefolder . '@' . $folder_copy . '@' . $subfolder . '@' . $sf[count($sf) - 1]);
                        $copy_status = $ftp->copy($this->domain . $check, $check, $dir . '/' . $subfolder . '/' . $sf[count($sf) - 1]);

                        if ($copy_status['resultCode'] == 1) {
//                    $check->copy($dir->path . DS . $check->name);
                            $done[$sf[count($sf) - 1]] = 1;
                        } else {
                            $done[$sf[count($sf) - 1]] = 0;
                            $err++;
                        }
                    }
                }
            }
            if ($err > 0) {
                $done['result'] = 0;
            } else {
                $done['result'] = 1;
            }
        } else {
            $done['result'] = 0;
        }
        $ftp->close();
        return $done;
    }

    function uploadFTP()
    {
//        $files_to_zip = glob(WWW_ROOT . '123' . '/*');
//        pr($files_to_zip);die;
        $ftp = new FTPUploader("localhost", 'pixel_vp', ')oKmNji9');
//        $dir = $ftp->make_directory('a/b/c/d');
        $dir = $ftp->copy('http://media.workman:8888/Projects/Viet_nam/b/Customer_group11/2015-01-09/Don_hang_00009/Chua_giao/684302330.jpg', 'Projects/Viet_nam/b/Customer_group11/2015-01-09/Don_hang_00009/Chua_giao/684302330.jpg', '684302330.jpg');
//        $result = $dir;

        pr($dir);
        die;
    }

    function create_zip($files = array(), $dest = '', $overwrite = false)
    {
        if (file_exists($dest) && !$overwrite) {
            return false;
        }
        if (($files)) {
            $zip = new ZipArchive();
            if ($zip->open($dest, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                return false;
            }

            foreach ($files as $file) {
                $file1 = explode('\\', $file);
                $zip->addFile($file, $file1[count($file1) - 1]);
            }
            $zip->close();
            return file_exists($dest);
        } else {
            return false;
        }
    }

    function addzip($source, $destination)
    {

        $files_to_zip = glob($source . '/*');
        $files_to_zip = str_replace('/', '\\', $files_to_zip);
        if ($this->create_zip($files_to_zip, $destination, true))
            return 1;
        else
            return 0;
    }

    function create_zip_from_url($files = array(), $dest = '', $overwrite = false)
    {
//        $files = array(
//            'http://google.com/images/logo.png',
//            'http://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Wikipedia-logo-en-big.png/220px-Wikipedia-logo-en-big.png'
//        );
//        if (file_exists($dest) && !$overwrite) {
//            return false;
//        }
        if (($files)) {
            # create new zip opbject
            $zip = new ZipArchive();
            if ($zip->open($dest, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                return false;
            }
            # create a temp file & open it
//            $tmp_file = tempnam('.','');
//            $zip->open($tmp_file, ZipArchive::CREATE);
//            pr($files);die;
            # loop through each file
            foreach ($files as $file) {

                # download file
                $download_file = file_get_contents($file);

                #add it to the zip
                $zip->addFromString(basename($file), $download_file);
            }
            # close zip
            $zip->close();
            return file_exists($dest);
        } else {
            return false;
        }
    }

    function setUserProducts()
    {
        /*if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if ($ip != '118.71.58.60')
            die('Phan nay dang duoc chinh sua, xin moi test phan khac truoc !');*/

        ///////////Lấy thông tin của đơn hàng và các sản phẩm được giao ////////////////////////
//        $this->Session->delete('dataexport');
//        debug($this->request->data);die;
//        echo count($this->request->data['Product']['Product_id']) ;die;
        if (isset($this->request->data['Product']['Project_id']) && isset($this->request->data['Product']['Product_id'])) {
            $normal = $feat = '';
            $products = $this->Product->find('all', array('conditions' => array('Product.id' => $this->request->data['Product']['Product_id'])));
            foreach ($products as $product) {
                if ($product['Product']['product_special'] == 1) {
                    $feat .= $product['Product']['id'] . ',';
                } else {
                    $normal .= $product['Product']['id'] . ',';
                }
            }
            $options = array('conditions' => array('Project.' . $this->Project->primaryKey => $this->request->data['Product']['Project_id']));
            $project = $this->Project->find('first', $options);
            $this->Session->write('dataexport', array('products' => $products, 'project' => $project));
            $this->set('dir', $this->dir);
            $this->set('domain', $this->domain);
            $process_type_group = $this->Processtypegroup->find('list', array('fields' => array('Processtypegroup.id', 'Processtypegroup.name')));
            $processTypes = $this->Processtype->find('list', array('fields' => array('Processtype.id', 'Processtype.name')));
            $productextension = $this->Productextension->find('list', array('fields' => array('Productextension.id', 'Productextension.name')));
            $productTypes = $this->Producttype->find('list', array('fields' => array('Producttype.id', 'Producttype.name')));
            $this->set(compact('feat', 'normal', 'processTypes', 'process_type_group', 'productextension', 'productTypes', 'project', 'products', 'domain'));
        }
        ///////////////////////Nhận thông tin chia hàng và lưu vào csdl////////////////////////////
        if ($this->request->is('post') && isset($this->request->data['Project']['save']) && $this->request->data['Project']['save'] == 1) {

            $today = date('Y-m-d H:i:s');
            $ids = $data = array();
            $check_feats = array();
            ///////////////Lấy danh sách sản phẩm đặc biệt /////////////////////
            $feats = explode(',', $this->request->data['Project']['Feat_products']);
            $normals = explode(',', $this->request->data['Project']['Normal_products']);
            foreach ($feats as $fs) {
                if ($fs != '') {
                    $check_feats[$fs] = 1;
                    $ids[] = $fs;
                }
            }
            foreach ($normals as $nm) {
                if ($nm != '') {
                    $check_feats[$nm] = 0;
                    $ids[] = $nm;
                }
            }
            $idUsers = explode(',', $this->request->data['NV_ID']);

            $ids_tmp = $ids;
            $ids = array();
            //$ids  = (array_chunk($ids, $number_file));
            for ($x = 0; $x < count($ids_tmp); $x++) {
                $ids[($x % count($idUsers))][] = $ids_tmp[$x];
            }


//            debug($check_feats);
//            debug($ids);die;
//            if (count($feats) > 0) {
//                foreach ($feats as $product) {
//                    if ($product != '') {
//                        $data['Product'][] = array(
//                            'id' => $product,
//                            'deliver_date' => $today,
//                            'deliver_user_id' => $this->current_user['id'],
//                            'perform_user_id' => $this->request->data['NV_ID']
//                        );
//                        $ids[] = $product;
//                    }
//                }
//            }
            ///////////////Lấy danh sách sản phẩm thường /////////////////////
            if (count($ids) > 0) {
                for ($j = 0; $j < count($idUsers); $j++) {
                    foreach ($ids[$j] as $product) {
                        $pro_tmp = array(
                            'id' => $product,
                            'deliver_date' => $today,
                            'deliver_user_id' => $this->current_user['id'],
                            'perform_user_id' => $idUsers[$j],
//                            'process_type_id' => $this->request->data['Project']['process_type_id'],
                            'priority' => $this->request->data['Project']['priority'],
                        );

                        if ($check_feats[$product] == 0) {
//                            $pro_tmp['product_extension_id'] = ($this->request->data['Project']['product_extension_id'] != '') ? $this->request->data['Project']['product_extension_id'] : 0;
                            $pro_tmp['process_type_id'] = $this->request->data['Project']['process_type_id'];
                            $pro_tmp['product_extension_id'] = $this->request->data['Project']['product_extension_id'];
                        }
                        $data['Product'][] = $pro_tmp;
                    }
                }

            }
//            debug($this->request->data);
//            debug($ids);
//            debug($data['Product']);
//            debug($idUsers);
            if (count($data['Product']) > 0) {
//                echo 'aaa';die;
                if ($this->Product->saveMany($data['Product'], array('deep' => true))) {
//                    if(1){
                    ////////////////////Chuyển file sang folder của người được chia hàng///////////////////
                    ////////////Nén xuất excel -> chuyển file excel sang ftp
//                    if ($this->request->data['ExcelUrl'] != '') {
//                        $ftp = new FTPUploader($this->host, $this->user, $this->pass);
//                        $name = explode('\\', $this->request->data['ExcelUrl']);
//                        $ftp->upload($this->request->data['ExcelUrl'], str_replace('@', '/', $this->dir . $this->request->data['UrlFolder'] . '@SUB@' . $name[count($name) - 1]));
//                    }
                    ////////////Nén zip nếu có yêu cầu
//                    echo 1;die;
                    for ($m = 0; $m < count($idUsers); $m++) {
                        if ($this->request->data['addzip'] == 1) {
                            $move[] = $this->movefiles($ids[$m], $this->request->data['Project']['Project_id'], 0, $idUsers[$m], 2);
                        } else {
                            $move[] = $this->movefiles($ids[$m], $this->request->data['Project']['Project_id'], 0, $idUsers[$m], 0);
                            //                           debug($ids[$m]);
//                            debug($this->request->data['Project']['Project_id']);
//                            debug($idUsers[$m]);

                        }
                    }
                    ///////////////Kiểm tra và update trạng thái của đơn hàng /////////////////////
                    $project = $this->Project->find('first', array('conditions' => array('Project.ID' => $this->request->data['Project']['Project_id'])));
                    $checks = $this->Product->find('count', array('conditions' => array('Product.project_id' => $this->request->data['Project']['Project_id'], 'Product.deliver_user_id <>' => array(null, ''))));
                    if ($checks == $project['Project']['Quantity']) {
                        $data['Project'] = array(
                            'ID' => $this->request->data['Project']['Project_id'],
                            'Status_id' => 3
                        );
                        //$this->Project->save($data['Project']);
                    }
                    //////////////////////////////////////////
                    //////////////Cập nhật diểm số cho nhân viên chia hàng ///////////////////////////////
                    $gpoint = $this->Action->find('first', array('conditions' => array('Action.ID' => 3)));
                    $point = 0;
                    /////// Tính điểm cho người kích hoạt: Điểm = số file x hệ số
                    if (!empty($gpoint)) {
                        $point = (count($normals) + count($feats)) * $gpoint['Action']['Point'];
                    }
                    $data['ProjectAction'] = array(
                        'Project_id' => $this->request->data['Project']['Project_id'],
                        'Action_id' => 3,
                        'User_id' => $this->current_user['id'],
                        'Point' => $point,
                        'value' => (count($normals) + count($feats))
                    );
                    $this->ProjectAction->save($data['ProjectAction']);
                    /////////////////////////////////////////////////////////////////////////////////////
                    $rj = '';
//                    debug($move);die;
                    if (!empty($move)) {
                        $a = '';
                        $rs = '';
                        foreach ($move as $mv) {
                            if (array_key_exists('reject', $mv)) {
                                foreach ($mv['reject'] as $xkey => $xvalue) {
                                    $a .= $xkey . ',';
                                }
                            }
                        }
                        if ($a != '') {
                            $rj = "<p>Ảnh của sản phẩm {$a} chưa được chuyển sang thu mục của người nhận hàng! Hãy kiểm tra lại.</p>";
                        }
                    }
                    $this->Session->setFlash(__('
                        <div id="alert" class="alert alert-success">
                            <button data-dismiss="alert" class="close">×</button>
                            <strong>Thành công!</strong> Bạn đã chia hàng thành công.
                            ' . $rj . '
                            <p>Số sản phẩm được kích hoạt là: ' . count($data['Product']) . '</p>
                        </div>'
                    ));
                    $this->changestatus($this->request->data['Project']['Project_id'], 3);
                    $this->redirect(Router::url(array('controller' => 'Projects', 'action' => 'detail', $this->request->data['Project']['Project_id']), true));
                } else {
                    $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Hãy thử lại.</div>'));
                    $this->redirect(Router::url(array('controller' => 'Projects', 'action' => 'detail', $this->request->data['Project']['Project_id']), true));
                }
            }
        }
    }

    public function export($username = '', $id = '')
    {
        $this->set('export', 1);
        $this->set('id', $id);
        $this->set('current', $this->current_user);
        $this->set('username', $username);
        $this->set('dir', ($this->dir != '') ? $this->dir : WWW_ROOT);
    }

    public function editAField()
    {
        if (isset($this->request->data['ID'])) {
            if (!$this->Project->exists($this->request->data['ID'])) {
                $output['resultCode'] = 0;
                $output['resultMsg'] = "Thao tác thất bại. Hãy thử lại!";
            } else {
                if (isset($this->request->data['InputDate']) && $this->request->data['InputDate'] != '') {
                    $this->request->data['InputDate'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $this->request->data['InputDate'])));
                }
                if (isset($this->request->data['CompTime']) && $this->request->data['CompTime'] != '') {
                    $this->request->data['CompTime'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $this->request->data['CompTime'])));
                }
                if (isset($this->request->data['returnTime']) && $this->request->data['returnTime'] != '') {
                    $this->request->data['returnTime'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $this->request->data['returnTime'])));
                }
                $this->Project->id = $this->request->data['ID'];
                if ($this->Project->save($this->request->data)) {
                    $output['resultCode'] = 1;
                    $output['resultMsg'] = "Thành công!";
                } else {
                    $output['resultCode'] = 0;
                    $output['resultMsg'] = "Thao tác thất bại. Hãy thử lại!";
                }
            }
        } else {
            $output['resultCode'] = 0;
            $output['resultMsg'] = "Thao tác thất bại. Hãy thử lại!";
        }
        $this->RequestHandler->respondAs('json');
        $this->autoRender = false;
        return json_encode($output);
    }

    public function edit($id = null)
    {

        if (!$this->Project->exists($id)) {
            throw new NotFoundException(__('Invalid project'));
        } else {
            $pj = $this->Project->findById($id);
            $status = $pj['Project']['Status_id'];
            $this->set('status', $status);
        }
        if ($this->request->is(array('post', 'put'))) {
//            echo WWW_ROOT;die;
            $options = array('conditions' => array('Project.' . $this->Project->primaryKey => $this->request->data['Project']['ID']));
            $dataold = $this->Project->find('first', $options);
//            pr($this->request->data);die;
//            debug($this->Product->find('count',array('conditions'=>array('Product.project_id'=>$id))));
//            debug((int)$this->request->data['Project']['Quantity']);
//            die;
            // $this->request->data['Project']['User_id'] = $this->current_user['id'];
            $this->request->data['Project']['UserReview'] = $this->request->data['NV_ID_1'] . ',';
            $this->request->data['Project']['user_download'] = $this->request->data['NV_ID_2'] . ',';
            $this->request->data['Project']['user_khac'] = $this->request->data['NV_ID_3'] . ',';
            if (!empty($this->request->data['ProcessType_id'])) {
                $this->request->data['Project']['ProcessType_id'] = $this->request->data['ProcessType_id'];
            }
//            $this->request->data['Project']['CustomerGroup_id'] = $this->request->data['CustomerGroup_id'];
//            $this->request->data['Project']['Customer_id'] = $this->request->data['Customer_id'];
//            $this->request->data['Project']['Code'] = str_replace(' ', '_', $this->stripVietName($this->request->data['Project']['Name']));
//            $this->request->data['Project']['product_extension_id'] = $this->request->data['Project']['product_extension_id'];

            if ($this->request->data['Project']['returnTime']) {
                $this->request->data['Project']['returnTime'] = date('Y-m-d H:i:s', strtotime(str_replace("/", "-", $this->request->data['Project']['returnTime'])));
            }
//            debug($this->request->data['Project']['DelFile']);die;


            if (isset($this->request->data['Project']['File']['name']) && $this->request->data['Project']['File']['name'] != '') {
                $this->request->data['Project']['File']['name'] = $this->char($this->request->data['Project']['File']['name']);
                $fileupload = $this->_uploadFiles(str_replace('@', '/', $this->dir . $dataold['Project']['UrlFolder'] . '@SUB'), $this->request->data['Project']['File'], null);
                if (array_key_exists('urls', $fileupload)) {
                    $this->request->data['Project']['File']['name'] = $fileupload['urls'][0];
                    $this->request->data['Project']['File'] = $this->request->data['Project']['File']['name'];
                }
            } else {
                unset($this->request->data['Project']['File']);
            }

            if (!empty($this->request->data['Project']['DelFile']) && $this->request->data['Project']['DelFile'] > 0) {
                $this->request->data['Project']['File'] = '';
            }
            //////////Cập nhật vào bảng ProjectCom ///////////////
            $this->request->data['Project']['Quantity'] = 0;
            foreach ($this->request->data['Project'] as $key => $val) {
                if (strpos($key, 'Com') !== false) {
                    $idx = explode('-', $key);
                    $data['ProjectCom'][] = array(
                        'Com_id' => $idx['1'],
                        'Quantity' => $val,
                        'ProductType_id' => 1,
                        'Project_id' => $this->request->data['Project']['ID']
                    );
                    $this->request->data['Project']['Quantity'] += $val;
                }
            }
            ///////////////cập nhật lại trạng thái dự án nếu số lượng file lớn hơn số lượng sảm phẩm
//            debug($this->request->data['Project']['Quantity']);
//            debug( $id);
//            debug(($this->Product->find('all',array('conditions'=>array('Product.project_id'=>$id)))));
//            die;
            if ($pj['Project']['Status_id'] != 6) {
                if (($this->Product->find('count', array('conditions' => array('Product.project_id' => $id))) < (int)$this->request->data['Project']['Quantity']) && ($dataold['Project']['Status_id'] > 1)) {

                    $this->request->data['Project']['Status_id'] = 2;
                }

                $check_1 = $this->Product->find('count', array('conditions' => array('Product.project_id' => $id, 'Product.deliver_user_id >' => 0)));

                if ((int)$this->request->data['Project']['Quantity'] == $check_1) {
                    $this->request->data['Project']['Status_id'] = 3;
                }

                $check_2 = $this->Product->find('count', array('conditions' => array('Product.project_id' => $id, 'Product.done_round' => array(1, 2))));
                if ((int)$this->request->data['Project']['Quantity'] == $check_2) {
                    $this->request->data['Project']['Status_id'] = 4;
                }
            }
            $countproducts_x = 0;
            $getcheck = $this->CheckerProduct->find('count', array('conditions' => array('CheckerProduct.project_id' => $id, 'CheckerProduct.check' => 1)));
            // if (!empty($getcheck)) {
            //     foreach ($getcheck as $check) {
            //         $products_x = explode(',', $check['CheckerProduct']['products']);
            //         $countproducts_x += count($products_x) - 1;
            //     }
            // }
            // debug($getcheck); die;

            if ($getcheck == (int)$this->request->data['Project']['Quantity']) {
                $this->request->data['Project']['Status_id'] = 5;
            }

            /////////////////////////////
//            pr($this->request->data);die;
            if (isset($this->request->data['ProcessType_id'])) {
                $this->request->data['Project']['ProcessType_id'] = $this->request->data['ProcessType_id'];
            }
            $this->Project->id = $this->request->data['Project']['ID'];
            if ($this->Project->save($this->request->data['Project'])) {
                // cap nhat file dac biet
                if (isset($this->request->data['Product'])) {
                    $i = 0;
                    $product = array();
                    foreach ($this->request->data['Product'] as $k => $value) {
                        if ($value['name'] != '') {
                            $getp = explode(',', $value['name']);
                            if (count($getp) > 1) {
                                foreach ($getp as $v) {
                                    $pd = $this->Product->find('first', array('conditions' => array('Product.name_file_product' => $v, 'Product.project_id' => $this->request->data['Project']['ID'])));
                                    if (count($pd)) {
                                        $product['Product'][$i] = $pd['Product'];
                                    }
                                    $expname = explode('.', $v);
                                    $product['Product'][$i]['name'] = $expname[0];
                                    $product['Product'][$i]['name_file_product'] = $v;
                                    $product['Product'][$i]['project_id'] = $this->request->data['Project']['ID'];
                                    $product['Product'][$i]['product_special'] = 1;
                                    $product['Product'][$i]['process_type_id'] = $this->request->data['Product'][$k]['process_type_id'];
                                    $product['Product'][$i]['product_extension_id'] = $this->request->data['Product'][$k]['product_extension_id'];
                                    $i++;
                                }
                            } else {
                                $pd = $this->Product->find('first', array('conditions' => array('Product.name_file_product' => $value['name'], 'Product.project_id' => $this->request->data['Project']['ID'])));
                                if (count($pd)) {
                                    $product['Product'][$i] = $pd['Product'];
                                }
                                $expname = explode('.', $value['name']);
                                $product['Product'][$i]['name'] = $expname[0];
                                $product['Product'][$i]['name_file_product'] = $value['name'];
                                $product['Product'][$i]['project_id'] = $this->request->data['Project']['ID'];
                                $product['Product'][$i]['product_special'] = 1;
                                $product['Product'][$i]['process_type_id'] = $this->request->data['Product'][$k]['process_type_id'];
                                $product['Product'][$i]['product_extension_id'] = $this->request->data['Product'][$k]['product_extension_id'];
                                $i++;
                            }
                        } else {
                            $product['Product'][$i] = null;
                        }
                    }
//                                debug($product['Product']);die;
                    $this->Product->saveMany($product['Product'], array('deep' => true));
                }
                $nomal_pds = $this->Product->find('all', array(
                    'conditions' => array(
                        'Product.project_id' => $this->request->data['Project']['ID'],
                        'Product.product_special' => 0
                    )
                ));
                if (count($nomal_pds) > 0) {
                    $npd = array();
                    foreach ($nomal_pds as $m => $nomal_pd) {
                        $npd[$m] = $nomal_pd;
                        $npd[$m]['Product']['process_type_id'] = $this->request->data['Project']['ProcessType_id'];
                        $npd[$m]['Product']['product_extension_id'] = $this->request->data['Project']['product_extension_id'];
                        $this->Product->save($npd[$m]);
                    }
                }
//                debug($npd);die;
//                $this->Product->saveMany($npd, array('deep' => true));

                /*
                 * Lấy hệ số điểm từ bảng Action ứng với Action đã tác động lên đơn hàng
                */

                $gpoint = $this->Action->find('first', array('conditions' => array('Action.ID' => 1)));
                $point = 0;
                /////// Tính điểm cho người tạo: Điểm = số file x hệ số
                if (!empty($gpoint)) {
                    $point = $this->request->data['Project']['Quantity'] * $gpoint['Action']['Point'];
                }
                ///////////////////////////////////
                //// lưu vào ProjectAction/////

                $data['ProjectAction'] = array(
                    'Project_id' => $this->request->data['Project']['ID'],
                    'Action_id' => 17,
                    'User_id' => $this->current_user['id'],
                    'Point' => $point,
                    'value' => $this->request->data['Project']['Quantity']
                );
//                pr($this->request->data);die;
                ///////////////////////////////////////
                if (count($data['ProjectCom']) > 0) {
                    /////Xóa dữ liệu cũ trước khi lưu //////////////////////
                    $this->ProjectCom->deleteAll(array('ProjectCom.Project_id' => $this->request->data['Project']['ID']));
                    $this->ProjectAction->deleteAll(array('ProjectAction.Project_id' => $this->request->data['Project']['ID'], 'ProjectAction.Action_id' => 1));
                    /////////////////////////////////
                    if ($this->ProjectCom->saveMany($data['ProjectCom'], array('deep' => true)) && $this->ProjectAction->save($data['ProjectAction'])) {
                        //Nếu lưu thành công thì xóa file cũ đi nếu có
                        if ($this->request->data['Project']['OldFile'] != '' && file_exists($this->request->data['Project']['OldFile'])) {
                            unlink($this->request->data['Project']['OldFile']);
                        }
                        $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Dự án đã được sửa.</div>'));
                        $this->redirect(Router::url(array('controller' => 'Projects', 'action' => 'index'), true));
                    } else {
                        //Nếu lưu không thành công thì xóa file vừa up đi nếu có và lưu lại dữ liệu cũ
                        if ($this->request->data['Project']['File'] != '' && file_exists($this->request->data['Project']['File'])) {
                            unlink($this->request->data['Project']['File']);
                        }
                        $this->Project->save($dataold['Project']);
                        $this->ProjectCom->saveMany($dataold['ProjectCom'], array('deep' => true));
                        $this->ProjectAction->save($dataold['ProjectAction']);
                        /////////////////////////////
                        $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm dự án mới.</div>'));
                    }
                } else {
                    if ($this->ProjectAction->save($data['ProjectAction'])) {
                        //Nếu lưu thành công thì xóa file cũ đi nếu có
                        if ($this->request->data['Project']['OldFile'] != '' && file_exists($this->request->data['Project']['OldFile'])) {
                            unlink($this->request->data['Project']['OldFile']);
                        }
                        $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm dự án mới.</div>'));
                        $this->redirect(Router::url(array('controller' => 'Projects', 'action' => 'index'), true));
                    } else {
                        //Nếu lưu không thành công thì xóa file vừa up đi nếu có và lưu lại dữ liệu cũ
                        if ($this->request->data['Project']['File'] != '' && file_exists($this->request->data['Project']['File'])) {
                            unlink($this->request->data['Project']['File']);
                        }
                        $this->Project->save($dataold['Project']);
                        ///////////////////////////////////////////////////
                        $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm dự án mới.</div>'));
                    }
                }
            } else {
                $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm dự án mới.</div>'));
            }
        } else {
            $options = array('conditions' => array('Project.' . $this->Project->primaryKey => $id));

            $this->request->data = $this->Project->find('first', $options);
//            debug($this->request->data);die;
            $profeats = array();
            if (isset($this->request->data['Product'])) {
                $feature_products = array();
//                debug($this->request->data['Product']);die;
                foreach ($this->request->data['Product'] as $k => $pro) {
                    if ($pro['product_special'] == 1) {
//                        debug($pro['process_type_id']);
                        $process_type_group_id_special = $this->Processtype->find('first', array('conditions' => array('Processtype.id' => $pro['process_type_id'])));
                        $processTypes_special = $this->Processtype->find('list', array('fields' => array('Processtype.id', 'Processtype.name'), 'conditions' => array('Processtype.process_type_group_id' => $process_type_group_id_special['Processtypegroup']['id'])));

                        $feature_products[$k] = $pro;
                        $feature_products[$k]['process_type_group_id_special'] = $process_type_group_id_special['Processtypegroup']['id'];
                        $feature_products[$k]['processTypes_special'] = $processTypes_special;
//                        debug($feature_products[$k]);

//                        if (isset($profeats[$pro['process_type_id'] . '-' . $pro['product_type_id']])) {
//                            $profeats[$pro['process_type_id'] . '-' . $pro['product_type_id']] .= $pro['name_file_product'] . ',';
//                        } else {
//                            $profeats[$pro['process_type_id'] . '-' . $pro['product_type_id']] = $pro['name_file_product'];
//                        }


                    }
                }
//                die;
            }
//            pr($this->request->data);die;
            $datacom = array();
            if (isset($this->request->data['ProjectCom']) && count($this->request->data['ProjectCom']) > 0) {
                foreach ($this->request->data['ProjectCom'] as $com) {
                    $datacom[$com['Com_id']] = $com;
                }
            }
            $pushuser1 = array();
            $pushuser2 = array();
            $pushuser3 = array();
            $usernames1 = $userid1 = '';
            $usernames2 = $userid2 = '';
            $usernames3 = $userid3 = '';
            $receivers1 = explode(",", $this->get($this->request->data['Project'],'UserReview'));
            $receivers2 = explode(",", $this->get($this->request->data['Project'],'user_download'));
            $receivers3 = explode(",", $this->get($this->request->data['Project'],'user_khac'));
            foreach ($receivers1 as $p => $s) {
                $pushuser1['conditions']['User.id'][] = $s;
            }
            foreach ($receivers2 as $p => $s) {
                $pushuser2['conditions']['User.id'][] = $s;
            }
            foreach ($receivers3 as $p => $s) {
                $pushuser3['conditions']['User.id'][] = $s;
            }
            $users1 = $this->User->find('all', $pushuser1);
            $users2 = $this->User->find('all', $pushuser2);
            $users3 = $this->User->find('all', $pushuser3);
            if (!empty($users1)) {
                foreach ($users1 as $user) {
                    $usernames1 .= $user['User']['name'] . ', ';
                }
            } else {
                $usernames1 = '';
            }
            $this->set('usernames1', $usernames1);
            if (!empty($users2)) {
                foreach ($users2 as $user) {
                    $usernames2 .= $user['User']['name'] . ', ';
                }
            } else {
                $usernames2 = '';
            }
            $this->set('usernames2', $usernames2);
            if (!empty($users3)) {
                foreach ($users3 as $user) {
                    $usernames3 .= $user['User']['name'] . ', ';
                }
            } else {
                $usernames3 = '';
            }
            $this->set('usernames3', $usernames3);
        }
        $process_type_group = $this->Processtypegroup->find('list', array('fields' => array('Processtypegroup.id', 'Processtypegroup.name')));
        $process_type_group_id = $this->request->data['ProcessType']['process_type_group_id'];
        $coms = $this->Com->find('all', array('order' => array('Com.group_com_id')));
        $countries = $this->Country->find('list', array('fields' => array('Country.id', 'Country.name')));
        $processTypes = $this->Processtype->find('list', array('fields' => array('Processtype.id', 'Processtype.name'), 'conditions' => array('Processtype.process_type_group_id' => $process_type_group_id)));
//        $processTypes_special = $this->Processtype->find('list', array('fields' => array('Processtype.id', 'Processtype.name')));
        $productextension = $this->Productextension->find('list', array('fields' => array('Productextension.id', 'Productextension.name')));
        $productTypes = $this->Producttype->find('list', array('fields' => array('Producttype.id', 'Producttype.name')));
//        pr( $this->request->data   );die;
//        $this->set(compact('customergroups','countries','projectTypes','users','coms', 'customers', 'projectTypes', 'processTypes', 'productTypes'));
        $this->set(compact('processTypes_special', 'process_type_group_id', 'feature_products', 'profeats', 'customergroups', 'datacom', 'countries', 'projectTypes', 'users', 'process_type_group', 'coms', 'customers', 'projectTypes', 'processTypes', 'productextension', 'productTypes'));
    }

    public function user_info($user_id = null)
    {
        $user_info = $this->User->findById($user_id);
        return $user_info;
    }

    public function detail($project_id = null)
    {
//        $exten_check = $this->ProductExtension->find('all',array('conditions'=>array('view_type'=>0),'fields' => 'ProductExtension.name'));
//        debug($exten_check);die;

        $rejectGroup = $this->Ratepoint->find('list');
        $this->set('ratePoint', $rejectGroup);
        $LargeGroup = $this->LargeGroup->find('list');
        $this->set('LargeGroup', $LargeGroup);
        $project = $this->Project->find('first', array('conditions' => array('Project.ID' => $project_id)));
        $product_comp = $this->Product->find('all', array('conditions' => array('Project.ID' => $project_id, 'Product.done_round >=' => 1)));
        $this->set('count_pd', count($product_comp));


        $userpoints = $this->ProjectAction->find('all', array('conditions' => array('ProjectAction.Project_id' => $project_id)));
        $reject = $this->Reject->find('all', array('conditions' => array('Reject.project_id' => $project_id)));
        $new_reject = array();
        foreach ($reject as $item) {
            if ($item['Reject']['user_id_reject'] != null) {
                $new_reject[$item['Reject']['user_id_reject']]['percent'][] = $item['Reject']['percent'];
                $new_reject[$item['Reject']['user_id_reject']]['datetime'][] = date('d/m/Y H:i:s', strtotime($item['Reject']['datetime']));
            }
//            if($item['Reject']['product_id'] != null && $item['Reject']['user_id_reject'] != null){
//                $new_reject[$item['Reject']['user_id_reject']][$stt]['percent'] =  $item['Reject']['percent'];
//                $new_reject[$item['Reject']['user_id_reject']][$stt]['datetime'] =  $item['Reject']['datetime'];
//            }
        }
//        debug($new_reject);
//        debug($reject);die;
        if (!empty($project)) {
            $products['total']['Delivered'] = 0;
            foreach ($project['Product'] as $product) {
                if ($product['done_round'] >= 1) {
                    if (isset($products['data'][$product['perform_user_id']]['Done'])) {
                        $products['data'][$product['perform_user_id']]['Done']++;
                    } else {
                        $products['data'][$product['perform_user_id']]['Done'] = 1;
                    }
                    if (isset($products['data'][$product['perform_user_id']]['Delivered'])) {
                        $products['data'][$product['perform_user_id']]['Delivered']++;
                    } else {
                        $products['data'][$product['perform_user_id']]['Delivered'] = 1;
                    }
                    $products['total']['Delivered']++;
                } else {
                    if ($product['perform_user_id'] != 0) {
                        if (isset($products['data'][$product['perform_user_id']]['Delivered'])) {
                            $products['data'][$product['perform_user_id']]['Delivered']++;
                        } else {
                            $products['data'][$product['perform_user_id']]['Delivered'] = 1;
                        }
                        if (isset($products['data'][$product['perform_user_id']]['product_id'])) {
                            $products['data'][$product['perform_user_id']]['product_id'] .= $product['id'] . ',';
                        } else {
                            $products['data'][$product['perform_user_id']]['product_id'] = $product['id'] . ',';
                        }
                        $products['total']['Delivered']++;
                    }
                }
            }
            $this->set('products', $products);
        }
        $this->set('new_reject', $new_reject);
        $this->set('project', $project);
        $this->set('userpoints', $userpoints);
        $this->set('dir', $this->dir);
        $this->set('project_id', $project_id);
    }

    public function GridviewDetail($project_id = null, $page_num = 1, $limit = 25)
    {
        $this->paginate = array(
            'limit' => $limit,
            'offset' => $page_num * $limit - $limit, 
            'conditions' => array('Product.project_id' => $project_id),
            'order' => 'Product.id desc'
        );
        $this->Product->recursive = 1;
        $data = $this->Paginator->paginate('Product');
        $project = $this->Project->findById($project_id);
        $this->set('project', $project);
        $this->set('products', $data);
        $this->set('project_id', $project_id);
        $this->set('dir', $this->dir);
        $this->set('domain', $this->domain);
		
		$this->set('page_num', $page_num);
        $this->set('limit', $limit);
    }

    public function check_product($product_id = null)
    {
        $checker = $this->CheckerProduct->find('first', array('conditions' => array('CheckerProduct.products LIKE' => "%{$product_id}%"), 'contain' => false));
        if (!empty($checker)) {
            return $checker['CheckerProduct']['done'];
        } else {
            return 0;
        }
    }

    public function ListviewDetail($project_id = null, $page_num = 1, $limit = 25)
    {
        $this->paginate = array(
            'limit' => $limit,
            'offset' => $page_num * $limit - $limit,
            'conditions' => array('Product.project_id' => $project_id),
            'order' => 'Product.id desc'
        );
        $project = $this->Project->findById($project_id);
        $this->Product->recursive = 1;
        $data = $this->Paginator->paginate('Product');
        $this->set('products', $data);
        $this->set('page_num', $page_num);
        $this->set('limit', $limit);

        $this->set('project_id', $project_id);
        $this->set('project', $project);
        $this->set('dir', $this->dir);
        $this->set('domain', $this->domain);
    }

    public function ActiveProject($project_id = null)
    {
        $ftp = new FTPUploader($this->host, $this->user, $this->pass);
        $project = $this->Project->find('first', array('conditions' => array('Project.ID' => $project_id)));
        $checkfile = $this->Product->find('count', array('conditions' => array('Product.project_id' => $project_id, 'Product.deliver_user_id >' => 0)));
        if (!empty($project)) {
//            $checkproduct = $this->Product->find('all',array('conditions'=>array('Product.project_id'=>$project_id,'Product.product_special'=>1)));
            ///////lấy các files trong thư mục chưa giao của đơn hàng ////////////////////
            $dir = str_replace('@', '/', $this->dir . $project['Project']['UrlFolder'] . '@' . $project['Project']['Code'] . '_Chua_giao');
            $files = Manager::listFolder($dir);

            $command = 'find "'. FTPUploader::$realFolder . $dir .'" -name "Thumbs.db" -type f -delete';
            exec($command);
            $command = 'find "'. FTPUploader::$realFolder . $dir .'" -name "*.DS_Store" -type f -delete';
            exec($command);


//            pr($files);die;
            ///////////// Cho vào mảng để insert vào db /////////////////
            $tam = $name = array();
            $tam_folders = array();
            foreach ($files as $f) {
                if ($ftp->file_size($f) > 0) {
                    $exp_filename = explode('/', $f);
                    if ($exp_filename[count($exp_filename) - 1] != 'Thumbs.db' && $exp_filename[count($exp_filename) - 1] != '._.DS_Store' && $exp_filename[count($exp_filename) - 1] != '.DS_Store') {
                        $tam[] = $f;
                        $exp_name = explode('.', $exp_filename[count($exp_filename) - 1]);
                        unset($exp_name[count($exp_name) - 1]);
                        $exp_name = implode($exp_name);
                        $name[] = $exp_name;
                        $tam_folders[] = '';
                    }
                } else {
//                    $dir = str_replace('@', '/', $this->dir . $project['Project']['UrlFolder'] . '@' . $project['Project']['Code'] . '_Chua_giao@'.$f);
                    $files_tam = Manager::listFolder($f);
//                    debug($files_tam);die;
                    foreach ($files_tam as $ft) {
                        if ($ftp->file_size($ft) > 0) {
                            $exp_folder = explode('/', $f);
                            $exp_filename = explode('/', $ft);
                            if ($exp_folder[count($exp_folder) - 1] != '__MACOSX' && $exp_filename[count($exp_filename) - 1] != 'Thumbs.db' && $exp_filename[count($exp_filename) - 1] != '._.DS_Store' && $exp_filename[count($exp_filename) - 1] != '.DS_Store') {
                                $tam[] = $ft;
                                $exp_name = explode('.', $exp_filename[count($exp_filename) - 1]);
                                unset($exp_name[count($exp_name) - 1]);
                                $exp_name = implode($exp_name);
                                $name[] = $exp_name;
                                $tam_folders[] = '@' . $exp_folder[count($exp_folder) - 1];
                            }
                        }
                    }
                }
            }
            $files = $tam;
            // update for duplicated file names . Luckymancv
            $files_name = array_unique($name);
            if ($project['Project']['duplicate'] == '1')
                $files_name = ($name);

            $filesize = 0;
            $normal = $feat = $data = array();
//            debug();
            if (count($files) == count($files_name)) {
//            pr(str_replace('@','\\',$this->dir.$project['Project']['UrlFolder'].'@Chua_giao'));die;
                if (count($files) > 0 && (count($files) + $checkfile) <= $project['Project']['Quantity']) {
                    //////////lấy file đặc biệt//////////////////////////
                    $products = $this->Product->find('all', array('conditions' => array('Product.project_id' => $project_id))); //,'Product.product_special'=>1
                    if (!empty($products)) {
//                    pr($products);die;
                        foreach ($products as $product) {
                            $product_expname = explode('.', $product['Product']['name_file_product']);
                            if ((int)$product['Product']['product_special'] == 1)
                                $feat[$product_expname[0]] = $product['Product'];
                            else
                                $normal[$product_expname[0]] = $product['Product'];
                        }
                    }
//                debug($feat);die;
                    ////////////////////////////////////////////
                    $check = 0;
                    $checkquantity = 0;
                    foreach ($files as $k => $file) {
//                        if($ftp->file_size($file)){
                        $exp = explode('/', $file);
                        $expname = explode('.', $exp[count($exp) - 1]);
                        $data['Product'][$k] = array(
                            'name' => $expname[0],
                            'project_id' => $project['Project']['ID'],
                            'name_file_product' => $exp[count($exp) - 1],
                            'url' => $project['Project']['UrlFolder'] . '@' . $project['Project']['Code'] . '_Chua_giao',
                            'sub_folder' => $tam_folders[$k],
                            'order' => $k,
                            'process_type_id' => $project['Project']['ProcessType_id'],
                            'product_type_id' => $project['Project']['ProductType_id'],
//                                'status'=>1
                        );
                        if (is_null($project['Project']['ProductType_id']) || $project['Project']['ProductType_id'] == null || $project['Project']['ProductType_id'] == 'null') {
                            $data['Product'][$k]['product_type_id'] = 0;
                        }

// if($_GET['debug']){
//     debug($data['Product']); 
// }
                        $size = $ftp->file_size(trim($file));
                        $data['Product'][$k]['file_size'] = $size;
// if($_GET['debug']){
//     debug($file."-".$size); die;
// }
                        $filesize += $ftp->file_size(trim($file));
//                    pr($filesize);die;
//                    }else{
//                            $exp = explode('/', $file);
//                            $sub_name =  $exp[count($exp) - 1];
//                            $sub_dir = str_replace('@', '/', $this->dir . $project['Project']['UrlFolder'] . '@' . $project['Project']['Code'] . '_Chua_giao@'.$sub_name);
//                            $sub_files = Manager::self::listFolder($sub_dir);
//                            pr ($sub_name);
//                        }
                        //Kiểm tra file đã có hay chưa
                        if (isset($normal)) {
                            if (isset($normal[$expname[0]])) {
//                                $checkquantity++;
//                                $data['Product'][$k]['id'] = $normal[$expname[0]]['id'];
//                                $data['Product'][$k]['process_type_id'] = $project['Project']['ProcessType_id'];
//                                $data['Product'][$k]['product_type_id'] = $project['Project']['ProductType_id'];
                                unset($data['Product'][$k]);
                            }
                        }
                        //Kiểm tra file đặc biệt đã có hay chưa
                        if (isset($feat)) {
                            if (isset($feat[$expname[0]])) {
//                                $check++;
//                                $data['Product'][$k]['id'] = $feat[$expname[0]]['id'];
                                if ($feat[$expname[0]]['url'] != '') {
                                    unset($data['Product'][$k]);
                                } else {
                                    unset($data['Product'][$k]);
                                    $data['Product'][$k]['id'] = $feat[$expname[0]]['id'];
                                    $data['Product'][$k]['url'] = $project['Project']['UrlFolder'] . '@' . $project['Project']['Code'] . '_Chua_giao';
                                    $data['Product'][$k]['sub_folder'] = $tam_folders[$k];
                                    $data['Product'][$k]['file_size'] = $size;
                                }
                            }
                        }
                    }
                    if (count($normal) <= ($project['Project']['Quantity'] - count($feat))) {
                        /////////Action.ID = 2 == Kích hoạt
                        $gpoint = $this->Action->find('first', array('conditions' => array('Action.ID' => 2)));
                        $point = 0;
                        /////// Tính điểm cho người kích hoạt: Điểm = số file x hệ số
                        if (!empty($gpoint)) {
                            $point = count($files) * $gpoint['Action']['Point'];
                        }
                        $data['ProjectAction'] = array(
                            'Project_id' => $project['Project']['ID'],
                            'Action_id' => 2,
                            'User_id' => $this->current_user['id'],
                            'Point' => $point,
                            'value' => count($files)
                        );
                        ///////////////Cập nhật trạng thái đơn hàng///////////////////////////
                        $project['Project']['Status_id'] = 2;
//                        $project['Project']['InitSize'] = $filesize;

                        //Add thời gian trả hàng dự kiến theo thời gian trả hàng trong nhóm khách hàng
                        if ($project['Project']['returnTime'] == '') {
                            if ($project['CustomerGroup']['id']) {
                                if ($project['CustomerGroup']['deliver_time']) {
                                    $project['Project']['returnTime'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')) + $project['CustomerGroup']['deliver_time'] * 3600);
                                } else {
                                    $project['Project']['returnTime'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')) + 12 * 3600);
                                }
                            } else {
                                $project['Project']['returnTime'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')) + 12 * 3600);
                            }
                        }
//                      Cập nhật Initsize
                        $product = $this->Product->find('all', array('conditions' => array('Project.ID' => $project_id)));
                        $j = 0;
                        foreach ($product as $pd) {
                            $j = $j + $pd['Product']['file_size'];
                        }
                        $project['Project']['InitSize'] = $j;
                        $project['Project']['ActiveTime'] = date("Y-d-m H:i:s");
                        //////////////////////////////////////


                        // in case of save product
                        if ($this->Product->saveMany($data['Product'], array('deep' => true)) && $this->ProjectAction->save($data['ProjectAction'])) {

                            //                      Cập nhật Initsize
                            $product = $this->Product->find('all', array('conditions' => array('Project.ID' => $project_id)));
                            $j = 0;
                            foreach ($product as $pd) {
                                $j = $j + $pd['Product']['file_size'];
                            }
                            $project['Project']['InitSize'] = $j;
                            if ($this->Project->save($project['Project'])) {
                                $output['returnCode'] = 1;
                                $output['returnMsg'] = "Kích hoạt thành công";
                                // Gửi mail khi kích hoạt
                                if (get($project['Project'], 'activemail') != 1) {
                                    if (get($project['Project'], 'email_1') == 1) {
                                        $email = $this->Email->findById('3');
                                        $customer_email = $this->Customer->findById($project['Project']['Customer_id']);
                                        $content_email = str_replace('#NAME_CUSTOMER#', $customer_email['Customer']['connector'] ? $customer_email['Customer']['connector'] : $customer_email['Customer']['name'], $email['Email']['content']);
                                        $content_email = str_replace('#NAME_PROJECT#', $project['Project']['Name'], $content_email);
                                        $today_project = date('d/m/Y H:i:s');
//                                $today_project = new DateTime($today_project);
////                                debug($customer_email);die;
//                                $tz = new DateTimeZone($customer_email['Country']['time_zone']);
//                                $today_project->setTimezone($tz);
//                                $date_email = $today_project->format('m/d/Y H:i:s'). ' '.$customer_email['Country']['time_zone'];
                                        $content_email = str_replace('#TIME_LATE#', $today_project, $content_email);
                                        $content_email = str_replace('#IMAGE#', '<img src="http://117.0.32.133:2015/pixel.png">', $content_email);
//                                debug($content_email);die;
                                        $this->send_email($customer_email['Customer']['email'], $project['Project']['Name'], $content_email, $project['Project']['EmailCc']);
                                        $this->Project->id = $project['Project']['ID'];
                                        $this->Project->saveField('activemail', 1);

                                    } else {
                                        if (get($project['Project'],'Email') != '') {
                                            $email = $this->Email->findById('3');
                                            $customer_email = $this->Customer->findById($project['Project']['Customer_id']);
                                            $content_email = str_replace('#NAME_CUSTOMER#', $customer_email['Customer']['connector'] ? $customer_email['Customer']['connector'] : $customer_email['Customer']['name'], $email['Email']['content']);
                                            $content_email = str_replace('#NAME_PROJECT#', $project['Project']['Name'], $content_email);
                                            $today_project = date('d/m/Y H:i:s');
//                                $today_project = new DateTime($today_project);
////                                debug($customer_email);die;
//                                $tz = new DateTimeZone($customer_email['Country']['time_zone']);
//                                $today_project->setTimezone($tz);
//                                $date_email = $today_project->format('m/d/Y H:i:s'). ' '.$customer_email['Country']['time_zone'];
                                            $content_email = str_replace('#TIME_LATE#', $today_project, $content_email);
                                            $content_email = str_replace('#IMAGE#', '<img src="http://117.0.32.133:2015/pixel.png">', $content_email);
                                            //debug($project['Project']['Email']);die;
                                            $this->send_email($project['Project']['Email'], $project['Project']['Name'], $content_email, $project['Project']['EmailCc']);
                                            $this->Project->id = $project['Project']['ID'];
                                            $this->Project->saveField('activemail', 1);
                                        }
                                    }
                                }
                            } else {
                                $output['returnCode'] = 0;
                                $output['returnMsg'] = "Có lỗi xảy ra trong quá trình kích hoạt 1. Hãy thử lại!";
                            }
                        } else {
                            $output['returnCode'] = 0;
                            $output['returnMsg'] = "Có lỗi xảy ra trong quá trình kích hoạt 2. Hãy thử lại!!";
                        }
                    } else {
                        $output['returnCode'] = 0;
                        $output['returnMsg'] = "Số File đặc biệt trong thư mục không đúng so với khai báo. Hãy thử lại!";
                    }
                } elseif ((count($files) + $checkfile) > $project['Project']['Quantity']) {
                    $output['returnCode'] = 0;
                    $output['returnMsg'] = "Số sản phẩm trong thư mục lớn hơn số lượng khai báo. Hãy thử lại!";
                } else {
                    $output['returnCode'] = 0;
                    $output['returnMsg'] = "Không có sản phẩm trong thư mục chưa giao. Hãy thử lại!";
                }
            } else {
                $output['returnCode'] = 0;
                $output['returnMsg'] = "Kiểm tra lại tên file và thử lại";
            }
        } else {
            $output['returnCode'] = 0;
            $output['returnMsg'] = "Có lỗi xảy ra trong quá trình kích hoạt 3. Hãy thử lại!";
        }
        $ftp->close();
        $this->RequestHandler->respondAs('json');
        $this->autoRender = false;
        return json_encode($output);
    }

    public function countProduct($project_id = null)
    {
        $project = $this->Product->find('count', array('conditions' => array('Product.project_id' => $project_id)));
        return $project;
    }

    public function delete($id = null)
    {
        $ftp = new FTPUploader($this->host, $this->user, $this->pass);
        $project = $this->Project->find('first', array('conditions' => array('Project.ID' => $id)));
        if (empty($project)) {
            throw new NotFoundException(__('Invalid project'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Project->delete($id)) {
            $folder = str_replace('@', '/', $this->dir . $project['Project']['UrlFolder']);
            $ftp->recursiveDelete($folder);
            $this->Product->deleteAll(array('Product.project_id' => $id), false);
            $this->ProjectAction->deleteAll(array('ProjectAction.Project_id' => $id), false);
            $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Dự án đã bị xóa.</div>'));
            $ftp->close();
            $this->redirect(Router::url(array('controller' => 'Projects', 'action' => 'index'), true));
        } else {
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể xóa dự án.</div>'));
        }
    }

//    public function deleteMulti($id = array())
//    {
//        $ftp = new FTPUploader($this->host,$this->user,$this->pass);
//        $projects = $this->Project->find('all', array('conditions' => array('Project.ID' => $id)));
//        if (empty($projects)) {
//            throw new NotFoundException(__('Invalid project'));
//        }
//        foreach($projects as $project){
//            if ($this->Project->delete($project['Project']['ID'])) {
//                $folder = str_replace('@', '/', $this->dir . $project['Project']['UrlFolder']);
//                $ftp->recursiveDelete($folder);
//                $this->Product->deleteAll(array('Product.project_id' => $project['Project']['ID']), false);
//                $this->ProjectAction->deleteAll(array('ProjectAction.Project_id' => $project['Project']['ID']), false);
//                $output[] = '<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Dự án đã bị xóa.</div>';
//            } else {
//                $output[] = '<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể xóa dự án.</div>';
//            }
//        }
//        $ftp->close();
//        $this->RequestHandler->respondAs('json');
//        $this->autoRender = false;
//        return json_encode($output);
//    }

    public function SelectUsers($number = null)
    {
        $this->layout = false;
        $departments = $this->Department->find('all');
        $users = $this->User->find('all',array(
            'conditions'=>array(
                'User.status' => 1
            )
        ));
        $dp = array();
        foreach ($departments as $department) {
            foreach ($users as $user) {
                if ($user['User']['department_id'] == $department['Department']['id']) {
//                    if($user['User']['ID']!=$this->Session->read('AdminUser.ID')){
                    $dp[$department['Department']['id']][] = $user['User'];
//                    }
                }
            }
        }
        $this->set(compact('dp', 'departments'));
        $this->set('number', $number);
    }

    public function SelectUsersSetpd($project_id = null)
    {
        $this->layout = false;
        $project = $this->Project->findById($project_id);
        if ($project['Project']['CustomerGroup_id'] != '' && $project['Project']['CustomerGroup_id'] != null) {
            $users = $this->User->find('all', array('conditions' => array('User.status' => 1, 'User.customer_group_id LIKE' => '%cusgr_' . $project['Project']['CustomerGroup_id'] . ',%')));
        } else {
            $users = $this->User->find('all', array('conditions' => array('User.status' => 1, 'User.customer_group_id LIKE' => '%cus_' . $project['Project']['Customer_id'] . ',%')));
        }
        $departments = $this->Department->find('all');

        $dp = array();
        foreach ($departments as $department) {
            foreach ($users as $user) {
                if ($user['User']['department_id'] == $department['Department']['id']) {
//                    if($user['User']['ID']!=$this->Session->read('AdminUser.ID')){
                    $dp[$department['Department']['id']][] = $user['User'];
//                    }
                }
            }
        }
        $this->set(compact('dp', 'departments'));
        $this->set('number', null);
    }

    public function SelectUsersReport($stt = null)
    {
        $this->layout = false;
        $departments = $this->Group->find('all');
        $users = $this->User->find('all');
        $dp = array();
        foreach ($departments as $department) {
            foreach ($users as $user) {
                if ($user['User']['group_id'] == $department['Group']['id']) {
//                    if($user['User']['ID']!=$this->Session->read('AdminUser.ID')){
                    $dp[$department['Group']['id']][] = $user['User'];
//                    }
                }
            }
        }

//        debug($stt);die;
        $this->set(compact('dp', 'departments', 'stt'));
    }

    public function RadioUsers($number = null)
    {
        $this->layout = false;
        $departments = $this->Department->find('all');
        $users = $this->User->find('all',array(
            'conditions'=>array(
                'User.status' => 1
            )
        ));
        $dp = array();
        foreach ($departments as $department) {
            foreach ($users as $user) {
                if ($user['User']['department_id'] == $department['Department']['id']) {
                    $dg = $this->Product->find('count', array('recursive' => -1,'conditions' => array('Product.deliver_user_id' => $user['User']['id'], 'Product.done_round' => 0)));
                    $dl = $this->Product->find('count', array('recursive' => -1,'conditions' => array('Product.deliver_user_id' => $user['User']['id'], 'Product.done_round >' => 0)));
//                    if($user['User']['ID']!=$this->Session->read('AdminUser.ID')){
                    $user['User']['dg'] = $dg;
                    $user['User']['dl'] = $dl;
                    $dp[$department['Department']['id']][] = $user['User'];
//                    }
                }
            }
        }
        $this->set(compact('dp', 'departments'));
        $this->set('number', $number);
    }

    public function deleteProduct($id = array())
    {
        $product = $this->Product->find('first', array('conditions' => array('Product.id' => $id)));
        if (!empty($product)) {
            $ftp = new FTPUploader($this->host, $this->user, $this->pass);
            $folder = str_replace('@', '/', $this->dir . $product['Product']['url'] . $product['Product']['sub_folder'] . '/' . $product['Product']['name_file_product']);
//            $this->Product->delete($id);
//            debug($folder);die;
            $rsdelete = $ftp->delete($folder);
//            debug($rsdelete);
            if ($rsdelete['resultCode'] == 1) {
                if ($this->Product->delete($id)) {
                    $output['resultCode'] = 1;
                    $output['resultMsg'] = 'Xóa sản phẩm thành công!';
                } else {
                    $output['resultCode'] = 0;
                    $output['resultMsg'] = 'Xóa sản phẩm thất bại! Hãy thử lại.';
                }
            } else {
                $output['resultCode'] = 0;
                $output['resultMsg'] = 'Xóa sản phẩm thất bại! Hãy thử lại.';
            }
        } else {
            $output['resultCode'] = 0;
            $output['resultMsg'] = 'Không tìm thấy thông tin sản phẩm! Hãy thử lại.';
        }
        $this->RequestHandler->respondAs('json');
        $this->autoRender = false;
        return json_encode($output);
    }

    public function bring($project_id = null)
    {
        $list = array();
        $project = $this->Project->find('first',
            array(
                'recursive' => -1,
                'contain' => array('Customer', 'CustomerGroup', 'User'),

                'conditions' => array('Project.ID' => $project_id
				)
            )
        );
        $product_comp = $this->Product->find('count',
            array(
                'recursive' => -1, //int
                'conditions' =>
                    array('Product.project_id' => $project_id,
			  'Product.done_round' => 1
		    )
            )
        );
        $this->set('count_pd', ($product_comp));
        if (!empty($project)) {
            $ftp = new FTPUploader($this->host, $this->user, $this->pass);
            $dir = str_replace('@', '/', $this->dir . $project['Project']['UrlFolder'] . '@DONE');
            $folders = Manager::listFolder($dir);

            $filedone = 0;
            $filecheck = 0;
            if ($folders != '' && count($folders) > 0) {
                $list = array();

                $_f = 0;

                $xcheck = array();
                //Đọc từng thư mục trong DONE
                foreach ($folders as $k => $folder)
                if (is_dir(Manager::$realFolder . $folder))
                {
                    $explo = explode('/', $folder);
//                    (count(explode('.', $explo[count($explo) - 1] )) < 2)

                    //Nếu không phải thư mục DONE chung của dự án
                    if (!is_file($folder) && ($explo[count($explo) - 1] != $project['Project']['Code'])) {
                        $f = explode('/', $folder);

//                        $childdir = str_replace('@', '/',$this->dir.$project['Project']['UrlFolder'].'@DONE@'.$folder);
                        $files = Manager::listFolder($folder);
                       
                        $list[$k]['dirname'] = $f[count($f) - 1];
                        if (is_array($files) && count($files) > 0) {
                            $tam = array();
                            $tam_folders = array();
                            //Đọc hết toàn bộ file trong từng thư mục

                            foreach ($files as $f) {
                                $xf = explode("/", $f);
                                if ($xf[count($xf) - 1] != "Thumbs.db") {

//                                    die($f);


                                    if ($ftp->file_size($f) > 0) {
                                        $tam[] = $f;
                                        $tam_folders[] = '';
                                    } else {
//                    $dir = str_replace('@', '/', $this->dir . $project['Project']['UrlFolder'] . '@' . $project['Project']['Code'] . '_Chua_giao@'.$f);
                                        $files_tam = Manager::listFolder($f);
//                    debug($files_tam);die;
                                        foreach ($files_tam as $ft) {
                                            $exp_folder = explode('/', $f);
                                            $tam[] = $ft;
                                            $tam_folders[] = '@' . $exp_folder[count($exp_folder) - 1];
                                        }
                                    }
                                    
                                }
                            }

                            $files = $tam;
                            $done = $check = 0;
                            $list[$k]['checker'] = array();
                            $list[$k]['doner'] = array();
                            $list[$k]['check'] = array();
                            $list[$k]['done'] = array();
                            $list[$k]['products'] = array();
                            $list[$k]['id_products'] = "";

                            if(@$_GET['show']){
                               debug($files);die;
                            }
//
                            foreach ($files as $j => $file) {
                                $file = str_replace('ÃƒÂ', 'Ã', $file);
                                if (count(explode('.', $file)) > 1) {
                                    $xxxfile = $file;
                                    $file = explode('/', $file);
                                    // chưa xử lý dc trường hợp vd abc.jpg.jpg
                                    $finish = explode('.', $file[count($file) - 1]);
//
                                    $tmp_file_name = "";
                                    for ($nf = 0; $nf < count($finish) - 1; $nf++) {
                                        $tmp_file_name .= $finish[$nf];
                                        if ($nf < count($finish) - 2) {
                                            $tmp_file_name .= ".";
                                        }
                                    }

                                    $file[count($file) - 1] = $tmp_file_name;
                                    //$file[count($file) - 1] = str_replace('.' . $finish[count($finish) - 1], '', $file[count($file) - 1]);


//                                    debug($file[count($file) - 1]);
                                    $productinfo = $this->Product->find('first',
                                        array(
                                            'fields' => array('id','name_file_product','done_round','deliver_user_id' ),
                                            'recursive' => -1, //int
                                            'conditions' => array(
                                                'Product.name_file_product LIKE' => "{$file[count($file) - 1]}%",
                                                'Product.project_id' => $project_id,
                                                'Product.done_round >' => 1
                                            ),
                                            'contain'=> array(
                                                'Project' => array(
                                                    'fields'=> array('ID','HasCheck')
                                                )
                                            ),
                                            'limit' => 1
                                        )
                                    );

//                                    debug($productinfo);
                                    if (!empty($productinfo)) {
                                        //Kiểm  tra 1 sản phẩm trong nhiều thư mục
//                                        if(in_array($productinfo['Product']['id'],$xcheck)){
//                                            echo $productinfo['Product']['id']."@".$xxxfile."<br>";
//                                        }else{
//                                            $xcheck[]=$productinfo['Product']['id'];
//                                            echo $productinfo['Product']['id']."@".$xxxfile."<br>";
//                                        }

                                        //echo $productinfo['Product']['project_id'];
                                        if ($project_id == 920) {
                                            // $_f++;
                                            // echo $_f;
                                            // debug($productinfo['Product']['id']);
                                            //debug($file[count($file) - 1]);
                                        }
                                        //DungHM: Notice
                                        //CHỗ này có vấn đề khi lấy sản phẩm theo LIKE %...%

                                        $checker = $this->CheckerProduct->find('first',
                                            array(
                                                'recursive' => 0, //int
                                                'contain' => array('Checker'),

                                                'conditions' => array(
                                                    'CheckerProduct.products' => $productinfo['Product']['id'],
                                                    'CheckerProduct.project_id' => $project_id
                                                ),
                                                'limit' => 1
                                            )
                                        );

                                        if (!empty($checker)) {
                                            if ($checker['CheckerProduct']['check'] == 1) {
                                                $check++;
                                                $filecheck++;
                                            }
                                            if ($checker['CheckerProduct']['done'] == 1) {
                                                $done++;
                                                $filedone++;
                                            }
                                        }
                                        $productinfo['Product']['Check'] = $checker;
                                        $list[$k]['products'][$j] = $productinfo;
                                        if (array_key_exists('id_products', $list[$k])) {
                                            $list[$k]['id_products'] .= $productinfo['Product']['id'] . ',';
                                        } else {
                                            $list[$k]['id_products'] = $productinfo['Product']['id'] . ',';
                                        }

                                        $list[$k]['deliver_id'] = $productinfo['Product']['deliver_user_id'];
                                        //Nếu trường hợp chuyển người khác làm thì lấy theo trường receive_user_id
//                                        if ($productinfo['Product']['receive_user_id']) {
//                                            //$list[$k]['deliver_id'] = $productinfo['Product']['receive_user_id'];
//                                        }
                                    }
                                    //                            $list[$k]['products'] = $productinfo['Product']['id'].',';
                                    $list[$k]['checker'] = isset($checker['Checker']['name']) ? $checker['Checker']['name'] : '';
                                    $list[$k]['doner'] = isset($checker['CheckerProduct']['doner']) ? $checker['CheckerProduct']['doner'] : '';
                                    $list[$k]['check'] = $check;
                                    $list[$k]['done'] = $done;
                                }
                            }
                        }
                    }
                }
            }

//            echo $filedone;
//             debug($list);die;
//            die();

            $this->Session->write('datachecker', $list);
            // pr($list);die;
            $this->set('project', $project);
            $this->set('list', $list);
            $this->set('currentUser', $this->current_user['name']);
            $this->set('dir', $this->dir);
            
            $this->set('filedone', $filedone);
            $this->set('domain', $this->domain);
        }
        $userpoints = $this->ProjectAction->find('all',
            array(
                'recursive' => 0, //int
                'conditions' => array('ProjectAction.Project_id' => $project_id)));
        $this->set('userpoints', $userpoints);
    }

    public function addChecker()
    {
        $output = 0;
        if ($this->request->is('post')) {
            $_temp = explode(",", $this->request->data['products']);
            foreach ($_temp as $_tid) {
                if ($_tid != '') {
                    $check = $this->CheckerProduct->find('first',
                        array('conditions' => array(
                            'CheckerProduct.project_id' => $this->request->data['project'],
                            'CheckerProduct.deliver_id' => $this->request->data['deliver'],
                            'CheckerProduct.products' => $_tid
                        )));
                    if (empty($check)) {
                        $datachecker['CheckerProduct'] = array(
                            'id' => null,
                            'project_id' => $this->request->data['project'],
                            'checker_id' => $this->request->data['checker'],
                            'deliver_id' => $this->request->data['deliver'],
                            'user_id' => $this->current_user['id'],
                            'products' => $_tid,
                            //'start_time' => date('Y-m-d H:i:s'),
                            'check' => 0,
                            'done' => 0,
                        );
                        if ($this->CheckerProduct->save($datachecker['CheckerProduct'])) {
                            $output = 1;
                        } else {
                            $output = 0;
                        }
                    } else {
                        $check['CheckerProduct']['checker_id'] = $this->request->data['checker'];
                        if ($this->CheckerProduct->save($check['CheckerProduct'])) {
                            $output = 1;
                        } else {
                            $output = 0;
                        }
                    }
                }
            }
        }
        $this->RequestHandler->respondAs('json');
        $this->autoRender = false;
        return json_encode($output);
    }

    public function check($project_id = null, $deliver_id = null, $k = null)
    {
        if ($this->Session->check('datachecker')) {
            $project = $this->Project->find('first', array('conditions' => array('Project.ID' => $project_id)));
            $product_comp = $this->Product->find('all', array('conditions' => array('Project.ID' => $project_id, 'Product.done_round >' => 1)));
            $this->set('count_pd', count($product_comp));
            $list = $this->Session->read('datachecker');

            $list = $list[$k];

            $_temp = explode(",", $list['id_products']);

            $_temp_condition = Array();
            if (count($_temp)) {

                //Mỗi lần check sản phẩm
                foreach ($_temp as $i => $pid) {
                    if (!empty($pid)) {
                        $_temp_condition['OR'][]['CheckerProduct.products'] = $pid;

                        //Kiểm tra lần lượt từng sản phẩm xem đã có trong bảng checker product chưa
                        $check = $this->CheckerProduct->find('first',
                            array('conditions' => array(
                                'CheckerProduct.project_id' => $project_id,
                                'CheckerProduct.deliver_id' => $deliver_id,
                                //_temp_condition
                                'CheckerProduct.products' => $pid
                            )));

                        //Nếu chưa thì thêm bản ghi mới và ser Check = 1
                        if (empty($check)) {

                            $this->CheckerProduct->create();
                            $datachecker['CheckerProduct'] = array(
                                'project_id' => $project_id,
                                'checker_id' => $this->current_user['id'],
                                'deliver_id' => $deliver_id,
                                'user_id' => $this->current_user['id'],
                                'products' => $pid,
                                'start_time' => date('Y-m-d H:i:s'),
                                'check' => 0,
                                'done' => 0,
                            );
                        } else {
                            //Nếu có rồi thì update Check = 1

                            $datachecker = $check;
                            $datachecker['CheckerProduct']['products'] = $pid;
                            $datachecker['CheckerProduct']['start_time'] = date('Y-m-d H:i:s');
//                            $datachecker['CheckerProduct']['check'] = 1;
                        }

                        $list['products'][$i]['Product']['Check'] = $datachecker;
//                        $datachecker['CheckerProduct']['check'] = 1;
                        if ($this->CheckerProduct->save($datachecker['CheckerProduct'])) {

                            $save = true;
                        }


                    }
                }
            }


            if ($save == true) {
                //////////////Cập nhật diểm số cho nhân viên done hàng ///////////////////////////////
                $gpoint = $this->Action->find('first', array('conditions' => array('Action.ID' => 6)));
                $point = 0;
                /////// Tính điểm cho người done: Điểm = số file x hệ số
                if (!empty($gpoint)) {
                    $point = count($list['products']) * $gpoint['Action']['Point'];
                }
                ////////Kiểm tra xem đã tính điểm chưa nếu chưa thì tính ngược lại thì cập nhật
                $checkpoint = $this->ProjectAction->find('first', array('conditions' => array('ProjectAction.User_id' => $this->current_user['id'], 'ProjectAction.Project_id' => $project_id, 'ProjectAction.Action_id' => 6)));
                if (empty($checkpoint)) {
                    $data['ProjectAction'] = array(
                        'Project_id' => $project_id,
                        'Action_id' => 6,
                        'User_id' => $this->current_user['id'],
                        'Point' => $point,
                        'value' => count($list['products'])
                    );
                } else {
                    $data = $checkpoint;
                    $data['ProjectAction']['Point'] = $point;
                }
//                $this->Session->write('datachecker',$list);
                $this->ProjectAction->save($data['ProjectAction']);
            } else {
                $this->redirect(Router::url(array('controller' => 'Projects', 'action' => 'bring', $project_id), true));
            }
//            pr($list['Product']['Check']);die;
            $this->set('list', $list);
            $this->set('project', $project);
            $this->set('dir', $this->dir);
            $this->set('domain', $this->domain);
        } else {
            $this->redirect(Router::url(array('controller' => 'Projects', 'action' => 'bring', $project_id), true));
        }
    }

    public function done($project_id = null, $deliver_id = null, $k = null)
    {
        $project = $this->Project->find('first', array('conditions' => array('Project.ID' => $project_id)));
        $list = $this->Session->read('datachecker');

        $list = $list[$k];
        $output = 1;
        $_temp = explode(",", $list['id_products']);

        foreach ($_temp as $_tid) {
            if ($_tid != '') {
                $check = $this->CheckerProduct->find('first',
                    array('conditions' => array(
                        'CheckerProduct.project_id' => $project_id,
                        'CheckerProduct.deliver_id' => $deliver_id,
                        'CheckerProduct.products' => $_tid

                    )));

                if (empty($check)) {
                    $this->CheckerProduct->create();
                    $check['CheckerProduct']['done'] = 1;
                    $check['CheckerProduct']['check'] = 1;
                    $check['CheckerProduct']['end_time'] = date('Y-m-d H:i:s');
                    $check['CheckerProduct']['project_id'] = $project_id;
                    $check['CheckerProduct']['deliver_id'] = $deliver_id;
                    $check['CheckerProduct']['products'] = $_tid;
                    $check['CheckerProduct']['user_id'] = $this->current_user['id'];
                    $check['CheckerProduct']['start_time'] = date('Y-m-d H:i:s');

                } else {
                    if ($check['CheckerProduct']['check'] == 0) {
                        //Lưu thông tin khi done_kt sản phảm
                        $check['CheckerProduct']['done'] = 1;
                        $check['CheckerProduct']['check'] = 1;
                        $check['CheckerProduct']['start_time'] = date('Y-m-d H:i:s');
                        $check['CheckerProduct']['end_time'] = date('Y-m-d H:i:s');
                    } else {
                        $check['CheckerProduct']['done'] = 1;
                    }

                }
                $check['CheckerProduct']['doner_id'] = $this->current_user['id'];
                if ($this->CheckerProduct->save($check['CheckerProduct'])) {
                    //////////////Cập nhật diểm số cho nhân viên done hàng ///////////////////////////////

                } else {
                    $output = 0;
                }
            }
        }

        if ($output != 0) {
            $gpoint = $this->Action->find('first', array('conditions' => array('Action.ID' => 7)));
            $point = 0;
            /////// Tính điểm cho người done: Điểm = số file x hệ số
            if (!empty($gpoint)) {
                $point = count($list['products']) * $gpoint['Action']['Point'];
            }
            ////////Kiểm tra xem đã tính điểm chưa nếu chưa thì tính ngược lại thì cập nhật
            $checkpoint = $this->ProjectAction->find('first', array('conditions' => array('ProjectAction.User_id' => $this->current_user['id'], 'ProjectAction.Project_id' => $project_id, 'ProjectAction.Action_id' => 7)));
            if (empty($checkpoint)) {
                $data['ProjectAction'] = array(
                    'Project_id' => $project_id,
                    'Action_id' => 7,
                    'User_id' => $this->current_user['id'],
                    'Point' => $point,
                    'value' => count($list['products'])
                );
            } else {
                $data = $checkpoint;
                $data['ProjectAction']['Point'] = $point;
            }
            $this->ProjectAction->save($data['ProjectAction']);
            //////////Copy file vào thư mục done dự án
            $output = $this->copyfiles($project['Project']['UrlFolder'] . '@DONE', $project['Project']['Code'], $list['dirname']);
            $this->changestatus($project['Project']['ID'], 5, 0);

        }
        $this->RequestHandler->respondAs('json');
        $this->autoRender = false;
        return json_encode($output);
    }

    public function changefile($product_id)
    {
        $product = $this->Product->find('first', array('conditions' => array('Product.id' => $product_id)));
        $done_pd = $this->DoneProduct->find('first', array(
            'conditions' => array('DoneProduct.product_id' => $product['Product']['id'])
        ));
//        debug($done_pd);
        $this->set('product', $product);
        $this->set('done_pd', $done_pd);
        if ($this->request->is('post')) {
            // update lại Compsize project
            $project = $this->Project->find('first', array('conditions' => array('Project.ID' => $product['Product']['project_id'])));
            $product_done_in_project = $this->Product->find('all', array('conditions' => array('Project.ID' => $product['Project']['ID'], 'Product.done_round >=' => '1')));
            $i = 0;
            foreach ($product_done_in_project as $pd) {
                $i = $i + $pd['Product']['done_size'];
            }
            $project['Project']['CompSize'] = $i;
            $this->Project->save($project);
//            pr($this->request->data);die;
            $ftp = new FTPUploader($this->host, $this->user, $this->pass);
//            $ftp->delete(str_replace('@', '/', $this->dir . $product['Product']['url'] . '@' . $product['Product']['name_file_product']));
//            pr($this->request->data);die;
            $this->request->data['Project']['File']['name'] = $this->char($this->request->data['Project']['File']['name']);
//            pr($this->request->data);die;
            $product['Product']['done_size'] = $this->request->data['Project']['File']['size'];
            $this->Product->save($product);
            $fileupload = $this->_uploadFiles(str_replace('@', '/', $this->dir . $product['Project']['UrlFolder'] . '@DONE@' . $product['Project']['Code'] . '-' . $product['Performer']['username'] . $product['Product']['sub_folder']), $this->request->data['Project']['File'], null);
            if (array_key_exists('urls', $fileupload)) {
                $this->request->data['Project']['File']['name'] = $fileupload['urls'][0];
                $this->request->data['Project']['File'] = str_replace("\\", '/', $this->request->data['Project']['File']['name']);
            }
            $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong>Thay thế File thành công</div>'));
            $this->redirect($this->referer());
        }
    }

    public function rejectCompany($product_id)
    {
        $product = $this->Product->find('first', array('conditions' => array('Product.id' => $product_id)));
        $this->set('product', $product);
        $this->set('dir', $this->dir);
        $this->set('domain', $this->domain);
        if ($this->request->is('post')) {
            $data['Productaction'] = array(
                'product_id' => $product_id,
                'user_id' => $this->current_user['id'],
                'note' => $this->request->data['Project']['Note'],
                'action_id' => 10,
                'date_feedback' => date('Y:m:d H:i:s'),
            );
            if ($this->Productaction->save($data['Productaction'])) {
                $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Sản phẩm đã bị reject.</div>'));
            } else {
                $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể reject sản phẩm. Hãy thử lại.</div>'));
            }
            $this->redirect($this->referer());
        }
    }

    public function download_all($project_id = null, $dirname = null)
    {
        $arr = array();
        $project = $this->Project->find('first', array('conditions' => array('Project.ID' => $project_id)));
        if (!empty($project)) {
            $ftp = new FTPUploader($this->host, $this->user, $this->pass);
            $childdir = str_replace('@', '/', $this->dir . $project['Project']['UrlFolder'] . '@DONE@' . $dirname);
            $files = Manager::listFolder($childdir);
            foreach ($files as $file) {
                if (count(explode('.', $file)) > 1) {
                    $arr[] = $this->domain . $file;
                }
            }
            if (!$this->download_as_zip($arr, $dirname)) {
                $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Có lỗi sảy ra! </strong> Bạn không thể download.</div>'));
                return $this->redirect(array('action' => 'index'));
            }
        }
    }

    public function getproductinfo($product_id)
    {
        $this->layout = false;
        $product = $this->Product->find('first', array('conditions' => array('Product.id' => $product_id)));
//        pr($product);die;
        $this->set('product', $product);
        $this->set('dir', $this->dir);
        $this->set('domain', $this->domain);
    }

    public function createprojectfirst()
    {
//        pr($_POST);die;
        if (isset($this->request->data['project_id']) && isset($this->request->data['product_id'])) {
            $project_id = $this->request->data['project_id'];
            $product_ids = $this->request->data['product_id'];
            $countproject = $this->Project->find('count', array('conditions' => array('Project.Parent_id' => $project_id)));
            $chkproject = $this->Project->find('first', array('conditions' => array('Project.ID' => $project_id), 'contain' => false));
            if (empty($chkproject)) {
                $output['resultCode'] = 0;
                $output['resultMsg'] = "Có lỗi xảy ra! Hãy thử lại.";
            } else {
                $ftp = new FTPUploader($this->host, $this->user, $this->pass);
                unset($chkproject['Project']['ID']);
                $this->request->data = $chkproject;
                $pro = $chkproject['Project']['Name'] . '_S' . ($countproject + 1);
                $this->request->data['Project']['User_id'] = $this->current_user['id'];
                $this->request->data['Project']['UrlFolder'] = $chkproject['Project']['UrlFolder'] . '_S' . ($countproject + 1);
                $this->request->data['Project']['Code'] = str_replace(' ', '_', $this->stripVietName($pro));
                $this->request->data['Project']['Name'] = $pro;
                $this->request->data['Project']['Quantity'] = count($product_ids);
                $this->request->data['Project']['Parent_id'] = $project_id;

                if ($this->request->data['Project']['InputDate'] != '') {
                    $this->request->data['Project']['InputDate'] = date('Y-m-d H:i:s');
                }

                ////////// Tạo ra các thư mục cần thiết của đơn hàng ////////////
                $ftp->make_directory(str_replace('@', '/', $this->dir . $this->request->data['Project']['UrlFolder'] . '@SUB'));
                $ftp->make_directory(str_replace('@', '/', $this->dir . $this->request->data['Project']['UrlFolder'] . '@' . $this->request->data['Project']['Code'] . '_Chua_giao'));
                $ftp->make_directory(str_replace('@', '/', $this->dir . $this->request->data['Project']['UrlFolder'] . '@DONE'));
                ////////////////////////////////////

                $this->Project->create();
                if ($this->Project->save($this->request->data['Project'])) {
                    ////////Cập nhật file đặc biệt nếu có/////
                    if (isset($product_ids)) {
                        $i = 0;
                        $products = $this->Product->find('all', array('conditions' => array('Product.id' => $product_ids), 'contain' => false));
//                        pr($products);die;
                        foreach ($products as $k => $product) {
//                            pr(str_replace('@', '/', $this->domain .$this->dir .$product['Product']['url'].'@'.$product['Product']['name_file_product']));
//                            pr(str_replace('@', '/', $this->dir .$product['Product']['url'].'@'.$product['Product']['name_file_product']));
//                            pr(str_replace('@', '/', $this->dir . $this->request->data['Project']['UrlFolder'] . '@Chua_giao@'.$product['Product']['name_file_product']));die;
                            $rscopy = $ftp->copy(str_replace('@', '/', $this->domain . $this->dir . $product['Product']['url'] . '@' . $product['Product']['name_file_product']), str_replace('@', '/', $this->dir . $product['Product']['url'] . '@' . $product['Product']['name_file_product']), str_replace('@', '/', $this->dir . $this->request->data['Project']['UrlFolder'] . "@" . $this->request->data['Project']['Code'] . '_Chua_giao@' . $product['Product']['name_file_product']));
                            if ($rscopy['resultCode'] == 1) {
                                $this->request->data['Product'][$k] = $product['Product'];
                                unset($this->request->data['Product'][$k]['id']);
                                $this->request->data['Product'][$k]['project_id'] = $this->Project->getInsertID();
                                $this->request->data['Product'][$k]['url'] = $this->request->data['Project']['UrlFolder'] . '@' . $this->request->data['Project']['Code'] . '_Chua_giao';
                            }
                        }
                        $this->Product->saveMany($this->request->data['Product'], array('deep' => true));
                    }
                    //////////Cập nhật vào bảng ProjectCom ///////////////

                    $data['ProjectCom'][] = array(
                        'Com_id' => 5,
                        'Quantity' => count($product_ids),
                        'ProductType_id' => 1,
                        'Project_id' => $this->Project->getInsertID()
                    );
                    /////////////////////////////
                    /*
                     * Lấy hệ số điểm từ bảng Action ứng với Action đã tác động lên đơn hàng
                     * Tính điểm cho người tạo và lưu vào ProjectAction
                    */

                    $gpoint = $this->Action->find('first', array('conditions' => array('Action.ID' => 1)));
                    $point = 0;
                    if (!empty($gpoint)) {
                        $point = $this->request->data['Project']['Quantity'] * $gpoint['Action']['Point'];
                    }
                    $data['ProjectAction'][] = array(
                        'Project_id' => $this->Project->getInsertID(),
                        'Action_id' => 1,
                        'User_id' => $this->current_user['id'],
                        'Point' => $point,
                        'value' => $this->request->data['Project']['Quantity']
                    );
                    /////////Action.ID = 2 == Kích hoạt
                    $gpoint = $this->Action->find('first', array('conditions' => array('Action.ID' => 2)));
                    $point1 = 0;
                    /////// Tính điểm cho người kích hoạt: Điểm = số file x hệ số
                    if (!empty($gpoint)) {
                        $point1 = count($this->request->data['Project']['Quantity']) * $gpoint['Action']['Point'];
                    }
                    $data['ProjectAction'][] = array(
                        'Project_id' => $this->Project->getInsertID(),
                        'Action_id' => 2,
                        'User_id' => $this->current_user['id'],
                        'Point' => $point1,
                        'value' => count($this->request->data['Project']['Quantity'])
                    );
                    ///////////////////////////////////////
                    if (count($data['ProjectCom']) > 0) {
                        if ($this->ProjectCom->saveMany($data['ProjectCom'], array('deep' => true)) && $this->ProjectAction->saveMany($data['ProjectAction'], array('deep' => true))) {
                            $output['resultCode'] = 1;
                            $output['Project_id'] = $this->Project->getInsertID();
                            $output['resultMsg'] = "Thành công! Dự án mới đã được tạo.";
                        } else {
                            $this->Project->delete($this->Project->getInsertID());
                            $output['resultCode'] = 0;
                            $output['resultMsg'] = "Thất bại! Không thể thêm dự án mới.";
                        }
                    } else {
                        if ($this->ProjectAction->save($data['ProjectAction'])) {
                            $output['resultCode'] = 1;
                            $output['Project_id'] = $this->Project->getInsertID();
                            $output['resultMsg'] = "Thành công! Dự án mới đã được tạo.";
                        } else {
                            $this->Project->delete($this->Project->getInsertID());
                            $output['resultCode'] = 0;
                            $output['resultMsg'] = "Thất bại! Không thể thêm dự án mới.";
                        }
                    }
                } else {
                    $output['resultCode'] = 0;
                    $output['resultMsg'] = "Thất bại! Dự án đã tồn tại trong hệ thống.";
                }
                $ftp->close();
            }
        } else {
            $output['resultCode'] = 0;
            $output['resultMsg'] = "Có lỗi xảy ra! Hãy thử lại.";
        }
        $this->RequestHandler->respondAs('json');
        $this->autoRender = false;
        return json_encode($output);
    }

    public function upload_done($project_id = null)
    {
        $this->layout = 'ajax';
        if ($this->request->is('post')) {
//            debug($this->request->data);
//            debug($project_id);
            $project = $this->Project->find('first', array('conditions' => array('Project.ID' => $project_id)));
            //Lưu số lượng file thực tế
//            if ($this->request->data['real_quality']) {
                $project['Project']['RealQuantity'] = $this->request->data['real_quality'];
                $project['Project']['complete_link'] = $this->request->data['pj_link'];
                $project['Project']['CompTime'] = date('Y-m-d H:i:s');
                $this->Project->id = $project_id;
                $this->Project->save($project['Project']);
//            }
            $output = $arr = array();

//        pr($project);die;
            if (!empty($project)) {
                $ftp = new FTPUploader($this->host, $this->user, $this->pass);
                $childdir = str_replace('@', '/', $this->dir . $project['Project']['UrlFolder'] . '@DONE@' . $project['Project']['Code']);
                $files = Manager::listFolder($childdir);
//            pr($files);
//            echo count($files);
//            die;
                $tam = array();
                $tam_folders = array();
                foreach ($files as $f) {
                    if ($ftp->file_size($f) > 0) {
                        $tam[] = $f;
                        $tam_folders[] = '';
                    } else {
//                    $dir = str_replace('@', '/', $this->dir . $project['Project']['UrlFolder'] . '@' . $project['Project']['Code'] . '_Chua_giao@'.$f);
                        $files_tam = Manager::listFolder($f);
//                    debug($files_tam);die;
                        foreach ($files_tam as $ft) {
                            $exp_folder = explode('/', $f);
                            $tam[] = $ft;
                            $tam_folders[] = '@' . $exp_folder[count($exp_folder) - 1];
                        }
                    }
                }
                $files = $tam;
                //Nếu số file trong thư mục done chưa đủ, thì không cho upload
                if (count($files) < $project['Project']['Quantity']) {
                    $output['resultCode'] = 0;
                    $output['resultMsg'] = "Lỗi không đủ file done của dự án, hãy kiểm tra và done hết trước khi upload!.";
                } else {


//            pr($files);die;
                    foreach ($files as $file) {
                        if (count(explode('.', $file)) > 1) {
                            $arr[] = $this->domain . $file;
                        }
                    }
//            pr($this->create_zip_from_url($arr,str_replace('\\', '/',WWW_ROOT. $project['Project']['Code'].'.zip')));die;
//                if (!$this->create_zip_from_url($arr, str_replace('\\', '/', WWW_ROOT . $project['Project']['Code'] . '.zip'))) {
//                    $output['resultCode'] = 0;
//                    $output['resultMsg'] = "Lỗi xảy ra trong quá trình upload! Hãy thử lại.";
//                } else {
//                    if ($project['Customer']['ftp'] && $project['Customer']['ftp_username'] && $project['Customer']['ftp_password']) {
//
//                    } else {
//                        $project['Customer']['ftp'] = $this->host;
//                        $project['Customer']['ftp_username'] = $this->user;
//                        $project['Customer']['ftp_password'] = $this->pass;
//
//                    }
//                    $customer_ftp = 1;
//                    $change = $ftp->change($project['Customer']['ftp'], $project['Customer']['ftp_username'], $project['Customer']['ftp_password']);
//                    if ($change) {
//                    } else {
//                        $customer_ftp = 0;
////                         $output['resultCode'] = 0;
////                         $output['resultMsg'] = "Lỗi không tìm thông tin FTP khách hàng! Hãy thử lại.";
//                        $change = $ftp->change($this->host, $this->user, $this->pass);
//                    }
//
//
//                    $result = $ftp->upload(str_replace('@', '/', WWW_ROOT . $project['Project']['Code'] . '.zip'), str_replace('@', '/', $this->dir . $project['Project']['UrlFolder'] . '@DONE@') . $project['Project']['Code'] . '.zip');
//                    if ($result['resultCode'] == 1) {
//                        unlink(WWW_ROOT . $project['Project']['Code'] . '.zip');
                    //////////////Cập nhật diểm số cho nhân viên done ///////////////////////////////
                    $gpoint = $this->Action->find('first', array('conditions' => array('Action.ID' => 8)));
                    $point = 0;
                    /////// Tính điểm cho người kích hoạt: Điểm = số file x hệ số
                    if (!empty($gpoint)) {
                        $point = $project['Project']['Quantity'] * $gpoint['Action']['Point'];
                    }
                    $data['ProjectAction'] = array(
                        'Project_id' => $project['Project']['ID'],
                        'Action_id' => 16,
                        'User_id' => $this->current_user['id'],
                        'Point' => $point,
                        'value' => $project['Project']['Quantity']
                    );
                    $this->ProjectAction->save($data['ProjectAction']);

//                        if ($email) {
                    $email = $this->Email->findById('2');
                    $customer_email = $this->Customer->findById($project['Customer']['id']);
                    $content_email = str_replace('#NAME_CUSTOMER#', $customer_email['Customer']['connector'] ? $customer_email['Customer']['connector'] : $customer_email['Customer']['name'], $email['Email']['content']);
                    $content_email = str_replace('#NAME_PROJECT#', $project['Project']['Name'], $content_email);
                    $today_project = date('d/m/Y H:i:s');
//                                $today_project = new DateTime($today_project);
////                                debug($customer_email);die;
//                                $tz = new DateTimeZone($customer_email['Country']['time_zone']);
//                                $today_project->setTimezone($tz);
//                                $date_email = $today_project->format('m/d/Y H:i:s'). ' '.$customer_email['Country']['time_zone'];
                    $content_email = str_replace('#TIME_COMP#', $today_project, $content_email);
                    $content_email = str_replace('#TIME_ACTIVE#', $project['Project']['InputDate'], $content_email);
                    $content_email = str_replace('#COUNT_FILE#', $project['Project']['RealQuantity'], $content_email);
                    $content_email = str_replace('#LINK#', $project['Project']['complete_link'], $content_email);
                    $content_email = str_replace('#IMAGE#', '<img src="http://117.0.32.133:2017/pixel.png">', $content_email);
//                                debug($content_email);die;
                    $cc = '';
                    if ($this->request->data['cc_email']) {
                        $cc = $this->request->data['cc_email'];
                    }

                    $mail_to = '';
                    if ($this->request->data['send_email']) {
                        $mail_to = $this->request->data['send_email'];
                    } else {
                        $mail_to = $customer_email['Customer']['email'];
                    }
                    if ($mail_to) {
                        //$this->send_email($mail_to, 'Projects ' . $project['Project']['Name'] . '  has done', $content_email, $cc);
                    }


//                        }

                    $output['resultCode'] = 1;

//                        if ($customer_ftp) {
//                            $output['resultMsg'] = "Upload thành công! Dữ liệu được upload lên " . $project['Customer']['ftp'] . '/' . $project['Project']['Code'] . '.zip';
//                        } else {
//                            $output['resultMsg'] = "Upload thành công! Dữ liệu được upload lên " . str_replace('@', '/', $this->dir . $project['Project']['UrlFolder'] . '@DONE@') . $project['Project']['Code'] . '.zip \n Click OK để hoàn thành';
//                        }


//                    } else {
//                        $output = $result;
                }

//                }
//            }
            } else {
                $output['resultCode'] = 0;
                $output['resultMsg'] = "Lỗi không tìm thấy đơn hàng! Hãy thử lại.";
            }
            $this->RequestHandler->respondAs('json');
            $this->autoRender = false;

            return json_encode($output);
        }

    }

    public function changestatusproject($project_id = null, $status = 0)
    {
        $output = $arr = array();
        $project = $this->Project->find('first', array('conditions' => array('Project.ID' => $project_id)));
        if (!empty($project)) {
            $project['Project']['Status_id'] = $status;
            $this->Project->id = $project['Project']['ID'];
            if ($this->Project->save($project['Project'])) {
                $output = 1;
            } else {
                $output = 0;
            }
        }
        $this->RequestHandler->respondAs('json');
        $this->autoRender = false;
        return json_encode($output);
    }

    public function changestatus($project_id = null, $status = 0, $redirect = 1)
    {
        $this->loadModel('Project');
        $this->loadModel('Product');
        $this->loadModel('CheckerProduct');
        $project = $this->Project->find('first', array('conditions' => array('Project.ID' => $project_id)));
//        debug($project);die;
        if (!empty($project)) {
            if ($status == 3) {
                if ($project['Project']['Status_id'] == 2) {
                    $check = $this->Product->find('count', array('conditions' => array('Product.project_id' => $project_id, 'Product.deliver_user_id >' => 0)));
                    $total = $project['Project']['Quantity'];
                    if ($check == $total) {
                        $project['Project']['Status_id'] = 3;
                        $this->Project->save($project['Project']);
                    }
                }
            }
            if ($status == 4) {
                if ($project['Project']['Status_id'] == 3) {
                    $check = $this->Product->find('count', array('conditions' => array('Product.project_id' => $project_id, 'Product.done_round' => array(0, 1))));
                    if ($check == 0) {
                        $project['Project']['Status_id'] = 4;
                        $this->Project->save($project['Project']);
                    }
                }
            }
            if ($status == 5) {

                if ($project['Project']['Status_id'] == 4) {
                    $countproducts = 0;
                    $getcheck = $this->CheckerProduct->find('all', array('conditions' => array('CheckerProduct.project_id' => $project_id)));
                    if (!empty($getcheck)) {
                        foreach ($getcheck as $check) {
                            $products = explode(',', $check['CheckerProduct']['products']);
                            $countproducts += count($products) - 1;
                        }
                    }
                    if ($countproducts == $project['Project']['Quantity']) {
                        $project['Project']['Status_id'] = $status;
                        $this->Project->save($project['Project']);

                    }
                }
                if ($redirect == 1) {
                    $this->redirect(Router::url(array('controller' => 'Projects', 'action' => 'bring', $project_id), true));
                } else {
                    return true;
                }
            }
        }
        $this->redirect(Router::url(array('controller' => 'Projects', 'action' => 'detail', $project_id), true));
    }

    public function auto_project()
    {
        $this->layout = 'ajax';
        if ($this->request->is('post')) {
            $keys = $this->request->data['items'];
//            print_r($keys);die;
            for ($i = 0; $i < count($keys); $i++) {
                $project = $this->Project->findById($keys[$i]);
                $project['Project']['auto'] = 1;
                $this->Project->save($project);
            }
            echo(count($keys));
        }
        exit;
    }

    public function cancel_deliver()
    {
        $this->layout = 'ajax';
        $product_id = $this->request->data('product_ids');
        $product_arr = explode(',', $product_id);
        $ftp = new FTPUploader($this->host, $this->user, $this->pass);
        for ($i = 0; $i < count($product_arr); $i++) {
            if ($product_arr[$i] != '') {
                $product_detail = $this->Product->findById($product_arr[$i]);
                $product_detail['Product']['deliver_user_id'] = 0;
                $product_detail['Product']['perform_user_id'] = 0;
                $product_detail['Product']['deliver_date'] = null;
                $url_file = $product_detail['Product']['url'] . $product_detail['Product']['sub_folder'];
                $dir = $product_detail['Project']['UrlFolder'] . '@' . $product_detail['Project']['Code'] . '_Chua_giao';
                $product_detail['Product']['url'] = $dir;
                $ftp->change($this->host, $this->user, $this->pass);
                $urlfile = str_replace('@', '/', $this->domain . $this->dir . $url_file . '@' . $product_detail['Product']['name_file_product']);
                $localfile = str_replace('@', '/', $this->dir . $url_file . '@' . $product_detail['Product']['name_file_product']);
                $dir = str_replace('@', '/', $this->dir . $dir . $product_detail['Product']['sub_folder']);
                $rsmove = $ftp->move($urlfile, $localfile, $dir . '/' . $product_detail['Product']['name_file_product']);
                $this->Product->save($product_detail);
                $working = $this->Working->find('first', array(
                    'conditions' => array('Working.product_id' => $product_arr[$i])
                ));
                $project = $this->Project->findById($product_detail['Product']['project_id']);
                $project['Project']['Status_id'] = 2;
                $this->Project->save($project);
                $this->Working->delete($working['Working']['id']);
            }
        }
        $ftp->close();
        exit;
    }

    public function form_reject()
    {
        $this->layout = 'ajax';
        $rejectGroup = $this->Ratepoint->find('list');
        $this->set('ratePoint', $rejectGroup);
        $project_id = $this->request->data['id'];
        if (!empty($this->request->data['id_product'])) {
            $product_id = $this->request->data['id_product'];
            $product = $this->Product->findById($product_id);
            $this->set('product', $product);
        }
        $rate = $this->request->data['rate'];
        $this->set('rate', $rate);
        $LargeGroup = $this->LargeGroup->find('list');
        $this->set('LargeGroup', $LargeGroup);
        $project = $this->Project->findById($project_id);
        $userpoints = $this->ProjectAction->find('all', array('conditions' => array('ProjectAction.Project_id' => $project_id)));
        $reject = $this->Reject->find('all', array('conditions' => array('Reject.project_id' => $project_id)));
        $new_reject = array();
        foreach ($reject as $item) {
            if ($item['Reject']['user_id_reject'] != null) {
                $new_reject[$item['Reject']['user_id_reject']]['percent'][] = $item['Reject']['percent'];
                $new_reject[$item['Reject']['user_id_reject']]['datetime'][] = date('d/m/Y H:i:s', strtotime($item['Reject']['datetime']));
                $new_reject[$item['Reject']['user_id_reject']]['action'][] = $item['Reject']['action_id'];
            }
//            if($item['Reject']['product_id'] != null && $item['Reject']['user_id_reject'] != null){
//                $new_reject[$item['Reject']['user_id_reject']][$stt]['percent'] =  $item['Reject']['percent'];
//                $new_reject[$item['Reject']['user_id_reject']][$stt]['datetime'] =  $item['Reject']['datetime'];
//            }
        }
        $this->set('userpoints', $userpoints);
        $this->set('project', $project);
        $this->set('new_reject', $new_reject);
        if (!empty($this->request->data['id_product'])) {
            $workings = $this->Working->find('all', array('conditions' => array('Working.product_id' => $this->request->data['id_product'], 'Product.project_id' => $project_id)));
            $this->set('workings', $workings);
        } else {
            $workings = $this->Reject->find('all', array('conditions' => array('Reject.project_id' => $project_id, 'Reject.action_id' => '100')));
            $this->set('workings', $workings);
        }
//        debug($working);die;
    }

    public function reject_check($project_id = null, $user_id = null, $action_id = null, $product_id = null)
    {
        if ($product_id == null) {
            $reject = $this->Reject->find('all', array(
                'conditions' => array(
                    'Reject.project_id' => $project_id,
                    'Reject.user_id_reject' => $user_id,
                    'Reject.action_id' => $action_id
                )));
        } else {
            $reject = $this->Reject->find('all', array(
                'conditions' => array(
                    'Reject.project_id' => $project_id,
                    'Reject.user_id_reject' => $user_id,
                    'Reject.action_id' => $action_id,
                    'Reject.product_id' => $product_id
                )));
        }
        return $rs = $reject;
    }

    public function checker_check($product_id = null)
    {
        $product = $this->CheckerProduct->find('first', array(
            'recursive' => -1, //int
            'conditions' => array(
                'CheckerProduct.products' => $product_id
            )));
        if (!$product || $product['CheckerProduct']['checker_id'] == 0 || $product['CheckerProduct']['checker_id'] == $this->Auth->user('id')) {

            return $rs = 1;

        } else {
            return $rs = 0;
        }
    }

    public function getProcess($prcess_type_id = null, $number = 0, $selected = null)
    {
        $this->layout = false;
        $process_type = $this->Processtype->find('list', array('conditions' => array('Processtype.process_type_group_id' => $prcess_type_id), 'fields' => array('Processtype.id', 'Processtype.name')));
        $this->set('process_type', $process_type);
        $this->set('number', $number);
        $this->set('selected', $selected);
    }

    public function okcheck($status = 0, $pd = null)
    {
        $pids = explode(',', $pd);
//        unset ($pids[count($pids)-1]);
//        debug($pd);die;
        foreach ($pids as $pid) {
            if ($pid != "") {
                $product = $this->Product->findById($pid);
                $checked = $this->CheckerProduct->find('first', array('conditions' => array('CheckerProduct.products' => $pid)));
                $checked['CheckerProduct']['check'] = 1;
                if (!$checked['CheckerProduct']['end_time']) {
                    $checked['CheckerProduct']['end_time'] = date("Y-m-d H:i:s");
                }
                $this->CheckerProduct->save($checked['CheckerProduct']);
            }
        }
        $this->changestatus($product['Project']['ID'], $status);
    }

    public function view_check($exten = "")
    {
        $exten_checks = $this->ProductExtension->find('all', array('conditions' => array('view_type' => 0), 'fields' => 'ProductExtension.name'));
        $rs = 0;
        foreach ($exten_checks as $exten_check) {
            if ($exten == $exten_check['ProductExtension']['name']) {
                $rs = 1;
            }
        }
        return $rs;
    }

    public function userupload($project_id)
    {
        $project = $this->Project->find('first', array('conditions' => array('Project.ID' => $project_id)));
        $gpoint = $this->Action->find('first', array('conditions' => array('Action.ID' => 8)));
        $point = 0;
        /////// Tính điểm cho người kích hoạt: Điểm = số file x hệ số
        if (!empty($gpoint)) {
            $point = $project['Project']['Quantity'] * $gpoint['Action']['Point'];
        }
        $data['ProjectAction'] = array(
            'Project_id' => $project_id,
            'Action_id' => 8,
            'User_id' => $this->current_user['id'],
            'Point' => $point,
            'value' => $project['Project']['Quantity']
        );
        $this->ProjectAction->save($data['ProjectAction']);
        die;
        return true;
    }

    public function check_project_name($name = "")
    {
        $result = 'OK';
        if (trim($name)) {
            $project = $this->Project->find('first', array('conditions' => array('OR' => array('Project.Name' => trim($name), 'Project.Code' => trim($name)))));
            if (count($project)) {
                $result = 'NOK';
            }
        } else {
            $result = 'OK';
        }
        die($result);
    }

    public function get($item, $key)
    {
        return isset($item[$key]) ? $item[$key] : "";
    }

}
