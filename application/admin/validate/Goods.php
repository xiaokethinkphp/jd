<?php  
namespace app\admin\validate;

use think\Validate;

class Goods extends Validate
{
    protected $rule = [
        'goods_name'  =>  'require|max:90',
        'goods_thumb' =>  'require',
        'goods_price'	=>	'require|egt:1|float',
        'goods_after_price'   =>  'require|egt:0|float',
        'goods_sales'	=>	'require|egt:1|float',
        'goods_inventory'	=>	'require|egt:1|float',
        'goods_pid'	=>	'require',
    ];
    protected $message = [
    'goods_name.require' => '请输入商品名称',
    // 'name.max'     => '名称最多不能超过25个字符',
    // 'age.number'   => '年龄必须是数字',
    // 'age.between'  => '年龄必须在1~120之间',
    // 'email'        => '邮箱格式错误',
];

}
?>