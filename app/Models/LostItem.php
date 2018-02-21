<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class LostItem extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The name of the models table
     *
     * @var string
     */
    protected $table = "lost_items";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'category', 'finder', 'owner', 'found', 'description'
    ];

    /**
     * Validation rules for adding a lost item
     *
     * @var array
     */
    public static $addItemRules = [
        "name" => "required|string",
        "description" => "required|string",
        "reporter" => "required|string",
        "category" => "required"
    ];

    /**
     * Build query and filter lost items to return only those
     * required
     *
     * @param $params - filter parameters
     *
     * @return $lostItems - filtered items
     */
    public static function buildItemsQuery($params)
    {
        $lostItems = LostItem::when(
            isset($params["searchQuery"]),
            function($query) use ($params) {
                $searchQuery = $params["searchQuery"];
                return $query->where("name", "iLIKE", "%".$searchQuery."%");
            }
        )->when(
            isset($params["categories"]),
            function($query) use ($params) {
                $categories = $params["categories"];
                return $query->whereHas("category",
                    function($query) use ($categories) {
                        $query->whereIn("name", $categories);
                    }
                );
            }
        )->when(
            isset($params["reporter"]),
            function($query) use ($params) {
                $reporter = $params["reporter"];
                return $query->where("found", false)->whereNotNull($reporter);
            }
        );

        return $lostItems;
    }

    /**
     * Get the owner who lost this item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo("App\Models\User", "owner");
    }

    /**
     * Get the user who found this item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function finder()
    {
        return $this->belongsTo("App\Models\User", "finder");
    }

    /**
     * Get the category of the item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo("App\Models\Category", "category");
    }
}
