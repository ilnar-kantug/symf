<?php

namespace App\Repository\Concerns;

use App\Filters\Filter;
use Doctrine\ORM\QueryBuilder;

trait Filterable
{
    public function applyFilters(QueryBuilder $qb, Filter $filter, $dto, $alias): QueryBuilder
    {
        $filter->setDto($dto);
        $filter->setAlias($alias);
        $filter->apply($qb);

        return $qb;
    }
}
