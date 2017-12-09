<?php

global $_W;
$input = $this->__input['encrypted'];

$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['title'] = $input['title'];
$data['fid'] = intval($input['fid']);

$id = intval($input['id']);

if(!empty($data['title'])){
    if(!empty($id)){
        pdo_update('imeepos_runner4_tasks_group',$data,array('id'=>$id));
        $data['id'] = $id;
    }else{
        pdo_insert('imeepos_runner4_tasks_group',$data);
        $data['id'] = pdo_insertid();
    }
}

$this->info = $data;
$this->msg = $input;
return $this;