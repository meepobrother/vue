<?php
global $_W;
$input = $this->__input['encrypted'];

$data = array();
$data['openid'] = $_W['openid'];
$data['avatar'] = $input['avatar'];
$data['nickname'] = $input['nickname'];
$data['to_openid'] = $input['toOpenid'];
$data['to_avatar'] = $input['toAvatar'];
$data['to_nickname'] = $input['toNickname'];
$data['content'] = $input['content'];
$data['uniacid'] = $_W['uniacid'];
$data['create_time'] = time();

if(!pdo_fieldexists('imeepos_runner_plugin_im','type')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner_plugin_im')." ADD COLUMN `type` varchar(32) DEFAULT ''");
}
$data['type'] = $input['type'];
if(!pdo_fieldexists('imeepos_runner_plugin_im','data')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner_plugin_im')." ADD COLUMN `data` text DEFAULT ''");
}
$data['data'] = serialize($input['data']);

pdo_insert('imeepos_runner_plugin_im',$data);

$this->info = $data;

$content = $input['nickname']."给你发送了一条消息\n";
if($input['type'] == 'text'){
	$content.= "内容:".$input['content']."\n";
}
if($input['type'] == 'image'){
	$content.="类型: 图片\n";
}
if($input['type'] == 'voice'){
	$content.="类型: 语音\n";
}
if($input['type'] == 'video'){
	$content.="类型: 视频\n";
}
if($input['type'] == 'shouqian'){
	$content.="类型: 收款\n";
	$content.="金额: ".$input['data']['money']."\n";
	$content.="备注: ".$input['data']['desc']."\n";
}


$title = '消息提醒';
$openid = $input['toOpenid'];
$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=im&openid='.$_W['openid'].'&m=imeepos_runner';
M('common')->mc_notice_consume2($openid,$title,$content,$url);

return $this;