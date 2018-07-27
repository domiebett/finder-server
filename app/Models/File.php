<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class File extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The name of the models table
     *
     * @var string
     */
    protected $table = "item_files";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'identity', 'url', 'item'
    ];

    /**
     * Validation rules for adding a lost item
     *
     * @var array
     */
    public static $addFileRules = [
        "name" => "required|string",
        "file" => "required"
    ];

    /**
     * Get the user who found this item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo("App\Models\LostItem", "item");
    }
}
