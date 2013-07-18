/*jslint devel: true, browser: true, indent: 4 */
/*global phantom, require */

(function (phantom, system) {

    'use strict';

    if (system.args.length < 2) {
        console.log('Usage: rasterize.js URL filename [ width height viewportWidth viewportHeight zoomFactor ]');
        phantom.exit();
    }

    var url = system.args[1];
    var outputFile = system.args[2];
    var width = system.args[3] || 1024;
    var height = system.args[4] || 768;
    var viewportWidth = system.args[5] || 1024;
    var viewportHeight = system.args[6] || 768;
    var zoomFactor = system.args[7] || 1;

    console.log('URL:', url);
    console.log('Output size:', width + 'x' + height);
    console.log('Viewport size:', viewportWidth + 'x' + viewportHeight);
    console.log('Zoom:', zoomFactor);

    var page = require('webpage').create();

    page.viewportSize = { width: viewportWidth, height: viewportHeight };

    page.open(url, function (status) {

        if (status !== 'success') {
            console.log('Unable to load the address!');
            window.setTimeout(function () {
                phantom.exit();
            }, 200);
        } else {
            window.setTimeout(function () {
                page.clipRect = { top: 0, left: 0, width: width, height: height };
                page.zoomFactor = zoomFactor;
                page.render(outputFile);
                console.log('Snapshot done', outputFile);
                phantom.exit();
            }, 200);
        }

    });

}(phantom, require('system')));


