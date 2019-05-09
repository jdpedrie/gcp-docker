<?php

namespace Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Test extends Command
{
    use CommandTrait;

    protected function configure()
    {
        $this->setName('test')
            ->setDescription('Run PHPUnit tests')
            ->addArgument('path', InputArgument::REQUIRED, 'The path of the project, relative to the workspace root.')
            ->addOption('group', 'g', InputOption::VALUE_OPTIONAL, 'Test group to execute')
            ->addOption('suite', 's', InputOption::VALUE_OPTIONAL, 'Test suite to execute');

        $this->addOptions();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $options = [];
        if ($input->getOption('group')) {
            $options[] = '--group=' . $input->getOption('group');
        }

        if ($input->getOption('suite')) {
            $options[] = '-c phpunit-' . $input->getOption('suite') . '.xml.dist';
        }

        $cmd = sprintf(
            'run %s',
            sprintf(
                'php /bin/bash -c "cd /gcp/%s; vendor/bin/phpunit %s"',
                $input->getArgument('path'),
                implode(' ', $options)
            )
        );

        $this->exec($input, $cmd);
    }
}
