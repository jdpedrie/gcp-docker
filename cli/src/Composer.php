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
        $cmd = sprintf(
            'run php %s',
            sprintf(
                '/bin/bash -c "cd /gcp/%s; composer %s"',
                $input->getArgument('path'),
                $input->getArgument('cmd')
            )
        );

        $this->exec($input, $cmd);
    }
}
