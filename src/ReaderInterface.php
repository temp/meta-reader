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
 * Reader interface
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
interface ReaderInterface
{
    /**
     * Check if requirements for reader are given
     *
     * @return bool
     */
    public function available();

    /**
     * Check if reader supports the given file
     *
     * @param string $filename
     *
     * @return bool
     */
    public function supports($filename);

    /**
     * Read meta
     *
     * @param string $filename
     *
     * @return ValueBag
     */
    public function read($filename);
}
