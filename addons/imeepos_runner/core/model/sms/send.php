<?php
global $_W;
load()->func('communication');
// ini_set("display_errors", "On");
// error_reporting(E_ALL | E_STRICT);

$input = $this->__input['encrypted'];
$input['mobile'] = isset($input['mobile']) ? $input['mobile'] : '';
$moile = trim($input['mobile']);
$code = random(4,true);
$member = pdo_get('imeepos_runner3_member',array('openid'=>$_W['openid'],'uniacid'=>$_W['uniacid']));

$nickname = isset($member['nickname']) ? $member['nickname'] : '会员';

if(empty($nickname)){
	$nickname = '会员';
}

$setting = M('setting')->getValue('system.code.tpl');
if(empty($setting['signname'])){
	$setting['signname'] = '跑腿代办';
}

if(empty($setting['appkey'])){
	$setting['appkey'] = '23364909';
}

if(empty($setting['appsecret'])){
	$setting['secret'] = '10e162a69e62e948cb2ba03ce414d843';
}else{
	$setting['secret'] = $setting['appsecret'];
}

if(empty($setting['code_tpl'])){
	$setting['send_tpl'] = 'SMS_62175088';
}else{
	$setting['send_tpl'] = $setting['code_tpl'];
}
if(empty($setting['recive_tpl'])){
	$setting['recive_tpl'] = 'SMS_10220460';
}

$input['action'] = isset($input['action']) ? $input['action'] : 'send.code';

if($input['action'] == 'recive'){
	$tpl = $setting['recive_tpl'];
}else{
	$tpl = $setting['send_tpl'];
}

$mobile = $input['mobile'];
if(empty($mobile)){
    $this->code = 0;
    $this->msg = '手机号码为空';
    return $this;
}

if($setting['type'] == '2'){
	$return = sendSmsJuhe($setting,$code,$mobile,$member);
	if($return){
		$data = array();
		//imeepos_runner3_code
		$data['mobile'] = trim($input['mobile']);
		$data['code'] = $code;
		$data['time'] = time();
		$data['uniacid'] = $_W['uniacid'];
		$data['openid'] = $_W['openid'];
		$data['content'] = '';

		pdo_insert('imeepos_runner3_code',$data);
		$id = pdo_insertid();

		$this->info = $id;

		return $this;
	}else{
		$this->code = 0;
		$this->msg = '发送失败';
		return $this;
	}
}

$member['nickname'] = cutstr($member['nickname'],5,true);

// $setting['code_title'] = !empty($setting['code_title']) ? $setting['code_title'] : 'code';
// $setting['nickname_title'] = !empty($setting['nickname_title']) ? $setting['nickname_title'] : 'nickname';

$content = array();
if($input['action'] == 'recive'){
	if(!empty($setting['recive_code_title'])){
		$content[$setting['recive_code_title']] = $code;
	}
	if(!empty($setting['recive_nickname_title'])){
		$content[$setting['recive_nickname_title']] = $member['nickname'];
	}
	if(!empty($setting['recive_product_title'])){
		$content['product'] = $setting['recive_product_title'];
	}
}else{
	if(!empty($setting['code_title'])){
		$content[$setting['code_title']] = $code;
	}
	if(!empty($setting['nickname_title'])){
		$content[$setting['nickname_title']] = $member['nickname'];
	}
	if(!empty($setting['product_title'])){
		$content['product'] = $setting['product_title'];
	}
}

$this->info = $content;


