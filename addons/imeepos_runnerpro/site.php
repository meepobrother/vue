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
	}
	public function doMobileHome() {
		//个人中心
	}
	public function doMobilePost() {
		//发布
	}
	public function doMobileSearch() {
		//搜索
	}
	public function doMobileDetail() {
		//详情
	}
	public function doWebIndex(){
		include $this->template('index');
	}

}