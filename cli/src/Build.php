<?php

namespace Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Build extends Command
{
    use CommandTrait;

    protected function configure()
    {
        $this->setName('build')
            ->setDescription('Rebuild a container')
            ->addOption('withoutCache', null, InputOption::VALUE_NONE, 'Provide flag to rebuild without cache.');

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
                'docker-compose -f %s build %s',
            ]),
            $input->getOption('keyfile'),
            $input->getOption('php'),
            $input->getOption('withoutGrpc') ? 'disabled': 'enabled',
            $input->getOption('withoutProtobuf') ? 'disabled': 'enabled',
            $defaults['composeFile'],
            $input->getOption('withoutCache') ? '--no-cache': ''
        );

        $this->exec($cmd);
    }
}
