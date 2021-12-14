<?php
namespace CsvCombine\Import;

use CsvCombine\Import\AppImport;
use Exception;

class CsvImport extends AppImport
{
    /*
     * loadDataCsv CSV読み込みアクション
     *
     * @param string $fileName 固定長テキストファイ
     * @param array $columnList 各カラム情報(name:カラム名,length:バイト数)
     * @param array $options
     */
    public function import($fileName, $columnList = [], $options = [])
    {
        $options = $this->getOptions($options);
        extract($options);

        //保存をするのでモデルを読み込み
        try {
            $data = array();
            $csvData = array();
            $file = fopen($fileName,"r");
            while($data = $this->fgetcsv_reg($file, 65536, $delimiter)) {//CSVファイルを","区切りで配列に
                mb_convert_variables($arrayEncoding, $importEncoding, $data);
                $csvData[] = $data;
            }

            $i = 0;
            $returnInfo =[];
            foreach ($csvData as $line) {
                $thisData = array();
                if (empty($columnList)) {
                    $thisColumnList = array();
                    $lineCount = 0;
                    foreach ($line as $line_v) {
                        $thisColumnList[] = $lineCount;
                        $lineCount++;
                    }
                } else {
                    $thisColumnList = $columnList;
                }
                foreach ($thisColumnList as $k => $v) {
                    if (isset($line[$k])) {
                        //先頭と末尾の"を削除
                        $b = $line[$k];
                        //カラムの数だけセット
                        $thisData = array_merge(
                                        $thisData,
                                        array($v => $b)
                        );
                    } else {
                        $thisData = array_merge(
                                        $thisData,
                                        array($v => '')
                        );
                    }
                }

                $returnInfo[$i] = $thisData;
                $i++;
            }
        } catch (Exception $e) {
            return false;
        }

        return $returnInfo;
    }

    /**
     * fgetcsv_reg
     *
     * this is a port of the original code written by yossy.
     *
     * @author yossy
     * @author hagiwara
     *
     * @param resource $handle
     * @param integer $length
     * @param string $d
     * @param string $e
     * @see http://yossy.iimp.jp/wp/?p=56
     * @return array
     */
    private function fgetcsv_reg (&$handle, $length = null, $d = ',', $e = '"')
    {
        $d = preg_quote($d);
        $e = preg_quote($e);
        $_line = "";
        $eof = false; // Added for PHP Warning.
        while ( $eof != true ) {
            $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
            $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
            if ($itemcnt % 2 == 0) $eof = true;
        }
        $_csv_line = preg_replace('/(?:\\r\\n|[\\r\\n])?$/', $d, trim($_line));
        $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';

        preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);

        $_csv_data = $_csv_matches[1];

        for ( $_csv_i=0; $_csv_i<count($_csv_data); $_csv_i++ ) {
            $_csv_data[$_csv_i] = preg_replace('/^'.$e.'(.*)'.$e.'$/s', '$1', $_csv_data[$_csv_i]);
            $_csv_data[$_csv_i] = str_replace($e.$e, $e, $_csv_data[$_csv_i]);
        }
        return empty($_line) ? false : $_csv_data;
    }

}
