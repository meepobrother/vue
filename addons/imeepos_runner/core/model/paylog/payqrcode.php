<?php
global $_W;
load()->classs('pay');
load()->classs('weixin.pay');
$input = $this->__input['encrypted'];

$id = intval($input['id']);
$task = pdo_get('imeepos_runner3_tasks',array('id'=>$id));
$detail = pdo_get('imeepos_runner3_detail',array('taskid'=>$id));
pdo_delete('imeepos_runner3_tasks_paylog',array('tasks_id'=>$id,'status'=>0));
$paylog = pdo_getall('imeepos_runner3_tasks_paylog',array('tasks_id'=>$id,'status'=>1));
$hasPay = 0;
foreach($paylog as $log){
	$hasPay += $log['fee'];
}
$fee = $task['total'] - $hasPay;


if($fee > 0 ){
	$pay = Pay::create('wechat');
	$item = array();
	$item['tid'] = $input['tid'];
	$item['fee'] = $fee;
	$item['status'] = 0;
	$item['uniacid'] = $_W['uniacid'];
	$item['type'] = 'wechat';
	$item['tasks_id'] = $id;
	if(empty($task['openid']) || $task['openid']=='fromUser'){
		$item['openid'] = $_W['openid'];
	}else{
		$item['openid'] = $task['openid'];
	}
	// $item['openid'] = !empty($task['openid']) ? $task['openid'] : $_W['openid'];
	$item['create_time'] = time();
	pdo_insert('imeepos_runner3_tasks_paylog',$item);
	
	$params = array();
	$params['openid'] = $item['openid'];
	$params['out_trade_no'] = trim($item['tid']);
	$params['body'] = $detail['goodsname'];
	$params['total_fee'] = $item['fee'] * 100;
	$params['trade_type'] = 'NATIVE';
	$params['product_id'] = $item['tid'];

	$unifiedorder = $pay->buildUnifiedOrder($params);
	// $paylog2NativeUrl = $pay->paylog2NativeUrl($params);
	if(is_error($unifiedorder)){
		$this->code = 0;
		$this->msg = $unifiedorder['message'];
		$this->info = $item;
		return $this;			
	}
	$this->info = $unifiedorder;
	return $this;
}else{
	$this->code = 0;
	$this->msg = '订单不需要支付';
	return $this;
}



return $this;