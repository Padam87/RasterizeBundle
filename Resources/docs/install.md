## Installation ##

### Composer ###

`composer require padam87/rasterize-bundle`

### AppKernel ###

```php
$bundles = array(
    ...
    new Padam87\RasterizeBundle\Padam87RasterizeBundle(),
);
```

### Chose a provider ###

- [Puppeteer](provider/puppeteer.md)
- [PhantomJS](provider/phantomjs.md)
- [Other](provider/other.md)
