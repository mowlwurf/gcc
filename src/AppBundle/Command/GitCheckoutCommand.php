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
            ->setDescription('Checkout branch or tag with auto-completion. Checking out commit hash is also possible.')
            ->addOption('tags','t',InputOption::VALUE_NONE, 'checkout version tag')
            ->addOption('hash', 'c', InputOption::VALUE_NONE, 'checkout version hash')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $gitService = new GitService();
        if ($input->getOption('hash')) {
            try {
                $gitService->checkout($input);
                exit(1);
            } catch (\Exception $e) {
                $output->writeln('Error occurred while checkout: ' . $e->getMessage());
                exit(0);
            }
        } elseif ($input->getOption('tags')) {
            $cliCompletionOptions = $gitService->getTags();
            $versionType          = 'tag';
            if (empty($cliCompletionOptions)) {
                $output->writeln('There seems to be no tags. Is this a git repository?');
                exit(0);
            }
        } else {
            $cliCompletionOptions = $gitService->getBranches();
            $versionType          = 'branch';
            if (empty($cliCompletionOptions)) {
                $output->writeln('There seems to be no branch. Is this a git repository?');
                exit(0);
            }
        }
        $output->writeln(sprintf('Checking out %s', $versionType));
        $service  = new ReadLineService($cliCompletionOptions);
        $service->setCallback();
        $input = $service->readLine();
        try {
            $gitService->checkout($input);
            exit(1);
        } catch (\Exception $e) {
            $output->writeln('Error occurred while checkout: ' . $e->getMessage());
            exit(0);
        }
    }
}