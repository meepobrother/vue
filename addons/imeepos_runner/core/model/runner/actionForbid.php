<?php

global $_W,$_GPC;

$input = $this->__input['encrypted'];
$id = intval($input['id']);
$id = $id > 0 ? $id : 0;

if(pdo_update('imeepos_runner3_member',array('forbid'=>1,'forbid_time'=>time()),array('id'=>$id))){
    $this->msg = '禁封成功';
    $this->code = 1;
}else{
    $this->msg = '禁封失败';
    $this->code = 0;
}

$this->info = $input;
return $this;

