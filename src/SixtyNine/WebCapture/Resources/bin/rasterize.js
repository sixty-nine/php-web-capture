/*jslint devel: true, browser: true, indent: 4 */
/*global phantom, require */

var Rasterizer = (function () {

    "use strict";

    return {

        render: function (url, outputFile, width, height, viewportWidth, viewportHeight, zoomFactor) {

            var w = width || 1024,
                h = height || 768,
                vpWidth = viewportWidth || w,
                vpHeight = viewportHeight || h,
                zoom = zoomFactor || 1,
                page = require('webpage').create();

            page.viewportSize = { width: vpWidth, height: vpHeight };

            page.open(url, function (status) {

                if (status !== 'success') {
                    console.log('Unable to load the address!');
                    window.setTimeout(function () {
                        phantom.exit();
                    }, 200);
                } else {
                    window.setTimeout(function () {
                        page.clipRect = { top: 0, left: 0, width: w, height: h };
                        page.zoomFactor = zoom;
                        page.render(outputFile);
                        console.log('Snapshot done');
                        phantom.exit();
                    }, 200);
                }

            });
        }

    };

}());


if (phantom.args.length < 2) {
    console.log('Usage: rasterize.js URL filename [ width height viewportWidth viewportHeight zoomFactor ]');
    phantom.exit();
}

Rasterizer.render(
    phantom.args[0],
    phantom.args[1],
    phantom.args[2], phantom.args[3],
    phantom.args[4], phantom.args[5],
    phantom.args[6]
);

