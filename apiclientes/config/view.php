<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Template Engine [EXPERIMENTAL]
    |--------------------------------------------------------------------------
    */
    'viewEngine' => \Leaf\Blade::class,

    /*
    |--------------------------------------------------------------------------
    | Custom config method
    |--------------------------------------------------------------------------
    */
    'config' => function ($bladeInstance) {
        $viewsPath = \Leaf\Config::get('views.path');
        $cachePath = \Leaf\Config::get('views.cachePath');

        if ($viewsPath && $cachePath) {
            $bladeInstance->configure($viewsPath, $cachePath);
        } else {
            error_log('Error: View paths or cache path not defined in app.php for Blade configuration.');
        }
    },

    /*
    |--------------------------------------------------------------------------
    | Custom render method
    |--------------------------------------------------------------------------
    */
    'render' => null,

    /*
    |--------------------------------------------------------------------------
    | Custom View Engine Extension [NUEVA LÍNEA A AÑADIR]
    |--------------------------------------------------------------------------
    |
    | Define una función para extender tu motor de vistas (ej. añadir directivas a Blade).
    | Si no necesitas extenderlo, puedes dejarlo como null o una función vacía.
    |
    */
    'extend' => null, // O puedes poner una función anónima vacía: 'extend' => function ($template) {},
];