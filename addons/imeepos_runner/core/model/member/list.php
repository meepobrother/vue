<?php
global $_W,$_GPC;

$input = $this->__input['encrypted'];

$start = intval($input['start']);
$len = intval($input['len']);

$start = $start>0? $start : 0;
$len = $len>0?$len: 20;


$start_time = strtotime(date('Y-m-d',time()));
$end_time = time();
//会员总数
$sql = "SELECT COUNT(*) FROM ".tablename('imeepos_runner3_member')." WHERE uniacid =:uniacid";
$params = array(':uniacid'=>$_W['uniacid']);
$totalMemberNum = pdo_fetchcolumn($sql,$params);
//跑腿总数
$sql = "SELECT COUNT(*) FROM ".tablename('imeepos_runner3_member')." WHERE uniacid =:uniacid AND isrunner = 1";
$params = array(':uniacid'=>$_W['uniacid']);
$totalRunnerNum = pdo_fetchcolumn($sql,$params);

//今日会员
$sql = "SELECT COUNT(*) FROM ".tablename('imeepos_runner3_member')." WHERE uniacid =:uniacid AND `time` >=:star_time AND `time` <=:end_time ";
$params = array(':uniacid'=>$_W['uniacid'],':star_time'=>$start_time,':end_time'=>$end_time);
$todayMemberNum = pdo_fetchcolumn($sql,$params);
//今日跑腿
$sql = "SELECT COUNT(*) FROM ".tablename('imeepos_runner3_member')." WHERE uniacid =:uniacid AND isrunner = 1 AND `time` >=:star_time AND `time` <=:end_time";
$params = array(':uniacid'=>$_W['uniacid'],':star_time'=>$start_time,':end_time'=>$end_time);
$todayRunnerNum = pdo_fetchcolumn($sql,$params);

//周统计
$time = strtotime ("-1 week");
$end_time = time();

$sql = "SELECT COUNT(*) as sum,FROM_UNIXTIME(time,'%d') as time_str FROM ".tablename('imeepos_runner3_member')." WHERE time >=:time GROUP BY time_str";
$weeks = pdo_fetchall($sql,array(':time'=>$time));

$sql = "SELECT * FROM ".tablename('imeepos_runner3_member')." WHERE uniacid = :uniacid ORDER BY time DESC limit {$start},{$len}";
$params = array(':uniacid'=>$_W['uniacid']);

$list = pdo_fetchall($sql,$params);

foreach ($list as &$li){
    $li['time'] = date('m-d H:i',$li['time']);
}
unset($li);

$data = array();
$data['totalMemberNum'] = $totalMemberNum ? $totalMemberNum : 0;
$data['totalRunnerNum'] = $totalRunnerNum ? $totalRunnerNum : 0;
$data['stodayMemberNum'] = $todayMemberNum ? $todayMemberNum : 0;
$data['todayRunnerNum'] = $todayRunnerNum ? $todayRunnerNum : 0;
$data['list'] = $list ? $list : array();
$data['weeks'] = $weeks ? $weeks: array();

$this->info = $data;
$this->msg = $input;

return $this;