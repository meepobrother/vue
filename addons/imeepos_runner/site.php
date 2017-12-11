<?php
/**
 * 小明跑腿模块微站定义
 *
 * @author imeepos
 * @url http://bbs.we7.cc/
 * 计算 财会
 */
defined('IN_IA') or exit('Access Denied');
define('UPDATE_URL','http://meepo.com.cn/addons/imeepos_oauth2/oauth/');
if(!function_exists('M')){
	function M($name){
		static $model = array();
		if(empty($model[$name])) {
			include IA_ROOT.'/addons/imeepos_runner/inc/core/model/'.$name.'.mod.php';
			$model[$name] = new $name();
		}
		return $model[$name];
	}
}

if(!function_exists('A')){
	function A($name){
		static $model = array();
		if(empty($model[$name])) {
			include IA_ROOT.'/addons/imeepos_runner/api/'.$name.'.php';
			$model[$name] = new $name();
		}
		return $model[$name];
	}
}

function J($status = -1,$message="获取数据失败",$info=array()){
	// header("Access-Control-Allow-Origin:*");
	$data = array();
	$data['status'] = $status;
	$data['message'] = $message;
	$data['info'] = $info;
	die(json_encode($data));
}

define('Meepo_Debug',false);

if(Meepo_Debug){
	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);
}

class Imeepos_runnerModuleSite extends WeModuleSite {

	public function doMobileV20(){
		global $_W,$_GPC;

		include $this->template('v20/index');
	}

	public function doMobileUpload(){
		global $_W,$_GPC;
		header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Credentials: true"); 
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        header('Access-Control-Max-Age: 86400'); 
		$src = "";
		$setting = $_W['setting']['upload'][$type];
		$result = array(
			'jsonrpc' => '2.0',
			'id' => 'id',
			'error' => array('code' => 1, 'message'=>''),
		);
		load()->func('file');
		if (empty($_FILES['file']['tmp_name'])) {
			$binaryfile = file_get_contents('php://input', 'r');
			if (!empty($binaryfile)) {
				mkdirs(ATTACHMENT_ROOT . '/temp');
				$tempfilename = random(5);
				$tempfile = ATTACHMENT_ROOT . '/temp/' . $tempfilename;
				if (file_put_contents($tempfile, $binaryfile)) {
					$imagesize = @getimagesize($tempfile);
					$imagesize = explode('/', $imagesize['mime']);
					$_FILES['file'] = array(
						'name' => $tempfilename . '.' . $imagesize[1],
						'tmp_name' => $tempfile,
						'error' => 0,
					);
				}
			}
		}
		if (!empty($_FILES['file']['name'])) {
			if ($_FILES['file']['error'] != 0) {
				$result['error']['message'] = '上传失败，请重试！';
				die(json_encode($result));
			}
			if (!file_is_image($_FILES['file']['name'])) {
				$result['message'] = '上传失败, 请重试.';
				die(json_encode($result));
			}
			$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			$ext = strtolower($ext);

			$file = file_upload($_FILES['file']);
			if (is_error($file)) {
				$result['error']['message'] = $file['message'];
				die(json_encode($result));
			}

			$pathname = $file['path'];
			$fullname = ATTACHMENT_ROOT . '/' . $pathname;

			$thumb = empty($setting['thumb']) ? 0 : 1; 			$width = intval($setting['width']); 			if ($thumb == 1 && $width > 0 && (!isset($_GPC['thumb']) || (isset($_GPC['thumb']) && !empty($_GPC['thumb'])))) {
				$thumbnail = file_image_thumb($fullname, '', $width);
				@unlink($fullname);
				if (is_error($thumbnail)) {
					$result['message'] = $thumbnail['message'];
					die(json_encode($result));
				} else {
					$filename = pathinfo($thumbnail, PATHINFO_BASENAME);
					$pathname = $thumbnail;
					$fullname = ATTACHMENT_ROOT .'/'.$pathname;
				}
			}
			$info = array(
				'name' => $_FILES['file']['name'],
				'ext' => $ext,
				'filename' => $pathname,
				'attachment' => $pathname,
				'url' => tomedia($pathname),
				'is_image' => 1,
				'filesize' => filesize($fullname),
			);
			$size = getimagesize($fullname);
			$info['width'] = $size[0];
			$info['height'] = $size[1];
			
			setting_load('remote');
			if (!empty($_W['setting']['remote']['type'])) {
				$remotestatus = file_remote_upload($pathname);
				if (is_error($remotestatus)) {
					$result['message'] = '远程附件上传失败，请检查配置并重新上传';
					file_delete($pathname);
					die(json_encode($result));
				} else {
					file_delete($pathname);
					$info['src'] = tomedia($pathname);
				}
			}
			
			pdo_insert('core_attachment', array(
				'uniacid' => $uniacid,
				'uid' => $_W['uid'],
				'filename' => $_FILES['file']['name'],
				'attachment' => $pathname,
				'type' => $type == 'image' ? 1 : 2,
				'createtime' => TIMESTAMP,
			));
			$info['id'] = pdo_insertid();
			die(json_encode($info));
		} else {
			// 失败
			$result['code'] = -1;
			$result['msg'] = '请选择要上传的图片！';
			$result['msg'] = array();
			die(json_encode($result));
		}


		$data = array();
		$data['code'] = 0;
		$data['msg'] = '';
		$data['data'] = array(
			"src"=>$src
		);
		die(json_encode($data));
	}

