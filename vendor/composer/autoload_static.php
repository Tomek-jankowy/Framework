<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitda6920b0428554338ece6c8a2e6c234e
{
    public static $files = array (
        '6e3fae29631ef280660b3cdad06f25a8' => __DIR__ . '/..' . '/symfony/deprecation-contracts/function.php',
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        '7e68e9daf6f734ec99981e140fdbc34f' => __DIR__ . '/..' . '/App/Core/OtherFunction.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Ctype\\' => 23,
            'Symfony\\Component\\Yaml\\' => 23,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'Symfony\\Component\\Yaml\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/yaml',
        ),
    );

    public static $prefixesPsr0 = array (
        'A' => 
        array (
            'App\\Module\\Views\\' => 
            array (
                0 => __DIR__ . '/../..' . '/vendor',
            ),
            'App\\Module\\Users\\' => 
            array (
                0 => __DIR__ . '/../..' . '/vendor',
            ),
            'App\\Module\\Tomplate\\' => 
            array (
                0 => __DIR__ . '/../..' . '/vendor',
            ),
            'App\\Core\\Settings\\' => 
            array (
                0 => __DIR__ . '/../..' . '/vendor',
            ),
            'App\\Core\\Routing\\' => 
            array (
                0 => __DIR__ . '/../..' . '/vendor',
            ),
            'App\\Core\\Http\\' => 
            array (
                0 => __DIR__ . '/../..' . '/vendor',
            ),
            'App\\Core\\Entity\\' => 
            array (
                0 => __DIR__ . '/../..' . '/vendor',
            ),
            'App\\Core\\Database\\' => 
            array (
                0 => __DIR__ . '/../..' . '/vendor',
            ),
            'App\\Core\\AppException\\' => 
            array (
                0 => __DIR__ . '/../..' . '/vendor',
            ),
            'App\\Core\\AppCore\\' => 
            array (
                0 => __DIR__ . '/../..' . '/vendor',
            ),
            'App\\Core\\Api\\' => 
            array (
                0 => __DIR__ . '/../..' . '/vendor',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitda6920b0428554338ece6c8a2e6c234e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitda6920b0428554338ece6c8a2e6c234e::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitda6920b0428554338ece6c8a2e6c234e::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitda6920b0428554338ece6c8a2e6c234e::$classMap;

        }, null, ClassLoader::class);
    }
}
