<?php
global $_W;
$input = $this->__input['encrypted'];

$start = isset($input['start']) ? intval($input['start']) : 0;
$len = isset($input['len']) ? intval($input['len']): 10;
$status = intval($input['status']);

$recive = empty($input['recive']) ? 0 : intval($input['recive']);
// type = 1预约取 type = 0 及时取
$where = " AND uniacid=:uniacid AND openid =:openid";
$params = array(':uniacid'=>$_W['uniacid'],':openid'=>$_W['openid']);

$openid = $_W['openid'];

if(!pdo_indexexists('imeepos_runner3_member','IDX_OPENID')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner3_member')." ADD INDEX IDX_OPENID (`openid`) ";
    pdo_query($sql);
}
if(!pdo_indexexists('imeepos_runner3_member','IDX_UNIACID')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner3_member')." ADD INDEX IDX_UNIACID (`uniacid`) ";
    pdo_query($sql);
}
$member = pdo_get('imeepos_runner3_member',array('openid'=>$_W['openid'],'uniacid'=>$_W['uniacid']));

$orderby = trim($input['orderby']);
$action = trim($input['action']);

if($action == 'index'){
    if(!pdo_indexexists('imeepos_runner3_tasks','IDX_UNIACID')){
        $sql = "ALTER TABLE ".tablename('imeepos_runner3_tasks')." ADD INDEX IDX_UNIACID (`uniacid`) ";
        pdo_query($sql);
    }
    $where = " AND uniacid =:uniacid";
    $params = array(':uniacid'=>$_W['uniacid']);
    $where .= " AND status <=1";
    
    if(empty($orderby)){
        $order = " create_time DESC";
    }else{
        if($orderby == 'DESC'){
            $order = " total DESC";
        }else{
            $order = " total ASC ";
        }
    }

    $type = intval($input['type']);
    if(!pdo_indexexists('imeepos_runner3_tasks','IDX_TYPE')){
        $sql = "ALTER TABLE ".tablename('imeepos_runner3_tasks')." ADD INDEX IDX_TYPE (`type`) ";
        pdo_query($sql);
    }
    if(isset($input['type'])){
        $where .= " AND type=:type";
        $params[':type'] = $type;
    }
    $sql2 = "SELECT * FROM ".tablename('imeepos_runner3_tasks')." WHERE 1 {$where} order by {$order} limit {$start},{$len}";
    $list = pdo_fetchall($sql2,$params);

    foreach ($list as &$li) {
        $member = pdo_get('imeepos_runner3_member',array('openid'=>$li['openid'],'uniacid'=>$_W['uniacid']));
        $li['nickname'] = $member['nickname'];
        $li['avatar']= $member['avatar'];
        if(!pdo_indexexists('imeepos_runner3_detail','IDX_TASKID')){
            $sql = "ALTER TABLE ".tablename('imeepos_runner3_detail')." ADD INDEX IDX_TASKID (`taskid`) ";
            pdo_query($sql);
        }
        $detail = pdo_get('imeepos_runner3_detail',array('taskid'=>$li['id']));
        $li['tag'] = $detail['goodsname'];
        $li['sendaddress'] = $detail['sendaddress'];
        $li['receiveaddress'] = $detail['receiveaddress'];
        $li['lat'] = $detail['receivelat'];
        $li['lng'] = $detail['receivelon'];
        $li['duration'] = $detail['duration'];
        $li['duration_value'] = intval($detail['duration_value']);
        $li['float_distance'] = $detail['float_distance'];
        $li['base_fee'] = $detail['base_fee'];
        $li['pickupdate'] = $detail['pickupdate'];
        $li['goodscost'] = $detail['goodscost'];

        if($li['pickupdate'] > 0 ){
            $li['pickupdate'] = date('m-d H:i',$li['pickupdate']);
        }else{
            $li['pickupdate'] = '越快越好';
        }
        //保存三天 三天后刚过期
        if(($li['create_time'] + 3*24*60*60) > time()){
            $li['hasMedia'] = !empty($li['media_id']) ? true : false;
        }else{
            $li['hasMedia'] = false;
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
        $li['create_time'] = date('m-d H:i',$li['create_time']);
    }
    unset($li);

    $this->info = $list;
    $this->msg = $input;
    return $this;
}


if($recive == 0){
    //我的接单
    // if($member['isadmin'] == 1 || $member['ismanager'] == 1){
    //     $where = " AND uniacid =:uniacid";
    //     $params = array(':uniacid'=>$_W['uniacid']);
    // }
    if(!pdo_indexexists('imeepos_runner3_tasks','IDX_STATUS')){
        $sql = "ALTER TABLE ".tablename('imeepos_runner3_tasks')." ADD INDEX IDX_STATUS (`status`) ";
        pdo_query($sql);
    }
    if($status){
        $where .= " AND status =:status";
        $params[':status'] = $status;
    }
    if(empty($orderby)){
        $order = " create_time DESC";
    }else{
        if($orderby == 'DESC'){
            $order = " total DESC";
        }else{
            $order = " total ASC ";
        }
    }
    $sql2 = "SELECT * FROM ".tablename('imeepos_runner3_tasks')." WHERE 1 {$where} order by {$order} limit {$start},{$len}";
    $list = pdo_fetchall($sql2,$params);

    foreach ($list as &$li) {
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

        if(($li['create_time'] + 3*24*60*60) > time()){
            $li['hasMedia'] = !empty($li['media_id']) ? true : false;
        }else{
            $li['hasMedia'] = false;
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
        $li['create_time'] = date('m-d H:i',$li['create_time']);
    }
    unset($li);

    $this->info = $list;
    $this->msg = $input;
    return $this;
}else{
    if(!pdo_indexexists('imeepos_runner3_tasks','IDX_OPENID')){
        $sql = "ALTER TABLE ".tablename('imeepos_runner3_tasks')." ADD INDEX IDX_OPENID (`openid`) ";
        pdo_query($sql);
    }
    $params = array(':openid'=>$openid,':uniacid'=>$_W['uniacid']);
    $where = " AND r.openid=:openid AND r.uniacid =:uniacid";
    // if($member['isadmin'] == 1 || $member['ismanager'] == 1){
    //     $where = " AND r.uniacid =:uniacid";
    //     $params = array(':uniacid'=>$_W['uniacid']);
    // }
    if($status){
        $where .= " AND t.status =:status";
        $params[':status'] = $status;
    }
    $sql3 = "SELECT t.create_time,t.status,r.taskid,t.id,t.openid,t.desc,t.type,t.total FROM "
            .tablename('imeepos_runner3_recive')." as r LEFT JOIN ".
            tablename('imeepos_runner3_tasks')." as t ON r.taskid = t.id WHERE 1 {$where} order by t.create_time desc limit {$start},{$len}";
            // $this->msg = $sql3;
    $list = pdo_fetchall($sql3,$params);

    // $sql = pdo_getall('imeepos_runner3_recive',array('openid'=>$_W['openid']));
    // $this->info = $list;
    // return $this;
    // $sql2 = "SELECT t.id,t.openid,t.desc,m.nickname,m.avatar,t.type FROM ".tablename('imeepos_runner3_tasks')." as t LEFT JOIN "
    //         .tablename('imeepos_runner3_member')." as m ON t.openid = m.openid AND t.uniacid = m.uniacid WHERE 1 {$where} order by t.create_time desc limit {$start},{$len}";
    // $list = pdo_fetchall($sql2,$params);

    foreach ($list as &$li) {
        $detail = pdo_get('imeepos_runner3_detail',array('taskid'=>$li['taskid']));
        $member = pdo_get('imeepos_runner3_member',array('openid'=>$li['openid'],'uniacid'=>$_W['uniacid']));
        $li['tag'] = $detail['goodsname'];
        $li['sendaddress'] = $detail['sendaddress'];
        $li['receiveaddress'] = $detail['receiveaddress'];
        $li['duration'] = $detail['duration'];
        $li['float_distance'] = $detail['float_distance'];
        $li['base_fee'] = $detail['base_fee'];
        $li['nickname'] = $member['nickname'];
        $li['avatar'] = $member['avatar'];
        $li['pickupdate'] = $detail['pickupdate'];
        $li['goodscost'] = $detail['goodscost'];
        if($li['pickupdate'] > 0 ){
            $li['pickupdate'] = date('m-d H:i',$li['pickupdate']);
        }else{
            $li['pickupdate'] = '越快越好';
        }
        $li['lat'] = $detail['receivelat'];
        $li['lng'] = $detail['receivelon'];
        $li['duration'] = $detail['duration'];

        if($li['pickupdate'] > 0 ){
            $li['pickupdate'] = date('m-d H:i',$li['pickupdate']);
        }else{
            $li['pickupdate'] = '越快越好';
        }

        if(($li['create_time'] + 3*24*60*60) > time()){
            $li['hasMedia'] = !empty($li['media_id']) ? true : false;
        }else{
            $li['hasMedia'] = false;
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
        $li['create_time'] = date('m-d H:i',$li['create_time']);
    }
    unset($li);

    $this->info = $list;
    return $this;
}

