<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'material_id',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'option_e',
        'answer',
        'explanation',
        'difficulty'
    ];

    /**
     * Get options as array for backward compatibility
     */
    public function getOptionsAttribute()
    {
        $options = [];
        
        if (!empty($this->option_a)) $options['A'] = $this->option_a;
        if (!empty($this->option_b)) $options['B'] = $this->option_b;
        if (!empty($this->option_c)) $options['C'] = $this->option_c;
        if (!empty($this->option_d)) $options['D'] = $this->option_d;
        if (!empty($this->option_e)) $options['E'] = $this->option_e;
        
        return $options;
    }

    /**
     * Set options from array for backward compatibility
     */
    public function setOptionsAttribute($value)
    {
        if (is_array($value)) {
            $this->option_a = $value['A'] ?? null;
            $this->option_b = $value['B'] ?? null;
            $this->option_c = $value['C'] ?? null;
            $this->option_d = $value['D'] ?? null;
            $this->option_e = $value['E'] ?? null;
        }
    }

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
