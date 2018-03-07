<?php
/**
 * oscshop2 B2C电子商务系统
 *
 * ==========================================================================
 * @link      http://www.oscshop.cn/
 * @copyright Copyright (c) 2015-2016 oscshop.cn. 
 * @license   http://www.oscshop.cn/license.html License
 * ==========================================================================
 *
 * @author    李梓钿
 *
 */
namespace osc\admin\controller;
use osc\common\controller\AdminBase;
use think\Db;
class Advert extends AdminBase{
	
	protected function _initialize(){
		parent::_initialize();
		$this->assign('breadcrumb1','广告');
		$this->assign('breadcrumb2','广告管理');
	}
	//广告列表
    public function index(){

		$filter=input('param.');
        
		
		$list=osc_advert()->get_advert_list($filter,config('page_num'));

		$this->assign('empty','<tr><td colspan="20">没有数据~</td></tr>');
		
		
		$this->assign('list',$list);
	
		return $this->fetch();

	 }
	 //新增广告
	 public function add(){
		
		if(request()->isPost()){
			
			$data=input('post.');
			
			$model=osc_model('admin','advert');  	
			
			$error=$model->validate($data);	
	
			if($error){					
				$this->error($error['error']);	
			}
			
			$return=$model->add_advert($data);		
			
			if($return){
												
				storage_user_action(UID,session('user_auth.username'),config('BACKEND_USER'),'新增了广告');		
			
				$this->success('新增成功！',url('Advert/index'));			
			}else{
				$this->error('新增失败！');			
			
			}
			
		}
		
	 	$this->assign('crumbs', '新增');
		$this->assign('action', url('Advert/add'));
		
	 	return $this->fetch('edit');
	 }
	 //商品基本信息
	 public function edit_general(){
	 	
		if(request()->isPost()){
			
			$data=input('post.');
			
			if(empty($data['name'])){
				$this->error('广告名称必填！');	
			}
			
			try{
				
				Db::name('advert')->update($data,false,true);
				storage_user_action(UID,session('user_auth.username'),config('BACKEND_USER'),'更新广告信息');							
				return $this->success('更新成功！',url('Advert/index'));
				
			}catch(Exception $e){
				return $this->error('更新失败！'.$e);	
			}
			
		}
		
	 	$this->assign('advert',Db::name('Advert')->find((int)input('id')));
		
	 	$this->assign('crumbs', '编辑广告信息');	
		
	 	return $this->fetch('general');
	 }
	
	 
	//删除商品
	function del(){
		
		$model=osc_model('admin','advert'); 
			
		$r=$model->del_advert((int)input('param.id'));	
		
		if($r){
			
			storage_user_action(UID,session('user_auth.username'),config('BACKEND_USER'),'删除广告'.input('get.id'));
			
			$this->redirect('Advert/index');
			
		}else{
			
			return $this->error('删除失败！',url('Advert/index'));
		}		
		
	}
	//更新状态
	function set_status(){
		$data=input('param.');
		
		$update['advert_id']=(int)$data['id'];
		$update['status']=(int)$data['status'];
		
		if(Db::name('advert')->update($update)){
			storage_user_action(UID,session('user_auth.username'),config('BACKEND_USER'),'更新广告状态');
			$this->redirect('Advert/index');
		}
	}	
	
	//更新排序
	function update_sort(){
		$data=input('post.');
		
		$update['advert_id']=(int)$data['advert_id'];
		$update['sort_order']=(int)$data['sort'];
		
		if(Db::name('advert')->update($update)){
			storage_user_action(UID,session('user_auth.username'),config('BACKEND_USER'),'更新广告排序');
			return true;
		}		
	}
	
}
?>