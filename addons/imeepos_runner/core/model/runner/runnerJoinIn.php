<?php
// 跑男加入
global $_W,$_GPC;
$input = $this->__input['encrypted'];
$openid = $input['openid'];
$openid = empty($openid) ? $_W['openid'] : $openid;
if(empty($openid)){
    $this->code = 0;
    $this->msg = '请在微信端打开!';
    return $this;
}
$runner = array();
$runner['mobile'] = $input['mobile'];

$code_id = intval($input['code_id']);
$code = pdo_get('imeepos_runner3_code',array('id'=>$code_id));
if(empty($code)){
    $this->code = 0;
    $this->msg = '验证码有误!';
    return $this;
}
if($code['code'] != $input['code']){
    $this->code = 0;
    $this->msg = '验证码有误!';
    return $this;
}else{
    // 更新跑腿表
    pdo_update('imeepos_runner3_member',$runner,array('openid'=>$_W['openid']));
    // 更新会员表
    $uid = mc_openid2uid($_W['openid']);
    if(!empty($uid)){
        pdo_update('mc_members',$runner,array('uid'=>$uid));
    }
}
// 验证码
$this->info = $input;
$this->msg = $input['success'];
return $this;