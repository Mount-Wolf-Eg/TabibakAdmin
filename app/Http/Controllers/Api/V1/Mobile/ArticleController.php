<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\DoctorArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Repositories\Contracts\ArticleContract;
use App\Repositories\Contracts\LikeContract;
use Exception;
use \Illuminate\Http\JsonResponse;

class ArticleController extends BaseApiController
{
    private LikeContract $likeContract;

    /**
     * ArticleController constructor.
     * @param ArticleContract $contract
     * @param LikeContract $likeContract
     */
    public function __construct(ArticleContract $contract, LikeContract $likeContract)
    {
        parent::__construct($contract, ArticleResource::class);
        $this->likeContract = $likeContract;
        $this->relations = ['mainImage', 'author', 'likes', 'images', 'medicalSpeciality', 'publisher',
            'author.doctor.medicalSpecialities'];
        $this->defaultScopes = ['isPublished' => true];
        $this->middleware('permission:create-article')->only(['store']);
        $this->middleware('permission:update-article')->only(['update']);
        $this->middleware('permission:delete-article')->only(['destroy']);
    }

    /**
     * Store a newly created resource in storage.
     * @param DoctorArticleRequest $request
     * @return JsonResponse
     */
    public function store(DoctorArticleRequest $request): JsonResponse
    {
        try {
            $article = $this->contract->create($request->validated());
            return $this->respondWithModel($article->load($this->relations));
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     * @param Article $article
     * @return JsonResponse
     */
    public function show(Article $article): JsonResponse
    {
        try {
            $this->contract->increment($article, 'views');
            return $this->respondWithModel($article->load($this->relations));
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DoctorArticleRequest $request
     * @param Article $article
     * @return JsonResponse
     */
    public function update(DoctorArticleRequest $request, Article $article) : JsonResponse
    {
        try {
            $article = $this->contract->update($article, $request->validated());
            return $this->respondWithModel($article->load($this->relations));
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param Article $article
     * @return JsonResponse
     */
    public function destroy(Article $article): JsonResponse
    {
        try {
            $this->contract->remove($article);
            return $this->respondWithSuccess(__('messages.actions_messages.delete_success'));
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * active & inactive the specified resource from storage.
     * @param Article $article
     * @return JsonResponse
     */
    public function changeActivation(Article $article): JsonResponse
    {
        try {
            $this->contract->toggleField($article, 'is_active');
            return $this->respondWithModel($article->load($this->relations));
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @param Article $article
     * @return JsonResponse
     */
    public function toggleLike(Article $article): JsonResponse
    {
        $this->likeContract->toggleRecord($article);
        return $this->respondWithModel($article);
    }
}
