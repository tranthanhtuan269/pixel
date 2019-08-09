<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
    public $uses = array('Country','WorkDepartment','Project', 'Status', 'Product', 'Timelogin', 'Vacation','User');

    public function beforeFilter(){
        parent::beforeFilter();

      }
    public function display()
    {
        $path = func_get_args();

        $count = count($path);
        if (!$count) {
            return $this->redirect('/');
        }
        $page = $subpage = $title_for_layout = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        if (!empty($path[$count - 1])) {
            $title_for_layout = Inflector::humanize($path[$count - 1]);
        }
        $this->set(compact('page', 'subpage', 'title_for_layout'));

        try {
            $this->render(implode('/', $path));
        } catch (MissingViewException $e) {
            if (Configure::read('debug')) {
                throw $e;
            }
            throw new NotFoundException();
        }
    }

    public function index()
    {
        $status = $this->Status->find('list', array('fields' => array('Status.ID', 'Status.Name')));
        $today = date('Y-m-d');
        $list_project = $this->Project->find('all', array(
            'limit' => 5,
            'order' => array('Project.id' => 'DESC')
        ));
        $file_of_date = $this->Product->find('count', array(
            'conditions' => array(
                'Product.deliver_date LIKE' => '%' . $today . '%',
                'Product.perform_user_id' => $this->Auth->user('id'),
            ),
        ));
        $countries = $this->Country->find('list', array('fields' => array('Country.id', 'Country.name')));
        $this->set(compact(array('countries')));
        $this->set('group_id',$this->Auth->user('group_id'));
        $this->set('list_project', $list_project);
        $this->set('status', $status);
        $this->set('file_of_date', $file_of_date);

    }

    public function form()
    {

    }

    public function time_work()
    {
        $to_day = date('Y-m-d');
        $time_work = $this->Timelogin->find('all', array(
            'fields' => array('Timelogin.time_login', 'Timelogin.time_logout', 'Timelogin.time_work'),
            'conditions' => array('Timelogin.time_login LIKE ' => '%' . $to_day . '%',
             'Timelogin.user_id' => $this->Auth->user('id'),
            )
        ));
        $th1 = 0;
        $th2 = 0;
        $today = date('Y-m-d H:i:s');
        foreach($time_work as $item){
            if($item['Timelogin']['time_logout'] == '' && $item['Timelogin']['time_login'] != ''){
                $th2 = $th2 + (strtotime($today) - strtotime($item['Timelogin']['time_login']));
            }
            if($item['Timelogin']['time_logout'] != '' && $item['Timelogin']['time_login'] != ''){
                $th1 = $th1 + $item['Timelogin']['time_work'];
            }
        }
        $total_time = $th1 + $th2;
        return $total_time;
    }

    public function time_work_customer()
    {
        $time_work = $this->WorkDepartment->find('first', array(
            'fields' => array('WorkDepartment.time_start', 'WorkDepartment.time_stop'),
            'conditions' => array('WorkDepartment.time_stop' => null,
                'WorkDepartment.user_id' => $this->Auth->user('id'),
            )
        ));
        $th1 = 0;
        $today = date('Y-m-d H:i:s');
        if(count($time_work) > 0){
            $th1 = (strtotime($today) - strtotime($time_work['WorkDepartment']['time_start']));
        }
        return $th1;
    }

    public function list_project()
    {
        $this->layout = 'ajax';
        if ($this->request->data['status'] > 0) {
            $list_project = $this->Project->find('all', array(
                'limit' => 5,
                'conditions' => array('Project.Status_id' => $this->request->data['status']),
                'order' => array('Project.id' => 'DESC')
            ));
            $this->set('list_project', $list_project);
        } else {
            $this->set('list_project', false);
        }
    }

    public function date_work()
    {
        $y = date('Y');
        $month = date("m");
        $d = mktime(0, 0, 0, $month, 1, $y);
        $from_time = date("Y-m-d H:i:s", $d);
        $month_1 = $month + 1;
        $d_1 = mktime(0, 0, 0, $month_1, 1, $y);
        $to_time = date("Y-m-d H:i:s", $d_1);
        $users = $this->Timelogin->find('all', array(
//            'fields' => 'DISTINCT Timelogin.user_id',
            'conditions' => array('Timelogin.time_login >=' => $from_time,
                'Timelogin.time_login <=' => $to_time,
                'Timelogin.user_id' => $this->Auth->user('id'),
            )));
        $total = 0;
        foreach ($users as $item) {
            $total = $total + $item['Timelogin']['time_work'];
        }
        $time_day = array_key_exists('TIME_WORK', $this->CF) ? $this->CF['TIME_WORK'] : 480;
        $time_work = round($total / ($time_day* 60), 1);
        return $time_work;
    }

    public function vacation()
    {
        $y = date('Y');
        $month = date("m");
        $d = mktime(0, 0, 0, $month, 1, $y);
        $from_time = date("Y-m-d H:i:s", $d);
        $month_1 = $month + 1;
        $d_1 = mktime(0, 0, 0, $month_1, 1, $y);
        $to_time = date("Y-m-d H:i:s", $d_1);
        $vacation = $this->Vacation->find('all', array(
//            'fields' => 'DISTINCT Vacation.user_id',
            'conditions' => array('Vacation.from_date >=' => $from_time,
                'Vacation.from_date <=' => $to_time,
                'Vacation.user_id' => $this->Auth->user('id'),
            )
        ));
        $dem = count($vacation);
        if ($dem == 0) {
            return 0;
        } else {
            $date_vacation_1 = 0;
            $date_vacation_2 = 0;
            foreach ($vacation as $item) {
                if ($item['Vacation']['to_date'] <= $to_time && $item['Vacation']['to_date'] >= $from_time) {
                    $date_vacation_1 = $date_vacation_1 + (strtotime($item['Vacation']['to_date']) - strtotime($item['Vacation']['from_date'])) / (24 * 60 * 60) + 1;
                }
                if ($item['Vacation']['to_date'] >= $to_time) {
                    $date_vacation_2 = $date_vacation_2 + (strtotime($to_time) - strtotime($item['Vacation']['from_date'])) / (24 * 60 * 60) + 1;
                }
            }
            $date_vacation = ($date_vacation_1 + $date_vacation_2);
            return $date_vacation;
        }

    }
    public function start_work (){
        $this->layout = 'ajax';
        $this->WorkDepartment->create();
        $array = array();
        $array['WorkDepartment']['user_id'] = $this->Auth->user('id');
        $array['WorkDepartment']['time_start'] = date('Y-m-d H:i:s');
        $array['WorkDepartment']['status'] = 1;
        $this->WorkDepartment->save($array) ;
        exit;
    }
    public function stop_work(){
        $this->layout = 'ajax';
        $work = $this->WorkDepartment->find('first',array(
            'conditions' => array(
                'WorkDepartment.status' => 1,
                'WorkDepartment.user_id' => $this->Auth->user('id'),
                'WorkDepartment.time_stop' => null
            ),
            'order' => array('WorkDepartment.id' => 'DESC')
        ));
        $today = date('Y-m-d H:i:s');
        $work['WorkDepartment']['status'] = 0;
        $work['WorkDepartment']['country_id'] = $this->request->data['country_id'];
        $work['WorkDepartment']['customer_id'] = $this->request->data['customer_id'];
        $work['WorkDepartment']['customer_group_id'] = $this->request->data['customerGroup_id'];
        $work['WorkDepartment']['content'] = $this->request->data['content'];
        $time_work = (strtotime($today) - strtotime($work['WorkDepartment']['time_start']));
        if ($time_work <= ($this->CF['TIME_DEPARTMENT'] * 60)) {
            $work['WorkDepartment']['time_stop'] = date('Y-m-d H:i:s');
            $work['WorkDepartment']['time_work'] = $time_work;
        } else {
            $duration= '+ '.($this->CF['TIME_DEPARTMENT']).'minutes';
            $cur_time = date('Y-m-d H:i:s', strtotime($duration, strtotime($work['WorkDepartment']['time_start'])));
            $work['WorkDepartment']['time_stop'] = $cur_time;
            $work['WorkDepartment']['time_work'] = ($this->CF['TIME_DEPARTMENT'] * 60);
        }
        $this->WorkDepartment->save($work);
        echo "OK";
        exit;
    }
}
