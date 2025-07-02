<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
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
}
