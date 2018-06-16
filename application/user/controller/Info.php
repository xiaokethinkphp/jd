<?php  
namespace app\user\controller;
/**
* 用户信息的控制器
*/
if(!isset($_SESSION['shopcart_info'])){
	session_start();
}
class Info extends Common
{
	
	public function index()
	/*个人中心首页*/
	{
		return view();
	}

	public function information()
	/*个人信息*/
	{
		$user_information_find = db('user')->field('user_id,user_nickname,user_name,user_sex,user_birth')->find(session('user_id'));
		$this->assign('user_information_find',$user_information_find);
		return view();
	}

	public function updateuserinfo()
	/*用户修改个人信息的提交方法*/
	{
		$post = request()->post();
		$post['user_id'] = session('user_id');
		$user_add_result = db('user')->update($post);
		if ($user_add_result!==false) {
			$this->success('用户信息修改成功','user/info/index');
		}
		else{
			$this->error('用户信息修改失败','user/info/index');
		}
	}

	public function safety()
	/*用户安全设置界面*/
	{
		$user_information_find = db('user')->find(session('user_id'));
		$this->assign('user_information_find',$user_information_find);
		return view();
	}

	public function password()
	/*用户修改密码的方法*/
	{
		return view();
	}

	public function updatepasswordhanddle()
	{
		$post = request()->post();
		$user_old_password = $post['user_old_password'];
		$user_information_find = db('user')->find(session('user_id'));
		$user_password = $user_information_find['user_password'];
		if ($user_password==md5($user_old_password)) {
			$user_new_password = $post['user_new_password'];
			$user_update_password_result = db('user')->update(['user_password'=>md5($user_new_password),'user_id'=>session('user_id')]);
		}
		else{
			$this->error('原密码错误，请重新操作','user/info/password');
		}
	}
	public function setpay()
	{
		$user_information_find = db('user')->find(session('user_id'));
		if ($user_information_find['user_phone']=='') {
			$this->error('该用户还未绑定手机号','user/info/bindphone2');
		}
		else{
			$this->assign('user_information_find',$user_information_find);
			return view();
		}
		
	}

	public function sendMessage()
	/*发送短信验证码的方法*/
	{
		if (request()->isAjax()) {
			$post = request()->post();
			$num = $post['num'];
			SendMessage($num);
		}
	}

	public function checkCode()
	/*验证验证码的方法*/
	{
		if (request()->isAjax()) {
			$post = request()->post();
			$num = $post['name'];
			$code = $post['param'];
			if (cookie((string)$num)==$code) {
				return ['status'=>'y','info'=>'验证码正确'];
			}
			else{
				return ['status'=>'n','info'=>'验证码错误'];
			}
		}
	}

	public function checkPassword()
	/*验证支付密码和登录密码是否一致*/
	{
		if (request()->isAjax()) {
			$post = request()->post();
			$password = md5($post['param']);
			$user_information_find = db('user')->find(session('user-id'));
			if ($password==$user_information_find['user_password']) {
				return ['status'=>'n','info'=>'支付密码不能和登录密码一致'];
			}
			else{
				return ['status'=>'y','info'=>'密码符合要求'];
			}
		}
	}

	public function setPayHanddle()
	/*处理设置修改支付密码的方法*/
	{
		$post = request()->post();
		// $num = array_keys($post)[0];
		$data = array();
		$data['user_pay_password'] = md5($post['user_pay_password']);
		$data['user_pay_password_status'] = '1';
		$data['user_id'] = session('user_id');
		$user_pay_password_update_result = db('user')->update($data);
		if ($user_pay_password_update_result!==false) {
			$this->success('用户支付密码设置成功！','user/info/index');
		}
		else{
			$this->error('用户密码设置失败','user/info/index');
		}
	}

	public function bindphone2()
	/*用户没有绑定手机时新绑定手机的方法*/
	{
		$user_phone_find = db('user')->find(session('user_id'));
		if ($user_phone_find['user_phone']!='') {
			$this->redirect('user/info/index');
		}
		return view();
	}

