<?php

namespace Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
        $cmd = sprintf(
            'run php %s/%s',
            $this->getDefaults()['codeRoot'],
            $input->getArgument('path')
        );

        $this->exec($input, $cmd);
    }
}
