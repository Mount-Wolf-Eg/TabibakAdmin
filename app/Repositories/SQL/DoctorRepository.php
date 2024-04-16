<?php

namespace App\Repositories\SQL;

use App\Constants\FileConstants;
use App\Models\Doctor;
use App\Repositories\Contracts\DoctorContract;
use App\Repositories\Contracts\FileContract;
use App\Repositories\Contracts\UserContract;

class DoctorRepository extends BaseRepository implements DoctorContract
{
    /**
     * DoctorRepository constructor.
     * @param Doctor $model
     */
    public function __construct(Doctor $model)
    {
        parent::__construct($model);
    }

    public function beforeCreate($attributes)
    {
        return resolve(UserContract::class)->prepareUserForRoleUsers($attributes);
    }

    public function beforeUpdate($attributes)
    {
        return resolve(UserContract::class)->prepareUserForRoleUsers($attributes);
    }

    public function syncRelations($model, $attributes)
    {
        if (isset($attributes['specialities'])) {
            $model->medicalSpecialities()->sync($attributes['specialities']);
        }
        self::syncAttachments($model, $attributes);
        if (isset($attributes['schedule_days'])) {
            foreach ($attributes['schedule_days'] as $day) {
                $day['doctor_id'] = $model->id;
                resolve(DoctorScheduleDayRepository::class)->create($day);
            }
        }
        if (isset($attributes['role'])) {
            $model->user->assignRole($attributes['role']);
        }
        if (isset($attributes['universities'])) {
            foreach ($attributes['universities'] as $university) {
                $data = collect($university)->except(['certificate'])->toArray();
                $universityModel = $model->universities()->updateOrCreate($data);
                if (isset($university['certificate'])) {
                    $file = resolve(FileContract::class)->find($university['certificate']);
                    $universityModel->certificate()->save($file);
                }
            }
        }
        if (isset($attributes['hospitals'])) {
            $model->hospitals()->sync($attributes['hospitals']);
        }
        return $model;
    }

    public static function syncAttachments($model, $attributes)
    {
        if (isset($attributes['attachments'])) {
            if(is_file($attributes['attachments'][0])){
                $attachments = collect($attributes['attachments'])->map(function ($attachment) {
                    return ['file' => $attachment, 'type' => FileConstants::FILE_TYPE_DOCTOR_ATTACHMENTS->value];
                })->toArray();
                $files = resolve(FileContract::class)->createMany($attachments);
            }else{
                $files = resolve(FileContract::class)->findIds($attributes['attachments']);
            }
            foreach ($files as $file)
                $model->attachments()->save($file);
        }
        return $model;
    }
}
