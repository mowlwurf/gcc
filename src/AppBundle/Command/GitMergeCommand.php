<?php

namespace AppBundle\Command;


use AppBundle\Service\GitService;
use AppBundle\Service\ReadLineService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GitMergeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('merge')
            ->setDescription('Merges branch into current branch')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $gitService           = new GitService();
        $cliCompletionOptions = $gitService->getBranches();
        $output->writeln(sprintf('Merging branch'));
        $service  = new ReadLineService($cliCompletionOptions);
        $service->setCallback();
        $input = $service->readLine();
        try {
            $gitService->merge($input);
            exit(1);
        } catch (\Exception $e) {
            $output->writeln('Error occurred while merging: ' . $e->getMessage());
            exit(0);
        }
    }
}