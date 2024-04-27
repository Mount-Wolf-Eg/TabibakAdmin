<?php

namespace App\Traits;

trait SearchTrait
{
    public function scopeOfKeyword($query, $keyword)
    {
        $withRelations = array_filter($this->searchable, static fn($col)=>str_contains($col,'.'));
        $columnsArr = array_filter($this->searchable, static fn($col)=>!in_array($col, $withRelations, true));
        $columns = implode(',', $columnsArr ?? []);
        return $query
            ->when(!empty($columns),function ($q)use ($columns, $keyword){
                $q->whereRaw("CONCAT_WS(' ', {$columns}) like '%{$keyword}%'");
            })
            ->when(!empty($withRelations),function ($q)use ($withRelations,$keyword){
                foreach ($withRelations as $relation){
                    $relation = explode('.',$relation);
                    $col = $relation[count($relation)-1];
                    array_pop($relation);
                    $relation = implode('.',$relation);
                    $q->orWhereRelation($relation,$col, 'like', '%'.$keyword.'%');
                }
            });
    }

    public function scopeOfHas($query, $relations)
    {
        foreach((array)$relations as $relation){
            $query->has($relation);
        }
        return $query;
    }

    public function scopeOfActive($query, $active = true)
    {
        $active = filter_var($active, FILTER_VALIDATE_BOOLEAN);
        return $query->where('is_active', $active);
    }

}
