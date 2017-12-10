<?php
global $_W,$_GPC;
$file = IA_ROOT."/addons/imeepos_runnerpro/inc/mobile/__init.php";
if (file_exists($file)) {
    require_once $file;
}
define('STATIC_PATH', MODULE_URL."template/mobile/coach/detail/");
$act = isset($_GPC['act']) ? trim($_GPC['act']) : '';
$_W['openid'] = $_W['openid'] ? $_W['openid'] : 'fromUser';
$user = mc_fansinfo($_W['openid']);
if ($act == 'list') {
    $sql = "SELEC * FROM ".tablename('imeepos_runner4_member_skill')." WHERE uniacid=:uniacid ";
    $params = array();
    $params['uniacid'] = $_W['uniacid'];
    $list = pdo_fetchall($sql, $params);
    $re = array();
    $re['list'] = $list;
    ToJson($re);
}

if ($act == 'update') {
    $input = $_GPC['__input'];
    unset($input['selected']);
    unset($input['lastDate']);
    unset($input['loading']);
    unset($input['max']);
    unset($input['content']);
    unset($input['tabs']);
    
    $id = isset($_GPC['id']) ? intval($_GPC['id']) : 0;
    $data = array();
    $input['detail']['avatar'] = $user['avatar'];
    $input['detail']['openid'] = $_W['openid'];
    $data['setting'] = serialize($input);
    $data['avatar'] = $user['avatar'];
    $data['openid'] = $_W['openid'];
    pdo_update('imeepos_runner4_member_skill', $data, array('id'=>$id));
    ToJson($data);
}

if ($act == 'detail') {
    $id = isset($_GPC['id']) ? intval($_GPC['id']) : 0;
    $year = isset($_GPC['year']) ? intval($_GPC['year']) : 0;
    $month = isset($_GPC['month']) ? intval($_GPC['month']) : 0;
    $day = isset($_GPC['day']) ? intval($_GPC['day']) : 0;
    
    $time = time();
    $sql = "SELECT * FROM ".tablename('imeepos_runner4_coach_log_time')." WHERE coachId=:coachId AND year=:year AND month=:month AND day=:day AND status > 0";
    $params = array();
    $params['coachId'] = $id;
    $params['year'] = $year;
    $params['month'] = $month;
    $params['day'] = $day;
    
    $list = pdo_fetchall($sql, $params);
    $re = array();
    $re['hasSelect'] = $list;
    $re['params'] = $params;
    $detail = pdo_get('imeepos_runner4_member_skill', array('id'=>$id));
    $detail['setting'] = unserialize($detail['setting']);
    $re['tags'] = !empty($detail['setting']['items']) ? $detail['setting']['items'] : array();
    $re['detail'] = $detail;
    $sql = "SELECT openid,create_time,status,title,star FROM ".tablename("imeepos_runner4_coach_log")." WHERE coachId=:coachId AND status>0 ORDER BY create_time DESC";
    $params = array();
    $params[':coachId'] = $id;
    $stars = pdo_fetchall($sql, $params);
    foreach ($stars as &$star) {
        $member = pdo_get("imeepos_runner3_member", array('openid'=>$star['openid']));
        $star['avatar'] = $member['avatar'] ? $member['avatar'] : 'https://meepo.com.cn/addons/imeepos_runnerpro/icon.jpg';
        $star['nickname'] = $member['nickname'];
        $star['create_time'] = date('y-m-d H:i', $star['create_time']);
    }
    unset($star);

    $re['stars'] = $stars;
    $starsTotal = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('imeepos_runner4_coach_log')." WHERE coachId=:coachId AND status>0", $params);
    $re['starsTotal'] = $starsTotal;
    ToJson($re);
}

if ($act == 'create') {
    $input = $_GPC['__input'];
    
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
    $data['title'] = $input['title'];
    $data['star'] = 0;
    pdo_insert('imeepos_runner4_coach_log', $data);
    $data['id'] = pdo_insertid();
    ToJson($data);
}

if ($act == 'add_skill') {
    $input = $_GPC['__input'];
    $member = pdo_get("imeepos_runner3_member", array('openid'=>$_W['openid']));
    if (!empty($input['mobile'])) {
        $data = array();
        $data['mobile'] = trim($input['mobile']);
        pdo_update('imeepos_runner3_member', $data, array('openid'=>$_W['openid']));
    }
    $data = array();
    $data['openid'] = $_W['openid'];
    $data['avatar'] = $member['avatar'] ? $member['avatar'] : 'https://meepo.com.cn/addons/imeepos_runnerpro/icon.jpg';
    $data['title'] = $input['title'];
    $data['desc'] = $input['desc'];
    $data['fee'] = floatval($input['fee']);
    $data['content'] = $input['content'];
    $data['create_time'] = time();
    $data['status'] = 0;
    foreach ($input['setting'] as &$setting) {
        $setting['fee'] = $data['fee'];
        $setting['timeLen'] = 30;
    }
    unset($setting);
    $save = array();
    $save['fee'] = $data['fee'];
    $save['action'] = 'pay';
    $save['detail'] = $data;
    $save['detail']['city'] = $input['city'];
    $save['detail']['role'] = '尚未完成认证';
    
    $save['timeLen'] = 30;
    $save['time'] = array();
    $save['time']['start'] = array('hour'=>7,'minute'=>0,'label'=>'07:00');
    $save['time']['end'] = array('hour'=>22,'minute'=>0,'label'=>'07:00');
    $save['items'] = $input['setting'];
    $data['setting'] = serialize($save);

    $skill = pdo_get('imeepos_runner4_member_skill', array('openid'=>$_W['openid']));
    if (empty($skill)) {
        pdo_insert('imeepos_runner4_member_skill', $data);
        $data['id'] = pdo_insertid();
    } else {
        pdo_update('imeepos_runner4_member_skill', $data, array('id'=>$skill['id']));
        $data['id'] = $skill['id'];
    }
    ToJson($data);
}

if ($act == 'groups') {
    $list = getListTopicGroup(0);
    $member = pdo_get("imeepos_runner3_member", array('openid'=>$_W['openid']));
    $skill = pdo_get("imeepos_runner4_member_skill", array('openid'=>$_W['openid']));
    $data = array();
    $data['groups'] = $list;
    $data['user'] = $member;
    $data['skill'] = $skill;
    ToJson($data);
}


if($act == 'my'){
    $skill = pdo_get('imeepos_runner4_member_skill', array('openid'=>$_W['openid']));
    $url = $this->createMobileUrl('coach_detail',array('id'=>$skill['id']));
    header("Location:".$url);
    exit();
}


include $this->template('coach/detail/index');


function getListTopicGroup($fid = 0)
{
    global $_W;
    $sql = "SELECT * FROM ".tablename('imeepos_runner4_skills_group')." WHERE uniacid=:uniacid AND fid=:fid ORDER BY displayorder ASC";
    $params = array(':uniacid'=>$_W['uniacid'],':fid'=>$fid);
    $list = pdo_fetchall($sql, $params);
    foreach ($list as &$li) {
        $li['tags'] = unserialize($li['tags']);
        $children = getListTopicGroup($li['id']);
        if (!empty($children)) {
            $li['children'] = $children;
        }
    }
    unset($li);
    $list = $list ? $list : array();
    return $list;
}
