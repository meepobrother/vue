<?php
global $_W;
$input = $this->__input['encrypted'];
$table = "imeepos_runner4_goods_group";

$data = array();
$data['title'] = $input['title'];
$data['desc'] = $input['desc'];
$data['uniacid'] = $_W['uniacid'];
$data['fid'] = intval($input['fid']);

if(!empty($input['title'])){
    if(empty($input['id'])){
        pdo_insert($table,$data);
        $data['id'] = pdo_insertid();
    }else{
        pdo_update($table,$data,array('id'=>$input['id']));
        $data['id'] = $input['id'];
    }
}

$this->info = $data;
$this->msg = $input;
return $this;