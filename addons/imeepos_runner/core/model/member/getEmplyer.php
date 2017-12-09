<?php

global $_W;
$input = $this->__input['encrypted'];
$shop_id = $input['shop_id'];

$shop = pdo_get('imeepos_runner4_shops',array('id'=>$shop_id));

$employers = unserialize($shop['employers']);

if(empty($employers)){
	$employers = array();
}

$this->info = $employers;
$this->msg = $shop;
$this->code = $input;
return $this;