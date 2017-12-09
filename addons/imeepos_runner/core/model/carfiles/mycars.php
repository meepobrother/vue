<?php

$input = $this->__input['encrypted'];
$openid = $_W['openid'];
$start = intval($input['start']);
$len = intval($input['len']);

$sql = "SELECT * FROM ".tablename('imeepos_repair_server_carfiles')." WHERE openid=:openid ORDER BY createt_time limit {$start},{$len}";
$params = array(':openid'=>$openid);

$list = pdo_fetchall($sql,$params);

$this->info = $list;

return $this;