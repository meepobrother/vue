<?php
define("MODULE_NAME", 'imeepos_runnerpro');
class CommonMeepoModel
{
    public function getAccount()
    {
        global $_W;
        load()->model('account');
        if (!(empty($_W['acid']))) {
            return WeAccount::create($_W['acid']);
        }
        $acid = pdo_fetchcolumn('SELECT acid FROM ' . tablename('account_wechats') . ' WHERE `uniacid`=:uniacid LIMIT 1', array(':uniacid' => $_W['uniacid']));
        return WeAccount::create($acid);
    }

    public function changeTitle($title)
    {
        $title = preg_replace('/[^\\x{4e00}-\\x{9fa5}A-Za-z0-9_]/u', '', $title);
        return $title;
    }

    public function createNO($table, $field, $prefix)
    {
        $billno = date('YmdHis') . random(6, true);
        while (1) {
            $count = pdo_fetchcolumn('select count(*) from ' . tablename($table) . ' where ' . $field . '=:billno limit 1', array(':billno' => $billno));
            if ($count <= 0) {
                break;
            }
            $billno = date('YmdHis') . random(6, true);
        }
        return $prefix . $billno;
    }

    public function deleteFile($attachment, $fileDelete = false)
    {
        global $_W;
        $attachment = trim($attachment);
        if (empty($attachment)) {
            return false;
        }
        $media = pdo_get('core_attachment', array('uniacid' => $_W['uniacid'], 'attachment' => $attachment));
        if (empty($media)) {
            return false;
        }
        if (empty($_W['isfounder']) && ($_W['role'] != 'manager')) {
            return false;
        }
        if ($fileDelete) {
            load()->func('file');
            if (!(empty($_W['setting']['remote']['type']))) {
                $status = file_remote_delete($media['attachment']);
            } else {
                $status = file_delete($media['attachment']);
            }
            if (is_error($status)) {
                exit($status['message']);
            }
        }
        pdo_delete('core_attachment', array('uniacid' => $_W['uniacid'], 'id' => $media['id']));
        return true;
    }

    public function ToXml($arr)
    {
        if (!(is_array($arr)) || (count($arr) <= 0)) {
            return error(-1, '数组数据异常！');
        }
        $xml = '<xml>';
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= '<' . $key . '>' . $val . '</' . $key . '>';
            } else {
                $xml .= '<' . $key . '><![CDATA[' . $val . ']]></' . $key . '>';
            }
        }
        $xml .= '</xml>';
        return $xml;
    }

    public function ToUrlParams($arr)
    {
        $buff = '';
        foreach ($arr as $k => $v) {
            if (($k != 'sign') && ($v != '') && !(is_array($v))) {
                $buff .= $k . '=' . $v . '&';
            }
        }
        $buff = trim($buff, '&');
        return $buff;
    }

    public function FromXml($xml)
    {
        if (!($xml)) {
            return error(-1, 'xml数据异常！');
        }
        libxml_disable_entity_loader(true);
        $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $arr;
    }

    public function wechat_jspay($params, $wechat, $type = 0)
    {
        global $_W;
        load()->func('communication');
        $wOpt = array();
        $package = array();
        $package['appid'] = $wechat['sub_appid'];
        $package['mch_id'] = $wechat['sub_mch_id'];
        $package['nonce_str'] = random(32);
        $package['body'] = $params['title'];
        $package['device_info'] = 'ewei_shopv2';
        $package['attach'] = $_W['uniacid'] . ':' . $type;
        $package['out_trade_no'] = $params['tid'];
        $package['total_fee'] = $params['fee'] * 100;
        $package['spbill_create_ip'] = CLIENT_IP;
        if (!(empty($params['goods_tag']))) {
            $package['goods_tag'] = $params['goods_tag'];
        }
        $package['notify_url'] = $_W['siteroot'] . 'payment/wechat/notify.php';
        $package['trade_type'] = 'JSAPI';
        $package['openid'] = ((empty($params['openid']) ? $_W['openid'] : $params['openid']));
        ksort($package, SORT_STRING);
        $string1 = '';
        foreach ($package as $key => $v) {
            if (empty($v)) {
                continue;
            }
            $string1 .= $key . '=' . $v . '&';
        }
        $string1 .= 'key=' . $wechat['apikey'];
        $package['sign'] = strtoupper(md5($string1));
        $dat = array2xml($package);
        $response = ihttp_request('https://api.mch.weixin.qq.com/pay/unifiedorder', $dat);
        if (is_error($response)) {
            return $response;
        }
        $xml = @simplexml_load_string($response['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
        if (strval($xml->return_code) == 'FAIL') {
            return error(-1, strval($xml->return_msg));
        }
        if (strval($xml->result_code) == 'FAIL') {
            return error(-1, strval($xml->err_code) . ': ' . strval($xml->err_code_des));
        }
        $prepayid = $xml->prepay_id;
        $wOpt['appId'] = $wechat['sub_appid'];
        $wOpt['timeStamp'] = TIMESTAMP . '';
        $wOpt['nonceStr'] = random(32);
        $wOpt['package'] = 'prepay_id=' . $prepayid;
        $wOpt['signType'] = 'MD5';
        ksort($wOpt, SORT_STRING);
        $string = '';
        foreach ($wOpt as $key => $v) {
            $string .= $key . '=' . $v . '&';
        }
        $string .= 'key=' . $wechat['apikey'];
        $wOpt['paySign'] = strtoupper(md5($string));
        return $wOpt;
    }
}
