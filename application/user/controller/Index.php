<?php
namespace app\user\controller;

// use alidayu\TopClient;
// use alidayu\AlibabaAliqinFcSmsNumSendRequest;

class Index
{
	public function index1()
	{
		
	}
    public function index()
    {
        return '';
    }

    public function sendemail()
    {
    	$email = '2235275483@qq.com';  //
		$title = '极客宇宙商城';  //标题
		// $address = url('user/login/active1',array('user_email'=>$user_email));
		$content = '请访问以下地址进行激活：http://localhost';
		// .$address;  //邮件内容
		dump($content);
		SendMail($email,$title,$content); //直接调用发送即可
    }

    public function sessiondelete()
    {
    	session('user_email',null);
    }

    public function sms()
    {
    	// 配置信息
		import('dayu.top.TopClient');
		import('dayu.top.request.AlibabaAliqinFcSmsNumSendRequest');
		import('dayu.top.ResultSet');
		import('dayu.top.RequestCheckUtil');
    	$appkey = config('appkey');
    	$secretKey = config('secretKey');
    	
		$c = new \TopClient;
		$c ->appkey = $appkey ;
		$c ->secretKey = $secretKey ;
		
		$req = new \AlibabaAliqinFcSmsNumSendRequest;
		$req ->setExtend( "" );
		$req ->setSmsType( "normal" );
		$req ->setSmsFreeSignName( "小可THINKP" );
		$req ->setSmsParam( "{code:'123456'}" );
		$req ->setRecNum( "15040366148" );
		$req ->setSmsTemplateCode( "SMS_69190745" );
		$resp = $c ->execute( $req );

	}

	public function send()
	{
		SendMessage('17702414036');
	}

	public function send1()
	{
		$phonenum = '17702414036';
		dump(cookie($phonenum));
	}
}
