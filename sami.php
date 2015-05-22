<?php

include 'vendor/autoload.php';

use Sami\Sami;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in($dir = __DIR__ . '/src')
;

return new Sami($iterator, array(
    'title'                => 'MimeSniffer API',
    'build_dir'            => __DIR__.'/build/api',
    'cache_dir'            => __DIR__.'/build/cache',
    'default_opened_level' => 2,
));
