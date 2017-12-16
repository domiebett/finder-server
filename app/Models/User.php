<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The name of the models table
     *
     * @var string
     */
    protected $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name', 'email', 'first_name', 'last_name', 'location', 'contact_info'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get all lost items the user found
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function foundItems() {
        return $this->hasMany("App\Models\LostItem", "finder");
    }

    /**
     * Get all lost items the user lost
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lostItems() {
        return $this->hasMany("App\Models\LostItem", "owner");
    }
}
