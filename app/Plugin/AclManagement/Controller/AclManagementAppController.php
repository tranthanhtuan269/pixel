<?php

class AclManagementAppController extends AppController
{
    public function setMessage($type = null, $massage = null)
    {
        if ($type == 'success') {
           return  "<div id='alert' class='alert alert-success'><button data-dismiss='alert' class='close'>×</button><strong>Thành công!</strong> ". $massage.".</div>";
        }else {
            return  "<div id='alert' class='alert alert-error'><button data-dismiss='alert' class='close'>×</button><strong>Thất bại!</strong> ". $massage.".</div>";
        }
    }
}