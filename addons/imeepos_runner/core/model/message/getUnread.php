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
		$sql = "SELECT l.*,m.avatar as avatar,m.nickname as nickname FROM ".tablename('imeepos_runner3_tasks_log')." as l LEFT JOIN ".tablename('imeepos_runner3_member')." as m ON l.openid = m.openid WHERE l.taskid = :taskid order by l.create_time desc limit ".($page - 1) * $psize .",".$psize;
		$params = array(':taskid'=>$input['taskid']);
		$list = pdo_fetchall($sql,$params);
		if(empty($list)){
			$this->code = 0;
			$this->msg = '没有相关数据';
			return $this;
		}
		foreach($list as &$li){
			$li['create_time'] = date('m-d H:i',$li['create_time']);
		}
		unset($li);
		$this->info = $list;
		return $this;
	}

	if($type == 'news'){
		$sql = "SELECT * FROM ".tablename('imeepos_runner3_message')." WHERE uniacid = :uniacid order by create_time desc limit ".($page - 1) * $psize .",".$psize;
		$params = array(':uniacid'=>$input['uniacid']);
		$list = pdo_fetchall($sql,$params);
		foreach($list as &$li){
			$li['create_time'] = date('m-d H:i',$li['create_time']);
		}
		unset($li);
		$this->info = $list;
		return $this;
	}

	if($type == 'system'){
		$sql = "SELECT * FROM ".tablename('imeepos_runner3_adv')." WHERE uniacid = :uniacid AND position = :position ORDER BY time desc limit ".($page - 1)*$psize .','.$psize;
		$params = array(':uniacid'=>$input['uniacid'],':position'=>'system');
		$list = pdo_fetchall($sql,$params);
		$this->info = $list;
		return $this;
	}

}
