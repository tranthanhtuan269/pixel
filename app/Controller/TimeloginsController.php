<?php
/**
 * Created by PhpStorm.
 * User: MSc. Hoang Dung
 * Date: 11/10/14
 * Time: 2:51 PM
 */
class TimeloginsController extends AppController
{
    public $uses = array('Vacation', 'Timelogin', 'User', 'Department');

    public function view()
    {
        $department = $this->Department->find('list');
        $this->set('department', $department);
        if ($this->request->is('post')) {
            $date_work = $this->request->data['Timelogin']['date_work'];
            $month = $this->request->data['Timelogin']['month'];
            $year = $this->request->data['Timelogin']['year'];
            $department_id = $this->request->data['Timelogin']['department_id'];
            $conditions = array();
            if ($department_id && $department_id != '') {
                $conditions['User.department_id '] = $department_id;
                if ($date_work == '' && $month == '' && $year == '') {
                    $month = date('m');
                    $month_2 = $month + 1;
                    $year = date('Y');
                    $date1 = date('Y-' . $month . '-01 00:00:00');
                    if ($month < 9) {
                        $date2 = date('Y-0' . $month_2 . '-01 00:00:00');
                    } else {
                        $date2 = date('Y-' . $month_2 . '-01 00:00:00');
                    }
                    $conditions['Timelogin.time_login >= '] = $date1;
                    $conditions['Timelogin.time_login < '] = $date2;
                }
            }
            if ($date_work && $date_work != '') {
                $conditions['Timelogin.time_login LIKE'] = '%' . date("Y-m-d", strtotime(str_replace('/', '-', $date_work))) . '%';
                $month_searh = explode('/', $date_work);
                $month = $month_searh[1];
                $year = $month_searh[2];
                $this->set('date_work', $date_work);
            }
            if ($date_work && $date_work != '' && $month && $month != '' && $year && $year != '') {
                $conditions['Timelogin.time_login LIKE'] = '%' . date("Y-m-d", strtotime(str_replace('/', '-', $date_work))) . '%';
                $month_searh = explode('/', $date_work);
                $month = $month_searh[1];
                $year = $month_searh[2];
                $this->set('date_work', $date_work);
            }
            if ($month && $month != '' && $year && $year != '') {
                $month_2 = $month + 1;
                $date1 = date($year . '-' . $month . '-01 00:00:00');
                if ($month < 9) {
                    $date2 = date($year . '-0' . $month_2 . '-01 00:00:00');
                } else {
                    $date2 = date($year . '-' . $month_2 . '-01 00:00:00');
                }
                $conditions['Timelogin.time_login >= '] = $date1;
                $conditions['Timelogin.time_login < '] = $date2;
            }
        } else {
            $month = date('m');
            $month_2 = $month + 1;
            $year = date('Y');
            $date1 = date('Y-' . $month . '-01 00:00:00');
            if ($month < 9) {
                $date2 = date('Y-0' . $month_2 . '-01 00:00:00');
            } else {
                $date2 = date('Y-' . $month_2 . '-01 00:00:00');
            }
            $conditions['Timelogin.time_login >= '] = $date1;
            $conditions['Timelogin.time_login < '] = $date2;
        }
        $list_login = $this->Timelogin->find('all', array(
            'conditions' => $conditions,
        ));
//        debug($list_login);die;
        $new_list = array();
        foreach ($list_login as $item) {
            if (!isset($new_list[$item['Timelogin']['user_id']])) {
                $new_list[$item['Timelogin']['user_id']] = array('time' => 0);
            }
            $new_list[$item['Timelogin']['user_id']]['time'] += $item['Timelogin']['time_work'];
            $new_list[$item['Timelogin']['user_id']]['name'] = $item['User']['name'];
            $new_list[$item['Timelogin']['user_id']]['username'] = $item['User']['username'];
            $new_list[$item['Timelogin']['user_id']]['department_id'] = $item['User']['department_id'];
        }
//        debug($new_list);die;
        $time_day = array_key_exists('TIME_WORK', $this->CF) ? $this->CF['TIME_WORK'] : 480;
        $this->set('new_list', $new_list);
        $this->set('month', $month);
        $this->set('year', $year);
        $this->set('time_day', $time_day);
        //        debug($new_list);
//        debug($list_login);die;
    }

    public function get_user($department_id = null)
    {
        $user_info = $this->Department->find('first', array(
            'fields' => array('Department.id', 'Department.name'),
            'conditions' => array(
                'Department.id' => $department_id,
            )
        ));
        return $user_info;
    }

