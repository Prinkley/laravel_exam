<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThingDescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'thing_id',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function thing()
    {
        return $this->belongsTo(Thing::class);
    }
}
