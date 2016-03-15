<?php

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Service\ReadLineService;
use AppBundle\Service\GitService;

class GitCheckoutCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('checkout')
            ->setDescription('Checkout branch with autocompletion')
            ->addOption('tags','t',InputOption::VALUE_NONE)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Choose Branch to checkout");
        $git      = new GitService();
        $branches = $git->getBranches();
        if (empty($branches)) {
            $output->writeln('There seems to be no branch. Is this a git repository?');
            exit(0);
        }
        $service  = new ReadLineService($branches);
        $input    = $service->readLine();
        $output->writeln("Checking out: $input");
        $git->checkout($input);
    }
}