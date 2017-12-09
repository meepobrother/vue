<?php

global $_W;
$input = $this->__input['encrypted'];
$input['tags'] = !empty($input['tags']) ? $input['tags'] : array();
$input['tags'] = serialize($input['tags']);

pdo_update('imeepos_runner4_shops_group',$input,array('id'=>$input['id']));
$input['tags'] = unserialize($input['tags']);
$this->info = $input;
return $this;