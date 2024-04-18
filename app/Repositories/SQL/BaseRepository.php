<?php

namespace App\Repositories\SQL;

use App\Exceptions\CantDeleteModelException;
use App\Repositories\Contracts\BaseContract;
use App\Traits\ActivityLogTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

abstract class BaseRepository implements BaseContract
{
    use ActivityLogTrait;

    protected Model $model;
    protected string $modelName;
    protected Builder $query;

    protected array $defaultFilters = [];

    /**
     * BaseRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->query = $model->query();
        $this->model = $model;
        $this->modelName = class_basename($this->model);;
    }

    public function getModelName()
    {
        return $this->modelName;
    }

    public function freshRepo(): static
    {
        $this->query = $this->model->query();
        return $this;
    }

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes = []): mixed
    {
        if (!empty($attributes)) {
            // Clean the attributes from unnecessary inputs
            if (method_exists($this, 'beforeCreate')) {
                $attributes = $this->beforeCreate($attributes);
            }
            $filtered = $this->cleanUpAttributes($attributes);
            $model = $this->query->create($filtered);
            if (method_exists($this, 'syncRelations')) {
                $this->syncRelations($model, $attributes);
            }
            $this->propertyLogActivity(
                $model,
                auth()->user(),
                "$this->modelName #id: $model->id Created",
                ['action' => 'Creation',
                    'data' => [
                        'user' => auth()->user()?->name,
                        'url' => '',
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ]]
            );
            if (method_exists($this, 'afterCreate')) {
                $this->afterCreate($model, $attributes);
            }
            return $model->refresh();
        }
        return false;
    }

    /**
     * @param Model $model
     * @param array $attributes
     *
     * @return mixed
     */
    public function update(Model $model, array $attributes = []): mixed
    {
        if (!empty($attributes)) {
            $oldModel = $model->replicate();
            if (method_exists($this, 'beforeUpdate')) {
                $attributes = $this->beforeUpdate($attributes);
            }
            // Clean the attributes from unnecessary inputs
            $filtered = $this->cleanUpAttributes($attributes);
            $model = tap($model)->update($filtered)->fresh();
            $changes = $this->customLogOnUpdateFields($filtered, $oldModel, $model);
            if (method_exists($this, 'syncRelations')) {
                $this->syncRelations($model, $attributes);
            }
            if (method_exists($this, 'afterUpdate')) {
                $this->afterUpdate($model, $changes);
            }
            return $model;
        }
        return false;
    }

    /**
     * @param Model $model
     * @param string $relation
     * @param array $attributes
     *
     * @return mixed
     */
    public function attach(Model $model, string $relation, array $attributes = []): mixed
    {
        if (!empty($attributes)) {
            return $model->{$relation}()->attach($attributes);
        }
        return false;
    }

    /**
     * @param Model $model
     * @param string $relation
     * @param array $attributes
     *
     * @return mixed
     */
    public function detach(Model $model, string $relation, array $attributes = []): mixed
    {
        if (!empty($attributes)) {
            return $model->{$relation}()->detach($attributes);
        }
        return false;
    }

    /**
     * @param Model $model
     * @param string $relation
     * @param array $attributes
     *
     * @return mixed
     */
    public function sync(Model $model, string $relation, array $attributes = []): mixed
    {
        if (!empty($attributes)) {
            return $model->{$relation}()->sync($attributes);
        }
        return false;
    }

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function updateAll(array $attributes = []): mixed
    {
        if (!empty($attributes)) {
            // Clean the attributes from unnecessary inputs
            $filtered = $this->cleanUpAttributes($attributes);
            return $this->query->update($filtered);
        }
        return false;
    }

    /**
     * @param $key
     * @param array $values
     * @param array $attributes
     *
     * @return int|bool
     */
    public function updateAllByKey($key, array $values = [], array $attributes = []): int|bool
    {
        if (!empty($attributes) && !empty($values)) {
            // Clean the attributes from unnecessary inputs
            $filtered = $this->cleanUpAttributes($attributes);
            return $this->query->whereIn($key, $values)->update($filtered);
        }
        return false;
    }

