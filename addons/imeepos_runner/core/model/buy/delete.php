<?php
global $_W;
$input = $this->__input['encrypted'];
$id = intval($input['id']);

$id = $input['id'];

//检查订购单是否为空
$openid = $input['__meepo_openid'];
if(empty($openid)){
	$openid = $_W['openid'];
}

$member = pdo_get('imeepos_runner3_member',array('openid'=>$openid,'uniacid'=>$_W['uniacid']));

if($member['isadmin'] == 1 || $member['ismanager'] == 1){
	pdo_begin();
	if(pdo_delete('imeepos_runner3_tasks', array('id'=>$id))){
		if(pdo_delete('imeepos_runner3_detail',array('taskid'=>$id))){
			pdo_delete('imeepos_runner3_tasks_paylog',array('tasks_id'=>$id));
			pdo_delete('imeepos_runner3_tasks_log',array('taskid'=>$id));
			pdo_delete('imeepos_runner3_recive',array('taskid'=>$id));
			pdo_commit();
		}
	}else{
		pdo_rollback();
	}
	$this->msg = '操作成功';
	$this->info = $input;
	return $this;
}

return $this;