<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiemtd
 * Date: 5/15/15
 * Time: 10:46 PM
 * To change this template use File | Settings | File Templates.
 */
class RejectsController extends AppController
{
    public $uses = array('RatePoint','WorkingGroup','Reject', 'ProjectAction', 'Notification', 'Email', 'User', 'Product', 'Project');

    public function add()
    {
        if ($this->request->is('post')) {
//            debug($this->request->data);die;
            $all_name_user = '';
            $all_percent = '';
            $user = $this->request->data['user'];
            $percent = $this->request->data['percent'];
            $action = $this->request->data['action'];
            $user_add = $this->request->data['user_add'];
            $percent_add = $this->request->data['percent_add'];
            $rate_point = $this->RatePoint->findById($this->request->data['Reject']['rate_point_id']);
//            debug($rate_point);die;

            $email = $this->Email->findById('7');
            $user_reject = array();
            //// reject nhân viên bị bổ xung////
            $data = null;


            foreach ($percent_add as $key => $pra) {
                $dt_user_add = array();
                if ($pra != '') {
                    $this->Reject->Create();
                    if (isset($this->request->data['Reject']['file']['name']) && $this->request->data['Reject']['file']['name'] != '') {
                        $this->request->data['Reject']['file']['name'] = $this->char($this->request->data['Reject']['file']['name']);
                        $fileupload = $this->_uploadFiles('/Reject', $this->request->data['Reject']['file'], null);
                        if (array_key_exists('urls', $fileupload)) {
                            $this->request->data['Reject']['file'] = $this->request->data['Reject']['file']['name'];
                            $file_name = $this->request->data['Reject']['file'];
                        }
                    } else {
                        $this->request->data['Reject']['file'] = '';
                    }
                    if (isset($file_name)) {
                        $this->request->data['Reject']['file'] = $file_name;
                    }
                    if (empty($this->request->data['Reject']['product_id']) || $this->request->data['Reject']['product_id'] == 0) {
                        $this->request->data['Reject']['product_id'] = null;
                    }
                    $this->request->data['Reject']['user_id_reject'] = $user_add[$key];
                    $user_reject[] = $user_add[$key];
                    $this->request->data['Reject']['action_id'] = '13';
                    $this->request->data['Reject']['rejecter'] = $this->Auth->user('id');
                    $this->request->data['Reject']['working_group_id'] = (isset($this->request->data['working_group_id'])) ? $this->request->data['working_group_id'] : 0;
                    $this->request->data['Reject']['percent'] = $pra;
                    $this->request->data['Reject']['user_id_message'] = $this->request->data['NV_ID_2'];
                    $this->request->data['Reject']['datetime'] = date('Y-m-d H:i:s');
                    $data['ProjectAction'] = array(
                        'Project_id' => $this->request->data['Reject']['project_id'],
                        'Action_id' => 13,
                        'User_id' => $user_add[$key],
                        'Point' => 0
                    );
                    $this->ProjectAction->save($data['ProjectAction']);
                    $this->Reject->save($this->request->data['Reject']);
                    //// Xử lý phần thông báo cho các nhân viên bị reject///
                    $this->Notification->create();
                    $user_info = $this->User->find('first', array(
                        'fields' => array('User.username', 'User.id'),
                        'conditions' => array('User.id' => $user_add[$key])
                    ));
                    $all_name_user .= $user_info['User']['username'] . ',';
                    $all_percent .= ($pra * 10) . ',';
                    $info_project = $this->Project->find('first', array(
                        'fields' => array('Project.Name', 'Project.ID'),
                        'conditions' => array('Project.ID' => $this->request->data['Reject']['project_id'])
                    ));
                    $content_email = str_replace('#NAME_USER_1#', $user_info['User']['username'], $email['Email']['content']);
                    $content_email = str_replace('#NAME_USER#', $user_info['User']['username'], $content_email);
                    $content_email = str_replace('#NAME_PROJECT#', $info_project['Project']['Name'], $content_email);
                    if ($this->request->data['Reject']['product_id'] != 0) {
                        $info_pro = $this->Product->find('first', array(
                            'fields' => array('Product.name_file_product', 'Product.id'),
                            'conditions' => array('Product.id' => $this->request->data['Reject']['product_id'])
                        ));
                        $content_email = str_replace('#NAME_FILE#', $info_pro['Product']['name_file_product'], $content_email);
                    } else {
                        $content_email = str_replace('#NAME_FILE#', '', $content_email);

                    }
                    $content_email = str_replace('#FILE#','<a href="'.$this->domain.'Reject/'.$this->request->data['Reject']['file'].'" download>'.$this->request->data['Reject']['file'].'</a>', $content_email);
                    $content_email = str_replace('#NOTE#', $this->request->data['Reject']['desc'], $content_email);
                    $content_email = str_replace('#PERCENT#', ($pra * 10), $content_email);
                    $content_email = str_replace('#BTN_OK#', '<a href="' . Router::url(array('controller' => 'Rejects', 'action' => 'accept', $this->Reject->getInsertID())) . '" class="btn btn-primary show-tooltip" title="Chấp nhận">Chấp nhận</a>', $content_email);
                    $content_email = str_replace('#BTN_CANCEL#','<a  data-toggle="modal"  id="cancel_reject" href="#modal-cancel" class="btn btn-danger show-tooltip" title="Xem xét lại">Xem lại</a><div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="modal-cancel" style="display: none;"><form action="/rejects/edit" method="post"><div class="modal-header">
    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
    <h3 id="myModalLabel">Nêu lý do xin xem xét lại</h3>
</div><div class="modal-body"><textarea name="data[Reject][Note]" class=" span12 ckeditor" id="RejectNote"></textarea><input type="hidden" name="data[Reject][id]" id="RejectId" value="'.$this->Reject->getInsertID().'"></div><div class="modal-footer">
    <button aria-hidden="true" data-dismiss="modal" class="btn">Hủy</button>
    <input type="submit" value="Gửi" class="btn btn-primary">
</div></form></div>', $content_email);
                    $dt_user_add['Notification']['content'] = $content_email;
                    $dt_user_add['Notification']['touser_id'] = $user_add[$key];
                    $dt_user_add['Notification']['title'] = 'Tiêu đề: Thông báo nhắc nhở/reject cấp <b>'.$rate_point['RatePoint']['name'].'</b> đối với đơn hàng <b>' . $info_project['Project']['Name'] . '</b>';
                    $dt_user_add['Notification']['createdate'] = date('Y-m-d H:i:s');
                    $dt_user_add['Notification']['user_id'] = 0;
//                    debug($content_email);die;
                    $this->Notification->save($dt_user_add);
                }
            }
            /// reject nhân viên liên quan đến dự án
            foreach ($percent as $key => $pr) {
                if ($pr != '') {
                    $this->Reject->Create();
                    if (isset($this->request->data['Reject']['file']['name']) && $this->request->data['Reject']['file']['name'] != '') {
                        $this->request->data['Reject']['file']['name'] = $this->char($this->request->data['Reject']['file']['name']);
                        $fileupload = $this->_uploadFiles('/Reject', $this->request->data['Reject']['file'], null);
                        if (array_key_exists('urls', $fileupload)) {
                            $this->request->data['Reject']['file'] = $this->request->data['Reject']['file']['name'];
                            $file_name = $this->request->data['Reject']['file'];
                        }
                    } else {
                        $this->request->data['Reject']['file'] = '';
                    }
                    if (isset($file_name)) {
                        $this->request->data['Reject']['file'] = $file_name;
                    }
                    if (empty($this->request->data['Reject']['product_id'])||$this->request->data['Reject']['product_id'] == 0) {
                        $this->request->data['Reject']['product_id'] = null;
                    }
                    $this->request->data['Reject']['user_id_reject'] = $user[$key];
                    $user_reject[] = $user[$key];
                    $this->request->data['Reject']['working_group_id'] = (isset($this->request->data['working_group_id'])) ? $this->request->data['working_group_id'] : 0;
                    $this->request->data['Reject']['action_id'] = $action[$key];
                    $this->request->data['Reject']['rejecter'] = (int)$this->Auth->user('id');

                    $this->request->data['Reject']['percent'] = $pr;
                    $this->request->data['Reject']['user_id_message'] = $this->request->data['NV_ID_2'];
                    $this->request->data['Reject']['datetime'] = date('Y-m-d H:i:s');

                    $this->Reject->save($this->request->data['Reject']);
                    //// Xử lý phần thông báo cho các nhân viên bị reject///
                    $this->Notification->create();
                    $user_info = $this->User->find('first', array(
                        'fields' => array('User.username', 'User.id'),
                        'conditions' => array('User.id' => $user[$key])
                    ));
                    $all_name_user .= $user_info['User']['username'] . ',';
                    $all_percent .= ($pr * 10) . ',';
                    $info_project = $this->Project->find('first', array(
                        'fields' => array('Project.Name', 'Project.ID'),
                        'conditions' => array('Project.ID' => $this->request->data['Reject']['project_id'])
                    ));
                    $content_email = str_replace('#NAME_USER_1#', $user_info['User']['username'], $email['Email']['content']);
                    $content_email = str_replace('#NAME_USER#', $all_name_user, $content_email);
                    $content_email = str_replace('#NAME_PROJECT#', $info_project['Project']['Name'], $content_email);

                    $info_pro = array(
                        'Product'=> array(
                            'name_file_product'=>'',
                            'name'=>'',
                        )
                    );


                    if ($this->request->data['Reject']['product_id'] != 0) {
                        $info_pro = $this->Product->find('first', array(
                            'fields' => array('Product.name_file_product', 'Product.id'),
                            'conditions' => array('Product.id' => $this->request->data['Reject']['product_id'])
                        ));
                        $content_email = str_replace('#NAME_FILE#', $info_pro['Product']['name_file_product'], $content_email);
                    } else {
                        $content_email = str_replace('#NAME_FILE#', '', $content_email);

                    }
                    $content_email = str_replace('#FILE#','<a href="'.$this->domain.'Reject/'.$this->request->data['Reject']['file'].'" download>'.$this->request->data['Reject']['file'].'</a>', $content_email);
                    $content_email = str_replace('#NOTE#', $this->request->data['Reject']['desc'], $content_email);
                    $content_email = str_replace('#PERCENT#', ($pr * 10), $content_email);
                    $content_email = str_replace('#BTN_OK#', '<a href="' . Router::url(array('controller' => 'Rejects', 'action' => 'accept', $this->Reject->getInsertID())) . '" class="btn btn-primary show-tooltip" title="Chấp nhận">Chấp nhận</a>', $content_email);
                    $content_email = str_replace('#BTN_CANCEL#','<a  data-toggle="modal"  id="cancel_reject" href="#modal-cancel" class="btn btn-danger show-tooltip" title="Xem xét lại">Xem lại</a>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="modal-cancel" style="display: none;">
<form action="/rejects/edit" method="post">
<div class="modal-header">
    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
    <h3 id="myModalLabel">Nêu lý do xin xem xét lại</h3>
</div>
<div class="modal-body"><textarea name="data[Reject][Note]" class=" span12 ckeditor" id="RejectNote">

 Gửi  Admin, traffic, teamlearders, văn phòng <br>    
Sau khi xem lại thực tế lý do bị reject và các lý do khách quan khác, Tôi '.$user_info['User']['username'].' đề nghị ban quản trị xem xét lại cho tôi nội dung reject với thông tin sau:<br><br>
File: '.$info_pro['Product']['name_file_product'].'<br>
Thuộc đơn hàng: '.$info_project['Project']['Name'].'<br>
  được reject vào '.date("H:i:s d/m/Y").' <br>

 Nghiệp vụ xử lý : làm hàng, check hàng, khởi tạo, v.v. <br>
 
Với Lý do khiếu nại:  <br><br><br><br>
 	
File đính kèm (nếu có) <br><br><br>

Mức độ reject đề xuất:  <br><br>

Xin cảm ơn<br>

</textarea>
    <input type="hidden" name="data[Reject][id]" id="RejectId" value="'.$this->Reject->getInsertID().'">
    </div>
<div class="modal-footer">
    <button aria-hidden="true" data-dismiss="modal" class="btn">Hủy</button>
    <input type="submit" value="Gửi" class="btn btn-primary">
</div>
</form>
</div>', $content_email);
                   // $content_email = str_replace('#BTN_CANCEL#','<a  data-toggle="modal"  id="cancel_reject" href="#modal-cancel-'.$this->Reject->getInsertID().'" class="btn btn-danger show-tooltip" title="Xem xét lại">Xem lại</a><div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="modal-cancel-'.$this->Reject->getInsertID().'" style="display: none;"></div>', $content_email);
                    $dt_user_add['Notification']['content'] = $content_email;
                    $dt_user_add['Notification']['title'] = 'Tiêu đề: Thông báo nhắc nhở/reject cấp <b>'.$rate_point['RatePoint']['name'].'</b> đối với đơn hàng <b>' . $info_project['Project']['Name'] . '</b>';
                    $dt_user_add['Notification']['touser_id'] = $user[$key];
                    $dt_user_add['Notification']['createdate'] = date('Y-m-d H:i:s');
                    $dt_user_add['Notification']['user_id'] = 0;
                    $this->Notification->save($dt_user_add);
                }

            }
            ///////////////////// xử lý phần gửi thông báo cho nhân viên dc nhận thông báo////////////////////
            $email_2 = $this->Email->findById('8');
            $user_mess = explode(',', $this->request->data['NV_ID_2']);
            foreach ($user_mess as $item) {
                if ($item != '') {
                    $this->Notification->create();
                    $user_info = $this->User->find('first', array(
                        'fields' => array('User.username', 'User.id'),
                        'conditions' => array('User.id' => $item)
                    ));
                    $info_project = $this->Project->find('first', array(
                        'fields' => array('Project.Name', 'Project.ID'),
                        'conditions' => array('Project.ID' => $this->request->data['Reject']['project_id'])
                    ));
                    $content_email = str_replace('#NAME_USER_1#', $user_info['User']['username'], $email_2['Email']['content']);
                    $content_email = str_replace('#NAME_USER#', $all_name_user, $content_email);
                    $content_email = str_replace('#NAME_PROJECT#', $info_project['Project']['Name'], $content_email);
                    if ($this->request->data['Reject']['product_id'] != 0) {
                        $info_pro = $this->Product->find('first', array(
                            'fields' => array('Product.name_file_product', 'Product.id'),
                            'conditions' => array('Product.id' => $this->request->data['Reject']['product_id'])
                        ));
                        $content_email = str_replace('#NAME_FILE#', $info_pro['Product']['name_file_product'], $content_email);
                    } else {
                        $content_email = str_replace('#NAME_FILE#', '', $content_email);

                    }
                    $content_email = str_replace('#FILE#','<a href="'.$this->domain.'Reject/'.$this->request->data['Reject']['file'].'" download>'.$this->request->data['Reject']['file'].'</a>', $content_email);
                    $content_email = str_replace('#NOTE#', $this->request->data['Reject']['desc'], $content_email);
                    $content_email = str_replace('#PERCENT#', $all_percent, $content_email);
                    $content_email = str_replace('#BTN_OK#', '<a href="' . Router::url(array('controller' => 'Rejects', 'action' => 'accept', $this->Reject->getInsertID())) . '" class="btn btn-primary show-tooltip" title="Chấp nhận">Chấp nhận</a>', $content_email);
                    $content_email = str_replace('#BTN_CANCEL#','<a  data-toggle="modal"  id="cancel_reject" href="#modal-cancel" class="btn btn-danger show-tooltip" title="Xem xét lại">Xem lại</a>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="modal-cancel" style="display: none;">
<form action="/rejects/edit" method="post">
<div class="modal-header">
    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
    <h3 id="myModalLabel">Nêu lý do xin xem xét lại</h3>
</div>
<div class="modal-body"><textarea name="data[Reject][Note]" class=" span12 ckeditor" id="RejectNote">

 Gửi  Admin, traffic, teamlearders, văn phòng <br>    
Sau khi xem lại thực tế lý do bị reject và các lý do khách quan khác, Tôi '.$user_info['User']['username'].' đề nghị ban quản trị xem xét lại cho tôi nội dung reject với thông tin sau:<br><br>
File: '.$info_pro['Product']['name_file_product'].'<br>
Thuộc đơn hàng: '.$info_project['Project']['Name'].'<br>
  được reject vào '.date("H:i:s d/m/Y").' <br>

 Nghiệp vụ xử lý : làm hàng, check hàng, khởi tạo, v.v. <br>
 
Với Lý do khiếu nại:  <br><br><br><br>
 	
File đính kèm (nếu có) <br><br><br>

Mức độ reject đề xuất:  <br><br>

Xin cảm ơn<br>

</textarea>
    <input type="hidden" name="data[Reject][id]" id="RejectId" value="'.$this->Reject->getInsertID().'">
    </div>
<div class="modal-footer">
    <button aria-hidden="true" data-dismiss="modal" class="btn">Hủy</button>
    <input type="submit" value="Gửi" class="btn btn-primary">
</div>
</form>
</div>', $content_email);
                    $dt_user_add['Notification']['content'] = $content_email;
                    $dt_user_add['Notification']['title'] = 'Tiêu đề: Thông báo nhắc nhở/reject cấp <b>'.$rate_point['RatePoint']['name'].'</b> đối với đơn hàng <b>' . $info_project['Project']['Name'] . '</b>';
                    $dt_user_add['Notification']['touser_id'] = $item;
                    $dt_user_add['Notification']['createdate'] = date('Y-m-d H:i:s');
                    $dt_user_add['Notification']['user_id'] = 0;
                    $this->Notification->save($dt_user_add);
                }
            }
            ///////////////////xử lý phần gửi thông báo cho nhân viên thuộc nhóm làm việc///////////////////////////
            $email_3 = $this->Email->findById('8');
//            $work_group_id = $this->request->data['working_group_id'];
//            $work_group = $this->WorkingGroup->findById($work_group_id);
//            $users_group = $work_group['WorkingGroup']['user_ids'];
//            $user_group_mess = explode(',',$users_group);
            $project = $this->Project->findById($this->request->data['Reject']['project_id']);
            if($project['Project']['CustomerGroup_id']!=''&&$project['Project']['CustomerGroup_id']!=null){
                $user_group_mess = $this->User->find('all',array(
                    'fields' => array('User.id'),
                    'conditions'=>array('User.customer_group_id LIKE'=> '%cusgr_'.$project['Project']['CustomerGroup_id'].',%')));
            }else{
                $user_group_mess = $this->User->find('all',array(
                    'fields' => array('User.id'),
                    'conditions'=>array('User.customer_group_id LIKE'=> '%cus_'.$project['Project']['Customer_id'].',%')));
            }
//            debug($user_reject);
//            debug($user_mess);
//            debug($user_group_mess);
            foreach($user_group_mess as $us_gm){
                if($us_gm['User']['id'] != ''){
//                    if(in_array($us_gm['User']['id'],$user_reject) || in_array($us_gm['User']['id'],$user_mess)){
//                    }else{
                        $this->Notification->create();
                        $user_info = $this->User->find('first', array(
                            'fields' => array('User.username', 'User.id'),
                            'conditions' => array('User.id' => $us_gm['User']['id'])
                        ));
                        $info_project = $this->Project->find('first', array(
                            'fields' => array('Project.Name', 'Project.ID'),
                            'conditions' => array('Project.ID' => $this->request->data['Reject']['project_id'])
                        ));
                        $content_email = str_replace('#NAME_USER_1#', $user_info['User']['username'], $email_3['Email']['content']);
                        $content_email = str_replace('#NAME_USER#', $all_name_user, $content_email);
                        $content_email = str_replace('#NAME_PROJECT#', $info_project['Project']['Name'], $content_email);
                        if ($this->request->data['Reject']['product_id'] != 0) {
                            $info_pro = $this->Product->find('first', array(
                                'fields' => array('Product.name_file_product', 'Product.id'),
                                'conditions' => array('Product.id' => $this->request->data['Reject']['product_id'])
                            ));
                            $content_email = str_replace('#NAME_FILE#', $info_pro['Product']['name_file_product'], $content_email);
                        } else {
                            $content_email = str_replace('#NAME_FILE#', '', $content_email);

                        }
                        $content_email = str_replace('#FILE#','<a href="'.$this->domain.'Reject/'.$this->request->data['Reject']['file'].'" download>'.$this->request->data['Reject']['file'].'</a>', $content_email);
                        $content_email = str_replace('#NOTE#', $this->request->data['Reject']['desc'], $content_email);
                        $content_email = str_replace('#PERCENT#', $all_percent, $content_email);
                        $dt_user_add['Notification']['content'] = $content_email;
                        $dt_user_add['Notification']['title'] = 'Tiêu đề: Thông báo nhắc nhở/reject cấp <b>'.$rate_point['RatePoint']['name'].'</b> đối với đơn hàng <b>' . $info_project['Project']['Name'] . '</b>';
                        $dt_user_add['Notification']['touser_id'] = $us_gm['User']['id'];
                        $dt_user_add['Notification']['createdate'] = date('Y-m-d H:i:s');
                        $dt_user_add['Notification']['user_id'] = 0;
                        $this->Notification->save($dt_user_add);
//                    }
                }
            }


            $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Đã reject sản phẩm.</div>'));
            if (!isset($this->request->data['Reject']['product_id'])) {
                return $this->redirect(Router::url(array('controller' => 'Projects', 'action' => 'index'), true));
            } else {
                if ($this->request->data['Reject']['link']!="" && $this->request->data['Reject']['link']!=null) {
                    return $this->redirect($this->request->data['Reject']['link']);
                } else {
                    return $this->redirect(array('controller' => 'projects', 'action' => 'detail', $this->request->data['Reject']['project_id']));
                }
            }
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong>Không thể lưu.</div>'));

        }
    }

    public function accept($reject_id = null)
    {
        $time_now = date("Y-m-d H:i:s");
        $reject = $this->Reject->findById($reject_id);
        $time = strtotime($time_now) - strtotime($reject['Reject']['datetime']);
        if(($time/60) < get($this->CF, 'ACCEPT_REJECT')){
            $reject['Reject']['confirm'] = '1';
            $this->Reject->save($reject);
            $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Bạn đã đồng ý bị reject</div>'));
        }
        else{
            $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Thời gian trả lời của đã quá '.get($this->CF,'ACCEPT_REJECT').' phút.</div>'));
        }
        return $this->redirect(Router::url(array('controller' => 'notifications', 'action' => 'index'), true));
    }
    public function edit(){
        if ($this->request->is(array('post', 'put'))) {
//            debug($this->request->data);die;
            $this->Reject->id = $this->request->data['Reject']['id'];
            if ($this->Reject->save($this->request->data)) {
                $reps = $this->User->find('all',array(
                    'fields'=>'User.id',
                    'conditions' => array(
                        'User.group_id' => array('1','2','3','5','6')
                    )
                    ));
//                debug($reps);die;
                foreach($reps as $rep){
                    if($rep['User']['id'] != ''){
                        $dt_user_add = array();
                        $this->Notification->create();
                        $dt_user_add['Notification']['content'] = $this->request->data['Reject']['Note'];
                        $dt_user_add['Notification']['title'] = 'Tiêu đề: Đề nghị xem xét lại';
                        $dt_user_add['Notification']['touser_id'] = $rep['User']['id'];
                        $dt_user_add['Notification']['createdate'] = date('Y-m-d H:i:s');
                        $dt_user_add['Notification']['user_id'] = 0;
//                        debug($dt_user_add);
                        $this->Notification->save($dt_user_add);
                    }
                }
//                die;
                $this->Session->setFlash(__('<div id="alert" class="alert alert-success"><button data-dismiss="alert" class="close">×</button><strong>Thành công!</strong> Đã gửi yêu cầu của bạn.</div>'));
            }else{
                $this->Session->setFlash(__('<div id="alert" class="alert alert-error"><button data-dismiss="alert" class="close">×</button><strong>Thất bại!</strong> Không thể gửi yêu cầu.</div>'));
            }
            return $this->redirect(Router::url(array('controller' => 'notifications', 'action' => 'index'), true));
        }
    }
}