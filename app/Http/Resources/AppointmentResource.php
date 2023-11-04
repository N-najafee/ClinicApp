<?php

namespace App\Http\Resources;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'datetime'=>$this->datetime,
            'advisor'=>new AdvisorResource($this->whenLoaded('advisor',function (){
                return $this->advisor->load('clinics');
            })),
            'patient'=>new PatientResource($this->whenLoaded('patient')),
        ];
    }
}
