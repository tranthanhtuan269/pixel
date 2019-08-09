<?php
class ProductextensionsController extends AppController{
    public function index($row = 20)
    {
        $keyword = $this->request->query('keyword');
        if($keyword){
            $this->paginate = array(
                'limit' => $row,
                'conditions' => array(
                    'or'=>array(
                        'name LIKE ' => '%' . $keyword . '%',
                        'view_type LIKE ' => '%' . $keyword . '%'
                    )
                ));
        }else{
            $this->paginate = array(
                'limit' => $row,
                'order'=>array('Productextension.order'=>'ASC')
            );
        }
        $all = $this->paginate('Productextension');
        $this->set('Productextensions', $all);
        $this->set('row', $row);
        $this->set('keyword', $keyword);
    }

    public function add(){
        if ($this->request->is('post')) {
            $this->Productextension->Create();
            if ($this->Productextension->save($this->request->data)) {
                $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Bản ghi mới đã được thêm.</div>'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm bản ghi mới.</div>'));

        }
    }

    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid product extension'));
        }

        $post = $this->Productextension->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid product extension'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Productextension->id = $id;
            if ($this->Productextension->save($this->request->data)) {
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

        if ($this->Productextension->delete($id)) {
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
            $this->Productextension->deleteAll(array('Productextension.id' => $keys), false);
            $this->Session->setFlash(
                __('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Xóa thành công!</strong> %s bản ghi đã được xóa.</div>', h(count($keys)))
            );
            echo('OK');
        }
        exit;
    }

    public function status($id = null, $status = null)
    {
        $this->layout = 'ajax';
        $category = $this->Productextension->findById($id);
        $category['Productextension']['status'] = $status;
        if ($this->Productextension->save($category)) {
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