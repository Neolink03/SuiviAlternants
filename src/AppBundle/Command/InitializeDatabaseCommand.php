<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitializeDatabaseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:database:initialize')
            ->setDescription('Create database, tables and load fixtures')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commands = array(
            'doctrine:database:create' =>   array('command' => 'doctrine:database:create'),
            'doctrine:schema:update' =>   array('command' => 'doctrine:schema:update', '--force'  => true),
            'doctrine:fixtures:load' =>   array('command' => 'doctrine:fixtures:load')
        );

        foreach ($commands as $commandName => $params ){
            $command = $this->getApplication()->find($commandName);
            $arrayInput = new ArrayInput($params);
            $command->run($arrayInput, $output);
        }
    }
}