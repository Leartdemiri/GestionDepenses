<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf3f4f79338c1ad35f63daab814f3ad8d
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
        'L' => 
        array (
            'LeartDmr2\\Depenses\\' => 19,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/src',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
        'LeartDmr2\\Depenses\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf3f4f79338c1ad35f63daab814f3ad8d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf3f4f79338c1ad35f63daab814f3ad8d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf3f4f79338c1ad35f63daab814f3ad8d::$classMap;

        }, null, ClassLoader::class);
    }
}
