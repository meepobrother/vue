<?php
/**
 * 跑腿2.0模块PC接口定义
 *
 * @author imeepos
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class Imeepos_runnerproModuleWebapp extends WeModuleWebapp {
	public function doPageTest(){
		global $_GPC, $_W;
		$errno = 0;
		$message = '返回消息';
		$data = array();
		return $this->result($errno, $message, $data);
	}
}