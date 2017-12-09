<?php
global $_W;
$input = $this->__input['encrypted'];


$table = "imeepos_runner4_app";
$app = array();
$app['author'] = $input['author'];
$app['title'] = $input['title'];
$app['token'] = random(32);
$app['uniacid'] = $_W['uniacid'];

$item = pdo_get($table,array('token'=>$input['token'],'uniacid'=>$_W['uniacid']));

if(!empty($app['title'])){
    pdo_insert($table,$app);
    $app['id'] = pdo_insertid();

    // 分类
    foreach($input['catalogs'] as $catalog){
        $log = array();
        $log['title'] = $catalog['title'];
        $log['app_id'] = $app['id'];

        pdo_insert('imeepos_runner4_app_catalog',$log);
        $log['id'] = pdo_insertid();
        // 页面
        foreach($catalog['pages'] as $page){
            $_page = array();

            $_page = array();
            $_page['title'] = $page['title'];
            $_page['desc'] = $page['desc'];
            
            $_page['cata_id'] = $log['id'];
            $_page['app_id'] = $log['app_id'];
            
            $_page['header'] = serialize($page['header']);
            $_page['body'] = serialize($page['body']);
            $_page['footer'] = serialize($page['footer']);
            $_page['menu'] = serialize($page['menu']);
            $_page['kefu'] = serialize($page['kefu']);

            pdo_insert('imeepos_runner4_app_catalog_pages',$_page);
        }
    }
}

$this->info = $app;
return $this;