	public function bindphone2handdle()
	{
		$post = request()->post();
		$user_phone_find = db('user')->where('user_phone','eq',$post['user_phone'])->find();
		if ($user_phone_find) {
			$this->error('该手机已经被注册，请更换绑定的手机号码');
		}
		if ($post['user_code']==cookie((string)$post['user_phone'])) {
			$data = array();
			$data['user_phone'] = $post['user_phone'];
			$data['user_id'] = session('user_id');
			db('user')->update($data);
			$this->success('验证码正确，手机绑定成功');
		}
		else{
			$this->error('验证码错误');
		}
	}
	public function bindphone1(){
		$user_information_find = db('user')->find(session('user_id'));
		$this->assign('user_information_find',$user_information_find);
		return view();
	}

	public function setcookie()
	{
		cookie('15040360478','123456');
	}

	public function checkCode1()
	/*手机换绑时验证原来手机号的方法*/
	{
		if (request()->isAjax()) {
			$post = request()->post();
			$phone = $post['phone'];
			$code = $post['code'];
			if ($code == cookie((string)$phone)) {
				return ['status'=>'yes','info'=>'验证码正确'];
			}
			else{
				return ['status'=>'no','info'=>'验证码错误'];
			}
		}
	}

	public function ii()
	{
		return view('1');
	}

	public function address()
	/*用户收货地址管理*/
	{
		$user_address_select = db('address')->alias('a')->where('user_id','eq',session('user_id'))->
		join('jd_province p','a.user_address_province = p.province_id')->
		join('jd_city c','a.user_address_city = c.city_id')->
		join('jd_district d','a.user_address_district = d.district_id')->order('address_default desc')->select();
		$this->assign('user_address_select',$user_address_select);
		// dump($user_address_select);die;
		return view();
	}

	public function addadresshanddle()
	/*用户添加收货地址的处理方法*/
	{
		$post = request()->post();
		$user_model = model('user');
		$user_find = $user_model->find(session('user_id'));
		// $user_find_toArray = $user_find->toArray();
		$user_find->address()->save($post);
		$this->redirect('user/info/address');
	}

	public function changeDefaultAddress()
	/*用户设置默认地址的方法*/
	{
		if (request()->isAjax()) {
			$post = request()->post();
			$address_id = $post['address_id'];
			//将该用户所有地址的address_default置为0
			db('address')->where('address_default','eq','1')->where('user_id','eq',session('user_id'))->update(['address_default'=>'0']);
			//将传递过来的address_id的address_default置为1
			db('address')->where('address_id','eq',$address_id)->update(['address_default'=>'1']);
		}
	}

	public function editAddress($address_id='')
	/*用户修改地址的显示界面*/
	{
		$address_find = db('address')->find($address_id);
		if ($address_find==null) {
			$this->redirect('user/info/index');
		}
		$this->assign('address_find',$address_find);
		return view();
	}

	public function editadresshanddle($address_id='')
	/*修改地址的处理方法*/
	{
		$address_find = db('address')->find($address_id);
		if ($address_find==null) {
			$this->redirect('user/info/index');
		}
		$post = request()->post();
		$post['address_id'] = $address_id;
		$address_edit_result = db('address')->update($post);
		if ($address_edit_result!==false) {
			$this->success('地址修改成功','user/info/address');
		}
		else{
			$this->error('地址修改失败','user/info/address');
		}
	}

			
	public function delAddress()
	/*ajax删除用户地址的方法*/
	{
		if (request()->isAjax()) {
			$post = request()->post();
			$address_id = $post['address_id'];
			$del_address_result = db('address')->delete($address_id);
			if ($del_address_result) {
				return ['status'=>'1','info'=>'用户地址删除成功'];
			}
			else{
				return ['status'=>'0'];
			}
		}
	}

