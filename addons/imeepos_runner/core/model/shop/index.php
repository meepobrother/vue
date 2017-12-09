<?php
/**
 * input['uniacid']
 * input['status']
 */
$input = $this->__input;

$page = $input['page'];
$page = $page > 0 ? $page : 1;
$psize = 10;
$params = array(':uniacid'=>$input['uniacid']);
$where = "";
$order = " create_time desc";
if(isset($input['status'])){
    $where .=" AND status = :status";
    $params[':status'] = intval($input['status']);
}
$sql = "SELECT * FROM ".tablename('imeepos_sou_shops')." WHERE uniacid = :uniacid {$where} ORDER BY {$order} limit ".($page-1)*$psize.",".$psize;

$list = pdo_fetchall($sql,$params);

if(empty($list)){
    $this->code = 0;
    $this->msg = '没有更多了';
    return $this;
}else{
    $this->info = $list;
    return $this;
}