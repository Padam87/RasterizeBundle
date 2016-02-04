## Installation ##

### Composer ###

    "padam87/rasterize-bundle": "~1.0",

### AppKernel ###

    $bundles = array(
        ...
        new Padam87\RasterizeBundle\Padam87RasterizeBundle(),
    );

### Install assets ###

    php app/console assets:install
