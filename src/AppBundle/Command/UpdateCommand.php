<?php
/**
 * Created by PhpStorm.
 * User: dnaumann
 * Date: 15.03.16
 * Time: 20:44
 */

namespace AppBundle\Command;

use Herrera\Phar\Update\Manager;
use Herrera\Phar\Update\Manifest;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class UpdateCommand extends ContainerAwareCommand
{
    const MANIFEST_FILE = 'http://mowlwurf.github.io/gcc/manifest.json';

    protected function configure()
    {
        $this
            ->setName('update')
            ->setDescription('Updates gcc.phar to the latest version')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = new Manager(Manifest::loadFile(self::MANIFEST_FILE));
        $manager->update($this->getApplication()->getVersion(), true);
    }
}