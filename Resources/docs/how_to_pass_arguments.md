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
    format;

format = system.args[1];
orientation = system.args[2];

system.stdin.setEncoding('UTF-8'); // force utf8 input encoding even when output is different
var content = system.stdin.read();

page.setContent(content, 'http://localhost');
page.viewportSize = { width: 1920, height: 1080 };
page.paperSize = { format: 'A4', orientation: orientation, border: '1cm' };

page.onLoadFinished = function(success) {
    page.render('/dev/stdout', {format: 'pdf'});
    phantom.exit();
};
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
