<?php
/**
 * Created by PhpStorm.
 * User: MSc. Hoang Dung
 * Date: 10/22/14
 * Time: 4:09 PM
 */
class DepartmentsController extends AppController{
public  $uses = array('Department','User');
    public function index($row = 10)
    {
        $keyword = $this->request->query('keyword');
        if($keyword){
            $this->paginate = array(
                'limit' => $row,
                'conditions' => array(
                    'name LIKE ' => '%' . $keyword . '%'),
                'order'=>array('Department.order'=>'ASC')
            );
        }else{
            $this->paginate = array(
                'limit' => $row,
                'order'=>array('Department.order'=>'ASC')
            );
        }
        $all = $this->paginate('Department');
        $this->set('department', $all);
        $this->set('row', $row);
        $this->set('keyword', $keyword);
    }

    public function add(){
        if ($this->request->is('post')) {
            $this->Department->Create();
            if ($this->Department->save($this->request->data)) {
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

        $post = $this->Department->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Department->id = $id;
            if ($this->Department->save($this->request->data)) {
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

        if ($this->Department->delete($id)) {
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
            $this->Department->deleteAll(array('Department.id' => $keys), false);
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
        $category = $this->Department->findById($id);
        $category['Department']['status'] = $status;
        if ($this->Department->save($category)) {
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
    public function getUsers($country_id = null,$selected = null){
        $this->layout = false;
        $users = $this->User->find('list',array('conditions'=>array('User.department_id'=>$country_id),'fields'=>array('User.id','User.name')));
        $this->set('users',$users);
        $this->set('selected',$selected);
    }
}