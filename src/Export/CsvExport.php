<?php
namespace CsvCombine\Export;

use CsvCombine\Export\AppExport;
use Exception;

/**
 * CsvExport
 *
 * @copyright Copyright (C) 2017 hagiwara.
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @author hagiwara
 */
class CsvExport extends AppExport {

    /*
     * make CSVの生成アクション
     *
     * @param array $list 出力のための配列(二次元配列が基本)
     * @param string $filePath 出力ファイルパス
     * @param array $options 下記パラメータを必要に応じて設定
     * delimiter 区切り文字の設定(デフォルトは",")
     * line_feed_code 改行コード(デフォルトは\r\n)
     * export_encoding 入力するファイルのエンコード(デフォルトはSJIS-win
     * array_encoding 出力する配列のエンコード(デフォルトはUTF-8
     */
    public function make($list, $filePath, $options = [])
    {
        $options = $this->getOptions($options);
        extract($options);
        $csvList = array();
        mb_convert_variables($exportEncoding, $arrayEncoding, $list);
        //$listにカンマか"がいた時の対応
        if (isset($list)) {
            if (is_array($list)) {
                foreach ($list as $k => $list1) {
                    if (is_array($list1)) {
                        foreach ($list1 as $m => $v) {
                            if (is_array($v)){
                                //3次元以上の配列の時はエラー
                                throw new Exception('array layer error');
                            }
                            $csvList[$k][$m] = $this->_parseCsv($v, $delimiter);
                        }
                    } else {
                        //1次元の時は1列目に値が入る。
                        $csvList[0][$k] = $this->_parseCsv($list1, $delimiter);
                    }
                }
            } else {
                //文字列の時は1カラムに値が入るだけ。
                $csvList[0][0] = $this->_parseCsv($list, $delimiter);
            }
        }

        $fp = fopen($filePath, 'w');
        foreach ($csvList as $fields) {
            fputs($fp, implode($delimiter, $fields) . $lineFeedCode);
        }
        fclose($fp);
        return true;
    }

    /*
     * _parseCsv
     * csv(など)の形式に変更
     *
     * @param string $v 変換する値
     * @param string $delimiter 区切り文字
     */
    private function _parseCsv($v, $delimiter)
    {
        //区切り文字・改行・ダブルクオートの時
        if (preg_match('/[' . $delimiter . '\\n"]/', $v)) {
            $v = str_replace('"', '""', $v);
            $v = '"' . $v . '"';
        }
        return $v;
    }

}
