<?php
global $_W;

$input = $this->__input['encrypted'];

$serverId = $input['serverId'];

load()->model('account');
$account = uni_fetch();
$a = WeAccount::create($account);

$media = array(
    'type'=>'image',
    'media_id'=>$serverId
);
$filepath = $a->downloadMedia($media);
$filepath = tomedia($filepath);

$filepath = str_replace($_W['siteroot'],'',$filepath);

$filepath = IA_ROOT.'/'.$filepath;
$filepath = realpath ( $filepath );
$appid = 'VTFNZ2qr3gmtY7ARvj6H1j';
$appkey = 'b0d0543caea4407ebe30a2b43aa316b4';

$file = array (
		"key" => $appid,
		"secret" => $appkey,
		"typeId" => "19",
		"format" => "json",
		"file" => new \CURLFile ( $filepath, 'image/jpeg' ) 
);
$curl = curl_init ();
curl_setopt ( $curl, CURLOPT_URL, "http://netocr.com/api/recog.do" );
curl_setopt ( $curl, CURLOPT_POST, true );
curl_setopt ( $curl, CURLOPT_POSTFIELDS, $file );
curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true );
$result = curl_exec ( $curl );

curl_close ( $curl );
$rs = json_decode ( $result, true );

if(!is_array($rs)){
	$this->code = 0;
	$this->info = $filepath;
	return $this;
}
if(empty($rs)){
	$this->code = 0;
	$this->info = $filepath;
	return $this;
}
$message = $rs['message'];

if($message['status'] == '-1'){
	$this->code = 0;
	$this->msg = $message['value'];
	$this->info = $rs;
	return $this;
}

$car_num = $rs['cardsinfo'][0]['items'][0]['content'];

$carfiles = pdo_get('imeepos_repair_server_carfiles',array('car_num'=>$input['car_num']));

if(empty($carfiles)){
	$carfiles = array();
	$carfiles['uniacid'] = $_W['uniacid'];
	$carfiles['car_num'] = $car_num;
	$carfiles['create_time'] = time();
	$carfiles['father'] = $_W['openid'];
	pdo_insert('imeepos_repair_server_carfiles',$data);
	$carfiles['id'] = pdo_insertid();
}

$this->info = $carfiles['id'];
return $this;
