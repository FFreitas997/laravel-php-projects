<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait CanLoadRelationships
{
    /**
     * Load relationships for the given model or query builder.
     * @param Model|QueryBuilder|EloquentBuilder|HasMany $for
     * @param array|null $relations
     * @return Model|EloquentBuilder|QueryBuilder|HasMany
     */
    protected function loadRelationships(Model|QueryBuilder|EloquentBuilder|HasMany $for, ?array $relations = null): Model|EloquentBuilder|QueryBuilder|HasMany
    {
        $relations = $relations ?? $this->relations ?? [];

        foreach ($relations as $relation) {
            $for->when(
                $this->shouldIncludeRelation($relation),
                fn($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation)
            );
        }

        return $for;
    }

    /**
     * Check if the relation should be included based on the request.
     * @param string $relation
     * @return bool
     */
    protected function shouldIncludeRelation(string $relation): bool
    {
        $include = request()->query('include');

        if (!$include) {
            return false;
        }

        $relations = array_map('trim', explode(',', $include));

        return in_array($relation, $relations, true);
    }
}
