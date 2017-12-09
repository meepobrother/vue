<?php

global $_W;
$input = $this->__input['encrypted'];
if(!empty($input['dev'])){
    ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);
}

$page = intval($input['page']);
$psize = intval($input['psize']);
$page = $page > 0 ? $page : 1;
$psize = $psize > 0 ? $page : 30;


$list = pdo_getall('imeepos_runner4_app',array('uniacid'=>$_W['uniacid']),array(),'id desc',array('id'),array(1,30));
foreach($list as &$li){
    $catalogs = pdo_getall('imeepos_runner4_app_catalog',array('app_id'=>$li['id']),array(),'id desc',array('id'),array(1,30));
    foreach($catalogs as &$catalog){
        $pages = pdo_getall('imeepos_runner4_app_catalog_pages',array('cata_id'=>$catalog['id']));
        foreach($pages as &$page){
            $page['header'] = _unserialize($page['header'],'layout-header','头部',array());
            $page['footer'] = _unserialize($page['footer'],'layout-footer','底部',array());
            $page['body'] = _unserialize($page['body'],'layout-body','主体',array());
            $page['menu'] = _unserialize($page['menu'],'layout-menu','快捷菜单',array());
            $page['kefu'] = _unserialize($page['kefu'],'layout-kefu','客服',array());
        }
        $catalog['pages'] = $pages;
    }
    unset($catalog);
    $li['catalogs'] = $catalogs;
    $li['rights'] = unserialize($li['rights']);
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