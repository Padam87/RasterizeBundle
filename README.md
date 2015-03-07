[![Build Status](https://travis-ci.org/Padam87/RasterizeBundle.svg?branch=master)](https://travis-ci.org/Padam87/RasterizeBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Padam87/RasterizeBundle/badges/quality-score.png?s=dbb22e5306bff73f2b3494261ddb8d6d7c6b35d7)](https://scrutinizer-ci.com/g/Padam87/RasterizeBundle/)
[![Coverage Status](https://coveralls.io/repos/Padam87/RasterizeBundle/badge.png)](https://coveralls.io/r/Padam87/RasterizeBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/7e2ecf7a-40d7-41f4-9103-a99593c435d2/mini.png)](https://insight.sensiolabs.com/projects/7e2ecf7a-40d7-41f4-9103-a99593c435d2)

[![Latest Stable Version](https://poser.pugx.org/padam87/rasterize-bundle/v/stable.png)](https://packagist.org/packages/padam87/rasterize-bundle)
[![Latest Unstable Version](https://poser.pugx.org/padam87/rasterize-bundle/v/unstable.png)](https://packagist.org/packages/padam87/rasterize-bundle)
[![Total Downloads](https://poser.pugx.org/padam87/rasterize-bundle/downloads.png)](https://packagist.org/packages/padam87/rasterize-bundle)
[![Monthly Downloads](https://poser.pugx.org/padam87/rasterize-bundle/d/monthly.png)](https://packagist.org/packages/padam87/rasterize-bundle)

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

    "padam87/rasterize-bundle": "~1.0",

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
padam87_rasterize:
    web_dir:              /../web # Temp dir location related to %kernel.root_dir%.
    temp_dir:             /bundles/padam87rasterize/temp # Temp dir location related to web dir. Must be in a location accessible by the web server.
    phantomjs:
        callable:             phantomjs
        options:              [] # https://github.com/ariya/phantomjs/wiki/API-Reference#wiki-command-line-options
    script:               /bundles/padam87rasterize/js/rasterize.js # Relative to web dir
    arguments: # You can define your own custom arguments. Will be added by default to every process.
        format:              pdf # Default, will always be added, even if you remove it from here.
```
