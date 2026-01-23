<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Thing extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'warranty', 'master_id'];

    protected $casts = [
        'warranty' => 'datetime',
    ];

    public function master()
    {
        return $this->belongsTo(User::class, 'master_id');
    }

    public function usages()
    {
        return $this->hasMany(Usage::class);
    }

    public function descriptions()
    {
        return $this->hasMany(ThingDescription::class);
    }

    public function activeDescription()
    {
        return $this->hasOne(ThingDescription::class)->where('is_active', true);
    }
}
