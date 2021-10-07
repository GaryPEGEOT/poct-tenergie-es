<?php

namespace App\MessageHandler;

use App\Amanda\InverterConfigFetcher;
use App\InverterData\InverterDataProviderInterface;
use App\Message\ImportPacData;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImportPacDataHandler implements MessageHandlerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private InverterConfigFetcher $inverterConfigFetcher;
    private HttpClientInterface $client;
    private InverterDataProviderInterface $dataProvider;
    private const BATCH_SIZE = 10000;

    public function __construct(InverterConfigFetcher $inverterConfigFetcher, HttpClientInterface $elasticsearchClient, InverterDataProviderInterface $dataProvider)
    {
        $this->inverterConfigFetcher = $inverterConfigFetcher;
        $this->client = $elasticsearchClient;
        $this->dataProvider = $dataProvider;
        $this->logger = new NullLogger();
    }

    public function __invoke(ImportPacData $message): void
    {
        $projectId = $message->getProjectId();
        $ctx = ['id' => $projectId, 'type' => 'pac_syncho'];
        $this->logger->info('[Inverter][Synchro] Starting synchro of project #{id}', $ctx);

        $config = $this->inverterConfigFetcher->fetch($projectId);
        $batch = [];

        foreach ($this->dataProvider->provide($projectId, $config) as $inverterData) {
            $batch[] = $inverterData;

            if (\count($batch) >= self::BATCH_SIZE) {
                $this->sendBatch($batch);
                $batch = [];
            }
        }
        $this->sendBatch($batch);
        $this->logger->info('[Inverter][Synchro] Finished synchro of project #{id}', $ctx);
    }

    private function sendBatch(array $batch): void
    {
        $count = \count($batch);

        if (!$count) {
            return;
        }

        $body = [];
        foreach ($batch as $line) {
            $id = uuid_create(UUID_TYPE_DEFAULT);
            $line['id'] = $id;
            $body[] = json_encode(['index' => ['_id' => $id, '_index' => 'inverters_data']]);
            $body[] = json_encode($line);
        }

        $this->logger->info('[Inverter][Synchro] Sending {count} new inverters data', compact('count'));
        $response = $this->client->request('POST', '/_bulk', [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => implode(\PHP_EOL, $body).\PHP_EOL,
        ]);

        $code = $response->getStatusCode();

        if ($code > 299) {
            $json = $response->getContent(false);
            $this->logger->error('[Inverter][Synchro] Request failed: {message}', ['message' => $json]);

            throw new \RuntimeException("Unable to send inverter data:\n$json");
        }
        $this->logger->info('[Inverter][Synchro] Request done');
    }
}
