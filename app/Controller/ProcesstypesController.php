<?php
class ProcesstypesController extends AppController{
    public $uses = array('Processtypegroup','Processtype');
    public function index($row = 20)
    {
        $keyword = $this->request->query('keyword');
        if($keyword){
            $this->paginate = array(
                'limit' => $row,
                'conditions' => array(
                    'name LIKE ' => '%' . $keyword . '%'),
                'order'=>array('Processtype.order'=>'ASC')
            );
        }else{
            $this->paginate = array(
                'limit' => $row,
                'order'=>array('Processtype.order'=>'ASC')
            );
        }
        $all = $this->paginate('Processtype');
        $this->set('processTypes', $all);
        $this->set('row', $row);
        $this->set('keyword', $keyword);
    }

    public function add(){
        $group_process = $this->Processtypegroup->find('list');
        $this->set('group_process', $group_process);
        if ($this->request->is('post')) {
            $this->Processtype->Create();
            if($this->request->data['Processtype']['number']==''){
                $this->request->data['Processtype']['number']=1;
            }
            if ($this->Processtype->save($this->request->data)) {
                $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Bản ghi mới đã được thêm.</div>'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm bản ghi mới.</div>'));

        }
    }

    public function edit($id = null) {
        $group_process = $this->Processtypegroup->find('list');
        $this->set('group_process', $group_process);
        if (!$id) {
            throw new NotFoundException(__('Invalid process type'));
        }

        $post = $this->Processtype->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid process type'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Processtype->id = $id;
            if($this->request->data['Processtype']['number']==''){
                $this->request->data['Processtype']['number']=1;
            }
            if ($this->Processtype->save($this->request->data)) {
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

        if ($this->Processtype->delete($id)) {
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
            $this->Processtype->deleteAll(array('Processtype.id' => $keys), false);
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
        $category = $this->Processtype->findById($id);
        $category['Processtype']['status'] = $status;
        if ($this->Processtype->save($category)) {
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
    public function getProcess($prcess_type_id = null,$number = 0,$selected = null)
    {
        $this->layout = false;
        $process_type = $this->Processtype->find('list', array('conditions' => array('Processtype.process_type_group_id' => $prcess_type_id), 'fields' => array('Processtype.id', 'Processtype.name')));
        $this->set('process_type', $process_type);
        $this->set('number', $number);
        $this->set('selected', $selected);
    }

}