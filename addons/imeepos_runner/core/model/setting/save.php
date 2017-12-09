<?php

global $_W,$_GPC;

$input = $this->__input['encrypted'];
$data = $input['data'];
$code = trim($input['code']);
load()->func('cache');

$key = 'cache.runner.'.$code.'.'.$_W['uniacid'];

$openid = $_W['openid'];
$member = pdo_get('imeepos_runner3_member',array('openid'=>$openid,'uniacid'=>$_W['uniacid']));

$act = $input['act'];


if($member['ismanager'] == 1 || $member['isadmin'] == 1){
	if($act == 'base64image'){
		//处理base64格式的图片
		load()->func('file');
		foreach($data as &$da){
			$img = $da['image'];
			if(strstr($img, 'base64,')){
				$base64_body = substr(strstr($img,','),1);
				$imageData= base64_decode($base64_body );
				preg_match('/data:image\/(.*?);base64,/', $img, $match);
				if($match[1]){
					$filename = $_W['uniacid'].'/'.date('Y/m/') . random(8,false).time(). '.'.$match[1];
					$pathinfo = pathinfo($filename);
					if (in_array(strtolower($pathinfo['extension']), array('mp4'))) {
						$filename = 'videos/' . $filename;
					} elseif (in_array(strtolower($pathinfo['extension']), array('amr', 'mp3', 'wma', 'wmv'))) {
						$filename = 'audios/' . $filename;
					} else {
						$filename = 'images/' . $filename;
					}
					
					file_write($filename, $imageData);
					file_remote_upload($filename);
					// file_image_thumb($filename,$filename,'1');s
					$da['image'] = tomedia($filename);
				}
			}
		}
		unset($da);
	}
	if(!empty($code)){
	    $data2 = serialize($data);
	    M('setting')->update($code,$data2);
		cache_delete($key);
	}
	$this->info = $data;
	$this->msg = $input;
	return $this;
}

$this->code = 0;
$this->msg = '权限不足';

return $this;