	public function shopcart()
	/*用户购物车界面*/
	{
		$user_model = model('user');
		$user_get = $user_model->get(session('user_id'));
		$goods = $user_get->shopcart()->field('goods_id,goods_num')->select();
		$goods_toArray = $goods->toArray();
		$goods_model = new \app\admin\model\Goods;
		$goods_list = array();
		foreach ($goods_toArray as $key => $value) {
			$goods_get = $goods_model->get($value['goods_id']);
			$goods_get->keywords;
			$goods_get_toArray = $goods_get->toArray();
			$goods_get_toArray['goods_num'] = $value['goods_num'];
			$goods_list[] = $goods_get_toArray;
		}
		$this->assign('goods_list',$goods_list);
		// dump($goods_list);
		return view();
	}

	public function addshopcart()
	/*商品加入购物车的Ajax处理*/
	{
		if (request()->isAjax()) {
			// if (session('user_id')==null) {
			// 	return ['user_status'=>'n'];
			// }
			$post = request()->post();
			$goods_id = $post['goods_id'];
			$goods_shopcart_result = db('shopcart')->where('goods_id','eq',$goods_id)->where('user_id','eq',session('user_id'))->find();
			if (!empty($goods_shopcart_result)) {
				db('shopcart')->where('goods_id','eq',$goods_id)->where('user_id','eq',session('user_id'))->setInc('goods_num');
			}
			else{
				$user_model = model('user');
				$user_find = $user_model->get(session('user_id'));
				$user_find->shopcart()->save(['goods_id'=>$goods_id]);
			}			
			
		}
	}

	public function desGoodsNum()
	/*购物车减一的Ajax请求*/
	{
		if (request()->isAjax()) {
			$post = request()->post();
			$goods_id = $post['goods_id'];
			$num = $post['num'];
			$user_id = session('user_id');
			$shopcart_des_result = db('shopcart')->where('goods_id','eq',$goods_id)->where('user_id','eq',$user_id)->setDec('goods_num');

			//$goods_find = db('goods')->find($goods_id);
			//$goods_after_price = $goods_find['goods_after_price'];
			
			//$goods_shopcart_find = 	db('shopcart')->where('goods_id','eq',$goods_id)->where('user_id','eq',$user_id)->find();
			//$goods_num = $goods_shopcart_find['goods_num'];
			//$goods_total_price = $goods_after_price*$goods_num;
			//return $goods_total_price;
		}

	}

	public function incGoodsNum()
	/*购物车加一的Ajax请求*/
	{
		if (request()->isAjax()) {
			$post = request()->post();
			$goods_id = $post['goods_id'];
			$num = $post['num'];
			$user_id = session('user_id');
			$shopcart_des_result = db('shopcart')->where('goods_id','eq',$goods_id)->where('user_id','eq',$user_id)->setInc('goods_num');

			//$goods_find = db('goods')->find($goods_id);
			//$goods_after_price = $goods_find['goods_after_price'];
			
			//$goods_shopcart_find = 	db('shopcart')->where('goods_id','eq',$goods_id)->where('user_id','eq',$user_id)->find();
			//$goods_num = $goods_shopcart_find['goods_num'];
			//$goods_total_price = $goods_after_price*$goods_num;
			//return $goods_total_price;
		}
	}

	public function pay()
	/*结算界面显示*/
	{
		if (empty($_SESSION['shopcart_info'])) {
			$this->redirect('user/info/shopcart');
		}
		$post = request()->post();
		$user_address_select = db('address')->alias('a')->where('user_id','eq',session('user_id'))->
		join('jd_province p','a.user_address_province = p.province_id')->
		join('jd_city c','a.user_address_city = c.city_id')->
		join('jd_district d','a.user_address_district = d.district_id')->order('address_default desc')->select();
		$this->assign('user_address_select',$user_address_select);


		$goods_model = new \app\admin\model\Goods;
		$shopcart = $_SESSION['shopcart_info'];
		$shopcart_info = array();
		$shopcart_total_price = 0;
		foreach ($shopcart as $key => $value) {
			$goods_get = $goods_model->get($value['goods_id']);
			$goods_get->keywords;
			$goods_get_toArray = $goods_get->toArray();
			$shopcart_total_price+=$value['goods_num']*$goods_get_toArray['goods_after_price'];

			$goods_get_toArray['goods_num'] = $value['goods_num'];
			$goods_get_toArray['total_price'] = $shopcart_total_price;
			$shopcart_info[] = $goods_get_toArray;
		}
		$this->assign('shopcart_info',$shopcart_info);
		unset($_SESSION['order_info']);
		$_SESSION['order_info'] = $shopcart_info;
		unset($_SESSION['shopcart_info']);
		// dump($user_address_select);die;
		return view();
	}

