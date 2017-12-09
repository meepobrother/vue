<?php
global $_W,$_GPC;
// 自动注册
$input = $this->__input['encrypted'];
$rcode = trim($_GPC['r']);

if(empty($rcode)){
    $rcode = random(64);
}
$info = cache_read($rcode);


checkauth();
return $this;