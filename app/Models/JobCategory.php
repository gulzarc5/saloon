<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    protected $table = 'job_categories';
    protected $fillable = ['name', 'image', 'status', 'man', 'woman', 'kids'];
    protected $primaryKey = 'id';

    public function subCategory()
    {
        return $this->hasMany('App\Models\SubCategory', 'category_id');
    }

    public function subCategoryWithStatus()
    {
        return $this->hasMany('App\Models\SubCategory', 'category_id')->where('status', 1);
    }
   
}
