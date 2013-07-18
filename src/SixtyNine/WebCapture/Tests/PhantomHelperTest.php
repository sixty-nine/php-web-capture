<?php

namespace SixtyNine\WebCapture\Tests;

use SixtyNine\WebCapture\PhantomHelper;
use PHPUnit\Framework\TestCase;

class PhantomHelperTest extends TestCase
{
    /** @var SixtyNine\WebCapture\Helper\PhantomHelper */
    protected $helper;

    public function setUp()
    {
        global $phantomJsPath;

        $this->helper = new PhantomHelper($phantomJsPath);
    }

    /** @expectedException \InvalidArgumentException */
    public function testUnexistingPhantomBin()
    {
        new PhantomHelper('/unexisting-phantom-bin');
    }

    /** @expectedException \InvalidArgumentException */
    public function testUnexistingScriptFile()
    {
        $this->helper->exec('/unexisting-script');
    }

    public function testExec()
    {
        $out = $this->helper->exec(__DIR__ . '/Fixtures/dummy.js');
        $this->assertEquals("Hello Phantom world\n", $out);
    }
}
