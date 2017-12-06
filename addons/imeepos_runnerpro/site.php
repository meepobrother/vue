<?php
/**
 * 跑腿2.0模块微站定义
 *
 * @author imeepos
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class Imeepos_runnerproModuleSite extends WeModuleSite {

	public function doMobileList() {
		//列表
		global $_W,$_GPC;

		include $this->template('v2/list');
	}
	public function doMobileHome() {
		//个人中心
		global $_W,$_GPC;
		
		include $this->template('v2/home');
	}
	public function doMobilePost() {
		//发布
		global $_W,$_GPC;
		
		include $this->template('v2/post');
	}
	public function doMobileSearch() {
		//搜索
		global $_W,$_GPC;
		
		include $this->template('v2/search');
	}
	public function doMobileDetail() {
		//详情
		global $_W,$_GPC;
		
		include $this->template('v2/detail');
	}
	public function doWebIndex(){
		include $this->template('index');
	}

}