	public function json()
	{
		$post = request()->post();
		$json = json_decode($post['data'],true);
		$_SESSION['shopcart_info'] = $json;
	}
	public function shopcart1(){
		dump($_SESSION['shopcart_info']);
		// die;
		unset($_SESSION['shopcart_info']);
	}

	public function setAddress()
	/*实时切换收货地址的ajax*/
	{
		if (request()->isAjax()) {
			$post = request()->post();
			$address_id = $post['address_id'];
			$address_find = db('address')->alias('a')->
		join('jd_province p','a.user_address_province = p.province_id')->
		join('jd_city c','a.user_address_city = c.city_id')->
		join('jd_district d','a.user_address_district = d.district_id')->order('address_default desc')->find($address_id);
		$_SESSION['order_info']['order_address'] = $address_id;
			return $address_find;
		}
	}

	public function success1()
	/*订单生成处理*/
	{
		if (empty($_SESSION['order_info']['order_address'])) {
			$this->error('请选择地址');
		}
		$order_info = $_SESSION['order_info'];
		$order_address = $order_info['order_address'];
		$user_model = model('user');
		$user_id = session('user_id');
		$user_get = $user_model->get($user_id);
		$data = [
			'order_time' => time(),
			'order_status'	=>	'1',
			'order_address' =>	$order_address,
		];
		$order_insert_result = $user_get->order()->save($data);
		//返回最后添加的主键值即order_id
		$order_id = db('order')->getLastInsID();
		
		$order_model = model("order");
		$order_get = $order_model->get($order_id);
		unset($order_info['order_address']);
		foreach ($order_info as $key => $value) {
			$data = [
				'goods_id'	=>	$value['goods_id'],
				'goods_num'	=>	$value['goods_num']
			];
			$order_get->ordergoods()->save($data);
		}

		$this->redirect('user/info/order');
	}

	public function order()
	/*用户订单管理*/
	{
		//实例化一个用户模型
		$user_model = model('user');
		//找到当前用户的模型对象
		$user_get = $user_model->get(session('user_id'));
		//查找出用户对应的订单对象
		$order_select = $user_get->order()->select();
		//将订单最想转换为数组
		$order_select_toArray = $order_select->toArray();
		//实例化一个订单对象
		$order_model = model("order");
		//实例化一个商品对象
		$goods_model = new \app\admin\model\Goods();
		//对订单数组进行循环遍历
		foreach ($order_select_toArray as $key => $value) {
			//得到订单的id值
			$order_id = $value['order_id'];
			//查找出该id值对应的订单对象
			$order_get = $order_model->get($order_id);
			//查找出该订单对象对应的订单商品对象
			$order_get->ordergoods;
			//将订单商品对象转换为数组
			$order_goods_toArray = $order_get->toArray();
			//定义订单的总价格
			$order_goods_toArray['total_price'] = 0;
			//遍历得到的订单商品数组
			foreach ($order_goods_toArray['ordergoods'] as $k => $v) {
				//得到商品的id值
				$goods_id = $v['goods_id'];
				//得到该商品的模型对象
				$goods_get = $goods_model->get($goods_id);
				//得到该商品的关键字
				$goods_get->keywords;
				//将商品对象转换为数组
				$goods_get_toArray = $goods_get->toArray();
				//将商品对象转换为数组，并存入到$order_goods_toArray[$k]['goods_info']中
				$order_goods_toArray['ordergoods'][$k]['goods_info'] = $goods_get_toArray;
				//计算商品的总价格
				$order_goods_toArray['total_price']+=$v['goods_num']*$goods_get_toArray['goods_after_price'];
			}
			$arr[] = $order_goods_toArray;
		}
		// dump($arr);
		$this->assign('arr',$arr);
		return view();
	}

