<?php
if (!(defined('IN_IA'))) {
    exit('Access Denied');
}
define("QRCODE_PATH", IA_ROOT."/data/qrcode/");
class QrcodeMeepoModel
{
    public $QRCODE_LIB = IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
    public $QRCODE_PATH;
    public $QRCODE_URL;
    public function __construct()
    {
        global $_W,$_GPC;
        $this->QRCODE_PATH = QRCODE_PATH . $_W['uniacid'] . '/';
        $this->QRCODE_URL = $_W['siteroot'] . 'data/qrcode/' . $_W['uniacid'] .'/';
        if (!(is_dir($this->QRCODE_PATH))) {
            load()->func('file');
            mkdirs($this->QRCODE_PATH);
        }
    }
    public function createShopQrcode($shopid = 0)
    {
        global $_W,$_GPC;
        $url = murl('entry//shop.detail', array('shopid' => $shopid), true);
        if (!(empty($posterid))) {
            $url .= '&posterid=' . $posterid;
        }
        $file = 'shop_qrcode_' . $shopid . '.png';
        return  $this->createQrcodeFile($url, $file);
    }
    public function createGoodsQrcode($goodsid = 0)
    {
        global $_W,$_GPC;
        $url = murl('entry//goods.detail', array('goodsid' => $goodsid), true);
        $file = 'goods_qrcode_' . $goodsid . '.png';
        return $this->createQrcodeFile($url, $file);
    }

    public function createMemberQrcode($openid = 'fromUser')
    {
        global $_W,$_GPC;
        $url = murl('entry//member.detail', array('openid' => $openid), true);
        $file = 'member_qrcode_' .$openid . '.png';
        return $this->createQrcodeFile($url, $file);
    }

    public function createTasksQrcode($taskid = 0)
    {
        global $_W,$_GPC;
        $url = murl('entry//member.detail', array('taskid' => $taskid), true);
        $file = 'tasks_qrcode_' .$taskid . '.png';
        return $this->createQrcodeFile($url, $file);
    }

    public function createQrcode($url)
    {
        global $_W,$_GPC;
        $file = md5(base64_encode($url)) . '.jpg';
        return $this->createQrcodeFile($url, $file);
    }

    public function createQrcodeFile($url, $file)
    {
        $qrcode_file = $this->QRCODE_PATH . $file;
        if (!(is_file($qrcode_file))) {
            require_once IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
            QRcode::png($url, $qrcode_file, QR_ECLEVEL_L, 4);
        }
        return $this->QRCODE_URL . $file;
    }
}
