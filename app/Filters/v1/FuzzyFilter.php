<?php

namespace App\Filters\v1;



use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\Filters\Filter;

class FuzzyFilter implements Filter
{
    private array $columns;
    private array $relationProperties = [];
    private array $properties = [];

    public function __construct(string ...$columns)
    {
        $this->columns = $columns;
    }

    /**
     * @param string $value
     */
    public function __invoke(Builder $query, $value, string $property): void
    {
        foreach (Arr::wrap($this->columns) as $column) {
            if ($this->isRelationProperty($query, $column)) {
                [$relation, $c] = $this->splitPropertyRelation($column);
                $this->relationProperties[$relation][] = $c;
            }

            if ($this->isProperty($query, $column)) {
                $this->properties[] = $column;
            }
        }

        $this->applyFilters($query, $this->properties, $value);

        $this->applyRelationFilters($query, $this->relationProperties, $value);
    }

    protected function applyFilters(Builder $query, array $attributes, string $value): void
    {
        $query->where(function (Builder $query) use ($attributes, $value) {
            foreach (Arr::wrap($attributes) as $attribute) {
                $query->orWhere(function ($query) use ($attribute, $value) {
                    foreach (explode(' ', $value) as $searchTerm) {
                        $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                    }
                });
            }
        });
    }

    protected function applyRelationFilters(Builder $query, array $attributes, $value): void
    {
        foreach ($attributes as $relation => $columns) {
            $this->applyRelationFilter($query, $relation, $columns, $value);
        }
    }

    protected function applyRelationFilter(Builder $query, $relation, array $attributes, $value): void
    {
        $query->orWhereHas($relation, function (Builder $query) use ($attributes, $value) {
            $this->applyFilters($query, $attributes, $value);
        });
    }

    protected function splitPropertyRelation(string $column): array
    {
        return explode('.', $column);
    }

    protected function isRelationProperty(Builder $query, string $property): bool
    {
        if (! Str::contains($property, '.')) {
            return false;
        }

        if (in_array($property, $this->relationProperties, true)) {
            return false;
        }

        if (Str::startsWith($property, $query->getModel()->getTable().'.')) {
            return false;
        }

        return true;
    }

    protected function isProperty(Builder $query, string $property): bool
    {
        if (in_array($property, $this->properties, true)) {
            return false;
        }

        if (Str::contains($property, '.') && ! Str::startsWith($property, $query->getModel()->getTable().'.')) {
            return false;
        }

        return true;
    }
}
