<?php
global $_W;
$code = '__meepo.app.uniacid';		
$setting = pdo_get('imeepos_runner3_setting',array('code'=>$code));
$__uniacidItem = unserialize($setting['value']);
$uniacid = $__uniacidItem['uniacid'];

$rcode = random(32);
$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['acid'] = $_W['uniacid'];
cache_write($rcode, $data);

$this->info = $rcode;
return $this;
