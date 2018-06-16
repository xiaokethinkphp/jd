<?php  
namespace app\user\model;
/**
* 城市模型
*/
class City extends \think\Model
{
	protected $resultSetType = 'collection';
	public function address(){
		//城市和地址的一对多关系
		return $this->belongsTo('Address');
	}
}
?>