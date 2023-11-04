<?php

namespace App\Policies;

use App\Models\Advisor;
use App\Models\Appointment;
use App\Models\Patient;

class AppointmentPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(Patient $patient): bool
    {
        return $patient instanceof Patient;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Patient $patient, Appointment $appointment): bool
    {

        return $patient instanceof Patient;
    }
}
