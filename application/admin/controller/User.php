<?php  
namespace app\admin\controller;
/**
* 用户控制器
*/
class User extends Common
{
	
	public function userlist(){
		//显示用户列表
		$user_select = db('user')->select();
		$this->assign('user_select',$user_select);
		return view();
	}

}
?>