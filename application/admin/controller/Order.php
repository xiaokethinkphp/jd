<?php
namespace app\admin\controller;
/**
* 订单控制器
*/
class Order extends Common
{
	
	public function orderlist()
	/*订单列表展示*/
	{
		$order_model = new \app\user\model\Order();
		$order_all = $order_model->all();
		foreach ($order_all as $key => $value) {
			$value->user;
			$value->address;
			$value['address']->province;
			$value['address']->city;
			$value['address']->district;
		}
		
		$order_all_toArray = $order_all->toArray();
		$this->assign('order_all_toArray',$order_all_toArray);
		// dump($order_all_toArray);
		return view();
	}

	public function orderinfo($order_id="")
	/*查看具体的订单信息*/
	{
		$order_model = new \app\user\model\Order();
		$order_get = $order_model->get($order_id);
		$order_get->ordergoods;
		foreach ($order_get['ordergoods'] as $key => $value) {
			$value->goods;
			$value['goods']->keywords;
		}
		$order_get->user;
		$order_get->address;
		$order_get['address']->province;
		$order_get['address']->city;
		$order_get['address']->district;
		$order_get->commentgoods;
		$order_get_toArray = $order_get->toArray();
		// dump($order_get_toArray);
		$this->assign('order_get_toArray',$order_get_toArray);
		return view();
	}

	public function changeStatus()
	/*改变订单状态的Ajax处理*/
	{
		if (request()->isAjax()) {
			$post = request()->post();
			$order_status = $post['order_status'];
			$order_id = $post['order_id'];
			$order_status_change = db('order')->update($post);
			if($order_status_change!==false){
				switch ($order_status) {
					case '-1':
						return "已失效";
						break;
					case '0':
						return "已完成";
						break;
					case '1':
						return "待付款";
						break;
					case '2':
						return "待发货";
						break;
					case '3':
						return "待收货";
						break;
				}
			}
			else{
				return "-2";
			}
		}
	}

	public function delorder($order_id='')
	/*订单删除的方法*/
	{
		$order_model = new \app\user\model\Order();
		$order_get = $order_model->get($order_id);
		if(empty($order_get)){
			$this->redirect('orderlist');
		}
		$order_get->commentgoods()->delete();
		$order_get->ordergoods()->delete();
		$order_get->delete();
		$this->redirect('orderlist');
	}
}
?>