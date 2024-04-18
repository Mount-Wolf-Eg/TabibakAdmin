<?php

namespace App\Repositories\SQL;

use App\Models\DoctorScheduleDay;
use App\Repositories\Contracts\DoctorScheduleDayContract;

class DoctorScheduleDayRepository extends BaseRepository implements DoctorScheduleDayContract
{
    /**
     * DoctorScheduleDayRepository constructor.
     * @param DoctorScheduleDay $model
     */
    public function __construct(DoctorScheduleDay $model)
    {
        parent::__construct($model);
    }

    public function syncRelations($model, $attributes)
    {
        if (isset($attributes['shifts'])) {
            $model->scheduleDayShifts()->delete();
            foreach ($attributes['shifts'] as $shift) {
                $parentShift = $model->shifts()->create($shift);
                $period = $model->doctor->consultation_period;
                $separator = $parentShift->to_time->diffInMinutes($parentShift->from_time) / $period;
                $slots = [];
                for ($i = 0; $i < $separator; $i++) {
                    $slots[] = [
                        'from_time' => $parentShift->from_time->addMinutes($period * $i),
                        'to_time' => $parentShift->from_time->addMinutes($period * ($i + 1)),
                        'parent_id' => $parentShift->id,
                        'doctor_schedule_day_id' => $model->id
                    ];
                }
                $parentShift->slots()->createMany($slots);
            }
        }
        return $model;
    }
}