    public function user_time()
    {
        $this->layout = 'ajax';
        $month = $this->request->data['month'];
        $month_2 = $month + 1;
        $date1 = date('Y-' . $month . '-01 00:00:00');
        if ($month < 9) {
            $date2 = date('Y-0' . $month_2 . '-01 00:00:00');
        } else {
            $date2 = date('Y-' . $month_2 . '-01 00:00:00');
        }
        $user_id = $this->request->data['user_id'];
        $name = $this->request->data['name'];
        $list_time_user = $this->Timelogin->find('all', array(
            'fields' => array('Timelogin.time_login', 'Timelogin.time_logout', 'Timelogin.time_work'),
            'conditions' => array('Timelogin.user_id' => $user_id,
                'Timelogin.time_login >= ' => $date1,
                'Timelogin.time_login < ' => $date2
            )
        ));
        $total = 0;
        $new_list = array();
        foreach ($list_time_user as $key => $item) {
            $total += $item['Timelogin']['time_work'];
            $login = explode(' ', $item['Timelogin']['time_login']);
            if ($item['Timelogin']['time_logout'] != '') {
                $logout = explode(' ', $item['Timelogin']['time_logout']);
                $new_list[$key]['time_logout'] = $logout[1];
            } else {
                $new_list[$key]['time_logout'] = '';
            }
            $new_list[$key]['time_login'] = $login[1];
            $date = explode('-', $login[0]);
            $new_list[$key]['date'] = $date[2] . '/' . $date[1] . '/' . $date[0];
            $new_list[$key]['time_work'] = $item['Timelogin']['time_work'];
        }
        $h = round($total / 3600, 0);
        $i = round(($total - ($h * 3600)) / 60, 0);
        $this->set('new_list', $new_list);
        $this->set('name', $name);
        $this->set('month', $month);
        $this->set('department', $this->request->data['department']);
        $this->set('h', $h);
        $this->set('i', $i);
    }

    public function get_vacation($user_id = null, $month = null, $year = null)
    {
        $month_2 = $month + 1;
        if ($month < 9) {
            $date2 = date($year . '-0' . $month_2 . '-01');
            $date1 = date($year . '-' . $month . '-01');
        } else {
            $date1 = date($year . '-' . $month . '-01');
            $date2 = date($year . '-' . $month_2 . '-01');
        }
        $conditions['Vacation.from_date >= '] = $date1;
        $conditions['Vacation.from_date < '] = $date2;
        $conditions['Vacation.permit < '] = 2;
        $conditions['Vacation.user_id'] = $user_id;
        $vacation = $this->Vacation->find('all', array(
//            'conditions' => $conditions
            'conditions' => array(
                'OR' => array(
                    $conditions,
                    array(
                        'Vacation.from_date < ' => $date1,
                        'Vacation.to_date >= ' => $date1,
                        'Vacation.to_date < ' => $date2,
                        'Vacation.user_id < ' => $user_id,
                        'Vacation.permit < ' => 2,
                    )
                )
            )
        ));
        $total1 = 0;
        $total1_permit = 0;
        $total2 = 0;
        $total2_permit = 0;
        $total3 = 0;
        $total3_permit = 0;
        foreach ($vacation as $item) {
            //tinh ngay nghi khong phep
            if ($item['Vacation']['from_date'] > $date1 &&  $date1 <= $item['Vacation']['to_date'] && $item['Vacation']['to_date'] < $date2 && $item['Vacationtype']['permit'] == 0) {
                $interval = (new DateTime($item['Vacation']['from_date']))->diff(new DateTime($item['Vacation']['to_date']));
                $total1 += ($interval->format('%a') + 1);
            }
            if ($item['Vacation']['to_date'] > $date2 && $item['Vacationtype']['permit'] == 0) {
                $interval = (new DateTime($item['Vacation']['from_date']))->diff(new DateTime($date2));
                $total2 += ($interval->format('%a'));
            }
            if($item['Vacation']['from_date'] < $date1 &&  $date1 <= $item['Vacation']['to_date'] && $item['Vacation']['to_date'] < $date2 && $item['Vacationtype']['permit'] == 0){
                $interval = (new DateTime($date1))->diff(new DateTime($item['Vacation']['to_date']));
                $total3 += ($interval->format('%a'));
            }
            //tinh ngay nghi co phep
            if ( $item['Vacation']['from_date'] > $date1 && $date1 <= $item['Vacation']['to_date'] && $item['Vacation']['to_date'] < $date2 && $item['Vacationtype']['permit'] == 1) {
                $interval_permit = (new DateTime($item['Vacation']['from_date']))->diff(new DateTime($item['Vacation']['to_date']));
                $total1_permit += ($interval_permit->format('%a') + 1);
            }
            if ($item['Vacation']['to_date'] > $date2 && $item['Vacationtype']['permit'] == 1) {
                $interval_permit = (new DateTime($item['Vacation']['from_date']))->diff(new DateTime($date2));
                $total2_permit += ($interval_permit->format('%a'));
            }
            if($item['Vacation']['from_date'] < $date1 &&  $date1 <= $item['Vacation']['to_date'] && $item['Vacation']['to_date'] < $date2 && $item['Vacationtype']['permit'] == 1){
                $interval = (new DateTime($date1))->diff(new DateTime($item['Vacation']['to_date']));
                $total3_permit += ($interval->format('%a'));
            }

        }
        $total = $total1 + $total2 + $total3;
        $total_permit = $total1_permit + $total2_permit + $total3_permit;
        return $total . '/' . $total_permit;
    }
}