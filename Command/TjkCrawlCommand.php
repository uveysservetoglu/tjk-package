<?php

namespace AppBundle\Command;

use AppBundle\AppBundle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TjkCrawlCommand extends ContainerAwareCommand
{
    protected function configure(){

        $this
            ->setName('crawl:tjk')
            ->setDescription('...')
            ->addOption(
                'type',
                't',
                InputOption::VALUE_OPTIONAL,
                'Specify which operation type to be crawled',
                'Bursa'
            );
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $type = $input->getOption('type');
        $class = '\\AppBundle\\Crawlers\\RaceSchedule';
        $crawler = new $class($this->getContainer()->get("kernel"));
        $crawler->crawl($type);

        $output->writeln('Command result.');
    }

}
