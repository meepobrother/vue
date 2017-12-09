<?php

$input = $this->__input;

$openid = $input['openid'];

if(empty($openid)){
	$this->code = 0;
	$this->msg = '请在微信浏览器中打开';
	return $this;
}

$type = $input['type'];
$status = $input['status'];

$where = "";
$params = array();
if($status == 'all'){
	//所有的
}else{
	$where .= " AND t.status = :status";
	$params[':status'] = $status;
}

$where .= " AND t.status > 0";


if($type == 'all'){

}else if($type == 'song'){
	$where .= " AND (t.type = 0 OR t.type = 1)";
}else if($type == 'buy'){
	$where .= " AND (t.type = 2 OR t.type = 3)";
}else if($type == 'help'){
	$where .= " AND (t.type = 4 OR t.type = 5)";
}else{

}

$page = intval($input['page']);
$page = $page > 0 ? $page : 1;
$psize  = 10;

$sql = "SELECT t.*,m.avatar as avatar,m.nickname as nickname FROM ".tablename('imeepos_runner3_tasks')." as t LEFT JOIN ".tablename('imeepos_runner3_member')." as m ON t.openid = m.openid WHERE t.openid = :openid {$where} order by t.create_time desc limit ".($page - 1) * $psize .",".$psize;
$params[':openid'] = $input['openid'];
$list = pdo_fetchall($sql,$params);
if(empty($list)){
	$this->code = 0;
	$this->msg = '没有更多了';
	return $this;
}

foreach($list as &$li){
	$li['create_time'] = date('m-d H:i');
	$sql ="SELECT COUNT(*) from ".tablename('imeepos_runner3_tasks_log')." WHERE taskid = :taskid";
	$params = array(':taskid'=>$li['id']);
	$li['message_num'] = pdo_fetchcolumn($sql,$params);
	if($li['type'] == 0){
		$li['type_title'] = '帮我送-文字';
	}else if($li['type'] == 1){
		$li['type_title'] = '帮我送-语音';
	}else if($li['type'] == 2){
		$li['type_title'] = '帮我买-文字';
	}else if($li['type'] == 3){
		$li['type_title'] = '帮我买-语音';
	}else if($li['type'] == 4){
		$li['type_title'] = '帮帮忙-文字';
	}else if($li['type'] == 5){
		$li['type_title'] = '帮帮忙-语音';
	}else{
		$li['type_title'] = '';
	}

	if($li['status'] == 0){
		$li['status_title'] = '未付款';
	}else if($li['status'] == 1){
		$li['status_title'] = '待接单';
	}else if($li['status'] == 2){
		$li['status_title'] = '进行中';
	}else if($li['status'] == 3){
		$li['status_title'] = '已完成';
	}else if($li['status'] == 4){
		$li['status_title'] = '已结束';
	}else{
		$li['status_title'] = '';
	}
}
unset($li);

$this->info = $list;
return $this;