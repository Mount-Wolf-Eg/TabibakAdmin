<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AcademicDegreeResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\DiseaseResource;
use App\Http\Resources\HospitalResource;
use App\Http\Resources\MedicalSpecialityResource;
use App\Http\Resources\UniversityResource;
use App\Http\Resources\VendorServiceResource;
use App\Http\Resources\VendorTypeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class FilterController extends Controller
{
    public function __invoke($model, Request $request): JsonResponse
    {
        $model = app('App\\Models\\' . $model);
        $request = $request->merge(['scope' => 'micro', 'withoutRelation' => $request['withoutRelation']??true]);
        $only = (array) $request->only;
        $except = (array) $request->except;
        $modelFilters = $model->getFilterModels();
        if (!empty($only)) {
            $modelFilters = array_intersect($modelFilters, $only);
        } elseif (!empty($except)) {
            $modelFilters = array_diff($modelFilters, $except);
        }
        $data = [];
        $filters = $request->all();
        $filters['active'] = true;
        $relations = $request['relations'] ?? [];
        foreach ($modelFilters as $modelFilter) {
            $modelRepo = app('App\\Repositories\\Contracts\\' . $modelFilter . 'Contract');
            $key = Str::plural(lcfirst($modelFilter));
            $modelRelations = isset($relations[$modelFilter]) ? explode(',', $relations[$modelFilter]) : [];
            $data = array_merge($data, [$key =>
                $this->getResource($modelFilter, $modelRepo->searchBySelected(null, [], $filters, $modelRelations))
            ]);
        }
        $customFilters = $model->getFilterCustom();
        if (!empty($only)) {
            $customFilters = array_intersect($customFilters, $only);
        } elseif (!empty($except)) {
            $customFilters = array_diff($customFilters, $except);
        }
        if (empty($request['customFilters'])) {
            foreach ($customFilters as $customFilter) {
                $data = array_merge($data, ["$customFilter" => $model::$customFilter()]);
            }
        } else {
            foreach ($customFilters as $customFilter) {
                if (in_array($customFilter, $request['customFilters'], true)) {
                    $data = array_merge($data, ["$customFilter" => $model::$customFilter()]);
                }
            }
        }
        return response()->json($data);
    }

    public function getResource($model, $data): AnonymousResourceCollection
    {
        return match ($model) {
            'City' => CityResource::collection($data),
            'MedicalSpeciality' => MedicalSpecialityResource::collection($data),
            'AcademicDegree' => AcademicDegreeResource::collection($data),
            'VendorType' => VendorTypeResource::collection($data),
            'VendorService' => VendorServiceResource::collection($data),
            'Disease' => DiseaseResource::collection($data),
            'Hospital' => HospitalResource::collection($data),
            'University' => UniversityResource::collection($data),
            default => $model::collection($data),
        };
    }
}
