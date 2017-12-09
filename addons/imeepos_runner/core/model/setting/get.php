<?php

global $_W,$_GPC;

$input = $this->__input['encrypted'];
$code = trim($input['code']);

$key = 'cache.runner.'.$code.'.'.$_W['uniacid'];

load()->func('cache');
$setting = $content = cache_read($key);

if(empty($setting)){
	$sql = "ALTER TABLE ".tablename('imeepos_runner3_setting')." MODIFY COLUMN `code` varchar(640) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT ''";
	pdo_query($sql);
	if($code == 'bmap.api.token'){
		$setting = M('setting')->getValue('bmap.api.token');
		if(empty($setting)){
			$url = 'https://aip.baidubce.com/oauth/2.0/token';
			$post_data['grant_type']       = 'client_credentials';
			$post_data['client_id']      = 'ZuxdALhVDzBcFqSbvvI7k8da';
			$post_data['client_secret'] = 'LgTqdGF8xognuBVF18je7l01PABGGeco';
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

		    cache_write('cloud.modules',$access_token);
		    $this->info = $access_token;
			return $this;
		}else{
			cache_write('cloud.modules',$setting['token']);
			$this->info = $setting['token'];
			return $this;
		}
	}
	$ext = strpos($code,'imeepos_runner');
	if($ext === false){
		
	}else{
		if(!empty($code)){
			$setting = M('setting')->getSystem($code);
			if(empty($setting)){
				$setting = M('setting')->check($code);
			}
			if(empty($setting)){
				$this->code = 0;
				$this->info = $setting;
				return $this;
			}
			cache_write('cloud.modules',$setting);
			if($code == 'setting.tixian.items'){
				$this->info = $setting['items'];
				$this->msg = $code;
				return $this;
			}
			$this->info = $setting;
			$this->msg = $key;
			return $this;
		}
	}
	if(!empty($code)){
	    $setting = M('setting')->getValue($code);
	    if(empty($setting)){
	    	$this->code = 0;
	    	return $this;
		}
		
		cache_write('cloud.modules',$setting);
		if($code == 'setting.tixian.items'){
			$this->info = $setting['items'];
			$this->msg = $code;
			return $this;
		}
	    $this->info = $setting;
		$this->msg = $input;
		return $this;
	}
}
if($code == 'setting.tixian.items'){
	$this->info = $setting['items'];
	$this->msg = $code;
	return $this;
}
$this->info = $setting;
$this->msg = $key;
return $this;

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
