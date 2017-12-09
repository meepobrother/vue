<?php
global $_W;
$input = $this->__input['encrypted'];
$table = "imeepos_runner4_app_widgets";

$data = array();
$data['type'] = $input['type'];
$data['name'] = $input['name'];
$data['tpl'] = trim($input['tpl']);
$data['group_id'] = intval($input['group_id']);


$id = intval($input['id']);
if(!empty($data['type'])){
    if($id){
        pdo_update($table,$data,array('id'=>$id));
    }else{
        pdo_insert($table,$data);
        $data['id'] = pdo_insertid();
    }
}

$this->info = $data;
return $this;