	public function checkAppRight($rights = array()){
		global $_W,$_GPC;
		$openid = $_W['openid'];
		$roles = $rights['roles'];
		if($this->checkRole('roles.none',$roles)){
			return true;
		}
		if(!empty($openid)){
			if($this->checkRole('roles.fans',$roles)){
				return true;
			}
		}
		$member = pdo_get('imeepos_runner3_member',array('openid'=>$_W['openid']));
		if(!empty($member)){
			if($this->checkRole('roles.member',$roles)){
				return true;
			}
		}
		if($member['isrunner'] == 1){
			if($this->checkRole('roles.runner',$roles)){
				return true;
			}
		}
		if($member['ismanager'] == 1){
			if($this->checkRole('roles.manager',$roles)){
				return true;
			}
		}
		if($member['isadmin'] == 1){
			if($this->checkRole('roles.admin',$roles)){
				return true;
			}
		}

		return false;
	}

	public function checkRole($r = '',$roles = array()){
		$hasRight = false;
		foreach($roles as $role){
			if($role['code'] == $r){
				$hasRight = $role['active'];
			}
		}
		return $hasRight;
	}
	// 应用预览
	public function doMobileApp(){
		global $_W,$_GPC;
		$id = intval($_GPC['aid']);
		$app = pdo_get('imeepos_runner4_app',array('id'=>$id));
		$app['rights'] = unserialize($app['rights']);
		if(!$this->checkAppRight($app['rights'])){
			itoast('您没有访问本应用权限','','error');
			return false;
		}
		// 获取默认页面
		$table = "imeepos_runner4_app_catalog_pages";
		$data = pdo_get($table,array('app_id'=>$id,'isdefault'=>1));
		if(empty($data)){
			$data = pdo_get($table,array('app_id'=>$id));
		}
		$data['body'] = unserialize($data['body']);
		$data['header'] = unserialize($data['header']);
		$data['footer'] = unserialize($data['footer']);
		$data['menu'] = unserialize($data['menu']);
		$data['kefu'] = unserialize($data['kefu']);
		$html_content = $data['html_content'];
		unset($data['html_content']);

		$table = 'imeepos_runner4_app_widgets';
		$widgets = pdo_getall($table);
		$result = array();
		if($_W['isajax']){
			$result['data'] = $data;
			$result['widgets'] = $widgets;
			die(json_encode($result));
		}
		$member = pdo_get('imeepos_runner3_member',array('openid'=>$_W['openid']));
		include $this->template('design/index');
	}

