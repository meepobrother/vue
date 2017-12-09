<?php
global $_W,$_GPC;
$input = $this->__input['encrypted'];
$type = trim($input['type']);

$page = intval($input['page']);
$psize = intval($input['psize']);
$page = $page > 0 ? $page : 1;
$psize = $psize > 0 ? $psize : 30;

if($type == 'other'){
    $type = 'divider';
}

$sql = "SELECT * FROM ".tablename('imeepos_runner3_tasks_paylog')." WHERE uniacid=:uniacid AND type=:type ORDER BY create_time DESC limit ".($page - 1)*$psize.",".$psize;
$params = array(':uniacid'=>$_W['uniacid'],':type'=>$type);
$list = pdo_fetchall($sql,$params);

foreach($list as &$li){
    $member = pdo_get('imeepos_runner3_member',array('openid'=>$li['openid']));
    $li['avatar'] = $member['avatar'];
    $li['nickname'] = $member['nickname'];
    $li['create_time'] = date('m-d H:i',$li['create_time']);
    if($li['status']){ }
    $li['tag'] = $li['tid'];
    $li['credit'] = $li['fee'];
}
unset($li);

if(empty($list)){
    $list = array();
}

$this->info = $list;
$this->msg = $input;

return $this;

