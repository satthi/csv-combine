<?php
namespace CsvCombine\Import;

/**
 * AppImport
 * Import系のまとめ
 * @author hagiwara
 */
class AppImport {
    protected $_defaultOptions = [
        'line_feed_code' => "\r\n",
        'array_encoding' => 'UTF-8',
        'import_encoding' => 'SJIS-win',
        // csvのみのオプション
        'delimiter' => ',',
        // 固定長のみのオプション
        'extra_fixed_options' => []
    ];

    /**
     * getOptions
     * 使用オプションのまとめ
     * @author hagiwara
     */
    protected function getOptions($options)
    {
        $options = array_merge($this->_defaultOptions, $options);
        // キーをすべてキャメライズする
        foreach ($options as $optionKey => $optionVal) {
            unset($options[$optionKey]);
            $options[$this->camelize($optionKey)] = $optionVal;
        }
        return $options;
    }

    /**
     * camelize
     * https://qiita.com/okapon_pon/items/498b88c2f91d7c42e9e8
     */
    private function camelize($str)
    {
        return lcfirst(strtr(ucwords(strtr($str, ['_' => ' '])), [' ' => '']));
    }
}
