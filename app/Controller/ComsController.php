<?php

class ComsController extends AppController{
    public $uses = array('GroupCom','Com');

    public function index($row = 5)
    {
        if ($this->request->is('requested')) {
            return $this->Com->find('all', array('conditions' => array('Com.status' => 1), 'order' => array('Com.order' => 'ASC')));
        }
        $keyword = $this->request->query('keyword');
        if($keyword){
            $this->paginate = array(
                'limit' => $row,
                'conditions' => array(
                    'name LIKE ' => '%' . $keyword . '%'),
                'order'=>array('Com.order'=>'ASC')
            );
        }else{
            $this->paginate = array(
                'limit' => $row,
                'order'=>array('Com.order'=>'ASC')
            );
        }
        $all = $this->paginate('Com');
        $this->set('com', $all);
        $this->set('row', $row);
        $this->set('keyword', $keyword);
    }

    public function add(){
        $groupCom = $this->GroupCom->find('list');
        $this->set('groupCom',$groupCom);
        if ($this->request->is('post')) {
            $this->Com->Create();
            if ($this->Com->save($this->request->data)) {
                $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Bản ghi mới đã được thêm.</div>'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm bản ghi mới.</div>'));

        }
    }

    public function edit($id = null) {
        $groupCom = $this->GroupCom->find('list');
        $this->set('groupCom',$groupCom);
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }

        $post = $this->Com->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Com->id = $id;
            if ($this->Com->save($this->request->data)) {
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

        if ($this->Com->delete($id)) {
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
            $this->Com->deleteAll(array('Com.id' => $keys), false);
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
        $category = $this->Com->findById($id);
        $category['Com']['status'] = $status;
        if ($this->Com->save($category)) {
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
    public function getCom($com_id = null){
        $this->layout = false;
        $com = $this->Com->find('list',array('conditions'=>array('Com.group_com_id'=>$com_id),'fields'=>array('Com.id','Com.name')));
        $this->set('com',$com);
    }

}