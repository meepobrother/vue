<?php

global $_W,$_GPC;
$input = $this->__input['encrypted'];

if(empty($_W['openid'])){
	$this->code = 0;
	$this->msg = '权限错误';
	return $this;
}

if(!pdo_fieldexists('imeepos_runner3_services','images')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner3_services')." ADD COLUMN `images` varchar(320) DEFAULT ''");
}

$action = $input['action'];
if($action == 'delete'){
	$id = $input['id'];
	if(empty($id)){
		$this->code = 0;
		$this->msg = '参数错误';
		return $this;
	}
	pdo_delete('imeepos_runner3_services',array('id'=>$id));
	$this->msg = '操作成功';
	return $this;
}

if(empty($input['id'])){
	$data = array();
	$data['title'] = $input['title'];
	$data['price'] = $input['price'];
	$data['images']= $input['images'];
	$data['create_time'] = time();
	$data['uniacid'] = $_W['uniacid'];
	$data['openid'] = $_W['openid'];

	pdo_insert('imeepos_runner3_services',$data);
} else{
	$data = array();
	$data['title'] = $input['title'];
	$data['price'] = $input['price'];
	$data['images']= $input['images'];
	$data['create_time'] = time();
	$data['uniacid'] = $_W['uniacid'];
	$data['openid'] = $_W['openid'];
	pdo_update('imeepos_runner3_services',$data,array('id'=>$input['id']));
}



$this->info = $input;
$this->msg = '操作成功';	

return $this;