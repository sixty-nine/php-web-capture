<?php

namespace SixtyNine\WebCapture\Helper;

class WebCaptureHelper
{
    const SUPPORTED_TYPE_GIF = 'gif';
    const SUPPORTED_TYPE_JPG = 'jpg';
    const SUPPORTED_TYPE_PNG = 'png';

    /**
     * Supported image types
     * @var array
     */
    protected $supportedTypes = array(
        self::SUPPORTED_TYPE_PNG,
        self::SUPPORTED_TYPE_JPG,
        self::SUPPORTED_TYPE_GIF,
    );

    /**@var \SixtyNine\WebCapture\Helper\PhantomHelper */
    protected $phantomHelper;

    /** @var string */
    protected $format;

    /**
     * @param PhantomHelper $phantomHelper
     */
    public function __construct(PhantomHelper $phantomHelper)
    {
        $this->phantomHelper = $phantomHelper;
        $this->rasterizeScript = __DIR__ . '/../Resources/bin/rasterize.js';
    }

    /**
     * Render a screenshot of the given URL in PNG format and returns the content of the image
     * @param string $url The URL to render
     * @param string $outputFile
     * @param int $width
     * @param int $height
     * @throws \InvalidArgumentException
     * @throws \Exception
     * @return string The file content of the rendered image
     */
    public function rasterize($url, $outputFile, $width = 1024, $height = 768, $viewportWidth = 1024, $viewportHeight = 768, $zoomFactor = 1)
    {
        if (!$this->checkOutputFormat($outputFile)) {
            throw new \InvalidArgumentException(sprintf("Unsupported image type '%s'", pathinfo($outputFile, PATHINFO_EXTENSION)));
        }

        // TODO: handle redirects in rasterize script
        $out = $this->phantomHelper->exec(
            $this->rasterizeScript,
            array($url, $outputFile, $width, $height, $viewportWidth, $viewportHeight, $zoomFactor)
        );

        // Check if the output file now exists, if it doesn't, then something went wrong with the rasterize script
        // Maybe the URL does not exist or it is empty
        if (!file_exists($outputFile)) {
            throw new \Exception("PhantomHelper could not create a screenshot of '$url': " . print_r($out, true));
        }
    }

    protected function checkOutputFormat($outputFile)
    {
        $ext = pathinfo($outputFile, PATHINFO_EXTENSION);
        return ($ext === 'jpeg') || in_array($ext, $this->supportedTypes);
    }
}
