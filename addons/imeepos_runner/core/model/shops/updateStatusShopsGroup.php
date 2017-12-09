<?php
global $_W;
$input = $this->__input['encrypted'];
if($input['status'] == 0){
    $input['status'] = 1;
    $this->msg = '1';
}else{
    $input['status'] = 0;
    $this->msg = '0';
}

pdo_update('imeepos_runner4_shops_group',array('status'=>$input['status']),array('id'=>$input['id']));
$this->info = $input;
return $this;
