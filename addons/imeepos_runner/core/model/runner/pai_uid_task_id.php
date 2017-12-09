<?php
global $_W;
$input = $this->__input['encrypted'];

$uid = intval($input['id']);

$member = pdo_get('imeepos_runner3_member', array('id'=>$uid));

// if($member['isadmin'] == 0){
// 	$this->code = 0;
// 	$this->msg = '权限错误';
// 	return $this;
// }


$id = intval($input['task_id']);
$openid = $member['openid'];

$task = pdo_get('imeepos_runner3_tasks',array('id'=>$id));
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
    $url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=detail&m=imeepos_runner&id='.$task['id'];
	M('common')->mc_notice_consume2($task['openid'],$title,$content,$url);


	$content = "您收到一份新的任务指派\n";
	$content .="请在".$detail['duration']."分钟内送达!";
	$content .="赏金".$task['total']."元";
	$title = '任务指派提醒';
    $url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=detail&m=imeepos_runner&id='.$task['id'];
    
	M('common')->mc_notice_consume2($openid,$title,$content,$url);

	
	$this->msg = '派单成功';
	return $this;
}else{
    if($task['status'] == 2){

        $content = "您的任务已被改派给-".$member['realname'] ? $member['realname'] : $member['nickname'];
        $title = '任务改派通知';
        $url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=detail&m=imeepos_runner&id='.$task['id'];
        
        M('common')->mc_notice_consume2($pai['openid'],$title,$content,$url);

        pdo_update('imeepos_runner3_recive',array('openid'=>$openid),array('id'=>$pai['id']));

        //改派通知
        $content = "您收到一份新的任务指派\n";
        $content .="请在".$detail['duration']."分钟内送达!";
        $content .="赏金".$task['total']."元";
        $title = '任务指派提醒';
        $url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=detail&m=imeepos_runner&id='.$task['id'];
        
        M('common')->mc_notice_consume2($openid,$title,$content,$url);

        $this->code = 1;
        $this->msg = '重派成功';
        return $this;
    }else{
        $this->code = 0;
        $this->msg = '此订单已完成，不能重派';
        return $this;
    }
}
// else{
// 	pdo_update('imeepos_runner3_tasks',array('status'=>2),array('id'=>$id));
// 	pdo_update('imeepos_runner3_tasks_paylog',array('status'=>2),array('tasks_id'=>$id));
// }

$this->msg = '派单成功';
return $this;