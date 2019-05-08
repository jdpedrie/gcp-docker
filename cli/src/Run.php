<?php

namespace Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Run extends Command
{
    use CommandTrait;

    protected function configure()
    {
        $this->setName('run')
            ->setDescription('Run a file in Docker')
            ->addArgument('path', InputArgument::REQUIRED, 'The path of the file, relative to the workspace root.');

        $this->addOptions();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $defaults = $this->getDefaults();

        $cmd = sprintf(
            implode(" \\\n", [
                'KEY="%s"',
                'PHP_VERSION="%s"',
                'GRPC="%s"',
                'PROTOBUF="%s"',
                'docker-compose -f %s run',
                'php php /gcp/%s'
            ]),
            $input->getOption('keyfile'),
            $input->getOption('php'),
            $input->getOption('withoutGrpc') ? 'disabled': 'enabled',
            $input->getOption('withoutProtobuf') ? 'disabled': 'enabled',
            $defaults['composeFile'],
            $input->getArgument('path')
        );

        $this->exec($cmd);
    }
}
