<?php
global $_W;

$input = $this->__input;

if(empty($input['openid'])){
	$this->code = 0;
	$this->msg = '微信中打开';
	return $this;
}

if(empty($input['taskid'])){
	$this->code = 0;
	$this->msg = '任务有误';
	return $this;
}

$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['openid'] = $input['openid'];
$data['create_time'] = time();
$data['content'] = $input['content'];
$data['taskid'] = $input['taskid'];

pdo_insert('imeepos_runner3_tasks_log',$data);
$data['id'] = pdo_insertid();

$data['nickname'] = $input['nickname'];
$data['avatar'] = $input['avatar'];

$this->info = $data;

return $this;