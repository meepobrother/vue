<?php

global $_W;
$input = $this->__input['encrypted'];
$action = $input['action'] ? $input['action'] : 'logs';

$start = intval($input['start']);
$len = intval($input['len']);
$openid = $_W['openid'];

$member = pdo_get('imeepos_runner3_member',array('openid'=>$openid,'uniacid'=>$_W['uniacid']));

if($action == 'total'){
	//财务处理
	$status = intval($input['status']);
	$status = $status > 0 ? $status : 1; 
	$params = array(':uniacid'=>$_W['uniacid']);
	if(!empty($status)){
		$where = " AND status=:status";
		$params[':status'] = $status;
	}

	if($status == 1){
		$where .= " AND tasks_id = 0";
	}else{
		$where .= " AND tasks_id > 0";
	}
	$copyWhere = $where;
	if($member['isadmin'] == 1 || $member['ismanager']){
		$sql = "SELECT * FROM ".tablename('imeepos_runner3_tasks_paylog')." WHERE uniacid=:uniacid {$where} ORDER BY create_time DESC limit {$start},{$len}";
		
		$list = pdo_fetchall($sql,$params);
		foreach($list as &$li){
			$recive = pdo_get('imeepos_runner3_recive',array('taskid'=>$li['tasks_id']));
			$li['update_time'] = date('m-d H:i',$li['create_time']);
			$recive_member = pdo_get('imeepos_runner3_member',array('openid'=>$recive['openid'],'uniacid'=>$_W['uniacid']));
			$li['avatar'] = $recive_member['avatar']; //接单人
			$member = pdo_get('imeepos_runner3_member',array('openid'=>$li['openid'],'uniacid'=>$_W['uniacid']));
			if($li['status'] == 1){
				$li['avatar'] = $member['avatar'];
			}
			if($li['status'] == 0){
				$li['status_title'] = '未支付';
			}else if($li['status'] == 1){
				$li['status_title'] = '用户已支付';
			}else if($li['status'] == 2){
				$li['status_title'] = '待送达';
			}else if($li['status'] == 3){
				$li['status_title'] = '待确认';
			}else if($li['status'] == 4){
				$li['status_title'] = '待发放';
			}
			//任务详情
			$where = " AND t.id=:taskid";
			$params = array(':taskid'=>$li['tasks_id']);
			$sql2 = "SELECT t.id,t.type,t.status,t.openid,t.create_time,d.* FROM ".tablename('imeepos_runner3_tasks')." as t LEFT JOIN ".
					tablename('imeepos_runner3_detail')." as d ON d.taskid = t.id WHERE 1 {$where} limit 1";
			$item = pdo_fetch($sql2,$params);
			
			$item['nickname'] = $member['nickname'];
			$item['avatar']= $member['avatar'];

			$item['tag'] = empty($item['goodsname']) ? '普通任务' : $item['goodsname'];

			$item['media_src'] = !empty($item['media_src']) ? $item['media_src'] : false;
			$item['media_id'] = !empty($item['media_id']) ? $item['media_id'] : false;
			//保存三天 三天后刚过期
			if(($item['create_time'] + 3*24*60*60) > time()){
				$item['hasMedia'] = !empty($item['media_id']) ? true : false;
			}else{
				$item['hasMedia'] = false;
			}

			$item['sendaddress'] = $item['sendaddress'];
			$item['receiveaddress'] = $item['receiveaddress'];
			$item['duration'] = intval($item['duration']);
			$item['float_distance'] = $item['float_distance'];
			$item['base_fee'] = $item['base_fee'];

			if($item['status'] == 0){
			    $item['status_title'] = '待接单';
			}
			if($item['status'] == 1){
			    $item['status_title'] = '待接单';
			}
			if($item['status'] == 2){
			    $item['status_title'] = '配送中';
			}
			if($item['status'] == 3){
			    $item['status_title'] = '待确认';
			}
			if($item['status'] == 4){
			    $item['status_title'] = '已确认';
			}
			if($item['status'] == 5){
			    $item['status_title'] = '已结款';
			}
			if($item['status'] == 6){
			    $item['status_title'] = '已退款';
			}

			$item['pickupdate'] = $item['pickupdate'];
		    if($item['pickupdate'] > 0 ){
		        $item['pickupdate'] = date('m-d H:i',$item['pickupdate']);
		    }else{
		        $item['pickupdate'] = '越快越好';
		    }

		    $item['lat'] = $item['receivelat'];
		    $item['lng'] = $item['receivelon'];
			$item['goodscost'] = floatval($item['goodscost']);
			$item['create_time'] = date('m-d H:i',$item['create_time']);
			$li['detail'] = $item;

			$taskJson = base64_decode($li['setting']);
			$setting = unserialize($taskJson);

			$li['setting'] = $setting;
			if(empty($li['setting']['action'])){
				unset($li);
			}
		}
		unset($li);
		$this->info = $list;
		$this->msg = $copyWhere;
	}else{
		$this->code = 0;
		$this->msg = '权限错误';
		return $this;
	}
}

if($action == 'mine'){
	$sql = "SELECT * FROM ".tablename('imeepos_runner3_tasks_paylog')." WHERE openid=:openid AND uniacid=:uniacid ORDER BY create_time DESC limit {$start},{$len}";
	$params = array(':uniacid'=>$_W['uniacid'],':openid'=>$openid);
	$list = pdo_fetchall($sql,$params);
	foreach($list as &$li){
		$li['create_time'] = date('m-d H:i',$li['create_time']);
		$task = pdo_get('imeepos_runner3_tasks',array('id'=>$li['tasks_id']));
		$li['type'] = $task['type'];
		if($li['status'] == 0){
			$li['status_title'] = '未支付';
		}else if($li['status'] >= 1){
			$li['status_title'] = '已支付';
		}
		$li['avatar'] = $member['avatar'];
	}
	unset($li);
	$this->info = $list;
	return $this;
}

if($action == 'logs'){
	$sql = "SELECT p.fee,p.status,r.create_time,r.taskid FROM ".tablename('imeepos_runner3_recive')." as r LEFT JOIN ".
		tablename('imeepos_runner3_tasks_paylog')." as p ON r.taskid = p.tasks_id WHERE r.uniacid=:uniacid AND r.openid=:openid ORDER BY r.create_time DESC limit {$start},{$len}";
	$params = array(':uniacid'=>$_W['uniacid'],':openid'=>$openid);
	$list = pdo_fetchall($sql,$params);
	foreach($list as &$li){
		//查找支付记录
		$task = pdo_get('imeepos_runner3_tasks',array('id'=>$li['taskid']));
		$li['type'] = $task['type'];
		$member = pdo_get('imeepos_runner3_member',array('openid'=>$task['openid'],'uniacid'=>$_W['uniacid']));
		$li['create_time'] = date('m-d H:i',$li['create_time']);
		if(empty($li['tasks_id'])){
			$li['tasks_id'] = $task['id'];
		}
		if(empty($li['fee'])){
			$li['fee'] = $task['total'];
		}
		if($li['status'] == 0){
			$li['status_title'] = '未支付';
		}else if($li['status'] == 1){
			$li['status_title'] = '用户已支付';
		}else if($li['status'] == 2){
			$li['status_title'] = '待送达';
		}else if($li['status'] == 3){
			$li['status_title'] = '待确认';
		}else if($li['status'] == 4){
			$li['status_title'] = '待发放';
		}
		$li['avatar'] = $member['avatar'];
	}
	unset($li);

	$this->info = $list;
	return $this;
}



