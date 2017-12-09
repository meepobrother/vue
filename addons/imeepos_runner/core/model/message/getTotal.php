<?php

$input = $this->__input;

$openid = $input['openid'];
$page = $input['page'];
$page = $page > 0 ? $page : 1;
$psize  = 15;

if(empty($openid)){
	$this->info = 0;
	return $this;
}else{
	$type = $input['type'];
	if($type == 'task'){
		$sql = "SELECT COUNT(l.id) FROM ".tablename('imeepos_runner3_tasks_log')." as l LEFT JOIN ".tablename('imeepos_runner3_member')." as m ON l.openid = m.openid WHERE l.uniacid = :uniacid AND l.taskid IN (SELECT id FROM ".tablename('imeepos_runner3_tasks')." WHERE openid = :openid AND uniacid = :uniacid AND (status = 1 OR status = 2 OR status = 3) ) ";
		$params = array(':uniacid'=>$input['uniacid'],':openid'=>$openid);
		$sum = pdo_fetchcolumn($sql,$params);
		$this->info = $sum;
		return $this;
	}

}
