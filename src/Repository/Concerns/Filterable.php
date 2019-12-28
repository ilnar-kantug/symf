<?php

namespace App\Repository\Concerns;

use App\DTO\Filters\FilterDto;
use App\Filters\Filter;
use Doctrine\ORM\QueryBuilder;

trait Filterable
{
    public function applyFilters(QueryBuilder $qb, Filter $filter, FilterDto $dto, string $alias): QueryBuilder
    {
        $filter->setDto($dto);
        $filter->setAlias($alias);
        $filter->apply($qb);

        return $qb;
    }
}
