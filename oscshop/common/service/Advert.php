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
 * 公共数据获取
 * 
 */
namespace osc\common\service;
use think\Db;
class Advert{
	
	/**
     * object 对象实例
     */
    private static $instance;
	
	//禁外部实例化
	private function __construct(){}
	
	//单例模式	
	public static function getInstance(){    
        if (!(self::$instance instanceof self))  
        {  
            self::$instance = new self();  
        }  
        return self::$instance;  
    }
	//禁克隆
	private function __clone(){}  
	
		
	/**
	 * 根据条件取得广告列表
	 * @param array $filter 条件
	 * @return object(think\paginator\Collection)  
	 */
	public function get_advert_list($filter,$page_num=10){
		
		$map=[];
		
		if(isset($filter['name'])){
			$map['name']=['like',$filter['name']];		
		}

		if(isset($filter['status'])){	
			$map['status']=['eq',$filter['status']];	
		}

		if(isset($filter['type'])){	
			$map['type']=['eq',$filter['type']];	
		}

		if(isset($filter['location'])){	
			$map['location']=['eq',$filter['location']];	
		}
		
		$map['advert_id']=['GT','0'];
		
		return Db::name('advert')
		->where($map)->order('advert_id desc')
		->paginate($page_num);
		
	}
	
	//商品详情信息
	public function get_goods_info($goods_id){
		
		if(!$goods=Db::name('goods')->alias('g')->join('__GOODS_DESCRIPTION__ gd','g.goods_id = gd.goods_id')->where('g.goods_id',$goods_id)->find()){
			return false;
		}
		return [
			'goods'=>$goods,
			'image'=>Db::name('goods_image')->where('goods_id',$goods_id)->select(),
			'options'=>$this->get_goods_options($goods_id),
			'discount'=>Db::name('goods_discount')->where('goods_id',$goods_id)->order('quantity ASC')->select(),
			'mobile_description'=>Db::name('goods_mobile_description_image')->where('goods_id',$goods_id)->order('sort_order asc')->select()
		];
	}
	//更新商品点击量
	public function update_goods_viewed($goods_id){
		Db::execute("UPDATE ".config('database.prefix')."goods SET viewed = (viewed + 1) WHERE goods_id =".$goods_id);	
	}
}
