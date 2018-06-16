<?php  
namespace app\index\controller;
/**
* 商品的控制器
*/
class Introduction extends \think\Controller
{
	
	public function index()
	{
		//显示商品详情的控制器
		return view('introduction');
	}
}
?>