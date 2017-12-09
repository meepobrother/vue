<?php
global $_W;
$input = $this->__input['encrypted'];
load()->model('payment');
load()->model('setting');

$id = intval($input['id']);
$task = pdo_get('imeepos_runner3_tasks',array('id'=>$id));
$detail = pdo_get('imeepos_runner3_detail',array('taskid'=>$id));
$paylog = pdo_getall('imeepos_runner3_tasks_paylog',array('tasks_id'=>$id,'status'=>1));
$hasPay = 0;
foreach($paylog as $log){
	$hasPay += $log['fee'];
}
$fee = $task['total'] - $hasPay;
pdo_delete('imeepos_runner3_tasks_paylog',array('tasks_id'=>$id,'status'=>0));

if($fee<=0){
	$this->code = 0;
	$this->msg = '订单不需要垫付';
	return $this;
}

//代付只能微信支付
$item = array();
$item['tid'] = $input['tid'];
$item['fee'] = $fee;
$item['status'] = 0;
$item['uniacid'] = $_W['uniacid'];
$item['type'] = 'wechat';
$item['tasks_id'] = $id;
$item['openid'] = $task['openid'];
$item['create_time'] = time();
pdo_insert('imeepos_runner3_tasks_paylog',$item);

//生成支付参数
$setting = uni_setting($_W['uniacid'], array('payment'));
if(!is_array($setting['payment'])) {
	$this->code = 0;
	$this->msg = '没有设定支付参数';
	return $this;
}
$wechat = $setting['payment']['wechat'];
$sql = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';
$row = pdo_fetch($sql, array(':acid' => $wechat['account']));
$wechat['appid'] = $row['key'];
$wechat['secret'] = $row['secret'];
$wechat['openid'] = $_W['openid'];

$params = array(
	'tid' => $input['tid'],
	'fee' => $item['fee'],
	'user' => $_W['openid'],
	'title' => urldecode($detail['goodsname']),
	'uniontid' => $input['tid']
);
if (intval($wechat['switch']) == 3 || intval($wechat['switch']) == 2) {
	$wOpt = wechat_proxy_build($params, $wechat);
} else {
	unset($wechat['sub_mch_id']);
	$wOpt = wechat_build($params, $wechat);
}

if (is_error($wOpt)) {
	if ($wOpt['message'] == 'invalid out_trade_no' || $wOpt['message'] == 'OUT_TRADE_NO_USED') {
		$id = date('YmdH');
		pdo_update('core_paylog', array('plid' => $id), array('plid' => $log['plid']));
		pdo_query("ALTER TABLE ".tablename('core_paylog')." auto_increment = ".($id+1).";");
		$this->code = 0;
		$this->info = array("opt"=>$wOpt,"taskJson"=>$taskJson);
		$this->msg = '抱歉，发起支付失败，系统已经修复此问题，请重新尝试支付。';
		return $this;
	}
	$this->code = 0;
	$this->info = array("opt"=>$wOpt,"taskJson"=>$taskJson);
	$this->msg = "抱歉，发起支付失败，具体原因为：“{$wOpt['errno']}:{$wOpt['message']}”。请及时联系站点管理员。";
	return $this;
}

$this->info = array("opt"=>$wOpt,"taskJson"=>$taskJson);

return $this;