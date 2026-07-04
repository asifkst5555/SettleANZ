<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Paths
    |--------------------------------------------------------------------------
    |
    | Here you may specify which view paths should be checked when rendering a
    | view. The first view that exists will be used. A main path is already
    | registered for you in the directory structure of your application.
    |
    */

    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled Views Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be
    | stored for your application. Typically, this is within the storage
    | directory. However, as usual, you are free to change this value.
    |
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),

    /*
    |--------------------------------------------------------------------------
    | Blade View Cache
    |--------------------------------------------------------------------------
    |
    | This option controls whether the compiled Blade templates are cached for
    | future requests. The cache is enabled by default in production and can
    | be disabled for local development.
    |
    */

    'cache' => env('VIEW_CACHE', true),

    /*
    |--------------------------------------------------------------------------
    | Relative Path Hashing
    |--------------------------------------------------------------------------
    |
    | This option controls whether Blade compiled view file names should use a
    | hash that is relative to the application base path. Keep this disabled
    | unless you need stable compiled paths across deployments.
    |
    */

    'relative_hash' => env('VIEW_RELATIVE_HASH', false),

];