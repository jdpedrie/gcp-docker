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
            ->addOption('suite', 's', InputOption::VALUE_OPTIONAL, 'Test suite to execute')
            ->addOption('coverage', 'c', InputOption::VALUE_OPTIONAL, 'Whether to create coverage reports.')
            // ->addOption('tverbose', 'v', InputOption::VALUE_NONE, 'Be verbose')
            ->addOption('debug', 'd', InputOption::VALUE_NONE, 'Debug')
            ->addOption('junit', 'j', InputOption::VALUE_NONE, 'Whether to generate junit log');

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

        if ($input->getOption('verbose')) {
            $options[] = '--verbose';
        }

        if ($input->getOption('debug')) {
            $options[] = '--debug';
        }

        if ($input->getOption('junit')) {
            $options[] = sprintf(
                '--log-junit=%s/%s/%s/log_junit.xml',
                $this->getDefaults()['codeRoot'],
                $input->getArgument('path'),
                $this->getDefaults()['coverage']
            );
        }

        if ($input->getOption('coverage')) {
            if ($input->getOption('coverage') === 'clover') {
                $options[] = sprintf(
                    '--coverage-clover=%s/%s/%s/%s',
                    $this->getDefaults()['codeRoot'],
                    $input->getArgument('path'),
                    $this->getDefaults()['coverage'],
                    'clover.xml'
                );
            } else {
                $options[] = sprintf(
                    '--coverage-html=%s/%s/%s',
                    $this->getDefaults()['codeRoot'],
                    $input->getArgument('path'),
                    $this->getDefaults()['coverage']
                );
            }
        } else {
            $options[] = '--no-coverage';
        }

        $cmd = sprintf(
            'run %s',
            sprintf(
                'php /bin/bash -c "cd %s/%s; vendor/bin/phpunit %s"',
                $this->getDefaults()['codeRoot'],
                $input->getArgument('path'),
                implode(' ', $options)
            )
        );

        $output->writeln('[cmd] Running command: ' . $cmd);
        $this->exec($input, $cmd);
    }
}
