<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\MedicalSpeciality;
use App\Repositories\Contracts\ArticleContract;
use App\Repositories\Contracts\MedicalSpecialityContract;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseWebController;
use App\Services\Repositories\ArticleNotificationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ArticleController extends BaseWebController
{
    protected MedicalSpecialityContract $medicalSpecialityContract;
    protected ArticleNotificationService $articleNotificationService;

    /**
     * ArticleController constructor.
     * @param ArticleContract $contract
     * @param MedicalSpecialityContract $medicalSpecialityContract
     */
    public function __construct(ArticleContract $contract, MedicalSpecialityContract $medicalSpecialityContract, ArticleNotificationService $articleNotificationService)
    {
        parent::__construct($contract, 'dashboard');
        $this->medicalSpecialityContract = $medicalSpecialityContract;
        $this->articleNotificationService = $articleNotificationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        $resources = $this->contract->search($request->all(), ['author', 'likes', 'complaints']);
        return $this->indexBlade(['resources' => $resources]);
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        $specialities = $this->medicalSpecialityContract->search(['active' => true], [], ['limit' => 0, 'page' => 0]);
        return $this->createBlade(['specialities' => $specialities]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ArticleRequest $request
     *
     * @return RedirectResponse
     */
    public function store(ArticleRequest $request): RedirectResponse
    {
        $this->contract->create($request->validated());
        return $this->redirectToIndex()->with('success', __('messages.actions_messages.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param Article $article
     *
     * @return View|Factory|Application
     */
    public function show(Article $article): View|Factory|Application
    {
        $article->load('complaints', 'likes', 'author', 'publisher', 'medicalSpeciality', 'images', 'mainImage');
        return $this->showBlade(['article' => $article]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Article $article
     *
     * @return View|Factory|Application
     */
    public function edit(Article $article): View|Factory|Application
    {
        $specialities = $this->medicalSpecialityContract->search(['active' => true], [], ['limit' => 0, 'page' => 0]);
        return $this->editBlade(['article' => $article, 'specialities' => $specialities]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ArticleRequest $request
     * @param Article $article
     *
     * @return RedirectResponse
     */
    public function update(ArticleRequest $request, Article $article): RedirectResponse
    {
        $this->contract->update($article, $request->validated());
        return $this->redirectToIndex()->with('success', __('messages.actions_messages.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Article $article
     *
     * @return RedirectResponse
     */
    public function destroy(Article $article): RedirectResponse
    {
       $this->contract->remove($article);
       return $this->redirectBack()->with('success', __('messages.actions_messages.delete_success'));
    }

    /**
     * active & inactive the specified resource from storage.
     * @param Article $article
     * @return RedirectResponse
     */
    public function changeActivation(Article $article): RedirectResponse
    {
        $this->contract->toggleField($article, 'is_active');
        return $this->redirectBack()->with('success', __('messages.actions_messages.update_success'));
    }

    public function publish($id): RedirectResponse
    {
        $article = Article::query()->find($id);

        if ($article['publish_date'] == null) {
            $article['publish_date'] = now();
            $article['publisher_id'] = auth()->id();
        } else {
            $article['publish_date'] = null;
        }
        $article->save();

        $this->articleNotificationService->newArticle($article);
        return $this->redirectBack()->with('success', __('messages.actions_messages.update_success'));
    }
}
