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
 * Exif tool reader test.
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class ExiftoolReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExiftoolReader
     */
    private $reader;

    public function setUp()
    {
        if (!class_exists('PHPExiftool\Reader')) {
            $this->markTestSkipped('PHPExiftool\Reader not available.');
        }

        $logger = $this->prophesize('Monolog\Logger');
        $logger->addInfo(Argument::cetera());

        $this->reader = new ExiftoolReader(Reader::create($logger->reveal()));
    }

    public function testAvailable()
    {
        $this->assertTrue($this->reader->available());
    }

    public function testSupportsJpgFile()
    {
        $isSupported = $this->reader->supports(__DIR__.'/fixture/file.jpg');

        $this->assertTrue($isSupported);
    }

    public function testSupportsTxtFile()
    {
        $isSupported = $this->reader->supports(__DIR__.'/fixture/file.txt');

        $this->assertFalse($isSupported);
    }

    public function testReadJpgFile()
    {
        $meta = $this->reader->read(__DIR__.'/fixture/file.jpg');

        $this->assertCount(23, $meta);
        $this->assertTrue($meta->has('File.FileType'));
    }

    public function testReadTextFile()
    {
        $meta = $this->reader->read(__DIR__.'/fixture/file.txt');

        $this->assertCount(0, $meta);
    }
}
