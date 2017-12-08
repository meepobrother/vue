<?php
global $_W,$_GPC;
include MODULE_ROOT."inc/mobile/__init.php";
define('STATIC_PATH', MODULE_URL."template/mobile/coach/time/");

$act = isset($_GPC['act']) ? trim($_GPC['act']) : '';

if($act == 'list'){
    $sql = "SELEC * FROM ".tablename('imeepos_runner4_member_skill')." WHERE uniacid=:uniacid ";
    $params = array();
    $params['uniacid'] = $_W['uniacid'];
    $list = pdo_fetchall($sql,$params);

    $re = array();
    $re['list'] = $list;
    die(json_encode($re));
}

if ($act == 'detail') {
    $id = isset($_GPC['id']) ? intval($_GPC['id']) : 0;
    $year = isset($_GPC['year']) ? intval($_GPC['year']) : 0;
    $month = isset($_GPC['month']) ? intval($_GPC['month']) : 0;
    $day = isset($_GPC['day']) ? intval($_GPC['day']) : 0;
    
    $time = time();
    $sql = "SELECT * FROM ".tablename('imeepos_runner4_coach_log_time')." WHERE coachId=:coachId AND year=:year AND month=:month AND day=:day";
    $params = array();
    $params['coachId'] = $id;
    $params['year'] = $year;
    $params['month'] = $month;
    $params['day'] = $day;
    
    $list = pdo_fetchall($sql, $params);

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header('P3P: CP="CAO PSA OUR"');
    header("Content-Type: application/json; charset=utf-8");

    $re = array();
    $re['hasSelect'] = $list;
    $re['params'] = $params;
    die(json_encode($re));
}

if ($act == 'create') {
    $input = $_GPC['__input'];
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header('P3P: CP="CAO PSA OUR"');
    header("Content-Type: application/json; charset=utf-8");

    $data = array();
    $data['coachId'] = isset($input['id']) ? intval($input['id']) : 0;
    if (empty($data['coachId'])) {
        $data['msg'] = '请选择服务项目';
        $data['code'] = 0;
        die(json_encode($data));
    }
    // coach detail
    $coach = pdo_get('imeepos_runner4_member_skill', array('id'=>$data['coachId']));
    if (empty($coach)) {
        $data['msg'] = '服务项目不存在或已删除';
        $data['code'] = 0;
        die(json_encode($data));
    }
    // $data['detail'] = $coach;
    $data['desc'] = isset($input['desc']) ? trim($input['desc']) : '';
    $data['coachTime'] = isset($input['time']) ? $input['time'] : array();
    $data['count'] = count($data['coachTime']);
    $data['timeIds'] = array();
    foreach ($data['coachTime'] as $coachTime) {
        $log_time = array();
        $log_time['year'] = $coachTime['year'];
        $log_time['month'] = $coachTime['month'];
        $log_time['day'] = $coachTime['day'];
        $log_time['hour'] = $coachTime['hour'];
        $log_time['minute'] = $coachTime['minute'];
        $log_time['coachId'] = $data['coachId'];
        $log_time['val'] = $coachTime['val'];
        $log_time['openid'] = $_W['openid'];
        $log_time['toOpenid'] = $coach['openid'];
        $log_time['timeInt'] = $coachTime['timeInt'];
        $log_time['status'] = 0;
        pdo_insert('imeepos_runner4_coach_log_time', $log_time);
        $id = pdo_insertid();
        $data['timeIds'][] = $id;
    }
    $data['coachTime'] = serialize($data['coachTime']);
    $data['timeIds'] = serialize($data['timeIds']);
    
    $data['total'] = $coach['fee'] * $data['count'];
    $data['fee'] = $coach['fee'];
    $data['openid'] = $_W['openid'];
    $data['toOpenid'] = $coach['openid'];
    $data['create_time'] = time();
    $data['tid'] = M('common')->createNO('imeepos_runner4_coach_log', 'tid', 'COACH');
    $data['status'] = 0;
    $data['title'] = $coach['title'];
    pdo_insert('imeepos_runner4_coach_log', $data);
    $data['id'] = pdo_insertid();
    die(json_encode($data));
}

include $this->template('coach/time/index');
