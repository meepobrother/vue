<?php
global $_W;
$debug = true;
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

//检查是否自己订单
$openid = $_W['openid'];

$recive = pdo_get('imeepos_runner3_recive',array('taskid'=>$id));

if(!$debug){
	if($openid == 'fromUser' || empty($openid)){
		$this->code = 0;
		$this->msg = '请在微信浏览器中打开完成该订单';
		return $this;
	}

	if($task['status'] != 2){
		$this->code = 0;
		$this->msg = '任务状态有误';
		return $this;
	}

	if($recive['openid'] != $openid){
		$this->code = 0;
		$this->msg = '权限错误';
		return $this;
	}
}

load()->classs('pay');
load()->classs('weixin.pay');
//检查未支付订单是否支付
$noPaylog = pdo_get('imeepos_runner3_tasks_paylog',array('tasks_id'=>$id,'status'=>0));
$pay = Pay::create();

if(empty($pay)){
	$pay = Pay::create('wechat');
}
$result = $pay->queryOrder($noPaylog['tid'],2);
if($result['trade_state'] == 'SUCCESS'){
	pdo_update('imeepos_runner3_tasks_paylog',array('status'=>2),array('id'=>$noPaylog['id']));
}

//检查订单支付问题

pdo_delete('imeepos_runner3_tasks_paylog',array('tasks_id'=>$id,'status'=>0));
$sql = "SELECT * FROM ".tablename('imeepos_runner3_tasks_paylog')." WHERE uniacid=:uniacid AND tasks_id=:tasks_id AND (status=1 OR status = 2)";
$params = array(':uniacid'=>$_W['uniacid'],':tasks_id'=>$id);

$paylog = pdo_fetchall($sql,$params);
$hasPay = 0;
foreach($paylog as $log){
	$hasPay += $log['fee'];
}
$needPay = floatval($order['total']) - $hasPay;

$member = pdo_get('imeepos_runner3_member',array('openid'=>$_W['openid'],'uniacid'=>$_W['uniacid']));

if($member['isadmin'] == 1 || $member['ismanager'] == 1){

}else{
	if($needPay > 0 ){
		$this->code = 0;
		$this->msg = '此订单还需要支付'.$needPay.'元';
		return $this;
	}
}

// 计算是否迟到
// 是否预约
$rewardXinyu = 0;
if(empty($detail['pickupdate'])){
   if(!empty($detail['duration_value'])){
       $finish_time = floatval(( time() - $recive['create_time'] ) / 60);
       $rewardTime = $detail['duration_value'] - $finish_time;
       if($rewardTime){
           $rewardXinyu = floatval( ( $rewardTime / $detail['duration_value'] ) * $data['total']);
       }
   }
}else{
   if(!empty($detail['duration_value'])){
       $finish_time = floatval((time() - $detail['pickupdate'] ) / 60);
       $rewardTime = $detail['duration_value'] - $finish_time;
       if($rewardTime){
           $rewardXinyu = floatval( ( $rewardTime / $detail['duration_value'] ) * $data['total']);
       }
   }
}
if($rewardXinyu <  0){
	$xinyu = floatval($member['xinyu'] + $rewardXinyu);
	if($xinyu>0){
		pdo_update('imeepos_runner3_member',array('xinyu'=>$xinyu),array('id'=>$member['id']));
	}else{
		//否则信誉清零
		pdo_update('imeepos_runner3_member',array('xinyu'=>0),array('id'=>$member['id']));
	}

}

//status == 3 已完成
pdo_update('imeepos_runner3_tasks',array('status'=>3),array('id'=>$id));
pdo_update('imeepos_runner3_recive',array('status'=>3,'update_time'=>time()),array('taskid'=>$id));
pdo_update('imeepos_runner3_tasks_paylog',array('status'=>3),array('tasks_id'=>$id,'status'=>2));
pdo_delete('imeepos_runner3_tasks_log',array('taskid'=>$id));

$content = "您的任务已完成,请及时确认";
$title = '任务完成提醒';
$openid = $order['openid'];
$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=detail&id='.$order['id'].'&m=imeepos_runner';
M('common')->mc_notice_consume2($order['openid'],$title,$content,$url);

$content = "成功完成任务,等待用户确认";
$title = '任务完成成功提醒';
$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=detail&id='.$order['id'].'&m=imeepos_runner';
M('common')->mc_notice_consume2($recive['openid'],$title,$content,$url);


$this->code = 1;
$this->msg = '成功完成任务';
return $this;