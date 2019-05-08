<?php

namespace Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Cmd extends Command
{
    use CommandTrait;

    protected function configure()
    {
        $this->setName('cmd')
            ->setDescription('Execute shell script')
            ->addArgument('path', InputArgument::REQUIRED, 'The path of the shell script, relative to the workspace root.');

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
                'docker-compose -f %s run php /gcp/%s',
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
