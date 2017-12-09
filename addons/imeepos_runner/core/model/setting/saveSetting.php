<?php
global $_W;
$input = $this->__input['encrypted'];
$data = $input['data'];
$code = trim($input['code']);
$key = 'cache.setting.'.$code.'.'.$_W['uniacid'];

if(!empty($code)){
    $data2 = serialize($data);
    M('setting')->update($code,$data2);
    cache_delete($key);
}
$this->info = $data;
$this->msg = $input;
return $this;