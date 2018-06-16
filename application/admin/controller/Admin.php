<?php  
namespace app\admin\controller;
/**
* 管理员控制器
*/
class Admin extends Common
{
	
	public function adminlist(){
		//显示管理员列表
		$admin_select = db('admin')->select();
		$this->assign('admin_select',$admin_select);
		return view();
	}
	public function add(){
		//管理员添加的方法
		return view();
	}
	public function addhanddle(){
		//添加管理员提交的方法
		$post = request()->post();
		$validate = validate('admin');
		if ($validate->check($post)) {
			unset($post['admin_password1']);
			$post['admin_password'] = md5($post['admin_password']);
			$admin_add_result = db('admin')->insert($post);
			if ($admin_add_result) {
				$this->success('管理员添加成功');
			}
			else{
				$this->error('管理员添加失败');
			}
		}
		else{
			$this->error($validate->getError());
		}
	}
	public function checkadminname1(){ 
		//验证管理员名称是否可用
		if (request()->isAjax()) {
			$post = request()->post();
			$admin_name = $post['param'];
			$admin_name_find_result = db('admin')->where('admin_name','eq',$admin_name)->find();
			if (!$admin_name_find_result) {
				return array(
					'status' => 'y',
					'info'	=>	'管理员'.$admin_name.'可以使用',
				);
			}
			else{
				return array(
					'status' => 'n',
					'info'	=>	'管理员'.$admin_name.'已经存在',
				);
			}
		}
	}

	public function checkadminname(){
		//添加管理员时的Ajax验证管理员名称
		if (request()->isAjax()) {
			$post = request()->post();
			$admin_name['admin_name'] = $post['param'];
			$validate = validate('admin');
			if ($validate->scene('admin_name')->check($admin_name)) {
				return array('status'=>'y','info'=>'管理员名称可以使用');
			}
			else{
				return array('status'=>'n','info'=>$validate->getError());
			}
		}
	}

	public function checkupdadminname(){
		//修改管理员时的Ajax验证管理员名称
		if (request()->isAjax()) {
			$post = request()->post();
			$admin['admin_id'] = $post['name'];
			$admin['admin_name'] = $post['param'];
			$validate = validate('admin');
			if ($validate->scene('admin_name')->check($admin)) {
				return array('status'=>'y','info'=>'管理员名称可以使用');
			}
			else{
				return array('status'=>'n','info'=>$validate->getError());
			}
		}
	}


	public function upd($admin_id=''){
		//显示管理员修改界面的方法
		$admin_find = db('admin')->find($admin_id);
		if ($admin_find=='') {
			$this->redirect('admin/adminlist');
		}
		$this->assign('admin_find',$admin_find);
		// dump($admin_find);die;
		return view();
	}

	public function updhanddle(){
		//管理员修改提交的方法
		$post = request()->post();
		$admin_name = $post[$post['admin_id']];
		$post['admin_name'] = $admin_name;
		unset($post[$post['admin_id']]);
		$validate = validate('admin');
		if ($validate->check($post)) {
			unset($post['admin_password1']);
			$post['admin_password'] = md5($post['admin_password']);
			$admin_update_result = db('admin')->update($post);
			if ($admin_update_result!==false) {
				$this->success('管理员信息更新成功','admin/adminlist');
			}
			else{
				$this->error('管理员信息更新失败','admin/adminlist');
			}
		}
		else{
			$this->error($validate->getError());
		}
	}
	public function del($admin_id=''){
		//删除管理员的方法
		$admin_find = db('admin')->find($admin_id);
		if (empty($admin_find)) {
			$this->redirect('admin/adminlist');
		}
		$admin_del_result = db('admin')->delete($admin_id);
		if ($admin_del_result) {
			$this->success('管理员删除成功','admin/adminlist');
		}
		else{
			$this->error('管理员删除失败','admin/adminlist');
		}
	}
}
?>