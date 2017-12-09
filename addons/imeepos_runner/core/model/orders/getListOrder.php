<?php

global $_W;
$input = $this->__input['encrypted'];

$sql = "SELECT * FROM ".tablename('imeepos_runner4_order')." WHERE uniacid=:uniacid ORDER BY create_time DESC, id DESC";
$params = array(':uniacid'=>$_W['uniacid']);
$list = pdo_fetchall($sql,$params);
foreach($list as &$li){
    $li['tag'] = unserialize($li['tag']);
    $li['checks'] = unserialize($li['checks']);
    $li['services'] = unserialize($li['services']);
    $li['emplyers'] = unserialize($li['emplyers']);
    $li['goods'] = unserialize($li['goods']);

    if(empty($li['checks'])){
    	$li['checks'] = array();
    }
    if(empty($li['services'])){
    	$li['services'] = array();
    }
    if(empty($li['emplyers'])){
    	$li['emplyers'] = array();
    }
    if(empty($li['goods'])){
    	$li['goods'] = array();
    }

    $li['carfiles'] = pdo_get("imeepos_repair_server_carfiles",array('id'=>$li['car_id']));
}
unset($li);

$this->info = $list;
return $this;