<?php

global $_W;
$input = $this->__input['encrypted'];

$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['title'] = $input['title'];
$data['tags'] = !empty($input['tags']) ? $input['tags'] : array();
$data['tags'] = serialize($data['tags']);
$data['fid'] = intval($input['fid']);

$id = intval($input['id']);

if(!empty($data['title'])){
    if(!empty($id)){
        pdo_update('imeepos_runner4_member_group',$data,array('id'=>$id));
        $data['tags'] = unserialize($data['tags']);
        $data['id'] = $id;
    }else{
        pdo_insert('imeepos_runner4_member_group',$data);
        $data['id'] = pdo_insertid();
        $data['tags'] = unserialize($data['tags']);
    }
}

$this->info = $data;
$this->msg = $input;
return $this;