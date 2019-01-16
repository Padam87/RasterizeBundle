[![Build Status](https://travis-ci.org/Padam87/RasterizeBundle.svg?branch=master)](https://travis-ci.org/Padam87/RasterizeBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Padam87/RasterizeBundle/badges/quality-score.png?s=dbb22e5306bff73f2b3494261ddb8d6d7c6b35d7)](https://scrutinizer-ci.com/g/Padam87/RasterizeBundle/)
[![Coverage Status](https://coveralls.io/repos/Padam87/RasterizeBundle/badge.png)](https://coveralls.io/r/Padam87/RasterizeBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/7e2ecf7a-40d7-41f4-9103-a99593c435d2/mini.png)](https://insight.sensiolabs.com/projects/7e2ecf7a-40d7-41f4-9103-a99593c435d2)

[![License](https://poser.pugx.org/padam87/rasterize-bundle/license.png)](https://packagist.org/packages/padam87/rasterize-bundle)
[![Latest Stable Version](https://poser.pugx.org/padam87/rasterize-bundle/v/stable.png)](https://packagist.org/packages/padam87/rasterize-bundle)
[![Latest Unstable Version](https://poser.pugx.org/padam87/rasterize-bundle/v/unstable.png)](https://packagist.org/packages/padam87/rasterize-bundle)
[![Total Downloads](https://poser.pugx.org/padam87/rasterize-bundle/downloads.png)](https://packagist.org/packages/padam87/rasterize-bundle)
[![Monthly Downloads](https://poser.pugx.org/padam87/rasterize-bundle/d/monthly.png)](https://packagist.org/packages/padam87/rasterize-bundle)

# Rasterize Bundle #

A bundle to rasterize web pages with Puppeteer (or other) for Symfony2

```php
$response = new Response(
    $this->get(Rasterizer::class)->rasterize(
        $this->renderView('Bundle:Folder:template.pdf.twig')
    ),
    200, [
        'Content-Type'          => 'application/pdf',
        'Content-Disposition'   => 'attachment; filename="my.pdf"'
    ]
);
```

[Installation](Resources/docs/configuration_reference.md)

[Configuration reference](Resources/docs/configuration_reference.md)

**Providers**
- [Puppeteer](Resources/docs/provider/puppeteer.md)
- [Other](Resources/docs/provider/other.md)

**How to...**
 - [pass arguments to the javascript file?](Resources/docs/how_to_pass_arguments.md)
 - [fully customize the Symfony Process?](Resources/docs/how_to_customize_the_process.md)



