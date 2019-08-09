<?php

App::uses('AclManagementAppController', 'AclManagement.Controller');

/**
 * Groups Controller
 *
 * @property Group $Group
 */
class GroupsController extends AclManagementAppController {

    function beforeFilter() {
        parent::beforeFilter();
        
//        $this->layout = "twitter_full";
        $this->layout = "default";
    }
    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->set('title', __('Groups'));
        $this->set('description', __('Manage Groups'));

        $this->Group->recursive = 0;        
        $this->set('groups', $this->paginate());
    }

    /**
     * view method
     *
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        $this->Group->id = $id;
        if (!$this->Group->exists()) {
            throw new NotFoundException(__('Invalid group'));
        }
        $this->set('group', $this->Group->read(null, $id));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->Group->create();
            if ($this->Group->save($this->request->data)) {
                $this->Session->setFlash($this->setMessage("success", "Đã thêm nhóm người dùng mới"));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash($this->setMessage("error", "Không thể thêm nhóm người dùng mới"));
            }
        }
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        $this->Group->id = $id;
        if (!$this->Group->exists()) {
            throw new NotFoundException(__('Invalid group'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Group->save($this->request->data)) {
                $this->Session->setFlash($this->setMessage("success", "Đã sửa nhóm người dùng"));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash($this->setMessage("error", "Không thể sửa nhóm người dùng"));

            }
        } else {
            $this->request->data = $this->Group->read(null, $id);
        }
    }

    /**
     * delete method
     *
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Group->id = $id;
        if (!$this->Group->exists()) {
            throw new NotFoundException(__('Invalid group'), 'error');
        }
        if ($this->Group->delete()) {
            $this->Session->setFlash($this->setMessage("success", "Đã xóa nhóm người dùng"));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash($this->setMessage("error", "Không thể xóa nhóm người dùng"));
        $this->redirect(array('action' => 'index'));
    }
    public function multi_del()
    {
        if ($this->request->is('post')) {
            $keys = $this->request->data['items'];
            $this->Group->deleteAll(array('Group.id' => $keys), false);
            $this->Session->setFlash(
                __('<div id="alert" class="alert alert-success"><strong> Xóa thành công!</strong> %s bản ghi đã được xóa.</div>', h(count($keys)))
            );
            echo('OK');
        }
        exit;
    }

}
?>