	public function doMobileDesign(){
		global $_W,$_GPC;
		$id = intval($_GPC['pid']);
		$table = "imeepos_runner4_app_catalog_pages";
		$data = pdo_get($table,array('id'=>$id));

		$data['body'] = unserialize($data['body']);
		$data['header'] = unserialize($data['header']);
		$data['footer'] = unserialize($data['footer']);
		$data['menu'] = unserialize($data['menu']);
		$data['kefu'] = unserialize($data['kefu']);

		$app = pdo_get('imeepos_runner4_app',array('id'=>$data['app_id']));
		$app['rights'] = unserialize($app['rights']);
		if(!$this->checkAppRight($app['rights'])){
			itoast('您没有访问本应用权限','','error');
			return false;
		}
		$html_content = $data['html_content'];
		unset($data['html_content']);

		$table = 'imeepos_runner4_app_widgets';
		$widgets = pdo_getall($table);
		$result = array();
		if($_W['isajax']){
			$result['data'] = $data;
			$result['widgets'] = $widgets;
			die(json_encode($result));
		}

		$member = pdo_get('imeepos_runner3_member',array('openid'=>$_W['openid']));
		include $this->template('design/index');
	}
	public function doMobileIndex() {
		//这个操作被定义用来呈现 功能封面
		global $_W,$_GPC;
		if(empty($_GET['m'])){
			$url = $this->createMobileUrl('index');
			header("location:".$url);
			exit();
		}
		load()->model('mc');
    	mc_oauth_userinfo();
    	if(!empty($_W['openid'])){
    		M('member')->update();
    	}
    	$this->checkMobile();
		$setting = M('setting')->getValue('setting.system');
		// 获取版本号
		$versionfile = IA_ROOT."/addons/imeepos_runner/version.php";
		if(file_exists($versionfile)){
			require_once $versionfile;
			$version = VERSION;
			if($version == '0.0.0'){
				$version = '开发同步版';
			}
		}else{
			$version = '1.0.0';
		}
		//添加索引
		if(!pdo_indexexists('imeepos_runner3_setting','IDX_CODE')){
			$sql = "ALTER TABLE ".tablename('imeepos_runner3_setting')." ADD INDEX IDX_CODE (`code`) ";
			pdo_query($sql);
		}
    	include $this->template('index');
	}

	public function doMobileFileList2(){
		global $_W,$_GPC;
		$filePath = MODULE_ROOT.'/template/mobile/';
		load()->func('file');
		$list = $this->listDir($filePath);
		$str = "";
		foreach($list as &$li){
			$li = str_replace($filePath,'../addons/imeepos_runner/template/mobile/',$li);
			$str.= $li."\n";
		}
		unset($li);
$html =  
<<<EOT
CACHE MANIFEST
# VERSION 1.34
# 直接缓存的文件
CACHE:
{$str}
EOT;
		header("Content-type:text/cache-manifest");
		print_r($html);
	}

	public function listDir($dir){
		$dir .= substr($dir, -1) == '/' ? '' : '/';
		$dirInfo = array();
		foreach (glob($dir.'*') as $v) {
			if(is_dir($v)){
				$dirInfo = array_merge($dirInfo, $this->listDir($v));
				unset($v);
			}else{
				$dirInfo[] = $v;
			}
		}
		return $dirInfo;
	}

	public function doMobileTasks(){
		global $_W,$_GPC;
		if(empty($_GET['m'])){
			$url = $this->createMobileUrl('tasks');
			header("location:".$url);
			exit();
		}
		load()->model('mc');
    	mc_oauth_userinfo();

    	$this->checkMobile();

    	$setting = M('setting')->getValue('setting.system');
    	include $this->template('index');
	}

	public function doMobileMap(){
		global $_W,$_GPC;
		if(empty($_GET['m'])){
			$url = $this->createMobileUrl('map');
			header("location:".$url);
			exit();
		}
		load()->model('mc');
    	mc_oauth_userinfo();

    	$this->checkMobile();

    	$setting = M('setting')->getValue('setting.system');
    	include $this->template('index');
	}

	public function doMobileDetail() {
		//这个操作被定义用来呈现 功能封面
		global $_W,$_GPC;
		load()->model('mc');
    	mc_oauth_userinfo();

    	$setting = M('setting')->getValue('setting.system');
    	
    	include $this->template('index');
	}

	public function doMobileIm() {
		//这个操作被定义用来呈现 功能封面
		global $_W,$_GPC;
		if(empty($_GET['m'])){
			$url = $this->createMobileUrl('im');
			header("location:".$url);
			exit();
		}
		load()->model('mc');
    	mc_oauth_userinfo();

    	$setting = M('setting')->getValue('setting.system');
    	
    	include $this->template('index');
	}

