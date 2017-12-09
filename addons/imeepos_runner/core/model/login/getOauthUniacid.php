<?php
global $_W,$_GPC;

$code = '__meepo.app.uniacid';
$setting = pdo_get('imeepos_runner3_setting',array('code'=>$code));
$__uniacidItem = unserialize($setting['value']);
$uniacid = $__uniacidItem['uniacid'];

$this->info = $uniacid;
return $this;