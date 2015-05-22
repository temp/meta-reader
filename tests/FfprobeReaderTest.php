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

use FFMpeg\FFProbe;
use Prophecy\Argument;
use Temp\MetaReader\FfprobeReader;

/**
 * Ffprobe reader test
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class FfprobeReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FfprobeReader
     */
    private $reader;

    public function setUp()
    {
        if (!class_exists('FFMpeg\FFProbe')) {
            $this->markTestSkipped('FFMpeg\FFProbe not available.');
        }

        $this->reader = new FfprobeReader(FFProbe::create());
    }

    public function testAvailable()
    {
        $this->assertTrue($this->reader->available());
    }

    public function testSupportsJpgFile()
    {
        $this->assertTrue($this->reader->supports(__DIR__ . '/fixture/file.jpg'));
    }

    public function testSupportsPdfFile()
    {
        $this->assertFalse($this->reader->supports(__DIR__ . '/fixture/file.pdf'));
    }

    public function testReadJpgFile()
    {
        $meta = $this->reader->read(__DIR__ . '/fixture/file.jpg');

        $this->assertCount(9, $meta);
        $this->assertTrue($meta->has('media.format_name'));
        $this->assertTrue($meta->has('video.stream_0.codec_type'));
    }

    public function testReadPdfFile()
    {
        $meta = $this->reader->read(__DIR__ . '/fixture/file.pdf');

        $this->assertCount(0, $meta);
    }
}
