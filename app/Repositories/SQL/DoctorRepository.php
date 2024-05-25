<?php

namespace App\Repositories\SQL;

use App\Constants\FileConstants;
use App\Models\Doctor;
use App\Repositories\Contracts\DoctorContract;
use App\Repositories\Contracts\FileContract;
use App\Repositories\Contracts\UserContract;
use function Laravel\Prompts\select;

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
        if (isset($attributes['attachments'])) {
            self::syncAttachments($model, $attributes);
        }
        if (isset($attributes['schedule_days'])) {
            self::syncScheduleDays($model, $attributes);
        }
        if (isset($attributes['role'])) {
            $model->user->assignRole($attributes['role']);
        }
        if (isset($attributes['universities'])) {
            self::syncUniversities($model, $attributes);
        }
        if (isset($attributes['hospitals'])) {
            $model->hospitals()->sync($attributes['hospitals']);
        }
        return $model;
    }

    public static function syncAttachments($model, $attributes)
    {
        if (is_file($attributes['attachments'][0])) {
            $attachments = collect($attributes['attachments'])->map(function ($attachment) {
                return ['file' => $attachment, 'type' => FileConstants::FILE_TYPE_DOCTOR_ATTACHMENTS->value];
            })->toArray();
            $files = resolve(FileContract::class)->createMany($attachments);
        } else {
            $files = resolve(FileContract::class)->findIds($attributes['attachments']);
        }
        foreach ($files as $file)
            $model->attachments()->save($file);
        return $model;
    }

    public static function syncScheduleDays($model, $attributes)
    {
        foreach ($attributes['schedule_days'] as $day) {
            $day['doctor_id'] = $model->id;
            $scheduleDay = resolve(DoctorScheduleDayRepository::class)->findBy('date', $day['date'], false);
            if ($scheduleDay)
                resolve(DoctorScheduleDayRepository::class)->update($scheduleDay, $day);
            else
                resolve(DoctorScheduleDayRepository::class)->create($day);
        }
        return $model;
    }

    public static function syncUniversities($model, $attributes)
    {
        foreach ($attributes['universities'] as $university) {
            $data = collect($university)->except(['certificate'])->toArray();
            if (!empty($data['doctor_university_id'])){
                $universityData = \Arr::except($data, 'doctor_university_id');
                $universityModel = $model->universities()->where('id', $data['doctor_university_id'])->first();
                $universityModel->update($universityData);
            }else{
                $universityModel = $model->universities()->updateOrCreate($data);
            }
            if (!empty($university['certificate'])) {
                $file = resolve(FileContract::class)->find($university['certificate']);
                $universityModel->certificate()->save($file);
            }
        }
        return $model;
    }
}
