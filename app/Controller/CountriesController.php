<?php

/**
 * Created by PhpStorm.
 * User: MSc. Hoang Dung
 * Date: 10/21/14
 * Time: 2:28 PM
 */
class CountriesController extends AppController
{

    public function index($row = 20)
    {
        $keyword = $this->request->query('keyword');
        if ($keyword) {
            $this->paginate = array(
                'limit' => $row,
                'conditions' => array('Country.name LIKE ' => '%' . $keyword . '%'),
                'order'=>array('Country.order'=>'ASC')
            );
        } else {
            $this->paginate = array(
                'limit' => $row,
                'order'=>array('Country.order'=>'ASC')
            );
        }
        $all = $this->paginate('Country');
        $this->set('countries', $all);
        $this->set('row', $row);
        $this->set('keyword', $keyword);
    }

    public function add()
    {
        $this->set('time_zone',$this->tz_list());
        if ($this->request->is('post')) {
            $this->Country->Create();
            if ($this->request->data['Country']['flag'] != "") {
                move_uploaded_file($this->request->data['Country']['flag']['tmp_name'], WWW_ROOT . 'medias/countries' . DS . $this->request->data['Country']['flag']['name']);
            }
            $this->request->data['Country']['flag'] = $this->request->data['Country']['flag']['name'];
            $this->request->data['Country']['independence_day'] = '0000-'.$this->request->data['Country']['independence_day']['month'].'-'.$this->request->data['Country']['independence_day']['day'];
            if ($this->Country->save($this->request->data)) {
                $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Bản ghi mới đã được thêm.</div>'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể thêm bản ghi mới.</div>'));

        }
    }

    public function edit($id = null)
    {
        $this->set('time_zone',$this->tz_list());
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }

        $post = $this->Country->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Country->id = $id;
            if ($this->request->data['Country']['flag'] != '') {
                move_uploaded_file($this->request->data['Country']['flag']['tmp_name'], WWW_ROOT . 'medias/countries' . DS . $this->request->data['Country']['flag']['name']);
                $this->request->data['Country']['flag'] = $this->request->data['Country']['flag']['name'];
            }
            if($this->request->data['Country']['flag'] == '') {
                $this->request->data['Country']['flag'] = $post['Country']['flag'];
            }

            $this->request->data['Country']['independence_day'] = '0000-'.$this->request->data['Country']['independence_day']['month'].'-'.$this->request->data['Country']['independence_day']['day'];
//            debug($this->request->data);die;
            if ($this->Country->save($this->request->data)) {
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

        if ($this->Country->delete($id)) {
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
            $this->Country->deleteAll(array('Country.id' => $keys), false);
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
        $Country = $this->Country->findById($id);
        $Country['Country']['status'] = $status;
        if ($this->Country->save($Country)) {
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

    function tz_list() {
        $zones_array = array();
        $timestamp = time();
        foreach(timezone_identifiers_list() as $key => $zone) {
            date_default_timezone_set($zone);
            $zones_array[$zone] = 'UTC/GMT ' . date('P', $timestamp) .' - '. $zone;
         }
        return $zones_array;
     }
}