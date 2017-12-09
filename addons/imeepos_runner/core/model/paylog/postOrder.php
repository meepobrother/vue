<?php
global $_W;
// ini_set("display_errors", "On");
// error_reporting(E_ALL | E_STRICT);

load()->model('payment');
load()->model('setting');
$input = $this->__input['encrypted'];

$taskJson = serialize($input);
$taskJson = base64_encode($taskJson);

$_W['acid'] = !empty($_W['acid']) ? $_W['acid'] : $_W['uniacid'];

$payType = $input['payType'];

if($input['total'] <= 0){
	$input['total'] = 0.01;
}

$action = $input['action']; //支付目的
$action = !empty($action) ? $action : 'task';


if($action == 'runner.buy'){
	//认证跑腿
	if(!checkCode($input)){
		$this->code = 0;
		$this->msg = '验证码不正确';
		return $this;
	}
	$member = pdo_get('imeepos_runner3_member',array('openid'=>$_W['openid'],'uniacid'=>$_W['uniacid']));
	if($member['isrunner'] == 1 && $member['status'] == 1){
		$this->code = 0;
		$this->msg = '请不要重复提交认证';
		return $this;
	}
}

if($action == 'coach.teacher'){
	
}

if(empty($input['total'])){
	$this->code = 0;
	$this->msg = '金额有误';
	return $this;
}

$total = floatval($input['total']);
if($total <= 0){
	$this->code = 0;
	$this->msg = '金额有误';
	return $this;
}
$input['total'] = $total;


if($action == 'shoukuan'){
	if($core_paylog == 'divider'){
		$this->code = 0;
		$this->msg = '信誉充值不支持货到付款';
		return $this;
	}
}

//信誉重置
if($action == 'runner.xinyu'){
	if($core_paylog == 'divider'){
		$this->code = 0;
		$this->msg = '信誉充值不支持货到付款';
		return $this;
	}
}

//货到付款
if($payType == 'divider'){
	$this->info = array("taskJson"=>$taskJson);
	return $this;
}

//余额支付
if($payType == 'credit'){
	load()->model('mc');
	$uid = mc_openid2uid($_W['openid']);
	if(empty($uid)){
		$this->code = 0;
		$this->msg = '会员不存在或已删除';
		return $this;
	}
	$member = mc_credit_fetch($uid,array('credit2'));
	$paylog = pdo_get('imeepos_runner3_tasks_paylog',array('tid'=>$input['tid']));
	if(!empty($paylog)){
		$this->code = 0;
		$this->msg = '订单号重复,请刷新';
		return $this;
	}
	$return = mc_credit_update($uid,'credit2','-'.$input['total'],array($uid,$input['goods']));
	if(is_error($return)){
		$this->code = 0;
		$this->msg = "对不起,您的余额不足";
		return $this;
	}
	//记录余额扣除
	//插入支付记录
	if(empty($paylog)){
		$p = array();
		$p['uniacid'] = $_W['uniacid'];
		$p['openid'] = $_W['openid'];
		$p['create_time'] = time();
		$p['type'] = 'credit';
		$p['status'] = 1;
		$p['tid'] = $input['tid'];
		$p['fee'] = floatval($input['total']);
		if(!pdo_fieldexists('imeepos_runner3_tasks_paylog','setting')){
			pdo_query("ALTER TABLE ".tablename('imeepos_runner3_tasks_paylog')." ADD COLUMN `setting` text DEFAULT ''");
		}
		$p['setting'] = $taskJson;
		$p['tasks_id'] = $input['taskid'];
		pdo_insert('imeepos_runner3_tasks_paylog',$p);
	}
	$this->info = array("taskJson"=>$taskJson);
	return $this;
}

// 微信支付
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
	'fee' => $input['total'],
	'user' => $_W['openid'],
	'title' => urldecode($input['goods']),
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
	$this->info = array("taskJson"=>$taskJson);
	$this->msg = $wOpt['message'];
	return $this;
}

