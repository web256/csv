<?php
/**
 * csv.class.php
 * ============================================================================
 * $Author: web256 $
 * $Date: 2015-9-14 上午10:32:19 $
 * $Id: csv.class.php 278286 2018-01-31 07:15:53Z wangdk $
 */

/**

 * @author web256
 * @example
 *       require MODULE_PATH.'/goods_count/helper/csv.class.php';
 *       $csv = new wcsv();
 *       $list = _model('goods_count_click_day')->getList(array(1=>1));
 *       $file_name    = '全国点击量';
 *       $select_field = array('click_num', 'date');
 *       $field        = array('点击量', '日期');
 *       $csv->downloadCsv($file_name, $field, $list, $select_field);
 *
 */
namespace csv;

class phpcsv
{

    /**
     * 文件指针
     * @var unknown_type
     */
    private $fp;


    /**
     * 输出Excel列名信息
     */
    private function setFields($field_list)
    {
        if ($field_list) {
            // 将数据通过fputcsv写到文件句柄
            fputcsv($this->fp,  $field_list);
        }
    }

    /**
     * 设置body体内容
     * @param unknown_type $body_list 数据内容
     * @param unknown_type $select_field_list 选中的内容字段
     * @param unknown_type $buffer_limit  刷新行数，buffer数据
     */
    private function setBody($body_list, $select_field_list, $buffer_limit)
    {
        $data = array();

        // 计数器
        $cnt = 0;

        // 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = $buffer_limit;

        // 逐行取出数据，不浪费内存
        $count = count($body_list);

        for($t=0; $t<$count; $t++) {

            $cnt ++;

            if ($limit == $cnt) { //刷新一下输出buffer，防止由于数据过多造成问题

                ob_flush();
                flush();
                $cnt = 0;
            }

            $row = $body_list[$t];
            foreach ($select_field_list as $k) {
                if (array_key_exists($k, $row)) {
//                     $data[$k] = @iconv('utf-8', 'gbk', $row[$k]);
                    $data[$k] = '"'.$row[$k].'"';
                }
            }
            // 占位符号，让最后一行超出隐藏
            $data['tab'] = " ";
            fputcsv($this->fp, $data);
        }
    }

    /**
     * 设置下载头部 header
     * @param unknown_type $csv_name
     */
    private function setHeader($csv_name)
    {
        // 输出Excel文件头，可把user.csv换成你要的文件名
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename='{$csv_name}.csv'");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header('Pragma:public');
        // 输出BOM头，支持UTF-8
        echo "\xEF\xBB\xBF";
    }

    /**
     * 导出Csv文件
     * @param unknown_type $csv_name  文件名
     * @param unknown_type $field_list  列头字段
     * @param unknown_type $body_list   数据内容
     * @param unknown_type $buffer_num  输出选中的内容字段
     * @param unknown_type $buffer_num  数据内容每间隔多少行刷新下缓冲区
     */
    public function downloadCsv($csv_name, $field_list,$body_list, $select_field_list = array(), $buffer_num = 100000)
    {
        // 打开PHP文件句柄，php://output 表示直接输出到浏览器
        $this->fp = fopen('php://output', 'a');

        $this->setHeader($csv_name);
        $this->setFields($field_list);
        $this->setBody($body_list, $select_field_list, $buffer_num);

        fclose($this->fp);
        exit;
    }

}
?>
