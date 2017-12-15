<?php
namespace CsvCombine\Test\TestCase\Export;

use CsvCombine\Export\CsvExport;
use PHPUnit\Framework\TestCase;

/**
 * CsvCombine\Test\TestCase\Export\CsvExport Test Case
 */
class CsvExportTest extends TestCase
{
    private $test1CsvPath;
    private $test2CsvPath;
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->CsvExport = new CsvExport();
        $this->test1CsvPath = dirname(dirname(dirname(__FILE__))) . '/test_app/test1.csv';
        $this->test2CsvPath = dirname(dirname(dirname(__FILE__))) . '/test_app/test2.csv';
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CsvExport);

        parent::tearDown();

        //不要なファイルを削除する
        unlink($this->test2CsvPath);
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function test_make()
    {
        //CSV1と同じ内容を作成
        $lists = [
            [
                '1',
                '2',
                '3',
            ],
            [
                'あ',
                'い',
                'う',
            ],
            [
                '"hoge',
                "\r\n",
                '',
            ],
        ];

        $this->CsvExport->make($lists, $this->test2CsvPath);
        $csv1Fp = fopen($this->test1CsvPath ,'r');
        $csv1 = fread($csv1Fp, filesize($this->test1CsvPath));
        fclose($csv1Fp);

        $csv2Fp = fopen($this->test2CsvPath ,'r');
        $csv2 = fread($csv2Fp, filesize($this->test2CsvPath));
        fclose($csv2Fp);

        //同じ内容で作成ができているかを確認
        $this->assertEquals($csv1, $csv2);


    }


}