$params = array();
$params['module'] = 'imeepos_runner';
$params['tid'] = $input['tid'];
$params['fee'] = floatval($input['total']);

$log = pdo_get('core_paylog', array('uniacid' => $_W['uniacid'], 'module' => $params['module'], 'tid' => $params['tid']));
if (empty($log)) {
	$log = array(
		'uniacid' => $_W['uniacid'],
		'acid' => $_W['acid'],
		'openid' => $_W['member']['uid'],
		'module' => $params['module'],
		'tid' => $params['tid'],
		'fee' => $params['fee'],
		'card_fee' => $params['fee'],
		'status' => '0',
		'is_usecard' => '0',
		'uniontid'=>$params['tid']
	);
	pdo_insert('core_paylog', $log);
}

$paylog = pdo_get('imeepos_runner3_tasks_paylog',array('tid'=>$input['tid']));
if(empty($paylog)){
	$p = array();
	$p['uniacid'] = $_W['uniacid'];
	$p['openid'] = $_W['openid'];
	$p['create_time'] = time();
	$p['type'] = 'wechat';
	$p['status'] = 0;
	$p['tid'] = $input['tid'];
	$p['fee'] = floatval($input['total']);
	$p['tasks_id'] = $input['taskid'];
	if(!pdo_fieldexists('imeepos_runner3_tasks_paylog','setting')){
		pdo_query("ALTER TABLE ".tablename('imeepos_runner3_tasks_paylog')." ADD COLUMN `setting` text DEFAULT ''");
	}
	$p['setting'] = $taskJson;
	pdo_insert('imeepos_runner3_tasks_paylog',$p);
}
$this->info = array("opt"=>$wOpt,"taskJson"=>$taskJson);

return $this;

function checkCode($task = array()){
	$sms = $task['sms'];
	$code = $sms['code'];
	$id = $sms['code_id'];
	$item = pdo_get('imeepos_runner3_code',array('id'=>$id));
	if($item['code'] == $code){
		return true;
	}else{
		return false;
	}
}



function wechat_proxy_build_meepo($params, $wechat) {
	global $_W;
	$uniacid = !empty($wechat['service']) ? $wechat['service'] : $wechat['borrow'];
	$oauth_account = uni_setting($uniacid, array('payment'));
	if (intval($wechat['switch']) == '2') {
		$_W['uniacid'] = $uniacid;
		$wechat['signkey'] = $oauth_account['payment']['wechat']['signkey'];
		$wechat['mchid'] = $oauth_account['payment']['wechat']['mchid'];
		unset($wechat['sub_mch_id']);
	} else {
		$wechat['signkey'] = $oauth_account['payment']['wechat_facilitator']['signkey'];
		$wechat['mchid'] = $oauth_account['payment']['wechat_facilitator']['mchid'];
	}
	$acid = pdo_getcolumn('uni_account', array('uniacid' => $uniacid), 'default_acid');
	$wechat['appid'] = pdo_getcolumn('account_wechats', array('acid' => $acid), 'key');
	$wechat['version'] = 2;
	return wechat_build_meepo($params, $wechat);
}

