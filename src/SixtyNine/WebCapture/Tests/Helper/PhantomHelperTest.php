<?php

namespace SixtyNine\WebCapture\Tests\Helper;

use SixtyNine\WebCapture\Helper\PhantomHelper;

class PhantomHelperTest extends \PHPUnit_Framework_TestCase
{
    /** @var SixtyNine\WebCapture\Helper\PhantomHelper */
    protected $helper;

    public function setUp()
    {
        global $phantomJsPath;

        $this->helper = new PhantomHelper($phantomJsPath);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUnexistingPhantomBin()
    {
        new PhantomHelper('/unexisting-phantom-bin');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUnexistingScriptFile()
    {
        $this->helper->exec('/unexisting-script');
    }

    public function testExec()
    {
        $out = $this->helper->exec(__DIR__ . '/../Fixtures/dummy.js');
        $this->assertEquals("Hello Phantom world\n", $out);
    }
}
