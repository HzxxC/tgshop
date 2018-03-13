<?php
/**
 * 管理员会员积分配置操作
 *
 * ==========================================================================
 * ==========================================================================
 *
 * @author    Pengchu
 *
 */
namespace osc\admin\controller;
use osc\common\controller\AdminBase;
use think\Db;
class Search extends AdminBase{
	
	protected function _initialize(){
		parent::_initialize();
		$this->assign('breadcrumb1','搜索');
		$this->assign('breadcrumb2','热词管理');
	}
	
    public function index(){     	
		
		$list = Db::name('search_hot')->paginate(config('page_num'));
		$this->assign('empty', '<tr><td colspan="20">~~暂无数据</td></tr>');
		$this->assign('list', $list);
	
		return $this->fetch();

	}

	public function add(){
		
		if(request()->isPost()){
			
			$data=input('post.');	
			unset($data['id']);		

			$data['date_added'] = time();
			
			$id=Db::name('Search_hot')->insert($data,false,true);
			
			if($id){
				
				storage_user_action(UID,session('user_auth.username'),config('BACKEND_USER'),'添加了搜索热词，'.$data['keyword']);	
											
				return ['success'=>'新增成功','id'=>$id,'keyword'=>$data['keyword']];
			}else{
				return ['error'=>'新增失败'];
			}
			
		}
	}
	
	public	function edit(){
		if(request()->isPost()){
			
			$data=input('post.');	
			
			$r=Db::name('Search_hot')->update($data);
			
			if($r){
				
				storage_user_action(UID,session('user_auth.username'),config('BACKEND_USER'),'修改了搜索热词'.$data['keyword']);
				
				return ['success'=>'修改成功','keyword'=>$data['keyword']];				
			
			}else{
								
				return ['error'=>'修改失败'];
			}
		}
	}

	function del(){
		
		if(request()->isPost()){
			$id=(int)input('post.id');
			
			if(Db::name('Search_hot')->where('id',$id)->delete()){
				
				storage_user_action(UID,session('user_auth.username'),config('BACKEND_USER'),'删除了搜索热词，id='.$id);
								
				return ['success'=>'删除成功'];
			}
		}		
	}

	public function get_info() {
		if(request()->isPost()){
			$id=(int)input('id');
			$d=Db::name('Search_hot')->find($id);
			
			return ['keyword'=>$d['keyword'],'sort_order'=>$d['sort_order'],'status'=>$d['status']] ;
		}
	}

	//更新状态
	function set_status(){
		$data=input('param.');
		
		$update['id']=(int)$data['id'];
		$update['status']=(int)$data['status'];
		
		if(Db::name('Search_hot')->update($update)){
			storage_user_action(UID,session('user_auth.username'),config('BACKEND_USER'),'更新热词状态');
			$this->redirect('Search/index');
		}
	}	
	 
}
?>