<?php  
namespace app\admin\validate;

use think\Validate;

class Property extends Validate
{
    protected $rule = [
        'property_name'  =>  'require|max:90|unique:property,property_name^property_pid',
        'property_pid'	=>	'require|gt:0',
    ];
    protected $message = [
        'property_name.require' => '请输入属性名称',
        'property_name.unique'  =>  '属性已经存在',
        'property_pid.gt'	=>	'请选择所属分类'
];

}
?>