<?php
$input = $this->__input;


$id = intval($input['id']);
if(!empty($id)){
    pdo_delete('imeepos_runner3_category',array('id'=>$id));
    pdo_delete('imeepos_runner3_category',array('fid'=>$id));
}

$this->info = $input;
return $this;