<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use phpDocumentor\Reflection\Types\Integer;

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
     * Default date format
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Build query and filter lost items to return only those
     * required
     *
     * @param $params - filter parameters
     *
     * @return LostItem $lostItems - filtered items
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
     * Sets the reporter of a lost item.
     *
     * @param String $reporter
     * @param Integer $currentUserId
     */
    public function setReporter(String $reporter, $currentUserId) {
        switch ($reporter) {
            case "owner":
                $this->owner = $currentUserId;
                break;
            case "finder":
                $this->finder = $currentUserId;
        }
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

    public function files()
    {
        return $this->hasMany("App\Models\ItemFile", "item");
    }

    /**
     * Mutator to format the category value
     */
    public function getCategoryAttribute($value)
    {
        $category = Category::find($value)->name;
        return $category;
    }

    /**
     * Mutator to format the finder value
     */
    public function getFinderAttribute($value)
    {
        $finder = $value ?
            formatUser(User::find($value)) : null;
        return $finder;
    }

    /**
     * Mutator to format the owner value
     */
    public function getOwnerAttribute($value) {
        $owner = $value ?
            formatUser(User::find($value)) : null;
        return $owner;
    }
}
