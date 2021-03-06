<?php

namespace Cli;

use Symfony\Component\Console\Command\Command;
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
            ->addOption('no-cache', null, InputOption::VALUE_NONE, 'Provide flag to rebuild without cache.');

        $this->addOptions();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cmd = sprintf(
            'build %s',
            $input->getOption('no-cache') ? '--no-cache' : ''
        );

        $this->exec($input, $cmd);
    }
}
