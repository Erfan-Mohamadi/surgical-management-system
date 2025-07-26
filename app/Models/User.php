<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Symfony\Component\HttpKernel\Profiler\Profile;

class User extends Authenticatable
{
    /*------------------------------------
    | Traits
    |-----------------------------------*/

    use HasFactory, LogsActivity, Notifiable;

    /*------------------------------------
    | Model Configuration
    |-----------------------------------*/

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*------------------------------------
    | Activity Log Configuration
    |-----------------------------------*/

    protected static $logAttributes = ['name', 'email', 'password'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['number', 'name'])  // Fields to log
            ->logOnlyDirty()               // Log only changed values
            ->dontSubmitEmptyLogs();       // Avoid empty logs
    }

    /*------------------------------------
    | Relationships
    |-----------------------------------*/

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }


    /*------------------------------------
    | Authentication Methods
    |-----------------------------------*/

    public function findForPassport($username)
    {
        return $this->querywhere('mobile', $username)->first();
    }
}
