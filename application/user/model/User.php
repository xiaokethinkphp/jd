<?php  
namespace app\user\model;
/**
* 用户模型
*/
class User extends \think\Model
{
	protected $resultSetType = 'collection';
	public function address()
	/*用户和地址的一对多关系*/
	{
		return $this->hasMany('Address');
	}

	public function shopcart()
	/*用户和购物车的一对多关系*/
	{
		return $this->hasMany('Shopcart');
	}

	public function order()
	/*用户和订单的一对多关系*/
	{
		return $this->hasMany('Order');
	}

	public function commentgoods()
	/*用户和商品评论的一对多关系*/
	{
		return $this->hasMany('Commentgoods');
	}
}
?>