    /**
     * @param array $attributes
     * @param null $id
     *
     * @return bool|mixed
     */
    public function createOrUpdate(array $attributes = [], $id = null): mixed
    {
        if (empty($attributes)) {
            return false;
        }
        // Clean the attributes from unnecessary inputs
        $filtered = $this->cleanUpAttributes($attributes);
        if ($id) {
            $model = $this->query->find($id);
            return $this->update($model, $filtered);
        }
        return $this->create($filtered);
    }

    /**
     * @param array $attributes
     * @param array $identifier
     *
     * @return bool|mixed
     */
    public function defaultUpdateOrCreate(array $attributes, array $identifier = []): mixed
    {
        if (empty($attributes)) {
            return false;
        }
        // Clean the attributes from unnecessary inputs
        $attributes = $this->cleanUpAttributes($attributes);
        $identifier = $this->cleanUpAttributes($identifier);
        if (key_exists('dynamics_id', $identifier)) {
            $exists = $this->query->where('dynamics_id', $identifier['dynamics_id'])->exists();
            $message = $exists ? 'Updated' : 'created';
            $action = $exists ? 'Update' : 'Creation';
        } else {
            $message = 'created';
            $action = 'Creation';
        }
        $model = $this->query->updateOrCreate($attributes, $identifier);
        $this->propertyLogActivity(
            $model,
            auth()->user(),
            "$this->modelName $message",
            ['action' => $action]
        );
        return $model;
    }

    /**
     * @param Model $model
     * @return bool|mixed|null
     * @throws Exception
     */
    public function remove(Model $model): mixed
    {
        // Check if has relations
        foreach ($model->getDefinedRelations() as $relation) {
            if ($model->$relation()->count()) {
                throw new CantDeleteModelException(__("messages.errors.cannot_delete", ['model' => __('messages.modelSingle.' . lcfirst($this->modelName)), 'relation' => __('messages.' . $relation)]));
            }
        }
        $this->propertyLogActivity(
            $model,
            auth()->user(),
            "$this->modelName #id: $model->id Removed",
            ['action' => 'Removing',
                'data' => [
                    'user' => auth()->user()?->name,
                    'url' => '',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]]
        );
        return $model->delete();
    }

