var page = require('webpage').create(),
    system = require('system'),
    format;

format = system.args[1];

system.stdin.setEncoding('UTF-8'); // force utf8 input encoding even when output is different
var content = system.stdin.read();

page.setContent(content, 'http://localhost');
page.viewportSize = { width: 1920, height: 1080 };
page.paperSize = { format: 'A4', orientation: 'portrait', border: '1cm' };

page.onLoadFinished = function(success) {
    page.render('/dev/stdout', {format: 'pdf'});
    phantom.exit();
};
