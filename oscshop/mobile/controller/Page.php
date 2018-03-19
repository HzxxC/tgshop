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
 
namespace osc\mobile\controller;
use think\Db;
class Page extends MobileBase
{
 	function index(){
 		
		cookie('jump_url',request()->url(true));
		
		$this->assign('SEO',['title'=>config('SITE_TITLE'),'keywords'=>config('SITE_KEYWORDS'),'description'=>config('SITE_DESCRIPTION')]);
		
		$id = input('advert_id');
		// 获得广告详情
		$info = get_advert_info($id);

		$info['description'] = htmlspecialchars_decode($info['description']);
		$this->assign('advert_info', $info);
		$this->assign('top_title', $info['name']);

		if(in_wechat())
			$this->assign('signPackage',wechat()->getJsSign(request()->url(true)));	
		
        return $this->fetch('index');
    }

}
