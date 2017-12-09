<?php
global $_W;

$input = $this->__input['encrypted'];

$code = $input['code'];
if($code == 'getrole'){
	$openid = $_W['openid'];
	$member = pdo_get('imeepos_runner3_member',array('openid'=>$openid,'uniacid'=>$_W['uniacid']));
	$data = array();
	$data['isRunner'] = $member['isrunner'] == 1 ? true : false;
	$data['isAdmin'] = $member['isadmin'] == 1 ? true : false;
	$data['isManager'] = $member['ismanager'] == 1 ? true : false;
	$setting = M('setting')->getSystem('imeepso_runner');
	if(empty($setting)){
		$setting = M('setting')->check('imeepso_runner');
	}
	$data['oauth_code'] = $setting['code'];
	$this->info = $data;
	return $this;
}
$url = $_W['siteurl'];
$urls = explode('#', $url);
load()->model('account');
$account = uni_fetch();
$a = WeAccount::create($account);
// print_r($a);
$config = $a->getJssdkConfig($urls[0]);
$this->info = $config;
$this->msg = $urls;
$this->code = $url;
return $this;