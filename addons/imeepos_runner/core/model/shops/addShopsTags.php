<?php

global $_W;
$input = $this->__input['encrypted'];

$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['title'] = $input['title'];


pdo_insert('imeepos_runner4_shops_tag',$data);
$data['id'] = pdo_insertid();

$this->info = $data;
$this->msg = $input;
return $this;