<?php

namespace App\Model;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\TermFilter;

/**
 * @ApiResource
 * @ApiFilter(OrderFilter::class, properties={"id", "datetime"})
 * @ApiFilter(TermFilter::class, properties={"projectId", "inverterId"})
 */
class InverterData
{
    /**
     * @ApiProperty(identifier=true)
     */
    public string $id = '';
    public string $projectId = '';
    public \DateTimeInterface $datetime;
    public string $inverterId = '';
    public int $pac = 0;
    public ?int $pacConsolidate = null;
}
