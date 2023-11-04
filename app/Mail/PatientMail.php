<?php

namespace App\Mail;

use App\Models\Advisor;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PatientMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $patient;
    protected $appointment;

    public function __construct(Patient $patient, Appointment $appointment)
    {
        $this->patient = $patient;
        $this->appointment = $appointment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.AppointmentDate',['appointment'=>$this->appointment])
            ->subject('Appointment date')
            ->to($this->patient->email);
    }
}
