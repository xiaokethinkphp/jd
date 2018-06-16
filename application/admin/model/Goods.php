<?php  
namespace app\admin\model;
/**
* 
*/
class Goods extends \think\Model
{	
	protected $resultSetType = 'collection';
	public function keywords(){
		return $this->belongsToMany('Keywords','goods_keywords');
	}
	public function cate(){
		//商品和分类的多对一关系
		return $this->belongsTo('Cate','goods_pid');
	}
	public function img(){
		//商品的细节图的一对多关系
		return $this->hasMany('Img');
	}

	public function goodsproperty(){
		//商品的细节图的一对多关系
		return $this->hasMany('Goodsproperty');
	}

	public function ordergoods()
	/*商品细节和订单商品的一对多关系*/
	{
		return $this->hasMany('app\user\model\Ordergoods');
	}

	public function commentgoods()
	/*商品和商品评论的一对多关系*/
	{
		return $this->hasMany('app\user\model\Commentgoods');
	}

	
}
?>