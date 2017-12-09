<?php

global $_W;
$input = $this->__input['encrypted'];
$start = $input['start'];
$end = $input['end'];

$action = $input['action'];
$key = 'DXEBZ-G3IRF-2YFJO-NVYN5-4LSIQ-T4BIY';
load()->func('communication');
if($action == 'driving'){
	$policy = $input['policy'];
	if(empty($policy)){
		//LEAST_TIME //LEAST_FEE //REAL_TRAFFIC
		$policy = 'REAL_TRAFFIC';
	}
	$url = "http://apis.map.qq.com/ws/direction/v1/driving/?from={$start['lat']},{$start['lng']}&to={$end['lat']},{$end['lng']}&policy={$policy}&key={$key}";
	$resp = ihttp_get($url);
	$content = $resp['content'];
	$content = json_decode($content,true);
	$this->info = $content;
	$this->msg = $input;
}
if($action == 'walking'){
	$url = "http://apis.map.qq.com/ws/direction/v1/walking/?from={$start['lat']},{$start['lng']}&to={$end['lat']},{$end['lng']}&key={$key}";
	$resp = ihttp_get($url);
	$content = $resp['content'];
	$content = json_decode($content,true);
	$this->info = $content;
	$this->msg = array('input'=>$input,'url'=>$url);
}

if($action == 'transit'){
	$policy = $input['policy'];
	if(empty($policy)){
		//LEAST_TIME //LEAST_FEE //REAL_TRAFFIC
		$policy = 'LEAST_TIME';
	}
	$url = "http://apis.map.qq.com/ws/direction/v1/transit/?from={$start['lat']},{$start['lng']}&to={$end['lat']},{$end['lng']}&policy={$policy}&key={$key}";
	$resp = ihttp_get($url);
	$content = $resp['content'];
	$content = json_decode($content,true);
	$this->info = $content;
	$this->msg = $input;
}

return $this;