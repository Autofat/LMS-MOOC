<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description', 
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the materials for this category.
     */
    public function materials()
    {
        return $this->hasMany(Material::class, 'category', 'name');
    }

    /**
     * Get count of materials in this category.
     */
    public function getMaterialsCountAttribute()
    {
        return $this->materials()->count();
    }

    /**
     * Get count of questions in this category.
     */
    public function getQuestionsCountAttribute()
    {
        return $this->materials()->withCount('questions')->get()->sum('questions_count');
    }
}
