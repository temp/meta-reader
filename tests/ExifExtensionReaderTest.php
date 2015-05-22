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
    public function testAvailable()
    {
        $reader = new ExifExtensionReader();

        if (!$reader->available()) {
            $this->markTestSkipped('Exif extension not loaded.');
        }
    }

    /**
     * @depends testAvailable
     */
    public function testSupportsJpgFile()
    {
        $reader = new ExifExtensionReader();

        $isSupported = $reader->supports(__DIR__ . '/fixture/file.jpg');

        $this->assertTrue($isSupported);
    }

    /**
     * @depends testAvailable
     */
    public function testSupportsTxtFile()
    {
        $reader = new ExifExtensionReader();

        $isSupported = $reader->supports(__DIR__ . '/fixture/file.txt');

        $this->assertFalse($isSupported);
    }

    /**
     * @depends testAvailable
     */
    public function testReadJpgFile()
    {
        $reader = new ExifExtensionReader();

        $meta = $reader->read(__DIR__ . '/fixture/file.jpg');

        $this->assertCount(2, $meta);
        $this->assertSame(array(
            'exif.Orientation' => '1',
            'exif.Exif_IFD_Pointer' => '38',
        ), $meta->toArray());
    }

    /**
     * @depends testAvailable
     */
    public function testReadTextFile()
    {
        $reader = new ExifExtensionReader();

        $meta = $reader->read(__DIR__ . '/fixture/file.txt');

        $this->assertCount(0, $meta);
    }
}
