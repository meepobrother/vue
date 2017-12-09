<?php
global $_W;
$input = $this->__input['encrypted'];
$page = intval($input['page']);
$psize = intval($input['psize']);

$page = $page > 0 ? $page : 1;
$psize = $psize > 0 ? $psize : 30;

$key = trim($input['key']);

if(!empty($key)){
	$where .=" AND car_num like '%{$key}%' ";
}

$sql = "SELECT * FROM ".tablename('imeepos_repair_server_carfiles')." WHERE 1 {$where} ORDER BY create_time DESC limit ".($page - 1)*$psize.",".$psize;
$params = array();

$list = pdo_fetchall($sql,$params);

foreach($list as &$li){
    $li['create_time'] = date('y-m-d', $li['create_time']);
}
unset($li);
if(empty($list)){
    $list = array();
}
$this->info = $list;
$this->msg = $sql;

return $this;