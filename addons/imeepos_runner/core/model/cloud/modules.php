<?php

define('MODULE_ROOT', str_replace("\\", '/', dirname(__FILE__)));
define('UPDATE_URL','http://meepo.com.cn/addons/imeepos_oauth2/oauth/');

$input = $this->__input['encrypted'];

$op =empty($input['op'])? 'display' : $input['op'];

load()->func('cache');
$content = cache_read('meepo.cloud.modules.modules');

if($content['status'] ==1){
	$menu = $content['data'];
	$this->info = $menu;
    $this->msg = $content;
	return $this;
}else{
    load()->func('file');
    
    load()->func('db');
    load()->model('setting');
    load()->func('communication');

    $oauth = array();
    $oauth['ip'] = gethostbyname($_SERVER['SERVER_ADDR']);
    $oauth['domain'] = $_SERVER['HTTP_HOST'];
    $setting = setting_load('site');
    $oauth['id'] =isset($setting['site']['key'])? $setting['site']['key'] : '1';

    $resp =ihttp_post(UPDATE_URL.'menus_new.php',array('ip'=>$oauth['ip'], 'id'=>$oauth['id'],'domain'=>$oauth['domain']));
    $content = json_decode($resp['content'],true);
    cache_write('meepo.cloud.modules.modules',$content);
    $this->code = 0;
    $this->info = $content;
    $this->msg = 'cloud';
	return $this;
}

return $this;
//便利文件夹
function my_scandir($dir) {
    global $my_scenfiles;
    if ($handle = opendir($dir)) {
        while (($file = readdir($handle)) !== false) {
            if ($file != ".." && $file != ".") {
                if (is_dir($dir . "/" . $file)) {
                    my_scandir($dir . "/" . $file);
                } else {
                    $my_scenfiles[] = $dir . "/" . $file;
                }
            }
        }
        closedir($handle);
    }
}

/*
 * 结构转数组
 * */
function cloud_object_array($array) {
    if(is_object($array)) {
        $array = (array)$array;
    } if(is_array($array)) {
        foreach($array as $key=>$value) {
            $array[$key] = cloud_object_array($value);
        }
    }
    return $array;
}