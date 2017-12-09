<?php
global $_W,$_GPC;
$input = $this->__input['encrypted'];

$lat = isset($input['lat']) ? $input['lat'] : $_GPC['lat'];
$lng = isset($input['lng']) ? $input['lng'] : $_GPC['lng'];

if(empty($lat) || empty($lng)){
    $this->code = 0;
    $this->msg = '定位失败';
    return $this;
}

$file = ROUTERPATH."/libs/Geohash.php";

if(file_exists($file)){
    require $file;

    $domain = new Domain_Geohash();
    $domain -> setLatitude($lat);
    $domain -> setLongitude($lng);
    $domain -> setPrecision(0.1);
    $hash = $domain -> __toString();
    $input['hash'] = $hash;

    $sql = "SELECT id,openid,avatar,nickname,realname,mobile,lat,lng FROM ".tablename('imeepos_runner3_member')." WHERE uniacid = :uniacid AND hash = :hash AND isrunner = :isrunner";
    $params = array(':uniacid'=>$_W['uniacid'],':hash'=>$hash,':isrunner'=>1);
    $list = pdo_fetchall($sql,$params);
    
    if(empty($list)){
        $this->code = 0;
        $this->msg = '附近没有跑腿人员';
        $this->info = $params;
        return $this;
    }else{
        $this->info = $list;
        return $this;
    }
}else{
    $this->code = 0;
    $this->msg = $file.'缺失';
    return $this;
}