<?php

declare(strict_types=1);

namespace App\Filter;

use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\AbstractSearchFilter;

class RangeFilter extends AbstractSearchFilter
{
    private const ALLOWED_KEYS = [
        'gt',
        'gte',
        'lt',
        'lte',
        'format',
        'relation',
        'time_zone',
        'boost',
    ];

    /**
     * {@inheritdoc}
     */
    protected function getQuery(string $property, array $values, ?string $nestedPath): array
    {
        $query = ['range' => [$property => []]];

        foreach ($values as $key => $value) {
            if (\in_array($key, self::ALLOWED_KEYS, true) && is_scalar($value)) {
                $query['range'][$property][$key] = $value;
            }
        }

        if (null !== $nestedPath) {
            $query = ['nested' => ['path' => $nestedPath, 'query' => $query]];
        }

        return $query;
    }
}
