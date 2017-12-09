<?php
global $_W;
load()->classs('pay');
load()->classs('weixin.pay');
$input = $this->__input['encrypted'];


///

//添加字段
if(!pdo_fieldexists('imeepos_runner3_tasks','payType')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner3_tasks')." ADD COLUMN `payType` varchar(32) DEFAULT ''");
}
//小费
if(!pdo_fieldexists('imeepos_runner3_tasks','small_fee')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner3_tasks')." ADD COLUMN `small_fee` decimal(10,2) DEFAULT '0.00'");
}

if(!pdo_fieldexists('imeepos_runner3_tasks','media_src')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner3_tasks')." ADD COLUMN `media_src` varchar(320) DEFAULT ''");
}

if(!pdo_fieldexists('imeepos_runner3_tasks','voice_time')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner3_tasks')." ADD COLUMN `voice_time` int(11) DEFAULT '0'");
}

//小费
if(!pdo_fieldexists('imeepos_runner3_detail','small_fee')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner3_detail')." ADD COLUMN `small_fee` decimal(10,2) DEFAULT '0.00'");
}

//体积
if(!pdo_fieldexists('imeepos_runner3_detail','tiji')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner3_detail')." ADD COLUMN `tiji` int(11) DEFAULT '0'");
}

if(!pdo_fieldexists('imeepos_runner3_detail','total_num')){
	pdo_query("ALTER TABLE ".tablename('imeepos_runner3_detail')." ADD COLUMN `total_num` int(11) DEFAULT '1'");
}

if(!pdo_fieldexists('imeepos_runner3_detail','steps')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner3_detail')." ADD COLUMN `steps` text DEFAULT ''");
}

if(!pdo_fieldexists('imeepos_runner3_detail','duration_value')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner3_detail')." ADD COLUMN `duration_value` int(11) DEFAULT '1'");
	pdo_query('alter table '.tablename('imeepos_runner3_detail').' modify column duration varchar(120);');
}

///

$taskJson = $input['taskJson'];
$taskJson = base64_decode($taskJson);
$task = unserialize($taskJson);

$action = $task['action']; //支付目的
$action = $action ? $action : 'task';

