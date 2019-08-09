<?php
App::uses('AppController', 'Controller');
App::import("Vendor", "FTPUploader");

class ProductsController extends AppController
{

    public $uses = array('DoneProduct','Product', 'AclManagement.User', 'Timelogin','Customer', 'Com', 'User', 'Group', 'Customergroup', 'Country', 'ProductCom', 'Productaction', 'Action', 'Product', 'Status', 'Processtype', 'Producttype');

    public $current_user;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->current_user = $this->Auth->user();
    }

    public function getProductAction($product_id = null,$deliver_id = null,$action_id=null){
        $output = array();
        $proactions = $this->Productaction->find('first',array('conditions'=>array('Productaction.Product_id'=>$product_id,'Productaction.action_id'=>$action_id)));
        $this->RequestHandler->respondAs('json');
        $this->autoRender = false;
        return json_encode($output);
    }

    public function updateProduct($id){
        $ftp = new FTPUploader($this->host,$this->user,$this->pass);
        if(isset($this->request->data['Product']['processinfo'])){
            $ck = $this->Product->find('first',array('conditions'=>array('Product.id'=>$id),'contains'=>false));
            if(!empty($ck)){
                $data['Product']['id'] = $id;
                $data['Product']['note_product'] = $this->request->data['Product']['processinfo'];
                if(isset($this->request->data['Product']['file']['name'])&&($this->request->data['Product']['file']['name'])!=''){
                    $data['Product']['note_file'] = $this->request->data['Product']['file']['name'];
                    $ftp->upload($this->request->data['Product']['file']['tmp_name'],str_replace('@', '/',$this->dir.$ck['Project']['UrlFolder'].'@SUB@'.$this->request->data['Product']['file']['name']));
                }
                if($this->Product->save($data)){
                    $output['resultCode'] = 1;
                    $output['resultMsg'] = "Cập nhật thành công";
                }else{
                    $output['resultCode'] = 0;
                    $output['resultMsg'] = "Cập nhật thất bại";
                }
            }else{
                $output['resultCode'] = 0;
                $output['resultMsg'] = "Cập nhật thất bại";
            }
        }else{
            $output['resultCode'] = 0;
            $output['resultMsg'] = "Cập nhật thất bại";
        }
        $this->RequestHandler->respondAs('json');
        $this->autoRender = false;
        return json_encode($output);
    }
    public function get_done_product($product_id = null){
        $done_pd = $this->DoneProduct->find('first',array(
            'conditions' => array('DoneProduct.product_id' => $product_id)
        ));
        return $done_pd;
    }
}
