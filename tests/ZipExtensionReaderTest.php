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

use Temp\MetaReader\ZipExtensionReader;

/**
 * Zip extension reader test.
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class ZipExtensionReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testAvailable()
    {
        $reader = new ZipExtensionReader();

        if (!$reader->available()) {
            $this->markTestSkipped('Zip extension not loaded.');
        }
    }

    /**
     * @depends testAvailable
     */
    public function testSupportsZipFile()
    {
        $reader = new ZipExtensionReader();

        $isSupported = $reader->supports(__DIR__.'/fixture/file.zip');

        $this->assertTrue($isSupported);
    }

    /**
     * @depends testAvailable
     */
    public function testSupportsTxtFile()
    {
        $reader = new ZipExtensionReader();

        $isSupported = $reader->supports(__DIR__.'/fixture/file.txt');

        $this->assertFalse($isSupported);
    }

    /**
     * @depends testAvailable
     */
    public function testReadZipFile()
    {
        $reader = new ZipExtensionReader();

        $meta = $reader->read(__DIR__.'/fixture/file.zip');

        $this->assertCount(1, $meta);
        $this->assertSame('1', (string) $meta->get('zip.numFiles'));
    }

    /**
     * @depends testAvailable
     */
    public function testReadTextFile()
    {
        $reader = new ZipExtensionReader();

        $meta = $reader->read(__DIR__.'/fixture/file.txt');

        $this->assertCount(0, $meta);
    }
}
