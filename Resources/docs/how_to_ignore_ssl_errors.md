# How to ignore SSL errors

Phantomjs, by default does not play nice with Self-signed certificates.
If you are working with one, you need to ignore ssl errors.

```yaml
padam87_rasterize:
    phantomjs:
        options:
            --ssl-protocol: tlsv1
            --ignore-ssl-errors: true
```
