<?php  
namespace app\admin\controller;
/**
* 
*/
class Keywords extends Common
{
	
	public function keywordslist(){
		//关键字列表显示
		$keywords_select = db('keywords')->paginate(3);
		if ($keywords_select) {
			$this->assign('keywords_select',$keywords_select);
			return view();
		}
		else{
			$this->redirect('index/index');
		}
	}

	public function add(){
		//添加关键字显示界面
		return view();
	}

	public function addhanddle(){
		$post = request()->post();
		$validate = validate('keywords');
		if (!$validate->check($post)) {
			$this->error($validate->getError(),'keywords/add');
		}
		$keywords_add_result = db('keywords')->insert($post);
		if($keywords_add_result){
			$this->success('关键字添加成功','keywords/keywordslist');
		}
		else{
			$this->error('关键字添加失败','keywords/keywordslist');
		}
	}
	public function validform(){
		return view();
	}
	public function validform1(){
		if(request()->isAjax()){
			$post = request()->post();
			$keywords_find = db('keywords')->where('keywords_name','eq',$post['param'])->find();
			if ($keywords_find) {
				return array(
					'status'	=>	'n',
					'info'	=>	'关键字'.$post['param'].'已经存在'
				);
			}
			else{
				return array(
					'status'	=>	'true',
					'info'	=>	'关键字'.$post['param'].'可以使用'
				);
			}
		}
	}
}
?>