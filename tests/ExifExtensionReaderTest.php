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

use Temp\MetaReader\ExifExtensionReader;

/**
 * Exif extension reader test
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class ExifExtensionReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExifExtensionReader
     */
    private $reader;

    public function setUp()
    {
        $reader = new ExifExtensionReader();

        if (!$reader->available()) {
            $this->markTestSkipped('Exif extension not loaded.');
        }

        $this->reader = $reader;
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

    public function testSupportsPngFile()
    {
        $isSupported = $this->reader->supports(__DIR__ . '/fixture/file.png');

        $this->assertFalse($isSupported);
    }

    public function testSupportsTxtFile()
    {
        $isSupported = $this->reader->supports(__DIR__ . '/fixture/file.txt');

        $this->assertFalse($isSupported);
    }

    public function testReadJpgFile()
    {
        $meta = $this->reader->read(__DIR__ . '/fixture/file.jpg');

        $this->assertCount(1, $meta);
        $this->assertSame(array(
            'exif.Orientation' => '1',
        ), $meta->toArray());
    }

    public function testReadPngFile()
    {
        $meta = $this->reader->read(__DIR__ . '/fixture/file.png');

        $this->assertCount(0, $meta);
    }

    public function testReadTextFile()
    {
        $meta = $this->reader->read(__DIR__ . '/fixture/file.txt');

        $this->assertCount(0, $meta);
    }
}
