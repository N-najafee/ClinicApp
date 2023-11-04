<?php

namespace App\Models;

use App\Constants\CacheConstant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Patient extends Authenticatable implements JWTSubject
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
            cache()->forget(CacheConstant::PATIENT);
        });
        static::updated(function () {
            cache()->forget(CacheConstant::PATIENT);
        });
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function advisors()
    {
        return $this->belongsToMany(Advisor::class, 'advisor_patient');
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
