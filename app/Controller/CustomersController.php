<?php
/**
 * Created by PhpStorm.
 * User: MSc. Hoang Dung
 * Date: 10/24/14
 * Time: 2:41 PM
 */
class CustomersController extends AppController{

    public $uses = array('Country','Customer');
    public function index($row = 5)
    {
        $country_id = $this->request->query('Country_id');
        $name = $this->request->query('name');
        $conditions = array();
        if ($name && $name != '') {
            $conditions['Customer.name'] = $name;
        }
        if ($country_id && $country_id != '') {
            $conditions['Customer.country_id'] = $country_id;
        }
        $this->paginate = array(
            'limit' => $row,
            'conditions' => $conditions,
            'order' => 'Customer.order ASC'
        );
        $all = $this->paginate('Customer');
        $this->set('customer', $all);
        $countries = $this->Country->find('list', array('fields' => array('Country.id', 'Country.name')));
        $this->set(compact(array('countries')));
        $this->set('country_id',$country_id);
        $this->set('row', $row);
        $this->set('name', $name);
    }

    public function add(){
        $category = $this->Country->find('list');
        $this->set('country', $category);
        if ($this->request->is('post')) {
            $this->Customer->Create();
            if ($this->Customer->save($this->request->data)) {
                $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Bản ghi mới đã được thêm.</div>'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm bản ghi mới.</div>'));

        }
    }

    public function edit($id = null) {
        $category = $this->Country->find('list');
        $this->set('country', $category);
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }

        $post = $this->Customer->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Customer->id = $id;
            if ($this->Customer->save($this->request->data)) {
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

        if ($this->Customer->delete($id)) {
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
            $this->Customer->deleteAll(array('Customer.id' => $keys), false);
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
        $category = $this->Customer->findById($id);
        $category['Customer']['status'] = $status;
        if ($this->Customer->save($category)) {
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

    public function getCustomers($country_id = null,$selected = null){
        $this->layout = false;
        $customers = $this->Customer->find('list',array('conditions'=>array('Customer.Country_id'=>$country_id),'fields'=>array('Customer.id','Customer.name')));
        $this->set('customers',$customers);
        $this->set('selected',$selected);
    }

    public function getCustomersSearch($country_id = null,$selected = null){
        $this->layout = false;
        $customers = $this->Customer->find('list',array('conditions'=>array('Customer.Country_id'=>$country_id),'fields'=>array('Customer.id','Customer.name')));
        $this->set('customers',$customers);
        $this->set('selected',$selected);
    }

    public function getEmail($customer_id = null){
        $this->layout = false;
        $ctg = $this->Customer->find('first',array('conditions' => array('Customer.id'=>$customer_id)));
        if(!empty($ctg)){
            $ctg = $ctg['Customer'];
            $ctg['returnCode'] = 1;
        }else{
            $ctg['returnCode'] = 0;
        }
        $this->RequestHandler->respondAs('json');
        $this->autoRender = false;
        return json_encode($ctg);
    }

}