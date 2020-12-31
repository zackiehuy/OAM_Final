<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */





    protected $dates = ['deleted_at'];


    protected $fillable  = [

        'name',
        'email',
        'location_id',
        'password',
        'staff_code',
        'joined_date',
        'date_of_birth',
        'is_admin',
        'gender',
        'type',
        'is_admin',
    ];
    public $withoutUpdatedAt = true;
    protected $guarded = [


    ];
    public function userLocation()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function scopeName($query, $request)
    {
        if ($request->has('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }
        return $query;
    }
    public function scopeStaffCode($query, $request)
    {
        if ($request->has('staff_code')) {
            $query->where('staff_code', 'LIKE', '%' . $request->staff_code . '%');
        }
        return $query;
    }
    public function scopeEmail($query, $request)
    {
        if ($request->has('email')) {
            $query->where('email', 'LIKE', '%' . $request->email . '%');
        }
        return $query;
    }
    public function scopeLocation($query, $request)
    {
        if ($request->has('location_id')) {
            $query->where('location_id', '=', $request->location_id);
        }
        return $query;
    }
    public function scopeDateOfBirth($query, $request)
    {
        if ($request->has('date_of_birth')) {
            $query->where('date_of_birth', 'LIKE', '%' . $request->date_of_birth . '%');
        }
        return $query;
    }
    public function scopeJoinedDate($query, $request)
    {
        if ($request->has('joined_date')) {
            $query->where('joined_date', 'LIKE', '%' . $request->joined_date . '%');
        }
        return $query;
    }
    public function scopeGender($query, $request)
    {
        if ($request->has('gender')) {
            $query->where('gender', '=', $request->gender);
        }
        return $query;
    }
    public function scopeType($query, $request)
    {
        if ($request->has('type')) {
            $query->where('is_admin', 'LIKE', '%' . $request->type . '%');
        }
        return $query;
    }
    public function scopeCreatedBy($query, $request)
    {
        if ($request->has('created_by')) {
            $query->where('created_by', 'LIKE', '%' . $request->created_by . '%');
        }
        return $query;
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public const ADMIN = '1';
    public const STAFF = '0';

    public static function isAdmin()
    {
        return (Auth::user()->is_admin == self::ADMIN);
    }

    public static function isStaff()
    {
        return (Auth::user()->is_admin == self::STAFF);
    }

    public function assignment()
    {
        return $this->hasMany('App\Models\Assignment', 'user_id');
    }
    public function request()
    {
            return $this->hasMany('App\Models\Requests', 'user_id');
    }
}
