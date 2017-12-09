<?php

global $_W;
$input = $this->__input['encrypted'];
$region = urlencode($input['region']);
$keyword = urlencode($input['keyword']);
$key = "DXEBZ-G3IRF-2YFJO-NVYN5-4LSIQ-T4BIY";

$action = $input['action'];


load()->func('communication');

$url = "http://apis.map.qq.com/ws/place/v1/search?boundary=region({$region},1)&keyword={$keyword}&orderby=_distance&key={$key}";

$resp = ihttp_get($url);
$content = $resp['content'];
$content = json_decode($content,true);
if($content['message'] == 'query ok'){
	$this->info = $content;
	$this->info = $content;
	return $this;
}else{
	$this->code = 0;
	$this->msg = '无结果';
	return $this;
}
