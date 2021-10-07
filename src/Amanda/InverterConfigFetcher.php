<?php

namespace App\Amanda;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class InverterConfigFetcher
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $invertersConfigClient)
    {
        $this->client = $invertersConfigClient;
    }

    public function fetch(int $projectId): array
    {
        $response = $this->client->request('GET', "/v2/appdata/inverters/config/$projectId");

        return $response->toArray()['datas'];
    }

    /**
     * @return array<string>
     */
    public function getProjectIds(): array
    {
        return $this->client->request('GET', '/v2/appdata/inverters/config')->toArray()['datas'];
    }
}
