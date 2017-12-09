<?php
global $_W,$_GPC;

$input = $this->__input['encrypted'];

$url = $input['url'];
$sql = "SELECT * FROM ".tablename('imeepos_oauth2_manage')." WHERE url=:url";
$params = array(':url'=>$url);
$list = pdo_fetchall($sql,$params);

$roles = array();
foreach($list as $li){
	$item = pdo_get('imeepos_oauth2_module',array('id'=>$li['module_id']));
	$roles[] = $item['code'];
}

$this->info = $roles;
return $this;