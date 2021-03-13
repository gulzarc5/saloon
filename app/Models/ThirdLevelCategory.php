<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThirdLevelCategory extends Model
{
    protected $table = 'third_level_categories';
    protected $fillable = [
        'top_category_id','sub_category_id', 'third_level_category_name', 'image','status' 
    ];

    /**
     * Get the user that owns the ThirdLevelCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }
}
