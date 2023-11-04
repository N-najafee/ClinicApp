<?php

namespace App\Http\Controllers;

use App\Constants\CacheConstant;
use App\Http\Requests\ClinicStoreRequest;
use App\Http\Requests\ClinicUpdateRequest;
use App\Http\Resources\ClinicResource;
use App\Library\Response;
use App\Models\Advisor;
use App\Models\Clinic;
use Illuminate\Support\Facades\DB;

class ClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clinics = cache()->remember(CacheConstant::CLINIC, CacheConstant::ONE_DAY, function () {
            return Clinic::with('advisors')->get();
        });
        return Response::Success(ClinicResource::collection($clinics->load('advisors')));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClinicStoreRequest $request)
    {
        $clinic = Clinic::create($request->validated());
        return Response::Success(new clinicResource($clinic));
    }

    /**
     * Display the specified resource.
     */
    public function show(Clinic $clinic)
    {
        return Response::Success(new clinicResource($clinic));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClinicUpdateRequest $request, Clinic $clinic)
    {
        $clinic->update($request->validated());
        return Response::Success(new clinicResource($clinic));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clinic $clinic)
    {
        try {
            DB::beginTransaction();
            $clinic->delete();
            $advisor = $clinic->advisors;
            $clinic->advisors()->detach($advisor);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return Response::Error($exception->getMessage());
        }
        return Response::Success();
    }

    function report()
    {
        $data =

        $advisors = Advisor::withCount(['appointments', 'patients'])->get();
//        $patients=Patient::withCount('appointments')->get();
//        $clinicsAdvisor=Clinic::withCount('advisors')->get();
//        $clinicsPatient=Clinic::withCount('patients')->get();
//        $clinicsappointment=Clinic::withCount('appointments')->get();
        foreach ($advisors as $advisor) {
            dd($advisor->appointments_count);
            $countAD = $advisor->advisor_count;
        }
    }
}
