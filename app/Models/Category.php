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
     * Get the sub categories for this category.
     */
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    /**
     * Get the materials for this category.
     */
    public function materials()
    {
        return $this->hasMany(Material::class, 'category', 'name');
    }

    /**
     * Get count of materials in this category (through sub categories).
     */
    public function getMaterialsCountAttribute()
    {
        return $this->subCategories()->withCount('materials')->get()->sum('materials_count');
    }

    /**
     * Get count of questions in this category (through sub categories).
     */
    public function getQuestionsCountAttribute()
    {
        $totalQuestions = 0;
        foreach ($this->subCategories as $subCategory) {
            $totalQuestions += $subCategory->questions_count;
        }
        return $totalQuestions;
    }
}
