<?php
global $_W;
$input = $this->__input['encrypted'];
$table = "imeepos_runner4_app_widgets_group";

$data = array();
$data['title'] = $input['title'];
$data['code'] = $input['code'];

$id = intval($input['id']);

if(!empty($data['title'])){
    if($id){
        pdo_update($table,$data,array('id'=>$id));
    }else{
        pdo_insert($table,$data);
        $data['id'] = pdo_insertid();
    }
}

$this->info = $data;
return $this;
