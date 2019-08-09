<?php

//App::import('Controller','Timelogins');

class NotificationsController extends AppController
{

    public $uses = array('Notification', 'Alert', 'User', 'Email');

    public function index($row = 10, $id = null)
    {
        $alerts = $this->Alert->find('all', array('conditions' => array('Alert.status' => 1)));
        $this->set('alerts', $alerts);
        $alert = $this->Alert->find('list', array('conditions' => array('Alert.status' => 1)));
        $this->set('alert', $alert);
        if ($this->request->is('requested')) {
            return $this->Notification->find('all', array(
                'order' => array('Notification.id' => 'DESC')));
        }
        $keyword = $this->request->query('keyword');
        if ($keyword) {
            $this->paginate = array(
                'limit' => $row,
                'conditions' => array(
                    'touser_id' => $this->Auth->user()['id'],
                    'title LIKE ' => '%' . $keyword . '%'),
                'order' => array('Notification.id' => 'DESC')
            );
        } else {
            $this->paginate = array(
                'conditions' => array(
                    'touser_id' =>$this->Auth->user()['id']
                ),
                'limit' => $row,
                'order' => array('Notification.id' => 'DESC')
            );
        }
        $all = $this->paginate('Notification');
        $this->set('notifications', $all);
        $this->set('row', $row);
        $this->set('keyword', $keyword);
        $this->set('id', $this->Auth->user()['id']);
        $users = $this->User->find('all');
        $this->set('users', $users);
    }

    public function countNotRead()
    {
        $tong = $this->Notification->find('count',
            array('conditions' => array(
                'touser_id LIKE ' => '%' . $this->Auth->user()['id'] . '%')
            ));
        $daDoc = $this->Notification->find('count',
            array('conditions' => array(
                'read_id LIKE ' => '%' . $this->Auth->user()['id'] . '%')
            ));
    }

