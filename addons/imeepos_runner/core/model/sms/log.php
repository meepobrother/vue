<?php
global $_W;
$input = $this->__input['encrypted'];

$start = intval($input['start']);
$len = intval($input['len']);
$len = $len > 0 ? $len: 0;

$sql = "SELECT * FROM ".tablename('imeepos_runner3_code')." WHERE uniacid=:uniacid ORDER BY time DESC limit {$start},{$len}";
$params = array();
$params[':uniacid'] = $_W['uniacid'];

$list = pdo_fetchall($sql,$params);
$this->info = $list;
return $this;