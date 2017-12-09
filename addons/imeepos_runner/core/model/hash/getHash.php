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

    $this->info = $hash;
    return $this;
}else{
    $this->code = 0;
    $this->msg = '缺少系统文件';
    return $this;
}