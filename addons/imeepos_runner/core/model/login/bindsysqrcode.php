<?php

global $_W,$_GPC;
// 扫码绑定
$input = $this->__input['encrypted'];
$rcode = trim($_GPC['r']);

if(!pdo_tableexists('imeepos_runner4_member_site')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_member_site')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) DEFAULT '0',
        `acid` int(11) DEFAULT '0',
        `siteroot` varchar(320) DEFAULT '',
        `openid` varchar(64) DEFAULT '',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
      pdo_query($sql);
}

if(empty($rcode)){
    $rcode = random(64);
}

load()->model('mc');
$user = mc_oauth_userinfo();
$uid = mc_openid2uid($user['openid']);

$site = pdo_get('imeepos_runner4_member_site',array('openid'=>$user['openid']));
$info = cache_read($rcode);

$data = array();
$data['openid'] = $user['openid'];
$data['uniacid'] = $info['uniacid'];
$data['acid'] = $_W['acid'];
$data['siteroot'] = $_W['siteroot'];
$data['type'] = 'admin';

if(!empty($user['openid'])){
    if(empty($site)){
        pdo_insert('imeepos_runner4_member_site', $data);
        $data['id'] = pdo_insertid();
    }else{
        pdo_update('imeepos_runner4_member_site', $data, array('id'=>$site['id']));
        $data['id'] = $site['id'];
    }
    $this->info = '绑定成功';
}else{
    $this->info = 'openid 为空!';
}

itoast($this->info,"./app/index.php?i={$_W['uniacid']}&c=entry&do=tasks&m=imeepos_runner",'success');
return $this;