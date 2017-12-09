<?php
global $_W;
$input = $this->__input['encrypted'];
// ini_set("display_errors", "On");
// error_reporting(E_ALL | E_STRICT);
$debug = true;
load()->model('mc');
$id = intval($input['id']);

if(empty($id)){
	$this->code = 0;
	$this->msg = '参数错误';
	return $this;
}
$order = pdo_get('imeepos_runner3_tasks',array('id'=>$id));

if(empty($order)){
    pdo_delete('imeepos_runner3_tasks',array('id'=>$id));
    $this->code = 0;
    $this->msg = '任务不存在或已删除';
    $this->info = array('id'=>$id,'order'=>$order);
    return $this;
}

$detail = pdo_get('imeepos_runner3_detail',array('taskid'=>$order['id']));

if(empty($detail)){
    pdo_delete('imeepos_runner3_tasks',array('id'=>$id));
    $this->code = 0;
    $this->msg = '任务不存在或已删除';
    $this->info = array("order"=>$order,'detail'=>$detail);
    return $this;
}

$sql = "SELECT * FROM ".tablename('imeepos_runner3_tasks_paylog')." WHERE tasks_id=:tasks_id AND status > 0 AND status < 6";
$params = array(':tasks_id'=>$id);
$paylog = pdo_fetchall($sql,$params);


$data = array();
$data['type'] = $order['type'];
$data['paylog'] = $paylog;
$data['id'] = $id;
$data['desc'] = $detail['message'];
$data['content'] = $detail['message'];
$data['message'] = $detail['message'];
$data['openid'] = $order['openid'];
//保存三天 三天后刚过期
$data['media_src'] = !empty($order['media_src']) ? $order['media_src'] : false;
$data['media_id'] = !empty($order['media_id']) ? $order['media_id'] : false;

if(($order['create_time'] + 3*24*60*60) > time()){
    $data['hasMedia'] = !empty($order['media_id']) ? true : false;
}else{
    $data['hasMedia'] = false;
}

$data['create_time'] = date('m-d H:i',$order['create_time']);
$data['media_src'] = !empty($order['media_src']) ? $order['media_src'] : false;

$data['receiverealname'] = $detail['receiverealname'];
$data['receiveaddress'] = $detail['receiveaddress'];
$data['receivedetail'] = $detail['receivedetail'];
$data['receivelat'] = $detail['receivelat'];
$data['receivelon'] = $detail['receivelon'];

$data['images'] = unserialize($detail['images']);


$data['sendrealname'] = $detail['sendrealname'];
$data['sendaddress'] = $detail['sendaddress'];
$data['senddetail'] = $detail['senddetail'];
$data['sendlat'] = $detail['sendlat'];
$data['sendlon'] = $detail['sendlon'];

$data['goodsname'] = $detail['goodsname'];
$data['goodscost'] = $detail['goodscost'];
$data['goodsweight'] = $detail['goodsweight'];


$data['steps'] = unserialize($detail['steps']);

$data['base_fee'] = $detail['base_fee'];
$data['small_fee'] = $detail['small_fee'];

$data['pickupdate'] = $detail['pickupdate'] > 0 ? date('m-d H:i',$detail['pickupdate']) : '';
$data['duration_value'] = $detail['duration_value'];
if(empty($data['pickupdate'])){
    $data['duration'] = $detail['duration'];
}else{
    $data['duration'] = date('m-d H:i',($detail['pickupdate'] + $detail['duration_value']*60 ));
}

$data['total'] = $detail['total'];
$data['total_num'] = $detail['total_num'];

$sender = pdo_get('imeepos_runner3_member',array('openid'=>$order['openid'],'uniacid'=>$_W['uniacid']));
$deta['sender'] = $sender;


//接单状态
$data['status'] = $order['status'];
if($data['status'] == 1){
    $data['status_title'] = '待接单';
    $data['recive'] = array();
}
if($data['status'] == 0){
    $data['status_title'] = '货到付款';
}
if($data['status'] == 2){
    $data['status_title'] = '配送中';
}
if($data['status'] == 3){
    $data['status_title'] = '已完成';
}
if($data['status'] == 4){
    $data['status_title'] = '再来一单';
}
if($data['status'] == 5){
    $data['status_title'] = '已申请退款';
}
if($data['status'] == 6){
    $data['status_title'] = '退款成功';
}

$openid = $_W['openid'];

$member = pdo_get('imeepos_runner3_member',array('openid'=>$_W['openid'],'uniacid'=>$_W['uniacid']));
$data['myinfo'] = array();
$data['myinfo']['openid'] = $_W['openid'];//'oZ4P7s7ViHLsNsj7B9c_-Lm6yOAc';
//$_W['openid'];
$data['myinfo']['isrunner'] = $member['isrunner'] == 1 ? true : false;
$data['myinfo']['avatar'] = $member['avatar'];
$data['myinfo']['nickname'] = $member['nickname'];
$data['myinfo']['realname'] = $member['realname'];
$data['myinfo']['mobile'] = $member['mobile'];

