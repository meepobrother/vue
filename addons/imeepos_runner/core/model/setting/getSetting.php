<?php
global $_W;
$input = $this->__input['encrypted'];
$code = trim($input['code']);
$key = 'cache.setting.'.$code.'.'.$_W['uniacid'];
load()->func('cache');
$setting = $content = cache_read($key);
if(empty($setting)){
    $setting = M('setting')->getValue($code);
    if(empty($setting)){
        $this->code = 0;
        return $this;
    }
    cache_write($key,$setting);
    $this->info = $setting;
    $this->msg = $input;
}else{
    $this->info = $setting;
    $this->msg = 'from cache';
}
return $this;