<?php

namespace App\Mail;

use App\Models\Advisor;
use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdvisorMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $advisor;
    protected $appointment;

    public function __construct(Advisor $advisor, Appointment $appointment)
    {
        $this->advisor = $advisor;
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
            ->to($this->advisor->email);
    }
}
