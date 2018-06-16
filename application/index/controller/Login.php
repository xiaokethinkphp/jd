<?php  
namespace app\index\controller;
/**
* 用户登录控制器
*/
class login extends \think\Controller
{
	
	public function index(){
		//用户登录界面显示
		return view('login');
	}
}
?>