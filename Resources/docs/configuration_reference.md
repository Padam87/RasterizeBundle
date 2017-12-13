## Configuration reference ##

*NOTE: No configuration necessary by default*

```YAML
padam87_rasterize:
    script:
        callable: node
        path: web\rasterize.js # Relative to project dir
    arguments:
        format: pdf # Default, will always be added, even if you remove it from here.
```
