<?php

namespace Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Composer extends Command
{
    use CommandTrait;

    protected function configure()
    {
        $this->setName('composer')
            ->setDescription('Run Composer in Docker')
            ->addArgument('path', InputArgument::REQUIRED, 'The path of the project, relative to the workspace root.')
            ->addArgument('cmd', InputArgument::REQUIRED, 'The composer argument');

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
                'docker-compose -f %s run php %s',
            ]),
            $input->getOption('keyfile'),
            $input->getOption('php'),
            $input->getOption('withoutGrpc') ? 'disabled': 'enabled',
            $input->getOption('withoutProtobuf') ? 'disabled': 'enabled',
            $defaults['composeFile'],
            sprintf(
                '/bin/bash -c "cd /gcp/%s; composer %s"',
                $input->getArgument('path'),
                $input->getArgument('cmd')
            )
        );

        // echo $cmd;exit;

        $this->exec($cmd);
    }
}
