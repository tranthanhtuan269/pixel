<?php

class CustomergroupsController extends AppController
{
    public $uses = array('Customergroup', 'Country', 'Customer', 'Productextension', 'Processtype');

    public function index($row = 20)
    {
        $country_id = $this->request->query('Country_id');
        $customer_id = $this->request->query('Customer_id');
        $customer_group_id = $this->request->query('CustomerGroup_id');
        $conditions = array();
        if ($country_id && $country_id != '') {
            $conditions['Customergroup.country_id'] = $country_id;
        }
        if ($customer_id && $customer_id != '') {
            $conditions['Customergroup.customer_id'] = $customer_id;
        }
        if ($customer_group_id && $customer_group_id != '') {
            $conditions['Customergroup.id'] = $customer_group_id;
        }
        $this->paginate = array(
            'limit' => $row,
            'conditions' => $conditions,
            'order' => 'Customergroup.name ASC'
        );
        $this->Customergroup->recursive = 1;
        $this->Customergroup->bindModel(
            array('belongsTo' => array(
                'Customer' => array(
                    'className' => 'Customer',
                    'foreignKey' => 'customer_id'
                ),
                'Country' => array(
                    'className' => 'Country',
                    'foreignKey' => 'country_id'
                ),

            )
            )
        );
        $data = $this->Paginator->paginate('Customergroup');
        $this->set('country_id',$country_id);
        $countries = $this->Country->find('list', array('fields' => array('Country.id', 'Country.name')));
        $this->set(compact(array('countries')));
        $this->set('customerGroups', $data);
        $this->set('row', $row);
//        debug($data);
    }

