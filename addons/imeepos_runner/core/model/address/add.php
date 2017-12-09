<?php

global $_W;
$input = $this->__input['encrypted'];

$data = $input;
$data['uniacid'] = $_W['uniacid'];
$data['create_at'] = time();
$data['openid'] = !empty($data['openid']) ? $data['openid'] : $_W['openid'];

pdo_insert('imeepos_runner3_address',$data);
$data['id'] = pdo_insertid();

$this->info = $data;
return $this;