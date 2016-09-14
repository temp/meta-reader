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

use Temp\MetaReader\Value\MetaValue;
use Temp\MetaReader\Value\ValueBag;

/**
 * Exif extension reader.
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class ExifExtensionReader implements ReaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function available()
    {
        return extension_loaded('exif') && function_exists('exif_read_data');
    }

    /**
     * {@inheritdoc}
     */
    public function supports($filename)
    {
        try {
            $result = @\exif_imagetype($filename);
        } catch (\Exception $e) {
            return false;
        }

        return $result !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function read($filename)
    {
        $meta = new ValueBag();

        try {
            $result = @\exif_read_data($filename, '', true);
        } catch (\Exception $e) {
            return $meta;
        }

        if (!empty($result['IFD0'])) {
            foreach ($result['IFD0'] as $key => $value) {
                $meta->set("exif.$key", new MetaValue($value));
            }
        }

        return $meta;
    }
}
