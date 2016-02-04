# How to pass arguments to the javascript file

In this example, we will use arguments to programmatically set the orientation of the PDF.

First, the configuration should be changed

```yaml
padam87_rasterize:
    script:               /js/my-rasterize.js
    arguments:
        format: pdf
        orientation: portrait
```

Note that the orientation argument has been added, by default it will be set as `portrait`.

A custom javascript is also necessary, to handle the newly received argument.

```js
var page = require('webpage').create(),
    system = require('system'),
    address, output, format, orientation;

address = system.args[1];
output = system.args[2];
format = system.args[3];
orientation = system.args[4];

page.viewportSize = { width: 1000, height: 3000 };
page.paperSize = { format: 'A4', orientation: orientation, border: '1cm' };

page.open(address, function (status) {
    if (status !== 'success') {
        console.log('Unable to load the address!');
        phantom.exit(1);
    } else {
        window.setTimeout(function () {
            page.render(output, { format: format });
            phantom.exit(0);
        }, 200);
    }
});
```

To change the orientation to `landscape`, you need to add one more parameter to the rasterizer call.

```php
$this->get('padam87_rasterize.rasterizer')->rasterize(
    $this->renderView('Bundle:Folder:template.pdf.twig')
    [
        'orientation' => 'landscape'
    ]
);
```
