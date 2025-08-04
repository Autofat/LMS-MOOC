<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'material_id',
        'question',
        'option_A',
        'option_B',
        'option_C',
        'option_D',
        'option_E',
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
        
        if (!empty($this->option_A)) $options['A'] = $this->option_A;
        if (!empty($this->option_B)) $options['B'] = $this->option_B;
        if (!empty($this->option_C)) $options['C'] = $this->option_C;
        if (!empty($this->option_D)) $options['D'] = $this->option_D;
        if (!empty($this->option_E)) $options['E'] = $this->option_E;
        
        return $options;
    }

    /**
     * Set options from array for backward compatibility
     */
    public function setOptionsAttribute($value)
    {
        if (is_array($value)) {
            $this->option_A = $value['A'] ?? null;
            $this->option_B = $value['B'] ?? null;
            $this->option_C = $value['C'] ?? null;
            $this->option_D = $value['D'] ?? null;
            $this->option_E = $value['E'] ?? null;
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
