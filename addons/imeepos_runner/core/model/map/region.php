<?php

global $_W;
$input = $this->__input['encrypted'];
$action = $input['action'];
$key = "DXEBZ-G3IRF-2YFJO-NVYN5-4LSIQ-T4BIY";
load()->func('communication');

if($action == 'list'){
	$url = "http://apis.map.qq.com/ws/district/v1/list?key={$key}";

	$resp = ihttp_get($url);
	$content = $resp['content'];
	$content = json_decode($content,true);
	$this->info = $content;
	return $this;
}

if($action == 'getchildren'){
	$id = $input['id'];
	$url = "http://apis.map.qq.com/ws/district/v1/getchildren?&id={$id}&key={$key}";
	$resp = ihttp_get($url);
	$content = $resp['content'];
	$content = json_decode($content,true);
	$this->info = $content;
	return $this;
}

if($action == 'search'){
	$keyword = $input['keyword'];
	$url = "http://apis.map.qq.com/ws/district/v1/search?&keyword={$keyword}&key={$key}";
	$resp = ihttp_get($url);
	$content = $resp['content'];
	$content = json_decode($content,true);
	$this->info = $content;
	return $this;
}

return $this;