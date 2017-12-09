<?php
global $_W;
$input = $this->__input['encrypted'];

$id = $input['id'];
//检查订购单是否为空
$order = pdo_get('imeepos_runner3_tasks',array('id'=>$id));
if(empty($order)){
	$this->code = 0;
	$this->msg = '订单不存在或已删除';
	return $this;
}

$detail = pdo_get('imeepos_runner3_detail',array('taskid'=>$id));
if(empty($detail)){
	$this->code = 0;
	$this->msg = '订单不存在或已删除';
	return $this;
}

if($order['status'] != 2){
	$this->code = 0;
	$this->msg = '任务不可取消';
	return $this;
}

//检查是否自己订单
$openid = $_W['openid'];

$recive = pdo_get('imeepos_runner3_recive',array('taskid'=>$id));

if($recive['openid'] != $_W['openid']){
	$this->code = 0;
	$this->msg = '权限错误';
	return $this;
}

//惩罚
$total = intval($order['total'] * 0.8);
$member = pdo_get('imeepos_runner3_member',array('openid'=>$openid,'uniacid'=>$_W['uniacid']));

$len = $member['xinyu'] - $total;
if(!pdo_fieldexists('imeepos_runner3_member','forbid_time')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner3_member')." ADD COLUMN `forbid_time` int(11) DEFAULT '0'");
}

if($len > 0){
	$forbid_time = time() + 2*24*60*60;
	pdo_update('imeepos_runner3_member',array('xinyu'=>$len,'forbid'=>1,'forbid_time'=>$forbid_time),array('id'=>$member['id']));
}else{
	$forbid_time = time() + 2*24*60*60;
	pdo_update('imeepos_runner3_member',array('xinyu'=>0,'forbid'=>1,'forbid_time'=>$forbid_time),array('id'=>$member['id']));
}

//删除接单记录
pdo_delete('imeepos_runner3_recive',array('id'=>$recive['id']));
pdo_delete('imeepos_runner3_tasks_log',array('taskid'=>$order['id']));
//如果是货到付款
if($order['payType'] == 'divider'){
	pdo_update('imeepos_runner3_tasks',array('status'=>0),array('id'=>$order['id']));
}else{
	pdo_update('imeepos_runner3_tasks',array('status'=>1),array('id'=>$order['id']));
}

$this->msg = '放弃成功';

return $this;