if($setting['type'] == 3){
	//新版阿里大鱼
	date_default_timezone_set("GMT");
	$post = array(
		'PhoneNumbers' => trim($input['mobile']),
		'SignName' => $setting['signname'],
		'TemplateCode' => trim($tpl),
		'TemplateParam' => json_encode($content),
		'OutId' => '',
		'RegionId' => 'cn-hangzhou',
		'AccessKeyId' => $setting['appkey'],
		'Format' => 'json',
		'SignatureMethod' => 'HMAC-SHA1',
		'SignatureVersion' => '1.0',
		'SignatureNonce' => uniqid(),
		'Timestamp' => date('Y-m-d\TH:i:s\Z'),
		'Action' => 'SendSms',
		'Version' => '2017-05-25',
	);
	ksort($post);
	$str = '';
	foreach ($post as $key => $value){
		$str .= '&' . percentEncode($key) . '=' . percentEncode($value);
	}
	$stringToSign = 'GET' . '&%2F&' . percentencode(substr($str, 1));
	$signature = base64_encode(hash_hmac('sha1', $stringToSign, "{$setting['secret']}&", true));
	$post['Signature'] = $signature;

	$url = 'http://dysmsapi.aliyuncs.com/?' . http_build_query($post);
	$result = ihttp_get($url);
	if(is_error($result)) {
		return $result;
	}
	$result = @json_decode($result['content'], true);
	if($result['Code'] != 'OK') {
		$this->code = 0;
		$this->msg = $result['Message'];
		$this->info = $content;
		return $this;
	}else{
		$data = array();
		//imeepos_runner3_code
		$data['mobile'] = $input['mobile'];
		$data['code'] = $code;
		$data['time'] = time();
		$data['uniacid'] = $_W['uniacid'];
		$data['openid'] = $_W['openid'];
		$data['content'] = '';

		pdo_insert('imeepos_runner3_code',$data);
		$id = pdo_insertid();
		$this->info = $id;

		return $this;
	}
}

if($setting['type'] == 0){
	$post = array(
		'method' => 'alibaba.aliqin.fc.sms.num.send',
		'app_key' => $setting['appkey'],
		'timestamp' => date('Y-m-d H:i:s'),
		'format' => 'json',
		'v' => '2.0',
		'sign_method' => 'md5',
		'sms_type' => 'normal',
		'sms_free_sign_name' => $setting['signname'],
		'rec_num' => trim($input['mobile']),
		'sms_template_code' => trim($tpl),
		'sms_param' => json_encode($content)
	);

	ksort($post);
	$str = '';
	foreach($post as $key => $val) {
		$str .= $key.$val;
	}
	$secret = $setting['secret'];
	$post['sign'] = strtoupper(md5($secret . $str . $secret));
	$query = '';
	foreach($post as $key => $val) {
		$query .= "{$key}=" . urlencode($val) . "&";
	}
	$query = substr($query, 0, -1);
	$url = 'http://gw.api.taobao.com/router/rest?' . $query;
	$result = ihttp_get($url);
	if(is_error($result)) {
		return $result;
	}
	$result = @json_decode($result['content'], true);
	if(!empty($result['error_response'])) {
		$this->code = 0;
		$this->msg = $result['error_response']['sub_msg'];
		return $this;
	}else{
		$data = array();
		//imeepos_runner3_code
		$data['mobile'] = $input['mobile'];
		$data['code'] = $code;
		$data['time'] = time();
		$data['uniacid'] = $_W['uniacid'];
		$data['openid'] = $_W['openid'];
		$data['content'] = '';

		pdo_insert('imeepos_runner3_code',$data);
		$id = pdo_insertid();

		$this->info = $id;
		return $this;
	}
}


$lib = ROUTERPATH.'/libs/aliSms/TopSdk.php';

if(file_exists($lib)){
	include $lib;
    
    //发送验证码
    $c = new TopClient;
	$c->appkey = $setting['appkey'];
	$c->secretKey = $setting['secret'];
	$req = new AlibabaAliqinFcSmsNumSendRequest;
	$req->setExtend("123456");
	$req->setSmsType("normal");
	$req->setSmsFreeSignName($setting['signname']);
	$member['nickname'] = cutstr($member['nickname'],5,true);
	if(empty($setting['code_title'])){
		$setting['code_title'] = 'code';
	}
	if(empty($setting['nickname_title'])){
		$setting['nickname_title'] = 'nickname';
	}
	if(empty($setting['product_title'])){
		$req->setSmsParam("{\"".$setting['code_title']."\":\"".$code."\",\"".$setting['nickname_title']."\":\"".$member['nickname']."\"}");
	}else{
		$req->setSmsParam("{ \" ".$setting['code_title']." \":\" ".$code." \",\" ".$setting['nickname_title']." \":\" ".$member['nickname']." \",\" "."\"product\":\"".$setting['product_title']."}");
	}
	
	$req->setRecNum($mobile);
	$req->setSmsTemplateCode($tpl);
	$resp = $c->execute($req);
	$resp = object_array($resp);
	
	// $resp = json_decode($resp,true);
	if($resp['result']['success'] == true){
		$data = array();
		//imeepos_runner3_code
		$data['mobile'] = $input['mobile'];
		$data['code'] = $code;
		$data['time'] = time();
		$data['uniacid'] = $_W['uniacid'];
		$data['openid'] = $_W['openid'];
		$data['content'] = '';

		pdo_insert('imeepos_runner3_code',$data);
		$id = pdo_insertid();

		$this->info = $id;

		return $this;
	}else{
		$this->code = 0;
		$this->msg = $resp['sub_msg'];
		$this->info = $resp;
		return $this;
	}
	$this->msg = $resp;
}else{
    $this->code = 0;
    $this->msg = '阿里大鱼sdk文件不存在';
    return $this;
}


