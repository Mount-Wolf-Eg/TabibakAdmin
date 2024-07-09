<?php

namespace App\Http\Controllers\Dashboard;

use App\Repositories\Contracts\ContactContract;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseWebController;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ContactController extends BaseWebController
{

    /**
     * ContactController constructor.
     * @param ContactContract $contract
     */
    public function __construct(ContactContract $contract)
    {
        parent::__construct($contract, 'dashboard', false);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        $resources = $this->contract->search($request->all(), ['user']);
        return $this->indexBlade(['resources' => $resources]);
    }

}
