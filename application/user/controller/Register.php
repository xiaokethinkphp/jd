<?php  
namespace app\user\controller;

/**
* 用户注册的控制器
*/
class Register extends \think\Controller
{
	
	public function register()
	{
		/*显示用户注册界面的方法*/
		return view();
	}

	public function registerhanddle()
	{
		/*处理用户提交注册请求的方法*/
		$post = request()->post();
		$post['user_password'] = md5($post['user_password']);
		$user_register_result = db('user')->insert($post);
		if ($user_register_result) {
			$this->success('恭喜你注册成功','index/index/index');
		}
		else{
			$this->error('注册失败，请重新注册');
		}
	}

	public function registerhanddle1()
	{
		/*处理用户提交注册请求的方法*/
		$post = request()->post();
		$post['user_password'] = md5($post['user_password1']);
		unset($post['user_password1']);
		$user_register_result = db('user')->insert($post);
		if ($user_register_result) {
			$this->success('恭喜你注册成功','index/index/index');
		}
		else{
			$this->error('注册失败，请重新注册');
		}
	}

	public function checkusername()
	{
		/*使用Ajax对用户注册时邮箱是否已经存在进行验证*/
		if (request()->isAjax()) {
			//如果是Ajax请求
			$post = request()->post();
			$user_email = $post['param'];
			$user_email_find = db('user')->where('user_email','eq',$user_email)->find();
			//查看传递过来的邮箱地址是否存在
			if ($user_email_find) {
				return ['status'=>'n','info'=>'该邮箱已经被注册'];
			}
			else {
				return ['status'=>'y','info'=>'该邮箱可以使用'];
			}
		}
	}
	public function checkusername1()
	{
		/*使用Ajax对用户注册时手机是否已经存在进行验证*/
		if (request()->isAjax()) {
			//如果是Ajax请求
			$post = request()->post();
			$user_email = $post['param'];
			$user_email_find = db('user')->where('user_phone','eq',$user_email)->find();
			//查看传递过来的邮箱地址是否存在
			if ($user_email_find) {
				return ['status'=>'n','info'=>'该手机已经被注册'];
			}
			else {
				return ['status'=>'y','info'=>'该手机可以使用'];
			}
		}
	}

	public function sendMobileCode()
	/*用户获取验证码的方法*/
	{
		if (request()->isAjax()) {
			$post = request()->post();
			$num = $post['num'];
			SendMessage($num);
		}
	}

	public function checkmobilenum()
	/*验证验证码是否有效*/
	{
		if (request()->isAjax()) {
			$post = request()->post();
			$code = $post['param'];
			if ($code==session('mobilenum')) {
				return ['status'=>'y','info'=>'验证码正确'];
			}
			else{
				return ['status'=>'n','info'=>'验证码错误'];
			}
		}
	}
}
?>