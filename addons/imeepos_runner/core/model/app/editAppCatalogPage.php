<?php
global $_W;
$input = $this->__input['encrypted'];
$table = "imeepos_runner4_app_catalog_pages";

$data = array();
$data['title'] = $input['title'];
$data['desc'] = $input['desc'];

$data['cata_id'] = $input['cata_id'];
$data['app_id'] = $input['app_id'];

$data['header'] = serialize($input['header']);
$data['body'] = serialize($input['body']);
$data['footer'] = serialize($input['footer']);
$data['menu'] = serialize($input['menu']);
$data['kefu'] = serialize($input['kefu']);
$data['pageType'] = $input['pageType'];


$data['html_content'] = $input['html_content'];


$id = intval($input['id']);
if($id){
    pdo_update($table,$data,array('id'=>$id));
    $data['id'] = $id;
}else{
    pdo_insert($table,$data);
    $data['id'] = pdo_insertid();
}
$data['header'] = unserialize($data['header']);
$data['body'] = unserialize($data['body']);
$data['footer'] = unserialize($data['footer']);
$data['menu'] = unserialize($data['menu']);
$data['kefu'] = unserialize($data['kefu']);

$this->info = $input;
return $this;

