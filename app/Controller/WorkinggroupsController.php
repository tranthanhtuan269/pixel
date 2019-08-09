<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiemtd
 * Date: 5/24/15
 * Time: 4:16 PM
 * To change this template use File | Settings | File Templates.
 */

class WorkinggroupsController extends AppController{
    public $uses = array('WorkingGroup','LargeGroup','User');

    public function index($row = 5)
    {
        if ($this->request->is('requested')) {
            return $this->WorkingGroup->find('all', array('conditions' => array('WorkingGroup.status' => 1)));
        }
        $keyword = $this->request->query('keyword');
        if ($keyword) {
            $this->paginate = array(
                'limit' => $row,
                'conditions' => array(
                    'name LIKE ' => '%' . $keyword . '%')
            );
        } else {
            $this->paginate = array(
                'limit' => $row,
            );
        }
        $all = $this->paginate('WorkingGroup');
        $this->set('WorkingGroup', $all);
        $this->set('row', $row);
        $this->set('keyword', $keyword);
    }

    public function add()
    {
        $large_group = $this->LargeGroup->find('list');
        $this->set('large_group',$large_group);
        if ($this->request->is('post')) {
            $this->WorkingGroup->Create();
            $this->request->data['WorkingGroup']['user_ids'] = $this->request->data['NV_ID_1'] . ',';
            if ($this->WorkingGroup->save($this->request->data)) {
                $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Bản ghi mới đã được thêm.</div>'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm bản ghi mới.</div>'));

        }
    }

    public function edit($id = null)
    {
        $large_group = $this->LargeGroup->find('list');
        $this->set('large_group',$large_group);
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }

        $post = $this->WorkingGroup->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
        $usernames1 = $userid1 = '';
        $receivers1 = explode(",", $post['WorkingGroup']['user_ids']);
        foreach ($receivers1 as $p => $s) {
            $pushuser1['conditions']['User.id'][] = $s;
        }
        $users1 = $this->User->find('all', $pushuser1);
        if (!empty($users1)) {
            foreach ($users1 as $user) {
                $usernames1 .= $user['User']['name'] . ', ';
            }
        } else {
            $usernames1 = '';
        }
        $this->set('usernames1', $usernames1);
        if ($this->request->is(array('post', 'put'))) {
            $this->WorkingGroup->id = $id;
            $this->request->data['WorkingGroup']['user_ids'] = $this->request->data['NV_ID_1'] . ',';
            if ($this->WorkingGroup->save($this->request->data)) {
                $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Bản ghi đã được lưu.</div>'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể lưu bản ghi.</div>'));
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function delete($id)
    {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

        if ($this->WorkingGroup->delete($id)) {
            $this->Session->setFlash(
                __('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Bản ghi đã bị xóa.</div>', h($id))
            );
            return $this->redirect(array('action' => 'index'));
        }
    }


    public function multi_del()
    {
        if ($this->request->is('post')) {
            $keys = $this->request->data['items'];
            $this->WorkingGroup->deleteAll(array('WorkingGroup.id' => $keys), false);
            $this->Session->setFlash(
                __('<div id="alert" class="alert alert-success"><strong>Xóa thành công!</strong> %s bản ghi đã được xóa.</div>', h(count($keys)))
            );
            echo('OK');
        }
        exit;
    }

    public function status($id = null, $status = null)
    {
        $this->layout = 'ajax';
        $category = $this->WorkingGroup->findById($id);
        $category['WorkingGroup']['status'] = $status;
        if ($this->WorkingGroup->save($category)) {
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
    public function getWorking($id = null){
        $this->layout = false;
        $working = $this->WorkingGroup->find('list',array('conditions'=>array('WorkingGroup.large_group_id'=>$id),'fields'=>array('WorkingGroup.id','WorkingGroup.name')));
        $this->set('working',$working);
    }
}