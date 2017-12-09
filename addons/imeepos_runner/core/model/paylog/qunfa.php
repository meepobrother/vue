<?php
global $_W;
$input = $this->__input['encrypted'];
$id = intval($input['id']);
set_time_limit(0);
if(empty($id)){
	$this->info = $id;
	return $this;
}
$taskid = $id;

$task = pdo_get('imeepos_runner3_tasks',array('id'=>$id));
$detail = pdo_get('imeepos_runner3_detail',array('taskid'=>$id));

$hash = '';
if(!empty($detail['receivelon']) && !empty($detail['receivelat'])){
    $file = ROUTERPATH."/libs/Geohash.php";
	if(file_exists($file)){
	    include_once $file;
	    $domain = new Domain_Geohash();
    	$domain -> setLatitude($detail['receivelat']);
	    $domain -> setLongitude($detail['receivelon']);
	    $domain -> setPrecision(0.1);
	    $hash = $domain -> __toString();
	    //检查hash字段
	    if(!pdo_fieldexists('imeepos_runner3_tasks','hash')){
	        pdo_query("ALTER TABLE ".tablename('imeepos_runner3_tasks')." ADD COLUMN `hash` varchar(32) DEFAULT ''");
	    }
	    $data = array();
	    $data['hash'] = $hash;
	    pdo_update('imeepos_runner3_tasks',$data,array('id'=>$id));
	}
}
//最新订单提醒
if(true){
	if($task['type'] == '0' || $task['type'] == '1'){
		//帮我送
		$content = "任务类型:".$detail['goodsname']."\n";
		$content .= "预约:".($detail['pickupdate'] > 0 ? date('m-d H:i',$detail['pickupdate']) : '尽快开始')."\n";
		$content .= "送达:".(!empty($detail['duration_value']) ? '接单后'.$detail['duration_value'].'分钟内完成' : '不限')."\n";
		$content .= "从:".$detail['sendaddress']."\n送到:".$detail['receiveaddress']."\n";
		$content .="赏金: ".$task['total'];
		$title = '最新任务提醒';
	}

	if($task['type'] == '2' || $task['type'] == '3'){
		//帮我送
		$content = "任务类型:".$detail['goodsname']."\n";
		$content .= "预约:".($detail['pickupdate'] > 0 ? date('m-d H:i',$detail['pickupdate']) : '尽快开始')."\n";
		$content .= "送达:".(!empty($detail['duration_value']) ? '接单后'.$detail['duration_value'].'分钟内完成' : '不限')."\n";
		$content .= "送到:".$detail['receiveaddress']."\n";
		$content .="赏金: ".$task['total'];
		$title = '最新任务提醒';
	}

	if($task['type'] == '4' || $task['type'] == '5'){
		$content = "任务类型:".$detail['goodsname']."\n";
		$content .= "预约:".($detail['pickupdate'] > 0 ? date('m-d H:i',$detail['pickupdate']) : '尽快开始')."\n";
		$content .= "时长:".(!empty($detail['duration_value']) ? $detail['duration_value'].'分钟' : '待定')."\n";
		$content .= "送到:".$detail['receiveaddress']."\n";
		$content .="赏金: ".$task['total'];
		$title = '最新任务提醒';
	}

	//汽车维修店铺工单 6 店铺工单 7 预约工单 
	if($task['type'] == '6'){
		$content = "维修工单:".$detail['goodsname']."\n";
		$content .="金额: ".$task['total'];
		$title = '最新维修工单提醒';
	}

	//预约工单
	if($task['type'] == '7'){
		$content = "洗车工单:".$detail['goodsname']."\n";
		$content .= "预约时间:".($detail['pickupdate'] > 0 ? date('m-d H:i',$detail['pickupdate']) : '尽快开始')."\n";
		$content .="金额: ".$task['total'];
		$content .= "地点: ".$detail['receiveaddress'];
		$title = '最新洗车工单提醒';
	}

	if($tatsk['type'] == '8'){
		$content = "汽车美容:".$detail['goodsname']."\n";
		$content .= "预约时间:".($detail['pickupdate'] > 0 ? date('m-d H:i',$detail['pickupdate']) : '尽快开始')."\n";
		$content .="金额: ".$task['total'];
		$content .= "地点: ".$detail['receiveaddress'];
		$title = '最新汽车美容工单提醒';
	}

	//维修龚工单
	if($task['type'] == '8' || $task['type'] == '9'){
		$content = "维修工单:".$detail['goodsname']."\n";
		$content .="金额: ".$task['total'];
		$title = '最新维修工单提醒';
	}
	
	$openid = $order['openid'];

	$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=detail&id='.$taskid.'&m=imeepos_runner';
	//发送给跑腿 发送给管理员和站长
	// $sql = "SELECT * FROM ".tablename('imeepos_runner3_member')." WHERE ( isadmin = 1 OR isrunner = 1 OR ismanager = 1) AND (hash = '' OR hash =:hash) AND uniacid=:uniacid";
	// $members = pdo_fetchall($sql,array(':hash'=>$hash,':uniacid'=>$_W['uniacid']));
	$members = array();
	if(empty($members)){
		$members = pdo_getall('imeepos_runner3_member',array('uniacid'=>$_W['uniacid'],'isrunner'=>1));
	}
	foreach($members as $member){
		M('common')->mc_notice_consume2($member['openid'],$title,$content,$url);
	}
}
$this->info = $id;
return $this;