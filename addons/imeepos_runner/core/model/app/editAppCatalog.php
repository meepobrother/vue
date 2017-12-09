<?php
global $_W;
$input = $this->__input['encrypted'];
$table = "imeepos_runner4_app_catalog";

$data = array();
$data['title'] = $input['title'];
$data['app_id'] = $input['app_id'];

$id = intval($input['id']);
if($id){
    pdo_update($table,$data,array('id'=>$id));
    $data['id'] = $id;
}else{
    pdo_insert($table,$data);
    $data['id'] = pdo_insertid();
}

$this->info = $input;
return $this;

