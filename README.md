# CsvCombine

[![Build Status](https://travis-ci.org/satthi/csv-combine.svg?branch=master)](https://travis-ci.org/satthi/csv-combine)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/satthi/csv-combine/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/satthi/csv-combine/?branch=master)

PHP versions  5/7

## 更新履歴 ##

* 2017/12/15 CakePHPバージョンからの置き換え

## 特徴 ##

* 配列 ⇔ CSV・TSVファイルを行う機能
* 固定長も対応対応

## 準備 ##

```
"satthi/csv-combine": "*"
```

********************

## 使い方(CSV出力) ##
```php
<?php
use CsvCombine\Export\CsvExport;

$csvExport = new CsvExport();
$list = array(
    array('a','b','cc'),
    array('あa','b','cc'),
);
$file = 'export.csv';
$options = array(
    'export_encoding' => 'UTf-8',
);
$csvExport->make($list, $file);
```

## 使い方(CSV入力) ##
```php
<?php
use CsvCombine\Import\CsvImport;
use CsvCombine\Import\FixedLengthImport;

$CsvImport = new CsvImport();

$filename = 'export.csv';
$list = array('A','B','C');
print_r($CsvImport->import($filename, $list));
```

## 使い方(固定長) ##
```php
<?php
use CsvCombine\Export\FixedLengthExport;

$FixedLengthExport = new FixedLengthExport();
$list = array(
    array('a','b','cc'),
    array('あa','b','cc'),
);
$file = 'export.txt';
$options = array(
    'export_encoding' => 'UTf-8',
);
$fixed_options = array(
    array('length' => 20, 'type' => 'text'),
    array('length' => 20, 'type' => 'text'),
    array('length' => 20, 'type' => 'text'),
);
$FixedLengthExport->make($list, $file, $fixed_options);

```

## 使い方(固定長) ##
```php
<?php
use CsvCombine\Import\FixedLengthImport;
$FixedLengthImport = new FixedLengthImport();

$filename = 'export.txt';
$list = array(
    array('name' => 'A', 'length' => 20),
    array('name' => 'B', 'length' => 20),
    array('name' => 'C', 'length' => 20),
);
print_r($FixedLengthImport->import($filename, $list));
```

## License ##

The MIT Lisence

Copyright (c) 2017 Fusic Co., Ltd. (http://fusic.co.jp)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

## Author ##

Satoru Hagiwara
