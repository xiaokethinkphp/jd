<?php  
namespace app\user\model;
/**
* 省份模型
*/
class Province extends \think\Model
{
	protected $resultSetType = 'collection';
	public function address(){
		//省份和地址的一对多关系
		return $this->belongsTo('Address');
	}
}
?>