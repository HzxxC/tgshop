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
namespace osc\member\controller;
use osc\common\controller\AdminBase;
use think\Db;
class PointsSetting extends AdminBase{
	
	protected function _initialize(){
		parent::_initialize();
		$this->assign('breadcrumb1','会员');
		$this->assign('breadcrumb2','积分管理');
	}
	
    public function index(){     	
		
		$list = Db::name('points_setting')->paginate(config('page_num'));
		$this->assign('empty', '<tr><td colspan="20">~~暂无数据</td></tr>');
		$this->assign('list', $list);
	
		return $this->fetch();

	}
	
	public	function edit(){
		if(request()->isPost()){
			
			$data=input('post.');	
			
			$result = $this->validate($data,'PointsSetting');
			
			if($result!==true){
				return ['error'=>$result];
			}
			
			$r=Db::name('Points_setting')->update($data);
			
			if($r){
				
				storage_user_action(UID,session('user_auth.username'),config('BACKEND_USER'),'修改了会员积分配置参数'.$data['name']);
				
				return ['success'=>'修改成功','name'=>$data['name']];				
			
			}else{
								
				return ['error'=>'修改失败'];
			}
		}
	}

	public function get_info() {
		if(request()->isPost()){
			$id=(int)input('id');
			$d=Db::name('points_setting')->find($id);
			
			return ['name'=>$d['name'],'points'=>$d['points'],'status'=>$d['status'],'type'=>$d['type']] ;
		}
	}
	 
}
?>