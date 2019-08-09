<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiemtd
 * Date: 5/4/15
 * Time: 1:12 AM
 * To change this template use File | Settings | File Templates.
 */
class TaskManagersController extends AppController{
    public function add_row(){
        $this->layout = 'ajax';
        $task_manager = $this->TaskManager->find('all',array(
            'limit' => 5,
            'order' => array('TaskManager.id' => 'DESC')
        ));
        $this->set('task_manager',$task_manager);
    }

}