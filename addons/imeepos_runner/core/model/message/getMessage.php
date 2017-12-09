<?php
global $_W;
$input = $this->__input['encrypted'];

$openid = $_W['openid'];

$page = $input['page'] ? $input['page'] : 1;
$psize = $input['psize'] ? $input['psize'] : 30;


$sql = "SELECT * FROM ".tablename('imeepos_runner_plugin_im')." WHERE to_openid=:to_openid ORDER BY create_time DESC LIMIT ".($page - 1)*$psize.",".$psize;
$params = array(':to_openid'=>$openid);

$list = pdo_fetchall($sql,$params);

foreach($list as &$li){
	$li['data'] = unserialize($li['data']);
}

unset($li);

$this->info = $list;
$this->msg = $params;

pdo_update('imeepos_runner_plugin_im',array('status'=>1),array('to_openid'=>$openid,'status'=>0));

return $this;