    public function add()
    {
        $alerts = $this->Alert->find('list', array('conditions' => array('Alert.status' => 1)));
        $this->set('alerts', $alerts);
        if ($this->request->is('post')) {
            $this->Notification->Create();
            $this->request->data['Notification']['touser_id'] = $this->request->data['NV_ID'];
            $this->request->data['Notification']['user_id'] = $this->Auth->user()['id'];
            $this->request->data['Notification']['createdate'] = date('Y-m-d H:i:s');
            if ($this->Notification->save($this->request->data)) {
                $this->Session->setFlash(__('<div id="Notification" class="Notification Notification-success"><button data-dismiss="Notification" class="close">×</button><strong>Thành công!</strong> Tin nhắn đã được gửi.</div>'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('<div id="Notification" class="Notification Notification-error"><button data-dismiss="Notification" class="close">×</button><strong>Thất bại!</strong> Không thể gửi tin nhắn.</div>'));

        }
    }


    public function view($id = null)
    {
        $users = $this->User->find('all');
        $this->set('users', $users);
        $alerts = $this->Alert->find('list', array('conditions' => array('Alert.status' => 1)));
        $this->set('alerts', $alerts);
        if (!$id) {
            throw new NotFoundException(__('Invalid notification'));
        }

        $notification = $this->Notification->findById($id);
        if($this->Auth->user()['id'] != $notification['Notification']['touser_id']) $this->redirect("/");
        // kiem tra la da luu read id chua, neu da luu roi thi thoi.
        $mystring = $notification['Notification']['read_id'];
        $pos = strpos($mystring, $this->Auth->user()['id']);
        if ($pos === false) {
            $notification['Notification']['read_id'] = $this->Auth->user()['id'] . ',' . $notification['Notification']['read_id'];
            $this->Notification->save($notification);
        }
        if (!$notification) {
            throw new NotFoundException(__('Invalid notification'));
        }
        $this->set('notification', $notification);
    }

    public function delete($id)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }

        $post = $this->Notification->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Notification->id = $id;
//            debug($post['Notification']['touser_id']);
            $newstr = str_replace($this->Auth->user()['id'], '', $post['Notification']['touser_id']);
//            debug($newstr);die;
            $this->request->data['Notification']['touser_id'] = $newstr;
            if ($this->Notification->save($this->request->data)) {
                $this->Session->setFlash(__('<div id="Notification" class="Notification Notification-success"><button data-dismiss="Notification" class="close">×</button><strong>Thành công!</strong> Đã xóa tin nhắn</div>'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('<div id="Notification" class="Notification Notification-error"><button data-dismiss="Notification" class="close">×</button><strong>Thất bại!</strong> Không thể xóa tin nhắn.</div>'));
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function multi_del()
    {
        if ($this->request->is('post')) {
            $keys = $this->request->data['items'];
            $flag = true;
            foreach ($keys as $item):
                $post = $this->Notification->findById($item);
                $newstr = str_replace($this->Auth->user()['id'], '', $post['Notification']['touser_id']);
                $post['Notification']['touser_id'] = $newstr;
                if (!$this->Notification->save($post)) {
                    $flag = false;
                }
            endforeach;
            if ($flag)
                $this->Session->setFlash(
                    __('<div id="Notification" class="Notification Notification-success"><strong>Xóa thành công!</strong> %s bản ghi đã được xóa.</div>', h(count($keys)))
                );
            echo('OK');
        }
        exit;
    }

    public function alert_type()
    {
        $alerts = $this->Alert->find('all', array('conditions' => array('Alert.status' => 1)));
        $this->set('alerts', $alerts);
//        echo 'XXXXX';die;
        $this->layout = 'ajax';
        if ($this->request->data['status'] > 0) {
            $notifications = $this->Notification->find('all', array(
                'conditions' => array(
                    'Notification.alert_id' => $this->request->data['status'],
                    'touser_id LIKE ' => '%' . $this->Auth->user()['id'] . '%'
                ),
                'order' => array('Notification.id' => 'DESC')
            ));
            $this->set('notifications', $notifications);
        } else {
            $notifications = $this->Notification->find('all', array(
                'conditions' => array(
                    'touser_id LIKE ' => '%' . $this->Auth->user()['id'] . '%'
                ),
                'order' => array('Notification.id' => 'DESC')
            ));
            $this->set('notifications', $notifications);
        }
    }
    //Mang can co dinh dang khi he thong gui
//array(
//    'title'=>'Kiemtra',
//    'touser_id'=>'16',
//    'content'=>'xxx'
//    )
    public function system_notification($pass = array())
    {
        if ($pass != null && $pass['title'] != null && $pass['touser_id'] != null && $pass['content']) {
            $this->Notification->Create();
            $this->Notification->save($pass);
        }
    }

    public function ngayCong()
    {
        $staffs = $this->User->find('all');
        $content = $this->Email->findById(4);
        $pass = array();
        foreach ($staffs as $item) {
            $pass['title'] = $content['Email']['title'];
            $nd = $content['Email']['content'];
            $pass['content'] = str_replace(array('#USER_NAME#', '#DATE#', '#CONG#'), array($item['User']['name'], date("d/m/Y"), '100'), $nd);
            $pass['touser_id'] = $item['User']['id'];
            $this->system_notification($pass);
        }
        $this->autoRender = false;
    }

    public function luongThuong()
    {
        $staffs = $this->User->find('all');
        $content = $this->Email->findById(5);
        $pass = array();
        foreach ($staffs as $item) {
            $pass['title'] = $content['Email']['title'];
            $nd = $content['Email']['content'];
            $pass['content'] = str_replace(array('#USER_NAME#', '#MONTH#', '#SALARY#'), array($item['User']['name'], date("m/Y"), '1.000.000 VND'), $nd);
            $pass['touser_id'] = $item['User']['id'];
            $this->system_notification($pass);
        }
        die;
    }

    public function rejectProject()
    {
        $staffs = $this->User->find('all');
        $content = $this->Email->findById(6);
        $pass = array();
        foreach ($staffs as $item) {
            $pass['title'] = $content['Email']['title'];
            $nd = $content['Email']['content'];
            $cumtomer = 'Viet Nam';
            $product = 'sản phẩm I';
            $pass['content'] = str_replace(array('#CUSTOMER#','#PRODUCTS#'), array($cumtomer,$product), $nd);
            $pass['touser_id'] = $item['User']['id'];
            $this->system_notification($pass);
        }
        die;
    }

    public function other()
    {
        $staffs = $this->User->find('all');
        $content = $this->Email->findById(4);
        $pass = array();
        foreach ($staffs as $item) {
            $pass['title'] = $content['Email']['title'];
            $nd = $content['Email']['content'];
            $pass['content'] = str_replace(array('#USER_NAME#', '#DATE#', '#CONG#'), array($item['User']['name'], date("d/m/Y"), '100'), $nd);
            $pass['touser_id'] = $item['User']['id'];
            $this->system_notification($pass);
        }
        die;
    }

}