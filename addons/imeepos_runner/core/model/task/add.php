<?php

$input = $this->__input;

$data = array();
$data['lat'] = $input['lat'];//精度
$data['lng'] = $input['lng'];//维度
$data['openid'] = $input['openid'];//粉丝
$data['desc'] = $input['desc'];//任务简介
$data['uniacid'] = $input['uniacid'];//公众号
$data['media_id'] = $input['media_id']; //多媒体
$data['total'] = $input['total']; //总费用
$data['small_money'] = abs(floatval($input['small_money'])); //小费
$data['limit_time'] = $input['limit_time']; //限制时间
$data['type'] = $input['type'];// 任务类型
$data['code'] = $input['code']; //收货吗
$data['qrcode'] = $input['qrcode']; //确认收获码
$data['message'] = $input['message'];//留言信息
$data['status'] = 0;
$data['create_time'] = time();
$data['update_time'] = time();

$file = ROUTERPATH."/libs/Geohash.php";
if(file_exists($file)){
    include_once $file;

    $domain = new Domain_Geohash();
    $domain -> setLatitude($data['lat']);
    $domain -> setLongitude($data['lng']);
    $domain -> setPrecision(0.1);
    $hash = $domain -> __toString();
    $data['hash'] = $hash;
    
    pdo_insert('imeepos_runner3_tasks',$data);
    $data['id'] = pdo_insertid();
}else{
    $this->code = 0;
    $this->msg = '缺少系统文件';
    return $this;
}