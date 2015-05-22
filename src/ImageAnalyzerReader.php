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

use Temp\ImageAnalyzer\ImageAnalyzer;
use Temp\MetaReader\Value\MetaValue;
use Temp\MetaReader\Value\ValueBag;

/**
 * Image analyzer reader
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class ImageAnalyzerReader implements ReaderInterface
{
    /**
     * @var ImageAnalyzer
     */
    private $analyzer;

    /**
     * @param ImageAnalyzer $analyzer
     */
    public function __construct(ImageAnalyzer $analyzer)
    {
        $this->analyzer = $analyzer;
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
        return $this->analyzer->supports($filename);
    }

    /**
     * {@inheritdoc}
     */
    public function read($filename)
    {
        $meta = new ValueBag();

        try {
            $imageInfo = $this->analyzer->analyze($filename);

            $meta
                ->set('image.width', new MetaValue($imageInfo->getWidth()))
                ->set('image.height', new MetaValue($imageInfo->getHeight()))
                ->set('image.format', new MetaValue($imageInfo->getFormat()))
                ->set('image.type', new MetaValue($imageInfo->getType()))
                ->set('image.colorspace', new MetaValue($imageInfo->getColorspace()))
                ->set('image.depth', new MetaValue($imageInfo->getDepth()));

            if ($imageInfo->getColors()) {
                $meta->set('image.colors', new MetaValue($imageInfo->getColors()));
            }
            if ($imageInfo->getQuality()) {
                $meta->set('image.quality', new MetaValue($imageInfo->getQuality()));
            }
            if ($imageInfo->getCompression()) {
                $meta->set('image.compression', new MetaValue($imageInfo->getCompression()));
            }
            if ($imageInfo->getResolution()) {
                $meta->set('image.resolution', new MetaValue($imageInfo->getResolution()));
            }
            if ($imageInfo->getUnits()) {
                $meta->set('image.units', new MetaValue($imageInfo->getUnits()));
            }
            if ($imageInfo->getProfiles()) {
                $meta->set('image.profiles', new MetaValue(implode(',', $imageInfo->getProfiles())));
            }
        } catch (\Exception $e) {
        }

        return $meta;
    }
}
