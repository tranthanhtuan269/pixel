<?php

class VacationsController extends AppController
{
    public $uses = array('Vacation', 'Vacationtype', 'User');

    public function index($row = 20)
    {
        $keyword = $this->request->query('keyword');
        if ($keyword) {
            $this->paginate = array(
                'limit' => $row,
                'conditions' => array(
                    'name LIKE ' => '%' . $keyword . '%'),
                'order' => array('Vacation.id' => 'DESC')
            );
        } else {
            $this->paginate = array(
                'limit' => $row,
                'order' => array('Vacation.id' => 'DESC')
            );
        }
        $all = $this->paginate('Vacation');
        $this->set('vacations', $all);
        $this->set('row', $row);
        $this->set('keyword', $keyword);
    }

    public function add()
    {
        // lay nhan vien, loai ngay nghi vao listbox
        $vacationTypes = $this->Vacationtype->find('list', array(
            'conditions' => array('Vacationtype.status' => '1')
        ));
        $this->set('vacationTypes', $vacationTypes);
        $this->set('user_id',$this->Auth->user('id'));
        $success = true;
        if ($this->request->is('post')) {
            $this->Vacation->Create();
//            $this->request->data['Vacation']['user_id'] = $this->request->data['NV_ID'];
            $this->request->data['Vacation']['user_id'] = $this->Auth->user('id');
            $this->request->data['Vacation']['permit'] = 2;
            if ($this->request->data['Vacation']['from_date'] && $this->request->data['Vacation']['from_date'] != '') {
                $this->request->data['Vacation']['from_date'] = date("Y-m-d", strtotime(str_replace('/', '-', $this->request->data['Vacation']['from_date'])));
            }
            if ($this->request->data['Vacation']['to_date'] && $this->request->data['Vacation']['to_date'] != '') {
                $this->request->data['Vacation']['to_date'] = date("Y-m-d", strtotime(str_replace('/', '-', $this->request->data['Vacation']['to_date'])));
            }
            if (!$this->Vacation->save($this->request->data)) {
                $success = false;
            };
            if ($success) {
                $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Bản ghi mới đã được thêm.</div>'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm bản ghi mới.</div>'));

        }
    }

    public function edit($id = null)
    {
        // lay nhan vien, loai ngay nghi vao listbox
        $vacationTypes = $this->Vacationtype->find('list', array(
            'conditions' => array('Vacationtype.status' => '1')
        ));
        $this->set('vacationTypes', $vacationTypes);
        if (!$id) {
            throw new NotFoundException(__('Invalid vacation'));
        }
        $post = $this->Vacation->findById($id);
        if ($post['Vacation']['from_date'] != '') {
            $post['Vacation']['from_date'] = date("d/m/Y", strtotime(str_replace('-', '/',$post['Vacation']['from_date'])));
        }
        if ($post['Vacation']['to_date'] != '') {
            $post['Vacation']['to_date'] = date("d/m/Y", strtotime(str_replace('-', '/', $post['Vacation']['to_date'])));
        }
        if (!$post) {
            throw new NotFoundException(__('Invalid vacation'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->request->data['Vacation']['from_date'] && $this->request->data['Vacation']['from_date'] != '') {
                $this->request->data['Vacation']['from_date'] = date("Y-m-d", strtotime(str_replace('/', '-', $this->request->data['Vacation']['from_date'])));
            }
            if ($this->request->data['Vacation']['to_date'] && $this->request->data['Vacation']['to_date'] != '') {
                $this->request->data['Vacation']['to_date'] = date("Y-m-d", strtotime(str_replace('/', '-', $this->request->data['Vacation']['to_date'])));
            }
            $this->request->data['Vacation']['id'] = $id;
            if ($this->Vacation->save($this->request->data)) {
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

        if ($this->Vacation->delete($id)) {
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
            $this->Vacation->deleteAll(array('Vacation.id' => $keys), false);
            $this->Session->setFlash(
                __('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Xóa thành công!</strong> %s bản ghi đã được xóa.</div>', h(count($keys)))
            );
            echo('OK');
        }
        exit;
    }

   public function edit_permit()
   {
       if($this->request->is(array('post', 'put')))
       {
           $vacation = $this->Vacation->findById($this->request->data['userid']);
           $vacation['Vacation']['id'] = $this->request->data['id'];
           $vacation['Vacation']['permit'] = $this->request->data['permit'];
           if ($this->Vacation->save($vacation)) {
               $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Trạng thái đã được thay đổi.</div>'));
               return $this->redirect(array('action' => 'index'));
           }
           $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể lưu bản ghi.</div>'));
       }
   }
}