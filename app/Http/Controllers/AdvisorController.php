<?php

namespace App\Http\Controllers;

use App\Constants\CacheConstant;
use App\Http\Requests\AdvisorUpdateRequest;
use App\Http\Resources\AdvisorResource;
use App\Library\Response;
use App\Models\Advisor;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AdvisorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $advisors = cache()->remember(CacheConstant::ADVISOR, CacheConstant::ONE_DAY, function () {
            return Advisor::with(['clinics', 'appointments'])->get();
        });
        return Response::Success(AdvisorResource::collection($advisors->load(['clinics', 'appointments'])));
    }

    /**
     * Display the specified resource.
     */
    public function show(Advisor $advisor)
    {
        return Response::Success(new AdvisorResource($advisor));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdvisorUpdateRequest $request, Advisor $advisor)
    {
        try {
            DB::beginTransaction();
            $advisor->update(
                Arr::except($request->validated(), 'clinic'),
            );
            $advisor->clinics()->sync($request->clinic);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return Response::Error($exception->getMessage(), 422);
        }
        return Response::Success(new AdvisorResource($advisor));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Advisor $advisor)
    {
        try {
            DB::beginTransaction();
            $advisor->delete();
            $clinic = $advisor->clinics;
            $advisor->clinics()->detach($clinic);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return Response::Error($exception->getMessage(), 422);
        }
        return Response::Success();
    }
}