function sendCode($type, $mobile, $content, $sid = 0){
	if($config_sms['set']['version'] == 2) {
		date_default_timezone_set("GMT");
		$post = array(
			'PhoneNumbers' => $mobile,
			'SignName' => $config_sms['set']['sign'],
			'TemplateCode' => trim($type),
			'TemplateParam' => json_encode($content),
			'OutId' => '',
			'RegionId' => 'cn-hangzhou',
			'AccessKeyId' => $config_sms['set']['key'],
			'Format' => 'json',
			'SignatureMethod' => 'HMAC-SHA1',
			'SignatureVersion' => '1.0',
			'SignatureNonce' => uniqid(),
			'Timestamp' => date('Y-m-d\TH:i:s\Z'),
			'Action' => 'SendSms',
			'Version' => '2017-05-25',
		);
		ksort($post);
		$str = '';
		foreach ($post as $key => $value){
			$str .= '&' . percentEncode($key) . '=' . percentEncode($value);
		}
		$stringToSign = 'GET' . '&%2F&' . percentencode(substr($str, 1));
		$signature = base64_encode(hash_hmac('sha1', $stringToSign, "{$config_sms['set']['secret']}&", true));
		$post['Signature'] = $signature;

		$url = 'http://dysmsapi.aliyuncs.com/?' . http_build_query($post);
		$result = ihttp_get($url);
		if(is_error($result)) {
			return $result;
		}
		$result = @json_decode($result['content'], true);
		if($result['Code'] != 'OK') {
			return error(-1, $result['Message']);
		}
	} else {
		$post = array(
			'method' => 'alibaba.aliqin.fc.sms.num.send',
			'app_key' => $config_sms['set']['key'],
			'timestamp' => date('Y-m-d H:i:s'),
			'format' => 'json',
			'v' => '2.0',
			'sign_method' => 'md5',
			'sms_type' => 'normal',
			'sms_free_sign_name' => $config_sms['set']['sign'],
			'rec_num' => $mobile,
			'sms_template_code' => trim($type),
			'sms_param' => json_encode($content)
		);

		ksort($post);
		$str = '';
		foreach($post as $key => $val) {
			$str .= $key.$val;
		}
		$secret = $config_sms['set']['secret'];
		$post['sign'] = strtoupper(md5($secret . $str . $secret));
		$query = '';
		foreach($post as $key => $val) {
			$query .= "{$key}=" . urlencode($val) . "&";
		}
		$query = substr($query, 0, -1);
		$url = 'http://gw.api.taobao.com/router/rest?' . $query;
		$result = ihttp_get($url);
		if(is_error($result)) {
			return $result;
		}
		$result = @json_decode($result['content'], true);
		if(!empty($result['error_response'])) {
			if(isset($result['error_response']['sub_code'])) {
				$msg = sms_error_code($result['error_response']['sub_code']);
				if(empty($msg)) {
					$msg['msg'] = $result['error_response']['sub_msg'];
				}
			} else {
				$msg['msg'] = $result['error_response']['msg'];
			}
			return error(-1, $msg['msg']);
		}
	}
}


function object_array($array) {  
    if(is_object($array)) {  
        $array = (array)$array;  
     } if(is_array($array)) {  
         foreach($array as $key=>$value) {  
             $array[$key] = object_array($value);  
         }  
     }  
     return $array;  
}

