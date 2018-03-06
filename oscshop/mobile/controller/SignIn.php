<?php
/**
 * oscshop2 会员签到
 *
 * ==========================================================================
 * ==========================================================================
 *
 * @author   Pengchu
 */
 
namespace osc\mobile\controller;
use think\Db;
class SignIn extends MobileBase
{
	protected function _initialize(){
		parent::_initialize();						
		define('UID',osc_service('mobile','user')->is_login());	
		//手机版
		if(!UID){
			if(!in_wechat()){
				$this->redirect('login/login');	
			}else{
				$this->error('系统错误');
			}			
		}		
	}
	
	function index(){

		$this->assign('SEO',['title'=>'会员签到']);
		
		$this->assign('top_title','会员签到');

		$this->assign('has_sign_in', member_has_sign_in(UID));

		$this->assign('flag','user');
		return $this->fetch();
	}
	
	public function sign_in() {
		if(request()->isPost()){

			$points = get_member_sign_in_points();
			
			if (empty($points)) {
				return ['error'=>'签到功能未开启'];
			}
			return member_sign_in(UID, $points, "会员签到");
		}
	}
}
