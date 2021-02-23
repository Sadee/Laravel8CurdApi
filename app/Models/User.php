<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
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
     * Get all of the models that can be.
     */
    public function userable()
    {
        return $this->morphTo();
    }

    /**
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        if($value != ""){
            $this->attributes['password'] = bcrypt($value);
        }
    }

    /**
     * Delete user with polymorphic relational data
     */
    public static function boot() {
        parent::boot();

        static::deleting(function($user) {
            // deleting polymorphic data
            $user->userable()->delete();
        });
    }
}
