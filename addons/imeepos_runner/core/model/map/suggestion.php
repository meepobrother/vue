<?php

global $_W;
$input = $this->__input['encrypted'];
$region = $input['region'];
$keyword = $input['keyword'];
$key = "DXEBZ-G3IRF-2YFJO-NVYN5-4LSIQ-T4BIY";


load()->func('communication');

$url = "http://apis.map.qq.com/ws/place/v1/suggestion/?region={$region}&keyword={$keyword}&key={$key}";

$resp = ihttp_get($url);
$content = $resp['content'];
$content = json_decode($content,true);
$this->info = $content;
return $this;