## Configuration reference ##

*NOTE: No configuration necessary by default*

```YAML
padam87_rasterize:
    phantomjs:
        callable:             phantomjs
        options:  # http://phantomjs.org/api/command-line.html
            '--output-encoding': 'UTF-8' # will default to ISO-8859-1 on windows
    script:               /bundles/padam87rasterize/js/rasterize.js # Relative to web dir
    arguments: # You can define your own custom arguments. Will be added by default to every process.
        format:              pdf # Default, will always be added, even if you remove it from here.
```
