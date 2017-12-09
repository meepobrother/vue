<?php
global $_W;
$input = $this->__input['encrypted'];
$table = "imeepos_runner4_skills";

$data = array();
$data['title'] = $input['title'];
$data['desc'] = $input['desc'];
// $data['setting'] = serialize($input['setting']);
$data['create_time'] = time();
$data['group_id'] = intval($input['group_id']);
$data['uniacid'] = $_W['uniacid'];
if(!empty($input['title'])){
    if(empty($input['id'])){
        pdo_insert($table,$data);
        $data['id'] = pdo_insertid();
        // $data['setting'] = unserialize($data['setting']);
    }else{
        pdo_update($table,$data,array('id'=>$input['id']));
        $data['id'] = $input['id'];
        // $data['setting'] = unserialize($data['setting']);
    }
}

$group = pdo_get('imeepos_runner4_skills_group',array('id'=>$data['group_id']));
$data['group_title'] = $group['title'];

$this->info = $data;
$this->msg = $input;
return $this;