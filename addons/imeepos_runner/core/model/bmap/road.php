<?php

global $_W;
load()->func('communication');

$input = $this->__input['encrypted'];
$start = $input['start'];
$end = $input['end'];
$key = '9fec152447ea0bae2b9bf8214fd06cdf';
$url = "http://api.map.baidu.com/geoconv/v1/?";
$params = "coords={$start['lat']},{$start['lng']};{$end['lat']},{$end['lng']}&from=3&to=5&output=json&ak={$key}";
$url = $url.$params;
$resp = ihttp_get($url);
$content = $resp['content'];
$content = json_decode($content,true);
// print_r($content);
$result = $content['result'];
$start['lat'] = $result[0]['x'];
$start['lng'] = $result[0]['y'];

$end['lat'] = $result[1]['x'];
$end['lng'] = $result[1]['y'];

$action = $input['action'];
$key = '1QEvefMRsGXyXnVj4DXSgeb4';
$url = "http://api.map.baidu.com/direction/v1?";
$params = "mode=riding&origin={$start['lat']},{$start['lng']}&destination={$end['lat']},{$end['lng']}&origin_region={$action}&destination_region={$action}&output=json&ak={$key}&tactics=10";
// $params = urlencode($params);
$url = $url.$params;
$resp = ihttp_get($url);
$content = $resp['content'];
$content = json_decode($content,true);
$this->info = $content;
$this->msg = $input;

return $this;