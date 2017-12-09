<?php

$input = $this->__input;

$data = array();
$data['lat'] = isset($input['lat']) ? $input['lat'] : '';
$data['lng'] = isset($input['lng']) ? $input['lng'] : '';
$data['openid'] = isset($input['openid']) ? $input['openid'] : 'fromUser';

if(empty($data['lat']) || empty($data['lng'])){
    $this->code = 0;
    $this->msg = '定位失败';
    return $this;
}

$file = ROUTERPATH."/libs/Geohash.php";

if(file_exists($file)){
    include_once $file;
    $domain = new Domain_Geohash();
    $domain -> setLatitude($data['lat']);
    $domain -> setLongitude($data['lng']);
    $domain -> setPrecision(0.1);
    $hash = $domain -> __toString();
    $data['hash'] = $hash;

    $data['lat'] = $data['lat'] * 1000000;
    $data['lng'] = $data['lng'] * 1000000;

    $member = pdo_get('imeepos_runner3_member',array('openid'=>$data['openid']));
    if(empty($member)){
        $data['uniacid'] = $input['uniacid'];
        $data['avatar'] = $input['avatar'];
        $data['nickname'] = $input['nickname'];

        pdo_insert('imeepos_runner3_member',$data);
        $data['id'] = pdo_insertid();
        $this->info = $data;
        return $this;
    }else{

        pdo_update('imeepos_runner3_member',$data,array('id'=>$member['id']));
        $this->info = array_merge($member,$data);
        return $this;
    }
}else{
    $this->code = 0;
    $this->msg = '缺少系统文件';
    return $this;
}