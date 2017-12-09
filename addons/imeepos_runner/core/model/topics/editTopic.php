<?php

global $_W;
$input = $this->__input['encrypted'];

$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['title'] = $input['title'];
$data['desc'] = $input['desc'];
$data['class_id'] = intval($input['class_id']);
$data['content'] = htmlspecialchars($input['content']);
$data['create_time'] = time();

$data['tags'] = !empty($input['tags']) ? $input['tags'] : array();
$data['tags'] = serialize($data['tags']);

$id = intval($input['id']);

if($input['title']){
    if(!empty($id)){
        pdo_update('imeepos_runner4_topics',$data,array('id'=>$id));
        $data['tags'] = unserialize($data['tags']);
    }else{
        pdo_insert('imeepos_runner4_topics',$data);
        $data['id'] = pdo_insertid();
        $data['tags'] = unserialize($data['tags']);
    }
}


$this->info = $data;
$this->msg = $input;
return $this;