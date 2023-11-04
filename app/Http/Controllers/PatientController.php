<?php

namespace App\Http\Controllers;

use App\Constants\CacheConstant;
use App\Http\Requests\PatientStoreRequest;
use App\Http\Requests\PatientUpdateRequest;
use App\Http\Resources\PatientResource;
use App\Library\Response;
use App\Models\Patient;
use Illuminate\Support\Arr;

class PatientController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = cache()->remember(CacheConstant::PATIENT, CacheConstant::ONE_DAY, function () {
            return Patient::with('appointments')->get();
        });
        return Response::Success(PatientResource::collection($patients->load('appointments')));
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return Response::Success(new PatientResource($patient));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PatientUpdateRequest $request, Patient $patient)
    {
        $data=Arr::except($request->validated(),'advisor');
        $patient->update($data);
        $patient->advisors()->sync($request->advisor);
        return Response::Success(new PatientResource($patient));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return Response::Success();
    }
}
