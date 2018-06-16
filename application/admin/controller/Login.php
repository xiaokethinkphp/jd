<?php  
namespace app\admin\controller;
/**
* 
*/
class Login extends \think\Controller
{
	
	public function login(){
		if (session('?admin_name')) {
			$this->error('您已经登录，请退出后再重新登录','index/index');
		}
		return view();
	}

	public function checklogin(){
		$post = request()->post();
		if (empty($post)) {
			$this->redirect('login/login');
		}
		if(!captcha_check($post['captcha'])){
			$this->error('验证码错误！','login/login');
		};
		$admin_find = db('admin')->where('admin_name','eq',$post['admin_name'])->find();
		if (empty($admin_find)) {
			$this->error('该管理员不存在，请重新登录','login/login');
		}
		else{
			$admin_password = $admin_find['admin_password'];
			if (md5($post['admin_password'])==$admin_password) {
				session('admin_id',$admin_find['admin_id']);
				session('admin_name',$admin_find['admin_name']);
				$this->success('登陆成功','index/index');
			}
			else{
				$this->error('管理员密码错误，请重新登录','login/login');
			}
		}
		
	}
	public function logout(){
		session('admin_name',null);
		session('admin_id',null);
		$this->redirect('login/login');
	}
}
?>