<?php

global $_W,$_GPC;
$file = IA_ROOT."/addons/imeepos_runnerpro/inc/mobile/__init.php";
if (file_exists($file)) {
    require_once $file;
}
define('STATIC_PATH', MODULE_URL."template/mobile/citys/select/");
$act = isset($_GPC['act']) ? trim($_GPC['act']) : '';
$_W['openid'] = $_W['openid'] ? $_W['openid'] : 'fromUser';

include $this->template('citys/select/index');