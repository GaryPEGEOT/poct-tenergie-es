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
class InvertersData
{
    /**
     * @ApiProperty(identifier=true)
     */
    public string $id = '';
    public int $projectId = 0;
    public \DateTimeInterface $datetime;
    public string $inverterId = '';

    /**
     * @var int|float|null
     */
    public $pac = null;
    public ?int $pacConsolidate = null;
}
