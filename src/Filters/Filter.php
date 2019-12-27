<?php

declare(strict_types=1);

namespace App\Filters;

use App\DTO\Filters\FilterDto;
use Doctrine\ORM\QueryBuilder;

abstract class Filter
{
    /**
     * @var FilterDto
     */
    protected $dto;

    /**
     * @var QueryBuilder
     */
    protected $builder;
    protected $alias;

    abstract protected function getAvailableFilters(): array;

    public function apply(QueryBuilder $builder): QueryBuilder
    {
        $this->builder = $builder;

        foreach ($this->calcFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    public function calcFilters(): array
    {
        $dtoKeys = array_keys($dtoData = $this->dto->getData());
        $intersect = array_intersect($dtoKeys, $this->getAvailableFilters());
        $result = [];
        foreach ($intersect as $elem) {
            $result[$elem] = $dtoData[$elem];
        }

        return $result;
    }

    public function setDto(FilterDto $dto)
    {
        $this->dto = $dto;
    }

    public function setAlias(string $alias)
    {
        $this->alias = $alias;
    }
}
