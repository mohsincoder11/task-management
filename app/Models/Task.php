<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'is_completed', 'position'];

    protected $casts = [
        'is_completed' => 'boolean',
    ];
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopeIncomplete($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position')->orderBy('created_at');
    }

}
