<?php

global $_W;

// ini_set("display_errors", "On");
// error_reporting(E_ALL | E_STRICT);

$setting = M('setting')->getValue('bmap.api.token');
if(empty($setting)){
	$url = 'https://aip.baidubce.com/oauth/2.0/token';
	$post_data['grant_type']       = 'client_credentials';
	$post_data['client_id']      = '1QEvefMRsGXyXnVj4DXSgeb4';
	$post_data['client_secret'] = '1WjnB7X91HmMUnx7FB1n9CnOfgx39RDr';
	$o = "";
	foreach ( $post_data as $k => $v ){
		$o.= "$k=" . urlencode( $v ). "&" ;
	}
	$post_data = substr($o,0,-1);
	$res = request_post($url, $post_data);
	$content = json_decode($res,true);
	$access_token = $content['access_token'];
	$setting = array();
	$setting['token'] = $access_token;
	$data2 = serialize($setting);
    M('setting')->update('bmap.api.token',$data2);
	$this->info = $access_token;
	return $this;
}else{
	$this->info = $setting['token'];
	return $this;
}

function request_post($url = '', $param = '') {
    if (empty($url) || empty($param)) {
        return false;
    }
    
    $postUrl = $url;
    $curlPost = $param;
    $curl = curl_init();//初始化curl
    curl_setopt($curl, CURLOPT_URL,$postUrl);//抓取指定网页
    curl_setopt($curl, CURLOPT_HEADER, 0);//设置header
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($curl);//运行curl
    curl_close($curl);
    
    return $data;
}

