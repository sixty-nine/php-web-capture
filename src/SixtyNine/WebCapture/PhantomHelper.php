<?php

namespace SixtyNine\WebCapture;

use Symfony\Component\Process\Process;

/**
 * PhantomJS wrapper class
 */
class PhantomHelper
{
    /**
     * The path of the PhantomJS binary
     * @var string
     */
    protected $phantomjsBin;

    /**
     * @param $phantomjsBin
     * @throws \InvalidArgumentException
     */
    public function __construct($phantomjsBin)
    {
        if (!file_exists($phantomjsBin)) {
            throw new \InvalidArgumentException("PhantomJS binary not found in '$phantomjsBin'.");
        }

        $this->phantomjsBin = $phantomjsBin;
    }

    /**
     * Execute a PhantomJS script
     * @param string $script The path to the script
     * @param array $args The arguments to pass to the script
     * @param bool $debug
     * @throws \InvalidArgumentException
     * @return string
     */
    public function exec($script, $args = array(), $debug = false)
    {
        if (!file_exists($script)) {
            throw new \InvalidArgumentException("PhantomJS script '$script' not found.");
        }

        $command = sprintf(
            "%s %s %s 2>&1",
            $this->phantomjsBin,
            $script,
            implode(' ', $args)
        );

        $process = new Process($command);

        if ($debug) {
            $process->run(function ($type, $buffer) {
                if (Process::ERR === $type) {
                    echo 'ERR > '.$buffer;
                } else {
                    echo 'OUT > '.$buffer;
                }
            });
        } else {
            $process->run();
        }

        return $process->getOutput();
    }
}
