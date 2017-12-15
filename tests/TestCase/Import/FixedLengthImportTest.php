<?php
namespace CsvCombine\Test\TestCase\Import;

use CsvCombine\Import\FixedLengthImport;
use PHPUnit\Framework\TestCase;

/**
 * CsvCombine\Import\FixedLengthImport Test Case
 */
class FixedLengthImportTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->FixedLengthImport = new FixedLengthImport();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FixedLengthImport);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function test_loadCsv()
    {
        $test1FixedLengthPath = dirname(dirname(dirname(__FILE__))) . '/test_app/test1.txt';
        $columnList = [
            ['name' => 'column1', 'length' => 8],
            ['name' => 'column2', 'length' => 10],
            ['name' => 'column3', 'length' => 6],
        ];
        $extraList = [
            //ヘッダー
            1 => [
                ['name' => 'columna', 'length' => 4],
                ['name' => 'columnb', 'length' => 8],
                ['name' => 'columnc', 'length' => 12],
            ],
            //フッター
            -1 => [
                ['name' => 'columnx', 'length' => 2],
                ['name' => 'columny', 'length' => 12],
                ['name' => 'columnz', 'length' => 10],
            ]
        ];
        $options = ['extra_fixed_options' => $extraList];
        $fixedLengthData = $this->FixedLengthImport->import($test1FixedLengthPath, $columnList, $options);
        //テストファイル
        //1行目
        $result1 = [
            'columna' => 'あい',
            'columnb' => 'う  いい',
            'columnc' => 'い    uuu'
        ];
        $this->assertTrue(
            $fixedLengthData[0] === $result1
        );

        //2行目
        $result2 = [
            'column1' => 'いうえ',
            'column2' => 'ううう',
            'column3' => 'eee'
        ];
        $this->assertTrue(
            $fixedLengthData[1] === $result2
        );

        //3行目
        $result3 = [
            'columnx' => 'ab',
            'columny' => 'cde   fggf',
            'columnz' => '    おお'
        ];
        $this->assertTrue(
            $fixedLengthData[2] === $result3
        );
    }


}
