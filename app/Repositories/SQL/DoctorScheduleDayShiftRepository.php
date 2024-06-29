<?php

namespace App\Repositories\SQL;

use App\Models\DoctorScheduleDayShift;
use App\Repositories\Contracts\DoctorScheduleDayShiftContract;

class DoctorScheduleDayShiftRepository extends BaseRepository implements DoctorScheduleDayShiftContract
{
    /**
     * DoctorScheduleDayShiftRepository constructor.
     * @param DoctorScheduleDayShift $model
     */
    public function __construct(DoctorScheduleDayShift $model)
    {
        parent::__construct($model);
    }

    public function syncRelations($model, $attributes): void
    {
        $parentShift = $model;
        $day = $model->day;
        $period = $day->doctor->consultation_period;
        $separator = $parentShift->to_time->diffInMinutes($parentShift->from_time) / $period;
        $slots = [];
        for ($i = 0; $i < $separator; $i++) {
            $slots[] = [
                'from_time' => $parentShift->from_time->addMinutes($period * $i),
                'to_time' => $parentShift->from_time->addMinutes($period * ($i + 1)),
                'parent_id' => $parentShift->id,
                'doctor_schedule_day_id' => $day->id
            ];
        }
        $parentShift->slots()->delete();
        $parentShift->slots()->createMany($slots);
    }
}
