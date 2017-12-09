<?php
global $_W;
$input = $this->__input['encrypted'];

$sql = "SELECT * FROM ".tablename('imeepos_oauth2_manage')." WHERE 1";
$params = array();

$list = pdo_fetchall($sql,$params);

foreach ($list as &$item) {
	$item['module'] = pdo_get('imeepos_oauth2_module',array('id'=>$item['module_id']));
}
unset($item);

$this->info = $list;
return $this;