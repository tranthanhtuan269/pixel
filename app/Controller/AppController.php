<?php
App::uses('Controller', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('File', 'Utility');
App::uses('Manager', 'Lib');
App::import("Vendor", "FTPUploader");

class AppController extends Controller
{
    public $helpers = array(
        'Html',
        'Form',
        'Session',
        'Text',
        'Time',
        'Js',
        'Cache',
        'Paginator',
        'PHPExcel'

    );

    public $uses = array('Productextension','Config', 'Timelogin', 'User', 'Notification', 'WorkDepartment','TaskManager');

    public $components = array(
        'Acl',
        'Cookie',
        'Auth' => array(
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'username'),
                    'scope' => array('User.status' => 1)
                )
            ),
            'authorize' => array(
                'Actions' => array('actionPath' => 'controllers')
            )
        ),
        'Session',
        'UploadImage',
        'Paginator',
        'RequestHandler'
    );
  public function add_task($arr = array())
    {
        $this->TaskManager->create();
        $data = array();
        if ($arr['product_id'] && $arr['product_id'] != '') {
            $data['TaskManager']['product_id'] = $arr['product_id'];
        }
        if (isset($arr['user_id_action']) && $arr['user_id_action'] != '') {
            $data['TaskManager']['user_id_action'] = $arr['user_id_action'];
        }
        if (isset($arr['message']) && $arr['message'] != '') {
            $data['TaskManager']['message'] = $arr['message'];
        }
        if (isset($arr['type']) && $arr['type'] != '') {
            $data['TaskManager']['type'] = $arr['type'];
        }
        if (isset($arr['note']) && $arr['note'] != '') {
            $data['TaskManager']['note'] = $arr['note'];
        }
        $data['TaskManager']['time'] = date('Y-m-d H:i:s');
        if($this->TaskManager->save($data)){
            $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Đã lưu csdl </div>'));
        }else{
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể lưu csdl </div>'));
        }
    }


    public function beforeFilter()
    {
        date_default_timezone_set("Asia/Bangkok");
        $edit_profile_user = $this->Auth->user();
        if (!empty($edit_profile_user)) {
            $edit_profile = $this->User->findById($edit_profile_user['id']);
//            debug($edit_profile);die;
            $this->set('edit_profile', $edit_profile);
        }
        $time_logout = $this->Timelogin->find('first', array(
            'recursive' => 0, //int
            'conditions' => array('Timelogin.user_id' => $edit_profile_user['id']),
            'order' => array('Timelogin.id' => 'DESC')
        ));
        $to_day = date("Y-m-d");
//        echo $to_day;die;
        $this->set('to_day', $to_day);
//        $user_time = $this->Timelogin->find('all', array(
//                'limit' => 3,
//                'order' => array('Timelogin.id' => 'DESC'),
//                'conditions' => array('Timelogin.user_id' => $edit_profile_user['id'], 'Timelogin.time_login LIKE ' => '%' . $to_day . '%'))
//        );
//        if (!empty($user_time)) {
//            $this->set('user_time', $user_time);
//        }
//        debug($user_time);die;
        if (!empty($time_logout)) {
            $this->set('time_logout', $time_logout['Timelogin']['time_login']);
        }
        //debug($edit_profile);die;
        //Kiểm tra loại định dạng//
        $product_extension = $this->Productextension->find('all',array(
            'recursive' => 0, //int
            'conditions' => array('Productextension.view_type' => 0)
        ));
        $value_extensions = array();
        foreach($product_extension as $pe){
            $value_extensions[$pe['Productextension']['name']] = $pe['Productextension']['view_type'];
        }
        $this->set('PE', $value_extensions);
        $this->PE = $value_extensions;
        $configs = $this->Config->find('all');
        $values = array();
        foreach ($configs as $cf) {
            $values[$cf['Config']['code']] = $cf['Config']['value'];
        }
        $this->set('CF', $values);
        $this->CF = $values;
        $this->set('domain',$this->domain);
        $this->set('group_id_user',$this->Auth->user('group_id'));
        //tìm trạng thái start/stop thời gian làm việc với khách hàng
        $work_department = $this->WorkDepartment->find('all',array(
            'fields' => array('WorkDepartment.id','WorkDepartment.user_id','WorkDepartment.status'),
            'recursive' => 0, //int
            'conditions' => array(
                'WorkDepartment.user_id' => $this->Auth->user('id'),
                'WorkDepartment.status' => 1
            )
        ));
        $dem_customer = count($work_department);
        $this->set('dem_customer',$dem_customer);
        $this->dem_customer = $dem_customer;
        parent::beforeFilter();

        //$this->Auth->allow( );//must comment after generate action

//        //Configure AuthComponent
        $this->Auth->loginAction = '/users/login';
        $this->Auth->logoutRedirect = '/users/login';
        $this->Auth->loginRedirect = array(
            'plugin' => false,
            'controller' => 'pages',
            'action' => 'index'
        );
//        debug($this->Auth->user());die;
        if (isset($this->params['prefix']) && $this->params['prefix'] == 'admin') {
            $this->layout = 'default';
        }
        $vacation_states = array('0' => 'Không phê duyệt', '1' => 'Phê duyệt', '2' => 'Đang chờ phê duyệt');
        $this->set('vacation_states', $vacation_states);
        //Tinh so tin nhan chua doc
		$authUser =$this->Auth->user(); 
        $sum = $this->Notification->find('count',
             array('conditions' => array(
                 'touser_id' => $authUser['id'])
             ));
         $read = $this->Notification->find('count',
             array('conditions' => array(
                 'touser_id' => $authUser['id'],
                 'read_id LIKE' => '%'.$authUser['id'].',%')
             ));
         if ($sum<=$read){
             $notRead = 0;
         } else {
             $notRead = $sum - $read;
         }
 //       $notRead = 1;
        $this->set('notRead',$notRead);
        
    }

    public $dir = "Projects@";
    public $domain = "http://localhost/pixelvn/";
    public $host = 'localhost';
    public $user = 'pixel_vp';
    public $pass = ')oKmNji9';
    public $projectFolder = "Giao_nhan_hang";


    function stripVietName($str)
    {
        if (!$str) return false;
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ',
            'D' => 'Đ',
            'E' => 'È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ',
            'I' => 'Ì|Í|Ị|Ỉ|Ĩ',
            'O' => 'Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ',
            'U' => 'Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ',
            'Y' => 'Ỳ|Ý|Ỵ|Ỷ|Ỹ',
        );
        foreach ($unicode as $nonUnicode => $uni)
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        return $str;
    }

    function _uploadFiles($folder, $file, $itemId = null)
    {
//        die($folder . " " . json_encode($file) . " " . $itemId);
        $ftp = new FTPUploader($this->host,$this->user,$this->pass);
        // setup dir names absolute and relative

        $folder_url = $folder;
        $rel_url = $folder;

// create the folder if it does not exist
//        if (!is_dir($folder_url)) {
//            pr($folder_url);die;
        $ftp->make_directory($folder);
//        }

        // if itemId is set create an item folder
        if ($itemId) {
            // set new absolute folder
            $folder_url = $folder . '/' . $itemId;
            // set new relative folder
            $rel_url = $folder . '/' . $itemId;
            // create directory
//            if (!is_dir($folder_url)) {
            $ftp->make_directory($folder_url);
//            }
        }

        // list of permitted file types, this is only images but documents can be added
        $permitted = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png');

        // replace spaces with underscores
        $filename = $file['name'];
        // assume filetype is false
        $typeOK = true;
        // check filetype is ok
//            foreach($permitted as $type) {
//                if($type == $file['type']) {
//                    $typeOK = true;
//                    break;
//                }
//            }

        // if file type ok upload the file
        if ($typeOK) {
            // switch based on error code
            switch ($file['error']) {
                case 0:
                    // check filename already exists
                    if (!file_exists($this->domain.'/'.$folder_url . '/' . $filename)) {
                        // create full filename
//                        $full_url = $folder_url . '/' . $filename;
                        $url = $rel_url . '/' . $filename;
                        // upload the file
                        $success = $ftp->upload($file['tmp_name'], $url,$this->domain);
                    } else {
                        // create unique filename and upload file
                        ini_set('date.timezone', 'Europe/London');
                        $now = date('Y-m-d-His');
//                        $full_url = $folder_url . '/' . $now . $filename;
                        $url = $rel_url . '/' . $now . $filename;
                        $success = $ftp->upload($file['tmp_name'], $url,$this->domain);
                    }
                    // if upload was successful
                    if ($success) {
                        // save the url of the file
                        $result['urls'][] = $url;
                    } else {
                        $result['errors'][] = "Error uploaded $filename. Please try again.";
                    }
                    break;
                case 3:
                    // an error occured
                    $result['errors'][] = "Error uploading $filename. Please try again.";
                    break;
                default:
                    // an error occured
                    $result['errors'][] = "System error uploading $filename. Contact webmaster.";
                    break;
            }
        } elseif ($file['error'] == 4) {
            // no file was selected for upload
            $result['nofiles'][] = "No file Selected";
        } else {
            // unacceptable file type
            $result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png.";
        }
        $ftp->close();
        return $result;
    }

    function char($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ|Ì|Í|Ị|Ỉ|Ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ|Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'y', $str);
        $str = preg_replace("/(đ|Đ)/", 'd', $str);
        $str = preg_replace("/(B)/", 'b', $str);
        $str = preg_replace("/( - )/", '-', $str);
        $str = preg_replace("/( )/", '-', $str);
        $str = preg_replace("/(  )/", '-', $str);
        $str = preg_replace("/(   )/", '-', $str);
        $str = preg_replace("/(    )/", '-', $str);
        $str = preg_replace("/(C)/", 'c', $str);
        $str = preg_replace("/(G)/", 'g', $str);
        $str = preg_replace("/(L)/", 'l', $str);
        $str = preg_replace("/(M)/", 'm', $str);
        $str = preg_replace("/(N)/", 'n', $str);
        $str = preg_replace("/(H)/", 'h', $str);
        $str = preg_replace("/(T)/", 't', $str);
        $str = preg_replace("/(K)/", 'k', $str);
        $str = preg_replace("/(S)/", 's', $str);
        $str = preg_replace("/(R)/", 'r', $str);
        $str = preg_replace("/(V)/", 'v', $str);
        $str = preg_replace("/(Y)/", 'y', $str);
        $str = preg_replace("/(W)/", 'w', $str);
        return trim($str);
    }

    public function findByModel($modelName = null, $input = array(), $type = "all")
    {
        $output = array();
        try {
            //goi model
            $this->loadModel($modelName);

            //lay ra ten khoa chinh
            $primaryKey = $this->{$modelName}->primaryKey;

            if (isset($input['order'])) {
                if (!is_array($input['order'])) {
                    $order = explode(' ', $input['order']);
                    if (count($order) == 2) {
                        $input['order'] = array($order[0] => $order[1]);
                    }
                }
            }
            if (isset($input['contain'])) {
                if ($input['contain'] == 1) {
                    $contain = false;
                } else {
                    $contain = $input['contain'];
                }
            } else {
                $contain = true;
            }
            $params = array(
                'conditions' => isset($input['conditions']) ? $input['conditions'] : array(),
                'fields' => isset($input['fields']) ? $input['fields'] : array(),
                'group' => isset($input['group']) ? $input['group'] : array(),
                'order' => isset($input['order']) ? $input['order'] : array($modelName . '.' . $primaryKey => 'DESC'),
                'contain' => $contain,
                'recursive' => isset($input['recursive']) ? $input['recursive'] : 1,
            );
            if ($type == 'all') {
                //neu phan trang
                if (isset($input['pageNumber'])) {
                    $recordNumber = $this->{$modelName}->find('count', array('conditions' => $params['conditions'], 'group' => $params['group']));
                    if ($recordNumber > 0) {
                        $params['limit'] = isset($input['pageLimit']) ? (int)$input['pageLimit'] : LIMIT_PAGE;
                        $params['page'] = isset($input['pageNumber']) ? (int)$input['pageNumber'] : DEFAULT_PAGE;

                        $output['recordNumber'] = $recordNumber;

                        if ($params['limit'] <= 0) {
                            $params['limit'] = LIMIT_PAGE;
                        }

                        if ($params['page'] <= 0) {
                            $params['page'] = DEFAULT_PAGE;
                        }

                        $output['countPage'] = ceil($output['recordNumber'] / $params['limit']);
                        if ($params['page'] < $output['countPage']) {
                            $output['nextFlag'] = 1;
                            $output['nextPage'] = $params['page'] + 1;
                        } else {
                            $output['nextFlag'] = 0;
                            $output['nextPage'] = $output['countPage'];
                            $params['page'] = $output['countPage'];
                        }

                        if ($params['page'] > 1) {
                            $output['prevFlag'] = 1;
                            $output['prevPage'] = $params['page'] - 1;
                        } else {
                            $output['prevFlag'] = 0;
                            $output['prevPage'] = 1;
                            $params['page'] = 1;
                        }
                        $params['offset'] = ($params['page'] - 1) * $params['limit'];
                    }
                } else { //neu khong phan trang
                    if (isset($input['pageLimit'])) {
                        $params['limit'] = (int)$input['pageLimit'];
                    }
                }
            }
            $data = $this->{$modelName}->find($type, $params);
            if (count($data) > 0) {
                $output['resultCode'] = 1;
                $output['resultMsg'] = "Success!";
                $output['data'] = $data;
            } else {
                $output['resultCode'] = 2;
                $output['resultMsg'] = "Not found!";
                $output['data'] = null;
            }
        } catch (Exception $e) {
            $output['resultCode'] = 0;
            $output['resultMsg'] = "Fail!";
            $output['detail'] = $e->getMessage();
        }
        return $output;
    }
    public  function download_as_zip($files = array(),$zipname = 'Resumes'){
        //$files = array('Dear GP.docx','ecommerce.doc');
        if(count($files)){

            # create new zip opbject
            $zip = new ZipArchive();

            # create a temp file & open it
            $tmp_file = 'all_product_'.date('d.m.Y').'.zip';
            $zip->open($tmp_file, ZipArchive::CREATE);

            # loop through each file
            foreach($files as $file){

                # download file
                $download_file = file_get_contents($file);

                #add it to the zip
                $zip->addFromString(basename($file),$download_file);

            }

            # close zip
            $zip->close();

            # send the file to the browser as a download
            header('Content-disposition: attachment; filename='.$zipname.'.zip');
            header('Content-type: application/zip');
            readfile( $tmp_file);
        }else{
            return false;
        }

    }



   public function send_email( $to, $subject, $body,$cc = "",$from = "pixelvn@gmail.com"){
        /* load CakePHP Email component */
        App::uses('CakeEmail', 'Network/Email');

        /* instantiate CakeEmail class */
		//echo $to;die;
        $Email = new CakeEmail();
        $Email->config('smtp');
        $Email->emailFormat('html');

        /* pass user input to function */
        //$Email->from($from);
        $Email->to($to);
	if($cc){
	$Email->Cc($cc);
	}
        $Email->subject($subject);
        $Email->send($body);

        $this->Session->setFlash('Email sent.');

        return true;
        /* render home.ctp instead of display.ctp */
        //$this->render('home');
    }


}
