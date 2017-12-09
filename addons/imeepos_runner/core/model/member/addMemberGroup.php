<?php
global $_W;
$input = $this->__input['encrypted'];

$group = array();
$group['title'] = trim($input['title']);
$group['desc'] = trim($input['desc']);
$group['status'] = intval($input['status']);
$group['uniacid'] = intval($_W['uniacid']);

if(!empty($group['title'])){
    if(!empty($input['id'])){
        pdo_update('imeepos_runner4_member_group',$group,array('id'=>$input['id']));
        $group['id'] = $input['id'];
    }else{
        pdo_insert('imeepos_runner4_member_group',$group);
        $group['id'] = pdo_insertid();
    }
}

$this->info = $group;
return $this;