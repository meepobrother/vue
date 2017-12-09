<?php
global $_W;
$debug = false;
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
}



//检查是否自己订单
$openid = $_W['openid'];
if(!$debug){
	if(empty($openid)){
		$this->code = 0;
		$this->msg = '请在微信浏览器中打开接单';
		return $this;
	}
	if($task['status'] > 1){
		$this->code = 0;
		$this->msg = '任务已被抢';
		return $this;
	}
	if($openid == $task['openid']){
		$this->code = 0;
		$this->msg = '这是您自己的任务哦';
		return $this;
	}
	//检查是否满足
	$member = pdo_get('imeepos_runner3_member',array('openid'=>$openid,'uniacid'=>$_W['uniacid']));
	if(empty($member['isrunner'])){
		$this->code = 0;
		$this->msg = '没有通过实名认证,不能接单';
		return $this;
	}
	if(empty($member['status'])){
		$this->code = 0;
		$this->msg = '您提交的资料正在核实';
		return $this;
	}
	if($member['xinyu'] < $detail['total']){
		$this->code = 0;
		$this->msg = '对不起,您的信誉不足,此订单需要'.$detail['total'].'信誉以上才能接单';
		return $this;
	}
	if($member['forbid'] == 1){
		$this->code = 0;
		$this->msg = '您的账户已被禁足,'.date('m-d H:i',$member['forbid_time']).'解封';
		return $this;
	}
}else{
	pdo_delete('imeepos_runner3_recive',array('taskid'=>$id));
}


if(!empty($taks['pickupdate'])){
	$now = time();
	if($now > $task['pickupdate']){
		$this->code = 0;
		$this->msg = '任务已过期';
		return $this;
	}
}


$recive = array();
$recive['uniacid'] = $_W['uniacid'];
$recive['openid'] = !empty($_W['openid']) ? $_W['openid'] : 'fromUser';
$recive['taskid'] = $id;
$recive['create_time'] = time();
$recive['status'] = 0;

if(pdo_insert('imeepos_runner3_recive',$recive)){
	$recive['id'] = pdo_insertid();
	pdo_update('imeepos_runner3_tasks',array('status'=>2),array('id'=>$id));
	pdo_update('imeepos_runner3_tasks_paylog',array('status'=>2),array('tasks_id'=>$id,'status'=>1));

	//模板消息提醒
	$content = "您的任务已被受理";
	$content .="预计: ".$detail['duration']."内送达,请保持电话畅通";
	$title = '任务受理提醒';
	$openid = $order['openid'];
	$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=detail&id='.$order['id'].'&m=imeepos_runner';
	M('common')->mc_notice_consume2($openid,$title,$content,$url);

	
	$this->info = $recive['id'];
	$this->msg = '恭喜您,接单成功';
	$this->code = $recive;
	return $this;
}

return $this;