<?php

namespace App\Http\Controllers;

use App\Constants\RoleConstant;
use App\Http\Requests\AuthRequest;
use App\Library\Response;
use App\Models\Advisor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
    /**
     * Handle an incoming registration request.
     * @return Response
     */
    public function register(AuthRequest $request)
    {
        $data = Arr::except($request->validated(), ['role', 'clinic', 'advisor']);
        if ($request->role === RoleConstant::PATIENT) {
            $patient = Patient::create($data);
            $patient->advisors()->attach($request->advisor);
        } else {
            try {
                $advisor = Advisor::create($data);
                $advisor->clinics()->attach($request->clinic);
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                return Response::Error($exception->getMessage(), 422);
            }
        }
        return Response::Success("Successfully created", 201);
    }

    /**
     * Handle an authentication attempt.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ($token = Auth::guard('advisor')->attempt($credentials)) {
            return Response::Success('advisor Successfully login', 200)->header('token', $token);
        } elseif ($token = Auth::guard('patient')->attempt($credentials)) {
            return Response::Success('patient Successfully login', 200)->header('token', $token);
        } else {
            return Response::Error('password or email is incorrect', 401);
        }
    }

    /**
     * Log the user out of the application.
     */
    public function logout()
    {
        return Response::Success('Logged out Successfully.', 200);
    }
}