	public function doMobilec0() {
		//这个操作被定义用来呈现 管理中心导航菜单
		// global $_W,$_GPC;
		// load()->model('mc');
  //   	mc_oauth_userinfo();
		// include $this->template('index.cache');
	}
	public function doMobileOpen(){
	    global $_W,$_GPC;
		if (isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: *");
			header("Access-Control-Allow-Credentials: true");
			header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
			header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
			header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
			header("Content-Type: application/json; charset=utf-8");
		}
        $__do = trim($_GPC['__do']);
        $input = isset($_GPC['__input']) ? $_GPC['__input'] : array();
        die($this->router->reset()->exec($__do,$input)->getJson());
    }
    public function doWebOpen(){
	    global $_W,$_GPC;
        if (isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: *");
			header("Access-Control-Allow-Credentials: true");
			header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
			header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
			header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
			header("Content-Type: application/json; charset=utf-8");
		}
        $__do = trim($_GPC['__do']);
        $input = isset($_GPC['__input']) ? $_GPC['__input'] : array();
        die($this->router->reset()->exec($__do,$input)->getJson());
    }

    public function doMobileNewOpen(){
	    global $_W,$_GPC;
        header("Access-Control-Allow-Origin:*");
        $__do = trim($_GPC['__do']);
        $input = isset($_GPC['__input']) ? $_GPC['__input'] : array();
        die($this->router->reset()->exec($__do,$input)->getJson());
    }
    public function doWebNewOpen(){
	    global $_W,$_GPC;
        header("Access-Control-Allow-Origin:*");
        $__do = trim($_GPC['__do']);
        $input = isset($_GPC['__input']) ? $_GPC['__input'] : array();
        die($this->router->reset()->exec($__do,$input)->getJson());
    }
    //aa674b3e
    public function doWebUpdate(){
    	global $_W;
    	$file = IA_ROOT.'/addons/imeepos_runner/install.php';
        include_once $file;
	    $file = IA_ROOT.'/addons/imeepos_runner/update.php';
        include_once $file;
		$file = IA_ROOT.'/addons/imeepos_runner/update-v10.php';
		include_once $file;

		include IA_ROOT."/addons/imeepos_runner/core/module/paylog/update.php";
		include IA_ROOT."/addons/imeepos_runner/core/module/tasks/update.php";
		include IA_ROOT."/addons/imeepos_runner/core/module/orders/update.php";
		include IA_ROOT."/addons/imeepos_runner/core/module/member/update.php";
		include IA_ROOT."/addons/imeepos_runner/core/module/login/update.php";
		include IA_ROOT."/addons/imeepos_runner/core/module/tixian/update.php";



		message('更新成功');
	}
	
    public function doWebClear(){
		global $_W,$_GPC;

		include IA_ROOT.'/addons/imeepos_runner/webclear.php';
		$file = IA_ROOT.'/addons/iemepos_runner/template/common';
		// 删除文件，兼容微擎1.0
		load()->func('file');
		if(is_dir($file)){
			rmdirs($file);
		}
		// 删除文件，兼容微擎1.0
		$file = IA_ROOT.'/addons/iemepos_runner/template/mobile/common';
		if(is_dir($file)){
			rmdirs($file);
		}

		// 新版升级
		include IA_ROOT.'/addons/imeepos_runner/core/model/member/update.php';
		include IA_ROOT.'/addons/imeepos_runner/core/model/login/update.php';
		include IA_ROOT.'/addons/imeepos_runner/core/model/orders/update.php';
		include IA_ROOT.'/addons/imeepos_runner/core/model/shops/update.php';
		include IA_ROOT.'/addons/imeepos_runner/core/model/topics/update.php';
		include IA_ROOT.'/addons/imeepos_runner/core/model/app/update.php';
		include IA_ROOT.'/addons/imeepos_runner/core/model/cards/update.php';
		include IA_ROOT.'/addons/imeepos_runner/core/model/cars/update.php';
		print_r('clear');
    }

    public function payResult($params = array()){
    	global $_W;
    	$result = $params['result'];
        $type = $params['type'];
        $from = $params['from'];
        $fee = floatval($params['fee']);
        if($fee <= 0){
        	return '';
        }
        $tid = $params['tid'];
        $paylog = pdo_get('imeepos_runner3_tasks_paylog',array('tid'=>$tid));
        //
        if($result == 'success'){
        	if($paylog['status'] == 0){
        		//插入任务
        		$openid = $params['user'];
        		$taskJson = $paylog['setting'];
        		$taskJson = base64_decode($taskJson);
				$task = unserialize($taskJson);
				$action = $task['action']; //支付目的
				$action = $action ? $action : 'task';
				if($action == 'task'){
					if(pdo_update('imeepos_runner3_tasks_paylog',array('status'=>1),array('id'=>$paylog['id']))){
						$taskid = $this->createTask($task,$openid);
						if(!empty($taskid)){
							pdo_update('imeepos_runner3_tasks_paylog',array('tasks_id'=>$taskid),array('id'=>$paylog['id']));
						}
					}
				}
        	}
        }

    }

