<?php
global $_W;
$input = $this->__input;

$sql = "SELECT * FROM ".tablename('imeepos_runner3_adv')." WHERE uniacid =:uniacid ORDER BY time DESC";
$params = array(':uniacid'=>$_W['uniacid']);
$list = pdo_fetchall($sql,$params);

$this->info = $list;

return $this;