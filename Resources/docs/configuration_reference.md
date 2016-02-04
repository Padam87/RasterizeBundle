## Configuration reference ##

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
