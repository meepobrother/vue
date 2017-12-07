<?php
/**
 * 跑腿2.0模块微站定义
 *
 * @author imeepos
 * @url
 */
defined('IN_IA') or exit('Access Denied');

require_once IA_ROOT . '/addons/imeepos_runnerpro/version.php';
require_once IA_ROOT . '/addons/imeepos_runnerpro/defines.php';
require_once IA_ROOT . '/addons/imeepos_runnerpro/functions.php';

// ini_set("display_errors", "On");
// error_reporting(E_ALL | E_STRICT);

class Imeepos_runnerproModuleSite extends WeModuleSite
{
    public function getMenus()
    {
        global $_W;
        return array(
            array('title' => '管理后台', 'icon' => 'fa fa-shopping-cart', 'url' => $this->createWebUrl('index'))
        );
    }
    public function doWebWeb()
    {
        // m('route')->run();
    }
    public function doMobileMobile()
    {
        // m('route')->run(false);
    }
    public function payResult($params)
    {
        // return m('order')->payResult($params);
    }
    public function __construct()
    {
        M('qrcode')->createQrcode('https://meepo.com.cn');
        $_W['page']['title'] = M('common')->changeTitle($_W['page']['title']);
    }

    public function doMobilePay(){
        global $_W,$_GPC;
        $params = array();
        $params['fee'] = '1';
        $params['title'] = '测试';
        $params['tid'] = M('common')->createNO('core_paylog','tid','');
        $params['ordersn'] = $params['tid'];
        $_W['page']['title'] = M('common')->changeTitle($_W['page']['title']);
        // print_r($_W['page']['title']);
        $this->pay($params);
    }
}
