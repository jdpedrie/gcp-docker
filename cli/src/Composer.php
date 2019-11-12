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
            ->addArgument('cmd', InputArgument::REQUIRED, 'The composer argument')
            ->addOption('no-dev', null, InputOption::VALUE_NONE, 'If set, run install/update with --no-dev')
            ->addOption('ignore-platform-reqs', null, InputOption::VALUE_NONE, 'If set, run with --ignore-platform-reqs');

        $this->addOptions();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $args = $input->getArgument('cmd');
        if ($input->getOption('no-dev')) {
            $args .= ' --no-dev';
        }

        if ($input->getOption('ignore-platform-reqs')) {
            $args .= ' --ignore-platform-reqs';
        }

        $cmd = sprintf(
            'run php %s',
            sprintf(
                '/bin/bash -c "cd %s/%s; composer %s"',
                $this->getDefaults()['codeRoot'],
                $input->getArgument('path'),
                $args
            )
        );

        $this->exec($input, $cmd);
    }
}
