<?php
/**
 * Created by PhpStorm.
 * User: MSc. Hoang Dung
 * Date: 10/22/14
 * Time: 4:42 PM
 */
class PointtimesController extends AppController{

    public function index($row = 5)
    {
        $keyword = $this->request->query('keyword');
        if($keyword){
            $this->paginate = array(
                'limit' => $row,
                'conditions' => array(
                    'name LIKE ' => '%' . $keyword . '%'),
                'order'=>array('Pointtime.order'=>'ASC')
            );
        }else{
            $this->paginate = array(
                'limit' => $row,
                'order'=>array('Pointtime.order'=>'ASC')
            );
        }
        $all = $this->paginate('Pointtime');
        $this->set('point_time', $all);
        $this->set('row', $row);
        $this->set('keyword', $keyword);
    }

    public function add(){
        if ($this->request->is('post')) {
            $this->Pointtime->Create();
            if ($this->Pointtime->save($this->request->data)) {
                $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Bản ghi mới đã được thêm.</div>'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm bản ghi mới.</div>'));

        }
    }

    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }

        $post = $this->Pointtime->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Pointtime->id = $id;
            if ($this->Pointtime->save($this->request->data)) {
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

        if ($this->Pointtime->delete($id)) {
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
            $this->Pointtime->deleteAll(array('Pointtime.id' => $keys), false);
            $this->Session->setFlash(
                __('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Xóa thành công!</strong> %s bản ghi đã được xóa.</div>', h(count($keys)))
            );
            echo ('OK');
        }
        exit;
    }

    public function status($id = null, $status = null)
    {
        $this->layout = 'ajax';
        $category = $this->Pointtime->findById($id);
        $category['Pointtime']['status'] = $status;
        if ($this->Pointtime->save($category)) {
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
}