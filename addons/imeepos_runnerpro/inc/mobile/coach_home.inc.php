<?php

global $_W,$_GPC;
$file = IA_ROOT."/addons/imeepos_runnerpro/inc/mobile/__init.php";
$_W['openid'] = $_W['openid'] ? $_W['openid'] : 'fromUser';

if (file_exists($file)) {
    require_once $file;
}
define('STATIC_PATH', MODULE_URL."template/mobile/coach/home/");
$act = isset($_GPC['act']) ? trim($_GPC['act']) : '';
$_W['openid'] = $_W['openid'] ? $_W['openid'] : 'fromUser';
// {"subscribe":1,
// "openid":"ojrmQt9r91gWieJeM3Zz7hAPIlaU",
// "nickname":"杨明明","sex":1,"language":"zh_CN","city":"浦东新区","province":"上海","country":"中国","headimgurl":"http://wx.qlogo.cn/mmopen/AAJwWvAlPkEkGGudbdZAuqSxV99HIg9drCCSguUOnPUzGjBb04fdibicc1j3n7yhqe63WRAD7kqBM4vTeicJ4dgwtTaFKubO1Jl/132","subscribe_time":1471469881,"remark":"","groupid":0,"tagid_list":[],"avatar":"http://wx.qlogo.cn/mmopen/AAJwWvAlPkEkGGudbdZAuqSxV99HIg9drCCSguUOnPUzGjBb04fdibicc1j3n7yhqe63WRAD7kqBM4vTeicJ4dgwtTaFKubO1Jl/132"}
$user = mc_fansinfo($_W['openid']);
$member = pdo_get('imeepos_runner3_member', array('openid'=>$_W['openid']));
$skill = pdo_get('imeepos_runner4_member_skill', array('openid'=>$_W['openid']));

$myinfo = array();
$myinfo['avatar'] = $user['avatar'];
$myinfo['nickname'] = $user['nickname'];
$myinfo['mobile'] = $member['mobile'];
$myinfo['tag'] = $skill['title'];
if($member['isadmin'] == 1){
    $desc = '站长';
}else if($member['ismanager'] == 1){
    $desc = '管理员';
}
else if($member['isrunner'] == 1){
    $desc = '跑腿员';
}
$myinfo['desc'] = $desc;
if ($_GPC['act'] === 'get_my_info') {
    ToJson($myinfo);
}
include $this->template('coach/home/index');
