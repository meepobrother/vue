<?php
$input = $this->__input['encrypted'];
$serverId = $input['serverId'];

// 上传语音

//上传到服务器
load()->model('account');
$account = uni_fetch();
$a = WeAccount::create($account);

$media = array(
    'type'=>'voice',
    'media_id'=>$serverId
);
$file = $a->downloadMedia($media);
$file = tomedia($file);

//如果过期 从新上传
// $return = $a->uploadMedia($path,'audios');
// $media_id = $return['media_id'];


//上传七牛

//音频转码

//转换格式

$this->info = $file;
return $this;
