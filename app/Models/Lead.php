<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Lead
 * @package App\Models
 */
class Lead extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'leads';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'name' => ""
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * @var array
     */
    protected $dates = [];

    /**
     * Get the user related to the account.
     */
    public function user()
    {
        return $this->morphOne('App\Models\User', 'userable');
    }

    /**
     * Delete user data as well
     */
    public static function boot() {
        parent::boot();

        static::deleting(function($lead) {
            // deleting polymorphic data
            $lead->user()->delete();
        });
    }
}
