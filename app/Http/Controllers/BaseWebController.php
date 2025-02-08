<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\BaseContract;
use Illuminate\Support\Str;

class BaseWebController extends Controller
{
    protected string $routeName, $viewPath;
    protected BaseContract $contract;

    public function __construct(BaseContract $contract, $viewPath, $modelName = null, $applyPermissionsName = null, bool $applyPermissions = true)
    {
        $modelName = $modelName ?? $contract->getModelName();
        $this->contract = $contract;
        $this->routeName = Str::kebab(Str::plural($modelName));
        $this->viewPath = $viewPath . '.' . $this->routeName;
        if ($applyPermissions) {
            $this->applyCrudPermissions($applyPermissionsName ?? $modelName);
        }
    }

    protected function indexBlade(array $with)
    {
        return view($this->viewPath . '.index', $with);
    }

    protected function createBlade(array $with = [])
    {
        return view($this->viewPath . '.create', $with);
    }

    protected function editBlade(array $with = [])
    {
        return view($this->viewPath . '.edit', $with);
    }

    protected function showBlade(array $with = [])
    {
        return view($this->viewPath . '.show', $with);
    }

    protected function customBlade(string $page, array $with = [])
    {
        return view($this->viewPath . '.' . $page, $with);
    }

    protected function loadPageAjax(array $with, $message = null, $page = '_table')
    {
        $data = view($this->viewPath . '.partials.' . $page, $with)->render();
        return response()->json([
            'data' => $data,
            'message' => $message,
        ]);
    }

    protected function redirectToIndex($params = [])
    {
        return redirect()->route($this->routeName . '.index', $params);
    }

    protected function redirectToCreate(array $arr = [])
    {
        return redirect()->route($this->routeName . '.create', $arr);
    }

    protected function redirectToShow(array $arr = [])
    {
        return redirect()->route($this->routeName . '.show', $arr);
    }

    protected function redirectToEdit(array $arr = [])
    {
        return redirect()->route($this->routeName . '.edit', $arr);
    }

    protected function redirectToCustomPage($page, $route = null, $data = [])
    {
        $route = $route ?? $this->routeName;
        return redirect()->route($route . '.' . $page, $data);
    }

    protected function redirectToTab($tabName,$params = [])
    {
        return redirect()->route($this->routeName . '.index','#'.$tabName)->with($params);
    }

    protected function redirectBack()
    {
        return redirect()->back();
    }

    protected function redirectBackWithInputs()
    {
        return redirect()->back()->withInput();
    }
}
