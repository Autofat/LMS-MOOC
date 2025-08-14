<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the category that owns this sub category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the materials for this sub category.
     */
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    /**
     * Get count of materials in this sub category.
     */
    public function getMaterialsCountAttribute()
    {
        return $this->materials()->count();
    }

    /**
     * Get count of questions in this sub category.
     */
    public function getQuestionsCountAttribute()
    {
        return $this->materials()->withCount('questions')->get()->sum('questions_count');
    }
}
