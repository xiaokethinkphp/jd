<?php  
namespace app\user\model;
/**
* 用户购物车模型
*/
class Shopcart extends \think\Model
{
	protected $resultSetType = 'collection';
	public function user(){
		//购物车和用户的多对一关系
		return $this->belongsTo('User');
	}
}
?>