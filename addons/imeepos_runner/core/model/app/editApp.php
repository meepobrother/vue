<?php
global $_W;
$input = $this->__input['encrypted'];
$table = "imeepos_runner4_app";

$data = array();
$data['token'] = $input['token'];
$data['title'] = $input['title'];
$data['author'] = $input['author'];
$data['uniacid'] = $_W['uniacid'];
$data['price'] = $input['price'];
$data['rights'] = serialize($input['rights']);

$id = intval($input['id']);
if(!empty($data['title'])){
    if($id){
        pdo_update($table,$data,array('id'=>$id));
        $data['id'] = $id;
    }else{
        pdo_insert($table,$data);
        $data['id'] = pdo_insertid();
    }
}
$data['rights'] = unserialize($data['rights']);
$this->info = $data;
return $this;

