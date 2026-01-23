<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArchivedThing extends Model
{
    use HasFactory;

    protected $table = 'archived_things';

    protected $fillable = [
        'thing_id',
        'name',
        'description',
        'warranty',
        'master_name',
        'master_id',
        'last_user_name',
        'last_user_id',
        'place_name',
        'place_id',
        'is_restored',
        'restored_by_id',
        'restored_at',
    ];

    protected $casts = [
        'is_restored' => 'boolean',
        'warranty' => 'datetime',
        'restored_at' => 'datetime',
    ];

    public function thing()
    {
        return $this->belongsTo(Thing::class);
    }

    public function master()
    {
        return $this->belongsTo(User::class, 'master_id');
    }

    public function lastUser()
    {
        return $this->belongsTo(User::class, 'last_user_id');
    }

    public function restoredBy()
    {
        return $this->belongsTo(User::class, 'restored_by_id');
    }
}
