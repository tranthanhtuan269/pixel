<?php

App::uses('AclManagementAppController', 'AclManagement.Controller');
App::uses('Manager', 'Lib');

/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AclManagementAppController
{

    public $uses = array('Customer','CustomerGroup','Country','AclManagement.User', 'Timelogin', 'Department', 'Group', 'Vacation','User');

    function beforeFilter()
    {
        parent::beforeFilter();

//        $this->layout = "twitter_full";
        $this->layout = "default";

        $this->Auth->allow('login', 'logout');
        $this->User->bindModel(array('belongsTo' => array(
            'Group' => array(
                'className' => 'AclManagement.Group',
                'foreignKey' => 'group_id',
                'dependent' => true
            )
        )), false);
    }

    /**
     * Temp acl init db
     */
//    function initDB() {
//        $this->autoRender = false;
//
//        $group = $this->User->Group;
//        //Allow admins to everything
//        $group->id = 1;
//        $this->Acl->allow($group, 'controllers');
//
//        //allow managers to posts and widgets
//        $group->id = 2;
//        $this->Acl->deny($group, 'controllers');
//        //$this->Acl->allow($group, 'controllers/Posts'); //allow all action of controller posts
//        $this->Acl->allow($group, 'controllers/Posts/add');
//        $this->Acl->deny($group, 'controllers/Posts/edit');
//
//        //we add an exit to avoid an ugly "missing views" error message
//        echo "all done";
//        exit;
//    }
    /**
     * login method
     *
     * @return void
     */
    function login()
    {
//        echo getenv('COMPUTERNAME');die;
        $computerName = getenv('COMPUTERNAME');
        if (!$computerName) {
            $computerName = 'Server_laurus';
        }
        if ($this->Cookie->read('username')) {
            $this->set('username', $this->Cookie->read('username'));
        }
        if ($this->Cookie->read('password')) {
            $this->set('password', $this->Cookie->read('password'));
        }
        $this->layout = false;
        if ($this->request->is('post')) {
//Kiem tra thong tin dang nhap o may khac
//Neu dang dang nhap o may khac thi hien ra thong bao
//            debug($this->request->data);die;
            $password = Security::hash($this->request->data['User']['password'], null, true);
	    $username = urlencode($this->request->data['User']['username']);
            $user_login = $this->User->query("SELECT * FROM users WHERE username = '" . $username . "' AND password = '" . $password . "';");
            if (count($user_login) > 0) {
                $login_old = $this->Timelogin->find('first', array(
                    'conditions' => array(
                        'user_id ' => $user_login[0]['users']['id'],
                        'time_login != ' => '',
                        'time_logout ' => null,
                        'name_computer != ' => $computerName
                    )
                ));
                $check_login = count($login_old);
                $status = $this->User->findByid($user_login[0]['users']['id']);
//                debug($status);die;
                if ($status['User']['status'] == true) {
                   // if ($check_login == 0) {
                        if ($this->Auth->login()) {
                            //Kiểm tra xem nhân viên có trong thời gian nghỉ phép không.
                            $vacation_user_id = $this->Auth->user('id');
                            $today_vacation = date('Y-m-d');
                            $conditions = array();
                            $conditions['Vacation.from_date <= '] = $today_vacation;
                            $conditions['Vacation.to_date > '] = $today_vacation;
                            $conditions['Vacation.permit'] = 1;
                            $conditions['Vacation.user_id'] = $vacation_user_id;
                            $vacation = $this->Vacation->find('all', array(
                                'conditions' => $conditions,
                            ));
                            if (count($vacation) < 1) {
                                $form_login = $this->request->data;
                                if ($form_login['User']['remember'] == 1) {
                                    $this->Cookie->write('username', $form_login['User']['username']);
                                    $this->Cookie->write('password', $form_login['User']['password']);
                                } else {
                                    $this->Cookie->destroy();
                                }
                                $employee_login = $this->Auth->user();
                                $today = date("Y-m-d H:i:s");
                                $check_logout = $this->Timelogin->find('first', array(
                                    'conditions' => array(
                                        'user_id ' => $user_login[0]['users']['id'],
                                        'time_login != ' => '',
                                        'time_logout ' => null,
                                        'name_computer = ' => $computerName
                                    ),
                                    'order' => array('Timelogin.id' => 'DESC')
                                ));
                                // kiem tra xem lan dang nhap gan nhat da log out hay chua.
                                if (count($check_logout) > 0) {
                                    $time_work = (strtotime($today) - strtotime($check_logout['Timelogin']['time_login']));
                                    if ($time_work <= ($this->CF['TIME_WORK'] * 60)) {
                                        $check_logout['Timelogin']['time_logout'] = $today;
                                        $check_logout['Timelogin']['time_work'] = $time_work;
                                    } else {
                                        $duration = '+ ' . ($this->CF['TIME_WORK']) . 'minutes';
                                        $cur_time = date('Y-m-d H:i:s', strtotime($duration, strtotime($check_logout['Timelogin']['time_login'])));
                                        $check_logout['Timelogin']['time_logout'] = $cur_time;
                                        $check_logout['Timelogin']['time_work'] = ($this->CF['TIME_WORK'] * 60);
                                    }
                                    $check_logout['Timelogin']['status'] = 1;
                                    $this->Timelogin->save($check_logout);
                                }
                                $this->Timelogin->Create();
                                $time_login = array();
                                $time_login['Timelogin']['user_id'] = $employee_login['id'];
                                $time_login['Timelogin']['time_login'] = $today;
                                $time_login['Timelogin']['name_computer'] = $computerName;
                                if ($this->Timelogin->save($time_login)) {
                                    $this->redirect($this->Auth->redirect());
                                    $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Xin chào!</strong> Bạn đã đăng nhập thành công.</div>'));
                                } else {
                                    $this->Session->setFlash(__('<div id="alert" class="alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại !</strong> username hoặc mật khẩu chưa đúng.</div>'));
                                }
                            } else {
                                $this->Session->setFlash(__('<div id="alert" class="alert-error"><button data-dismiss="alert" class="close">×</button><strong>Rất tiếc!</strong>Bạn đang trong thời gian nghỉ phép.</div>'));
                            }
                        }
                   // } else {
                   //     $this->Session->setFlash(__('<div id="alert" class="alert-error"><button data-dismiss="alert" class="close">×</button><strong>Cảnh báo!</strong> Tài khoản của bạn đang đăng nhập ở máy khác<br/> Hãy thoát ra trước khi đăng nhập tại máy này</div>'));
                   // }
                } else {
                    $this->Session->setFlash(__('<div id="alert" class="alert-error"><button data-dismiss="alert" class="close">×</button><strong>Cảnh báo!</strong> Tài khoản của bạn đang ở trạng thái unpublic<br/></div>'));
                }

            } else {
                $this->Session->setFlash(__('<div id="alert" class="alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại !</strong> username hoặc mật khẩu chưa đúng.</div>'));
            }
        }
    }

    /**
     * logout method
     *
     * @return void
     */
    function logout()
    {
        $today = date("Y-m-d H:i:s");
        $staff_login = $this->Auth->user();
        $time_out = $this->Timelogin->find('first', array(
            'conditions' => array('Timelogin.user_id' => $staff_login['id']),
            'order' => array('Timelogin.id' => 'DESC')
        ));
        $time_to_check = array_key_exists('TIME_WORK', $this->CF) ? $this->CF['TIME_WORK'] : 480; //Minute
        if (round((strtotime($today) - strtotime($time_out['Timelogin']['time_login'])) / 60) > $time_to_check) {
            $time_out['Timelogin']['time_logout'] = date('Y-m-d H:i:s', strtotime($time_out['Timelogin']['time_login']) + $time_to_check * 60);
        } else {
            $time_out['Timelogin']['time_logout'] = $today;
        }
        $time_out['Timelogin']['time_work'] = (strtotime($time_out['Timelogin']['time_logout']) - strtotime($time_out['Timelogin']['time_login']));
        if ($this->Timelogin->save($time_out)) {
            $this->redirect($this->Auth->logout());
        }
    }

    /**
     * index method
     *
     * @return void
     */
    public
    function index($row = 10)
    {
        $this->set('title', __('Users'));
        $this->set('description', __('Manage Users'));

        $this->User->recursive = 1;
        $department = $this->Department->find('list');
        $this->set('department', $department);
        $group = $this->Group->find('list');
        $this->set('group', $group);
        $group_id = $this->request->query('group_id');
        $department_id = $this->request->query('department_id');
        $name = $this->request->query('name');
        $email = $this->request->query('email');
        $conditions = array();
        if ($email && $email != '') {
            $conditions['User.email LIKE'] = '%' . $email . '%';
        }
        if ($name && $name != '') {
            $conditions['User.name LIKE'] = '%' . $name . '%';
        }
        if ($group_id && $group_id != '') {
            $conditions['group_id'] = $group_id;
        }
        if ($department_id && $department_id != '') {
            $conditions['department_id'] = $department_id;
        }
        $this->paginate = array(
            'limit' => $row,
            'conditions' => $conditions
        );
        $all = $this->paginate('User');
        $this->set('users', $all);
        $this->set('row', $row);
        $this->set('name', $name);
        $this->set('email', $email);
        $this->set('department_id', $department_id);
        $this->set('group_id', $group_id);
    }

    /**
     * view method
     *
     * @param string $id
     * @return void
     */
    public
    function view($id = null)
    {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'), 'error');
        }
        $this->set('user', $this->User->read(null, $id));
    }

    /**
     * add method
     *
     * @return void
     */
    public
    function add()
    {
        $department = $this->Department->find('list');
        $this->set('department', $department);
        $countries = $this->Country->find('all', array('fields' => array('Country.id', 'Country.name')));

        foreach($countries as $kto=>$ct){
            $customers = $this->Customer->find('all', array('fields'=>array('Customer.id','Customer.name'),'conditions' => array('Customer.country_id'=>$ct['Country']['id'])));//Country['id'];

            foreach ($customers as $k => $cu){
                $groupscus = $this->CustomerGroup->find('all',array('fields'=>array('CustomerGroup.id','CustomerGroup.name'),'conditions' => array('CustomerGroup.customer_id'=>$cu['Customer']['id'])));//Country['id'];
                $customers[$k]['children'] = $groupscus;
            }

            $countries[$kto]['children']= $customers;
        }
        $this->set('countries',$countries);
        if ($this->request->is('post')) {
//            debug($this->request->data);die;
            $this->loadModel('AclManagement.User');
            $this->User->create();
            if ($this->request->data['User']['avatar'] != "") {
                $fileupload = $this->_uploadFiles('medias/avatar_employee', $this->request->data['User']['avatar'], null);
            }
            $this->request->data['User']['avatar'] = $this->request->data['User']['avatar']['name'];
            $array = $this->request->data;
            if ($array['User']['date_of_birth'] && $array['User']['date_of_birth'] != '') {
                $array['User']['date_of_birth'] = date("Y-m-d", strtotime(str_replace('/', '-', $array['User']['date_of_birth'])));
            }
            if ($array['User']['start_work_day'] != '') {
                $array['User']['start_work_day'] = date("Y-m-d", strtotime(str_replace('/', '-', $array['User']['start_work_day'])));
            }
            if ($array['User']['day_work_official'] != '') {
                $array['User']['day_work_official'] = date("Y-m-d", strtotime(str_replace('/', '-', $array['User']['day_work_official'])));
            }
//            debug($array);die;
            if ($this->User->save($array)) {
                $this->Session->setFlash($this->setMessage("success", "Đã thêm thông tin người dùng"));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash($this->setMessage("error", "Không thể thêm thông tin người dùng"));
                $this->redirect(array('action' => 'index'));
            }
        }

        $groups = $this->User->Group->find('list');
        $this->set(compact('groups'));
    }
    
    public function ftp($id = null)
    {
        $curr_user = $this->Auth->user();
        // echo $id; 
        // debug($curr_user);
        // die;
        
        if($id==$this->Auth->user('id') || $curr_user['Group']['id']==1 || $curr_user['Group']['id']==2 || $curr_user['Group']['id']==7){
            
        }else{
            $this->redirect('/');
            die('Bạn không có quyền sửa tài khoản người khác!');
        }
        
        $user = $this->User->findByid($id);
        $username = $user['User']['username'];
        Manager::createUser($username);
        
        $this->Session->setFlash($this->setMessage("success", "Thông tin người dùng được lưu"));
        $this->redirect(array('action' => 'index'));
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($id = null)
    {
        $curr_user = $this->Auth->user();
        // echo $id; 
        // debug($curr_user);
        // die;
        
        if($id==$this->Auth->user('id') || $curr_user['Group']['id']==1 || $curr_user['Group']['id']==2 || $curr_user['Group']['id']==7){
            
        }else{
            $this->redirect('/');
            die('Bạn không có quyền sửa tài khoản người khác!');
        }
        $countries = $this->Country->find('all', array('fields' => array('Country.id', 'Country.name')));

        foreach($countries as $kto=>$ct){
            $customers = $this->Customer->find('all', array('fields'=>array('Customer.id','Customer.name'),'conditions' => array('Customer.country_id'=>$ct['Country']['id'])));//Country['id'];

            foreach ($customers as $k => $cu){
                $groupscus = $this->CustomerGroup->find('all',array('fields'=>array('CustomerGroup.id','CustomerGroup.name'),'conditions' => array('CustomerGroup.customer_id'=>$cu['Customer']['id'])));//Country['id'];
                $customers[$k]['children'] = $groupscus;
            }

            $countries[$kto]['children']= $customers;
        }
        $this->set('countries',$countries);
        $department = $this->Department->find('list');
        $this->set('department', $department);
        $group = $this->User->findByid($id);
        $this->set('employee', $group);
        $this->set('domain', $this->domain);
        $this->User->id = $id;
        if (!$id) {
            throw new NotFoundException(__('Invalid user'));
        }
        $post = $this->User->findById($id);
		if(isset($_REQUEST['debug'])){
			print_r($_POST);
			echo $id;
		print_r($post);die;
		}
        if ($post['User']['date_of_birth'] != '') {
            $post['User']['date_of_birth'] = date_format(date_create($post['User']['date_of_birth']), "d/m/Y");
        }
        if ($post['User']['start_work_day'] != '') {
            $post['User']['start_work_day'] = date_format(date_create($post['User']['start_work_day']), "d/m/Y");
        }
        if ($post['User']['day_work_official'] != '') {
            $post['User']['day_work_official'] = date_format(date_create($post['User']['day_work_official']), "d/m/Y");
        }
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $this->User->id = $id;
            if ($this->request->data['User']['avatar']['name'] != '') {
                $fileupload = $this->_uploadFiles('medias/avatar_employee', $this->request->data['User']['avatar'], null);
                $this->request->data['User']['avatar'] = $this->request->data['User']['avatar']['name'];
            }
            if ($this->request->data['User']['avatar']['name'] == '') {
                $this->request->data['User']['avatar'] = $post['User']['avatar'];
            }
            
            $array = $this->request->data;
            if ($array['User']['date_of_birth'] != '') {
                $array['User']['date_of_birth'] = date("Y-m-d", strtotime(str_replace('/', '-', $array['User']['date_of_birth'])));
            }

            if ($array['User']['start_work_day'] != '') {
                $array['User']['start_work_day'] = date("Y-m-d", strtotime(str_replace('/', '-', $array['User']['start_work_day'])));
            }
            if ($array['User']['day_work_official'] != '') {
                $array['User']['day_work_official'] = date("Y-m-d", strtotime(str_replace('/', '-', $array['User']['day_work_official'])));
            }
            
            
            if ($this->request->data['User']['password'] != '' && $this->request->data['User']['password2'] != '') {
                $array['User']['password'] = $this->request->data['User']['password'];
                $array['User']['password2'] = $this->request->data['User']['password2'];
            }
            else {
                unset($array['User']['password']);
                unset($array['User']['password2']);
            }
            
            // pr($array);die;
            if ($this->User->save($array)) {
                $this->Session->setFlash($this->setMessage("success", "Thông tin người dùng được lưu"));
                $this->redirect(array('action' => 'index'));
            } else {
                print_r($this->User->validationErrors);
                die();
                $this->Session->setFlash($this->setMessage("error", "Không thể lưu thông tin người dùng"));
                $this->redirect(array('action' => 'index'));
            }
        }
        $groups = $this->User->Group->find('list');
        $this->set(compact('groups'));
        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    /**
     * delete method
     *
     * @param string $id
     * @return void
     */
    public
    function delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('User deleted'), 'success');
            $this->Session->setFlash($this->setMessage("success", "Người dùng đã bị xóa"));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash($this->setMessage("error", "Không thể xóa người dùng"));
        $this->redirect(array('action' => 'index'));
    }

    /**
     *  Active/Inactive User
     *
     * @param <int> $user_id
     */
    public
    function toggle($user_id, $status)
    {
        $this->layout = "ajax";
        $status = ($status) ? 0 : 1;
        $this->set(compact('user_id', 'status'));
        if ($user_id) {
            $data['User'] = array('id' => $user_id, 'status' => $status);
            $allowed = $this->User->saveAll($data["User"], array('validate' => false));
            $this->Session->setFlash($this->setMessage("success", "Trạng thái bản ghi đã được thay đổi"));


        }
    }

    public
    function multi_del()
    {
        if ($this->request->is('post')) {
            $keys = $this->request->data['items'];
            $this->User->deleteAll(array('User.id' => $keys), false);
            $this->Session->setFlash(
                __('<div id="alert" class="alert alert-success"><strong> Xóa thành công!</strong> %s bản ghi đã được xóa.</div>', h(count($keys)))
            );
            echo('OK');
        }
        exit;
    }

    public function profile($user_id = null)
    {
        if ($user_id == null)
            $user_id=$this->Auth->user('id');
        $curr_user = $this->Auth->user();
        if($user_id==$this->Auth->user('id') || $curr_user['Group']['id']==1 || $curr_user['Group']['id']==2 || $curr_user['Group']['id']==7){
            
        }else{
            $this->redirect('/');
            die('Bạn không có quyền sửa tài khoản người khác!');
        }
        $user_info = $this->User->findById($user_id);
        $this->set('user_info', $user_info);
        $this->set('domain', $this->domain);
        if ($this->request->is(array('post', 'put'))) {
            $user = $this->User->findById($this->request->data['User']['id']);
            $this->User->id = $this->request->data['User']['id'];
            $post = array();
            if ($this->request->data['User']['avatar']['name'] != '') {
                $fileupload = $this->_uploadFiles('medias/avatar_employee', $this->request->data['User']['avatar'], null);
                $post['User']['avatar'] = $this->request->data['User']['avatar']['name'];
            }
            if ($this->request->data['User']['avatar']['name'] == '') {
                $post['User']['avatar'] = $user['User']['avatar'];
            }
            if ($this->request->data['User']['password'] != '' && $this->request->data['User']['password2'] != '') {
                $post['User']['password'] = $this->request->data['User']['password'];
                $post['User']['password2'] = $this->request->data['User']['password2'];
            }
            $post['User']['id'] = $this->request->data['User']['id'];
            $post['User']['name'] = $this->request->data['User']['name'];
            $post['User']['phone'] = $this->request->data['User']['phone'];
            $post['User']['id_number'] = $this->request->data['User']['id_number'];
            $post['User']['address'] = $this->request->data['User']['address'];
            $post['User']['email'] = $user['User']['email'];
            if ($this->User->save($post)) {
                $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong>Thông tin người dùng được thay đổi!</div>'));
                $this->redirect(array('action' => 'index'));

            } else {
                $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong>Không thể thay đổi thông tin người dùng!</div>'));
                $this->redirect(array('action' => 'index'));
            }
        }
    }
}

?>
