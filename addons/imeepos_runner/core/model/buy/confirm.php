<?php
global $_W;
$input = $this->__input['encrypted'];
$id = intval($input['id']);
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


//检查是否自己订单
$openid = $_W['openid'];

$member = pdo_get('imeepos_runner3_member',array('openid'=>$openid,'uniacid'=>$_W['uniacid']));

$recive = pdo_get('imeepos_runner3_recive',array('taskid'=>$id));

if($member['isadmin'] == 1 || $member['ismanager']){
	
}else{
	if($order['openid'] != $_W['openid']){
		$this->code = 0;
		$this->msg = '权限错误';
		// $this->info = array('openid1'=>$order['openid'],'openid2'=>$_W['openid']);
		return $this;
	}
}


//确认送达
if($order['total'] < 0){
	$this->code = 0;
	$this->msg = '任务金额有误';
	return $this;
}

if($order['status'] == 2 || $order['status'] == 3){
	// 4 已确认
	pdo_update('imeepos_runner3_tasks',array('status'=>4),array('id'=>$id));
	pdo_update('imeepos_runner3_recive',array('status'=>4),array('taskid'=>$id));
	pdo_update('imeepos_runner3_tasks_paylog',array('status'=>4),array('tasks_id'=>$id,'status'=>3));

	$content = "用户已确认完成,等待佣金到账";
	$title = '任务确认提醒';
	$openid = $order['openid'];
	$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=detail&id='.$order['id'].'&m=imeepos_runner';
	M('common')->mc_notice_consume2($recive['openid'],$title,$content,$url);

}else{
	$this->code = 0;
	$this->msg = '操作错误';
	return $this;
}

$this->msg = '操作成功';

return $this;