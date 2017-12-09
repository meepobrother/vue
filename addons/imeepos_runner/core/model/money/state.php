<?php
global $_W,$_GPC;
$input = $this->__input['encrypted'];

// ini_set("display_errors", "On");
// error_reporting(E_ALL | E_STRICT);


$type = trim($input['type']);
$return = array();
$return['labels'] = array();
$return['data'] = array();
$return['label'] = '';

$start = strtotime(date('Y-m-d 00:00:00'));
$end = time();
$uniacid = $_W['uniacid'];
if($type == 'day'){
    $return['label'] = '日统计';
    $return['labels'] = array(
        '前天',
        '昨天',
        '今天'
    );
    $start = strtotime(date('Y-m-d 00:00:00'));
    $end = time();
    $return['data'][] = getData(strtotime('-2 day'), strtotime('-1 day'));
    $return['data'][] = getData(strtotime('-1 day'), $start);    
    $return['data'][] = getData($start,$end);
}

if($type == 'week'){
    $return['label'] = '周统计';
    $return['labels'] = array(
        '上周',
        '本周'
    );
    // 上周
    $start = mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7,date("Y"));
    $end = mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y"));
    $return['data'][] = getData($start,$end);
    // 本周
    $start = mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"));
    $end = mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"));
    $return['data'][] = getData($start,$end);    
}

if($type == 'month'){
    $return['label'] = '月统计';
    $return['labels'] = array(
        '上月',        
        '本月'
    );
    // 上周
    $start = mktime(0, 0 , 0,date("m")-1,1,date("Y"));
    $end = mktime(23,59,59,date("m") ,0,date("Y"));
    $return['data'][] = getData($start,$end);
    // 本周
    $start = mktime(0, 0 , 0,date("m"),1,date("Y"));
    $end = mktime(23,59,59,date("m"),date("t"),date("Y"));
    $return['data'][] = getData($start,$end); 
}

if($type == 'jidu'){

}
            
$this->info = $return;
$this->msg = $params;
return $this;


function getData($start,$end){
    global $_W;
    $sql = "SELECT SUM(`fee`) FROM ".tablename('imeepos_runner3_tasks_paylog')." WHERE uniacid={$_W['uniacid']} AND create_time>={$start} AND create_time<={$end}";
    $today = pdo_fetchcolumn($sql);
    return $today;
}