	public function orderinfo($order_id='')
	/*订单详情*/
	{
		if (empty($order_id)) {
			$this->redirect('user/info/index');
		}
		$order_find = db('order')->find($order_id);
		if (empty($order_find)) {
			$this->redirect('user/info/index');
		}
		/*$order_model = model('order');
		$order_get = $order_model->get($order_id);
		$order_goods = $order_get->ordergoods()->select();
		$order_goods_toArray = $order_goods->toArray();*/
		//以上四行操作得到的结果和使用db("ordergoods")->where('order_id','eq',$order_id)->select()的结果是一样的。
		//实例化一个订单模型
		$order_model = model('order');
		//获取对应order_id值得订单对象
		$order_get = $order_model->get($order_id);
		//将地址信息写入到订单对象当中
		$order_get->address;
		//将省份信息写入到订单对象的address属性中
		$order_get['address']->province;
		//将城市信息写入到订单对象的address属性中
		$order_get['address']->city;
		//将区县信息写入到订单对象的address属性中
		$order_get['address']->district;
		//将订单商品信息写入到订单对象当中
		$order_get->ordergoods;
		//定义一个订单总价格
		$total_price = 0;
		//订单和订单商品信息之间是一对多关系，需要循环
		foreach ($order_get['ordergoods'] as $key => $value) {
			//将商品具体信息写入到订单商品对象中去
			$value->goods;
			//将关键字信息写入到商品对象中去；
			$value['goods']->keywords;
			//将value转换为数组，得到商品数量和价格等信息
			$value_toArray = $value->toArray();
			//计算总价格
			$total_price += $value_toArray['goods_num']*$value_toArray['goods']['goods_after_price'];
		}
		//将最终得到的订单信息转换为数组
		$order_get_toArray = $order_get->toArray();
		//将价格信息写入到最终得到的订单信息中去
		$order_get_toArray['total_price'] = $total_price;
		// dump($order_get_toArray);
		$this->assign('order_get_toArray',$order_get_toArray);
		return view();
	}

	public function delorder($order_id='')
	/*订单删除的方法*/
	{
		$order_model = model('order');
		$order_get = $order_model->get($order_id);
		if(empty($order_get)){
			$this->redirect('user/info/order');
		}
		$order_get->commentgoods()->delete();
		$order_get->ordergoods()->delete();
		$order_get->delete();
		$this->redirect('user/info/order');
	}

	public function commentlist($order_id='')
	/*商品评论*/
	{
		$order_model = model('order');
		$order_get = $order_model->get($order_id);
		if(empty($order_get)){
			$this->redirect('user/info/order');
		}
		$order_get->ordergoods;
		foreach($order_get['ordergoods'] as $k=>$v){
			$v->goods;
			$v['goods']->keywords;
		}
		$order_get_toArray = $order_get->toArray();
		$this->assign('order_get_toArray',$order_get_toArray);
		return view();
	}

	public function commentAjax()
	/*订单进行评论提交的Ajax处理*/
	{
		if(request()->isAjax()){
			$post = request()->post();
			$order_id = $post['order_id'];
			$comment = json_decode($post['comment'],true);
			$_SESSION['comment'] = $comment;
			$order_model = model('order');
			$order_get = $order_model->get($order_id);
			$comment_time = time();

			foreach($comment as $key=>$value){
				$value['user_id'] = session('user_id');
				$order_get->commentgoods()->save($value);
			}
			$order_get->save(['comment_time'=>$comment_time]);
			$order_get->save(['order_status'=>'0']);
			return $comment;
		}
	}

	public function aaa()
	{
		dump($_SESSION['comment']);date();
	}

}
?>