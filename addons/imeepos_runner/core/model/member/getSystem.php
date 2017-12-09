<?php
global $_W;
$input = $this->__input['encrypted'];
// 检查权限
$__meepo_openid = $input['__meepo_openid'];
$__meepo_rcode = $input['__meepo_rcode'];

if(empty($__meepo_rcode) || empty($__meepo_openid)){
	$this->info = array();
	$this->msg = $input;
	$this->code = -1;
	return $this;
}


$where = " uniacid=:uniacid ";
$params = array(':uniacid'=>$_W['uniacid']);
if(isset($input['shop_id'])){
	$shop_id = intval($input['shop_id']);
	$where .= " AND shop_id=:shop_id ";
	$params[':shop_id'] = $shop_id;
}
$sql = "SELECT * FROM ".tablename('imeepos_runner3_member')." WHERE {$where} ORDER BY time desc ";
$list = pdo_fetchall($sql,$params);

$this->info = $list;
$this->msg = $input;
return $this;