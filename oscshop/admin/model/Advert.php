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
 * @author    Pengchu
 *
 */
namespace osc\admin\model;
use think\Db;
class Advert{
	
	public function validate($data){

		$error=array();
		if(empty($data['name'])){
			$error['error']='广告名称必填';
		}elseif($data['type'] == 2 && empty($data['href'])) {
			$error['error']='站外广告链接地址必填';
		} 
		
		if($error){
			return $error;				
		}
						
	}
	
	public function add_advert($data){		
			
			$advert['name']=$data['name'];
			$advert['image']=$data['image'];
			$advert['type']=$data['type'];
			if ($data['type'] == 2) {
				$advert['href']=$data['href'];
			}
			$advert['location']=$data['location'];
			
			$advert['status']=$data['status'];
			$advert['sort_order']=(int)$data['sort_order'];
			$advert['date_added']=date('Y-m-d H:i:s',time());
			$advert['description'] = $data['description'];
			
			return Db::name('advert')->insert($advert,false,true);
		
	}
	
	public function del_advert($advert_id){
		
		try{
								
			Db::name('advert')->where('advert_id',$advert_id)->delete();
			
			return true;
		}catch(Exception $e){
			return false;
		}
	}
		
}
?>