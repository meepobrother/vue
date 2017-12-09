<?php

global $_W;
$input = $this->__input['encrypted'];

$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['title'] = $input['title'];
$data['desc'] = $input['desc'];
$data['status'] = intval($input['status']);

if(!empty($data['title'])){
    if(!empty($input['id'])){
        pdo_update('imeepos_runner4_order_class',$data,array('id'=>$input['id']));
        $data['id'] = $input['id'];
    }else{
        pdo_insert('imeepos_runner4_order_class',$data);
        $data['id'] = pdo_insertid();
    }
}

$this->info = $data;
$this->msg = $input;
return $this;