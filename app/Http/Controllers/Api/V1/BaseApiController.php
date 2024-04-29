<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\BaseContract;
use App\Traits\BaseApiResponseTrait;
use Symfony\Component\HttpFoundation\Response;

class BaseApiController extends Controller
{
    use BaseApiResponseTrait;
    protected bool $order = true;
    protected BaseContract $contract;
    protected mixed $modelResource;
    protected array $relations = [];
    protected array $defaultScopes = [];

    /**
     * BaseApiController constructor.
     *
     * @param BaseContract $contract
     * @param mixed $modelResource
     * @param bool|string $applyPermissions
     */
    public function __construct(BaseContract $contract, mixed $modelResource, bool|string $applyPermissions = '')
    {
        $this->contract = $contract;
        $this->modelResource = $modelResource;

        // Include embedded data
        if (request()->has('embed')) {
            $this->parseIncludes(request('embed'));
        }

        if (!empty($applyPermissions)) {
            $this->applyCrudPermissions($applyPermissions);
        }
    }

    /**
     * index() Display a listing of the resource.
     *
     * @param array $additional
     * @return mixed
     */
    public function index(array $additional = []): mixed
    {
        $page = 1;
        $limit = 10;
        $order = [];
        $filters = array_merge(request()->all(), $this->defaultScopes);
        if (request()->has('page')) {
            $page = request('page');
        }
        if (request()->has('limit')) {
            $limit = request('limit');
        }
        if (request()->has('order')) {
            $order = request('order');
        }
        $data = array_merge(request()->all(), ['order' => $order, 'limit' => $limit, 'page' => $page]);
        $models = $this->contract->search($filters, $this->relations, $data);
        $groupBy = request('groupBy');
        if ($groupBy)
            return $this->respondWithGroupByCollection($models, $groupBy);
        return $this->respondWithCollection($models, null, [], $additional);
    }


    /**
     * parseIncludes() used to explode embed relations array
     *
     * @param $embed
     */
    protected function parseIncludes($embed): void
    {
        $this->relations = explode(',', $embed);
    }

    /**
     * respond() used to return resource with status and headers
     *
     * @param $resources
     * @param array $headers
     * @param array $additional
     * @return mixed
     */
    protected function respond($resources, array $headers = [], array $additional = []): mixed
    {
        return $resources
            ->additional(array_merge(['status' => $this->getStatusCode()], $additional))
            ->response()
            ->setStatusCode($this->getStatusCode())
            ->withHeaders($headers);
    }

    /**
     * respondWithCollection() used to take collection
     * and return its data transformed by resource response
     *
     * @param $collection
     * @param int|null $statusCode
     * @param array $headers
     * @param array $additional
     * @return mixed
     */
    protected function respondWithCollection($collection, int $statusCode = null, array $headers = [], array $additional = []): mixed
    {
        $statusCode = $statusCode ?? Response::HTTP_OK;
        $resources = forward_static_call([$this->modelResource, 'collection'], $collection);
        return $this->setStatusCode($statusCode)->respond($resources, $headers, $additional);
    }

    /**
     * respondWithGroupByCollection() used to take group by collection
     * and return its data transformed by resource response
     *
     * @param $models
     * @param string $groupBy
     * @param int|null $statusCode
     * @param array $headers
     * @return mixed
     */
    protected function respondWithGroupByCollection($models, string $groupBy, int $statusCode = null, array $headers = []): mixed
    {
        $statusCode = $statusCode ?? Response::HTTP_OK;
        $models = $models->map(function ($items, $key) use ($groupBy){
            $model = $items->first()->getModel();
            $casts = $model->getCasts();
            $groupBy = str_replace('.value', '', $groupBy);
            if (array_key_exists($groupBy, $casts) && preg_match("/\bConstants\b/i", $casts[$groupBy])){
                $key = $model->getCasts()[$groupBy]::getLabels()[$key];
            }
            return [
                'groupBy' => $key,
                'items' => forward_static_call([$this->modelResource, 'collection'], $items)
            ];
        })->all();
        return $this->respondWithArray(['status'=> $statusCode, 'data' => $models], $headers);
    }

    /**
     * respondWithModel() used to return result with one model relation
     *
     * @param $model
     * @param int|null $statusCode
     * @param array $headers
     * @return mixed
     */
    protected function respondWithModel($model, int $statusCode = null, array $headers = []): mixed
    {
        $statusCode = $statusCode ?? Response::HTTP_OK;
        $resource = new $this->modelResource($model->load($this->relations)); // ???
        return $this->setStatusCode($statusCode)->respond($resource, $headers);
    }
}
