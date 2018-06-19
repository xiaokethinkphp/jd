<?php  
namespace app\admin\controller;
/**
* 
*/
if(!isset($_SESSION['imgupload'])){
	session_start();
}
class Goods extends Common
{
	
	public function add(){
		//添加商品界面
		if (cookie('imgupload')) {
			$cookie_arr = unserialize(cookie('imgupload'));
			foreach ($cookie_arr as $key => $value) {
				$url_pre = DS.'jd'.DS.'public';
				$url = str_replace($url_pre,'.',$value);
				if (file_exists($url)) {
					unlink($url);
				}
			}
		}
		cookie('img',null);
		unset($_SESSION['imgupload']);
		if (session('goods_thumb')){
			$url_pre = DS.'jd'.DS.'public';
			$url = str_replace($url_pre,'.',session('goods_thumb'));
			if (file_exists($url)) {
				unlink($url);
			}
		}
		session('goods_thumb',null);
		$cate_select = db('cate')->select();
		$cate_model = model('Cate');
		$cate_list = $cate_model->getChildrenId($cate_select);
		//获取无限级分类列表
		$this->assign('cate_list',$cate_list);

		$cate_list1 = $cate_model->getChildren($cate_select);
		//获取无限级分类列表
		$this->assign('cate_list1',$cate_list1);


		return view();
	}

	public function uploadthumb(){
		if (session('goods_thumb')){
			$url_pre = DS.'jd'.DS.'public';
			$url = str_replace($url_pre,'.',session('goods_thumb'));
			if (file_exists($url)) {
				unlink($url);
			}
		}
		//利用插件上传图片的方法
	    $file = request()->file('goods_thumb');
	    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
	    if($info){
	        $address = DS.'jd'.DS.'public' . DS . 'uploads'.DS.$info->getSaveName();
	        session('goods_thumb',$address);
	        return $address;
	    }else{
	        echo $file->getError();
	    }
	}

	public function addhanddle(){
		//添加商品表单提交处理
		$post = request()->post();
		$post['goods_thumb'] = session('goods_thumb');
		$post['goods_status'] = isset($post['goods_status'])?$post['goods_status']:'0';
		$post['goods_pid']	=	isset($post['goods_pid'])?$post['goods_pid']:null;
		$post['goods_after_price']	= empty($post['goods_after_price'])?'0':$post['goods_after_price'];
		if ($post['goods_after_price']!=0) {
			if ($post['goods_after_price']>=$post['goods_price']) {
				session('goods_thumb',null);
				unset($_SESSION['imgupload']);
				$this->error('促销价格不能大于商品价格','goods/add');
			}
		}
		if (!isset($_SESSION['imgupload'])) {
			session('goods_thumb',null);
			$this->error('请不要同时打开多个添加商品窗口','goods/add');
		}
		$imgupload = $_SESSION['imgupload'];
		// dump($post);die;
		$validate = validate('Goods');
		if (!$validate->check($post)) {
			session('goods_thumb',null);
			unset($_SESSION['imgupload']);
			$this->error($validate->getError(),'goods/add');
		}
		$goods_add_result = db('goods')->insertGetId($post);
		if ($goods_add_result) {
			session('goods_thumb',null);
			$goods_model = new \app\admin\model\Goods;
			$goods = $goods_model->find($goods_add_result);
			foreach ($imgupload as $key => $value) {
				if($value!='0'){
					$goods->img()->save(['url'=>$value]);
				}
				
			}
			unset($_SESSION['imgupload']);
			$this->success('商品添加成功','goods/goodslist');
		}
		else{
			session('goods_thumb',null);
			unset($_SESSION['imgupload']);
			$this->error('商品添加失败','goods/goodslist');
		}
		
	}

	public function canclethumb(){
		//实时删除缩略图的方法
		if (request()->isAjax()) {
			if (session('goods_thumb')){
			$url_pre = DS.'jd'.DS.'public';
			$url = str_replace($url_pre,'.',session('goods_thumb'));
			if (file_exists($url)) {
				unlink($url);
			}
		}
		session('goods_thumb',null);
		}
	}

	public function goodslist1(){
		//商品列表显示的方法
		$goods_select = db('goods')->join('jd_cate','jd_goods.goods_pid = jd_cate.cate_id')->paginate(3);
		$this->assign('goods_select',$goods_select);
		return view();
	}

