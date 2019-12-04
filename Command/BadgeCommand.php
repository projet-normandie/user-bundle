<?php

namespace ProjetNormandie\UserBundle\Command;

use ProjetNormandie\CommonBundle\Command\DefaultCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BadgeCommand extends DefaultCommand
{
    protected function configure()
    {
        $this
            ->setName('pn-user:badge')
            ->setDescription('Command to maj badges')
            ->addArgument(
                'function',
                InputArgument::REQUIRED,
                'Who do you want to do?'
            )
            ->addOption(
                'debug',
                null,
                InputOption::VALUE_NONE,
                ''
            )
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init($input);
        $function = $input->getArgument('function');
        switch ($function) {
            case 'maj':
                $this->getContainer()->get('doctrine')->getRepository('ProjetNormandieUserBundle:User')->majBadge();
                break;
        }
        $this->end($output);
        return true;
    }
}
