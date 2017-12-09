<?php

global $_W;
$input = $this->__input['encrypted'];
// ini_set("display_errors", "On");
// 	error_reporting(E_ALL | E_STRICT);

$id = intval($input['id']);

$order = pdo_get('imeepos_runner4_order',array('id'=>$id));

$fee = $order['fee'];



if($order['is_finish'] == 0){
	$time = strtotime(date('y-m-d',time() - 24 * 60 * 60));

	pdo_begin();
	// 统计店铺
	$shop_id = $input['shop_id'];
	$data = pdo_get('imeepos_runner4_state_shop',array('shop_id'=>$shop_id,'create_time'=>$time,'uniacid'=>$_W['uniacid']));
	if(empty($data)){
		$item = array();
		$item['shop_id'] = $shop_id;
		$item['fee'] = $fee;
		$item['uniacid'] = $_W['uniacid'];
		$item['create_time'] = $time;
		pdo_insert('imeepos_runner4_state_shop',$item);
	}else{
		$data['fee'] = $data['fee'] + $fee;
		pdo_update('imeepos_runner4_state_shop',array('fee'=>$data['fee']),array('id'=>$data['id']));
	}
	// 分类统计
	$class_id = $input['class_id'];

	$data = pdo_get('imeepos_runner4_state_group',array('group_id'=>$class_id,'uniacid'=>$_W['uniacid'],'create_time'=>$time));
	if(empty($data)){
		$item = array();
		$item['group_id'] = $class_id;
		$item['fee'] = $fee;
		$item['uniacid'] = $_W['uniacid'];

		$item['create_time'] = $time;
		pdo_insert('imeepos_runner4_state_group',$item);
	}else{
		$data['fee'] = $data['fee'] + $fee;
		pdo_update('imeepos_runner4_state_group',array('fee'=>$data['fee']),array('id'=>$data['id']));
	}

	// 工时统计
	$emplyers = $input['emplyers'];

	foreach($emplyers as $emplyer){
		$data = pdo_get('imeepos_runner4_state_emplyer',array('openid'=>$emplyer['openid'],'create_time'=>$time,'uniacid'=>$_W['uniacid']));
		if(empty($data)){
			$item = array();
			$item['openid'] = $emplyer['openid'];
			$item['fee'] = $fee;
			$item['uniacid'] = $_W['uniacid'];

			$item['create_time'] = $time;
			pdo_insert('imeepos_runner4_state_emplyer',$item);
		}else{
			$data['fee'] = $data['fee'] + $fee;
			pdo_update('imeepos_runner4_state_emplyer',array('fee'=>$data['fee']),array('id'=>$data['id']));
		}
	}

	// 零部件统计
	$goods = $input['goods'];

	foreach($goods as $good){
		$data = pdo_get('imeepos_runner4_state_good',array('good_id'=>$good['id'],'uniacid'=>$_W['uniacid'],'create_time'=>$time));
		if(empty($data)){
			$item = array();
			$item['good_id'] = $good['id'];
			$item['uniacid'] = $_W['uniacid'];

			$item['num'] = 1;
			$item['create_time'] = $time;
			pdo_insert('imeepos_runner4_state_good',$item);
		}else{
			$data['num'] = $data['num'] + 1;
			pdo_update('imeepos_runner4_state_good',array('num'=>$data['num']),array('id'=>$data['id']));
		}
	}

	// 服务项目统计
	$services = $input['services'];
	 
	foreach($services as $service){
		// 今日
		$data = pdo_get('imeepos_runner4_state_service',array('service_id'=>$service['id'],'create_time'=>$time));
		if(empty($data)){
			$item = array();
			$item['service_id'] = $service['id'];
			$item['num'] = 1;
			$item['create_time'] = $time;
			pdo_insert('imeepos_runner4_state_service',$item);
		}else{
			$data['num'] = $data['num'] + 1;
			pdo_update('imeepos_runner4_state_service',array('num'=>$data['num']),array('id'=>$data['id']));
		}
	}

	if(pdo_update('imeepos_runner4_order',array('is_finish'=>1),array('id'=>$id))){
		pdo_commit();
		$this->code = 1;
		$this->msg = '完成任务成功';
		return $this;
	}else{
		pdo_rollback();
	}

}


$this->msg = '任务状态有误，请联系管理员处理！';
return $this;