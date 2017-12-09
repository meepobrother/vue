<?php
$input = $this->__input['encrypted'];
$carfiles = pdo_get('imeepos_repair_server_carfiles',array('car_num'=>$input['car_num']));

if(empty($carfiles)){
	$this->code = 0;
	$this->msg = '汽车资料不存在';
	return $this;
}

$this->info = $carfiles;

return $this;