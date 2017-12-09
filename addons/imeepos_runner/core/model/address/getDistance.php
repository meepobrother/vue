<?php

global $_W;
$input = $this->__input['encrypted'];
$start = $input['start'];
$end = $input['end'];

$key = 'DXEBZ-G3IRF-2YFJO-NVYN5-4LSIQ-T4BIY';
$url = "http://apis.map.qq.com/ws/direction/v1/walking/?from={$start['lat']},{$start['lng']}&to={$end['lat']},{$end['lng']}&key={$key}";

load()->func('communication');
$resp = ihttp_get($url);
$content = $resp['content'];
$content = json_decode($content,true);
$this->info = $content;
return $this;