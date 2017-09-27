<?php
namespace app\common\library\office;

/**
 * 导航状态类型
 */
class Excel
{

    /**
     * 加载excel
     * @param $file
     * @param null $format
     * @return array
     * @throws \PHPExcel_Exception
     */
    public static function load($file, $format = null)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($file);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数

        $excelArray = [];
        $field = [];

        //获取第一列标题
        for ($k = 'A'; $k <= 'Y'; $k++) {
            $excelValue = $objPHPExcel->getActiveSheet()->getCell("{$k}1")->getValue();
            if(!empty($excelValue)) {
                $field[$k] = $excelValue;
            }

        }

        //excel 转化成 数组
        for($j=2; $j<=$highestRow; $j++) {
            $array = [];
            foreach($field as $key => $value) {
                $array[$value] = (string) $objPHPExcel->getActiveSheet()->getCell("{$key}{$j}")->getValue();
            }
            if($array[$field['A']]) {
                array_push($excelArray, $array);
            }
        }

        //格式化
        if(is_array($format)) {
            $formatArray = [];
            foreach($excelArray as $key => $value) {
                $array = [];
                foreach($value as $k => $v){
                    if(isset($format[$k])) {
                        $array[$format[$k]] = $v;
                    }
                }
                array_push($formatArray, $array);
            }

            return $formatArray;
        }

        return $excelArray;
    }

    public static function export($data)
    {
        $objPHPExcel = new \PHPExcel();


        /*以下是一些设置 ，什么作者  标题啊之类的*/
        /*  $objPHPExcel->getProperties()->setCreator("转弯的阳光")
              ->setLastModifiedBy("转弯的阳光")
              ->setTitle("数据EXCEL导出")
              ->setSubject("数据EXCEL导出")
              ->setDescription("备份数据")
              ->setKeywords("excel")
              ->setCategory("result file");*/

        foreach($data as $k => $v){
            $num = $k+1;
            //Excel的第A列，uid是你查出数组的键值，下面以此类推
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, array_get($v, 0, ''))
                ->setCellValue('B'.$num, array_get($v, 1, ''))
                ->setCellValue('C'.$num, array_get($v, 2, ''))
                ->setCellValue('D'.$num, array_get($v, 3, ''))
                ->setCellValue('E'.$num, array_get($v, 4, ''))
                ->setCellValue('F'.$num, array_get($v, 5, ''))
                ->setCellValue('G'.$num, array_get($v, 6, ''))
                ->setCellValue('H'.$num, array_get($v, 7, ''))
                ->setCellValue('I'.$num, array_get($v, 8, ''))
                ->setCellValue('J'.$num, array_get($v, 9, ''))
                ->setCellValue('K'.$num, array_get($v, 10, ''))
                ->setCellValue('L'.$num, array_get($v, 11, ''))
                ->setCellValue('M'.$num, array_get($v, 12, ''))
                ->setCellValue('N'.$num, array_get($v, 13, ''))
                ->setCellValue('O'.$num, array_get($v, 14, ''))
                ->setCellValue('P'.$num, array_get($v, 15, ''))
                ->setCellValue('Q'.$num, array_get($v, 16, ''))
                ->setCellValue('R'.$num, array_get($v, 17, ''))
                ->setCellValue('S'.$num, array_get($v, 18, ''))
                ->setCellValue('T'.$num, array_get($v, 19, ''))
                ->setCellValue('U'.$num, array_get($v, 20, ''))
                ->setCellValue('V'.$num, array_get($v, 21, ''))
                ->setCellValue('W'.$num, array_get($v, 22, ''))
                ->setCellValue('S'.$num, array_get($v, 23, ''))
                ->setCellValue('Y'.$num, array_get($v, 24, ''))
                ->setCellValue('Z'.$num, array_get($v, 25, ''));
        }

        $objPHPExcel->getActiveSheet()->setTitle('数据');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: applicationnd.ms-excel');
        header('Content-Disposition: attachment;filename="asdas.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}