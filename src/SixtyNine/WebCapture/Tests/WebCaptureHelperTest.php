<?php

namespace SixtyNine\WebCapture\Tests;

use SixtyNine\WebCapture\PhantomHelper;
use SixtyNine\WebCapture\WebCaptureHelper;
use PHPUnit\Framework\TestCase;

class WebCaptureHelperTest extends TestCase
{
    /** @var SixtyNine\WebCapture\WebCaptureHelper */
    protected $helper;

    /** @var string */
    protected $outputFile = '/tmp/output.jpg';

    /** @var string */
    protected $testHtml;

    public function setUp()
    {
        global $phantomJsPath;

        $this->testHtml = sprintf('file://%s/Fixtures/test.html', __DIR__);

        $this->helper = new WebCaptureHelper(new PhantomHelper($phantomJsPath));

        if (file_exists($this->outputFile)) {
            unlink($this->outputFile);
        }
    }

    public function tearDown()
    {
        if (file_exists($this->outputFile)) {
            unlink($this->outputFile);
        }
    }

    /** @expectedException \InvalidArgumentException */
    public function testRasterizeInvalidFormat()
    {
        $this->helper->rasterize('http://google.ch', '/tmp/output.bmp');
    }

    public function testRasterize()
    {

        foreach (array('png', 'jpeg') as $ext) {

            $output = '/tmp/output.' . $ext;

            if (file_exists($output)) {
                unlink($output);
            }

            $this->helper->rasterize('http://google.ch', $output, 123, 456);

            $this->assertTrue(file_exists($output));

            $size = getimagesize($output);
            $this->assertTrue(is_array($size));
            $this->assertEquals(123, $size[0]);
            $this->assertEquals(456, $size[1]);
            $this->assertEquals('image/' . $ext, $size['mime']);

            unlink($output);
        }
    }

    public function testRasterizeDefaultValues()
    {
        $this->helper->rasterize($this->testHtml, $this->outputFile);

        $this->assertTrue(file_exists($this->outputFile));

        $size = getimagesize($this->outputFile);
        $this->assertTrue(is_array($size));
        $this->assertEquals(1024, $size[0]);
        $this->assertEquals(768, $size[1]);
        $this->assertEquals('image/jpeg', $size['mime']);

        $this->assertSameImage(__DIR__ . '/Fixtures/test-1024x768-1024x768-1.jpg', $this->outputFile);
    }

    /**
    * @dataProvider dataProvider
    */
    public function testRasterizeSize($width, $height, $viewportWidth, $viewportHeight, $zoom, $expectedImage)
    {
        $this->helper->rasterize($this->testHtml, $this->outputFile, $width, $height, $viewportWidth, $viewportHeight, $zoom);

        $this->assertTrue(file_exists($this->outputFile));

        if (filesize($expectedImage) !== filesize($this->outputFile)) {
            $this->assertTrue(false, sprintf('File sizes do not match: %s - %s', $this->outputFile, $expectedImage));
        }

        $this->assertSameImage($expectedImage, $this->outputFile);
    }

    public function dataProvider()
    {
        return array(
            array(1024, 768, 1024, 768, 1, __DIR__ . '/Fixtures/test-1024x768-1024x768-1.jpg'),
            array(1024, 768, 1024, 768, 2, __DIR__ . '/Fixtures/test-1024x768-1024x768-2.jpg'),
            array(800, 600, 800, 600, 1, __DIR__ . '/Fixtures/test-800x600-800x600-1.jpg'),
            array(1024, 768, 800, 600, 1, __DIR__ . '/Fixtures/test-1024x768-800x600-1.jpg'),
            array(800, 600, 1024, 768, 1, __DIR__ . '/Fixtures/test-800x600-1024x768-1.jpg')
        );
    }

    protected function assertSameImage($expectedImage, $result)
    {
        // First compare the file sizes to avoid phpunit to display huge diffs if they differ
        $this->assertEquals(
            filesize($expectedImage),
            filesize($result)
        );

        $this->assertEquals(
            file_get_contents($expectedImage),
            file_get_contents($result)
        );
    }
}
