<?php
global $_W;
// ini_set("display_errors", "On");
// 	error_reporting(E_ALL | E_STRICT);
$input = $this->__input['encrypted'];

$code = $input['code'];

if($code == 'check'){
	pdo_update('imeepos_runner3_member',array('status'=>1),array('id'=>$input['id']));
	// pdo_update('imeepos_runner3_member',array('status'=>0),array('id'=>$input['id']));
	$content = "恭喜您,您的资料审核通过!点击去任务大厅逛逛吧!";
	$title = '资料审核通知';
	$openid = $order['openid'];
	$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=index&m=imeepos_runner';
	M('common')->mc_notice_consume2($recive['openid'],$title,$content,$url);
}
if($code == 'uncheck'){
	pdo_update('imeepos_runner3_member',array('forbid'=>1,'forbid_time'=>time() + 3*24*60*60),array('id'=>$input['id']));
	$content = "您的招呼涉嫌违规,已被管理员禁封!";
	$title = '违规禁封通知';
	$openid = $order['openid'];
	$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=index&m=imeepos_runner';
	M('common')->mc_notice_consume2($recive['openid'],$title,$content,$url);
}

if($code == 'unforbid'){
	pdo_update('imeepos_runner3_member',array('forbid'=>0,'forbid_time'=>time()),array('id'=>$input['id']));
}

if($code == 'fail'){
	pdo_update('imeepos_runner3_member',array('status'=>0),array('id'=>$input['id']));
	$content = "您的资料未通过审核,请重新上传";
	$title = '资料审核通知';
	$openid = $order['openid'];
	$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=index&m=imeepos_runner';
	M('common')->mc_notice_consume2($recive['openid'],$title,$content,$url);
}

$start = intval($input['start']);
$len = $input['len'];
$len = !empty($len) ? $len : 7;

$where = " AND status = 1";
if(isset($input['status'])){
	$where = " AND status = ".intval($input['status']);
}

$key = $input['key'];
if(!empty($key)){
	$where .= " AND ( mobile like '%{$key}%' OR realname like '%{$key}%' ) ";
}

if(isset($input['forbid'])){
	$where .= " AND forbid = ".intval($input['forbid']);
}

$sql = "SELECT avatar,nickname,id,openid,realname,mobile,status,card_image1,card_image2,forbid,forbid_time,time,xinyu FROM ".tablename('imeepos_runner3_member')." WHERE uniacid =:uniacid AND isrunner = 1 {$where} ORDER BY xinyu DESC, time DESC limit {$start},{$len}";
$params = array(':uniacid'=>$_W['uniacid']);
$list = pdo_fetchall($sql,$params);

foreach($list as &$li){
	$li['tag'] = $li['forbid'] == 1 ? '拉黑' : '正常';
	$li['create_time'] = date('m-d H',$li['time']);
}
unset($li);

$this->info = $list;
$this->msg = $input;

return $this;