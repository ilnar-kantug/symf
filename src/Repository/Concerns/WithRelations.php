<?php

declare(strict_types=1);

namespace App\Repository\Concerns;

use Doctrine\ORM\QueryBuilder;

trait WithRelations
{
    public function fetchRelations(array $relations, string $alias, QueryBuilder $query): void
    {
        foreach ($relations as $element) {
            if (in_array($element, $this->getRelationsNames())) {
                $query
                    ->leftJoin($alias . '.' . $element, $innerAlias = $alias . '_' . $element)
                    ->addSelect($innerAlias);
            }
        }
    }
}
