<?php

$input = $this->__input;

$lat = $input['lat'];
$lng = $input['lng'];

if(empty($lat) || empty($lng)){
    $this->code = 0;
    $this->msg = '定位失败';
    return $this;
}

$file = ROUTERPATH."/libs/Geohash.php";
if(file_exists($file)){
    include_once $file;

    $domain = new Domain_Geohash();
    $domain -> setLatitude($lat);
    $domain -> setLongitude($lng);
    $domain -> setPrecision(0.1);
    $hash = $domain -> __toString();

    $sql = "SELECT * FROM ".tablename('imeepos_runner3_member')." WHERE uniacid = :uniacid AND hash = :hash AND isrunner != 1";
    $params = array(':uniacid'=>$input['uniacid'],':hash'=>$hash);
    $list = pdo_fetchall($sql,$params);
    if(empty($list)){
        $this->code = 0;
        $this->msg = '附近没有粉丝';
        $this->info = $hash;
        return $this;
    }else{
        $this->info = $list;
        return $this;
    }
}else{
    $this->code = 0;
    $this->msg = '缺少系统文件';
    return $this;
}