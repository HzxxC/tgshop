<?php
/**
 * 会员积分配置
 *
 * ==========================================================================
 * ==========================================================================
 *
 * @author    Pengchu
 *
 */
namespace osc\member\validate;
use think\Validate;
class PointsSetting extends Validate
{
    protected $rule = [
        'points'  =>  'relevance_status',
          
    ];

    protected $message = [
        'points'  =>  '请设置积分大小',
    ];

    protected function relevance_status($value, $rule, $data) {

        return ($data['status'] == 1 && $value != 0) ? true : false;
    }

	
}
?>