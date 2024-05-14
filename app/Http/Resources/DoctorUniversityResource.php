<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class DoctorUniversityResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request) : array
    {
        $this->micro = [
            'id' => $this->id,
        ];
        $this->relations = [
            'university' => $this->relationLoaded('university') ? new UniversityResource($this->university) : null,
            'academic_degree' => $this->relationLoaded('academicDegree') ? new AcademicDegreeResource($this->academicDegree) : null,
            'medical_specialty' => $this->relationLoaded('medicalSpeciality') ? new MedicalSpecialityResource($this->medicalSpeciality) : null,
            'certificate' => $this->relationLoaded('certificate') ? new FileResource($this->certificate) : null
        ];
        return $this->getResource();
    }

}
