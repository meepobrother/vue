<?php
global $_W,$_GPC;

$shops = pdo_getall('imeepos_runner4_shops',array('uniacid'=>$_W['uniacid']));

foreach($shops as &$shop){
	$shop['employers'] = unserialize($shop['employers']);
	if(empty($shop['employers'])){
		$shop['employers'] = array();
	}
	
	// 统计每个店铺的营业额
	$sql = "SELECT SUM(fee) as y, create_time as x FROM ".tablename("imeepos_runner4_state_shop")." WHERE shop_id=:shop_id AND uniacid=:uniacid GROUP BY create_time ";
	$params = array();
	$params[':shop_id'] = $shop['id'];
	$params[':uniacid'] = $_W['uniacid'];
	$shop['list'] = pdo_fetchall($sql,$params);
	$shop['list'] = formatListTime($shop['list']);

	foreach($shop['employers'] as &$employer){
		
		$sql = "SELECT SUM(fee) as y, create_time as x FROM ".tablename("imeepos_runner4_state_emplyer")." WHERE openid=:openid AND uniacid=:uniacid GROUP BY create_time ";
		$params = array();
		$params[':openid'] = $employer['openid'];
		$params[':uniacid'] = $_W['uniacid'];
		$list = pdo_fetchall($sql,$params);
		// 统计
		$employer['list'] = formatListTime($list);
		// 总和
		$sql = "SELECT SUM(fee) FROM ".tablename("imeepos_runner4_state_emplyer")." WHERE openid=:openid AND uniacid=:uniacid ";
		$params = array();
		$params[':openid'] = $employer['openid'];
		$params[':uniacid'] = $_W['uniacid'];
		$total = pdo_fetchcolumn($sql,$params);
		$employer['total'] = $total;

		// 本月
		$start = strtotime(date('y-m-01',time()));
		$end = time();
		$sql = "SELECT SUM(fee) FROM ".tablename("imeepos_runner4_state_emplyer")." WHERE openid=:openid AND uniacid=:uniacid AND create_time>={$start} AND create_time <= {$end}";
		$params = array();
		$params[':openid'] = $employer['openid'];
		$params[':uniacid'] = $_W['uniacid'];
		$total = pdo_fetchcolumn($sql,$params);
		$employer['month_now'] = $total > 0 ? $total : 0.00;
		$employer['params_now'] = array($start,$end);

		// 上月
		$timestamp = time();
		$start = date('Y-m-01',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01'));
		$end = date('Y-m-d', strtotime("$start +1 month -1 day"));
		$start = strtotime($start);
		$end = strtotime($end);

		$sql = "SELECT SUM(fee) FROM ".tablename("imeepos_runner4_state_emplyer")." WHERE openid=:openid AND uniacid=:uniacid AND create_time>={$start} AND create_time <= {$end}";
		$params = array();
		$params[':openid'] = $employer['openid'];
		$params[':uniacid'] = $_W['uniacid'];
		$total = pdo_fetchcolumn($sql,$params);
		$employer['month_last'] = $total > 0 ? $total : 0.00;
		$employer['params_last'] = array($start,$end);

	}
	unset($employer);
}
unset($shop);

$this->info = $shops;
return $this;

function getMonth($date){
    $firstday = date("Y-m-01",strtotime($date));
    $lastday = date("Y-m-d",strtotime("$firstday +1 month -1 day"));
    return array($firstday,$lastday);
}

function getlastMonthDays($date){
    $timestamp= time();
    $firstday=date('Y-m-01',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01'));
    $lastday=date('Y-m-d',strtotime("$firstday +1 month -1 day"));
    return array($firstday,$lastday);
}

function getNextMonthDays($date){
    $timestamp=strtotime($date);
    $arr=getdate($timestamp);
    if($arr['mon'] == 12){
        $year=$arr['year'] +1;
        $month=$arr['mon'] -11;
        $firstday=$year.'-0'.$month.'-01';
        $lastday=date('Y-m-d',strtotime("$firstday +1 month -1 day"));
    }else{
        $firstday=date('Y-m-01',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)+1).'-01'));
        $lastday=date('Y-m-d',strtotime("$firstday +1 month -1 day"));
    }
    return array($firstday,$lastday);
}

function formatListTime($list = array(), $format = 'm月d日'){
	foreach($list as &$li){
		$li['x'] = date($format, $li['x']);
	}
	unset($li);
	$list = array_merge(array(array('x'=>0,'y'=>0)),$list);
	return $list;
}