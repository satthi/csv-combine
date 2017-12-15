<?php
namespace CsvCombine\Import;

use CsvCombine\Import\AppImport;
use Exception;

class FixedLengthImport extends AppImport
{

    /*
     * loadData 固定長読み込みアクション
     *
     * @param string $fileName 固定長テキストファイ
     * @param array $columnList 各カラム情報(name:カラム名,length:バイト数)
     * @param array $options 下記パラメータを必要に応じて設定
     * line_feed_code 改行コード(デフォルトは\r\n)
     * array_encoding 出力するする配列のエンコード(デフォルトはUTF-8
     * import_encoding 入力するテキストのエンコード(デフォルトはSJIS-win
     * extra_fixed_options 出力のための固定長の設定(列によって桁数が異なる場合の設定)
     */
    public function import($fileName, $columnList, $options = [])
    {
        $options = array_merge($this->_defaultOptions,$options);
        extract($options);

        $fp = fopen($fileName,'r');
        $data = fread($fp, filesize($fileName));
        fclose($fp);

        return $this->importBody($data, $columnList, $options);
    }

    /*
     * importBody 固定長内容読み込みアクション
     *
     * @param string $data 固定長テキストデータ
     * @param array $column_list 各カラム情報(name:カラム名,length:バイト数)
     * @param array $options 下記パラメータを必要に応じて設定
     * line_feed_code 改行コード(デフォルトは\r\n)
     * array_encoding 出力するする配列のエンコード(デフォルトはUTF-8
     * import_encoding 入力するテキストのエンコード(デフォルトはSJIS-win
     * extra_fixed_options 出力のための固定長の設定(列によって桁数が異なる場合の設定)
     */
    public function importBody($data, $baseColumnList, $options = [])
    {
        $options = $this->getOptions($options);
        extract($options);

        $returnInfo = [];
        //まずは分割
        $dataExplode = explode($lineFeedCode, $data);
        $listCount = count($dataExplode);
        foreach ($dataExplode as $row => $text) {
            //空行は無視
            if (strlen($text) === 0) {
                continue;
            }
            $startPoint = 0;
            $columnList = $baseColumnList;
            if (array_key_exists($row + 1, $extraFixedOptions)) {
                $columnList = $extraFixedOptions[$row + 1];
            } elseif (array_key_exists($row - $listCount + 1, $extraFixedOptions)) {
                $columnList = $extraFixedOptions[$row - $listCount + 1];
            }

            foreach ($columnList as $columnInfo) {
                $returnInfo[$row][$columnInfo['name']] = rtrim(substr($text, $startPoint, $columnInfo['length']));
                $startPoint += $columnInfo['length'];
            }
        }

        //最後にまとめて文字コードを変換
        foreach ($returnInfo as $row => $returnInfoRow) {
            foreach ($returnInfoRow as $columnName => $returnInfoVal) {
                $returnInfo[$row][$columnName] = mb_convert_encoding($returnInfoVal, $arrayEncoding, $importEncoding);
            }
        }

        return $returnInfo;
    }
}
