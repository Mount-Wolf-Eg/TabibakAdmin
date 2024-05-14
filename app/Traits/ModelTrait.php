<?php

namespace App\Traits;

use Illuminate\Contracts\Translation\Translator;
use Illuminate\Foundation\Application;
use ReflectionClass;

trait ModelTrait
{
    public function __construct()
    {
        parent::__construct();
        if (isset($this->dates)) {
            foreach ($this->dates as $date) {
                $this->casts[$date] = 'datetime';
            }
        }
    }
    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters ?? [];
    }

    /**
     * @return array
     */
    public function getFilterModels(): array
    {
        return $this->filterModels ?? [];
    }

    public function getFilterCustom(): array
    {
        return $this->filterCustom ?? [];
    }

    public function getDefinedRelations()
    {
        return $this->definedRelations ?? [];
    }

    public function syncOneToMany($relation, $options = []): void
    {
        $oldOptionsIDs = [];
        // create new options
        $newOptions = [];
        foreach ($options as $option) {
            if (!isset($option['id'])) {
                $newOptions[] = $this->$relation()->getModel()->newInstance($option);
            } else {
                $oldOptionsIDs[] = $option['id'];
            }
        }
        // delete removed options
        $allOptionsIDs = $this->$relation->lists('id');
        $allOptionsIDs = $allOptionsIDs->all();
        if (!empty($allOptionsIDs)) {
            $removedOptionsIDs = array_diff($allOptionsIDs, $oldOptionsIDs);
            $this->$relation()->whereIn('id', $removedOptionsIDs)->delete();
        }
        // save new options after delete removed options
        $this->$relation()->saveMany($newOptions);
    }

    public static function getConstants($keyContains = null, $returnCount = false): array|int
    {
        // Get all Constants
        $constants = (new ReflectionClass(static::class))->getConstants();
        // Return filtered Constants based on Constants names filter
        if (!empty($keyContains)) {
            $constants = array_filter($constants, static function ($k) use ($keyContains) {
                return str_starts_with($k, $keyContains);
            }, ARRAY_FILTER_USE_KEY);
        }
        if ($returnCount) {
            return count($constants);
        }
        return $constants;
    }

    public function getActiveStatusAttribute(): Application|array|string|Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        return $this->is_active ? __('Active') : __('Inactive');
    }

    public function getActiveClassAttribute(): string
    {
        return $this->is_active ? 'success' : 'danger';
    }

    public function getModelName(): string
    {
        return str_replace('App\\Models\\', '', get_class($this));
    }

    public function hasColumn($column): bool
    {
        return $this->getConnection()->getSchemaBuilder()->hasColumn($this->getTable(), $column);
    }
}
