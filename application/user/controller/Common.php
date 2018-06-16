<?php  
namespace app\user\controller;
/**
* 
*/
class Common extends \think\Controller
{
	
	public function _initialize(){
		if(!session('?user_email')){
			$this->error('您还没有登录','login/login');
		}
	}
}
?>