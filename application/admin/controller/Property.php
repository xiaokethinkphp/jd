<?php  
namespace app\admin\controller;
/**
* 商品属性管理
*/
class Property extends \think\Controller
{
	
	public function propertylist($property_pid=''){
		//属性列表的方法
		if ($property_pid=='') {
			$property_select = db('property')->select();
		}
		else{
			$property_select = db('property')->where('property_pid','eq',$property_pid)->select();
			if (empty($property_select)) {
				$this->redirect('property/propertylist');
			}
		}
		$cate_model = model('Cate');
		$cate_select = db('cate')->select();
		$property_select_fatherid = array();
		//带fatherid的属性数组
		foreach ($property_select as $key => $value) {
			$property_pid = $value['property_pid'];
			$father = $cate_model->getFather($cate_select,$property_pid);
			
			$value['father'][] = $father[0]['father'][0]['father'][0]['cate_name'];
			$value['father'][] = $father[0]['father'][0]['cate_name'];
			$value['father'][] = $father[0]['cate_name'];
			$property_select_fatherid[] = $value;
		}
		$cate_list1 = $cate_model->getChildren($cate_select);
		//获取无限级分类列表
		$this->assign('cate_list1',$cate_list1);
		$this->assign('property_select',$property_select_fatherid);
		return view();
		
	}

	public function add(){
		//添加属性的方法
		$cate_select = db('cate')->select();
		//获取全部分类
		$cate_model = model('Cate');
		
		$cate_list1 = $cate_model->getChildren($cate_select);
		//获取无限级分类列表，多维数组
		$this->assign('cate_list1',$cate_list1);

		return view();
	}
	public function addhanddle(){
		//添加属性的提交方法
		$post = request()->post();
		$post['property_pid'] = isset($post['property_pid'])?$post['property_pid']:'0';
		$validate = validate('property');
		if ($validate->check($post)) {
			$property_add_result = db('property')->insert($post);
			if ($property_add_result) {
				$this->success('属性添加成功','property/propertylist');
			}
			else{
				$this->error('属性添加失败','property/add');
			}
		}
		else{
			$this->error($validate->getError(),'property/add');
		}
	}

	public function upd($property_id=""){
		//属性修改界面的显示
		$property_find = db('property')->find($property_id);
		if ($property_find==null) {
			$this->redirect('property/propertylist');
		}
		$this->assign('property_find',$property_find);

		$cate_select = db('cate')->select();
		//获取全部分类
		$cate_model = model('Cate');
		
		$cate_list1 = $cate_model->getChildren($cate_select);
		//获取无限级分类列表，多维数组
		$this->assign('cate_list1',$cate_list1);

		$cate_in = $cate_model->getFatherId($cate_select,$property_find['property_pid']);
		$cate_in_new['one'] = $cate_in[0];
		$cate_in_new['two'] = $cate_in[1];
		$cate_in_new['three'] = $cate_in[2];
		$this->assign('cate_in',$cate_in_new);
		return view();

	}

	public function updhanddle(){
		//属性修改提交的方法
		$post = request()->post();
		$property_upd_result = db('property')->update($post);
		if ($property_upd_result!==false) {
			$this->success('商品修改成功','property/propertylist');
		}
		else{
			$this->error('商品修改失败','property/propertylist');
		}
	}

	
}
?>