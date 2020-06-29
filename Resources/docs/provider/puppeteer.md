# Add puppeteer

`yarn add puppeteer` or `npm i puppeteer`

# Create a script
- **Symfony 4:** assets/rasterize.js
- **Symfony 3:** web/rasterize.js

```js
const puppeteer = require('puppeteer');
const fs = require('fs');

let stdinBuffer = fs.readFileSync(0 /* STDIN_FILENO */, 'utf8');
let html = stdinBuffer.toString();

const args = process.argv;
const format = args[2];

(async () => {
    const browser = await puppeteer.launch({
        ignoreHTTPSErrors: true
    });
    const page = await browser.newPage();

    await page.setContent(html, { waitUntil: 'networkidle0' });

    let buff = null;

    if (format == 'pdf') {
        buff = await page.pdf({
            format: 'A4',
            printBackground: true,
            margin: { top: '1cm', right: '1cm', bottom: '1cm', left: '1cm' }
        });
    } else {
        buff = await page.screenshot({
            type: format,
            quality: 100,
        });
    }

    process.stdout.write(buff);

    await browser.close();
})();
```

# Test puppeteer
`echo "test text" | node rasterize.js pdf`

If anything other than a PDF comes out, you might have problems.

A common problem is that chrome is missing. In that case please check out puppeteer issues, eg: https://github.com/GoogleChrome/puppeteer/issues/1602
