var page = require('webpage').create(),
    system = require('system'),
    address, output, format;

address = system.args[1];
output = system.args[2];
format = system.args[3];

page.viewportSize = { width: 1000, height: 3000 };
page.paperSize = { format: 'A4', orientation: 'portrait', border: '1cm' };

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