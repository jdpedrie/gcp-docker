<?php

namespace Cli;

use Symfony\Component\Console\Input\InputInterface;
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
        )->addOption(
            'extension',
            'e',
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'PECL extensions to install.'
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

    private function sharedEnvVars(InputInterface $input)
    {
        $extensions = $input->getOption('extension');
        $extensions = is_array($extensions)
            ? implode(' ', $extensions)
            : $extensions;

        $imageId = md5(json_encode($input->getOptions()));

        return sprintf(
            implode(" \\\n", [
                'KEY="%s"',
                'PHP_VERSION="%s"',
                'GRPC="%s"',
                'PROTOBUF="%s"',
                'EXTENSIONS="%s"',
                'IMAGE_ID="%s"',
            ]),
            $input->getOption('keyfile'),
            $input->getOption('php'),
            $input->getOption('withoutGrpc') ? 'disabled' : 'enabled',
            $input->getOption('withoutProtobuf') ? 'disabled' : 'enabled',
            $extensions,
            $imageId
        );
    }

    private function exec(InputInterface $input, $cmd)
    {
        $vars = $this->sharedEnvVars($input);
        $compose = sprintf('docker-compose -f %s', $this->getDefaults()['composeFile']);
        $cmd = $vars . ' \\' . PHP_EOL . $compose . ' ' . $cmd;

        while (@ob_end_flush()); // end all output buffers if any

        $proc = popen($cmd, 'r');
        while (!feof($proc)) {
            echo fread($proc, 4096);
            @flush();
        }
    }
}
