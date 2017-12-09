<?php

global $_W;
$input = $this->__input;

$data = $input['encrypted'];
$id = intval($data['id']);

if(!empty($id)){
    unset($data['id']);
    pdo_update('imeepos_runner3_address',$data,array('id'=>$id));
}

$this->info = $data;

return $this;