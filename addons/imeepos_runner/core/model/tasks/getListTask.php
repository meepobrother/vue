<?php

global $_W;
$input = $this->__input['encrypted'];

$params = array();
$where = "";

if(isset($input['type']) && $input['type'] != 'all'){
    $type = intval($input['type']);
    $where .= " AND type={$type} ";
    $params[':type'] = $input['type'];
}

if(isset($input['status']) && $input['status'] != 'all'){
    $status = intval($input['status']);
    $where .=" AND status={$status} ";
}

if(isset($input['payType']) && $input['payType'] != 'all'){
    $payType = trim($input['payType']);
    $where .=" AND payType=:payType ";
    $params[':payType'] = $payType;
}

$sql = "SELECT * FROM ".tablename('imeepos_runner3_tasks')." WHERE uniacid={$_W['uniacid']} {$where} ORDER BY id DESC ";
$list = pdo_fetchall($sql,$params);

foreach($list as &$li){ 
    $member = mc_fetch($li['openid'],array('uid','avatar','nickname'));
    $li['member'] = $member;
    // 任务详情
    $detail = pdo_get('imeepos_runner3_detail',array('taskid'=>$li['id']));
    unset($detail['steps']);
    $li['detail'] = $detail;
    $li['create_time'] = date('m-d H:i',$li['create_time']);
    $li['tag'] = $detail['goodsname'];
    $li['avatar'] = $member['avatar'];
    $li['nickname'] = $member['nickname'];
    //recive
    $pai = pdo_get('imeepos_runner3_recive',array('taskid'=>$li['id']));
    $reciver = pdo_get('imeepos_runner3_member',array('uniacid'=>$_W['uniacid'],'openid'=>$pai['openid']));
    $li['reciver'] = $reciver;
}
unset($li);

if(empty($list)){
    $list = array();
    $this->msg = $where;
    $this->info = $list;
    return $this;
}
$this->info = $list;
$this->msg = $input;
return $this;