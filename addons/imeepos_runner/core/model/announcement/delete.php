<?php
$input = $this->__input;


$id = intval($input['id']);
if(!empty($id)){
    pdo_delete('imeepos_runner3_announcement',array('id'=>$id));
}

$this->info = $input;
return $this;