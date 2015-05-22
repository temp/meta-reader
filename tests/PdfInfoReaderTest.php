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
    private function createPdfFile()
    {
        return new PdfFile(
            \Poppler\Driver\Pdfinfo::create(),
            \Poppler\Driver\Pdftotext::create(),
            \Poppler\Driver\Pdftohtml::create()
        );
    }

    public function testAvailable()
    {
        if (!class_exists('Poppler\Processor\PdfFile')) {
            $this->markTestSkipped('Poppler\Processor\PdfFile not available.');
        }

        $reader = new PdfInfoReader($this->createPdfFile());

        $this->assertTrue($reader->available());
    }

    /**
     * @depends testAvailable
     */
    public function testSupportsPdfFile()
    {
        $reader = new PdfInfoReader($this->createPdfFile());

        $isSupported = $reader->supports(__DIR__ . '/fixture/file.pdf');

        $this->assertTrue($isSupported);
    }

    /**
     * @depends testAvailable
     */
    public function testSupportsTxtFile()
    {
        $reader = new PdfInfoReader($this->createPdfFile());

        $isSupported = $reader->supports(__DIR__ . '/fixture/file.txt');

        $this->assertFalse($isSupported);
    }

    /**
     * @depends testAvailable
     */
    public function testReadPdfFile()
    {
        $reader = new PdfInfoReader($this->createPdfFile());

        $meta = $reader->read(__DIR__ . '/fixture/file.pdf');

        $this->assertCount(18, $meta);
        $this->assertSame('This is a test PDF file', (string) $meta->get('pdfinfo.title'));
    }

    /**
     * @depends testAvailable
     */
    public function testReadTextFile()
    {
        $reader = new PdfInfoReader($this->createPdfFile());

        $meta = $reader->read(__DIR__ . '/fixture/file.txt');

        $this->assertCount(0, $meta);
    }
}
