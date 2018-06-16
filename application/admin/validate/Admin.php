<?php  
namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        'admin_name'  =>  'require|alphaNum|length:3,10|unique:admin,admin_name',
        'admin_password' =>  'require|length:6,10|confirm:admin_password1',
        'captcha|验证码'=>'require|captcha'
    ];
    protected $message = [
        'admin_name.require'    => '请输入管理员名称名称',
        'admin_name.alphaNum'   => '名称只能是字母和数字',
        'admin_name.length'     => '管理员名称长度介于3~10之间',
        'admin_name.unique'     =>  '管理员已经存在，请重新添加',
        'admin_password.require'  => '请输入管理员密码',
        'admin_password.length' =>  '管理员密码长度介于6~10之间',
        'admin_password.confirm'    =>  '两次输入的密码不一致'
    ];
    protected $scene = [
        'admin_name'    =>  ['admin_name'],
    ];

}
?>