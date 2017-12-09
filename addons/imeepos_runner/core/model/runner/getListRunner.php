<?php
global $_W,$_GPC;
$input = $this->__input['encrypted'];

$status = intval($input['status']);

// 0 1 2 -1
$page = !empty($input['page']) ? intval($input['page']) : 1;
$psize = !empty($input['psize']) ? intval($input['psize']) : 20;

if(isset($input['status'])){
	$where = " AND status = ".intval($input['status']);
}

$key = $input['key'];
if(!empty($key)){
	$where .= " AND ( mobile like '%{$key}%' OR realname like '%{$key}%' ) ";
}

if(isset($input['forbid'])){
	$where .= " AND forbid = ".intval($input['forbid']);
}

$sql = "SELECT * FROM ".tablename('imeepos_runner3_member')." WHERE uniacid =:uniacid AND isrunner = 1 {$where} ORDER BY xinyu DESC, time DESC limit ".($page-1)*$psize.",{$psize}";
$params = array(':uniacid'=>$_W['uniacid']);
$list = pdo_fetchall($sql,$params);

foreach($list as &$li){
	$li['tag'] = $li['forbid'] == 1 ? '拉黑' : '正常';
	$li['create_time'] = date('m-d H',$li['time']);
}
unset($li);

if(empty($list)){
    $list = array();
}

$this->info = $list;
$this->msg = $sql;
return $this;