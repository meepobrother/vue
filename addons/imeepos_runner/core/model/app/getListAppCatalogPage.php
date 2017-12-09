<?php
global $_W;
$table = "imeepos_runner4_app_catalog_pages";

$input = $this->__input['encrypted'];
if(!empty($input['dev'])){
    ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);
}

$page = intval($input['page']);
$psize = intval($input['psize']);
$page = $page > 0 ? $page : 1;
$psize = $psize > 0 ? $page : 30;

$app_id = intval($input['app_id']);

$list = pdo_getall($table,array('app_id'=>$app_id),array(),'id desc',array('id'),array(1,30));
foreach($list as &$li){
    $li['header'] = _unserialize($li['header'],'layout-header','头部',array());
    $li['footer'] = _unserialize($li['footer'],'layout-footer','底部',array());
    $li['body'] = _unserialize($li['body'],'layout-body','主体',array());
    $li['menu'] = _unserialize($li['menu'],'layout-menu','快捷菜单',array());
    $li['kefu'] = _unserialize($li['kefu'],'layout-kefu','客服',array());
}
unset($li);

$this->info = $list;
return $this;

function _unserialize($str = '',$type = '',$name = '',$children = array()){
    $data = unserialize($str);
    if(empty($data)){
        $data = array(
            'type'=>$type,
            'name'=>$name,
            'children'=>$children,
            'styleObj'=>array(
                'color'=> '#fff'
            ),
            'classObj'=>array(
                'author'=>false
            ),
            'containerClass'=>array(
                'author'=>false
            ),
            'containerStyle'=>array(
                'color'=> '#fff'
            ),            
        );
    }
    return $data;
}