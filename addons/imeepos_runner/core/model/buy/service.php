<?php
global $_W;
$input = $this->__input['encrypted'];
// $id = intval($input['id']);

$id = $input['id'];
//检查订购单是否为空
$order = pdo_get('imeepos_runner3_tasks',array('id'=>$id));

if(empty($order)){
	$this->code = 0;
	$this->msg = '订单不存在或已删除';
	return $this;
}

$detail = pdo_get('imeepos_runner3_detail',array('taskid'=>$id));
if(empty($detail)){
	$this->code = 0;
	$this->msg = '订单不存在或已删除';
	return $this;
}

//检查是否自己订单
$openid = $_W['openid'];
$member = pdod_get('imeepos_runner3_member',array('openid'=>$_W['openid'],'uniacid'=>$_W['uniacid']));

$recive = pdo_get('imeepos_runner3_recive',array('taskid'=>$id));

// if($recive['openid'] != $_W['openid']){
// 	$this->code = 0;
// 	$this->msg = '权限错误';
// 	return $this;
// }
//创建数据表

$sql = "CREATE TABLE ".tablename('imeepos_runner3_service_message')." (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned DEFAULT '0',
  `taskid` int(11) unsigned DEFAULT '0',
  `openid` varchar(64) DEFAULT NULL,
  `content` text,
  `create_time` int(11) DEFAULT '0',
  `status` tinyint(2) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  `reply` text,
  PRIMARY KEY (`id`),
  KEY `INDEX_OPENID` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

if(!pdo_tableexists('imeepos_runner3_service_message')){
	pdo_query($sql);
}


$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['create_time'] = time();
$data['openid'] = $_W['openid'];
$data['content'] = trim($input['content']);
$data['status'] = 0;
$data['taskid'] = $id;

pdo_insert('imeepos_runner3_service_message',$data);


$content = "投诉提醒\n";
$content .="投诉留言: ".$input['content']."\n";
$content .= "投诉人: ".$member['nickname'];
$content .= "联系电话: ".$member['mobile'];
if($openid == $recive['openid']){
  //投诉任务主
  $sender = pdo_get('imeepos_runner3_member',array('openid'=>$order['openid'],'uniacid'=>$_W['uniacid']));
  $content.="投诉任务主: ".$sender['nickname'];
  $content.="联系电话: ".$sender['mobile'];
}else{
  $reciver = pdo_get('imeepos_runner3_member',array('openid'=>$recive['openid'],'uniacid'=>$_W['uniacid']));
  $contentt.="投诉接单人".$reciver['nickname'];
  $contentt.="联系电话".$reciver['mobile'];
}
$title = '投诉提醒';
$openid = $recive['openid'];
$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=detail&id='.$order['id'].'&m=imeepos_runner';
$sql = "SELECT * FROM ".tablename('imeepos_runner3_member')." WHERE uniacid=:uniacid AND (isadmin = 1 OR ismanager=1 )";
$list = pdo_fetchall($sql,array(':uniacid'=>$_W['uniacid']));
foreach($list as $li){
  M('common')->mc_notice_consume2($li['openid'],$title,$content,$url);
}
$this->msg = '发送成功!';

return $this;