if($openid == $order['openid'] || $member['isamin'] || $member['ismanager']){
    $data['sendmobile'] = $detail['sendmobile'];
    $data['receivemobile'] = $detail['receivemobile'];
}else{
    $data['sendmobile'] = '(电话: 接单后可见)';
    $data['receivemobile'] = '(电话: 接单后可见)';
}


if($data['status'] >= 2 && $data['status'] < 5){
    
    $recive = pdo_get('imeepos_runner3_recive',array('taskid'=>$id));

    $reciver = pdo_get('imeepos_runner3_member',array('openid'=>$recive['openid'],'uniacid'=>$_W['uniacid']));
    $deta['reciver'] = $reciver;
    
    if(empty($recive)){
        $this->code = 0;
        $this->msg = '接单信息为空';
        $this->info = $data;
        return $this;
    }
    if($openid == $recive['openid']){
        $data['sendmobile'] = $detail['sendmobile'];
        $data['receivemobile'] = $detail['receivemobile'];
    }

    $data['recive'] = array();
    $data['recive']['create_time'] = date('Y-m-d H:i',$recive['create_time']);
    $data['recive']['finish_time'] = intval((time() - $recive['create_time'] ) / 60);
    // if(str_replace('小时', replace, subject)){}
    // $detail['duration'] = ;//换算成分钟
    $data['recive']['rewardTime'] = $detail['duration_value'] - $data['recive']['finish_time'];
    if($data['recive']['rewardTime'] < 0){
        $data['recive']['rewardXinyu'] = intval( ( $data['recive']['rewardTime'] / $detail['duration'] ) * $data['total']);
    }
    // else{
    //     $data['recive']['rewardXinyu'] = intval(0.5 * $data['total']);
    // }

    $data['recive']['openid'] = $recive['openid'];
    if(empty($data['pickupdate'])){
        $data['recive']['songda'] = date('m-d H:i',$recive['create_time'] + $detail['duration_value'] * 60);
        $data['recive']['duration_value'] = $detail['duration_value'];
    }else{
        $data['recive']['songda'] = date('m-d H:i',$detail['pickupdate'] + $detail['duration_value'] * 60);
        $data['recive']['duration_value'] = $detail['duration_value'];
    }
    

    
    $recive_member = pdo_get('imeepos_runner3_member',array('openid'=>$recive['openid'],'uniacid'=>$_W['uniacid']));
    
    $data['recive']['avatar'] = $recive_member['avatar'];
    $data['recive']['nickname'] = $recive_member['nickname'];
    $data['recive']['mobile'] = $recive_member['mobile'];
    $data['recive']['realname'] = $recive_member['realname'];
    $data['recive']['uid'] = $recive_member['uid'];
    $data['recive']['lat'] = $recive_member['lat'];
    $data['recive']['lng'] = $recive_member['lng'];
    $data['recive']['update_time'] = !empty($recive['update_time']) ? date('m-d H:i',$recive['update_time'] + 3*24*60*60) : false;

    //未支付金额
    $noPayMoney = 0;
    $hasPay = 0;
    $paylog = pdo_getall('imeepos_runner3_tasks_paylog',array('tasks_id'=>$id,'status'=>1));
    foreach($paylog as $log){
        $hasPay += $log['fee'];
    }
    $data['recive']['noPayMoney'] = $order['total'] - $hasPay;

    $sql = "SELECT * FROM ".tablename('imeepos_runner3_tasks_log')." WHERE taskid=:taskid ORDER BY create_time DESC ";
    $params = array(':taskid'=>$id);
    $list = pdo_fetchall($sql,$params);
    $data['logs'] = $list;
}else{
    $data['logs'] = array();
    $data['recive'] = array('avatar'=>'','nickname'=>'');
}

$sender = pdo_get('imeepos_runner3_member',array('openid'=>$order['openid'],'uniacid'=>$_W['uniacid']));
$data['sender'] = array();
$data['sender']['avatar'] = $sender['avatar'];
$data['sender']['nickname'] = $sender['nickname'];
$data['sender']['openid'] = $order['openid'];
$data['sender']['lat'] = $sender['lat'];
$data['sender']['lng'] = $sender['lng'];

// 聊天 toinfo
if($order['openid'] == $_W['openid']){
    //我的任务
    if(!empty($reciver)){
        $data['toinfo'] = $reciver;
    }else{
        //自己给自己发消息
        $data['toinfo'] = $sender;
    }
}else{
    $data['toinfo'] = $sender;
}




$this->info = $data;
return $this;