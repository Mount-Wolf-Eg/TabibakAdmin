<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteRequest;
use App\Models\Note;
use App\Repositories\Contracts\NoteContract;
use Illuminate\Http\RedirectResponse;

class NoteController extends Controller
{
    private NoteContract $contract;

    /**
     * NoteController constructor.
     * @param NoteContract $contract
     */
    public function __construct(NoteContract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param NoteRequest $request
     * @return RedirectResponse
     */
    public function store(NoteRequest $request): RedirectResponse
    {
        $this->contract->create($request->validated());
        return redirect()->back()->with('success', __('messages.actions_messages.create_success'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param NoteRequest $request
     * @param Note $note
     * @return RedirectResponse
     */
    public function update(NoteRequest $request, Note $note): RedirectResponse
    {
        $this->contract->update($note, $request->validated());
        return redirect()->back()->with('success', __('messages.actions_messages.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Note $note
     * @return RedirectResponse
     */
    public function destroy(Note $note): RedirectResponse
    {
        $this->contract->remove($note);
        return redirect()->back()->with('success', __('messages.actions_messages.delete_success'));
    }
}
