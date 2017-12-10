<?php
global $_W,$_GPC;
$_W['openid'] = $_W['openid'] ? $_W['openid'] : 'fromUser';
$skill = pdo_get('imeepos_runner4_member_skill', array('openid'=>$_W['openid']));
if (empty($skill)) {
    $url = $this->createMobileUrl('coach_add');
    header("Location:".$url);
    exit();
}
$url = $this->createMobileUrl('coach_detail', array('id'=>$skill['id']));
header("Location:".$url);
exit();