    public function add()
    {
//        lấy cho list box
        $countries = $this->Country->find('list', array(
            'conditions' => array('Country.status' => '1')));
        $this->set('countries', $countries);
        $customers = $this->Customer->find('list', array(
            'conditions' => array('Customer.status' => '1')));
        $this->set('customers', $customers);
        $productExtensions = $this->Productextension->find('list', array(
            'conditions' => array('Productextension.status' => '1')));
        $this->set('productExtensions', $productExtensions);
        $processTypes = $this->Processtype->find('list', array(
            'conditions' => array('Processtype.status' => '1')));
        $this->set('processTypes', $processTypes);
//        lay com


        if ($this->request->is('post')) {
            $this->Customergroup->Create();

            if (isset($this->request->data['Customergroup']['upload_file'],
                $this->request->data['Customergroup']['upload_file']['tmp_name'])
            && $this->request->data['Customergroup']['upload_file']['tmp_name']
            ) {
                move_uploaded_file($this->request->data['Customergroup']['upload_file']['tmp_name'], WWW_ROOT . 'medias/customer_group' . DS . $this->request->data['Customergroup']['upload_file']['name']);
            }
            $this->request->data['Customergroup']['upload_file'] = $this->request->data['Customergroup']['upload_file']['name'];


            if (isset($this->request->data['Customergroup']['help_file'],
                    $this->request->data['Customergroup']['help_file']['tmp_name'])
                && $this->request->data['Customergroup']['help_file']['tmp_name']
            ) {
                $fileupload = $this->_uploadFiles('medias/Customergroup_files/', $this->request->data['Customergroup']['help_file'], null);
            }
            $this->request->data['Customergroup']['help_file'] = str_replace(' ', '_', $this->request->data['Customergroup']['help_file']['name']);



//            lay com va luu vao trong db
            $coms = $this->request->data['Com'];
            $str = "";
            foreach ($coms['name'] as $com) {
                $str = $com . ',' . $str;
            }
            $this->Customergroup->set(array('com_ids' => $str));
//            debug($this->request->data);die;
            if ($this->Customergroup->save($this->request->data)) {
                $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Bản ghi mới đã được thêm.</div>'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm bản ghi mới.</div>'));

        }
    }

    public function getCustomerGroups($customer_id = null, $selected = null)
    {
        $this->layout = false;
        $Customergroups = $this->Customergroup->find('list', array('conditions' => array('Customergroup.customer_id' => $customer_id), 'fields' => array('Customergroup.id', 'Customergroup.name')));
        $this->set('Customergroups', $Customergroups);
        $this->set('selected', $selected);
    }
    public function getCustomerGroupsChoose($customer_id = null, $selected = null)
    {
        $this->layout = false;
        $Customergroups = $this->Customergroup->find('list', array('conditions' => array('Customergroup.customer_id' => $customer_id), 'fields' => array('Customergroup.id', 'Customergroup.name')));
        $this->set('Customergroups', $Customergroups);
        $this->set('selected', $selected);
    }

    public function edit($id = null)
    {

        //        lấy cho list box
        $countries = $this->Country->find('list', array(
            'conditions' => array('Country.status' => '1')));
        $this->set('countries', $countries);
        $customers = $this->Customer->find('list', array(
            'conditions' => array('Customer.status' => '1')));
        $this->set('customers', $customers);
        $productExtensions = $this->Productextension->find('list', array(
            'conditions' => array('Productextension.status' => '1')));
        $this->set('productExtensions', $productExtensions);
        $processTypes = $this->Processtype->find('list', array(
            'conditions' => array('Processtype.status' => '1')));
        $this->set('processTypes', $processTypes);
        if (!$id) {
            throw new NotFoundException(__('Invalid customer group'));
        }

        $post = $this->Customergroup->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid customer group'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $this->Customergroup->id = $id;



            if (isset($this->request->data['Customergroup']['del_help_file']) && ($this->request->data['Customergroup']['del_help_file']==1)) {
                    $this->request->data['Customergroup']['help_file'] = "";

               }else{
                 if($this->request->data['Customergroup']['help_file']['name']!=""){
                    $fileupload = $this->_uploadFiles('medias/Customergroup_files/', $this->request->data['Customergroup']['help_file'], null);
                    $this->request->data['Customergroup']['help_file'] = str_replace(' ', '_', $this->request->data['Customergroup']['help_file']['name']);
                }else{
                    $this->request->data['Customergroup']['help_file'] = str_replace(' ', '_', $this->request->data['Customergroup']['help_file_temp']);
                }
               }

            


            if ($this->request->data['Customergroup']['upload_file'] != '') {
                move_uploaded_file($this->request->data['Customergroup']['upload_file']['tmp_name'], WWW_ROOT . 'medias/customer_group' . DS . $this->request->data['Customergroup']['upload_file']['name']);
                $this->request->data['Customergroup']['upload_file'] = $this->request->data['Customergroup']['upload_file']['name'];
            } else {
                $this->request->data['Customergroup']['upload_file'] = $post['Customergroup']['upload_file'];
            }
//            lay com va luu vao trong db
            $coms = $this->request->data['Com'];
            $str = "";
            foreach ($coms['name'] as $com) {
                $str = $com . ',' . $str;
            }
            $this->Customergroup->set(array('com_ids' => $str));
            if ($this->Customergroup->save($this->request->data)) {
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

        if ($this->Customergroup->delete($id)) {
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
            $this->Customergroup->deleteAll(array('Customergroup.id' => $keys), false);
            $this->Session->setFlash(
                __('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Xóa thành công!</strong> %s bản ghi đã được xóa.</div>', h(count($keys)))
            );
            echo('OK');
        }
        exit;
    }

    public function getCustomergroupComs($ctgroup_id = null){
        $this->layout = false;
        $ctg = $this->Customergroup->find('first',array('conditions' => array('Customergroup.id'=>$ctgroup_id)));
        if(!empty($ctg)){
            $ctg = $ctg['Customergroup'];
            $ctg['returnCode'] = 1;
        }else{
            $ctg['returnCode'] = 0;
        }
        $this->RequestHandler->respondAs('json');
        $this->autoRender = false;
        return json_encode($ctg);
    }

    public function getCustomergroupData($ctgroup_id = null){
        $this->layout = false;
        $ctg = $this->Customergroup->find('first',array('conditions' => array('Customergroup.id'=>$ctgroup_id)));
        if(!empty($ctg)){
            $ctg = $ctg['Customergroup'];
            $ctg['returnCode'] = 1;
        }else{
            $ctg['returnCode'] = 0;
        }
        $this->RequestHandler->respondAs('json');
        $this->autoRender = false;
        return json_encode($ctg);
    }

    public function status($id = null, $status = null)
    {
        $this->layout = 'ajax';
        $category = $this->Customergroup->findById($id);
        $category['Customergroup']['status'] = $status;
        if ($this->Customergroup->save($category)) {
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