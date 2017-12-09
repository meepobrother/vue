<?php
global $_W,$_GPC;
$user = $_W['user'];

$uid = $_GPC['__uid'];

// $this->info = $_W;
$this->msg = $uid;
// $this->code = $_COOKIE;
return $this;