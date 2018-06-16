<?php  
namespace app\user\model;
/**
* 订单商品模型
*/
class Ordergoods extends \think\Model
{
	protected $resultSetType = 'collection';
	public function order()
	{
		//订单和用户的多对一关系
		return $this->belongsTo('Order');
	}

	public function goods()
	/*订单商品和商品信息的多对一关系*/
	{
		return $this->belongsTo('app\admin\model\Goods');
	}
}
?>