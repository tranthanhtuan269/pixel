<?php

$info = $this->viewVars['acos_details'][$data['Aco']['id']];
$return = "<span title=\"";
if (array_key_exists('description', $info)) {
    $return .= $info['description'];
}
$return .= "\">";
if (!$hasChildren && $depth >= 2) {
    $return .= "<a href=\"javascript:;;\" onclick=\"acos.edit('{$this->Html->url('/acl_management/user_permissions/edit')}','{$data['Aco']['id']}'); return false;\">{$info['name']}</a>";
    //$return .= "&nbsp;&nbsp;".$this->Html->image('/acl_management/img/icons/user.png', array('style'=>'display:none'));
} else {
    if (array_key_exists('name', $info)) {
        $return .= $info['name'];
    }
}
$return .= "</span>";

echo $return;

