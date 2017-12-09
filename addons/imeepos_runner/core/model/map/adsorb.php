<?php

global $_W;
$input = $this->__input['encrypted'];
$key = "DXEBZ-G3IRF-2YFJO-NVYN5-4LSIQ-T4BIY";	
load()->func('communication');

$action = $input['action'];

if($action == 'location'){
	$location = $input['location'];
	$radius = $input['radius'];
	$url = "http://apis.map.qq.com/ws/streetview/v1/getpano?location={$location['lat']},{$location['lng']}&radius={$radius}&key={$key}";
	$resp = ihttp_get($url);
	$content = $resp['content'];
	$content = json_decode($content,true);
	$this->info = $content;
	return $this;
}

if($action == 'id'){
	$id = $input['id'];
	$radius = $input['radius'];
	$url = "http://apis.map.qq.com/ws/streetview/v1/getpano?id={$id}&radius={$radius}&key={$key}";
	$resp = ihttp_get($url);
	$content = $resp['content'];
	$content = json_decode($content,true);
	$this->info = $content;
	return $this;
}

if($action == 'poi'){
	$poi = $input['poi'];
	$radius = $input['radius'];
	$url = "http://apis.map.qq.com/ws/streetview/v1/getpano?poi={$poi}&radius={$radius}&key={$key}";
	$resp = ihttp_get($url);
	$content = $resp['content'];
	$content = json_decode($content,true);
	$this->info = $content;
	return $this;
}

return $this;