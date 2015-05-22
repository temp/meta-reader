<?php

/*
 * This file is part of the Meta Reader package.
 *
 * (c) Stephan Wentz <stephan@wentz.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Temp\MetaReader\Tests;

use Poppler\Processor\PdfFile;
use Temp\MetaReader\PdfInfoReader;

/**
 * pdfinfo reader test
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class PdfInfoReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PdfInfoReader
     */
    private $reader;

    public function setUp()
    {
        if (!class_exists('Poppler\Processor\PdfFile')) {
            $this->markTestSkipped('Poppler\Processor\PdfFile not available.');
        }

        try {
            $this->reader = new PdfInfoReader(
                new PdfFile(
                    \Poppler\Driver\Pdfinfo::create(),
                    \Poppler\Driver\Pdftotext::create(),
                    \Poppler\Driver\Pdftohtml::create()
                )
            );
        } catch (\Exception $e) {
            $this->markTestSkipped('PdfInfoReader not available.');
        }
    }

    public function testAvailable()
    {
        $this->assertTrue($this->reader->available());
    }

    public function testSupportsPdfFile()
    {
        $this->assertTrue($this->reader->supports(__DIR__ . '/fixture/file.pdf'));
    }

    public function testSupportsTxtFile()
    {
        $this->assertFalse($this->reader->supports(__DIR__ . '/fixture/file.txt'));
    }

    public function testReadPdfFile()
    {
        $meta = $this->reader->read(__DIR__ . '/fixture/file.pdf');

        $this->assertCount(18, $meta);
        $this->assertSame('This is a test PDF file', (string) $meta->get('pdfinfo.title'));
    }

    public function testReadTextFile()
    {
        $meta = $this->reader->read(__DIR__ . '/fixture/file.txt');

        $this->assertCount(0, $meta);
    }
}
