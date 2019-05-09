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
        $cmd = sprintf(
            'run php php /gcp/%s',
            $input->getArgument('path')
        );

        $this->exec($input, $cmd);
    }
}