    public function createTask($task = array(),$openid = ''){
		global $_W;
		$data = array();
		$data['uniacid'] = $_W['uniacid'];
		$data['status'] = 0;
		$data['create_time'] = time();
		$data['update_time'] = time();
		$data['message'] = $task['desc'];
		$data['openid'] = $openid;
		//录音 serverId
		$data['media_id'] = $task['serverId'];
		$data['voice_time'] = $task['voice_time'];
		$data['media_src'] = $task['src'];
		$data['small_fee'] = floatval($task['money']);
		$data['type'] = $task['type']; //任务类型
		$data['payType'] = $task['payType']; // 支付类型
		if($data['payType'] == 'credit'){
			$data['status'] = 1;
		}
		if($data['payType'] == 'wechat'){
			$data['status'] = 1;
		}
		$data['limit_time'] = $task['duration'];
		$data['total'] = $task['total'];
		$code = random(6,true);
		$data['code'] = $code;
		$qrcode = 'imeepos_runner'.md5($code.$data['create_time']);
		$data['qrcode'] = $qrcode;
		if(pdo_insert('imeepos_runner3_tasks',$data)){
			$taskid = pdo_insertid();
			pdo_update('imeepos_runner3_tasks_paylog',array('tasks_id'=>$taskid),array('tid'=>$task['tid'],'uniacid'=>$_W['uniacid']));
			pdo_update('imeepos_runner3_tasks',array('status'=>1),array('id'=>$taskid));
			$detail = array();
			$detail['taskid'] = $taskid;
			$detail['goodscost'] = $task['price'];
			$detail['goodsname'] = $task['goods'];
			$detail['goodsweight'] = $task['weight'];
			$detail['uniacid'] = $_W['uniacid'];

			$detail['receivelon'] = $task['end']['lng'];
			$detail['receivelat'] = $task['end']['lat'];
			$detail['receivedetail'] = $task['end']['detail'];
			$detail['receivemobile'] = $task['end']['mobile'];
			$detail['receiverealname'] = $task['end']['realname'];
			$detail['receiveaddress'] = $task['end']['poiname'];

			$detail['senddetail'] = $task['start']['detail'];
			$detail['sendlat'] = $task['start']['lat'];
			$detail['sendlon'] = $task['start']['lng'];
			$detail['sendaddress'] = $task['start']['poiname'];
			$detail['sendrealname'] = $task['start']['realname'];
			$detail['sendmobile'] = $task['start']['mobile'];
			$detail['images'] = serialize($task['images']);
			// $detail['base_fee'] = floatval($task['baojia']['value']);	
			$detail['small_fee'] = floatval($task['money']);
			// 保价
			$detail['base_fee'] = floatval($task['baojia']['value']);	
		    $detail['tiji'] = $task['tiji'];

			// ALTER TABLE `ims_imeepos_runner3_detail` ADD `total_num` INT(11) NOT NULL DEFAULT '1' AFTER `sendmobile`;
			$detail['total_num'] = !empty($task['number']) ? $task['number'] : 1;
		    $detail['steps'] = serialize($task['steps']);
			if($task['time']){
				$str = str_replace('年', '-', $task['time']['value']);
				$str = str_replace('月', '-', $str);
				$str = str_replace('日', '', $str);
				$detail['pickupdate'] = strtotime($str);
			}
			$detail['total'] = $task['total'];
			$detail['message'] = $task['desc'];
			$detail['duration'] = !empty($task['duration']) ? $task['duration'] : '待定	';
			$detail['duration_value'] = $task['duration_value'];
			$detail['float_distance'] = $task['routeLen'];
			// $detail['goodscost'] = $task['price'];s
			pdo_insert('imeepos_runner3_detail',$detail);
			return $taskid;
		}
		return 0;
	}

    public function doWebAdmin(){
    	return $this->doWebIndex();
    }

