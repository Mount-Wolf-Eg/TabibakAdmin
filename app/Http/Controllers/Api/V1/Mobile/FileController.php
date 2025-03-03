<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\FileRequest;
use App\Http\Resources\FileResource;
use App\Models\{Consultation, File};
use App\Repositories\Contracts\FileContract;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Constants\FileConstants;

/**
 * @group files
 */
class FileController extends BaseApiController
{
    /**
     * UserController constructor.
     * @param FileContract $contract
     */
    public function __construct(FileContract $contract)
    {
        parent::__construct($contract, FileResource::class);
    }

    public function consultationFiles()
    {
        // $patient_id = request()->patient_id ?? auth()->user()->patient?->id;
        $patient_id = request()->patient_id ?? auth()->id();

        $files = File::where(function ($query) use($patient_id) {
            $query->when(! request()->consultation_id, function ($query) use($patient_id) {
                $query->where('user_id', $patient_id)
                    ->orWhereHasMorph('fileable', [Consultation::class], function ($query) use($patient_id) {
                        // $query->where('patient_id', $patient_id);
                        $query->whereHas('patient', function ($query) use($patient_id) {
                            $query->where('user_id', $patient_id);
                        });
                    });
            })
            ->when(request()->consultation_id, function ($query) {
                $query->whereHasMorph('fileable', 'ConsultationVendor', function ($query) {
                    $query->whereHas('consultation', function ($query) {
                        $query->where('consultations.id', request()->consultation_id);
                    });
                })
                ->orWhereHasMorph('fileable', [Consultation::class], function ($query) {
                    $query->where('consultations.id', request()->consultation_id);
                });
            });
        })->whereIn('type', [
            FileConstants::FILE_TYPE_CONSULTATION_ATTACHMENTS,
            FileConstants::FILE_CONSULTATION_REFERRAL
        ])->get();

        return FileResource::collection($files)->additional(['status' => 200]);
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
     * @return JsonResponse
     *
     * @unauthenticated
     */
    public function store(FileRequest $request): JsonResponse
    {
        try {
            $file = $this->contract->create($request->validated());
            $file->load('user');
            return $this->respondWithModel($file);
        }catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Delete File
     *
     * @param File $file
     * @return JsonResponse
     *
     * @unauthenticated
     */
    public function destroy(File $file): JsonResponse
    {
        try{
            $this->contract->remove($file);
            return $this->respondWithSuccess(__('Deleted Successfully'));
        }catch(Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

}
