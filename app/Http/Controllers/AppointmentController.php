<?php

namespace App\Http\Controllers;

use App\Constants\CacheConstant;
use App\Http\Requests\AppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Library\Response;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments =cache()->remember(CacheConstant::APPOINTMENT,CacheConstant::ONE_DAY,function (){
           return Appointment::with(['advisor', 'patient'])->get();
        });
        return Response::Success(AppointmentResource::collection($appointments));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AppointmentRequest $request)
    {
        $this->authorize('create', Appointment::class);
        $patient = Auth::user();
        $appointment = $this->appointmentService->processAppointment($request, $patient);
        $advisor = $appointment->advisor;
        $this->appointmentService->sendMails($advisor, $patient, $appointment);
        return Response::Success(new AppointmentResource($appointment));
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        return Response::Success(new AppointmentResource($appointment));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        $patient = Auth::user();
        $appointment = $this->appointmentService->processAppointment($request, $patient, $appointment);
        $advisor = $appointment->advisor;
        $this->appointmentService->sendMails($advisor, $patient, $appointment);
        return Response::Success(new AppointmentResource($appointment));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
    }
}
