<?php
global $_W,$_GPC;
// 扫码登陆
$input = $this->__input['encrypted'];
$rcode = trim($_GPC['r']);

if(empty($rcode)){
    $rcode = random(64);
}

load()->model('mc');
$user = mc_oauth_userinfo();
$uid = mc_openid2uid($user['openid']);

$date = array();

// 查找 siteroot acid uniacid
$site = pdo_get('imeepos_runner4_member_site',array('openid'=>$date['openid']));
if(!empty($site)){
    $date['siteroot'] = $site['siteroot'];
    $date['acid'] = $site['uniacid'];
    $date['uniacid'] = $site['uniacid'];
}else{

}

$date['uid'] = $uid;
$date['openid'] = $user['openid'];
$date['rcode'] = $rcode;
$date['info'] = $user;

load()->func('cache');
cache_write($rcode, $date);

$this->info = $date;

itoast('扫码成功',"./app/index.php?i={$_W['uniacid']}&c=entry&do=tasks&m=imeepos_runner",'success');
return $this;