	public function goodslist($goods_pid=''){
		$goods_model = model('Goods');
		// $goods_all = $goods_model->all(function($query){
  //   			$query->where('status', 1);
		// });
		// $goods_all_toArray = $goods_all->toArray();
		// $goods_info = array();
		// foreach ($goods_all_toArray as $key => $value) {
		// 	$goods_get = $goods_model->get($value['goods_id']);
		// 	$goods_keywords = $goods_get->keywords;
		// 	$goods_keywords_toArray = $goods_keywords->toArray();
		// 	$value['keywords'] = $goods_keywords_toArray;
		// 	$goods_cate = $goods_get->cate;
		// 	$goods_cate_toArray = $goods_cate->toArray();
		// 	$value['cate_name'] = $goods_cate_toArray['cate_name'];
		// 	$goods_info[] = $value;
		// }
		// $this->assign('goods_info',$goods_info);


		
		$cate_model = model('Cate');
		$cate_select = db('cate')->select();
		$cate_list1 = $cate_model->getChildren($cate_select);
		//获取无限级分类列表
		$this->assign('cate_list1',$cate_list1);


		$cate_find = db('cate')->find($goods_pid);
		if ($cate_find) {
			$goods_all = $goods_model->all(function($query) use ($goods_pid){
    			$query->where('goods_pid','eq',$goods_pid);
			});
			$this->assign('cate_find',$cate_find);
		}
		else{
			$goods_all = $goods_model->all();
			$this->assign('cate_find','');
		}
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

	public function upd($goods_id=''){
		// dump($_SESSION['imgupload']);die;
		//商品修改界面显示
		if ($goods_id=='') {
			$this->redirect('goods/goodslist');
		}
		$goods_find = db('goods')->find($goods_id);
		if (empty($goods_find)) {
			$this->redirect('goods/goodslist');
		}
		if (session('goods_thumb')!=$goods_find['goods_thumb']){
			$url_pre = DS.'jd'.DS.'public';
			$url = str_replace($url_pre,'.',session('goods_thumb'));
			if (file_exists($url)) {
				unlink($url);
			}
		}
		unset($_SESSION['old']);
		//清除旧图片信息
		session('goods_thumb',null);
		$cate_select = db('cate')->select();
		$cate_model = model('Cate');
		$cate_list1 = $cate_model->getChildren($cate_select);
		// dump($cate_list1);die;
		//获取无限级分类列表

		$cate_in = $cate_model->getFatherId($cate_select,$goods_find['goods_pid']);
		$cate_in_new['one'] = $cate_in[0];
		$cate_in_new['two'] = $cate_in[1];
		$cate_in_new['three'] = $cate_in[2];
		$this->assign('cate_in',$cate_in_new);
		// dump($cate_in_new);die;
		$this->assign('cate_list1',$cate_list1);
		$this->assign('goods_find',$goods_find);

		//获取商品细节图信息
		$img_select = db('img')->where('goods_id','eq',$goods_id)->select();
		if (isset($_SESSION['imgupload'])) {
			foreach ($_SESSION['imgupload'] as $key => $value) {
				$url_pre = DS.'jd'.DS.'public';
				$url = str_replace($url_pre,'.',$value);
				if (file_exists($url)) {
					unlink($url);
				}
			}
		}
		unset($_SESSION['imgupload']);
		unset($_SESSION['old']);
		//清除旧图片信息
		foreach ($img_select as $key => $value) {
			$_SESSION['imgupload'][] = '1';
			$_SESSION['old'][] = $value['url'];
		}
		//获取旧的信息
		$this->assign('img_select',$img_select);
		return view();
	}

	public function updhanddle(){
		//修改商品提交界面
		$post = request()->post();
		$goods_info = db('goods')->find($post['goods_id']);
		$img_url = $goods_info['goods_thumb'];
		if (session('goods_thumb')!=null) {
			//图片进行过替换的情况
			$post['goods_thumb'] = session('goods_thumb');
			$url_pre = DS.'jd'.DS.'public';
			$url = str_replace($url_pre,'.',$img_url);
			if (file_exists($url)) {
				unlink($url);
			}

		}
		else{
			$post['goods_thumb'] = $img_url;
		}
		$post['goods_status'] = isset($post['goods_status'])?$post['goods_status']:'0';
		$post['goods_pid']	=	isset($post['goods_pid'])?$post['goods_pid']:null;
		$post['goods_after_price']	= empty($post['goods_after_price'])?'0':$post['goods_after_price'];
		if ($post['goods_after_price']!=0) {
			if ($post['goods_after_price']>=$post['goods_price']) {
				$this->error('促销价格不能大于商品价格');
			}
		}
		
		$validate = validate('Goods');
		if (!$validate->check($post)) {
			$this->error($validate->getError(),'goods/goodslist');
		}
		$imgupload = $_SESSION['imgupload'];
		$goods_add_result = db('goods')->update($post);;
		if ($goods_add_result!==false) {
			session('goods_thumb',null);
			$goods_model = new \app\admin\model\Goods;
			$goods = $goods_model->find($post['goods_id']);
			foreach ($imgupload as $key => $value) {
				if($value == '-1'){
					//旧图片删除
					db('img')->where('url','eq',$_SESSION['old'][$key])->delete();
					$url_pre = DS.'jd'.DS.'public';
					$url = str_replace($url_pre,'.',$_SESSION['old'][$key]);
					if (file_exists($url)) {
						unlink($url);
					}
				}
				else if($value!='1'&$value!='0'){
					//新增图片的情况
					$goods->img()->save(['url'=>$value]);
				}
			}
			unset($_SESSION['old']);
			unset($_SESSION['imgupload']);
			$this->success('商品修改成功','goods/goodslist');
		}
		else{
			unset($_SESSION['old']);
			unset($_SESSION['imgupload']);
			session('goods_thumb',null);
			$this->error('商品修改失败','goods/goodslist');
		}


	}

	public function del($goods_id=''){
		//商品信息删除的方法
		if ($goods_id=='') {
			$this->redirect('goods/goodslist');
		}
		$goods_find = db('goods')->find($goods_id);
		if (empty($goods_find)) {
			$this->redirect('goods/goodslist');
		}
		//删除商品的缩略图
			if ($goods_find['goods_thumb']){
				$url_pre = DS.'jd'.DS.'public';
				$url = str_replace($url_pre,'.',$goods_find['goods_thumb']);
				if (file_exists($url)) {
					unlink($url);
				}
			}
			//删除商品对应的关键字
			$goods_keywords_del_result = db('goods_keywords')->where('goods_id','eq',$goods_id)->delete();
			//删除商品对应的细节图
			//使用数据库的方式进行删除
			// db('img')->where('goods_id','eq',$goods_id)->delete();
			//使用模型进行删除：
			$goods_model = model('goods');
			$goods_get = $goods_model->get($goods_id);
			$goods_img = $goods_get->img()->select();
			$goods_img_toArray = $goods_img->toArray();
			foreach ($goods_img as $key => $value) {
				$url_pre = DS.'jd'.DS.'public';
				$url = str_replace($url_pre,'.',$value['url']);
				if (file_exists($url)) {
					unlink($url);
				}
			}
			$goods_get->img()->delete();
			$goods_goodsproperty = $goods_get->goodsproperty()->delete();
		//删除商品的信息('jd_goods')
		$goods_del_result = db('goods')->delete($goods_id);
		if ($goods_del_result) {
			$this->success('商品删除成功','goods/goodslist');
		}
		else{
			$this->error('商品删除失败','goods/goodslist');
		}
		
	}
	 public function keywordsaddhanddle1(){
    	$post = request()->post();
    	$goods_id = array_keys($post)[0];
    	$keywords_name = array_values($post)[0];
    	$keywords_find = db('keywords')->where('keywords_name','eq',$keywords_name)->find();
    	$keywords_id = $keywords_find['keywords_id'];
    	$goods_model = model('Goods');
    	$goods = $goods_model->get($goods_id);
		// 增加关联的中间表数据
		$goods->keywords()->attach($keywords_id);
    	
    }

	public function keywordsajax(){
        if (request()->isAjax()) {
            $post = request()->post();
            $post_val = $post['val'];
            $keywords_like = db('keywords')->where('keywords_name','like','%'.$post_val.'%')->limit(3)->select();
            return $keywords_like;
        }
    }

    public function keywordsaddhanddle(){
    	//添加关键字提交的方法
    	$post = request()->post();
    	$goods_id = array_keys($post)[0];
    	$keywords_name = array_values($post)[0];
    	if (empty($keywords_name)) {
    		$this->error('关键字不能为空','goods/goodslist');
    	}
    	$keywords_find = db('keywords')->where('keywords_name','eq',$keywords_name)->find();
    	if (empty($keywords_find)) {
    		$this->error('该关键字不存在，请先添加','keywords/add');
    	}
    	$keywords_id = $keywords_find['keywords_id'];
    	$goods_keywords_find = db('goods_keywords')->where('goods_id','eq',$goods_id)->where('keywords_id','eq',$keywords_id)->find();
    	if (!empty($goods_keywords_find)) {
    		$this->redirect('goods/goodslist');
    	}
    	$goods_model = model('goods');
    	$goods = $goods_model->get($goods_id);
		// 增加关联的中间表数据
		$goods->keywords()->attach($keywords_id);
		$this->redirect('goods/goodslist');
    }

    public function keywordsdelhanddle($goods_id='',$keywords_name=''){
    	if (empty($goods_id)|empty($keywords_name)) {
    		$this->redirect('goods/goodslist');
    	}
    	$goods_find = db('goods')->find($goods_id);
    	if (empty($goods_find)) {
    		$this->redirect('goods/goodslist');
    	}
    	$keywords_find = db('keywords')->where('keywords_name','eq',$keywords_name)->find();
    	if (empty($keywords_find)) {
    		$this->redirect('goods/goodslist');
    	}
    	$keywords_id = $keywords_find['keywords_id'];
    	$goods_keywords_find = db('goods_keywords')->where('goods_id','eq',$goods_id)->where('keywords_id','eq',$keywords_id)->find();
    	if (empty($goods_keywords_find)) {
    		$this->redirect('goods/goodslist');
    	}
    	$goods_model = model('goods');
    	$goods = $goods_model->get($goods_id);
		// 增加关联的中间表数据
		$goods->keywords()->detach($keywords_id);
		$this->redirect('goods/goodslist');


    }

    public function imgupload(){
    	//商品细节图上传的方法
    	$file = request()->file('goods_img');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads'.DS.'img');
        if($info){
            $address = DS.'jd'.DS.'public' . DS . 'uploads'.DS.'img'.DS.$info->getSaveName();
            $_SESSION['imgupload'][] = $address;
            $session_str = serialize($_SESSION['imgupload']);
            cookie('imgupload',$session_str,3600);
            return $address;
        }else{
            echo $file->getError();
        }
    }

    public function imgcancle(){
    	if (request()->isAjax()) {
            $post = request()->post();
            $img_index = $post['index'];
            $img_address = $_SESSION['imgupload'][$img_index];
            if($img_address==1){
            	$_SESSION['imgupload'][$img_index] = '-1';
            }
            else{
            	$_SESSION['imgupload'][$img_index]='0';
            }
            $url_pre = DS.'jd'.DS.'public';
            $url = str_replace($url_pre,'.',$img_address);
            if (file_exists($url)) {
                unlink($url);
            }
        }
    }

    public function addproperty($goods_id=""){
    	//添加商品属性的界面
    	$goods_find = db('goods')->find($goods_id);
    	if($goods_find==null){
    		$this->redirect('goods/goodslist');
    	}
    	$this->assign('goods_find',$goods_find);

    	$goods_pid = $goods_find['goods_pid'];
    	$property_select = db('property')->where('property_pid','eq',$goods_pid)->select();
    	$this->assign('property_select',$property_select);

    	$goods_model = model('goods');
    	$goods = $goods_model->get($goods_id);
    	$goodsproperty_select = $goods->goodsproperty()->select();
    	$goodsproperty_select_toArray = $goodsproperty_select->toArray();
    	$this->assign('goodsproperty_select_toArray',$goodsproperty_select_toArray);
    	return view();
    }

    public function addpropertyhanddle(){
    	//添加商品属性的提交方法
    	$post = request()->post();
    	$goods_id = $post['goods_id'];
    	//获取商品id
    	$goods_model = model('goods');
    	$goods = $goods_model->get($goods_id);
    	$goodsproperty_select = $goods->goodsproperty()->select();
    	$goodsproperty_select_toArray = $goodsproperty_select->toArray();
		$goodsproperty_propertyid = array_column($goodsproperty_select_toArray,'property_id');
		//获取到的指定商品id的属性id
		foreach ($post as $key => $value) {
			/**
			提交数据的四种情况
			1、原有属性已存在，新的属性不为空。进行更新。
			2、原有属性已存在，新的属性为空，进行删除。
			3、原有属性不存在，新的属性不为空，进行添加。
			4、原有属性不存在，新的属性为空，do nothing。
			*/
			if($key!='goods_id'){
				if(in_array($key,$goodsproperty_propertyid)){
					//提交数据项已存在，进行更新
					if ($value=="") {
						//数据为空的时候删除数据
						db('goodsproperty')->where(['property_id'=>$key,'goods_id'=>$goods_id])->delete();
					}
					else{
						//数据不为空的时候进行数据的更新
						db('goodsproperty')->where(['property_id'=>$key,'goods_id'=>$goods_id])->update(['goodsproperty_content'=>$value]);
					}
				}
				else{
					//提交一个新的数据项，进行添加
					if ($value!='') {
						$goods->goodsproperty()->save(['property_id'=>$key,'goodsproperty_content'=>$value]);
					}
				}
			}
		}
		$this->redirect('goods/goodslist');
    }
   

}
?>