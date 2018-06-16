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
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }

    public function sendemail()
    {
    	$email = '425532268@qq.com';  //
		$title = '小可老师';  //标题
		$content = '哈哈哈';  //邮件内容
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
