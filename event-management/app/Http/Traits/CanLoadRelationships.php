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
        // Get the relations to load, either from the provided array or from the class property
        $relations = $relations ?? $this->relations ?? [];

        // Loop through each relation and conditionally load it based on the request
        foreach ($relations as $relation) {

            // Check if the relation should be included
            $included = $this->shouldIncludeRelation($relation);

            // If the relation should be included, load it using the appropriate method based on the type of $for
            $for->when($included, fn($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation));
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
        // Get the 'include' query parameter from the request
        $include = request()->query('include');

        if (!$include) {
            return false;
        }

        // Split the 'include' parameter into an array of relations and trim whitespace
        $relations = array_map('trim', explode(',', $include));

        // Check if the given relation is in the array of relations to include
        return in_array($relation, $relations, true);
    }
}
