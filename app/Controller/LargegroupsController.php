<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiemtd
 * Date: 5/24/15
 * Time: 4:15 PM
 * To change this template use File | Settings | File Templates.
 */
class LargegroupsController extends AppController{
    public $uses = array('LargeGroup');

    public function index($row = 5)
    {
        if ($this->request->is('requested')) {
            return $this->LargeGroup->find('all', array('conditions' => array('LargeGroup.status' => 1)));
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
        $all = $this->paginate('LargeGroup');
        $this->set('LargeGroup', $all);
        $this->set('row', $row);
        $this->set('keyword', $keyword);
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->LargeGroup->Create();
            if ($this->LargeGroup->save($this->request->data)) {
                $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Bản ghi mới đã được thêm.</div>'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm bản ghi mới.</div>'));

        }
    }

    public function edit($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }

        $post = $this->LargeGroup->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->LargeGroup->id = $id;
            if ($this->LargeGroup->save($this->request->data)) {
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

        if ($this->LargeGroup->delete($id)) {
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
            $this->LargeGroup->deleteAll(array('LargeGroup.id' => $keys), false);
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
        $category = $this->LargeGroup->findById($id);
        $category['LargeGroup']['status'] = $status;
        if ($this->LargeGroup->save($category)) {
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