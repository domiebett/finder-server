<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemFile extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = "item_files";

    /**
     * Attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        "name", "identity", "url", "item"
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item() {
        return $this->belongsTo("App\Models\LostItem", "item");
    }

}
