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
        $tid = $params['tid'];
        if ($this->isInString($tid, 'COACH')) {
            $this->payResultCoach($params);
        }
    }
    public function __construct()
    {
        M('qrcode')->createQrcode('https://meepo.com.cn');
        $_W['page']['title'] = M('common')->changeTitle($_W['page']['title']);
    }

    public function doMobilePay()
    {
        global $_W,$_GPC;
        $tid = trim($_GPC['tid']);
        if (empty($tid)) {
            itoast('参数错误', $this->createMobileUrl('runner_index'), 'error');
        }
        // 检查前缀
        if ($this->isInString($tid, 'COACH')) {
            $params = $this->payCoach($tid);
        } else {
            itoast('订单格式错误', $this->createMobileUrl('runner_index'), 'error');
        }
        
        $this->pay($params);
    }

    private function isInString($haystack, $needle)
    {
        $array = explode($needle, $haystack);
        return count($array) > 1;
    }

    private function payCoach($tid)
    {
        $log = pdo_get('imeepos_runner4_coach_log', array('tid'=>$tid));
        if (empty($log)) {
            itoast('参数错误', $this->createMobileUrl('runner_index'), 'error');
        }
        $params = array();
        $params['fee'] = $log['total'];
        $params['title'] = $log['title'] ? $log['title'] : '预约';
        $params['tid'] = $log['tid'];
        $params['ordersn'] = $params['tid'];
        $params['user'] = $_W['member']['uid'] ? $_W['member']['uid'] : $_W['openid'];
        return $params;
    }

    private function payResultCoach($params)
    {
        $fee = intval($params['fee']);
        $data = array(
            'status' => ($params['result'] == 'success' ? 1 : 0),
            'payType' => $params['type']
        );
        $tid = $params['tid'];
        $log = pdo_get('imeepos_runner4_coach_log', array('tid'=>$tid));
        if (1 <= $log['status']) {
            return true;
        }
        if ($params['from'] == 'return') {
            if (pdo_update('imeepos_runner4_coach_log', $data, array('id'=>$log['id']))) {
                unset($data['payType']);
                pdo_update('imeepos_runner4_coach_log_time', $data, array('coachId'=>$log['coachId']));
                itoast('支付成功', $this->createMobileUrl('runner_index'), 'success');
                return true;
            }
        }
    }
}
