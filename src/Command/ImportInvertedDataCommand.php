<?php

namespace App\Command;

use App\Entity\InvertersData;
use Doctrine\ORM\EntityManagerInterface;
use FOS\ElasticaBundle\Persister\ObjectPersisterInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportInvertedDataCommand extends Command
{
    protected static $defaultName = 'app:import-inverted-data';
    protected static $defaultDescription = 'Add a short description for your command';
    private EntityManagerInterface $manager;
    private ObjectPersisterInterface $persister;

    public function __construct(EntityManagerInterface $manager, ObjectPersisterInterface $persister)
    {
        parent::__construct();
        $this->manager = $manager;
        $this->persister = $persister;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('projectId', InputArgument::REQUIRED, 'ProjectId')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $projectId = $input->getArgument('projectId');

        $query = $this->manager->getRepository(InvertersData::class)->createQueryBuilder('i');

        $query->where('i.idProjet = :id')->setParameter('id', $projectId);
        $io->progressStart();

        foreach ($query->getQuery()->toIterable() as [$item]) {
            $this->persister->insertOne($item);
            $this->manager->detach($item);
            $io->progressAdvance();
        }
        $io->progressFinish();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
