<?php
namespace CsvCombine\Export;

use CsvCombine\Export\AppExport;
use Exception;

/**
 * FixedLengthExport  code license:
 *
 * @copyright Copyright (C) 2017 hagiwara.
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @author hagiwara
 */
class FixedLengthExport extends AppExport {

    /*
     * make 固定長の作成アクション
     *
     * @param array $list 出力のための配列(二次元配列が基本)
     * @param array $filePath 出力のための固定長の設定(各カラムのバイト数)
     * @param array $fixedOptions 出力のための固定長の設定(各カラムのバイト数)
     * @param array $options 下記パラメータを必要に応じて設定
     * line_feed_code 改行コード(デフォルトは\r\n)
     * directory 一時保存ディレクトリ(デフォルトはTMP,最終的に削除をする)
     * export_encoding 出力するファイルのエンコード(デフォルトはSJIS-win
     * array_encoding 入力する配列のエンコード(デフォルトはUTF-8
     * extra_fixed_options 出力のための固定長の設定(列によって桁数が異なる場合の設定)
     */
    public function make($list, $filePath, $fixedOptions, $options = [])
    {
        $fp = fopen($filePath, 'w');
        fwrite($fp, $this->makeText($list, $fixedOptions, $options));
        fclose($fp);

        return true;
    }

    /*
     * getRawData ファイルに出力した生テキストデータを取得
     * @param array $list 出力のための配列(二次元配列が基本)
     * @param array $fixedOptions 出力のための固定長の設定(各カラムのバイト数)
     * @param array $options 下記パラメータを必要に応じて設定
     * file_name 出力ファイル名(デフォルトはexport.txt)
     * line_feed_code 改行コード(デフォルトは\r\n)
     * directory 一時保存ディレクトリ(デフォルトはTMP,最終的に削除をする)
     * export_encoding 出力するファイルのエンコード(デフォルトはSJIS-win
     * array_encoding 入力する配列のエンコード(デフォルトはUTF-8
     * extra_fixed_options 出力のための固定長の設定(列によって桁数が異なる場合の設定)
     */
    public function getRawData($list, $fixedOptions, $options = [])
    {
        return $this->makeText($list, $fixedOptions, $options);
    }

    /*
     * makeText ファイルに出力した生テキストデータを取得
     * @param array $list 出力のための配列(二次元配列が基本)
     * @param array $fixedOptions 出力のための固定長の設定(各カラムのバイト数)
     * @param array $options 下記パラメータを必要に応じて設定
     * file_name 出力ファイル名(デフォルトはexport.txt)
     * line_feed_code 改行コード(デフォルトは\r\n)
     * directory 一時保存ディレクトリ(デフォルトはTMP,最終的に削除をする)
     * export_encoding 出力するファイルのエンコード(デフォルトはSJIS-win
     * array_encoding 入力する配列のエンコード(デフォルトはUTF-8
     * extra_fixed_options 出力のための固定長の設定(列によって桁数が異なる場合の設定)
     */
    private function makeText($list, $fixedOptions, $options)
    {
        $options = $this->getOptions($options);
        extract($options);

        mb_convert_variables($exportEncoding, $arrayEncoding, $list);

        // keyを振りなおしておく。
        $list = array_merge($list);
        $listCount = count($list);
        //$listにカンマか"がいた時の対応
        $returnText = '';
        foreach ($list as $row => $listVal) {
            $columnOptions = $fixedOptions;
            if (array_key_exists($row + 1, $extraFixedOptions)) {
                $columnOptions = $extraFixedOptions[$row + 1];
            } elseif (array_key_exists($row - $listCount, $extraFixedOptions)) {
                $columnOptions = $extraFixedOptions[$row - $listCount];
            }

            foreach ($columnOptions as $fixedOptionKey => $fixedInfo) {
                if (!array_key_exists($fixedOptionKey, $listVal)) {
                    //必要なデータが存在しないエラー
                    throw new Exception('data not exist');
                } else if (strlen($listVal[$fixedOptionKey]) > $fixedInfo['length']) {
                    throw new Exception('length error');
                }

                if ($fixedInfo['type'] == 'text') {
                    $returnText .= str_pad($listVal[$fixedOptionKey], $fixedInfo['length']);
                } elseif ($fixedInfo['type'] == 'integer') {
                    $returnText .= sprintf('%0' . $fixedInfo['length'] . 's', ($listVal[$fixedOptionKey]));
                } else {
                    throw new Exception('type error');
                }
            }
            $returnText .= $lineFeedCode;
        }
        return $returnText;
    }
}
