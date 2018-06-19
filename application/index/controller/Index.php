<?php
namespace app\index\controller;
use PHPExcel;
use PHPExcel_IOFactory;
//商城首页
class Index extends \think\Controller
{
    public function index()
    {
    	$cate_select = db('cate')->order('cate_sort')->select();
    	$cate_model = model('cate');
    	$cate_list = $cate_model->getChildren($cate_select);
    	$this->assign('cate_list',$cate_list);
        return view();
    }

    public function index1()
    {
        $address = db('address')->select();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','ID')->setCellValue('B1','姓名')->setCellValue('C1','地址');
        // 设置工作薄名称
        $objPHPExcel->getActiveSheet()->setTitle(iconv('gbk', 'utf-8', 'phpexcel测试'));
        // 设置默认字体和大小
        $objPHPExcel->getDefaultStyle()->getFont()->setName(iconv('gbk', 'utf-8', '宋体'));
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);dump($address);
        foreach($address as $key => $v){
            $k=$key+2;//表格是从1开始的
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$k,$v['address_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$k,$v['user_address_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$k,$v['user_address_detail']);
            }
        $objPHPExcel->getActiveSheet()->setTitle('User');
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="xiaoke.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
        
        return '111';
    }
}