function wechat_build_meepo($params, $wechat) {
	global $_W;
	load()->func('communication');
	if (empty($wechat['version']) && !empty($wechat['signkey'])) {
		$wechat['version'] = 1;
	}
	$wOpt = array();
	if ($wechat['version'] == 1) {
		$wOpt['appId'] = $wechat['appid'];
		$wOpt['timeStamp'] = strval(TIMESTAMP);
		$wOpt['nonceStr'] = random(8);
		$package = array();
		$package['bank_type'] = 'WX';
		$package['body'] = $params['title'];
		$package['attach'] = $_W['uniacid'];
		$package['partner'] = $wechat['partner'];
		$package['out_trade_no'] = $params['uniontid'];
		$package['total_fee'] = $params['fee'] * 100;
		$package['fee_type'] = '1';
		$package['notify_url'] = $_W['siteroot'] . 'payment/wechat/notify.php';
		$package['spbill_create_ip'] = CLIENT_IP;
		$package['time_start'] = date('YmdHis', TIMESTAMP);
		$package['time_expire'] = date('YmdHis', TIMESTAMP + 600);
		$package['input_charset'] = 'UTF-8';
		if (!empty($wechat['sub_mch_id'])) {
			$package['sub_mch_id'] = $wechat['sub_mch_id'];
		}
		ksort($package);
		$string1 = '';
		foreach($package as $key => $v) {
			if (empty($v)) {
				continue;
			}
			$string1 .= "{$key}={$v}&";
		}
		$string1 .= "key={$wechat['key']}";
		$sign = strtoupper(md5($string1));

		$string2 = '';
		foreach($package as $key => $v) {
			$v = urlencode($v);
			$string2 .= "{$key}={$v}&";
		}
		$string2 .= "sign={$sign}";
		$wOpt['package'] = $string2;

		$string = '';
		$keys = array('appId', 'timeStamp', 'nonceStr', 'package', 'appKey');
		sort($keys);
		foreach($keys as $key) {
			$v = $wOpt[$key];
			if($key == 'appKey') {
				$v = $wechat['signkey'];
			}
			$key = strtolower($key);
			$string .= "{$key}={$v}&";
		}
		$string = rtrim($string, '&');
		$wOpt['signType'] = 'SHA1';
		$wOpt['paySign'] = sha1($string);
		return $wOpt;
	} else {
		$package = array();
		$package['appid'] = $wechat['appid'];
		$package['mch_id'] = $wechat['mchid'];
		$package['nonce_str'] = random(8);
		$package['body'] = cutstr($params['title'], 26);
		$package['attach'] = $_W['uniacid'];
		$package['out_trade_no'] = $params['uniontid'];
		$package['total_fee'] = $params['fee'] * 100;
		$package['spbill_create_ip'] = CLIENT_IP;
		$package['time_start'] = date('YmdHis', TIMESTAMP);
		$package['time_expire'] = date('YmdHis', TIMESTAMP + 600);
		$package['notify_url'] = $_W['siteroot'] . 'payment/wechat/notify.php';
		$package['trade_type'] = 'JSAPI';
		$package['openid'] = empty($wechat['openid']) ? $_W['fans']['from_user'] : $wechat['openid'];
		if (!empty($wechat['sub_mch_id'])) {
			$package['sub_mch_id'] = $wechat['sub_mch_id'];
		}
		ksort($package, SORT_STRING);
		$string1 = '';
		foreach($package as $key => $v) {
			if (empty($v)) {
				continue;
			}
			$string1 .= "{$key}={$v}&";
		}
		$string1 .= "key={$wechat['signkey']}";
		$package['sign'] = strtoupper(md5($string1));
		$dat = array2xml($package);
		$response = ihttp_request('https://api.mch.weixin.qq.com/pay/unifiedorder', $dat);
		if (is_error($response)) {
			return $response;
		}
		$xml = @isimplexml_load_string($response['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
		if (strval($xml->return_code) == 'FAIL') {
			return error(-1, strval($xml->return_msg));
		}
		if (strval($xml->result_code) == 'FAIL') {
			return error(-1, strval($xml->err_code).': '.strval($xml->err_code_des));
		}
		$prepayid = $xml->prepay_id;
		$wOpt['appId'] = $wechat['appid'];
		$wOpt['timeStamp'] = strval(TIMESTAMP);
		$wOpt['nonceStr'] = random(8);
		$wOpt['package'] = 'prepay_id='.$prepayid;
		$wOpt['signType'] = 'MD5';
		ksort($wOpt, SORT_STRING);
		foreach($wOpt as $key => $v) {
			$string .= "{$key}={$v}&";
		}
		$string .= "key={$wechat['signkey']}";
		$wOpt['paySign'] = strtoupper(md5($string));
		return $wOpt;
	}
}