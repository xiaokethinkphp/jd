<?php  
namespace app\user\model;
/**
* 用户地址模型
*/
class Address extends \think\Model
{
	protected $resultSetType = 'collection';
	public function user(){
		//商品和分类的多对一关系
		return $this->belongsTo('User');
	}

	public function order()
	/*地址和订单的一对多关系*/
	{
		return $this->hasMany('Order');
	}

	public function province()
	/*地址和省份的多对一关系*/
	{
		return $this->belongsTo('Province','user_address_province');
	}

	public function city()
	/*地址和城市的多对一关系*/
	{
		return $this->belongsTo('City','user_address_city');
	}

	public function district()
	/*地址和区县的多对一关系*/
	{
		return $this->belongsTo('District','user_address_district');
	}
}
?>