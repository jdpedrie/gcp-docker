<?php

namespace Cli;

use Symfony\Component\Console\Input\InputOption;

trait CommandTrait
{
    private function addOptions()
    {
        $defaults = $this->getDefaults();

        $this->addOption(
            'php',
            'p',
            InputOption::VALUE_OPTIONAL,
            'The PHP version to execute with. i.e. `7.2`',
            $defaults['phpVersion']
        )->addOption(
            'keyfile',
            'k',
            InputOption::VALUE_OPTIONAL,
            'The keyfile name (without .json) to use as default credentials. Relative to $workspaceRoot/keys.',
            $defaults['keyfile']
        )->addOption(
            'withoutGrpc',
            null,
            InputOption::VALUE_NONE
        )->addOption(
            'withoutProtobuf',
            null,
            InputOption::VALUE_NONE
        );
    }

    private function getDefaults()
    {
        static $defaults;

        if (!$defaults) {
            $defaults = json_decode(file_get_contents(__DIR__ . '/../../defaults.json'), true);
        }

        return $defaults;
    }

    private function exec($cmd)
    {
        while (@ob_end_flush()); // end all output buffers if any

        $proc = popen($cmd, 'r');
        while (!feof($proc)) {
            echo fread($proc, 4096);
            @flush();
        }
    }
}
