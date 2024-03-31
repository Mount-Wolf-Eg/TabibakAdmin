<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Models\File;
use App\Repositories\Contracts\FileContract;
use Exception;
use Illuminate\Http\RedirectResponse;

/**
 * @group files
 */
class FileController extends Controller
{
    private FileContract $contract;

    /**
     * UserController constructor.
     * @param FileContract $contract
     */
    public function __construct(FileContract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * Upload new file
     *
     * @bodyParam file required The uploaded file.
     * @bodyParam type string required The file type. (meeting attachment -> request_meeting_attachment)
     * <p>Available types:</p>
     * <p><code>user_avatar => To upload user avatar</code></p>
     *
     * @param FileRequest $request
     * @return RedirectResponse
     *
     * @unauthenticated
     */
    public function store(FileRequest $request): RedirectResponse
    {
        try {
            $file = $this->contract->create($request->validated());
            $file->load('user');
            return redirect()->back()->with('message', __('messages.actions_messages.upload_success'));
        }catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete File
     *
     * @param File $file
     * @return RedirectResponse
     *
     * @unauthenticated
     */
    public function destroy(File $file): RedirectResponse
    {
        try{
            $this->contract->remove($file);
            return redirect()->back()->with('message', __('messages.actions_messages.delete_success'));
        }catch(Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

}
