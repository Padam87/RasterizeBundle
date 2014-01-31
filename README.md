# Rasterize Bundle #

A bundle to rasterize web pages with PhantomJS for Symfony2

## 1. Example ##

```php
$response = new Response(
    $this->get('padam87_rasterize.rasterizer')->rasterize(
        $this->renderView('Bundle:Folder:template.pdf.twig')
    ),
    200, [
        'Content-Type'          => 'application/pdf',
        'Content-Disposition'   => 'attachment; filename="my.pdf"'
    ]
);
```

## 2. Installation ##

### 2.1. Composer ###

    "padam87/rasterize-bundle": "1.0.*",

### 2.2. AppKernel ###

    $bundles = array(
		...
        new Padam87\RasterizeBundle\Padam87RasterizeBundle(),
    );

### 2.3 Install assets ###

	php app/console assets:install

## 3. Configuration reference ##

*NOTE: No configuration necessary by default*

```YAML
web_dir:              /../web # Temp dir location related to %kernel.root_dir%.
temp_dir:             /bundles/padam87rasterize/temp # Temp dir location related to web dir. Must be in a location accessible by the web server.
phantomjs:
    callable:             phantomjs
    options:              [] # https://github.com/ariya/phantomjs/wiki/API-Reference#wiki-command-line-options
script:               /bundles/padam87rasterize/js/rasterize.js # Relative to web dir
arguments:
    format:              pdf # Default, will always be added, even if you remove it from here
```


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/Padam87/rasterizebundle/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

