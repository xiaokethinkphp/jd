<?php  
namespace app\user\model;
/**
* 用户购物车模型
*/
class Order extends \think\Model
{
	protected $resultSetType = 'collection';
	public function user(){
		//订单和用户的多对一关系
		return $this->belongsTo('User');
	}
	public function ordergoods()
	/*订单和订单商品的一对多关系*/
	{
		return $this->hasMany('Ordergoods');
	}

	public function address()
	/*订单和地址的多对一关系*/
	{
		return $this->belongsTo('Address','order_address');
	}

	public function commentgoods()
	/*订单和具体评论内容的一对多关系*/
	{
		return $this->hasMany('Commentgoods');
	}
}
?>