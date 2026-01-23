<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usage extends Model
{
    use HasFactory;

    protected $fillable = ['thing_id', 'place_id', 'user_id', 'amount', 'dimension_id'];

    public function thing()
    {
        return $this->belongsTo(Thing::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dimension()
    {
        return $this->belongsTo(Dimension::class);
    }
}
