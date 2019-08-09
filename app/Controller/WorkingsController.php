<?php

/**
 * Created by PhpStorm.
 * User: MSc. Hoang Dung
 * Date: 11/6/14
 * Time: 5:25 PM
 */
 
 App::uses('ProductReturn', 'Model');
 App::uses('Product', 'Model');
 
class WorkingsController extends AppController
{
    public $uses = array('Productextension', 'ProductReturn', 'Productaction.product_id', 'Note', 'Productaction', 'Product', 'Project', 'Processtype', 'Working', 'Productcategory', 'Producttype', 'Doneproduct', 'Working', 'Group', 'Productaction', 'Customer', 'CheckerProduct');
    public $current_user;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->current_user = $this->Auth->user();
    }

    public function index()
    {
        $check_product = $this->CheckerProduct->find('all', array(
            'conditions' => array(
                'CheckerProduct.checker_id' => $this->Auth->user('id'),
                'CheckerProduct.check' => 0,
                'CheckerProduct.done' => 0
            ),
        ));
//        debug($check_product);die;
        $check = array();
        foreach ($check_product as $k => $check_pd) {
            $check[$check_pd['Project']['ID']] = $check_pd['Project'];
        }
        $product_deliver = $this->Product->find('all', array(
            'fields' => array('Project.UrlFolder', 'Product.note_file', 'Project.File', 'Project.duplicate', 'Product.id', 'Product.name_file_product', 'Product.sub_folder', 'Processtype.name', 'Processtype.time_counting', 'Product.url', 'Product.receive_user_id', 'Product.project_id', 'Product.done_round', 'Product.priority', 'Product.status'),
            'order' => array('Product.date_of_completion' => 'DESC', 'Product.priority' => 'DESC'),
            'conditions' => array('Product.perform_user_id' => $this->Auth->user('id'),
                'Product.done_round < ' => 2,
                'Product.status > ' => 0,
                array(
                    'OR' => array(
                        array('Product.receive_user_id' => null),
                        array('Product.receive_user_id' => $this->Auth->user('id'))
                    )
                )
            )
        ));
        $product = array();
        foreach ($product_deliver as $key => $item) {
            $product[$item['Product']['project_id']][$key] = $item;
            $abc = $this->Productaction->find('count', array(
                'conditions' => array(
                    'Productaction.product_id' => $item['Product']['id']
                )
            ));
            $product[$item['Product']['project_id']][$key]['feedback'] = $abc;
        }
//    debug($product_deliver);die;

        $check_status = $this->Working->find('all', array(
            'recursive' => -1, //int
            'conditions' => array(
                'Working.status' => 1,
                'Working.user_id' => $this->Auth->user('id'),
            ),
            'limit' => 1

        ));
        $productextension = $this->Productextension->find('list');
        $this->set('avatar', $this->Auth->user('avatar'));
        $this->set('productextension', $productextension);
        $this->set('dir', $this->dir);
        $this->set('product_deliver', $product);
        $this->set('domain', 'http://pixel-files.pixelvn.com/');
        $this->set('check_status', $check_status);
        $this->set('CF', $this->CF);
        $auto_project = $this->Project->find('count', array(
            'recursive' => -1, //int
            'fields' => array('Project.auto', 'Project.Status_id'),
            'conditions' => array('Project.auto' => 1, 'Project.Status_id <= ' => 2),
            'limit ' => 1
        ));
        
        $this->set('auto_project', ($auto_project));
        $this->set('check_product', $check_product);
        $this->set('check', $check);
        return $product_deliver;
    }

    public function auto_product()
    {
        $this->layout = 'ajax';
        $product = $this->Product->find('all', array(
            'conditions' => array(
                'Project.auto' => 1,
                'Project.Status_id' => 2,
                'Product.deliver_user_id' => 0,
            )
        ));

        if (count($product) > 0) {
            $dem = 0;
            $saveItem = array();
            foreach ($product as $key => $item) {
                if ($key < 2) {
                    $dem++;
                    $item['Product']['deliver_user_id'] = $this->Auth->user('id');
                    $item['Product']['perform_user_id'] = $this->Auth->user('id');

//                        $move = $this->movefiles($ids, $this->request->data['Project']['Project_id'], 0, $this->Auth->user('id'), 0);
                    $move = $this->movefiles($item['Product']['id'], $item['Product']['project_id'], 0, $this->Auth->user('id'), 0);
                    $item['Product']['deliver_date'] = date('Y-m-d H:i:s');
                    $saveItem[] = $item;

                }
            }
            if (count($saveItem)) {
                $this->Product->saveMany($saveItem);
            }
            echo $dem;
            exit;

        } else {
            echo 'NULL';
        }
        exit;
//        print_r($product);die;

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
                $uptoftp->make_directory(str_replace('@', '/', $this->dir . $project['Project']['UrlFolder'] . '@' . $project['Project']['Code'] . '-' . $user['User']['username']));
                $dir = $project['Project']['UrlFolder'] . '@' . $project['Project']['Code'] . '-' . $user['User']['username'];
            }

            $dir = str_replace('@', '/', $this->dir . $dir);


            //////////////Xử lý copy file sang folder mới và delete file cũ //////////
            foreach ($products as $product) {
                $uptoftp->change($this->host, $this->user, $this->pass);
                $urlfile = str_replace('@', '/', $this->domain . $this->dir . $product['Product']['url'] . '@' . $product['Product']['name_file_product']);
                $localfile = str_replace('@', '/', $this->dir . $product['Product']['url'] . '@' . $product['Product']['name_file_product']);

                $rsmove = $uptoftp->move($urlfile, $localfile, $dir . '/' . $product['Product']['name_file_product']);

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
                    $done['done'][$product['Product']['name_file_product']] = str_replace('@', '/', $this->domain . $dir . $project['Project']['UrlFolder'] . '@' . $project['Project']['Code'] . '-' . $user['User']['username'] . '@' . $product['Product']['name_file_product']);
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


    public function project_info($id = null)
    {
        $this->Project->recursive = 0;
        $name_project = $this->Project->find('first', array(
            'fields' => array('Project.name', 'Project.Require', 'Project.Note', 'Customer.name', 'Customer.customer_code', 'Project.UrlFolder', 'Project.File'),
            'conditions' => array('Project.ID' => $id)
        ));
//        debug($name_project);die;
        return $name_project;
    }

    public function time_work($product_id = null)
    {
        $get_status = $this->Working->find('first', array(
            'fields' => array('Working.process_time', 'Working.status', 'Working.process_time', 'Working.last_working', 'Working.start_time'),
            'conditions' => array(
                'Working.product_id' => $product_id,
                'Working.user_id' => $this->Auth->user('id'),
            ),
        ));
        if (count($get_status) == 0) {
            return 0;
        } else {
            if ($get_status['Working']['status'] == 1) {
                if ($get_status['Working']['last_working'] == '') {
                    return (strtotime(date("Y-m-d H:i:s")) - strtotime($get_status['Working']['start_time']));
                } else {
                    return (intval($get_status['Working']['process_time']) + intval((strtotime(date("Y-m-d H:i:s")) - strtotime($get_status['Working']['last_working']))));
                }
            } else {
                return $get_status['Working']['process_time'];
            }
        }
    }

    public function get_status($product_id = null)
    {
        $this->autoRender = false;
        $get_status = $this->Working->find('first', array(
            'fields' => array('Working.status'),
            'conditions' => array(
                'Working.product_id' => $product_id,
                'Working.user_id' => $this->Auth->user('id'),
            ),
        ));
        if (count($get_status) == 0) {
            return false;
        } else {
            return $get_status['Working']['status'];
        }
    }


    public function SelectUsers()
    {
        $this->layout = false;
        $departments = $this->Group->find('all');
        $users = $this->User->find('all');
        $dp = array();
        foreach ($departments as $department) {
            foreach ($users as $user) {
                if ($user['User']['group_id'] == $department['Group']['id']) {
                    $dp[$department['Group']['id']][] = $user['User'];
                }
            }
        }
        $this->set(compact('dp', 'departments'));
    }

    public function RadioUsers()
    {
        $this->layout = false;
        $departments = $this->Group->find('all');
        $users = $this->User->find('all');
        $dp = array();
        foreach ($departments as $department) {
            foreach ($users as $user) {
                if ($user['User']['group_id'] == $department['Group']['id']) {
                    $dp[$department['Group']['id']][] = $user['User'];
                }
            }
        }
        $this->set(compact('dp', 'departments'));
    }

    public function start()
    {
        if ($this->request->is('post')) {

            $check_status = $this->Working->find('first', array(
                'recursive' => -1, //int
                'conditions' => array(
                    'Working.user_id' => $this->Auth->user('id'),
//                    'product_id' => $this->request->data['product_id'],
                    'status' => '1'
                )
            ));


            if (count($check_status)) {
                echo "NO_OK";
            } else {
                $this->Working->Create();
                $start_time = date('Y-m-d H:i:s');
                $working = array();
                $working['Working']['product_id'] = $this->request->data['product_id'];
                $working['Working']['user_id'] = $this->Auth->user('id');
                $working['Working']['start_time'] = $start_time;
                $working['Working']['status'] = 1;
                if ($this->Working->save($working)) {
                    echo('OK');
                } else {
                    echo('NO_OK');
                }
            }
        }
        exit;
    }

    public function pause()
    {
        if ($this->request->is('post')) {

            $check_status = $this->Working->find('first', array(
                'recursive' => -1, //int
                'conditions' => array(
                    'product_id' => $this->request->data['product_id'],
                    'status' => '1'
                )
            ));


            if (count($check_status)) {
                $pause = $this->Working->find('first', array(
                    'conditions' => array(
                        'Working.product_id' => $this->request->data['product_id'],
                        'Working.user_id' => $this->Auth->user('id'),
                    ),
                    'order' => array('Working.id' => 'DESC')
                ));
                $start_time = date('Y-m-d H:i:s');
                $pause['Working']['end_time'] = $start_time;
                $pause['Working']['status'] = 2;
                if ($pause['Working']['last_working'] == '') {
                    $pause['Working']['process_time'] = intval($pause['Working']['process_time']) + intval((strtotime($pause['Working']['end_time']) - strtotime($pause['Working']['start_time'])));
                } else {
                    $pause['Working']['process_time'] = intval($pause['Working']['process_time']) + intval((strtotime($pause['Working']['end_time']) - strtotime($pause['Working']['last_working'])));
                }
                $pause['Working']['break'] = $pause['Working']['break'] + 1;
                if ($this->Working->save($pause)) {
                    echo gmdate("H:i:s", $pause['Working']['process_time']);
                } else {
                    echo('NO_OK');
                }
            } else {
                echo "NO_OK";

            }
        }
        exit;
    }

    public function continue_work()
    {
        if ($this->request->is('post')) {
            $check_status = $this->Working->find('first', array(
                'recursive' => -1, //int
                'conditions' => array(
                    'Working.user_id' => $this->Auth->user('id'),
                    'status' => '1'
                )
            ));


            if (count($check_status)) {
                echo "NO_OK";
            } else {
                $pause = $this->Working->find('first', array(
                    'conditions' => array(
                        'Working.product_id' => $this->request->data['product_id'],
                        'Working.user_id' => $this->Auth->user('id'),
                    ),
                    'order' => array('Working.id' => 'DESC')
                ));
                $start_time = date('Y-m-d H:i:s');
                $pause['Working']['last_working'] = $start_time;
                $pause['Working']['status'] = 1;
                if ($this->Working->save($pause)) {
                    echo('OK');
                } else {
                    echo('NO_OK');
                }
            }
        }
        exit;
    }

    public function done($product_id = null, $done = null)
    {
        $product = $this->Product->findById($product_id);
        if ($product_id != null) {
            $customer = $this->Customer->findById($product['Project']['Customer_id']);
            $this->set('country', $customer['Country']['name']);
        }
        $list_product_category = $this->Productcategory->find('list');
        $list_product_type = $this->Producttype->find('list');
        $this->set('done_product', $product);
        $this->set('list_product_category', $list_product_category);
        $this->set('list_product_type', $list_product_type);
        $this->set('dir', $this->dir);
        $this->set('domain', $this->domain);
        $this->set('done', $done);
        if ($this->request->is('post')) {
            $type = $this->request->data['done_type'];

            if (isset($_GET['debug']) && $_GET['debug']) {
                //move_uploaded_file($this->request->data["Working"]["name_file_product"]["tmp_name"], "abc.jpg");
                //print_r($this->request->data);//die;
            }

            $ftp = new FTPUploader($this->host, $this->user, $this->pass);
            $name_file_old = $this->request->data['Working']['name_file_old'];
            $name_file_done = "";
            $fullname_file_done = "";
            $filezise_done = 0;
            //Neu upload qua FTP
            if (isset($_GET['debug']) && $_GET['debug']) {
                //Kiem tra xem co file trong FTP cua user nay chua
                $FTPFiles = $ftp->listfiles('/FTP/Users/user1/project1/');
                $name_file_old_arr = explode('.', $name_file_old);
                unset($name_file_old_arr[count($name_file_old_arr) - 1]);
                $request_filename = implode('.', $name_file_old_arr);
                if (isset($this->request->data['Working']['product_extension_id']) && $this->request->data['Working']['product_extension_id'] != 11) {
                    $request_ext = $this->request->data['Working']['format_return'];
                    //Kiem tra tat ca cac file trong thu muc FTP hiien tai cua User
                } else {
                    $request_ext = "";
                }

                foreach ($FTPFiles as $ffile) {

                    //New co ten file nao thoa man dieu kien
                    //Co yeu cau ten file
                    $fullname = explode("/", $ffile);
                    $fname = $fullname[count($fullname) - 1];

                    if ($request_ext) {
                        if ($fname == $request_filename . "." . $request_ext && $ftp->file_size($ffile) > 0) {
                            $name_file_done = $fname;
                            $fullname_file_done = $ffile;
                            $filezise_done = $ftp->file_size($fullname_file_done);
                        }
                    } else {
                        if (strpos($fname, $request_filename) !== false && $ftp->file_size($ffile) > 0) {
                            $name_file_done = $fname;
                            $fullname_file_done = $ffile;
                            $filezise_done = $ftp->file_size($fullname_file_done);
                        }
                    }
                }

                if (!$name_file_done) {
                    $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Sản phẩm của bạn chưa được upload lên FTP, Hãy kiểm tra lại.</div>'));
                    $this->redirect(array('action' => 'done', $this->request->data['Working']['product_id'], $this->request->data['Working']['done']));
                }
            } else {
                $name_file_done = $this->request->data['Working']['name_file_product']['name'];
            }

            $done_name = $name_file_done;
            if (isset($this->request->data['Working']['product_extension_id']) && $this->request->data['Working']['product_extension_id'] != 11) {
                if ($name_file_done && $name_file_done != '') {
                    $name_file_done_arr = explode('.', $name_file_done);
                    $name_file_done_extension = $name_file_done_arr[count($name_file_done_arr) - 1];
                    unset($name_file_done_arr[count($name_file_done_arr) - 1]);
                    $name_file_done = implode('.', $name_file_done_arr);
                    $name_file_old_arr = explode('.', $name_file_old);
                    $name_file_old_extension = $name_file_old_arr[count($name_file_old_arr) - 1];
                    unset($name_file_old_arr[count($name_file_old_arr) - 1]);
                    $name_file_old = implode('.', $name_file_old_arr);


                    //if ((($name_file_done_extension == $this->request->data['Working']['format_return']) && ($name_file_old == $name_file_done)) || (($this->request->data['Working']['format_return'] == '') && ($name_file_old == $name_file_done) ) ){
                    $process_time = $this->Working->find('first', array(
                        'conditions' => array(
                            'Working.product_id' => $this->request->data['Working']['product_id'],
                            'Working.user_id' => $this->Auth->user('id'),
                        )
                    ));
                    $product = $this->Product->findById($this->request->data['Working']['product_id']);
                    $dir = str_replace('@', '/', $this->dir . $this->request->data['Working']['url'] . '@DONE@' . $product['Project']['Code'] . '-' . $this->Auth->user('username') . $product['Product']['sub_folder']);
                    $ftp->make_directory($dir);
                    if ($this->request->data['Working']['done'] == 1) {
                        $product['Product']['done_round'] = 1;
                        $product['Product']['date_of_completion'] = date('Y-m-d H:i:s');

                        if (isset($_GET['debug']) && $_GET['debug']) {
                            $product['Product']['done_size'] = $filezise_done;
                            exec("move \"" . str_replace("\\FTP", "FTP", str_replace("/", "\\", $this->projectFolder . $fullname_file_done)) . "\" \"" . $this->projectFolder . $dir . '/' . $done_name . "\"");

                        } else {
                            $product['Product']['done_size'] = $this->request->data['Working']['name_file_product']['size'];

                            /** luckymancvp */
                            $des = FTPUploader::$realFolder . $dir . '/' . $this->request->data['Working']['name_file_product']['name'];
                            $command = ("mv \"" . $this->request->data['Working']['name_file_product']['tmp_name'] . "\" \"" . $des . "\"");

                            $command = $command . ' 2>&1';
                            $fileupload = exec($command, $out);

                            $command = 'chmod 0777 "'. $des . '"';
                            $command = $command . ' 2>&1';
                            $fileupload = exec($command, $out);
                        }
                        // echo "move \"" . str_replace("/", "\\", $this->request->data['Working']['name_file_product']['tmp_name']) . "\" \"" . $this->projectFolder . $dir . '/' . $this->request->data['Working']['name_file_product']['name'] . "\"";
                        // 	die;
                        $this->Product->save($product);
                        $this->Doneproduct->Create();

                        $process_time['Working']['status'] = 3;
                        $this->Working->save($process_time);
                        $done_product = array();
                    }
                    if ($this->request->data['Working']['done'] == 2) {
                        $product['Product']['done_round'] = 2;
                        $product['Product']['date_of_completion'] = date('Y-m-d H:i:s');
                        if (isset($_GET['debug']) && $_GET['debug']) {
                            $product['Product']['done_size'] = $filezise_done;
                        } else {
                            $product['Product']['done_size'] = $this->request->data['Working']['name_file_product']['size'];
                        }
                        $product['Product']['done_size'] = $this->request->data['Working']['name_file_product']['size'];

                        $this->Product->save($product);
                        $done_product = $this->Doneproduct->find('first', array(
                            'conditions' => array(
                                'Doneproduct.product_id' => $this->request->data['Working']['product_id'],
                                'Doneproduct.user_id' => $this->Auth->user('id'),
                            )
                        ));
                    }
                    if ($this->request->data['Working']['done'] == 2) {
                        $filename = str_replace('@', '/', $this->dir . $this->request->data['Working']['url'] . '@DONE@' . $this->Auth->user('username') . '@' . $this->request->data['Working']['name_file_product']['name']);
                        $ftp->delete($filename);
                    }
//                      $fileupload = exec("move \"" . str_replace("/", "\\", $this->request->data['Working']['name_file_product']['tmp_name']) . "\" \"" . $this->projectFolder . $dir . '/' . $this->request->data['Working']['name_file_product']['name'] . "\"");
                    if (isset($_GET['debug']) && $_GET['debug']) {
                        exec("move \"" . str_replace("\\FTP", "FTP", str_replace("/", "\\", $this->projectFolder . $fullname_file_done)) . "\" \"" . $this->projectFolder . $dir . '/' . $done_name . "\"");

                    } else {
                        /** luckymancvp edit1 */
//                        $fileupload = exec("mv \"" . $this->request->data['Working']['name_file_product']['tmp_name'] . "\" \"" . $this->projectFolder . $dir . '/' . $this->request->data['Working']['name_file_product']['name'] . "\"");


                        /** luckymancvp */
                        $des = FTPUploader::$realFolder . $dir . '/' . $this->request->data['Working']['name_file_product']['name'];
                        $command = ("mv \"" . $this->request->data['Working']['name_file_product']['tmp_name'] . "\" \"" . $des . "\"");

                        $command = $command . ' 2>&1' . "";
                        $fileupload = exec($command, $out);

                        $command = 'chmod 0777 "'. $des . '"';
                        $command = $command . ' 2>&1';
                        $fileupload = exec($command, $out);
                    }

                    $done_product['Doneproduct']['name_file_done'] = $this->request->data['Working']['name_file_product']['name'];
                    $done_product['Doneproduct']['number_process'] = $this->request->data['Working']['number_process'];
                    $done_product['Doneproduct']['product_category_id'] = $this->request->data['Working']['product_category_id'];
                    $done_product['Doneproduct']['product_type_id'] = $this->request->data['Working']['product_type_id'];
                    $done_product['Doneproduct']['process_type_id'] = $this->request->data['Working']['process_type_id'];
                    $done_product['Doneproduct']['user_id'] = $this->Auth->user('id');
                    $done_product['Doneproduct']['date_return'] = date('Y-m-d H:i:s');
                    $done_product['Doneproduct']['process_time'] = $process_time['Working']['process_time'];
                    $done_product['Doneproduct']['product_id'] = $this->request->data['Working']['product_id'];
                    $done_product['Doneproduct']['url'] = $this->dir . $this->request->data['Working']['url'] . '@DONE@' . $this->Auth->user('username');
                    if ($this->Doneproduct->save($done_product)) {
                        $project = $this->Project->find('first', array('conditions' => array('Project.ID' => $product['Product']['project_id'])));
                        $product_done_in_project = $this->Product->find('all', array('conditions' => array('Project.ID' => $product['Project']['ID'], 'Product.done_round >=' => '1')));
                        $i = 0;
                        foreach ($product_done_in_project as $pd) {
                            $i = $i + get($pd['Product'], 'done_size');
                        }
                        $project['Project']['CompSize'] = $i;
                        $this->Project->save($project);
                        if ($this->request->data['Working']['done'] == 1) {
                            $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Sản phẩm trong trạng thái done lần 1.</div>'));
                        } else {
                            $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Sản phẩm đã được trả.</div>'));
                            $this->changestatus($this->request->data['Working']['project_id'], 3);

                        }
                        $this->redirect(array('action' => 'index'));
                    } else {
                        $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Bạn chưa trả được hàng</div>'));
                        $this->redirect(array('action' => 'index'));
                    }
                    // } else {
                    //     $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Sản phẩm bạn trả chưa đúng với định dạng yêu cầu hoặc chưa đúng tên.</div>'));
                    //     $this->redirect(array('action' => 'done', $this->request->data['Working']['product_id'], $this->request->data['Working']['done']));

                    // }
                }
            } else {
                if ($name_file_done && $name_file_done != '') {
                    $name_file_done_arr = explode('.', $name_file_done);
                    $name_file_done_extension = $name_file_done_arr[count($name_file_done_arr) - 1];
                    unset($name_file_done_arr[count($name_file_done_arr) - 1]);
                    $name_file_done = implode('.', $name_file_done_arr);
                    $name_file_old_arr = explode('.', $name_file_old);
                    $name_file_old_extension = $name_file_old_arr[count($name_file_old_arr) - 1];
                    unset($name_file_old_arr[count($name_file_old_arr) - 1]);
                    $name_file_old = implode('.', $name_file_old_arr);
                    if ($name_file_old == $name_file_done) {
                        $process_time = $this->Working->find('first', array(
                            'conditions' => array(
                                'Working.product_id' => $this->request->data['Working']['product_id'],
                                'Working.user_id' => $this->Auth->user('id'),
                            )
                        ));
                        $product = $this->Product->findById($this->request->data['Working']['product_id']);
                        if ($this->request->data['Working']['done'] == 1) {
                            $product['Product']['done_round'] = 1;
                            $product['Product']['date_of_completion'] = date('Y-m-d H:i:s');
                            $dir = str_replace('@', '/', $this->dir . $this->request->data['Working']['url'] . '@DONE@' . $product['Project']['Code'] . '-' . $this->Auth->user('username') . $product['Product']['sub_folder']);

                            if (false) {
                                $product['Product']['done_size'] = $filezise_done;
                                exec("mv \"" . str_replace("\\FTP", "FTP", str_replace("/", "\\", $this->projectFolder . $fullname_file_done)) . "\" \"" . $this->projectFolder . $dir . '/' . $done_name . "\"");
                            } else {
                                $product['Product']['done_size'] = $this->request->data['Working']['name_file_product']['size'];
                                $fileupload = $this->_uploadFiles($dir, $this->request->data['Working']['name_file_product'], null);

                            }

//                            $product['Product']['done_size'] = $this->request->data['Working']['name_file_product']['size'];
                            $this->Product->save($product);
                            $this->Doneproduct->Create();
//                            $fileupload = $this->_uploadFiles($dir, $this->request->data['Working']['name_file_product'], null);
                            $process_time['Working']['status'] = 3;
                            $this->Working->save($process_time);
                            $done_product = array();
                        }
                        if ($this->request->data['Working']['done'] == 2) {
                            $product['Product']['done_round'] = 2;
                            $product['Product']['date_of_completion'] = date('Y-m-d H:i:s');
                            $product['Product']['done_size'] = $this->request->data['Working']['name_file_product']['size'];
                            $this->Product->save($product);
                            $done_product = $this->Doneproduct->find('first', array(
                                'conditions' => array(
                                    'Doneproduct.product_id' => $this->request->data['Working']['product_id'],
                                    'Doneproduct.user_id' => $this->Auth->user('id'),
                                )
                            ));
                        }
                        if ($this->request->data['Working']['done'] == 2) {
                            $filename = str_replace('@', '/', $this->dir . $this->request->data['Working']['url'] . '@DONE@' . $this->Auth->user('username') . $product['Product']['sub_folder'] . '@' . $this->request->data['Working']['name_file_product']['name']);
                            $ftp->delete($filename);
                        }
//                        $fileupload = $this->_uploadFiles(str_replace('@', '/', $this->dir . $this->request->data['Working']['url'] . '@DONE@' . $product['Project']['Code'] . '-' . $this->Auth->user('username') . $product['Product']['sub_folder']), $this->request->data['Working']['name_file_product'], null);
                        if (false) {
                            exec("move \"" . str_replace("\\FTP", "FTP", str_replace("/", "\\", $this->projectFolder . $fullname_file_done)) . "\" \"" . $this->projectFolder . $dir . '/' . $done_name . "\"");

                        } else {
                            $fileupload = $this->_uploadFiles(str_replace('@', '/', $this->dir . $this->request->data['Working']['url'] . '@DONE@' . $product['Project']['Code'] . '-' . $this->Auth->user('username') . $product['Product']['sub_folder']), $this->request->data['Working']['name_file_product'], null);

                        }

                        $done_product['Doneproduct']['name_file_done'] = $this->request->data['Working']['name_file_product']['name'];
                        $done_product['Doneproduct']['number_process'] = $this->request->data['Working']['number_process'];
                        $done_product['Doneproduct']['product_category_id'] = $this->request->data['Working']['product_category_id'];
                        $done_product['Doneproduct']['product_type_id'] = $this->request->data['Working']['product_type_id'];
                        $done_product['Doneproduct']['process_type_id'] = $this->request->data['Working']['process_type_id'];
                        $done_product['Doneproduct']['user_id'] = $this->Auth->user('id');
                        $done_product['Doneproduct']['date_return'] = date('Y-m-d H:i:s');
                        $done_product['Doneproduct']['process_time'] = $process_time['Working']['process_time'];
                        $done_product['Doneproduct']['product_id'] = $this->request->data['Working']['product_id'];
                        $done_product['Doneproduct']['url'] = $this->dir . $this->request->data['Working']['url'] . '@DONE@' . $this->Auth->user('username');
                        if ($this->Doneproduct->save($done_product)) {
                            $project = $this->Project->find('first', array('conditions' => array('Project.ID' => $product['Product']['project_id'])));
                            $product_done_in_project = $this->Product->find('all', array('conditions' => array('Project.ID' => $product['Project']['ID'], 'Product.done_round >=' => '1')));
                            $i = 0;
                            foreach ($product_done_in_project as $pd) {
                                $i = $i + get($pd['Product'], 'done_size');
                            }
                            $project['Project']['CompSize'] = $i;
                            $this->Project->save($project);
                            if ($this->request->data['Working']['done'] == 1) {
                                $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Sản phẩm trong trạng thái done lần 1.</div>'));
                            } else {
                                $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Sản phẩm đã được trả.</div>'));
                                $this->changestatus($this->request->data['Working']['project_id'], 3);
                            }
                            $this->redirect(array('action' => 'index'));
                        } else {
                            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Bạn chưa trả được hàng</div>'));
                            $this->redirect(array('action' => 'index'));
                        }
                    } else {
                        $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Sản phẩm bạn trả chưa đúng tên.</div>'));
                        $this->redirect(array('action' => 'done', $this->request->data['Working']['product_id'], $this->request->data['Working']['done']));

                    }
                }
            }
            die;
            $ftp->close();

        }

    }

    public function changestatus($project_id = null, $status = 0)
    {
        $this->loadModel('Project');
        $this->loadModel('Product');
        $this->loadModel('CheckerProduct');
        $project = $this->Project->find('first', array('conditions' => array('Project.ID' => $project_id)));
        if (!empty($project)) {
            if ($status == 3) {
                if ($project['Project']['Status_id'] == 2) {
                    $check = $this->Product->find('count', array('conditions' => array('Product.project_id' => $project_id, 'Product.deliver_user_id' => 0)));
                    if ($check == 0) {
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
                            $countproducts += count($products);
                        }
                    }
                    if ($countproducts == $project['Project']['Quantity']) {
                        $project['Project']['Status_id'] = $status;
                        $this->Project->save($project['Project']);
                    }
                }
            }
        }
        $this->redirect(Router::url(array('controller' => 'Workings', 'action' => 'index'), true));
    }

    public function change($product_id = null)
    {
        $product = $this->Product->findById($product_id);
        $this->set('done_product', $product);
        $this->set('dir', $this->dir);
//        debug($this->request->data);die;
        if ($product['Product']['receive_user_id'] == null) {
            $ftp = new FTPUploader($this->host, $this->user, $this->pass);
            if ($this->request->is('post')) {
                $product_change = $this->Product->findById($this->request->data['Working']['product_id']);
                $product_change['Product']['receive_user_id'] = $this->request->data['NV_ID'];
                $product_change['Product']['perform_user_id'] = $this->request->data['NV_ID'];
                $product_change['Product']['note_product'] = $this->request->data['Working']['note'];
                $user_change = $this->User->findById($this->request->data['NV_ID']);
                $product_change['Product']['url'] = $product['Project']['UrlFolder'] . '@' . $product['Project']['Code'] . '-' . $user_change['User']['username'];
                $dir = str_replace('@', '/', $this->dir . $product['Project']['UrlFolder'] . '@' . $product['Project']['Code'] . '-' . $user_change['User']['username'] . $product['Product']['sub_folder']);
//                debug($dir);die;
                $ftp->make_directory($dir);
                if ($this->request->data['Working']['name_file_product'] != "") {
                    $filename = str_replace('@', '/', $this->dir . $this->request->data['Working']['url'] . '@' . $product['Product']['name_file_product']);
                    $fileupload = $this->_uploadFiles(str_replace('@', '/', $dir), $this->request->data['Working']['name_file_product'], null);
                    echo '444';
                    $ftp->delete($filename);
                }
                if ($this->Product->save($product_change)) {
                    $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Sản phẩm đã được chuyển.</div>'));
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Bạn chưa chuyển được hàng </div>'));
                    $this->redirect(array('action' => 'index'));
                }
            }
            $ftp->close();
        } else {
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Sản phẩm này đã được chuyển một lần </div>'));
            $this->redirect(array('action' => 'index'));
        }

    }

    public function feedback()
    {
        if ($this->request->is('post')) {
            $today = date('Y-m-d H:i:s');
            $product_id = explode(' ,', $this->request->data['Working']['product_id']);
            for ($i = 0; $i < count($product_id); $i++) {
                $this->Productaction->create();
                $product = array();
                $product['Productaction']['date_feedback'] = $today;
                $product['Productaction']['product_id'] = $product_id[$i];
                $product['Productaction']['note_id'] = $this->request->data['Working']['note_id'];
                $product['Productaction']['process_type_id'] = $this->request->data['Working']['process_type_id'];
                $product['Productaction']['product_extension_id'] = $this->request->data['Working']['product_extension_id'];
                $product['Productaction']['user_id'] = $this->Auth->user('id');
                $product['Productaction']['action_id'] = 12;
                $product['Productaction']['status'] = 0;
                $task = array();
                $task['product_id'] = $product_id[$i];
                $task['user_id_action'] = $this->Auth->user('id');
                $task['message'] = 'Feedback sản phẩm';
                $this->add_task($task);
                $this->Productaction->save($product);
            }
            $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Đã Feedback sản phẩm .</div>'));
            return $this->redirect(array('action' => 'index'));
        }
    }

    public function save_feedback()
    {
        $this->layout = 'ajax';
        $product_id = $this->request->data['product_id'];
        $process_type_id = $this->request->data['process_type_id'];
        $product_extension_id = $this->request->data['product_extension_id'];
        $table_product = $this->Product->findById($product_id);
        $table_product_action = $this->Productaction->find('first', array(
            'conditions' => array(
                'Productaction.product_id' => $product_id
            )
        ));
        $table_product['Product']['process_type_id'] = $process_type_id;
        $table_product['Product']['product_extension_id'] = $product_extension_id;
        $this->Product->save($table_product);
        if ($this->Productaction->delete($table_product_action['Productaction']['id'])) {
            echo 'OK';
        }
        exit;
    }

    public function cancel_feedback()
    {
        $this->layout = 'ajax';
        $product_id = $this->request->data['product_id'];
        $table_product_action = $this->Productaction->find('first', array(
            'conditions' => array(
                'Productaction.product_id' => $product_id
            )
        ));
        if ($this->Productaction->delete($table_product_action['Productaction']['id'])) {
            echo 'OK';
        }
        exit;
    }

    public function download_all()
    {
        $files = array(
            'export-excel.xls'
        );
        if (!$this->download_as_zip($files)) {
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Có lỗi sảy ra! </strong> Bạn không thể download.</div>'));
            return $this->redirect(array('action' => 'index'));
        }
    }

    public function done_all()
    {
        $this->layout = 'ajax';
        if ($this->request->is('post')) {
            $product = $this->request->data['product_id'];
            $product = explode(',', $product);
//            debug($product);die;

            $product_done_in_project = null;
            $project = null;
            $product_done = null;

            for ($i = 0; $i < count($product); $i++) {
                $product_done = $this->Product->findById($product[$i]);
                if ($product_done['Product']['done_round'] >= 1) {


                    $product_done['Product']['done_round'] = 2;

//                $product['Product']['done_size'] = $this->request->data['Working']['name_file_product']['size'];
                    $this->Product->save($product_done);

                    if (!$project)
                        $project = $this->Project->find('first', array('conditions' => array('Project.ID' => $product_done['Project']['ID'])));

                }
            }

            if ($product_done && $project) {
                if (!$product_done_in_project)
                    $product_done_in_project = $this->Product->find('all', array('conditions' => array('Project.ID' => $product_done['Project']['ID'], 'Product.done_round >' => '1')));
                $j = 0;
                foreach ($product_done_in_project as $pd) {
                    $j = $j + (get($pd['Product'],'done_size'));
                }
                $project['Project']['CompSize'] = $j;
                if ($project['Project']['Quantity'] == count($product_done_in_project)) {
                    //Thay đổi trạng thái dự án thành hoàn tất xử lý nếu tất cả các file đã done
                    $project['Project']['Status_id'] = 4;
                }
                $this->Project->id = $project['Project']['ID'];
                $this->Project->save($project['Project']);
            }

            echo('OK');
        }
    }

    public function list_note()
    {
        $note = $this->Note->find('list', array(
            'fields' => array('Note.id', 'Note.note')
        ));
        return $note;
    }

    public function list_process()
    {
        $note = $this->Processtype->find('list', array(
            'fields' => array('Processtype.id', 'Processtype.name')
        ));
        return $note;
    }

    public function product_return()
    {
        if ($this->request->is('post')) {
            $keys = $this->request->data['items'];
            $data = array();
            for ($i = 0; $i < count($keys); $i++) {
                $this->ProductReturn->Create();
                $data['ProductReturn']['product_id'] = $keys[$i];
                $data['ProductReturn']['status'] = 0;
                $data['ProductReturn']['date_return'] = date("Y-m-d H:i:s");
                $data['ProductReturn']['user_id'] = $this->Auth->user('id');
                $this->ProductReturn->save($data);
                
                $working = $this->Product->findById($keys[$i]);
                $working['Product']['status'] = 0;
                $working['Product']['deliver_user_id'] = 0;
                $this->Product->save($working);
                
                $project = $this->Project->findById($working['Project']['ID']);
                $project['Project']['Status_id'] = 2;
                $this->Project->save($project['Project']);
            }
            $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Bạn đã trả lại sản phẩm.</div>'));
        }
        echo "OK";
        exit;
    }

    public function save_return()
    {
        $this->layout = 'ajax';
        if ($this->request->is('post')) {
            if($this->request->data['state'] == 1){ // state = 1 is accept
                $this->accept_return($this->request->data['product_return_id']);    
            }else{ // state != 1 is not accept
                $this->reject_return($this->request->data['product_return_id']);
            }
        }
        echo 'OK';
        exit;
    }
    

    public function view_list_product()
    {
        $list_product_feedback = $this->Productaction->find('all', array(
            'conditions' => array(
                'Productaction.status' => 0
            )
        ));
        $list_product_return = $this->ProductReturn->find('all', array(
            'limit' => 20,
            'conditions' => array(
                // 'ProductReturn.status' => 0
            ),
            'order' => array('ProductReturn.id' => 'DESC')
        ));
        
        $this->set('list_product_feedback', $list_product_feedback);
        $this->set('list_product_return', $list_product_return);
    }

    public function check_status($product_id = null)
    {
        
       $status = $this->Working->find('all', array(
            'conditions' => array(
                'Working.product_id' => $product_id,
                'Working.status' => 1,
                'Working.user_id' => $this->Auth->user('id')
            )
        ));
        return count($status);
    }

    public function please_product()
    {
        $this->layout = 'ajax';
        $task = array();
        $task['user_id_action'] = $this->Auth->user('id');
        $task['message'] = 'Xin chia hàng';
        $this->add_task($task);
        exit;
    }
    
    public function reject_return($product_return_id)
    {
        $table_product_return = $this->ProductReturn->findById($product_return_id);
        
        if (!count($table_product_return))
            return;
            
        $product_id = $table_product_return['ProductReturn']['product_id'];
        $table_product = $this->Product->findById($product_id);
        
        $this->ProductReturn->delete($product_return_id);
        if (count($table_product)) {
            $table_product['Product']['status'] = Product::STATUS_DA_CHIA;
            $this->Product->save($table_product);
        }
    }
    
    public function accept_return($product_return_id)
    {
        $ftp = new FTPUploader($this->host, $this->user, $this->pass);
        $table_product_return = $this->ProductReturn->findById($product_return_id);
        
        if (!count($table_product_return))
            return;
            
        $product_id = $table_product_return['ProductReturn']['product_id'];
        $table_product = $this->Product->findById($product_id);
        
        $table_product['Product']['status'] = 1;
        $table_product['Product']['perform_user_id'] = 0;
        $table_product['Product']['deliver_date'] = null;
        $table_product['Product']['deliver_user_id'] = 0;
        $table_product['Product']['status'] = 1;
        
        $dir = '';
        if ($table_product['Product']['name_file_product'] != '') {
            $ftp->change($this->host, $this->user, $this->pass);
            $urlfile = str_replace('@', '/', $this->domain . $this->dir . $table_product['Product']['url'] . $table_product['Product']['sub_folder'] . '@' . $table_product['Product']['name_file_product']);
            $localfile = str_replace('@', '/', $this->dir . $table_product['Product']['url'] . $table_product['Product']['sub_folder'] . '@' . $table_product['Product']['name_file_product']);
            $dir = $table_product['Project']['UrlFolder'] . '@' . $table_product['Project']['Code'] . '_Chua_giao' . $table_product['Product']['sub_folder'];
            $dir = str_replace('@', '/', $this->dir . $dir);
            $rsmove = $ftp->move($urlfile, $localfile, $dir . '/' . $table_product['Product']['name_file_product']);
            $table_product['Product']['url'] = $table_product['Project']['UrlFolder'] . '@' . $table_product['Project']['Code'] . '_Chua_giao';
            $this->Product->save($table_product);
            $project = $this->Project->findById($table_product['Product']['project_id']);
            $project['Project']['Status_id'] = 2;
            $this->Project->save($project);
            $check_feedback = $this->Productaction->find('first', array(
                'conditions' => array(
                    'Productaction.product_id' => $product_id
                )
            ));


            $check_working = $this->Working->find('first', array(
                'conditions' => array(
                    'Working.product_id' => $product_id,
                    'Working.user_id' => $this->Auth->user('id')
                )
            ));
            if (count($check_working)) {
                if ($this->Working->delete($check_working['Working']['id'])) {

                }
            }
            if (count($check_feedback)) {
                if ($this->Productaction->delete($check_feedback['Productaction']['id'])) {

                }
            }
            
            $this->ProductReturn->delete($product_return_id);

        }
        $ftp->close();
    }

    public function return_product()
    {
        $this->layout = 'ajax';
        if ($this->request->is('post')) {
            $product_id = $this->request->data['product_id'];
            $product_id = explode(',', $product_id);
            $note = $this->request->data['note'];
//            print_r($product_id);
//            die;
            $ftp = new FTPUploader($this->host, $this->user, $this->pass);
            
            for ($i = 0; $i < count($product_id); $i++) {
                $this->ProductReturn->Create();
                $data['ProductReturn']['product_id'] = $product_id[$i];
                $data['ProductReturn']['status'] = ProductReturn::STATUS_WANT_RETURN;
                $data['ProductReturn']['date_return'] = date("Y-m-d H:i:s");
                $data['ProductReturn']['user_id'] = $this->Auth->user('id');
                $data['ProductReturn']['note'] = $note;
                $this->ProductReturn->save($data);
                
                
                $table_product = $this->Product->findById($product_id[$i]);
                if (count($table_product)) {
                    $table_product['Product']['status'] = Product::STATUS_DANG_REJECT;
                    $this->Product->save($table_product);
                }
                
                
            }
            $ftp->close();
        }
        echo "OK";
        exit;
    }

    public function product_check()
    {
        $product_checks = $this->CheckerProduct->find('all', array(
            'conditions' => array(
                'CheckerProduct.checker_id' => $this->current_user['id'],
                'CheckerProduct.check' => 0
            )));
//        pr($product_checks);die;
        foreach ($product_checks as $product_check) {
//            $product_check['CheckerProduct']['check'] = 1;
            $product_check['CheckerProduct']['start_time'] = date('Y-m-d H:i:s');
            $this->CheckerProduct->save($product_check);
        }
        $this->redirect(Router::url(array('controller' => 'Workings', 'action' => 'index')));
    }

    public function product_done()
    {
        $product_dones = $this->CheckerProduct->find('all', array(
            'conditions' => array(
                'CheckerProduct.checker_id' => $this->current_user['id'],
                'CheckerProduct.check' => 0
            )));
        foreach ($product_dones as $product_done) {
            $product_done['CheckerProduct']['check'] = 1;
            $product_done['CheckerProduct']['end_time'] = date('Y-m-d H:i:s');
            $this->CheckerProduct->save($product_done);
        }
        $this->redirect(Router::url(array('controller' => 'Workings', 'action' => 'index')));

//        $product_done['Product']['done_size'] = $this->request->data['Working']['name_file_product']['size'];


        if ($this->CheckerProduct->save($product_done)) {
            //1: workman.media/Projects/...user/abc.jpg;   This->domain + replace @ -> / ($this->dir + ProjectFolder) + DONE + ProjectCode + _ + UserAcc + filename
            //2: /Projects/...user/abcc.jpg   ;   ProjectFolder + DONE + ProjectCode + _ + UserAcc + filename
            //3: /Projects/..../abc.jpg ;  ProjectFolder + DONE + ProjectCode + _ + UserAcc + filename
            $user = $this->Auth->user();
            $from = str_replace('@', '/', $this->dir . $product_done['Project']['UrlFolder'] . '@DONE@' . $product_done['Project']['Code'] . '-' . $user['username'] . $product_done['Product']['sub_folder'] . '@' . $product_done['Product']['name_file_product']);
            $to = str_replace('@', '/', $this->dir . $product_done['Project']['UrlFolder'] . '@DONE@' . $product_done['Project']['Code'] . $product_done['Product']['sub_folder']);
//            pr($from);pr($to);die;
            /** Khong hiểu tại sao lại bị lỗi */
            $ftp->make_directory($to);
            $ftp->copy($this->domain . $from, $from, $to . '/' . $product_done['Product']['name_file_product']);
            $this->redirect(Router::url(array('controller' => 'Workings', 'action' => 'index')));
        }
    }

    public function working_time($product_id = null)
    {
        $rs = $this->Working->find('first', array('conditions' => array('Working.product_id' => $product_id)));
        return $rs;
    }
}
