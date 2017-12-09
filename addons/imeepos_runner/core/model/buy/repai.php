<?php
global $_W,$_GPC;
$input = $this->__input['encrypted'];

$sql = "SELECT * FROM ".tablename('imeepos_runner3_member')." WHERE isrunner=1 AND status = 1 AND uniacid={$_W['uniacid']}";
$params = array();
$list = pdo_fetchall($sql,$params);

foreach ($list as &$li) {
    $li['task_id'] = $input['id'];
}
unset($li);

$this->info = $list;

return $this;