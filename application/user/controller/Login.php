<?php  
namespace app\user\controller;

/**
* 用户登录控制器
*/
class Login extends \think\Controller
{
	
	public function login()
	/*用户登录界面显示*/
	{
		return view();
	}

	public function loginhanddle()
	/*用户登录的提交方法*/
	{
		$post = request()->post();
		$user_email = $post['user_email'];
		$user_password = md5($post['user_password']);
		$user_find = db('user')->where('user_email','eq',$user_email)->where('user_password','eq',$user_password)->find();
		if(empty($user_find)){
			//用户或密码错误的情况
			$this->error('用户或密码错误，请重新登录','user/login/login');
		}
		else if($user_find['user_email_active']=='0'){
			//用户邮箱没有激活的情况
			$this->error('该邮箱并未激活，请登录该邮箱进行激活',url('user/login/active',array('user_email'=>$user_find['user_email'])));
		}
		else{
			//用户邮箱已经激活的情况
			session('user_id',$user_find['user_id']);
			session('user_email',$user_find['user_email']);
			$this->success('登录成功！','index/index/index');
		}
	}

	public function loginout()
	/*用户登出的方法*/
	{
		session('user_id',null);
		session('user_email',null);
		$this->redirect('index/index/index');
	}

	public function active($user_email)
	{
		$title = 'JD商城';  //标题
		$address = url('user/login/active1',array('user_email'=>$user_email));
		$address = urldecode($address);
		$content = '请访问以下地址进行激活http://localhost'.$address; dump($content); 
		//邮件内容
		SendMail($user_email,$title,$content); //直接调用发送即可
	}

	public function active1($user_email)
	{
		$user_email_find = db('user')->where('user_email','eq',$user_email)->find();
		if ($user_email_find) {
			if ($user_email_find['user_email_active']=='1') {
				$this->error('该邮箱已经激活，请重新登录','user/login/login');
			}
			else{
				db('user')->update(['user_email_active'=>'1','user_id'=>$user_email_find['user_id']]);
				$this->success('该邮箱被成功激活，请登录','user/login/login');
			}
		}
		else{
			$this->redirect('index/index/index');
		}
	}
}
?>