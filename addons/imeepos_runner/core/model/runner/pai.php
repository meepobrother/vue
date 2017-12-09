<?php
global $_W;
$input = $this->__input['encrypted'];

$member = pdo_get('imeepos_runner3_member',array('openid'=>$_W['openid'],'uniacid'=>$_W['uniacid']));

if($member['isadmin'] == 0){
	$this->code = 0;
	$this->msg = '权限错误';
	return $this;
}

$id = intval($input['id']);
$openid = $input['openid'];

$task = pdo_get('imeepos_runner3_tasks',array('id'=>$id));
if($task['status'] > 1){
	$this->code = 0;
	$this->msg = '此订单已被抢了';
	return $this;
}
$detail = pdo_get('imeepos_runner3_detail',array('taskid'=>$id));
//检查派单
$pai = pdo_get('imeepos_runner3_recive',array('taskid'=>$id));
if(empty($pai)){
	$data = array();
	$data['uniacid'] = $_W['uniacid'];
	$data['taskid'] = $id;
	$data['openid'] = $openid;
	$data['create_time'] = time();
	$data['fee'] = $task['total'];
	$data['status'] = 0;

	pdo_insert('imeepos_runner3_recive',$data);
	pdo_update('imeepos_runner3_tasks',array('status'=>2),array('id'=>$id));
	pdo_update('imeepos_runner3_tasks_paylog',array('status'=>2),array('tasks_id'=>$id));
	//派单通知
	$content = "您的任务已被受理";
	$content .="预计在".$detail['duration']."分钟内送达,请保持电话畅通";
	$title = '任务受理提醒';
	$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=index&m=imeepos_runner';
	if($task['type'] == 0 || $task['type'] == 1){
		$url.="#/song/detail/".$task['id'];
	}
	if($task['type'] == 2 || $task['type'] == 3){
		$url.="#/buy/detail/".$task['id'];
	}
	if($task['type'] == 4 || $task['type'] == 5){
		$url.="#/help/detail/".$task['id'];
	}
	M('common')->mc_notice_consume2($task['openid'],$title,$content,$url);


	$content = "您收到一份新的任务指派\n";
	$content .="请在".$detail['duration']."分钟内送达!";
	$content .="赏金".$task['total']."元";
	$title = '任务指派提醒';
	$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=index&m=imeepos_runner';
	if($task['type'] == 0 || $task['type'] == 1){
		$url.="#/song/detail/".$task['id'];
	}
	if($task['type'] == 2 || $task['type'] == 3){
		$url.="#/buy/detail/".$task['id'];
	}
	if($task['type'] == 4 || $task['type'] == 5){
		$url.="#/help/detail/".$task['id'];
	}
	M('common')->mc_notice_consume2($openid,$title,$content,$url);

	
	$this->msg = '派单成功';
	return $this;
}else{
	$this->code = 0;
	$this->msg = '派单失败';
	return $this;
}
// else{
// 	pdo_update('imeepos_runner3_tasks',array('status'=>2),array('id'=>$id));
// 	pdo_update('imeepos_runner3_tasks_paylog',array('status'=>2),array('tasks_id'=>$id));
// }

$this->msg = '派单成功';
return $this;