// 余额支付
if($task['payType'] == 'credit'){
	$item = pdo_get('imeepos_runner3_tasks_paylog',array('tid'=>$task['tid']));
	if(empty($item)){
		$this->code = 0;
		$this->msg = '支付失败';
		return $this;
	}
	//发布任务
	if($action == 'task'){
		//查询记录
		$id = createTask($task);
		$this->code = 1;
		$this->msg = '支付成功';
		$this->info = $id;
		return $this;
	}else if($action == 'runner.buy'){
		if(!checkCode($task)){
			$this->code = 0;
			$this->msg = '验证码有误';
			return $this;
		}
		$member = pdo_get('imeepos_runner3_member',array('openid'=>$_W['openid'],'uniacid'=>$_W['uniacid']));
		if(empty($member)){
			$this->code = 0;
			$this->msg = '会员不存在或已删除';
			return $this;
		}
		$paylog = pdo_get('imeepos_runner3_tasks_paylog',array('tid'=>$task['tid']));
		if(empty($paylog)){
			$this->code = 0;
			$this->msg = '支付失败';
			return $this;
		}
		createRunner($task);

		$this->code = 1;
		$this->msg = '支付成功';
		$this->info = array();
		return $this;
	}else if($action == 'runner.xinyu'){
		$value = $task['value'];
		$openid = $_W['openid'];
		$member = pdo_get('imeepos_runner3_member',array('openid'=>$_W['openid'],'uniacid'=>$_W['uniacid']));
		if(empty($member)){
			$this->code = 0;
			$this->msg = '会员不存在或已删除';
			return $this;
		}
		$xinyu = $member['xinyu'] + $value;
		pdo_update('imeepos_runner3_member',array('xinyu'=>$xinyu),array('id'=>$member['id']));
		$this->code = 1;
		$this->msg = '支付成功';
		$this->info = array();
		return $this;
	}else if($action == 'shoukuan'){
		$data = $task['data'];
		$toOpenid = $data['to_openid'];
		if(empty($toOpenid)){
			$this->code = 0;
			$this->msg = '参数错误';
			$this->info = $task;
			return $this;
		}
		load()->model('mc');
		$uid = mc_openid2uid($toOpenid);
		if(empty($uid)){
			$this->code = 0;
			$this->msg = 'uid为空';
			return $this;
		}
		//到账余额
		$task['total'] = floatval($task['total']);
		if($task['total'] <= 0){
			$this->code = 0;
			$this->msg = '金额错误';
			return $this;
		}
		$return = mc_credit_update($uid,'credit2',$task['total'],array($uid,$task['goods'],'imeepos_runner_plugin_im',0,0));
		if(is_error($return)){
			$this->code = 0;
			$this->msg = "对不起,您的余额不足";
			return $this;
		}
		$this->info = '';
		$this->msg = '支付成功';
		return $this;
	}else if($action == 'coach.teacher'){
		//预约老师
		$data = array();
		$data['uniacid'] = $_W['uniacid'];
		$data['openid'] = $_W['openid'];
		$data['day'] = $task['day']['value'];
		$data['time'] = $task['time']['title'];
		$data['goods'] = $task['goods'];
		$data['message'] = $task['message'];
		$data['realname'] = $task['realname'];
		$data['mobile'] = $task['mobile'];
		$data['type'] = $task['type'];
		$data['seat'] = $task['seat']['title'];
		$data['create_time'] = time();
		$data['status'] = 0;
		$data['to_openid'] = $task['to_openid'];
		$data['other'] = $task['other'];

		pdo_insert('imeepos_runner_plugin_coach_log',$data);

		$id = pdo_insertid();
		$this->info = $id;
		return $this;
	}else{
		$this->code = 0;
		$this->msg = '支付项目不存在';
		$this->info = $task;
		return $this;
	}

}else if($task['payType'] == 'wechat'){
	load()->classs('pay');
	$pay = Pay::create('weixin');

	if(empty($pay)){
		$pay = Pay::create('wechat');
	}

	$result = $pay->queryOrder($task['tid'],2);
	if(is_error($result)){
		$this->code = 0;
		$this->msg = $result['message'];
		return $this;
	}
	if($result['trade_state'] == 'SUCCESS'){
		if($action == 'task'){
			//添加支付记录
			$paylog = pdo_get('imeepos_runner3_tasks_paylog',array('tid'=>$task['tid']));
			if(empty($paylog)){
				$p = array();
				$p['uniacid'] = $_W['uniacid'];
				$p['openid'] = $_W['openid'];
				$p['create_time'] = time();
				$p['type'] = 'wechat';
				$p['status'] = 1;
				$p['tid'] = $task['tid'];
				$p['fee'] = floatval($task['total']);
				$p['tasks_id'] = $id;
				pdo_insert('imeepos_runner3_tasks_paylog',$p);
				$id = createTask($task);
				$this->code = 1;
				$this->msg = '支付成功';
				return $this;
			}else{
				if($paylog['status'] ==0){
					if(pdo_update('imeepos_runner3_tasks_paylog',array('status'=>1),array('id'=>$paylog['id']))){
						$id = createTask($task);
					}
				}else{
					$id = $paylog['tasks_id'];
				}
			}
			$this->code = 1;
			$this->msg = '支付成功';
			$this->info = $id;
			return $this;
		}else if($action == 'runner.buy'){
			if(!checkCode($task)){
				$this->code = 0;
				$this->msg = '验证码有误';
				return $this;
			}
			$member = pdo_get('imeepos_runner3_member',array('openid'=>$_W['openid'],'uniacid'=>$_W['uniacid']));
			if(empty($member)){
				$this->code = 0;
				$this->msg = '会员不存在或已删除';
				return $this;
			}
			createRunner($task);
			$this->code = 1;
			$this->msg = '支付成功';
			$this->info = $id;
			return $this;
		}else if($action == 'runner.xinyu'){
			$value = $task['value'];
			$openid = $_W['openid'];
			$member = pdo_get('imeepos_runner3_member',array('openid'=>$_W['openid'],'uniacid'=>$_W['uniacid']));
			if(empty($member)){
				$this->code = 0;
				$this->msg = '会员不存在或已删除';
				return $this;
			}
			$xinyu = $member['xinyu'] + $value;
			pdo_update('imeepos_runner3_member',array('xinyu'=>$xinyu),array('id'=>$member['id']));
			$this->code = 1;
			$this->msg = '支付成功';
			$this->info = array();
			return $this;
		}else if($action == 'coach.teacher'){
			//预约老师
			$data = array();
			$data['uniacid'] = $_W['uniacid'];
			$data['openid'] = $_W['openid'];
			$data['day'] = $task['day']['value'];
			$data['time'] = $task['time']['title'];
			$data['goods'] = $task['goods'];
			$data['message'] = $task['message'];
			$data['realname'] = $task['realname'];
			$data['mobile'] = $task['mobile'];
			$data['type'] = $task['type'];
			$data['seat'] = $task['seat']['title'];
			$data['create_time'] = time();
			$data['status'] = 0;

			$data['to_openid'] = $task['to_openid'];
			$data['other'] = $task['other'];

			pdo_insert('imeepos_runner_plugin_coach_log',$data);

			$id = pdo_insertid();
			$this->info = $id;
			return $this;
		}else{
			$this->code = 0;
			$this->msg = '支付项目不存在';
			return $this;
		}
	}
	if($result['trade_state'] == 'REFUND'){
		$this->code = 0;
		$this->msg = '支付出现问题,已转入退款';
		return $this;
	}
	if($result['trade_state'] == 'NOTPAY'){
		$this->code = 0;
		$this->msg = '您尚未未支付';
		return $this;
	}
	if($result['trade_state'] == 'NOTPAY'){
		$this->code = 0;
		$this->msg = '支付已关闭';
		return $this;
	}
	if($result['trade_state'] == 'REVOKED'){
		$this->code = 0;
		$this->msg = '已撤销（刷卡支付）';
		return $this;
	}
	if($result['trade_state'] == 'USERPAYING'){
		//支付中 继续查询

		$this->code = 3;
		$this->msg = '用户支付中';
		return $this;
	}
	if($result['trade_state'] == 'PAYERROR'){
		$this->code = 0;
		$this->msg = '支付失败(银行返回失败)';
		return $this;
	}
	$this->info = array("task"=>$task,"result"=>$result);
}else if($task['payType'] == 'divider'){
	if($action == 'task'){
		$id = createTask($task);
		$this->code = 1;
		$this->msg = '支付成功';
		$this->info = $id;
		return $this;
	}else{
		$this->code = 0;
		$this->msg = '不支持货到付款';
		return $this;
	}
}else{
	$this->code = 0;
	$this->msg = '请选择有效的支付方式';
	return $this;
}

