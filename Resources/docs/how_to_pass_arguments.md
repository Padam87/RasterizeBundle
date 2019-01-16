# How to pass arguments to the javascript file

In this example, we will use arguments to programmatically set the orientation of the PDF.

First, the configuration should be changed

```yaml
padam87_rasterize:
    arguments:
        orientation: portrait
```

Note that the orientation argument has been added, by default it will be set as `portrait`.

A custom javascript is also necessary, to handle the newly received argument.

```js
// ...
const args = process.argv;
const format = args[2];
const orientation = args[3];
// ...
```

To change the orientation to `landscape`, you need to add one more parameter to the rasterizer call.

```php
$this->get(Rasterizer::class)->rasterize(
    $this->renderView('Bundle:Folder:template.pdf.twig')
    [
        'orientation' => 'landscape'
    ]
);
```
