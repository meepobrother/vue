<?php
global $_W,$_GPC;
$_W['openid'] = $_W['openid'] ? $_W['openid'] : 'fromUser';
define('STATIC_PATH', MODULE_URL."template/mobile/runner/index/");

include $this->template('runner/index/index');
