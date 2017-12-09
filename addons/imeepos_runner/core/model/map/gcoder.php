<?php

global $_W;
$input = $this->__input['encrypted'];
$location = $input['location'];
$key = "DXEBZ-G3IRF-2YFJO-NVYN5-4LSIQ-T4BIY";


load()->func('communication');

$url = "http://apis.map.qq.com/ws/geocoder/v1/?location={$location['lat']},{$location['lng']}&key={$key}&get_poi=1";

$resp = ihttp_get($url);
$content = $resp['content'];
$content = json_decode($content,true);
$this->info = $content;
return $this;