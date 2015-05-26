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

use Prophecy\Argument;
use Temp\MetaReader\ChainReader;
use Temp\MetaReader\Value\ValueBag;

/**
 * Chain reader test
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class ChainReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ChainReader
     */
    private $reader;

    public function setUp()
    {
        $reader = new ChainReader();

        $this->reader = $reader;
    }

    public function testAvailableFalse()
    {
        $readerMock1 = $this->prophesize('Temp\MetaReader\ReaderInterface');
        $readerMock1->available()->willReturn(false);
        $this->reader->addReader($readerMock1->reveal());

        $this->assertFalse($this->reader->available());
    }

    public function testAvailableTrue()
    {
        $readerMock1 = $this->prophesize('Temp\MetaReader\ReaderInterface');
        $readerMock1->available()->willReturn(true);
        $this->reader->addReader($readerMock1->reveal());

        $this->assertTrue($this->reader->available());
    }

    public function testSupportsFalse()
    {
        $readerMock1 = $this->prophesize('Temp\MetaReader\ReaderInterface');
        $readerMock1->available()->willReturn(true);
        $readerMock1->supports(Argument::cetera())->willReturn(false);
        $this->reader->addReader($readerMock1->reveal());

        $isSupported = $this->reader->supports(__DIR__ . '/fixture/file.jpg');

        $this->assertFalse($isSupported);
    }

    public function testSupportsTrue()
    {
        $readerMock1 = $this->prophesize('Temp\MetaReader\ReaderInterface');
        $readerMock1->available()->willReturn(true);
        $readerMock1->supports(Argument::cetera())->willReturn(true);
        $this->reader->addReader($readerMock1->reveal());

        $isSupported = $this->reader->supports(__DIR__ . '/fixture/file.jpg');

        $this->assertTrue($isSupported);
    }

    public function testReadFile()
    {
        $readerMock1 = $this->prophesize('Temp\MetaReader\ReaderInterface');
        $readerMock1->available()->willReturn(true);
        $readerMock1->supports(Argument::cetera())->willReturn(true);
        $readerMock1->read(Argument::cetera())->willReturn(new ValueBag());
        $this->reader->addReader($readerMock1->reveal());

        $meta = $this->reader->read(__DIR__ . '/fixture/file.jpg');

        $this->assertCount(0, $meta);
    }
}
