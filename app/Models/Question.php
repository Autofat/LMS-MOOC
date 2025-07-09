<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'material_id',
        'question',
        'options',
        'answer',
        'explanation',
        'difficulty'
    ];

    protected $casts = [
        'options' => 'array',
    ];

    /**
     * Set difficulty to lowercase
     */
    public function setDifficultyAttribute($value)
    {
        $this->attributes['difficulty'] = $value ? strtolower($value) : null;
    }

    /**
     * Get difficulty with proper case
     */
    public function getDifficultyAttribute($value)
    {
        if (!$value) return null;
        
        // Capitalize first letter for display
        return ucfirst($value);
    }

    /**
     * Get the material that owns this question.
     */
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
