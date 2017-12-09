<?php

global $_W,$_GPC;
$input = $this->__input['encrypted'];
$id = intval($input['id']);
$item = pdo_get('imeepos_runner3_tasks_paylog',array('id'=>$id));

$taskJson = base64_decode($item['setting']);
$task = unserialize($taskJson);
// $task = json_decode(base64_decode($item['setting']),true);
$action = $task['action']; //支付目的

if($action == 'task'){
	$tasks_id = createTask($task,$item['openid']);

	if(empty($tasks_id)){
		$this->code = 0;
		$this->msg = '检测失败';
		return $this;
	}else{
		pdo_update('imeepos_runner3_tasks_paylog',array('tasks_id'=>$tasks_id),array('id'=>$id));
		$this->info = $tasks_id;
		$this->msg = '异常处理成功';
		return $this;
	}
} else{
	$this->code = 2;
	$this->msg = '非异常情况';
	$this->info = $task;
	return $this;
}

$this->info = $id;
return $this;


function createTask($task = array(),$openid = ''){
	global $_W;
	$data = array();
	$data['uniacid'] = $_W['uniacid'];
	$data['status'] = 0;
	$data['create_time'] = time();
	$data['update_time'] = time();
	$data['message'] = $task['desc'];
	$data['openid'] = $openid;

	//录音 serverId
	$data['media_id'] = $task['serverId'];
	if(!pdo_fieldexists('imeepos_runner3_tasks','voice_time')){
        pdo_query("ALTER TABLE ".tablename('imeepos_runner3_tasks')." ADD COLUMN `voice_time` int(11) DEFAULT '0'");
    }
	$data['voice_time'] = $task['voice_time'];
	if(!pdo_fieldexists('imeepos_runner3_tasks','media_src')){
        pdo_query("ALTER TABLE ".tablename('imeepos_runner3_tasks')." ADD COLUMN `media_src` varchar(320) DEFAULT ''");
    }
    
	$data['media_src'] = $task['src'];


	$data['type'] = $task['type']; //任务类型
	$data['payType'] = $task['payType']; // 支付类型

	if($data['payType'] == 'credit'){
		$data['status'] = 1;
	}
	if($data['payType'] == 'wechat'){
		$data['status'] = 1;
	}
	//添加字段
	if(!pdo_fieldexists('imeepos_runner3_tasks','payType')){
        pdo_query("ALTER TABLE ".tablename('imeepos_runner3_tasks')." ADD COLUMN `payType` varchar(32) DEFAULT ''");
    }

    //小费
    if(!pdo_fieldexists('imeepos_runner3_tasks','small_fee')){
        pdo_query("ALTER TABLE ".tablename('imeepos_runner3_tasks')." ADD COLUMN `small_fee` decimal(10,2) DEFAULT '0.00'");
    }
    $data['small_fee'] = floatval($task['money']);
	$data['limit_time'] = $task['duration'];
	$data['total'] = $task['total'];
	

	$code = random(6,true);
	$data['code'] = $code;
	$qrcode = 'imeepos_runner'.md5($code.$data['create_time']);
	$data['qrcode'] = $qrcode;

	pdo_insert('imeepos_runner3_tasks',$data);
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
	//小费
	if(!pdo_fieldexists('imeepos_runner3_detail','small_fee')){
        pdo_query("ALTER TABLE ".tablename('imeepos_runner3_detail')." ADD COLUMN `small_fee` decimal(10,2) DEFAULT '0.00'");
    }
	$detail['small_fee'] = floatval($task['money']);
	// 保价
	$detail['base_fee'] = floatval($task['baojia']['value']);	

	//体积
	if(!pdo_fieldexists('imeepos_runner3_detail','tiji')){
        pdo_query("ALTER TABLE ".tablename('imeepos_runner3_detail')." ADD COLUMN `tiji` int(11) DEFAULT '0'");
    }
    $detail['tiji'] = $task['tiji'];
	
	if(!pdo_fieldexists('imeepos_runner3_detail','total_num')){
        pdo_query("ALTER TABLE ".tablename('imeepos_runner3_detail')." ADD COLUMN `total_num` int(11) DEFAULT '1'");
    }
	// ALTER TABLE `ims_imeepos_runner3_detail` ADD `total_num` INT(11) NOT NULL DEFAULT '1' AFTER `sendmobile`;
	$detail['total_num'] = !empty($task['number']) ? $task['number'] : 1;

	if(!pdo_fieldexists('imeepos_runner3_detail','steps')){
        pdo_query("ALTER TABLE ".tablename('imeepos_runner3_detail')." ADD COLUMN `steps` text DEFAULT ''");
    }
    $detail['steps'] = serialize($task['steps']);

	if($task['time']){
		$str = str_replace('年', '-', $task['time']['value']);
		$str = str_replace('月', '-', $str);
		$str = str_replace('日', '', $str);
		$detail['pickupdate'] = strtotime($str);
	}

	$detail['total'] = $task['total'];

	$detail['message'] = $task['desc'];
	if(!pdo_fieldexists('imeepos_runner3_detail','duration_value')){
        pdo_query("ALTER TABLE ".tablename('imeepos_runner3_detail')." ADD COLUMN `duration_value` int(11) DEFAULT '1'");
    	pdo_query('alter table '.tablename('imeepos_runner3_detail').' modify column duration varchar(120);');
    }

	$detail['duration'] = !empty($task['duration']) ? $task['duration'] : '待定	';
	$detail['duration_value'] = $task['duration_value'];
	$detail['float_distance'] = $task['routeLen'];
	// $detail['goodscost'] = $task['price'];s

	pdo_insert('imeepos_runner3_detail',$detail);

	return $detail['taskid'];
}