$this->msg = '支付方式有误';
return $this;

function checkCode($task = array()){
	$sms = $task['sms'];
	$code = $sms['code'];
	$id = $sms['code_id'];
	$item = pdo_get('imeepos_runner3_code',array('id'=>$id));
	if($item['code'] == $code){
		return true;
	}else{
		return false;
	}
}

function createRunner($task = array()){
	global $_W;

	$xinyu = intval($task['xinyu']['value']);
	$member = pdo_get('imeepos_runner3_member',array('openid'=>$_W['openid'],'uniacid'=>$_W['uniacid']));
	$data = array();
	$data['xinyu'] = $member['xinyu'] + $task['xinyu']['value'];
	$data['realname'] = $task['realname'];
	$data['mobile'] = $task['sms']['mobile'];

	$data['card_image1'] = $task['card']['card_image1'];
	$data['card_image2'] = $task['card']['card_image2'];
	$data['status'] = 0;
	$data['isrunner'] = 1;

	pdo_update('imeepos_runner3_member',$data,array('openid'=>$_W['openid'],'uniacid'=>$_W['uniacid']));

}

function createTask($task = array()){
	global $_W;
	$data = array();
	// ini_set("display_errors", "On");
	// error_reporting(E_ALL | E_STRICT);
	$data['uniacid'] = $_W['uniacid'];
	$data['status'] = 0;
	$data['create_time'] = time();
	$data['update_time'] = time();
	$data['message'] = $task['desc'];
	$data['openid'] = $_W['openid'];
	//录音 serverId
	$data['media_id'] = $task['serverId'];
	$data['voice_time'] = $task['voice_time'];
	$data['media_src'] = $task['src'];
	$data['small_fee'] = floatval($task['money']);
	$data['type'] = $task['type']; //任务类型
	$data['payType'] = $task['payType']; // 支付类型
	if($data['payType'] == 'credit'){
		$data['status'] = 1;
	}
	if($data['payType'] == 'wechat'){
		$data['status'] = 1;
	}
	$data['limit_time'] = $task['duration'];
	$data['total'] = $task['total'];
	$code = random(6,true);
	$data['code'] = $code;
	$qrcode = 'imeepos_runner'.md5($code.$data['create_time']);
	$data['qrcode'] = $qrcode;
	if(pdo_insert('imeepos_runner3_tasks',$data)){
		$taskid = pdo_insertid();
		pdo_update('imeepos_runner3_tasks_paylog',array('tasks_id'=>$taskid),array('tid'=>$task['tid'],'uniacid'=>$_W['uniacid']));
		pdo_update('imeepos_runner3_tasks',array('status'=>1),array('id'=>$taskid));
		$detail = array();
		$detail['taskid'] = $taskid;
		$detail['goodscost'] = $task['price'];
		$detail['goodsname'] = $task['goods'];
		$detail['goodsweight'] = $task['weight'];
		$detail['uniacid'] = $_W['uniacid'];

		$detail['receivelon'] = $task['end']['lng'];
		$detail['receivelat'] = $task['end']['lat'];
		$detail['receivedetail'] = $task['end']['detail'];
		$detail['receivemobile'] = $task['end']['mobile'];
		$detail['receiverealname'] = $task['end']['realname'];
		$detail['receiveaddress'] = $task['end']['poiname'];

		$detail['senddetail'] = $task['start']['detail'];
		$detail['sendlat'] = $task['start']['lat'];
		$detail['sendlon'] = $task['start']['lng'];
		$detail['sendaddress'] = $task['start']['poiname'];
		$detail['sendrealname'] = $task['start']['realname'];
		$detail['sendmobile'] = $task['start']['mobile'];
		$detail['images'] = serialize($task['images']);
		// $detail['base_fee'] = floatval($task['baojia']['value']);	
		$detail['small_fee'] = floatval($task['money']);
		// 保价
		$detail['base_fee'] = floatval($task['baojia']['value']);	
	    $detail['tiji'] = $task['tiji'];

		// ALTER TABLE `ims_imeepos_runner3_detail` ADD `total_num` INT(11) NOT NULL DEFAULT '1' AFTER `sendmobile`;


		$detail['total_num'] = !empty($task['number']) ? $task['number'] : 1;
	    $detail['steps'] = serialize($task['steps']);
		if($task['time']){
			$str = str_replace('年', '-', $task['time']['value']);
			$str = str_replace('月', '-', $str);
			$str = str_replace('日', '', $str);
			$detail['pickupdate'] = strtotime($str);
		}
		$detail['total'] = $task['total'];
		$detail['message'] = $task['desc'];
		$detail['duration'] = !empty($task['duration']) ? $task['duration'] : '待定	';
		$detail['duration_value'] = $task['duration_value'];
		$detail['float_distance'] = $task['routeLen'];
		// $detail['goodscost'] = $task['price'];s
		pdo_insert('imeepos_runner3_detail',$detail);
		return $taskid;
	}
	return 0;
}