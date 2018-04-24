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

    // https://github.com/GoogleChrome/puppeteer/issues/728
    // await page.setContent(html);
    // await page.waitForNavigation({ waitUntil: 'networkidle0' });

    await page.goto('data:text/html,' + html, { waitUntil: 'networkidle0' });

    if (format == 'pdf') {
        await page.pdf({
            path: 1, // STDOUT_FILENO
            format: 'A4',
            printBackground: true,
            margin: { top: '1cm', right: '1cm', bottom: '1cm', left: '1cm' }
        });
    } else {
        await page.screenshot({
            path: 1, // STDOUT_FILENO
            type: format,
            quality: 100,
        });
    }

    await browser.close();
})();
```

# Test puppeteer
`echo "test text" | node rasterize.js pdf`

If anything other than a PDF comes out, you might have problems.

A common problem is that chrome is missing. In that case please check out puppeteer issues, eg: https://github.com/GoogleChrome/puppeteer/issues/1602
