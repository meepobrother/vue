<?php
global $_W;
// ini_set("display_errors", "On");
// 	error_reporting(E_ALL | E_STRICT);
$input = $this->__input['encrypted'];

//插入记录 朝赵重复
$carfiles = pdo_get('imeepos_repair_server_carfiles',array('car_num'=>$input['car_num']));


if(empty($carfiles)){
	if(empty($input['car_num'])){
		$this->code = 0;
        $this->msg = '请输入车牌号码';
        $this->info = $input;
		return $this;
	}
	$data = array();
	$data['uniacid'] = $_W['uniacid'];
	$data['car_num'] = $input['car_num'];
	$data['jar_num'] = $input['jar_num'];
	$data['create_time'] = time();
	$data['realname'] = $input['realname'];
	$data['mobile'] = $input['mobile'];
	$data['father'] = $_W['openid'];
	$data['licheng'] = $input['licheng'];

	pdo_insert('imeepos_repair_server_carfiles',$data);

	$info = array();
	$info['id'] = pdo_insertid();

	if(empty($carfiles['openid'])){
		$info['needBind'] = true;
	}else{
		$info['needBind'] = false;
	}

	$this->code = 1;
	$this->info = $info;
	$this->msg = '操作成功,请用户扫码绑定微信!';
	return $this;
}else{
	$info = array();
	$info['id'] = $carfiles['id'];

	if(empty($carfiles['openid'])){
		$info['needBind'] = true;
	}else{
		$info['needBind'] = false;
	}
	$this->code = 1;
	$this->msg = $info['needBind'] ? '请用户扫码绑定微信' : '此车辆已经录入';
	$this->info = $info;
	return $this;
}

$this->info = $input;
return $this;