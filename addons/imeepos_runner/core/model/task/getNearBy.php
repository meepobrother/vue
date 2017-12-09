<?php
global $_W;
$input = $this->__input['encrypted'];

$lat = $input['lat'];
$lng = $input['lng'];

$start = isset($input['start']) ? intval($input['start']) : 0;
$len = isset($input['len']) ? intval($input['len']): 10;
$orderby = trim($input['orderby']);

if(empty($lat) || empty($lng)){
    $this->code = 0;
    $this->msg = '定位失败';
    $this->info = $input;
    return $this;
}

$type = trim($input['type']);

$file = ROUTERPATH."/libs/Geohash.php";
if(file_exists($file)){

    if(empty($orderby)){
        $order = " create_time DESC";
    }else{
        if($orderby == 'DESC'){
            $order = " total DESC";
        }else{
            $order = " total ASC ";
        }
    }   
    $where = "";

    $params = array(':uniacid'=>$_W['uniacid']);
    $type = trim($input['type']);
    if(!empty($type)){
        if($type == 'help'){
            $where .= " AND ( type=4 OR type = 5 )";
        }
        if($type == 'song'){
            $where .= " AND ( type=0 OR type = 1 )";
        }

        if($type == 'buy'){
            $where .= " AND ( type=2 OR type = 3 )";
        }
    }

    include_once $file;

    $domain = new Domain_Geohash();
    $domain -> setLatitude($lat);
    $domain -> setLongitude($lng);
    $domain -> setPrecision(0.1);
    $hash = $domain -> __toString();

    if(!empty($hash)){
        $where .= " AND hash=:hash";
        $params[':hash'] = $hash;
    }

    $sql = "SELECT * FROM ".tablename('imeepos_runner3_tasks')." WHERE uniacid = :uniacid AND status<=1 {$where} ORDER BY {$order} limit {$start},{$len}";
    $list = pdo_fetchall($sql,$params);

    foreach($list as &$li){
        $member = pdo_get('imeepos_runner3_member',array('openid'=>$li['openid'],'uniacid'=>$_W['uniacid']));
        $li['nickname'] = $member['nickname'];
        $li['avatar']= $member['avatar'];
    
        $detail = pdo_get('imeepos_runner3_detail',array('taskid'=>$li['id']));
        $li['tag'] = $detail['goodsname'];
        $li['sendaddress'] = $detail['sendaddress'];
        $li['receiveaddress'] = $detail['receiveaddress'];
        $li['lat'] = $detail['receivelat'];
        $li['lng'] = $detail['receivelon'];
        $li['duration'] = $detail['duration'];
        $li['float_distance'] = $detail['float_distance'];
        $li['base_fee'] = $detail['base_fee'];
        $li['pickupdate'] = $detail['pickupdate'];
        $li['goodscost'] = $detail['goodscost'];

        if($li['pickupdate'] > 0 ){
            $li['pickupdate'] = date('m-d H:i',$li['pickupdate']);
        }else{
            $li['pickupdate'] = '越快越好';
        }

        if($li['status'] == 0){
            $li['status_title'] = '待接单';
        }
        if($li['status'] == 1){
            $li['status_title'] = '待接单';
        }
        if($li['status'] == 2){
            $li['status_title'] = '配送中';
        }
        if($li['status'] == 3){
            $li['status_title'] = '待确认';
        }
        if($li['status'] == 4){
            $li['status_title'] = '已确认';
        }
        if($li['status'] == 5){
            $li['status_title'] = '已打款';
        }
        if($li['status'] == 6){
            $li['status_title'] = '已退单';
        }
    }
    unset($li);
    if(empty($list)){
        $this->code = 0;
        $this->msg = '附近没有任务';
        return $this;
    }else{
        $this->info = array('list'=>$list,'hash'=>$hash);
        $this->msg = $order;
        return $this;
    }
}else{
    $this->code = 0;
    $this->msg = '缺少系统文件';
    return $this;
}