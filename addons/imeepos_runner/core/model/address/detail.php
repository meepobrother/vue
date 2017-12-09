<?php

$input = $this->__input['encrypted'];


$id = intval($input['id']);

$item = pdo_get('imeepos_runner3_address',array('id'=>$id));

$this->info = $item;
return $item;