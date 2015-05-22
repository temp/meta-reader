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
use Temp\MetaReader\ImageAnalyzerReader;
use Temp\MetaReader\PdfInfoReader;

/**
 * Image analyzer reader test
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class ImageAnalyzerTest extends \PHPUnit_Framework_TestCase
{
    private function createImageAnalyzer()
    {
        return new ImageAnalyzer(
        );
    }

    public function testAvailable()
    {
        if (!class_exists('Temp\ImageAnalyzer')) {
            $this->markTestSkipped('Temp\ImageAnalyzer not available.');
        }

        $reader = new ImageAnalyzerReader($this->createImageAnalyzer());

        $this->assertTrue($reader->available());
    }

    /**
     * @depends testAvailable
     */
    public function testSupportsPdfFile()
    {
        $reader = new ImageAnalyzerReader($this->createImageAnalyzer());

        $isSupported = $reader->supports(__DIR__ . '/fixture/file.pdf');

        $this->assertTrue($isSupported);
    }

    /**
     * @depends testAvailable
     */
    public function testSupportsTxtFile()
    {
        $reader = new ImageAnalyzerReader($this->createImageAnalyzer());

        $isSupported = $reader->supports(__DIR__ . '/fixture/file.txt');

        $this->assertFalse($isSupported);
    }

    /**
     * @depends testAvailable
     */
    public function testReadPdfFile()
    {
        $reader = new ImageAnalyzerReader($this->createImageAnalyzer());

        $meta = $reader->read(__DIR__ . '/fixture/file.pdf');

        $this->assertCount(18, $meta);
        $this->assertSame('This is a test PDF file', (string) $meta->get('pdfinfo.title'));
    }

    /**
     * @depends testAvailable
     */
    public function testReadTextFile()
    {
        $reader = new ImageAnalyzerReader($this->createImageAnalyzer());

        $meta = $reader->read(__DIR__ . '/fixture/file.txt');

        $this->assertCount(0, $meta);
    }
}
