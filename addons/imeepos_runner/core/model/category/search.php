<?php
global $_W;
$input = $this->__input;

$sql = "SELECT * FROM ".tablename('imeepos_runner3_category')." WHERE uniacid =:uniacid ORDER BY displayorder DESC";
$params = array(':uniacid'=>$_W['uniacid']);
$list = pdo_fetchall($sql,$params);

foreach ($list as &$li){
    $li['setting'] = unserialize($li['setting']);
}

$this->info = $list;

return $this;