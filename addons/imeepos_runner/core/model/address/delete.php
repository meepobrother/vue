<?php
$input = $this->__input['encrypted'];


$id = intval($input['id']);
if(!empty($id)){
    pdo_delete('imeepos_runner3_address',array('id'=>$id));
}

$this->info = $input;
return $this;