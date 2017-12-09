<?php
global $_W;
$input = $this->__input['encrypted'];

if(empty($_W['openid'])){
	$this->code = 0;
	$this->msg = '请在微信端打开';
	return $this;
}
$action = isset($input['action']) ? $input['action'] : '';

if($action == 'shop.info'){

	$id = $input['id'];
	if(empty($id)){
		$this->code = 0;
		$this->msg = '参数错误';
		return $this;
	}
	$sql = "SELECT nickname,avatar,xinyu,status,forbid,forbid_time,isrunner,realname,mobile,openid FROM ".tablename('imeepos_runner3_member')." WHERE id=:id AND uniacid=:uniacid";
	$params = array(':id'=>$id,':uniacid'=>$_W['uniacid']);
	$member = pdo_fetch($sql,$params);

	$services = pdo_getall('imeepos_runner3_services',array('openid'=>$member['openid']));
	$member['services'] = $services;
	$this->info = $member;
	$this->code = $_W['openid'];
	return $this;
}

if($action == 'getInfo'){
	$openid = $input['openid'];
	$sql = "SELECT nickname,avatar,xinyu,status,forbid,forbid_time,isrunner,realname,mobile,openid FROM ".tablename('imeepos_runner3_member')." WHERE openid=:openid AND uniacid=:uniacid";
	$params = array(':openid'=>$openid,':uniacid'=>$_W['uniacid']);
	$member = pdo_fetch($sql,$params);

	$myinfo = pdo_get('imeepos_runner3_member',array('openid'=>$_W['openid'],'uniacid'=>$_W['uniacid']));
	$this->info = array('toinfo'=>$member,'myinfo'=>$myinfo);
	$this->msg = $input;
	return $this;
}

$sql = "SELECT nickname,avatar,xinyu,status,forbid,forbid_time,isrunner,realname,mobile,openid,isadmin,ismanager FROM ".tablename('imeepos_runner3_member')." WHERE openid=:openid AND uniacid=:uniacid";
$params = array(':openid'=>$_W['openid'],':uniacid'=>$_W['uniacid']);
$member = pdo_fetch($sql,$params);

if(empty($member)){
	$this->code = 0;
	$this->msg = '用户不存在或已删除';
	$this->info = $input;
	return $this;
}
$member['level'] = 'one';
$member['tag'] = '普通粉丝';

$member['nickname'] = cutstr($member['nickname'], '8', true);

if(empty($member['mobile'])){
	$member['level'] = 'two';
	$member['tag'] = 'vip会员';
}

if($member['isrunner'] == 1){
	$member['level'] = 'three';
	$member['tag'] = '服务人员';
}

if($member['isadmin']== 1){
	$member['tag'] = '站长';
}

if($member['ismanager']){
	$member['tag'] = '管理员';
}

//接单总数
$sql = "SELECT COUNT(*) FROM ".tablename('imeepos_runner3_recive')." WHERE openid=:openid AND uniacid=:uniacid";
$params = array(':openid'=>$_W['openid'],':uniacid'=>$_W['uniacid']);
$total = pdo_fetchcolumn($sql,$params);
$member['total'] = $total ? $total : 0;	

//积分和余额
load()->model('mc');
$uid = mc_openid2uid($_W['openid']);
$user = mc_credit_fetch($uid,array('credit1','credit2'));
$member['credit1'] = $user['credit1'] ? $user['credit1'] : '0';
$member['credit2'] = $user['credit2'] ? $user['credit2'] : '0';



$this->info = $member;
return $this;