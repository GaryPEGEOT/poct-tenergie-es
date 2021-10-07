<?php

namespace App\Command;

use App\Amanda\InverterConfigFetcher;
use App\Message\ImportPacData;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

class CronInitImportPacDataCommand extends Command
{
    protected static $defaultName = 'app:cron:init-import-pac-data';
    protected static $defaultDescription = 'Trigger PAC Synchro';
    private MessageBusInterface $bus;
    private InverterConfigFetcher $configFetcher;

    public function __construct(MessageBusInterface $bus, InverterConfigFetcher $configFetcher)
    {
        parent::__construct();
        $this->bus = $bus;
        $this->configFetcher = $configFetcher;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('projectId', InputArgument::OPTIONAL, 'Synchronize only one project')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        if (null !== $projectId = $input->getArgument('projectId')) {
            $this->bus->dispatch(new ImportPacData($projectId));
            $io->success("Triggered synchronization for project #{$projectId}");

            return 0;
        }

        $projectIds = $this->configFetcher->getProjectIds();
        $io->progressStart(\count($projectIds));
        foreach ($projectIds as $id) {
            $this->bus->dispatch(new ImportPacData($id));
            $io->progressAdvance();
        }
        $io->progressFinish();

        $io->success('Done');

        return Command::SUCCESS;
    }
}
