<?php

/*
 * This file is part of the Meta Reader package.
 *
 * (c) Stephan Wentz <stephan@wentz.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Temp\MetaReader\Tests\Value;

use Temp\MetaReader\Value\ValueBag;

/**
 * Meta bag test
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class MetaBagTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorValues()
    {
        $metaBag = new ValueBag(array('foo' => 'bar'));

        $this->assertAttributeContains('bar', 'values', $metaBag);
    }

    public function testHasReturnsCorrectValue()
    {
        $metaBag = new ValueBag(array('foo' => 'bar'));

        $this->assertTrue($metaBag->has('foo'));
        $this->assertFalse($metaBag->has('invalid'));
    }

    public function testGetReturnsCorrectValue()
    {
        $metaBag = new ValueBag(array('foo' => 'bar'));

        $this->assertSame('bar', (string) $metaBag->get('foo'));
    }

    public function testGetReturnsNullOnInvalidKey()
    {
        $metaBag = new ValueBag();

        $this->assertNull($metaBag->get('invalid'));
    }

    public function testGetReturnsDefaultValueOnInvalidKey()
    {
        $metaBag = new ValueBag();

        $this->assertSame('baz', (string) $metaBag->get('invalid', 'baz'));
    }

    public function testRemoveWithValidKeyRemovesValue()
    {
        $metaBag = new ValueBag(array('foo' => 'bar'));

        $metaBag->remove('foo');

        $this->assertNull($metaBag->get('invalid'));
    }

    public function testRemoveWithInvalidKey()
    {
        $metaBag = new ValueBag();

        $metaBag->remove('foo');

        $this->assertNull($metaBag->get('invalid'));
    }

    public function testMerge()
    {
        $metaBag = new ValueBag(array('foo' => 1));
        $mergeMetaBag = new ValueBag(array('bar' => 2));

        $metaBag->merge($mergeMetaBag);

        $this->assertCount(2, $metaBag);
    }

    public function testMergeWithoutOverride()
    {
        $metaBag = new ValueBag(array('foo' => 'bar'));
        $mergeMetaBag = new ValueBag(array('foo' => 'baz'));

        $metaBag->merge($mergeMetaBag);

        $this->assertCount(1, $metaBag);
        $this->assertSame('bar', (string) $metaBag->get('foo'));
    }

    public function testMergeWithOverride()
    {
        $metaBag = new ValueBag(array('foo' => 'bar'));
        $mergeMetaBag = new ValueBag(array('foo' => 'baz'));

        $metaBag->merge($mergeMetaBag, true);

        $this->assertCount(1, $metaBag);
        $this->assertSame('baz', (string) $metaBag->get('foo'));
    }

    public function testCount()
    {
        $metaBag = new ValueBag(array('foo' => 1, 'bar' => 2, 'baz' => 3));

        $this->assertSame(3, $metaBag->count());
        $this->assertCount(3, $metaBag);
    }
}
