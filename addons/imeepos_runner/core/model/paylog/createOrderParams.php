<?php
global $_W,$_GPC;
$input = $this->__input['encrypted'];

// ini_set("display_errors", "On");
// error_reporting(E_ALL | E_STRICT);

load()->classs('pay');
load()->classs('weixin.pay');


$id = intval($_GPC['id']);
if(empty($id)){
	$id = intval($input['id']);
}
if(empty($id)){
	$this->info = array();
	$this->msg = '参数错误';
	return $this;
}

$order = pdo_get('imeepos_runner4_order',array('id'=>$id));

$fee = 0;
$order['checks'] = unserialize($order['checks']);
$order['services'] = unserialize($order['services']);
$order['emplyers'] = unserialize($order['emplyers']);
$order['goods'] = unserialize($order['goods']);

if(empty($order['checks'])){
	$order['checks'] = array();
}
if(empty($order['services'])){
	$order['services'] = array();
}
if(empty($order['emplyers'])){
	$order['emplyers'] = array();
}
if(empty($order['goods'])){
	$order['goods'] = array();
}

foreach($order['services'] as $service){
	$fee += floatval($service['fee']);
}

foreach($order['goods'] as $good){
	$fee += floatval($good['fee']);
}

if(pdo_update('imeepos_runner4_order',array('fee'=>$fee),array('id'=>$id))){
	// 计算费用
}

$params = array();
$params['openid'] = $_W['openid'];
$params['out_trade_no'] = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);;
$params['body'] = $order['shop_title'].'-'.$order['class_title'];
$params['total_fee'] = $fee * 100;
$params['trade_type'] = 'NATIVE';
$params['product_id'] = $order['id'];
$pay = Pay::create();

$unifiedorder = $pay->buildUnifiedOrder($params);
if(is_error($unifiedorder)){
	$this->code = 0;
	$this->msg = $unifiedorder['message'];
	$this->info = $fee;
	return $this;			
}

$this->info = $unifiedorder;
$this->msg = $fee;
return $this;
