<?php

global $_W;
$input = $this->__input['encrypted'];

$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['title'] = $input['title'];
$data['desc'] = $input['desc'];
$data['create_time'] = time();

$data['content'] = htmlspecialchars($input['content']);
$data['group_id'] = intval($input['group_id']);

$id = intval($input['id']);

if(!empty($data['title'])){
    if(!empty($id)){
        pdo_update('imeepos_runner4_actives',$data,array('id'=>$id));
        $data['id'] = $id;
    }else{
        pdo_insert('imeepos_runner4_actives',$data);
        $data['id'] = pdo_insertid();
    }
}

$this->info = $data;
$this->msg = $input;
return $this;