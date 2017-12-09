<?php

global $_W;
$input = $this->__input['encrypted'];

$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['title'] = $input['title'];
$data['tags'] = !empty($input['tags']) ? $input['tags'] : array();
$data['tags'] = serialize($data['tags']);

if($input['fid'] == $input['id']){
	$data['fid'] = 0;
	$data['fids'] = [];
}else{
	$data['fid'] = $input['fid'];
}

$data['fids'] = serialize($input['fids']);

$id = intval($input['id']);

if(!empty($data['title'])){
    if(!empty($id)){
        pdo_update('imeepos_oauth2_manage_group',$data,array('id'=>$id));
        $data['tags'] = unserialize($data['tags']);
        $data['id'] = $id;
    }else{
        pdo_insert('imeepos_oauth2_manage_group',$data);
        $data['id'] = pdo_insertid();
        $data['tags'] = unserialize($data['tags']);
    }
}

$this->info = $data;
$this->msg = $input;
return $this;