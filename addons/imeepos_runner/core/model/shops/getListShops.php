<?php

global $_W;
$input = $this->__input['encrypted'];

$list = pdo_getall('imeepos_runner4_shops',array('uniacid'=>$_W['uniacid']),array(),'id desc');
foreach($list as &$li){ 
    $li['shopers'] = unserialize($li['shopers']);
    $li['employers'] = unserialize($li['employers']);
    $li['kefus'] = unserialize($li['kefus']);
}
unset($li);

if(empty($list)){
    $list = array();
}
$this->info = $list;
return $this;