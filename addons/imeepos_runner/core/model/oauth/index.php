<?php
include '../framework/bootstrap.inc.php';
header("Access-Control-Allow-Origin:*");
$input = $_GPC['__input'];
$encrypted = $input['encrypted'];
$key = $input['key'];

$json=AesJs::decrypt($encrypted,md5(strtoupper($key)));
$json = json_decode($json,true);

$data = array();
$data['code'] = 1;
$data['msg'] = 'success';
$data['info'] = array();

if($json['action'] == 'v10.getRole'){
	// $data['info'] = array('role'=>'admin');
	$d = array();
	$d['role'] = 'manager';
	$d['menus'] = array(
		array('icon'=>'hot','link'=>array('index'),'title'=>'功能模块','active'=>0),
		array('icon'=>'hot','link'=>array('setting'),'title'=>'系统设置','active'=>0),
		array('icon'=>'hot','link'=>array('money'),'title'=>'运营管理','active'=>0)
	);
	$data['info'] = $d;
}

if($json['action'] == 'v10.getVersion'){
	// $data['info'] = array('role'=>'admin');
	$data['info'] = 'free';
}

if($json['action'] == 'v10.getLink'){
    // $data['info'] = array('role'=>'admin');
    $data['info'] = 'free';
}

if($json['action'] == 'v10.getModule'){
    // $data['info'] = array('role'=>'admin');
    $data['info'] = 'free';
}

if($json['action'] == 'v10.getMusic'){
    // $data['info'] = array('role'=>'admin');
    $data['info'] = 'free';
}

if($json['action'] == 'v10.getPicture'){
    // $data['info'] = array('role'=>'admin');
    $data['info'] = 'free';
}

if($json['action'] == 'v10.getIcon'){
    // $data['info'] = array('role'=>'admin');
    $data['info'] = 'free';
}

if($json['action'] == 'v10.getModelDetail'){
    // $data['info'] = array('role'=>'admin');
    $data['info'] = 'free';
}

if($json['action'] == 'v10.qrBind'){
	
}

if($json['action'] == 'v10.qrBindAdmin'){
	
}

if($json['action'] == 'bindMobile'){

}

if($json['login'] == 'login'){

}

if($json['sms']){
    //获取登陆验证码
}

die(json_encode($data));
	
class AesJs
{
    /**向量
     * @var string
     */
    private static $iv = "1234567890123412";//16位
    /**
     * 默认秘钥
     */
    const KEY = '1111111111111123';//16位

    public static function init($iv = '')
    {
        self::$iv = $iv;
    }

    /**
     * 加密字符串
     * @param string $data 字符串
     * @param string $key  加密key
     * @return string
     */
    public static function encrypt($data = '', $key = self::KEY)
    {
        $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, self::$iv);
        return base64_encode($encrypted);
    }

    /**
     * 解密字符串
     * @param string $data 字符串
     * @param string $key  加密key
     * @return string
     */
    public static function decrypt($data = '', $key = self::KEY)
    {
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($data), MCRYPT_MODE_CBC, self::$iv);
        return rtrim($decrypted, "\0");
    }
}