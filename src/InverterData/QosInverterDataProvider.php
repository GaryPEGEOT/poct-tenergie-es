<?php

namespace App\InverterData;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class QosInverterDataProvider implements InverterDataProviderInterface
{
    private HttpClientInterface $client;
    private LoggerInterface $logger;

    public function __construct(HttpClientInterface $qosClient, LoggerInterface $logger)
    {
        $this->client = $qosClient;
        $this->logger = $logger;
    }

    public function provide(int $projectId, array $config): \Generator
    {
        $responses = [];
        $dateMax = new \DateTimeImmutable('today');
        $dateMin = $dateMax->modify('-1 day');
        foreach ($config['invertersConfig'] as $inverter) {
            $sensorId = $inverter['E3236'];
            $interval = $inverter['E3275'];
            $page = 1;

            if (empty($sensorId)) {
                continue;
            }
            $responses[] = $this->client->request(
                'GET',
                "/sensors/{$sensorId}/daily_mesures",
                [
                    'query' => compact('page'),
                    'user_data' => [
                        'interval' => $interval,
                        'sensorId' => $sensorId,
                        'inverterId' => $inverter['G572'],
                    ],
                ]
            );
        }

        $this->logger->info('[Inverter][Synchro][QOS] Requesting {count} sensor(s) data', ['count' => \count($responses)]);

        /**
         * @var ResponseInterface $res
         */
        foreach ($this->client->stream($responses) as $res => $chunk) {
            try {
                if ($chunk->isLast()) {
                    ['interval' => $interval, 'sensorId' => $sensorId, 'inverterId' => $inverterId] = $res->getInfo('user_data');
                    $measurements = $res->toArray();
                    $closestDate = null;

                    $this->logger->info('[Inverter][Synchro] Received sensor #{id} data', ['id' => $sensorId]);
                    foreach ($measurements as $measurement) {
                        $closestDate ??= $measurement['date'];
                        foreach ($this->formatData($measurement, $interval) as $date => $pac) {
                            if ((new \DateTime($date, new \DateTimeZone('Europe/Paris'))) > $dateMin) {
                                break;
                            }

                            yield [
                                'projectId' => $projectId,
                                'inverterId' => $inverterId,
                                'datetime' => $date,
                                'pac' => $pac,
                                'pacConsolidate' => null,
                            ];
                        }
                    }
                }
            } catch (TransportExceptionInterface $e) {
                $this->logger->error($e->getMessage());
            }
        }
        $this->logger->info('[Inverter][Synchro][QOS] Synchronisation complete');
    }

    private function formatData(array $measurement, string $interval): array
    {
        $data = [];

        //Verification de l'interval
        if ('10' != $interval && '15' != $interval) {
            throw new \Exception("QOS Interval time is different of 10 and 15 minutes. Interval: {$interval}");
        }

        //Récupération des valeurs
        ['values' => $values, 'date' => $date] = $measurement;
        $values = json_decode($values, true);

        //Verification du nombre de données par rapport à l'interval
        $valuesCount = null !== $values ? \count($values) : 0;

        if ('10' == $interval && !\in_array($valuesCount, [0, 144, 288])) {
            throw new \Exception("QOS Nb values incoherent for interval 10 minutes. 144 expected values. Values received: {$valuesCount}");
        } elseif ('15' == $interval && !\in_array($valuesCount, [0, 96])) {
            throw new \Exception('QOS Nb values incoherent for interval 10 minutes. 96 expected values. Values received: '.$valuesCount);
        }

        //Récupératin de la date de début
        $dateMes = \DateTime::createFromFormat('Y-m-d', $date, new \DateTimeZone('UTC'));
        $dateMes->setTime(0, 0, 0);
        $dateMes->setTimezone(new \DateTimeZone('Europe/Paris'));

        //Affectation des valeurs par date
        if ('10' == $interval && 288 == $valuesCount) { //Cas excepetionnel quand données sur 5 minutes => recalage sur 10 minutes
            $interval = 5;
            $addValue = true;
            foreach ($values as $val) {
                if ($addValue) {
                    $key = $dateMes->format(\DATE_ISO8601);
                    $data[$key] = $val;
                }

                $dateMes->add(new \DateInterval('PT'.$interval.'M'));
                $addValue = !$addValue;
            }
        } elseif (0 == $valuesCount) {
            $nbValue = 144;
            if ('15' == $interval) {
                $nbValue = 96;
            }

            for ($i = 0; $i < $nbValue; ++$i) {
                $key = $dateMes->format(\DATE_ISO8601);
                $data[$key] = null;

                $dateMes->add(new \DateInterval('PT'.$interval.'M'));
            }
        } else {
            foreach ($values as $val) {
                $key = $dateMes->format(\DATE_ISO8601);
                $data[$key] = $val;

                $dateMes->add(new \DateInterval('PT'.$interval.'M'));
            }
        }
        ksort($data);

        return $data;
    }
}
