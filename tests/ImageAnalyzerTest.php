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

use Temp\ImageAnalyzer\Driver\GdDriver;
use Temp\MetaReader\ImageAnalyzerReader;
use Temp\ImageAnalyzer\ImageAnalyzer;

/**
 * Image analyzer reader test
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class ImageAnalyzerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ImageAnalyzerReader
     */
    private $reader;

    public function setUp()
    {
        if (!class_exists('Temp\ImageAnalyzer\ImageAnalyzer')) {
            $this->markTestSkipped('Temp\ImageAnalyzer not available.');
        }

        $this->reader = new ImageAnalyzerReader(new ImageAnalyzer(new GdDriver()));
    }

    public function testAvailable()
    {
        $this->assertTrue($this->reader->available());
    }

    public function testSupportsJpgFile()
    {
        $isSupported = $this->reader->supports(__DIR__ . '/fixture/file.jpg');

        $this->assertTrue($isSupported);
    }

    public function testSupportsTxtFile()
    {
        $isSupported = $this->reader->supports(__DIR__ . '/fixture/file.txt');

        $this->assertFalse($isSupported);
    }

    public function testReadJpgFile()
    {
        $meta = $this->reader->read(__DIR__ . '/fixture/file.jpg');

        $this->assertCount(6, $meta);
        $this->assertSame('RGB', (string) $meta->get('image.colorspace'));
    }

    public function testReadTextFile()
    {
        $meta = $this->reader->read(__DIR__ . '/fixture/file.txt');

        $this->assertCount(0, $meta);
    }
}
