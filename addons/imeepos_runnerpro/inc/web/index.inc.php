<?php
global $_W,$_GPC;
$code = '__meepo.app.uniacid';
$setting = pdo_get('imeepos_runner3_setting', array('code'=>$code));
$__uniacidItem = unserialize($setting['value']);
$uniacid = $__uniacidItem['uniacid'];

$rcode = random(32);
$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['acid'] = $_W['uniacid'];
cache_write($rcode, $data);

// 添加
if (!pdo_fieldexists('imeepos_runner4_member_site', 'type')) {
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_member_site')." ADD COLUMN `type` varchar(64) NOT NULL DEFAULT 'siter'";
    pdo_query($sql);
}

$list = pdo_getall('imeepos_runner4_member_site', array('uniacid'=>$_W['uniacid'],'type'=>"siter"));
foreach ($list as &$li) {
    $member = mc_fansinfo($li['openid'], $uniacid, $uniacid);
    $li['avatar'] = $member['avatar'];
    $li['nickname'] = $member['nickname'];
    $li['mobile'] = $member['mobile'];
    $li['realname'] = $member['realname'];
}
unset($li);

$adminers = pdo_getall('imeepos_runner4_member_site', array('uniacid'=>$_W['uniacid'],'type'=>"admin"));
foreach ($adminers as &$li) {
    $member = mc_fansinfo($li['openid'], $uniacid, $uniacid);
    $li['avatar'] = $member['avatar'];
    $li['nickname'] = $member['nickname'];
    $li['mobile'] = $member['mobile'];
    $li['realname'] = $member['realname'];
}
unset($li);

$accounts = pdo_getall('account_wechats');

if ($_GPC['act'] == 'save') {
    $value = serialize(array('uniacid'=>$_POST['uniacid']));
    if (empty($setting)) {
        pdo_insert('imeepos_runner3_setting', array('code'=>$code,'value'=>$value));
    } else {
        pdo_update('imeepos_runner3_setting', array('value'=>$value), array('code'=>$code));
    }
    die(json_encode($_POST));
}
include $this->template('index');
