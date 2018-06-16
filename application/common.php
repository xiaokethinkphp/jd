<?php

// use phpmailer\phpmailer;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
    function SendMail($address,$title,$message)
    /*发送邮件的方法*/
    {
        vendor('PHPMailer.class#PHPMailer');
        $mail=new PHPMailer();          // 设置PHPMailer使用SMTP服务器发送Email
        $mail->IsSMTP();                // 设置邮件的字符编码，若不指定，则为'UTF-8'
        $mail->CharSet='UTF-8';         // 添加收件人地址，可以多次使用来添加多个收件人
        $mail->AddAddress($address);    // 设置邮件正文
        $mail->Body=$message;           // 设置邮件头的From字段。
        $mail->From=config('MAIL_ADDRESS');  // 设置发件人名字
        $mail->FromName='小可老师';  // 设置邮件标题
        $mail->Subject=$title;          // 设置SMTP服务器。
        $mail->Host=config('MAIL_SMTP');     // 设置为"需要验证" ThinkPHP 的config方法读取配置文件
        $mail->SMTPAuth=true;           // 设置用户名和密码。
        $mail->Username=config('MAIL_LOGINNAME');
        $mail->Password=config('MAIL_PASSWORD'); // 发送邮件。
        return($mail->Send());
    }

    function SendMessage($phonenum)
    /*发送短信方法*/
        {
            // 配置信息
            import('dayu.top.TopClient');
            import('dayu.top.TopLogger');
            import('dayu.top.request.AlibabaAliqinFcSmsNumSendRequest');
            import('dayu.top.ResultSet');
            import('dayu.top.RequestCheckUtil');
            $appkey = config('appkey');
            $secretKey = config('secretKey');
            
            $c = new \TopClient;
            $c ->appkey = $appkey ;
            $c ->secretKey = $secretKey ;
            
            $num = rand(100000,999999);

            cookie((string)$phonenum,$num,60);
            $req = new \AlibabaAliqinFcSmsNumSendRequest;
            $req ->setExtend( "" );
            $req ->setSmsType( "normal" );
            $req ->setSmsFreeSignName( "小可THINKP" );
            $req ->setSmsParam( "{code:'".$num."'}" );
            $req ->setRecNum( $phonenum );
            $req ->setSmsTemplateCode( "SMS_69190745" );
            $resp = $c ->execute( $req );


        }
        function hide_phone($str){  
            /*电话号码替换中间四位数*/
            $resstr=substr_replace($str,'****',3,4);  
            return $resstr;  
        }  
