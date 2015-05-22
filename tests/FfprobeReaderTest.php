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
    private function createFfprobe()
    {
        return FFProbe::create();
    }

    public function testAvailable()
    {
        if (!class_exists('FFMpeg\FFProbe')) {
            $this->markTestSkipped('FFMpeg\FFProbe not available.');
        }

        $reader = new FfprobeReader($this->createFfprobe());

        $this->assertTrue($reader->available());
    }

    /**
     * @depends testAvailable
     */
    public function testSupportsJpgFile()
    {
        $reader = new FfprobeReader($this->createFfprobe());

        $isSupported = $reader->supports(__DIR__ . '/fixture/file.jpg');

        $this->assertTrue($isSupported);
    }

    /**
     * @depends testAvailable
     */
    public function testSupportsPdfFile()
    {
        $reader = new FfprobeReader($this->createFfprobe());

        $isSupported = $reader->supports(__DIR__ . '/fixture/file.pdf');

        $this->assertFalse($isSupported);
    }

    /**
     * @depends testAvailable
     */
    public function testReadJpgFile()
    {
        $reader = new FfprobeReader($this->createFfprobe());

        $meta = $reader->read(__DIR__ . '/fixture/file.jpg');

        $this->assertCount(9, $meta);

        $this->assertTrue($meta->has('media.format_name'));
        $this->assertTrue($meta->has('video.stream_0.codec_type'));
    }

    /**
     * @depends testAvailable
     */
    public function testReadPdfFile()
    {
        $reader = new FfprobeReader($this->createFfprobe());

        $meta = $reader->read(__DIR__ . '/fixture/file.pdf');

        $this->assertCount(0, $meta);
    }
}
