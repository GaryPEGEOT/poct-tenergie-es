<?php

namespace App\InverterData;

interface InverterDataProviderInterface
{
    public function provide(int $projectId, array $config): \Generator;
}
