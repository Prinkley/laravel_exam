<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dimension extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'abbreviation'];

    public function usages()
    {
        return $this->hasMany(Usage::class);
    }
}
