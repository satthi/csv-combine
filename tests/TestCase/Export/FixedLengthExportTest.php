<?php
namespace CsvCombine\Test\TestCase\Export;

use CsvCombine\Export\FixedLengthExport;
use PHPUnit\Framework\TestCase;

/**
 * CsvCombine\Test\TestCase\Export\FixedLengthExport Test Case
 */
class FixedLengthExportTest extends TestCase
{
    private $test1FixedLengthPath;
    private $test2FixedLengthPath;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->FixedLengthExport = new FixedLengthExport();

        $this->test1FixedLengthPath = dirname(dirname(dirname(__FILE__))) . '/test_app/test1.txt';
        $this->test2FixedLengthPath = dirname(dirname(dirname(__FILE__))) . '/test_app/test2.txt';
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FixedLengthExport);

        parent::tearDown();

        //不要なファイルを削除する
        unlink($this->test2FixedLengthPath);
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function test_make()
    {
        // $test2_fixed_length_path_pathinfo = pathinfo($this->test2FixedLengthPath);
        //CSV1と同じ内容を作成
        $list = [
            [
                'あいう',
                'いいい',
                'uu',
                'u',
            ],
            [
                'いうえ',
                'ううう',
                'eee',
            ],
            [
                'ab',
                'cde',
                'fggf',
                'おお',
            ],
        ];
        $fixedOptions = [
            ['length' => 8, 'type' => 'text'],
            ['length' => 10, 'type' => 'text'],
            ['length' => 6, 'type' => 'text'],
        ];
        $headerOptions = [
            ['length' => 8, 'type' => 'text'],
            ['length' => 10, 'type' => 'text'],
            ['length' => 2, 'type' => 'text'],
            ['length' => 4, 'type' => 'text'],
        ];
        $footerOptions = [
            ['length' => 2, 'type' => 'text'],
            ['length' => 6, 'type' => 'text'],
            ['length' => 10, 'type' => 'text'],
            ['length' => 6, 'type' => 'text'],
        ];
        $options = [
            'extra_fixed_options' => [
                1 => $headerOptions,
                -1 => $footerOptions,
            ]
        ];
        $this->FixedLengthExport->make($list, $this->test2FixedLengthPath, $fixedOptions, $options);

        $fixedLength1Fp = fopen($this->test1FixedLengthPath ,'r');
        $fixedLength1 = fread($fixedLength1Fp, filesize($this->test1FixedLengthPath));
        fclose($fixedLength1Fp);

        $fixedLength2Fp = fopen($this->test2FixedLengthPath ,'r');
        $fixedLength2 = fread($fixedLength2Fp, filesize($this->test2FixedLengthPath));
        fclose($fixedLength2Fp);

        //同じ内容で作成ができているかを確認
        $this->assertEquals($fixedLength1, $fixedLength2);

    }

}
