<?php
global $_W;
$input = $this->__input['encrypted'];
$table = "imeepos_runner4_goods_tags";

$data = array();
$data['title'] = $input['title'];
$data['uniacid'] = $_W['uniacid'];

if(!empty($data['title'])){
    if(empty($input['id'])){
        pdo_insert($table,$data);
        $data['id'] = pdo_insertid();
    }else{
        pdo_update($table,$data,array('id'=>$input['id']));
        $data['id'] = $input['id'];
    }
}

$this->info = $data;
return $this;