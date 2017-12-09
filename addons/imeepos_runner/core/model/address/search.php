<?php

global $_W;
$input = $this->__input['encrypted'];
$openid = $_W['openid'];
$openid = empty($openid) ? 'fromUser' : $openid;

$start = $input['start'];
$len = $input['len'];
$op = $input['op'];


if(!empty($openid)){
    $sql = "SELECT * FROM ".tablename('imeepos_runner3_address')." WHERE uniacid =:uniacid AND openid =:openid ORDER BY create_at DESC limit {$start},{$len}";
    $params = array(':uniacid'=>$_W['uniacid'],':openid'=>$openid);
    $list = pdo_fetchall($sql,$params);
    $this->info = $list;
}
else{
    $sql = "SELECT * FROM ".tablename('imeepos_runner3_address')." WHERE uniacid =:uniacid ORDER BY create_at DESC limit {$start},{$len}";
    $params = array(':uniacid'=>$_W['uniacid']);
    $list = pdo_fetchall($sql,$params);
    $this->info = $list;
}

$this->msg = $input;


return $this;