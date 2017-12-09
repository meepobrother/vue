<?php

global $_W;
$input = $this->__input['encrypted'];

$page = intval($input['page']);
$psize = intval($input['psize']);
$page = $page > 0 ? $page : 1;
$psize = $psize > 0 ? $psize : 30;

$sql = "SELECT * FROM ".tablename('imeepos_runner4_tasks_group')." WHERE uniacid=:uniacid ORDER BY displayorder DESC limit ".($page - 1)*$psize.",".$psize;
$params = array(':uniacid'=>$_W['uniacid']);
$list = pdo_fetchall($sql,$params);
if(empty($list)){
    $list = array();
}
$this->info = $list;
return $this;