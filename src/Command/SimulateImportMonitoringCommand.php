<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SimulateImportMonitoringCommand extends Command
{
    private const LIMIT = 400000;
    protected static $defaultName = 'app:simulate-import-monitoring';
    protected static $defaultDescription = 'Add a short description for your command';
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('batchSize', InputArgument::OPTIONAL, 'Import batch size', 10000)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $batchSize = $input->getArgument('batchSize');
        $page = self::LIMIT / $batchSize;
        $now = new \DateTime('last week');
        $id = 1;
        $responses = [];

        while ($page > 0) {
            $body = '';
            for ($i = 0; $i < $batchSize; ++$i) {
                ++$id;
                $date = $now->modify('+1 hour')->format(\DATE_ISO8601);
                $body .= <<<EOL
                    {"index":{"_id":"$id","_index":"inverters_data"}}
                    {"id":$id,"idProjet":3,"datetime":"$date","inverterId":1868,"pac":12648,"pacConsolidate":null}

                    EOL;
            }
            $responses[] = $this->client->request('POST', 'http://localhost:9200/_bulk', [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => $body,
            ]);
            --$page;
        }

        $stopwatch = new Stopwatch();
        $stopwatch->start('ev');
        $io->progressStart(\count($responses));
        foreach ($this->client->stream($responses) as $chunk) {
            if ($chunk->isLast()) {
                $io->progressAdvance();
            }
        }
        $io->progressFinish();

        $io->success(sprintf('Done. results: %s', $stopwatch->stop('ev')));

        return Command::SUCCESS;
    }
}
