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

use Phlexible\Component\MediaType\Model\MediaType;
use Poppler\Processor\PdfFile;
use Temp\MetaReader\Value\MetaValue;
use Temp\MetaReader\Value\ValueBag;

/**
 * pdfinfo reader
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class PdfInfoReader implements ReaderInterface
{
    /**
     * @var PdfFile
     */
    private $pdfFile;

    /**
     * @param PdfFile $pdfFile
     */
    public function __construct(PdfFile $pdfFile)
    {
        $this->pdfFile = $pdfFile;
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
            $this->pdfFile->getInfo($filename);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($filename)
    {
        $meta = new ValueBag();

        try {
            $infos = $this->pdfFile->getInfo($filename);

            foreach ($infos as $key => $value) {
                $meta->set(strtolower("pdfinfo.$key"), new MetaValue($value));
            }
        } catch (\Exception $e) {
        }

        return $meta;
    }
}
