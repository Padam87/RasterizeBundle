![CI status](https://github.com/Padam87/RasterizeBundle/actions/workflows/ci.yaml/badge.svg)

[![License](https://poser.pugx.org/padam87/rasterize-bundle/license.png)](https://packagist.org/packages/padam87/rasterize-bundle)
[![Latest Stable Version](https://poser.pugx.org/padam87/rasterize-bundle/v/stable.png)](https://packagist.org/packages/padam87/rasterize-bundle)
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

[Installation](Resources/docs/install.md)

[Configuration reference](Resources/docs/configuration_reference.md)

**Providers**
- [Puppeteer](Resources/docs/provider/puppeteer.md)
- [Other](Resources/docs/provider/other.md)

**How to...**
 - [pass arguments to the javascript file?](Resources/docs/how_to_pass_arguments.md)
 - [fully customize the Symfony Process?](Resources/docs/how_to_customize_the_process.md)



