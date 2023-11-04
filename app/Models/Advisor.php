<?php

namespace App\Models;

use App\Constants\CacheConstant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Advisor extends Authenticatable implements JWTSubject
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['first_name', 'last_name', 'email', 'password'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->password = Hash::make($model->password);
        });

        static::created(function () {
            cache()->forget(CacheConstant::ADVISOR);
        });
        static::updated(function () {
            cache()->forget(CacheConstant::ADVISOR);
        });
    }

    public function clinics()
    {
        return $this->belongsToMany(Clinic::class, 'advisor_clinic');
    }

    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'advisor_patient');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
