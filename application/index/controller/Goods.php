<?php  
namespace app\index\controller;
/**
* 前台商品控制器
*/
class Goods extends \think\Controller
{
	
	public function goodslist1($goods_pid=''){
		if ($goods_pid=='') {
			$this->redirect('index/index');
		}
		$goods_exist = db('goods')->where('goods_pid','eq',$goods_pid)->where('goods_status','eq','1')->select();
		$goods_select = db('goods')->where('goods_pid','eq',$goods_pid)->where('goods_status','eq','1')->paginate(4);
		if (empty($goods_exist)) {
			$this->redirect('index/index');
		}
		$this->assign('goods_select',$goods_select);

		return view();
	}

	public function goodslist($goods_pid='',$goods_order='id'){
		//带关键字的商品列表界面显示
		if ($goods_pid=='') {

			$this->redirect('index/index');
		}
		$goods_exist = db('goods')->where('goods_pid','eq',$goods_pid)->where('goods_status','eq','1')->select();
		if (empty($goods_exist)) {
			$this->redirect('index/index');
		}

		if ($goods_order=='goods_sales') {
			$goods_order = 'goods_sales desc';
		}
		else if ($goods_order=='goods_price_asc') {
			$goods_order = 'goods_price';
		}
		else if ($goods_order=='goods_price_desc') {
			$goods_order = 'goods_price desc';
		}
		else{
			$goods_order = 'goods_id';
		}

		$this->assign('goods_pid',$goods_pid);
		$goods_model = new \app\admin\model\Goods;
		$cate_model = new \app\admin\model\Cate;
	
		$goods_all = $goods_model->all(function($query) use ($goods_pid,$goods_order){
			$query->where('goods_pid','eq',$goods_pid)->where('goods_status','eq','1')->order($goods_order);
		});
		
		$goods_all_toArray = $goods_all->toArray();
			$goods_info = array();
			foreach ($goods_all_toArray as $key => $value) {
				$goods_get = $goods_model->get($value['goods_id']);
				$goods_keywords = $goods_get->keywords;
				$goods_keywords_toArray = $goods_keywords->toArray();
				$value['keywords'] = $goods_keywords_toArray;
				$goods_cate = $goods_get->cate;
				$goods_cate_toArray = $goods_cate->toArray();
				$value['cate_name'] = $goods_cate_toArray['cate_name'];
				$goods_info[] = $value;
			}
		$this->assign('goods_info',$goods_info);
		$goods_totle = count($goods_info);//得到数据总数
		$page_class = new \app\admin\controller\Page($goods_totle,4);
		$show = $page_class->fpage();//模板显示的内容
		$limit = $page_class->setlimit();//获取limit信息   '3,2'
		$limit = explode(',',$limit);//['3','2']
		$list = array_slice($goods_info,$limit[0],$limit[1]);//123456
		$this->assign('show',$show);
		$this->assign('goods_info',$list);
		return view();

	}

	public function introduction($goods_id=''){
		//商品详细信息界面
		//判断商品是否存在
		if ($goods_id=='') {
			$this->redirect('index/index');
		}
		$goods_find = db('goods')->find($goods_id);
		if (empty($goods_find)) {
			$this->redirect('index/index');
		}
		$goods_status = $goods_find['goods_status'];
		if ($goods_status==0) {
			$this->redirect('index/index');
		}

		$goods_model = new \app\admin\model\Goods;
		$goods_get = $goods_model->get($goods_id);
		$goods_get_toArray = $goods_get->toArray();
		/*获取商品关键字*/
		$goods_keywords = $goods_get->keywords;
		$goods_keywords_toArray = $goods_keywords->toArray();
		$goods_get_toArray['keywords'] = $goods_keywords_toArray;
		/*获取商品细节图*/
		$goods_img = $goods_get->img;
		$goods_img_toArray = $goods_img->toArray();
		$goods_get_toArray['img'] = $goods_img_toArray;
		/*获取商品属性*/
		$goods_goodsproperty = $goods_get->goodsproperty;
		$goods_goodsproperty_toArray1 = $goods_goodsproperty->toArray();
		$goods_goodsproperty_toArray = array();
		foreach ($goods_goodsproperty_toArray1 as $key => $value) {
			$property = db('property')->where('property_id','eq',$value['property_id'])->find();
			$value['property_name'] = $property['property_name'];
			$goods_goodsproperty_toArray[] = $value;
		}
		$goods_get_toArray['goodsproperty'] = $goods_goodsproperty_toArray;
		$this->assign('goods_introduction',$goods_get_toArray);
		//商品定位的实现
		$goods_pid = $goods_find['goods_pid'];
		$cate_select = db('cate')->select();
		$cate_model = new \app\admin\model\Cate;
		$cate_in = $cate_model->getFatherId($cate_select,$goods_pid);
		$this->assign('cate_in',$cate_in);
		//商品评论展示
		$goods_get->commentgoods;
		$comment_num=0;
		$total_score = 0;
		$hignscore=0;
		$middlescore=0;
		$lowscore=0;
		foreach ($goods_get['commentgoods'] as $key => $value) {
			$value->user;
			$value->order;
			$value_toArray = $value->toArray();
			switch ($value_toArray['comment_score']) {
				case '5':
					$hignscore++;
					break;
				case '3':
					$middlescore++;
					break;
				default:
					$lowscore++;
					break;
			}
			$comment_num++;
			$total_score+=$value_toArray['comment_score'];
		}
		$average = $total_score/$comment_num;
		// dump($goods_get->toArray());
		$goods_comments = $goods_get->toArray();
		$goods_comments['comment_num'] = $comment_num;
		$goods_comments['average'] = $average;
		$goods_comments['hign_score'] = $hignscore;
		$goods_comments['middle_score'] = $middlescore;
		$goods_comments['low_score'] = $lowscore;
		// dump($goods_comments);
		$this->assign('goods_comments',$goods_comments);
		$goods_totle = count($goods_comments['commentgoods']);
		$page_class = new \app\admin\controller\Page($goods_totle,2);
		$show = $page_class->fpage();//模板显示的内容
		$limit = $page_class->setlimit();//获取limit信息   '3,2'
		$limit = explode(',',$limit);//['3','2']
		$list = array_slice($goods_comments['commentgoods'],$limit[0],$limit[1]);//123456
		$this->assign('show',$show);
		return view();
	}

	public function search()
	/*用户搜索*/
	{
		$post = request()->post();
		if (empty($post)) {
			$this->redirect('index/index/index');
		}
		$keywords = $post['goods_keywords'];
		if ($keywords=='') {
			$this->redirect('index/index/index');
		}
		$keywords_find = db('keywords')->where('keywords_name','eq',$keywords)->find();
		if (empty($keywords_find)) {
			$this->redirect('index/index/index');
		}
		$keywords_id = $keywords_find['keywords_id'];
		$keywords_model = new \app\admin\model\Keywords;
		$keywords_get = $keywords_model->get($keywords_id);;
		$keywords_toArray = $keywords_get->toArray();
		$goods = $keywords_get->goods;
		$goods_toArray = $goods->toArray();
		$this->assign('keywords',$keywords);
		foreach ($goods as $key => $value) {
			$keywords = $value->keywords;
			$goods_search_all[] = $value->toArray();
		}
		$this->assign('goods_search_all',$goods_search_all);
		// dump($goods_search_all);die;
		return view();
	}



}

?>