    public function canRemove(Model $model): bool
    {
        // Check if model has relations
        foreach ($model->getDefinedRelations() as $relation) {
            if ($model->$relation()->count()) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $relations
     * @return static
     */
    public function has(array $relations = []): static
    {
        foreach ($relations as $relation) {
            $this->query->has($relation);
        }
        return $this;
    }

    /**
     * @param array $relations
     * @return $this
     */
    public function doesntHave(array $relations = []): static
    {
        foreach ($relations as $relation) {
            $this->query->has($relation);
        }
        return $this;
    }

    public function havingRaw($sql): static
    {
        $this->query->havingRaw($sql);
        return $this;
    }

    /**
     * @param array $relations
     * @return $this
     */
    public function whereHas(array $relations = []): static
    {
        foreach ($relations as $relationName => $filters) {
            //            info($relationName);
            if (!method_exists($this->model, $relationName)) {
                //                info("no relation");
                return $this;
            }
            $this->query->whereHas($relationName, function ($query) use ($relationName, $filters) {
                if (!empty($filters)) {
                    //                    info($filters);
                    $relatedModel = $this->getRelatedModel($relationName);
                    if (!$relatedModel) {
                        //                        info("no model");
                        return $this;
                    }
                    foreach ($relatedModel->getFilters() as $filter) {
                        //                        info($filter);
                        if (isset($filters[$filter])) {
                            $withFilter = "of" . ucfirst($filter);
                            $query->$withFilter($filters[$filter]);
                        }
                    }
                }
            });
        }
        return $this;
    }


    /**
     * @param array $columns
     * @return $this
     */
    public function withSum(array $columns = []): static
    {
        foreach ($columns as $column) {
            $split = explode('.', $column);
            if (count($split) == 2) {
                $this->query->withSum($split[0], $split[1]);
            }
        }
        return $this;
    }


    /**
     * @param array $relations
     * @return $this
     */
    public function withCount(array $relations = []): static
    {
        foreach ($relations as $relation) {
            $this->query->withCount($relation);
        }
        return $this;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->query->count();
    }

    /**
     * @param $filters
     * @return int
     */
    public function countWithFilters($filters): int
    {
        $query = $this->query;
        foreach ($this->model->getFilters() as $filter) {
            if (isset($filters[$filter])) {
                $withFilter = "of" . ucfirst($filter);
                $query = $query->$withFilter($filters[$filter]);
            }
        }
        return $query->count();
    }

    /**
     * @param $query
     * @param array $filters
     * @return Builder
     */
    public function withFilters($query, array $filters = []): Builder
    {
        if (count($filters)) {
            foreach ($this->model->getFilters() as $filter) {
                if (isset($filters[$filter])) {
                    $withFilter = "of" . ucfirst($filter);
                    $query = $query->$withFilter($filters[$filter]);
                }
            }
        }
        return $query;
    }

    /**
     * @return object|null
     */
    public function first(): null|object
    {
        return $this->query->first();
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        return $this->query->exists();
    }

    /**
     * @return bool
     */
    public function doesntExist(): bool
    {
        return $this->query->doesntExist();
    }

    /**
     * @param Model $model
     * @param $column
     * @param int $value
     * @return void
     */
    public function increment(Model $model, $column, int $value = 1): void
    {
        $model->increment($column, $value);
    }

    /**
     * @param Model $model
     * @param $column
     * @param $value
     * @return void
     */
    public function decrement(Model $model, $column, $value): void
    {
        $model->decrement($column, $value);
    }

    /**
     * @param $column
     * @return mixed
     */
    public function sum($column): mixed
    {
        return $this->aggregate('sum', $column);
    }

    /**
     * @param $function
     * @param $column
     * @return mixed
     */
    public function aggregate($function, $column): mixed
    {
        return $this->query->{$function}($column);
    }

    /**
     * @param $ids
     * @return mixed
     */
    public function findIds($ids): mixed
    {
        return $this->query->findOrFail($ids);
    }

    /**
     * @param int|array $id
     * @param array $relations
     * @param array $filters
     * @return mixed
     */
    public function find(int|array $id, array $relations = [], array $filters = []): mixed
    {
        $query = $this->query;
        $query = $this->applyRelations($query, $relations);
        return $this->withFilters($query, $filters)->find($id);
    }

    /**
     * @param $column
     * @param $data
     * @return mixed
     */
    public function getByKey($column, $data): mixed
    {
        return $this->query->whereIn($column, (array)$data)->get();
    }

    /**
     * @param int $id
     * @param array $relations
     * @param array $filters
     * @return mixed
     */
    public function findOrFail(int $id, array $relations = [], array $filters = []): mixed
    {
        $query = $this->query;
        $query = $this->applyRelations($query, $relations);
        return $this->withFilters($query, $filters)->findOrFail($id);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param bool $fail
     * @return mixed
     */
    public function findBy(string $key, mixed $value, bool $fail = true): mixed
    {
        $model = $this->query->where($key, $value);
        return $fail ? $model->firstOrFail() : $model->first();
    }

    /**
     * @param mixed $fields
     *
     * @return mixed
     */
    public function findByFields(array $fields): mixed
    {
        $query = $this->query;
        if (isset($fields['and'])) {
            $query = $query->where($fields['and']);
        }
        if (isset($fields['or'])) {
            $query = $query->orWhere(function (Builder $query) use ($fields) {
                foreach ($fields['or'] as $condition) {
                    $query = $query->orWhere($condition[0], $condition[1]);
                }
            });
        }
        return $query->first();
    }

    /**
     * @param array $filters
     * @param array $relations
     * @return Model|Builder|null
     */
    public function findByFilters(array $filters, array $relations = []): Model|Builder|null
    {
        $query = $this->query;
        $query = $this->applyRelations($query, $relations);
        $query = $this->withFilters($query, $filters);
        return $query->first();
    }

    /**
     * @param array $wheres
     * @param array|null $data
     * @return mixed
     */
    public function whereOrCreate(array $wheres, array $data = null): mixed
    {
        return $this->query->firstOrCreate($data ?? $wheres, $wheres);
    }

    public function applyConditions($query, $conditions)
    {
        if (!empty($conditions)) {
            foreach ($conditions as $conditionType => $whereConditions) {
                if ($conditionType == 'where' && !empty($whereConditions)) {
                    foreach ($whereConditions as $field => $value) {
                        $query = $query->where($field, $value);
                    }
                }

                if ($conditionType == 'whereNot' && !empty($whereConditions)) {
                    foreach ($whereConditions as $field => $value) {
                        $query = $query->where($field, '!=', $value);
                    }
                }

                if ($conditionType == 'whereDateLess' && !empty($whereConditions)) {
                    foreach ($whereConditions as $field => $value) {
                        $query = $query->whereDate($field, '<=', Carbon::parse($value));
                    }
                }
                if ($conditionType == 'whereDateMore' && !empty($whereConditions)) {
                    foreach ($whereConditions as $field => $value) {
                        $query = $query->whereDate($field, '>=', Carbon::parse($value));
                    }
                }

                if ($conditionType == 'whereIn' && !empty($whereConditions)) {
                    foreach ($whereConditions as $field => $value) {
                        $query = $query->whereIn($field, $value);
                    }
                }

                if ($conditionType == 'whereNotIn' && !empty($whereConditions)) {
                    foreach ($whereConditions as $field => $value) {
                        $query = $query->whereNotIn($field, $value);
                    }
                }

                if ($conditionType == 'whereLike' && !empty($whereConditions)) {
                    foreach ($whereConditions as $field => $value) {
                        $query = $query->where($field, 'like', '%' . $value . '%');
                    }
                }

                if ($conditionType == 'whereBetween' && !empty($whereConditions)) {
                    foreach ($whereConditions as $field => $value) {
                        $query = $query->whereBetween($field, $value);
                    }
                }
            }
        }
        return $query;
    }

    public function applyRelations($query, $relations)
    {
        if (!empty($relations)) {
            $query = $query->with($relations);
        }
        return $query;
    }

    /**
     * @param string|null $labelField
     * @param string $valueField
     * @param bool $applyOrder
     * @param string $orderBy
     * @param string $orderDir
     * @param array $conditions
     *
     * @return mixed
     */
    public function findAllForFormSelect(
        string $labelField = null,
        string $valueField = 'id',
        bool   $applyOrder = false,
        string $orderBy = self::ORDER_BY,
        string $orderDir = self::ORDER_DIR,
        array  $conditions = []
    ): mixed
    {
        $query = $this->query;
        if ($applyOrder) {
            $query = $query->orderBy($orderBy, $orderDir);
        }
        $query = $this->applyConditions($query, $conditions);
        return $query->pluck($valueField, $labelField);
    }

    /**
     * @param array $fields
     * @param bool $applyOrder
     * @param string $orderBy
     * @param string $orderDir
     * @return mixed
     */
    public function findAll(array $fields = ['*'], bool $applyOrder = true, string $orderBy = self::ORDER_BY, string $orderDir = self::ORDER_DIR): mixed
    {
        $query = $this->query;
        if ($applyOrder) {
            $query = $query->orderBy($orderBy, $orderDir);
        }
        return $query->get($fields);
    }

    /**
     * @param $query
     * @param array $filters
     * @param array $relations
     * @param array $data
     * @return mixed
     */
    public function baseSearch($query, array $filters = [],
                               array $relations = [], array $data = []): mixed
    {
        $query = $this->applyRelations($query, $relations);
        if (!empty($filters)) {
            foreach ($this->model->getFilters() as $filter) {
                //if (isset($filters[$filter]) and !empty($filters[$filter])) {
                if (isset($filters[$filter])) {
                    $withFilter = "of" . ucfirst($filter);
                    $query = $query->$withFilter($filters[$filter]);
                }
            }
        }
        return $query;
    }

    /**
     * @param array $filters
     * @param array $relations
     * @param array $data
     * @return mixed
     */
    public function search(array $filters = [], array $relations = [], array $data = []): mixed
    {
        $query = $this->baseSearch($this->query, $filters, $relations, $data);
        return $this->getQueryResult($query, $data);
    }

    /**
     * @param array $filters
     * @param array $relations
     * @param array $data
     * @return mixed
     */
    public function searchWithTrashed(array  $filters = [], array  $relations = [], array $data = []): mixed
    {
        $query = $this->baseSearch($this->query->withTrashed(), $filters, $relations, $data);
        return $this->getQueryResult($query, $data);
    }

    /**
     * @param $query
     * @param array $data
     * @return mixed
     */
    public function getQueryResult($query, array $data = []): mixed
    {
        $page = $data['page'] ?? true;
        $limit = $data['limit'] ?? self::LIMIT;
        $customizePaginationURI = $data['customizePaginationUri'] ?? null;
        $paginationURI = $data['paginationUri'] ?? null;
        $order = $data['order'] ?? [];
        /**
         * orderIgnoreNull
         *
         * $orderIgnoreNull: to order items with specific and keep null values in the last
         * $orderIgnoreNull['nullable_column']: when the column specified here is null, the item will be sorted in the last
         * $orderIgnoreNull['order_column']: order by column
         * $orderIgnoreNull['dir']: order direction
         *
         * example:
         *
         * $orderIgnoreNull = [
         *  'nullable_column' => 'parent_id',
         *  'order_column' => 'id',
         *  'dir' => 'DESC'
         * ]
         */
        $orderIgnoreNull = $data['orderIgnoreNull'] ?? [];
        if (!empty($orderIgnoreNull)) {
            $query = $query->orderByRaw("CASE WHEN ".$orderIgnoreNull['nullable_column']." IS NULL THEN 1 ELSE 0 END")
                ->orderBy($orderIgnoreNull['order_column'], $orderIgnoreNull['dir']);
        }
        if (!empty($order)) {
            foreach ($order as $orderBy => $orderDir) {
                $query = $query->orderBy($orderBy, $orderDir);
            }
        }else{
            $query = $query->latest();
        }
        if (config('app.query_debug')) {
            info($query->toSql());
        }
        $groupBy = $data['groupBy'] ?? null;
        if (!empty($groupBy)) {
            return $query->get()->groupBy($groupBy);
        }
        if ($customizePaginationURI) {
            $query = $query->paginate($limit);
            return $query->withPath($paginationURI);
        }
        if ($page) {
            return $query->paginate($limit);
        }
        if ($limit) {
            return $query->take($limit)->get();
        }
        return $query->get();
    }

    protected function cleanUpAttributes($attributes): array
    {
        return collect($attributes)->filter(function ($value, $key) {
            return $this->model->isFillable($key);
        })->toArray();
    }

    /**
     * @param null $groupBy
     * @param array $fields
     * @param array $filters
     * @param array $relations
     * @param bool $applyOrder
     * @param bool $page
     * @param bool $limit
     * @param string $orderBy
     * @param string $orderDir
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function searchBySelected(
        $groupBy = null,
        array $fields = [],
        array $filters = [],
        array $relations = [],
        bool $applyOrder = false,
        bool $page = false,
        bool $limit = false,
        string $orderBy = self::ORDER_BY,
        string $orderDir = self::ORDER_DIR
    ): array|\Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator|Collection
    {
        $query = $this->query;
        $query = $this->applyRelations($query, $relations);
        if (!empty($filters)) {
            foreach ($this->model->getFilters() as $filter) {
                //if (isset($filters[$filter]) and !empty($filters[$filter])) {
                if (isset($filters[$filter])) {
                    $withFilter = "of" . ucfirst($filter);
                    $query = $query->$withFilter($filters[$filter]);
                }
            }
        }
        if (!empty($fields)) {
            $query = $query->selectRaw(implode(',', $fields));
        }
        if (!empty($groupBy)) {
            $query = $query->groupBy(implode(',', $groupBy));
        }
        if ($applyOrder) {
            $query = $query->orderBy($orderBy, $orderDir);
        }
        if ($page) {
            return $query->paginate($limit);
        }
        if ($limit) {
            return $query->take($limit)->get();
        }
        return $query->get();
    }

    /**
     * Create a Pagination From Items Of  array Or collection.
     *
     * @param array|Collection $items
     * @param int $perPage
     * @param int|null $page
     * @param array $options
     *
     * @return LengthAwarePaginator
     */
    public function paginate(array|Collection $items, int $perPage = 15, int $page = null, array $options = []): LengthAwarePaginator
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /**
     * @param Model $model
     * @param string $relation
     * @param array $attributes
     *
     * @return mixed
     */
    public function relationCreate(Model $model, string $relation, array $attributes = []): mixed
    {
        if (!empty($attributes)) {
            return $model->{$relation}()->create($attributes);
        }
        return false;
    }


    /**
     * @param $model
     * @param string $field
     *
     * @return mixed
     */
    public function toggleField($model, string $field): mixed
    {
        $newVal = 1;
        if ($model[$field] === 1) {$newVal = 0;}
        return $this->update($model, [$field => $newVal]);
    }

    public function restoreDeletedRecord($id): void
    {
        $this->model->withTrashed()->find($id)->restore();
    }
}
