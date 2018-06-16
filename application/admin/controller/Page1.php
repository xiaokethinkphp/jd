<?php  
namespace app\admin\controller;
//分页
/**
* 
*/
class Page{
/**
* 分页
* @category 功能
* @param $totle：信息总数
* @param $displaypg：每页显示信息数，这里设置为默认是20；
* @param $url：分页导航中的链接，除了加入不同的查询信息“page”外的部分都与这个URL相同.默认值本该设为本页URL（即$_SERVER["REQUEST_URI"]），但设置默认值的右边只能为常量，所以该默认值设为空字符串，在函数内部再设置为本页URL。
* @return string
*/
function pageft($totle, $displaypg=20, $url=''){

// $page=fget("page", 1);
$page=100;
$url=empty($url) ? $_SERVER["REQUEST_URI"] : $url;

//URL分析：
$parse_url=parse_url($url);
$url_query=isset($parse_url["query"]) ? $parse_url["query"] : ""; //单独取出URL的查询字串
if($url_query){
// $url_query=preg_replace("/(&?)(page=$page)/","",$url_query);
$url_query=preg_replace("/page=[^&]*[&]?/i","",$url_query);
	//原来的
$url=str_replace($parse_url["query"],$url_query,$url);//将处理后的URL的查询字串替换原来的URL的查询字串
$url.="&page";//在URL后加page查询信息，但待赋值
}else{
$url.="?page";
}

//页码计算：
$lastpg=ceil($totle/$displaypg); //最后页，也是总页数
$lastpg=$lastpg ? $lastpg : 1; //没有显示条目，置最后页为1
$page=min($lastpg,$page);
$prepg=$page-1; //上一页
$nextpg=($page==$lastpg ? 0 : $page+1); //下一页
$firstcount=($page-1)*$displaypg;

//如果只有一页则跳出函数,没有分页的文字显示（备用）
//if($lastpg<=1) return false;

//开始分页导航条代码
$pagenav="显示第 ".($totle?($firstcount+1):0) . "/" . min($firstcount+$displaypg,$totle)." 条记录，共 $totle 条记录<br/>";

$pagenav.=" <a href='$url=1'>首页</a> ";
if($prepg) $pagenav.=" <a href='$url=$prepg'>前页</a> "; else $pagenav.=" 前页 ";
if($nextpg) $pagenav.=" <a href='$url=$nextpg'>后页</a> "; else $pagenav.=" 后页 ";
$pagenav.=" <a href='$url=$lastpg'>尾页</a> ";

//下拉跳转列表，循环列出所有页码
$pagenav.="　到第 <select name='topage' size='1' onchange='window.location=\"$url=\"+this.value'>\n";
for($i=1;$i<=$lastpg;$i++){
if($i==$page){
$pagenav.="<option value='$i' selected>$i</option>\n";
}else{
$pagenav.="<option value='$i'>$i</option>\n";
}
}
$pagenav.="</select> 页，共 $lastpg 页";

//组织返回值
$re_str['limit'] = "limit {$firstcount},{$displaypg}";
$re_str['str'] = $pagenav;
$re_str['firstcount'] = $firstcount;
$re_str['displaypg'] =$displaypg;
return $re_str;
}
}
?>