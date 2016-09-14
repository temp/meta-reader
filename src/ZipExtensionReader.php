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
 * Zip extension reader.
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class ZipExtensionReader implements ReaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function available()
    {
        return extension_loaded('zip');
    }

    /**
     * {@inheritdoc}
     */
    public function supports($filename)
    {
        $zip = new \ZipArchive();

        return $zip->open($filename) === true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($filename)
    {
        $meta = new ValueBag();

        try {
            $zip = new \ZipArchive();
            $result = $zip->open($filename);

            if ($result === true) {
                if ($zip->comment) {
                    $meta->set('zip.comment', new MetaValue($zip->comment));
                }

                if ($zip->numFiles) {
                    $meta->set('zip.numFiles', new MetaValue($zip->numFiles));
                }

                if ($zip->status) {
                    $meta->set('zip.status', new MetaValue($zip->status));
                }

                $zip->close();
            }
        } catch (\Exception $e) {
        }

        return $meta;
    }
}
