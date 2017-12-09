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

$recive = pdo_get('imeepos_runner3_recive',array('taskid'=>$id));

// if($order['openid'] != $_W['openid']){
// 	$this->code = 0;
// 	$this->msg = '权限错误';
// 	return $this;
// }
//创建数据表

$sql = "CREATE TABLE ".tablename('imeepos_runner3_reminds')." (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned DEFAULT '0',
  `taskid` int(11) unsigned DEFAULT '0',
  `openid` varchar(64) DEFAULT NULL,
  `to_openid` varchar(64) DEFAULT NULL,
  `content` text,
  `create_time` int(11) DEFAULT '0',
  `status` tinyint(2) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  `reply` text,
  PRIMARY KEY (`id`),
  KEY `INDEX_OPENID` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

if(!pdo_tableexists('imeepos_runner3_reminds')){
	pdo_query($sql);
}


$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['create_time'] = time();
$data['openid'] = $_W['openid'];
$data['content'] = trim($input['msg']);
$data['status'] = 0;
$data['taskid'] = $id;
$data['to_openid'] = $recive['openid'];

pdo_insert('imeepos_runner3_reminds',$data);

//发送模板消息提醒
//模板消息提醒
  $content = "任务消息提醒\n";
  $content .="提醒内容: ".$input['msg']."\n";
  $title = '任务消息提醒';
  $openid = $recive['openid'];
  $url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=detail&id='.$order['id'].'&m=imeepos_runner';
  M('common')->mc_notice_consume2($openid,$title,$content,$url);

  
$this->msg = '已发送给接单人员!';

return $this;