    public function doWebDelete(){
    	global $_W;
    	pdo_delete('imeepos_runner3_member',array('uniacid'=>$_W['uniacid']));

		message('更新成功');
    }
    public function doWebclearsystem(){
    	pdo_delete('imeepos_runner3_setting',array('code'=>'update.setting'));
    	message('从操作成功');
    }

    public function doWebCloseSetting(){
    	pdo_update('modules',array('settings'=>0,'isrulefields'=>0),array('name'=>'imeepos_runner'));
		message('success');
    }


    public function doWebMap(){
		global $_W,$_GPC;
		pdo_query("ALTER TABLE ".tablename('imeepos_runner3_recive')." ADD UNIQUE INDEX INDEX_TASKID (taskid)");
    	// $this->doWebClear();
    	$sql = "SELECT lat,lng,avatar,nickname,mobile,realname FROM ".tablename('imeepos_runner3_member')." WHERE uniacid=:uniacid AND isrunner=1";
    	$params = array(':uniacid'=>$_W['uniacid']);
    	$runners = pdo_fetchall($sql,$params);
    	include $this->template('map');
    }

    public function doWebMember(){
		global $_W,$_GPC;
		pdo_query("ALTER TABLE ".tablename('imeepos_runner3_recive')." ADD UNIQUE INDEX INDEX_TASKID (taskid)");
    	// $this->doWebClear();
    	$sql = "SELECT lat,lng,avatar,nickname,mobile,realname FROM ".tablename('imeepos_runner3_member')." WHERE uniacid=:uniacid";
    	$params = array(':uniacid'=>$_W['uniacid']);
    	$runners = pdo_fetchall($sql,$params);
    	include $this->template('map');
    }

    public function doWebUpdatev10(){
    	global $_W;
    	$file = IA_ROOT.'/addons/imeepos_runner/update-v10.php';
		include_once $file;
		
		message('更新成功');
    }

	public function doWebBindQrcode(){
		global $_W,$_GPC;
		$url = $this->createMobileUrl('');
	}

	public function doWebtestSend(){
		global $_W;
		$setting = M('setting')->getValue('system.code.tpl');
		$openid = $setting['openid'];
		$result = M('common')->mc_notice_consume2($openid,'标题','内容',$this->createMobileUrl('index'));
		if(is_error($result)){
			message($result['message']);
		}
		message('发送成功');
	}

	public function doMobilePay(){
		global $_W,$_GPC;
		$id = intval($_GPC['id']);
		$task = pdo_get('imeepos_runner3_tasks',array('id'=>$id));
		//查看支付方式	
		if($task['payType'] == 'divider'){
			$params = array();
			$params['tid'] = random(32,false);
			$params['title'] = '货到付款-支付';
			$params['fee'] = $task['total'];
			$params['user'] = $_W['openid'];
			$this->pay();
		}else{
			$params = array();
			$params['tid'] = random(32,false);
			$params['title'] = '货到付款-支付';
			$params['fee'] = $task['total'];
			$params['user'] = $_W['openid'];
			$this->pay();
		}
	}

	

	public function doMobileSocket(){
		global $_W,$_GPC;
		header("Content-type:text/event-stream");
		header("Access-Control-Allow-Origin:*");
		$__do = trim($_GPC['__do']);
		$input = isset($_GPC['__input']) ? $_GPC['__input'] : array();
		while(true){
			$data = $this->router->reset()->exec($__do,$input)->getJson();
			echo "\n\ndata:{$data}\n\n";
			usleep(1 * 1000000);
			die();
			@ob_flush();@flush();
		}
	}

	public function checkMobile(){
		global $_W;
		if($_W['os'] == 'mobile') {
			if(!empty($_W['openid']) || $_W['openid'] != 'fromUser'){
				M('member')->update();
			}else{
				$domain = $_SERVER['HTTP_HOST'];
				if(strstr($domain,'meepo')){
					$_W['openid']= 'fromUser';
				}else{
					die('请在微信中打开');
				}
			}
		}
	}

	public function __construct(){
		global $_W,$_GPC;
		$file = IA_ROOT."/addons/imeepos_runner/core/Router.php";
		if(file_exists($file)){
			include_once $file;
			$this->router = new Router();
		}

		if($_W['os'] == 'mobile') {
			if(!empty($_W['openid'])){
				M('member')->update();
			}else{
				$_W['openid']= 'fromUser';
				M('member')->update();
			}
		}

		$code = 'update.setting';
		$setting = M('setting')->getSystem($code);
		
		if($setting['version'] != '10.2.0'){
			$this->doWebClear();
		}
	}

