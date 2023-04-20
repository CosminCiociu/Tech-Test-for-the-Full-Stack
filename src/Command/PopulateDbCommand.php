<?php

namespace App\Command;

use App\Entity\Order;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommand(
    name: 'populate-db',
    description: 'Add a short description for your command',
)]
class PopulateDbCommand extends Command
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Populate table with orders.json data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $finder = new Finder();
        $jsonContent = [];

        $finder->in('migrations/seedscustom')->name('orders.json');

        if($finder->hasResults()){
            foreach ($finder as $file) {
                $jsonContent = json_decode($file->getContents());
            }
        }
        if(!empty($jsonContent)){
            foreach($jsonContent as $row) {
                $order = new Order();
                $order->setId($row->id);
                $order->setDate($this->formatDate($row->date));
                $order->setCustomer($row->customer);
                $order->setAddress1($row->address1);
                $order->setCity($row->city);
                $order->setPostcode($row->postcode);
                $order->setCountry($row->country);
                $order->setAmount($row->amount);
                $order->setStatus($row->status);
                $order->setDeleted($row->deleted);
                $order->setLastModified($this->formatDate($row->last_modified));
                
                $this->entityManager->persist($order);
            }
        }

        $this->entityManager->flush();

        $output->writeln('Rows created successfully');

        return Command::SUCCESS;
    }

    private function formatDate($stringDate): ?\DateTimeInterface 
    {
        $ymd = DateTime::createFromFormat('Y-m-d H:i:s', $stringDate);
        return $ymd;
    }
}
