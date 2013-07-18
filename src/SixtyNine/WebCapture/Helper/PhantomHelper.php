<?php

namespace SixtyNine\WebCapture\Helper;

/**
 * PhantomJS wrapper class
 */
class PhantomHelper
{
    /**
     * The path of the PhantomJS binary
     * @var string
     */
    protected $phantomjs_bin;

    public function __construct($phantomjs_binary)
    {
        if (!file_exists($phantomjs_binary)) {
            throw new \InvalidArgumentException("PhantomJS binary not found in '$phantomjs_binary'.");
        }

        $this->phantomjs_bin = $phantomjs_binary;
    }

    /**
     * Execute a PhantomJS script
     * @param string $script The path to the script
     * @param array $args The arguments to pass to the script
     * @throws \InvalidArgumentException
     * @return string
     */
    public function exec($script, $args = array())
    {
        if (!file_exists($script)) {
            throw new \InvalidArgumentException("PhantomJS script '$script' not found.");
        }

        $command = sprintf(
            "%s %s %s 2>&1",
            $this->phantomjs_bin,
            $script,
            implode(' ', $args)
        );

        $output = shell_exec($command);

        return $output;
    }
}
