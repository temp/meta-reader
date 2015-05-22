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

use FFMpeg\FFProbe;
use FFMpeg\FFProbe\DataMapping\Stream;
use Temp\MetaReader\Value\MetaValue;
use Temp\MetaReader\Value\ValueBag;

/**
 * Ffprobe reader
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class FfprobeReader implements ReaderInterface
{
    /**
     * @var FFProbe
     */
    private $ffprobe;

    /**
     * @param FFProbe $ffprobe
     */
    public function __construct(FFProbe $ffprobe)
    {
        $this->ffprobe = $ffprobe;
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
            $streams = $this->ffprobe->streams($filename);
        } catch (\Exception $e) {
            return false;
        }

        foreach ($streams as $stream) {
            if (!$stream->has('codec_type') || !in_array($stream->get('codec_type'), array('audio', 'video'))) {
                return false;
            }
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
            $format = $this->ffprobe->format($filename);

            if ($format->has('format_name')) {
                $meta->set('media.format_name', new MetaValue($format->get('format_name')));
            }
            if ($format->has('format_long_name')) {
                $meta->set('media.format_long_name', new MetaValue($format->get('format_long_name')));
            }
            if ($format->has('duration')) {
                $meta->set('media.duration', new MetaValue($format->get('duration')));
            }
            if ($format->has('bit_rate')) {
                $meta->set('media.bit_rate', new MetaValue($format->get('bit_rate')));
            }
            if ($format->has('width')) {
                $meta->set('media.width', new MetaValue($format->get('width')));
            }
            if ($format->has('height')) {
                $meta->set('media.height', new MetaValue($format->get('height')));
            }
            if ($format->has('nb_streams')) {
                $meta->set('media.number_of_streams', new MetaValue($format->get('nb_streams')));
            }

            $streams = $this->ffprobe->streams($filename);

            foreach ($streams as $stream) {
                /* @var $stream Stream */
                $index = $stream->get('index');
                $prefix = 'stream_' . $index;

                $type = 'media';
                if ($stream->isVideo()) {
                    $type = 'video';
                } elseif ($stream->isAudio()) {
                    $type = 'audio';
                }

                if ($stream->has('codec_type')) {
                    $meta->set("$type.$prefix.codec_type", new MetaValue($stream->get('codec_type')));
                }
                if ($stream->has('codec_name')) {
                    $meta->set("$type.$prefix.codec_name", new MetaValue($stream->get('codec_name')));
                }
                if ($stream->has('codec_long_name')) {
                    $meta->set("$type.$prefix.codec_long_name", new MetaValue($stream->get('codec_long_name')));
                }
                if ($stream->has('codec_time_base')) {
                    $meta->set("$type.$prefix.codec_time_base", new MetaValue($stream->get('codec_time_base')));
                }
                if ($stream->has('codec_tag_string')) {
                    $meta->set("$type.$prefix.codec_tag", new MetaValue($stream->get('codec_tag_string')));
                }
                if ($stream->has('bit_rate')) {
                    $meta->set("$type.$prefix.bit_rate", new MetaValue($stream->get('bit_rate')));
                }
                if ($stream->has('display_aspect_ration')) {
                    $meta->set("$type.$prefix.aspect_ratio", new MetaValue($stream->get('display_aspect_ratio')));
                }
                if ($stream->has('avg_frame_rate')) {
                    $meta->set("$type.$prefix.frame_rate", new MetaValue($stream->get('avg_frame_rate')));
                }
                if ($stream->has('bits_per_sample')) {
                    $meta->set("$type.$prefix.bits_per_sample", new MetaValue($stream->get('bits_per_sample')));
                }
                if ($stream->has('channels')) {
                    $meta->set("$type.$prefix.channels", new MetaValue($stream->get('channels')));
                }
            }
        } catch (\Exception $e) {
        }

        return $meta;
    }
}
