<?php

class EmailsController extends AppController
{
    public function index($row = 20)
    {
        $keyword = $this->request->query('keyword');
        if ($keyword) {
            $this->paginate = array(
                'limit' => $row,
                'conditions' => array(
                    'name LIKE ' => '%' . $keyword . '%'),
                'order' => array('Email.order' => 'ASC')
            );
        } else {
            $this->paginate = array(
                'limit' => $row,
                'order' => array('Email.order' => 'ASC')
            );
        }
        $all = $this->paginate('Email');
        $this->set('emails', $all);
        $this->set('row', $row);
        $this->set('keyword', $keyword);
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->Email->Create();
            if ($this->Email->save($this->request->data)) {
                $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Bản ghi mới đã được thêm.</div>'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm bản ghi mới.</div>'));

        }
    }

    public function edit($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid email template'));
        }

        $post = $this->Email->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid email template'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Email->id = $id;
            if ($this->Email->save($this->request->data)) {
                $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Bản ghi đã được lưu.</div>'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể lưu bản ghi.</div>'));
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function status($id = null, $status = null)
    {
        $this->layout = 'ajax';
        $category = $this->Email->findById($id);
        $category['Email']['status'] = $status;
        if ($this->Email->save($category)) {
            $this->set('status', $status);
            $this->Session->setFlash(
                __('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Bạn vừa thay đổi trạng thái của bản ghi.</div>')
            );
        } else {
            $this->set('status', 'false');
            $this->Session->setFlash(
                __('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Bạn khổng thể thay đổi trạng thái của bản ghi.</div>')
            );
        }
    }
// Gui Email khach hang khi khoi tao du an
//Cac thong tin can co la gi
    public function guiMail()
    {
        $getContent = $this->Email->findById(3);
        $getContent['Email']['content'];
        $name = 'Name Company';
        $newContent = str_replace('#USER_NAME#', $name, $getContent['Email']['content']);
        $this->send_email('thanhle111@gmail.com', 'thanhle1286@gmail.com', 'Test Mail', $newContent);
        die;
    }

//    Gui mail khi hoan thanh du an
//Cac thong tin can co la gi

    public function guiMailComplete()
    {
        $getContent = $this->Email->findById(3);
        $getContent['Email']['content'];
        $name = 'Name Company';
        $newContent = str_replace('#USER_NAME#', $name, $getContent['Email']['content']);
        $this->send_email('thanhle111@gmail.com', 'thanhle1286@gmail.com', 'Test Mail', $newContent);
        die;
    }



}