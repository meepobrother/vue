<?php
// 自动登陆
global $_W,$_GPC;
// 扫码登陆
$input = $this->__input['encrypted'];
$rcode = trim($_GPC['r']);
if(empty($rcoce)){
    $rcode = $input['r'];
}
if(empty($rcode)){
    $rcode = random(64);
}

$user = cache_read($rcode);
if(!empty($user)){
	$site = pdo_get('imeepos_runner4_member_site',array('openid'=>$user['openid']));
	$member = pdo_get('imeepos_runner3_member',array('openid'=>$user['openid']));

	$user['realname'] = $member['realname'];
	$user['mobile'] = $member['mobile'];
	$user['siteroot'] = $site['siteroot'];
	$user['uniacid'] = $site['uniacid'];
	$user['acid'] = $site['acid'];
	// uni_modules
	$modules = uni_modules_by_uniacid($user['uniacid'],true);
	$roles = array();
	foreach($modules as $key=>$module){
		$roles[] = $key;
	}
	if($site['type'] == 'admin'){
		$roles[] = 'imeepos_runner_admin';
	}
	$user['roles'] = $roles;
	$user['account'] = $_W['account'];
	$this->code = 1;
}else{
	$user = array();
	$this->code = 0;
}

$this->info = $user;
return $this;

