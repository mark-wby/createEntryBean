<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita1339534a8d369ff91d0a9e63b6e56ca
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'MarkWang\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'MarkWang\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita1339534a8d369ff91d0a9e63b6e56ca::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita1339534a8d369ff91d0a9e63b6e56ca::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}