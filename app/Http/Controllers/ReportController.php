<?php

namespace App\Http\Controllers;

use App\Library\Response;
use App\Models\Advisor;
use App\Models\Clinic;
use App\Models\Patient;

class ReportController extends Controller
{
    /**
     * count of advisor & appointment per clinic
     */
    public function clinic()
    {
        $clinics = Clinic::withCount('advisors')->get();
        $data = $clinics->map(function ($clinic) {
            $appointmentsCount = 0;
            if ($clinic->advisors_count > 0) {
                $appointmentsCount = count($clinic->advisors->first()->appointments);
            }
            return $clinic->id = [
                'advisorCount' => $clinic->advisors_count,
                'appointmentsCount' => $appointmentsCount,
            ];
        });

        return Response::Success($data);
    }

    /**
     * count of patient & appointment per advisor
     */
    public
    function advisor()
    {
        $advisors = Advisor::withCount(['appointments', 'patients'])->get();
        $data = [];
        foreach ($advisors as $advisor) {
            $data[$advisor->id] = [
                'patientsCount' => $advisor->patients_count,
                'appointmentsCount' => $advisor->appointments_count
            ];
        }
        return Response::Success($data);
    }

    /**
     * count of appointment per patient
     */
    public
    function patient()
    {
        $patients = Patient::withCount('appointments')->get();
        $data = $patients->map(function ($patient) {
            return $patient->id = ['appointmentsCount' => $patient->appointments_count];
        });
        return Response::Success($data);
    }
}
