<?php

/*
 * This file is part of the Meta Reader package.
 *
 * (c) Stephan Wentz <stephan@wentz.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Temp\MetaReader;

use Temp\MetaReader\Value\ValueBag;

/**
 * Chain reader
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class ChainReader implements ReaderInterface
{
    /**
     * @var ReaderInterface[]
     */
    private $readers;

    /**
     * @param ReaderInterface[] $readers
     */
    public function __construct(array $readers = array())
    {
        foreach ($readers as $reader) {
            $this->addReader($reader);
        }
    }

    /**
     * @param ReaderInterface $reader
     */
    public function addReader(ReaderInterface $reader)
    {
        $this->readers[] = $reader;
    }

    /**
     * @return bool
     */
    public function available()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($filename)
    {
        foreach ($this->readers as $reader) {
            if ($reader->isAvailable() && $reader->supports($filename)) {
                return true;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function read($filename)
    {
        $meta = new ValueBag();

        foreach ($this->readers as $reader) {
            if ($reader->isAvailable() && $reader->supports($filename)) {
                $meta->merge($reader->read($filename));
            }
        }

        return $meta;
    }
}
