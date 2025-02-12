<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    'core' => [
        'path' => './assets/app/core.js',
    ],
    'form' => [
        'path' => './assets/app/form.js',
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/turbo' => [
        'version' => '7.3.0',
    ],
    'bootstrap' => [
        'version' => '5.3.3',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.3',
        'type' => 'css',
    ],
    'trix' => [
        'version' => '2.1.12',
    ],
    'trix/dist/trix.min.css' => [
        'version' => '2.1.12',
        'type' => 'css',
    ],
    'datatables.net-dt' => [
        'version' => '2.2.2',
    ],
    'jquery' => [
        'version' => '3.7.1',
    ],
    'datatables.net' => [
        'version' => '2.2.2',
    ],
    'datatables.net-dt/css/dataTables.dataTables.min.css' => [
        'version' => '2.2.2',
        'type' => 'css',
    ],
    'datatables.net-responsive-dt' => [
        'version' => '3.0.4',
    ],
    'datatables.net-responsive' => [
        'version' => '3.0.4',
    ],
    'datatables.net-responsive-dt/css/responsive.dataTables.min.css' => [
        'version' => '3.0.4',
        'type' => 'css',
    ],
    'datatables.net-buttons-dt' => [
        'version' => '3.2.2',
    ],
    'datatables.net-buttons' => [
        'version' => '3.2.2',
    ],
    'datatables.net-buttons-dt/css/buttons.dataTables.min.css' => [
        'version' => '3.2.2',
        'type' => 'css',
    ],
    'slim-select' => [
        'version' => '2.10.0',
    ],
    'slim-select/dist/slimselect.min.css' => [
        'version' => '2.10.0',
        'type' => 'css',
    ],
    'leaflet' => [
        'version' => '1.9.4',
    ],
    'leaflet/dist/leaflet.min.css' => [
        'version' => '1.9.4',
        'type' => 'css',
    ],
    'leaflet-gesture-handling' => [
        'version' => '1.2.2',
    ],
];
