<?php

namespace App\Services;

use App\Library\Response;
use App\Mail\AdvisorMail;
use App\Mail\PatientMail;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AppointmentService
{
    /**
     * Process appointment for create or update.
     * @return $appointment
     */
    public function processAppointment($request, $patient, $appointment = null)
    {
        try {
            $data=$request->validated();
            DB::beginTransaction();
            $reservedDate = $this->ReservedDate($data['advisor_id'], $data['datetime']);
            if ($reservedDate) {
                return Response::Error('please change advisor or date');
            }
            if ($appointment) {
                $appointment->update($data);
            } else {
//                dd($patient);
                $appointment = $patient->appointments()->create($data);
            }
            DB::commit();
            return $appointment;
        } catch (\Exception $exception) {
            DB::rollBack();
            return Response::Error("please change advisor or date." . $exception->getMessage(), 422);
        }
    }

    /**
     * Send appointment with mail to advisor & patient.
     */
    public function sendMails($advisor, $patient, $appointment)
    {
        Mail::send(new AdvisorMail($advisor, $appointment));
        Mail::send(new PatientMail($patient, $appointment));
    }

    /**
     * Find reserved date.
     */
    private function ReservedDate($advisorId, $datetime)
    {
        return Appointment::where('advisor_id', $advisorId)
            ->where('datetime', $datetime)
            ->first();
    }
}
