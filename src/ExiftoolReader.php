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

use PHPExiftool\Driver\Metadata\Metadata;
use PHPExiftool\Driver\Value\ValueInterface;
use PHPExiftool\FileEntity;
use PHPExiftool\Reader;
use Temp\MetaReader\Value\MetaValue;
use Temp\MetaReader\Value\ValueBag;

/**
 * Exiftool reader.
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class ExiftoolReader implements ReaderInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
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
        try {
            $fileEntity = $this->reader->reset()->files($filename)->first();
        } catch (\Exception $e) {
            return false;
        }

        return $fileEntity instanceof FileEntity;
    }

    /**
     * {@inheritdoc}
     */
    public function read($filename)
    {
        $meta = new ValueBag();

        try {
            $fileEntity = $this->reader->reset()->files($filename)->first();
        } catch (\Exception $e) {
            return $meta;
        }

        if ($fileEntity->getMetadatas()->containsKey('File:ImageWidth')) {
            $meta->set(
                'image.width',
                new MetaValue((int) $fileEntity->getMetadatas()->get('File:ImageWidth')->getValue()->asString())
            );
        } elseif ($fileEntity->getMetadatas()->containsKey('PNG:ImageWidth')) {
            $meta->set(
                'image.width',
                new MetaValue((int) $fileEntity->getMetadatas()->get('PNG:ImageWidth')->getValue()->asString())
            );
        }

        if ($fileEntity->getMetadatas()->containsKey('File:ImageHeight')) {
            $meta->set(
                'image.height',
                new MetaValue((int) $fileEntity->getMetadatas()->get('File:ImageHeight')->getValue()->asString())
            );
        } elseif ($fileEntity->getMetadatas()->containsKey('PNG:ImageHeight')) {
            $meta->set(
                'image.height',
                new MetaValue((int) $fileEntity->getMetadatas()->get('PNG:ImageHeight')->getValue()->asString())
            );
        }

        foreach ($fileEntity as $metadata) {
            /* @var $metadata Metadata */

            if (ValueInterface::TYPE_BINARY === $metadata->getValue()->getType()) {
                continue;
            }

            $groupName = ($metadata->getTag()->getGroupName());
            $name = ($metadata->getTag()->getName());
            $value = (string) $metadata->getValue();
            if ($groupName === 'System' || !ctype_print($value)) {
                continue;
            }

            $path = "$groupName.$name";
            $meta->set($path, new MetaValue($value));
        }

        return $meta;
    }
}
