# How to fully customize the process?

The rasterize methods accepts a callback as the 4th parameter.

```php
$response = new Response(
    $this->get('padam87_rasterize.rasterizer')->rasterize(
        $this->renderView('Bundle:Folder:template.pdf.twig'),
        [],
        [],
        function (Process $process) {
            // HERE
        }
    ),
    200, [
        'Content-Type'          => 'application/pdf',
        'Content-Disposition'   => 'attachment; filename="my.pdf"'
    ]
);
```
