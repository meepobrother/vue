<?php
/**
 * 跑腿2.0模块微站定义
 *
 * @author imeepos
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

require_once IA_ROOT . '/addons/imeepos_runnerv2/version.php';
require_once IA_ROOT . '/addons/imeepos_runnerv2/defines.php';
require_once MEEPO_INC . 'functions.php';

class Imeepos_runnerv2ModuleSite extends WeModuleSite {
	public function getMenus(){
		global $_W;
        return array(
    		array('title' => '管理后台', 'icon' => 'fa fa-shopping-cart', 'url' => $this->createWebUrl('index'))
    	);
	}
	public function doWebWeb(){
        m('route')->run();
    }
    public function doMobileMobile(){
        m('route')->run(false);
    }
    public function payResult($params){
        return m('order')->payResult($params);
	}
	public function __construct(){}
}