function sendSmsJuhe($setting = array(),$code = '',$mobile = '',$member = array(),$isRecive = false){
	$sendUrl = 'http://v.juhe.cn/sms/send';
	$smsConf = array(
		'key'   => $setting['juhe_appkey'],
		'mobile'    => $mobile,
		'tpl_id'    => $isRecive ? $setting['juhe_recive_tpl'] : $setting['juhe_code_tpl'],
		'tpl_value' =>'#nickname#='.$member['nickname'].'&#code#='.$code
	);
	$content = juhecurl($sendUrl,$smsConf,1);
	if($content){
		$result = json_decode($content,true);
		$error_code = $result['error_code'];
		if($error_code == 0){
			return true;
		}else{
			$msg = $result['reason'];
			return error(-1,$msg);
		}
	}else{
		return false;
	}
	return true;
}

function juhecurl($url,$params=false,$ispost=0){
	$httpInfo = array();
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
	curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
	curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
	if( $ispost )
	{
		curl_setopt( $ch , CURLOPT_POST , true );
		curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
		curl_setopt( $ch , CURLOPT_URL , $url );
	}else{
		if($params){
			curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
		}else{
			curl_setopt( $ch , CURLOPT_URL , $url);
		}
	}
	$response = curl_exec( $ch );
	if ($response === FALSE) {
		//echo "cURL Error: " . curl_error($ch);
		return false;
	}
	$httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
	$httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
	curl_close( $ch );
	return $response;
}


function sms_error_code($code = ''){
	$msgs = array(
		'isv.OUT_OF_SERVICE' => array(
			'msg' => '业务停机',
			'handle' => '登陆www.alidayu.com充值',
		),
		'isv.PRODUCT_UNSUBSCRIBE' => array(
			'msg' => '产品服务未开通',
			'handle' => '登陆www.alidayu.com开通相应的产品服务',
		),
		'isv.ACCOUNT_NOT_EXISTS' => array(
			'msg' => '账户信息不存在',
			'handle' => '登陆www.alidayu.com完成入驻',
		),
		'isv.ACCOUNT_ABNORMAL' => array(
			'msg' => '账户信息异常',
			'handle' => '联系技术支持',
		),

		'isv.SMS_TEMPLATE_ILLEGAL' => array(
			'msg' => '模板不合法',
			'handle' => '登陆www.alidayu.com查询审核通过短信模板使用',
		),

		'isv.SMS_SIGNATURE_ILLEGAL' => array(
			'msg' => '签名不合法',
			'handle' => '登陆www.alidayu.com查询审核通过的签名使用',
		),
		'isv.MOBILE_NUMBER_ILLEGAL' => array(
			'msg' => '手机号码格式错误',
			'handle' => '使用合法的手机号码',
		),
		'isv.MOBILE_COUNT_OVER_LIMIT' => array(
			'msg' => '手机号码数量超过限制',
			'handle' => '批量发送，手机号码以英文逗号分隔，不超过200个号码',
		),

		'isv.TEMPLATE_MISSING_PARAMETERS' => array(
			'msg' => '短信模板变量缺少参数',
			'handle' => '确认短信模板中变量个数，变量名，检查传参是否遗漏',
		),
		'isv.INVALID_PARAMETERS' => array(
			'msg' => '参数异常',
			'handle' => '检查参数是否合法',
		),
		'isv.BUSINESS_LIMIT_CONTROL' => array(
			'msg' => '触发业务流控限制',
			'handle' => '短信验证码，使用同一个签名，对同一个手机号码发送短信验证码，允许每分钟1条，累计每小时7条。 短信通知，使用同一签名、同一模板，对同一手机号发送短信通知，允许每天50条（自然日）',
		),

		'isv.INVALID_JSON_PARAM' => array(
			'msg' => '触发业务流控限制',
			'handle' => 'JSON参数不合法	JSON参数接受字符串值',
		),
	);
	return $msgs[$code];
}


function percentEncode($str) {
	$result = urlencode($str);
	$result = preg_replace('/\+/', '%20', $result);
	$result = preg_replace('/\*/', '%2A', $result);
	$result = preg_replace('/%7E/', '~', $result);
	return $result;
}