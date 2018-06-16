<?php  
namespace app\admin\validate;

use think\Validate;

class Keywords extends Validate
{
    protected $rule = [
        'keywords_name'  =>  'require|max:90|unique:keywords,keywords_name',
    ];
    protected $message = [
        'keywords_name.require' => '请输入关键字名称',
        'keywords_name.unique'  =>  '关键字已经存在',
];

}
?>