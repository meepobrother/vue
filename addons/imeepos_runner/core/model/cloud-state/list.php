<?php

global $_W;
$input = $this->__input['encrypted'];
$table = "imeepos_runner4_cloud_site";


$page = intval($input['page']);
$psize = intval($input['psize']);
$page = $page > 0 ? $page : 1;
$psize = $psize > 0 ? $psize : 30;


$sql = "SELECT * FROM ".tablename('imeepos_runner4_cloud_site')." WHERE 1 ORDER BY scan_num DESC LIMIT ".($page - 1)*$psize.",".$psize;
$params = array();
$list = pdo_fetchall($sql,$params);

$this->info = !empty($list) ? $list : array();
$this->msg = $input;
return $this;
