<?php  
namespace app\user\model;
/**
* 区县模型
*/
class District extends \think\Model
{
	protected $resultSetType = 'collection';
	public function address(){
		//区县和地址的一对多关系
		return $this->belongsTo('Address');
	}
}
?>