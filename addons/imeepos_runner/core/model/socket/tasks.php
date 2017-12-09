<?php
global $_W;

//找新任务
$openid = $_W['openid'];

$sql = "SELECT id,openid,create_time,type,media_id	 FROM ".tablename('imeepos_runner3_tasks')." WHERE id NOT IN (SELECT taskid FROM "
	.tablename('imeepos_runner3_listenlog')." WHERE uniacid = '{$_W['uniacid']}' AND openid = '{$_W['openid']}') AND uniacid = :uniacid AND status <=1 order by create_time DESC limit 1";
$params = array(':uniacid'=>$_W['uniacid']);
$item = pdo_fetch($sql,$params);


if(empty($item)){
	$this->code = 0;
	$this->msg = '';
	return $this;
}


$log = array();
$log['uniacid'] = $_W['uniacid'];
$log['taskid'] = $item['id'];
$log['create_time'] = time();
$log['openid'] = $_W['openid'];

pdo_insert('imeepos_runner3_listenlog',$log);


$detail = pdo_get('imeepos_runner3_detail',array('taskid'=>$item['id']));
$sender = pdo_get('imeepos_runner3_member',array('openid'=>$item['openid'],'uniacid'=>$_W['uniacid']));
$item['tag'] = $detail['goodsname'];
$item['avatar'] = $sender['avatar'];
$item['nickname'] = $sender['nickname'];
$item['create_time'] = date('m-d H:i',$item['create_time']);
$item['poiname'] = $detail['receiveaddress'];

$this->info = $item;
return $this;