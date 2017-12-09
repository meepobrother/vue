<?php

global $_W;
$input = $this->__input['encrypted'];

$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['title'] = $input['title'];

if(!empty($input['title'])){
    if(!empty($input['id'])){
        pdo_update('imeepos_runner4_order_tag',$data,array('id'=>$input['id']));
        $data['input'] = $input['id'];
    }else{
        pdo_insert('imeepos_runner4_order_tag',$data);
        $data['id'] = pdo_insertid();
    }
}

$this->info = $data;
$this->msg = $input;
return $this;