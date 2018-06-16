<?php  
namespace app\admin\model;
/**
* 
*/
class Keywords extends \think\Model
{
	protected $resultSetType = 'collection';
	public function goods(){
		return $this->belongsToMany('Goods','goods_keywords');
	}
}
?>