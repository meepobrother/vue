<?php
global $_W;
$input = $this->__input;
load()->model('mc');

if(empty($input['openid'])){
	$this->code = 0;
	$this->msg = '参数错误';
	return $this;
}

$member = pdo_get('imeepos_runner3_member',array('openid'=>$input['openid']));

if(empty($member)){
	$this->code = 0;
	$this->msg = '用户不存在或已删除';
	return $this;
}

$user = mc_fetch($_W['openid']);

$this->info = $user['credit2'];
return $this;