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

use PHPExiftool\Reader;
use Prophecy\Argument;
use Temp\MetaReader\ExiftoolReader;

/**
 * Exif tool reader test
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class ExiftoolReaderTest extends \PHPUnit_Framework_TestCase
{
    private function createReader()
    {
        $logger = $this->prophesize('Monolog\Logger');
        $logger->addInfo(Argument::cetera());

        return Reader::create($logger->reveal());
    }

    public function testAvailable()
    {
        if (!class_exists('PHPExiftool\Reader')) {
            $this->markTestSkipped('PHPExiftool\Reader not available.');
        }

        $reader = new ExiftoolReader($this->createReader());

        $this->assertTrue($reader->available());
    }

    /**
     * @depends testAvailable
     */
    public function testSupportsJpgFile()
    {
        $reader = new ExiftoolReader($this->createReader());

        $isSupported = $reader->supports(__DIR__ . '/fixture/file.jpg');

        $this->assertTrue($isSupported);
    }

    /**
     * @depends testAvailable
     */
    public function testSupportsTxtFile()
    {
        $reader = new ExiftoolReader($this->createReader());

        $isSupported = $reader->supports(__DIR__ . '/fixture/file.txt');

        $this->assertFalse($isSupported);
    }

    /**
     * @depends testAvailable
     */
    public function testReadJpgFile()
    {
        $reader = new ExiftoolReader($this->createReader());

        $meta = $reader->read(__DIR__ . '/fixture/file.jpg');

        $this->assertCount(19, $meta);
        $this->assertTrue($meta->has('exiftool.file.filetype'));
    }

    /**
     * @depends testAvailable
     */
    public function testReadTextFile()
    {
        $reader = new ExiftoolReader($this->createReader());

        $meta = $reader->read(__DIR__ . '/fixture/file.txt');

        $this->assertCount(0, $meta);
    }
}