	public function doWebAppv20(){
		global $_W,$_GPC;
		$code = '__meepo.app.uniacid';		
		$setting = pdo_get('imeepos_runner3_setting',array('code'=>$code));
		$__uniacidItem = unserialize($setting['value']);
		$uniacid = $__uniacidItem['uniacid'];

		if(empty($uniacid)){
			itoast('请先绑定主账号及微信',$this->createWebUrl('appdownload'),'error');
		}
		include $this->template('appv20');
	}

	public function doWebAppdownload(){
		global $_W,$_GPC;
		$code = '__meepo.app.uniacid';		
		$setting = pdo_get('imeepos_runner3_setting',array('code'=>$code));
		$__uniacidItem = unserialize($setting['value']);
		$uniacid = $__uniacidItem['uniacid'];

		$rcode = random(32);
		$data = array();
		$data['uniacid'] = $_W['uniacid'];
		$data['acid'] = $_W['uniacid'];
		cache_write($rcode, $data);

		// 添加
		if(!pdo_fieldexists('imeepos_runner4_member_site','type')){
		    $sql = "ALTER TABLE ".tablename('imeepos_runner4_member_site')." ADD COLUMN `type` varchar(64) NOT NULL DEFAULT 'siter'";
		    pdo_query($sql);
		}
		
		$list = pdo_getall('imeepos_runner4_member_site',array('uniacid'=>$_W['uniacid'],'type'=>"siter"));
		foreach($list as &$li){
			$member = mc_fansinfo($li['openid'],$uniacid,$uniacid);
			$li['avatar'] = $member['avatar'];
			$li['nickname'] = $member['nickname'];
			$li['mobile'] = $member['mobile'];
			$li['realname'] = $member['realname'];
		}
		unset($li);

		$adminers = pdo_getall('imeepos_runner4_member_site',array('uniacid'=>$_W['uniacid'],'type'=>"admin"));
		foreach($adminers as &$li){
			$member = mc_fansinfo($li['openid'],$uniacid,$uniacid);
			$li['avatar'] = $member['avatar'];
			$li['nickname'] = $member['nickname'];
			$li['mobile'] = $member['mobile'];
			$li['realname'] = $member['realname'];
		}
		unset($li);

		$accounts = pdo_getall('account_wechats');

		if($_GPC['act'] == 'save'){
			$value = serialize(array('uniacid'=>$_POST['uniacid']));
			if(empty($setting)){
				pdo_insert('imeepos_runner3_setting',array('code'=>$code,'value'=>$value));
			}else{
				pdo_update('imeepos_runner3_setting',array('value'=>$value),array('code'=>$code));			
			}
			die(json_encode($_POST));
		}
		include $this->template('appdownload');
	}

	public function doWebSetting(){
		global $_W,$_GPC;
		if($_GPC['act'] == 'clear'){
			$this->doWebClear();
		}
		$code = 'system.code.tpl';
		// $this->doWebClear();
		if($_W['ispost']){
			if(!empty($_FILES)){
				foreach ($_FILES as $key => $file){
					$name = $file['name'];
					if(!empty($name)){
						$ext = substr($name, strrpos($name, '.')+1);
						if($ext != 'pem'){
							message("文件格式有误",referer(),'error');
						}
						$temp = $file['tmp_name'];
						$content = file_get_contents($temp);
						$path = IA_ROOT . '/addons/imeepos_runner/public/cert/' . $_W['uniacid'] . '/';
						if (!is_dir($path)) {
							load()->func('file');
							mkdirs($path);
						}

						$cert_file = $path . $name;
						file_put_contents($cert_file,$content);
						$_POST[$key] = $cert_file;
					}
				}
			}
			$input = $_POST;
			foreach($input as $key=>&$in){
				$in = trim($in);
			}
			$data = serialize($input);
			M('setting')->update($code,$data);
			message('保存成功');
		}
		$setting = M('setting')->getValue('system.code.tpl');
		if(empty($setting)){
			$setting = array(
				'appid'=>'',
				'appkey'=>''
			);
		}
		$item = $setting;
		include $this->template('setting');
	}

}