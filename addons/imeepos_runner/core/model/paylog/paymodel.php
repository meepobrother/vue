<?php
global $_W;

$input = $this->__input['encrypted'];

$url = "http://meepo.com.cn/app/index.php?i=2&c=entry&m=imeepos_runner_plugin_pay&do=open&__do=pay.qrpay";
load()->func('communication');
$params = array();
$params['__input'] = array();
$params['__input']['encrypted'] = base64_encode(json_encode($input));
$return = ihttp_post($url,$params);

$content = $return['content'];
$content = json_decode($content,true);

if($content['code'] == 1){
	$this->info = $content['info'];
}else{
	$this->code = 0;
	$this->msg = $content['msg'];
}

return $this;