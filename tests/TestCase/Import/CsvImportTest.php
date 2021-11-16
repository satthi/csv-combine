<?php
namespace CsvCombine\Test\TestCase\Import;

use CsvCombine\Import\CsvImport;
use PHPUnit\Framework\TestCase;

/**
 * CsvCombine\Import\CsvImport Test Case
 */
class CsvImportTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->CsvImport = new CsvImport();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->CsvImport);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function test_loadCsv()
    {
        $test1_csv_path = dirname(dirname(dirname(__FILE__))) . '/test_app/test1.csv';
        $column = [
            'column1',
            'column2',
            'column3',
        ];
        $csvData = $this->CsvImport->import($test1_csv_path, $column);
        //テストファイル
        //1行目
        $result1 = [
            'column1' => '1',
            'column2' => '2',
            'column3' => '3'
        ];
        $this->assertTrue(
            $csvData[0] === $result1
        );

        //2行目
        $result2 = [
            'column1' => 'あ',
            'column2' => 'い',
            'column3' => 'う'
        ];
        $this->assertTrue(
            $csvData[1] === $result2
        );

        //3行目
        $result3 = [
            'column1' => '"hoge',
            'column2' => "\r\n",
            'column3' => ''
        ];
        $this->assertTrue(
            $csvData[2] === $result3
        );
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function test_loadCsvEmpty()
    {
        $test1_csv_path = dirname(dirname(dirname(__FILE__))) . '/test_app/test1.csv';
        $csvData = $this->CsvImport->import($test1_csv_path);
        //テストファイル
        //1行目
        $result1 = [
            '1',
            '2',
            '3'
        ];
        $this->assertTrue(
            $csvData[0] === $result1
        );

        //2行目
        $result2 = [
            'あ',
            'い',
            'う'
        ];
        $this->assertTrue(
            $csvData[1] === $result2
        );

        //3行目
        $result3 = [
            '"hoge',
            "\r\n",
            ''
        ];
        $this->assertTrue(
            $csvData[2] === $result3
        );
    }


}
